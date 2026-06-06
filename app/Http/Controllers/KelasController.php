<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KelasBimbingan;
use App\Models\Order;
use App\Models\OrderBimbinganDetail;
use App\Models\OrderUmrohDetail;
use App\Models\Paket;
use App\Models\SesiBimbingan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelasList = KelasBimbingan::query()
            ->with(['paket:id,nama_paket'])
            ->withCount(['sesiBimbingans', 'orderBimbinganDetails'])
            ->latest()
            ->paginate(10);

        return view('admin.kelas.index', compact('kelasList'));
    } 

    public function create(): View
    {
        $pakets = Paket::query()
            ->whereIn('type', ['BIMBINGAN_HAJI', 'UMROH'])
            ->orderBy('nama_paket')
            ->get(['id', 'nama_paket', 'type']);

        return view('admin.kelas.create', compact('pakets'));
    }

    public function show(KelasBimbingan $kelas): View
    {
        $kelas->load([
            'paket:id,nama_paket',
            'sesiBimbingans' => fn ($q) => $q->orderBy('mulai_at')->orderBy('id'),
        ]);

        return view('admin.kelas.show', compact('kelas'));
    }

    public function showUser(Request $request, $id): View
    {
        $userId = $request->user()?->id;

        $order = Order::query()
            ->with([
                'paket:id,nama_paket,type',
                'orderBimbinganDetail.kelasBimbingan.paket:id,nama_paket',
                'orderBimbinganDetail.kelasBimbingan.sesiBimbingans' => fn ($q) => $q->orderBy('mulai_at')->orderBy('id'),
                'orderUmrohDetail.kelasBimbingan.paket:id,nama_paket',
                'orderUmrohDetail.kelasBimbingan.sesiBimbingans' => fn ($q) => $q->orderBy('mulai_at')->orderBy('id'),
            ])
            ->when(
                $userId,
                fn ($query) => $query->where('user_id', $userId),
                fn ($query) => $query->whereRaw('1=0')
            )
            ->whereKey($id)
            ->firstOrFail();

        $kelas = null;
        if ($order->paket->type === 'BIMBINGAN_HAJI' && $order->orderBimbinganDetail) {
            $kelas = $order->orderBimbinganDetail->kelasBimbingan;
        } elseif ($order->paket->type === 'UMROH' && $order->orderUmrohDetail) {
            $kelas = $order->orderUmrohDetail->kelasBimbingan;
        }

        return view('user.kelas.show', compact('order', 'kelas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'paket_id' => ['required', 'integer', 'exists:pakets,id'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'nama_kelas' => ['required', 'string', 'max:255'],
            'nama_pembimbing' => ['nullable', 'string', 'max:255'],
        ]);

        $tahun = (int) $validated['tahun'];
        $mulaiPeriode = Carbon::create($tahun - 1, 8, 1)->startOfDay();
        $selesaiPeriode = Carbon::create($tahun, 7, 31)->endOfDay();

        $result = DB::transaction(function () use ($validated, $mulaiPeriode, $selesaiPeriode): array {
            $paket = Paket::query()->findOrFail($validated['paket_id']);

            $kelas = KelasBimbingan::query()->create([
                'paket_id' => (int) $validated['paket_id'],
                'nama_kelas' => (string) $validated['nama_kelas'],
                'status' => 'draft',
                'mulai_periode' => $mulaiPeriode->toDateString(),
                'selesai_periode' => $selesaiPeriode->toDateString(),
                'nama_pembimbing' => $validated['nama_pembimbing'] ?? null,
            ]);

            // Buat sesi bimbingan 12x, jadwal 2x seminggu.
            // Pola sederhana: sesi 1 di awal minggu, sesi 2 tiga hari setelahnya.
            for ($i = 1; $i <= 12; $i++) {
                $weekIndex = intdiv($i - 1, 2);
                $inWeekIndex = ($i - 1) % 2; // 0 = sesi pertama minggu ini, 1 = sesi kedua minggu ini

                $mulaiAt = $mulaiPeriode
                    ->copy()
                    ->setTime(8, 0)
                    ->addWeeks($weekIndex)
                    ->addDays($inWeekIndex === 1 ? 3 : 0);

                $selesaiAt = $mulaiAt->copy()->addHours(2);

                SesiBimbingan::query()->create([
                    'kelas_bimbingan_id' => $kelas->id,
                    'judul' => 'Manasik ' . $i,
                    'mulai_at' => $mulaiAt,
                    'selesai_at' => $selesaiAt,
                    'lokasi' => null,
                    'keterangan' => null,
                ]);
            }

            // Assign order sesuai dengan tipe paket
            $assignedCount = 0;
            if ($paket->type === 'BIMBINGAN_HAJI') {
                // Assign order bimbingan yang belum punya kelas pada periode yang sama.
                $assignedCount = OrderBimbinganDetail::query()
                    ->whereNull('kelas_bimbingan_id')
                    ->whereHas('order', function ($q) use ($kelas, $mulaiPeriode, $selesaiPeriode) {
                        $q->where('paket_id', (int) $kelas->paket_id)
                            ->whereBetween('created_at', [$mulaiPeriode, $selesaiPeriode]);
                    })
                    ->update([
                        'kelas_bimbingan_id' => $kelas->id,
                    ]);
            } elseif ($paket->type === 'UMROH') {
                // Assign order umroh yang belum punya kelas pada periode yang sama.
                $assignedCount = OrderUmrohDetail::query()
                    ->whereNull('kelas_bimbingan_id')
                    ->whereHas('order', function ($q) use ($kelas, $mulaiPeriode, $selesaiPeriode) {
                        $q->where('paket_id', (int) $kelas->paket_id)
                            ->whereBetween('created_at', [$mulaiPeriode, $selesaiPeriode]);
                    })
                    ->update([
                        'kelas_bimbingan_id' => $kelas->id,
                    ]);
            }

            return [
                'kelas' => $kelas,
                'assignedCount' => (int) $assignedCount,
            ];
        });

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dibuat. Order ter-assign: ' . ($result['assignedCount'] ?? 0));
    }
}
