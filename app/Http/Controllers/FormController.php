<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentJamaah;
use App\Models\Jamaah;
use App\Models\Order;
use App\Models\KelasBimbingan;
use App\Models\OrderBimbinganDetail;
use App\Models\OrderUmrohDetail;
use App\Models\Paket;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function formBimbinganCreate(Paket $paket)
    {
        abort_if($paket->type !== 'BIMBINGAN_HAJI', 404);

        return view('user.form-bimbingan', compact('paket'));
    }

    public function formBimbinganStore(Request $request, Paket $paket): RedirectResponse
    {
        abort_if($paket->type !== 'BIMBINGAN_HAJI', 404);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nik' => ['required', 'string', 'regex:/^\d{16}$/', 'unique:jamaahs,nik'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'no_tlpn' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],

            'nomor_porsi' => ['required', 'string', 'max:255'],

            'dok_ktp' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_kk' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_surat_nikah' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_foto' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_passport' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $userId = (int) $request->user()->id;

        DB::transaction(function () use ($validated, $request, $userId, $paket): void {
            $jamaah = Jamaah::create([
                'user_id' => $userId,
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'no_tlpn' => $validated['no_tlpn'],
                'alamat' => $validated['alamat'],
                'status' => 'pending',
            ]);

            $order = Order::create([
                'user_id' => $userId,
                'jamaah_id' => $jamaah->id,
                'paket_id' => $paket->id,
                'status' => 'pending',
                'harga_snapshot' => (int) $paket->harga,
                'total_tagihan' => (int) $paket->harga,
                'durasi_cicilan' => null,
                'total_dibayar' => 0,
            ]);

            // $kelasBimbinganId = KelasBimbingan::query()
            //     ->where('paket_id', $paket->id)
            //     ->where('mulai_periode', '<=', Carbon::create(now()->year, 8, 1)->toDateString())
            //     ->orderBy('mulai_periode')
            //     ->value('id');


            OrderBimbinganDetail::create([
                'order_id' => $order->id,
                'nomor_porsi' => $validated['nomor_porsi'],
                'kelas_bimbingan_id' => null,
            ]);

            $dokumenMap = [
                'ktp' => 'dok_ktp',
                'kk' => 'dok_kk',
                'surat_nikah' => 'dok_surat_nikah',
                'foto' => 'dok_foto',
                'passport' => 'dok_passport',
            ];

            foreach ($dokumenMap as $jenis => $inputName) {
                $path = $request->file($inputName)->store("dokumen_jamaah/{$jamaah->id}", 'public');

                DocumentJamaah::create([
                    'jamaah_id' => $jamaah->id,
                    'jenis' => $jenis,
                    'file_path' => $path,
                    'status' => 'proses',
                    'submitted_at' => now(),
                ]);
            }
        });

        return back()->with('success', 'Pendaftaran berhasil dikirim. Admin akan memverifikasi dokumen Anda.');
    }

    public function formUmrohCreate(Paket $paket)
    {
        abort_if($paket->type !== 'UMROH', 404);

        return view('user.form-umroh', compact('paket'));
    }

    public function formUmrohStore(Request $request, Paket $paket): RedirectResponse
    {
        abort_if($paket->type !== 'UMROH', 404);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nik' => ['required', 'string', 'regex:/^\d{16}$/', 'unique:jamaahs,nik'],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'no_tlpn' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],

            'tanggal_keberangkatan' => ['nullable', 'date'],

            'dok_ktp' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_kk' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_surat_nikah' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_foto' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
            'dok_passport' => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $userId = (int) $request->user()->id;

        DB::transaction(function () use ($validated, $request, $userId, $paket): void {
            $jamaah = Jamaah::create([
                'user_id' => $userId,
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'no_tlpn' => $validated['no_tlpn'],
                'alamat' => $validated['alamat'],
                'status' => 'pending',
            ]);

            $order = Order::create([
                'user_id' => $userId,
                'jamaah_id' => $jamaah->id,
                'paket_id' => $paket->id,
                'status' => 'pending',
                'harga_snapshot' => (int) $paket->harga,
                'total_tagihan' => (int) $paket->harga,
                'durasi_cicilan' => null,
                'total_dibayar' => 0,
            ]);

            OrderUmrohDetail::create([
                'order_id' => $order->id,
                'tanggal_keberangkatan' => $validated['tanggal_keberangkatan'] ?? null,
            ]);

            $dokumenMap = [
                'ktp' => 'dok_ktp',
                'kk' => 'dok_kk',
                'surat_nikah' => 'dok_surat_nikah',
                'foto' => 'dok_foto',
                'passport' => 'dok_passport',
            ];

            foreach ($dokumenMap as $jenis => $inputName) {
                $path = $request->file($inputName)->store("dokumen_jamaah/{$jamaah->id}", 'public');

                DocumentJamaah::create([
                    'jamaah_id' => $jamaah->id,
                    'jenis' => $jenis,
                    'file_path' => $path,
                    'status' => 'proses',
                    'submitted_at' => now(),
                ]);
            }
        });

        return back()->with('success', 'Pendaftaran berhasil dikirim. Admin akan memverifikasi dokumen Anda.');
    }
}
