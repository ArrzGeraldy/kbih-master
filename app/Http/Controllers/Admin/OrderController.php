<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use App\Models\KelasBimbingan;
use App\Models\OrderBimbinganDetail;
use App\Models\OrderUmrohDetail;

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

        $totalTagihan = (int) ($order->total_tagihan ?? 0);
        $totalDibayar = (int) ($order->total_dibayar ?? 0);
        $sisaTagihan = max(0, $totalTagihan - $totalDibayar);

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
            'totalTagihan',
            'totalDibayar',
            'sisaTagihan',
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

        // reload order to get latest totals
        $order->refresh();

        $dpAmount = $this->calculateDpAmount($order);
        if ($dpAmount > 0 && (int) $order->total_dibayar >= $dpAmount) {
            if (in_array((string) $order->status, ['draft', 'pending'], true)) {
                $order->update(['status' => 'active']);
            }
        }

        // If order is fully paid after this pembayaran, set status done and assign kelas bimbingan
        if ((int) $order->total_dibayar === (int) $order->harga_snapshot) {
            $kelasBimbinganId = KelasBimbingan::query()
                ->where('paket_id', $order->paket_id)
                ->where('mulai_periode', '<=', Carbon::create(now()->year, 8, 1)->toDateString())
                ->orderBy('mulai_periode')
                ->value('id');

            if ($kelasBimbinganId) {
                $order->load(['paket', 'orderBimbinganDetail', 'orderUmrohDetail']);

                if (optional($order->paket)->type === 'BIMBINGAN_HAJI') {
                    if ($order->orderBimbinganDetail) {
                        $order->orderBimbinganDetail()->update(['kelas_bimbingan_id' => $kelasBimbinganId]);
                    }
                } elseif (optional($order->paket)->type === 'UMROH') {
                    if ($order->orderUmrohDetail) {
                        $order->orderUmrohDetail()->update(['kelas_bimbingan_id' => $kelasBimbinganId]);
                    } else {
                        OrderUmrohDetail::create([
                            'order_id' => $order->id,
                            'kelas_bimbingan_id' => $kelasBimbinganId,
                            'tanggal_keberangkatan' => null,
                        ]);
                    }
                }

                // mark order done if not already
                if ((string) $order->status !== 'done') {
                    $order->update(['status' => 'done']);
                }
            }
        }

        return response()->json([
            'message' => 'Pembayaran DP berhasil dicatat.',
            'order_status' => (string) $order->status,
        ]);
    }

    public function editBimbingan(Order $order): View
    {
        $order->load(['paket', 'orderBimbinganDetail']);

        $kelasBimbingans = KelasBimbingan::query()
            ->where('paket_id', $order->paket_id)
            ->orderBy('mulai_periode')
            ->get();

        return view('admin.order.edit-bimbingan', compact('order', 'kelasBimbingans'));
    }

    public function updateBimbingan(Request $request, Order $order): RedirectResponse
    {
        if (optional($order->paket)->type !== 'BIMBINGAN_HAJI') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order ini bukan paket Bimbingan Haji.');
        }

        $validated = $request->validate([
            'nomor_porsi' => ['required', 'string', 'max:255'],
            'kelas_bimbingan_id' => ['required', 'integer', 'exists:kelas_bimbingans,id'],
        ]);

        $kelasBimbingan = KelasBimbingan::query()
            ->where('paket_id', $order->paket_id)
            ->whereKey($validated['kelas_bimbingan_id'])
            ->first();

        if (! $kelasBimbingan) {
            return redirect()->back()->withInput()->withErrors(['kelas_bimbingan_id' => 'Kelas bimbingan tidak valid untuk paket ini.']);
        }

        $order->orderBimbinganDetail()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'nomor_porsi' => $validated['nomor_porsi'],
                'kelas_bimbingan_id' => $kelasBimbingan->id,
            ]
        );

        return redirect()->route('admin.orders.index', $order)
            ->with('success', 'Detail bimbingan berhasil diperbarui.');
    }

    public function editUmroh(Order $order): View
    {
        $order->load(['paket', 'orderUmrohDetail']);

        $kelasBimbingans = KelasBimbingan::query()
            ->where('paket_id', $order->paket_id)
            ->orderBy('mulai_periode')
            ->get();

        return view('admin.order.edit-umroh', compact('order', 'kelasBimbingans'));
    }

    public function updateUmroh(Request $request, Order $order): RedirectResponse
    {
        if (optional($order->paket)->type !== 'UMROH') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order ini bukan paket Umroh.');
        }

        $validated = $request->validate([
            'tanggal_keberangkatan' => ['nullable', 'date'],
            'kelas_bimbingan_id' => ['required', 'integer', 'exists:kelas_bimbingans,id'],
        ]);

        $kelasBimbingan = KelasBimbingan::query()
            ->where('paket_id', $order->paket_id)
            ->whereKey($validated['kelas_bimbingan_id'])
            ->first();

        if (! $kelasBimbingan) {
            return redirect()->back()->withInput()->withErrors(['kelas_bimbingan_id' => 'Kelas bimbingan tidak valid untuk paket ini.']);
        }

        $order->orderUmrohDetail()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'tanggal_keberangkatan' => $validated['tanggal_keberangkatan'],
                'kelas_bimbingan_id' => $kelasBimbingan->id,
            ]
        );

        return redirect()->route('admin.orders.index', $order)
            ->with('success', 'Detail Umroh berhasil diperbarui.');
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
