<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentJamaah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokumenController extends Controller
{
    public function update(Request $request, DocumentJamaah $dokumen): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:verify,reject'],
            'alasan_penolakan' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['action'] === 'verify') {
            DB::transaction(function () use ($dokumen): void {
                $dokumen->update([
                    'status' => 'verify',
                    'alasan_penolakan' => null,
                    'verify_at' => now(),
                ]);

                $semuaDokumenSudahVerify = ! DocumentJamaah::query()
                    ->where('jamaah_id', $dokumen->jamaah_id)
                    ->where('status', '!=', 'verify')
                    ->exists();

                if ($semuaDokumenSudahVerify && $dokumen->jamaah) {
                    $dokumen->jamaah->update([
                        'status' => 'verify',
                    ]);
                }
            });

            return back()->with('success', 'Dokumen berhasil diverifikasi.');
        }

        // reject
        $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:1000'],
        ]);

        $dokumen->update([
            'status' => 'reject',
            'alasan_penolakan' => (string) $request->input('alasan_penolakan'),
            'verify_at' => now(),
        ]);

        return back()->with('success', 'Dokumen berhasil ditolak.');
    }
}
