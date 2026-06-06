<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SesiBimbingan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SesiBimbinganController extends Controller
{
    public function edit(SesiBimbingan $sesi): View
    {
        $sesi->load([
            'kelasBimbingan:id,nama_kelas,paket_id',
            'kelasBimbingan.paket:id,nama_paket',
        ]);

        return view('admin.sesi_bimbingan.edit', compact('sesi'));
    }

    public function update(Request $request, SesiBimbingan $sesi): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'mulai_at' => ['nullable', 'date'],
            'selesai_at' => ['nullable', 'date', 'after_or_equal:mulai_at'],
            'lokasi' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $sesi->update([
            'judul' => (string) $validated['judul'],
            'mulai_at' => $validated['mulai_at'] ?? null,
            'selesai_at' => $validated['selesai_at'] ?? null,
            'lokasi' => $validated['lokasi'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.kelas.show', $sesi->kelas_bimbingan_id)
            ->with('success', 'Sesi berhasil diperbarui.');
    }
}
