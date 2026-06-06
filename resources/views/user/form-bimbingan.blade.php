<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config("app.name", "Laravel") }} - Admin KBIH</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-gray-100">
    <x-nav-user />

    <main class="max-w-[90%] md:max-w-3xl lg:max-w-6xl mx-auto py-8">
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-xl font-semibold text-green-900">Form Pendaftaran Paket Bimbingan</h2>
        <p class="text-sm text-gray-600 mt-1">
          Lengkapi data jamaah, nomor porsi, dan upload dokumen untuk diproses admin.
        </p>

        @if (session('success'))
          <div class="mt-4 p-3 rounded border border-green-200 bg-green-50 text-green-900">
            {{ session('success') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mt-4 p-3 rounded border border-red-200 bg-red-50 text-red-800">
            <p class="font-medium">Ada data yang belum sesuai.</p>
            <ul class="list-disc pl-5 mt-1 text-sm">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          
          <div>
            <h3 class="font-semibold text-green-900">Paket Dipilih</h3>
            <div class="mt-3 border border-green-900/20 bg-green-50/40 rounded-lg p-4">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-sm text-gray-600">Nama Paket</p>
                  <p class="font-semibold text-gray-900">{{ $paket->nama_paket }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm text-gray-600">Harga</p>
                  <p class="font-semibold text-green-900">Rp {{ number_format($paket->harga, 0, ',', '.') }}</p>
                </div>
              </div>
  
            </div>
          </div>
        <form
          class="mt-6"
          method="POST"
          action="{{ route('bimbingan.store', $paket) }}"
          enctype="multipart/form-data"
        >
          @csrf

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div>
              <h3 class="font-semibold">Nomor Porsi</h3>
              <div class="mt-3">
                <label class="block text-sm font-medium" for="nomor_porsi">No. Porsi Keberangkatan</label>
                <input
                  id="nomor_porsi"
                  name="nomor_porsi"
                  type="text"
                  value="{{ old('nomor_porsi') }}"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                />
                @error('nomor_porsi')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <div class="mt-8">
            <h3 class="font-semibold">Data Jamaah</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
              <div>
                <label class="block text-sm font-medium" for="nama_lengkap">Nama Lengkap</label>
                <input
                  id="nama_lengkap"
                  name="nama_lengkap"
                  type="text"
                  value="{{ old('nama_lengkap') }}"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                />
                @error('nama_lengkap')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="jenis_kelamin">Jenis Kelamin</label>
                <select
                  id="jenis_kelamin"
                  name="jenis_kelamin"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                >
                  <option value="">-- Pilih --</option>
                  <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                  <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="nik">NIK</label>
                <input
                  id="nik"
                  name="nik"
                  type="text"
                  inputmode="numeric"
                  value="{{ old('nik') }}"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                />
                <p class="text-xs text-gray-500 mt-1">Harus 16 digit.</p>
                @error('nik')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="no_tlpn">No. Telepon</label>
                <input
                  id="no_tlpn"
                  name="no_tlpn"
                  type="text"
                  value="{{ old('no_tlpn') }}"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                />
                @error('no_tlpn')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="tempat_lahir">Tempat Lahir</label>
                <input
                  id="tempat_lahir"
                  name="tempat_lahir"
                  type="text"
                  value="{{ old('tempat_lahir') }}"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                />
                @error('tempat_lahir')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="tanggal_lahir">Tanggal Lahir</label>
                <input
                  id="tanggal_lahir"
                  name="tanggal_lahir"
                  type="date"
                  value="{{ old('tanggal_lahir') }}"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                />
                @error('tanggal_lahir')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium" for="alamat">Alamat</label>
                <textarea
                  id="alamat"
                  name="alamat"
                  rows="3"
                  class="mt-1 block w-full rounded border-gray-300 focus:border-green-900 focus:ring-green-900"
                  required
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <div class="mt-8">
            <h3 class="font-semibold">Upload Dokumen</h3>
            <p class="text-sm text-gray-600 mt-1">Format: JPG/PNG/PDF, maksimal 5MB per file.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
              <div>
                <label class="block text-sm font-medium" for="dok_ktp">KTP</label>
                <input id="dok_ktp" name="dok_ktp" type="file" class="mt-1 block w-full" required />
                @error('dok_ktp')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="dok_kk">Kartu Keluarga (KK)</label>
                <input id="dok_kk" name="dok_kk" type="file" class="mt-1 block w-full" required />
                @error('dok_kk')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="dok_surat_nikah">Surat Nikah</label>
                <input id="dok_surat_nikah" name="dok_surat_nikah" type="file" class="mt-1 block w-full" required />
                @error('dok_surat_nikah')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="dok_foto">Foto</label>
                <input id="dok_foto" name="dok_foto" type="file" class="mt-1 block w-full" required />
                @error('dok_foto')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium" for="dok_passport">Passport</label>
                <input id="dok_passport" name="dok_passport" type="file" class="mt-1 block w-full" required />
                @error('dok_passport')
                  <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <div class="mt-8 flex items-center gap-3">
            <button
              type="submit"
              class="px-4 py-2 rounded bg-green-900 text-white"
            >
              Kirim Pendaftaran
            </button>
            <p class="text-sm text-gray-600">Status akan <span class="font-medium">pending</span> sampai diverifikasi admin.</p>
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
