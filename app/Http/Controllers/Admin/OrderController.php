<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class OrderController extends Controller
{
    private const STATUS_OPTIONS = ['draft', 'pending', 'active', 'done', 'cancel'];
    private const PAKET_TYPE_OPTIONS = ['BIMBINGAN_HAJI', 'UMROH'];

    private const REQUIRED_DOKUMEN_JENIS = [
        'ktp',
        'kk',
        'surat_nikah',
        'foto',
        'passport',
    ];

    public function index(Request $request): View
    {
        $status = $request->string('status')->trim()->toString();
        if (!in_array($status, self::STATUS_OPTIONS, true)) {
            $status = '';
        }

        $tipePaket = $request->string('tipe_paket')->trim()->toString();
        if (!in_array($tipePaket, self::PAKET_TYPE_OPTIONS, true)) {
            $tipePaket = '';
        }

        $orders = Order::query()
            ->with([
                'user:id,name',
                'jamaah:id,user_id,nama_lengkap,nik,status',
                'paket:id,nama_paket,type,harga',
                'orderBimbinganDetail:order_id,nomor_porsi,kelas_bimbingan_id',
            ])
            ->when(
                $status !== '',
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                $tipePaket !== '',
                fn ($query) => $query->whereHas('paket', fn ($paketQuery) => $paketQuery->where('type', $tipePaket))
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $filters = [
            'status' => $status,
            'tipe_paket' => $tipePaket,
        ];

        return view('admin.order.index', [
            'orders' => $orders,
            'filters' => $filters,
            'statusOptions' => self::STATUS_OPTIONS,
            'paketTypeOptions' => self::PAKET_TYPE_OPTIONS,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load([
            'user:id,name,email',
            'jamaah',
            'jamaah.dokumenJamaahs',
            'paket',
            'orderBimbinganDetail',
            'orderUmrohDetail',
        ]);

        return view('admin.order.show', compact('order'));
    }

    public function showUser(Request $request, $id)
    {
        $userId = $request->user()?->id;

        $order = Order::query()
            ->with([
                'user:id,name,email',
                'jamaah',
                'jamaah.dokumenJamaahs',
                'paket',
                'orderBimbinganDetail',
                'orderUmrohDetail',
            ])
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->whereKey($id)
            ->firstOrFail();

        $dpAmount = $this->calculateDpAmount($order);
        $dokumenVerified = $this->isAllRequiredDokumenVerified($order);
        $dpAlreadyPaid = $dpAmount > 0 ? ((int) $order->total_dibayar >= $dpAmount) : false;
        $canPayDp = $dokumenVerified && !$dpAlreadyPaid && in_array((string) $order->status, ['draft', 'pending'], true);

        $midtransClientKey = (string) env('MIDTRANS_CLIENT_KEY', '');
        $midtransSnapUrl = (bool) env('MIDTRANS_IS_PRODUCTION', false)
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';


        return view('user.order.show', compact(
            'order',
            'dpAmount',
            'dokumenVerified',
            'dpAlreadyPaid',
            'canPayDp',
            'midtransClientKey',
            'midtransSnapUrl'
        ));
    }

    public function createDpSnapToken(Request $request, $id): JsonResponse
    {
        $userId = $request->user()?->id;

        $order = Order::query()
            ->with([
                'user:id,name,email',
                'jamaah',
                'jamaah.dokumenJamaahs',
                'paket',
            ])
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->whereKey($id)
            ->firstOrFail();

        if (!$this->isAllRequiredDokumenVerified($order)) {
            return response()->json([
                'message' => 'Dokumen belum lengkap / belum diverifikasi admin.',
            ], 422);
        }

        $dpAmount = $this->calculateDpAmount($order);
        if ($dpAmount <= 0) {
            return response()->json([
                'message' => 'DP tidak valid.',
            ], 422);
        }

        if ((int) $order->total_dibayar >= $dpAmount) {
            return response()->json([
                'message' => 'DP sudah terpenuhi.',
            ], 409);
        }

        $midtransOrderId = 'DP-' . $order->id . '-' . Str::uuid();

        $pembayaran = Pembayaran::query()->create([
            'order_id' => $order->id,
            'tipe' => 'dp',
            'tanggal_bayar' => null,
            'jumlah' => $dpAmount,
            'metode' => 'midtrans_snap',
            'status' => 'pending',
            'gateway' => 'midtrans',
            'midtrans_order_id' => $midtransOrderId,
        ]);

        $this->configureMidtrans();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $dpAmount,
            ],
            'customer_details' => [
                'first_name' => (string) ($order->user?->name ?? 'User'),
                'email' => (string) ($order->user?->email ?? ''),
            ],
            'item_details' => [
                [
                    'id' => 'DP-' . $order->id,
                    'price' => $dpAmount,
                    'quantity' => 1,
                    'name' => 'DP ' . (string) ($order->paket?->nama_paket ?? ('Order #' . $order->id)),
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $pembayaran->update([
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'dp_amount' => $dpAmount,
            'midtrans_order_id' => $midtransOrderId,
        ]);
    }

    public function markDpSuccess(Request $request, $id): JsonResponse
    {
        $userId = $request->user()?->id;

        $order = Order::query()
            ->with([
                'paket',
                'jamaah',
                'jamaah.dokumenJamaahs',
            ])
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->whereKey($id)
            ->firstOrFail();

        $payload = $request->all();
        $midtransOrderId = (string) ($payload['order_id'] ?? '');
        if ($midtransOrderId === '') {
            return response()->json(['message' => 'Payload invalid (missing order_id).'], 422);
        }

        $pembayaran = Pembayaran::query()
            ->where('order_id', $order->id)
            ->where('midtrans_order_id', $midtransOrderId)
            ->latest()
            ->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan.'], 404);
        }

        if ($pembayaran->status === 'verify') {
            return response()->json([
                'message' => 'Sudah terverifikasi.',
                'order_status' => (string) $order->status,
            ]);
        }

        $transactionTime = $payload['transaction_time'] ?? null;
        $paidAt = $transactionTime ? Carbon::parse($transactionTime) : now();

        $pembayaran->update([
            'status' => 'verify',
            'tanggal_bayar' => $paidAt,
            'payment_type' => $payload['payment_type'] ?? null,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'fraud_status' => $payload['fraud_status'] ?? null,
            'gateway_payload' => $payload,
        ]);

        $order->update([
            'total_dibayar' => ((int) $order->total_dibayar) + ((int) $pembayaran->jumlah),
        ]);

        $dpAmount = $this->calculateDpAmount($order);
        if ($dpAmount > 0 && (int) $order->total_dibayar >= $dpAmount) {
            if (in_array((string) $order->status, ['draft', 'pending'], true)) {
                $order->update(['status' => 'active']);
            }
        }

        return response()->json([
            'message' => 'Pembayaran DP berhasil dicatat.',
            'order_status' => (string) $order->status,
        ]);
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey = (string) env('MIDTRANS_SERVER_KEY', '');
        MidtransConfig::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        MidtransConfig::$isSanitized = (bool) env('MIDTRANS_IS_SANITIZED', true);
        MidtransConfig::$is3ds = (bool) env('MIDTRANS_IS_3DS', true);
    }

    private function calculateDpAmount(Order $order): int
    {
        $dp = (int) ($order->paket?->dp ?? 0);
        $minimum = (int) ($order->paket?->minimum_pembayaran ?? 0);

        return max($minimum, $dp);
    }

    private function isAllRequiredDokumenVerified(Order $order): bool
    {
        $dokumen = optional($order->jamaah)->dokumenJamaahs;
        if (!$dokumen) {
            return false;
        }

        $byJenis = $dokumen->keyBy(fn ($d) => strtolower((string) $d->jenis));
        foreach (self::REQUIRED_DOKUMEN_JENIS as $jenis) {
            $d = $byJenis->get($jenis);
            if (!$d || (string) $d->status !== 'verify') {
                return false;
            }
        }

        return true;
    }
}
