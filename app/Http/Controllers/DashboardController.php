<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Order;
use App\Models\Paket;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboardAdmin()
    {
        $orderTotalsByStatus = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($v) => (int) $v)
            ->all();

        $statusOptions = ['draft', 'pending', 'active', 'done', 'cancel'];
        $ordersByStatus = [];
        foreach ($statusOptions as $status) {
            $ordersByStatus[$status] = (int) ($orderTotalsByStatus[$status] ?? 0);
        }

        $totalOrders = array_sum($ordersByStatus);
        $totalJamaah = Jamaah::query()->count();
        $totalPakets = Paket::query()->count();
        $totalPembayaranVerified = (int) Pembayaran::query()->where('status', 'verify')->sum('jumlah');

        $recentOrders = Order::query()
            ->with([
                'jamaah:id,nama_lengkap,nik',
                'paket:id,nama_paket,type',
            ])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'ordersByStatus' => $ordersByStatus,
            'totalJamaah' => $totalJamaah,
            'totalPakets' => $totalPakets,
            'totalPembayaranVerified' => $totalPembayaranVerified,
            'recentOrders' => $recentOrders,
        ]);
    }
    public function dashboardUser(Request $request)
    {
        $userId = $request->user()?->id;

        $orders = Order::query()
            ->with([
                'paket:id,nama_paket,type',
                'jamaah:id,nama_lengkap',
            ])
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->latest()
            ->get();

        $totalOrders = $orders->count();
        $totalPaid = $orders->sum(fn ($order) => (int) $order->total_dibayar);
        $totalOutstanding = $orders->sum(fn ($order) => max(0, (int) $order->total_tagihan - (int) $order->total_dibayar));
        $activeOrders = $orders->where('status', 'active')->count();

        $paketRecommendations = Paket::query()
            ->latest()
            ->limit(3)
            ->get();

        return view('user.dashboard', compact(
            'orders',
            'totalOrders',
            'totalPaid',
            'totalOutstanding',
            'activeOrders',
            'paketRecommendations'
        ));
    }
}
