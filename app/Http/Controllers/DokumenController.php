<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentJamaah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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


    public function updateUser(Request $request, $id)
    {
        // 1. Validasi file gambar (Maksimal 5MB)
        $request->validate([
            'dok' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png'],
        ]);

        try {
            // 2. Cari data dokumen berdasarkan ID
            $document = DocumentJamaah::findOrFail($id);

            // 3. Proses upload file baru
            if ($request->hasFile('dok')) {
                // (Opsional) Hapus file lama di storage agar tidak memenuhi server
                if ($document->dok && Storage::disk('public')->exists($document->dok)) {
                    Storage::disk('public')->delete($document->dok);
                }

                // Simpan file baru ke folder 'dokumen_jamaah' di disk public
                $filePath = $request->file('dok')->store('dokumen_jamaah', 'public');
            }

            // 4. Update data ke database
            $document->update([
                'dok' => $filePath ?? $document->dok,
                'status' => 'proses', // Ubah status kembali ke pending agar bisa dicek admin lagi
                'alasan_penolakan' => null // Hapus alasan penolakan yang lama
            ]);

            return redirect()->back()->with('success', 'Dokumen berhasil di-upload ulang dan sedang ditinjau.');

        } catch (\Exception $e) {
            Log::error('Gagal upload ulang dokumen: ' . $e->getMessage());
            
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }
}
