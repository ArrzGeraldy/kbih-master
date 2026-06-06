<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PembayaranController extends Controller
{

    public function showHistory(Request $request, $orderId)
    {
        $userId = $request->user()?->id;

        $order = Order::query()
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->whereKey($orderId)
            ->firstOrFail();

        $pembayarans = Pembayaran::query()
            ->where('order_id', $order->id)
            ->orderByDesc('tanggal_bayar')
            ->orderByDesc('id')
            ->get();

        return view('user.pembayaran.history', [
            'order' => $order,
            'pembayarans' => $pembayarans,
        ]);
    }

    public function createCicilanSnapToken(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        $amount = (int) $validated['amount'];
        $userId = $request->user()?->id;

        $order = Order::query()
            ->with(['user:id,name,email', 'paket:id,nama_paket'])
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->whereKey($id)
            ->firstOrFail();

        $dpVerified = Pembayaran::query()
            ->where('order_id', $order->id)
            ->where('tipe', 'dp')
            ->where('status', 'verify')
            ->exists();

        if (!$dpVerified) {
            return response()->json([
                'message' => 'DP belum terverifikasi.',
            ], 422);
        }

        $totalTagihan = (int) ($order->total_tagihan ?? 0);
        $totalDibayar = (int) ($order->total_dibayar ?? 0);
        $sisaTagihan = max(0, $totalTagihan - $totalDibayar);

        if ($sisaTagihan <= 0) {
            return response()->json([
                'message' => 'Tagihan sudah lunas.',
            ], 409);
        }

        if ($amount > $sisaTagihan) {
            return response()->json([
                'message' => 'Nominal melebihi sisa tagihan.',
            ], 422);
        }

        $midtransOrderId = 'CICILAN-' . $order->id . '-' . Str::uuid();

        $pembayaran = Pembayaran::query()->create([
            'order_id' => $order->id,
            'tipe' => 'cicilan',
            'tanggal_bayar' => null,
            'jumlah' => $amount,
            'metode' => 'midtrans_snap',
            'status' => 'pending',
            'gateway' => 'midtrans',
            'midtrans_order_id' => $midtransOrderId,
        ]);

        $this->configureMidtrans();

        $paketName = (string) ($order->paket?->nama_paket ?? ('Order #' . $order->id));

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => (string) ($order->user?->name ?? 'User'),
                'email' => (string) ($order->user?->email ?? ''),
            ],
            'item_details' => [
                [
                    'id' => 'CICILAN-' . $order->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Cicilan ' . $paketName,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $pembayaran->update([
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'amount' => $amount,
            'midtrans_order_id' => $midtransOrderId,
        ]);
    }

    public function markCicilanSuccess(Request $request, $id): JsonResponse
    {
        $userId = $request->user()?->id;

        $order = Order::query()
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

        if ((string) $pembayaran->tipe !== 'cicilan') {
            return response()->json(['message' => 'Tipe pembayaran tidak sesuai.'], 422);
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

        $newTotalDibayar = ((int) $order->total_dibayar) + ((int) $pembayaran->jumlah);
        $orderUpdate = [
            'total_dibayar' => $newTotalDibayar,
        ];

        if ($newTotalDibayar === (int) $order->harga_snapshot) {
            $orderUpdate['status'] = 'done';
        }

        $order->update($orderUpdate);

        return response()->json([
            'message' => 'Pembayaran cicilan berhasil dicatat.',
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
}
