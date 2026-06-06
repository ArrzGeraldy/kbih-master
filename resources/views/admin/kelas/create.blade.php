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
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard-admin.js'])
  </head>
  <body class="font-sans antialiased bg-gray-100">
    <div class="w-full min-h-screen flex relative">
      <!-- aside -->
      <x-sidebar />

      <div
        class="flex-1 flex flex-col overflow-hidden ms-0 lg:ms-64"
        id="content"
      >
        <!-- topbar -->
        <x-topbar />

        <!-- main content -->
        <main class="flex-1 overflow-y-auto p-6">
          <div class="max-w-4xl">
            <div class="flex items-start justify-between gap-4 mb-6">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Tambah Kelas Bimbingan</h2>
                <p class="text-sm text-gray-600">Buat kelas dan otomatis generate 12 sesi bimbingan.</p>
              </div>

              <a
                href="{{ route('admin.kelas.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Kembali
              </a>
            </div>

            <x-flash-message />

            @if ($errors->any())
              <div class="mb-6 rounded-md border border-red-200 bg-red-50 p-4">
                <div class="text-sm font-medium text-red-800">Periksa input kamu:</div>
                <ul class="mt-2 list-disc ps-5 text-sm text-red-700">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="rounded-lg border border-gray-200 bg-white p-6">
              <form method="POST" action="{{ route('admin.kelas.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div class="sm:col-span-2">
                    <label for="paket_id" class="block text-sm font-medium text-gray-700">Paket</label>
                    <select
                      id="paket_id"
                      name="paket_id"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    >
                      <option value="" disabled {{ old('paket_id') ? '' : 'selected' }}>Pilih paket</option>
                      @foreach (($pakets ?? collect()) as $paket)
                        <option value="{{ $paket->id }}" {{ (string) old('paket_id') === (string) $paket->id ? 'selected' : '' }}>
                          {{ $paket->nama_paket }}
                        </option>
                      @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Paket tipe Bimbingan Haji atau Umroh.</p>
                  </div>

                  <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun Periode</label>
                    <input
                      type="number"
                      inputmode="numeric"
                      id="tahun"
                      name="tahun"
                      value="{{ old('tahun', now()->year) }}"
                      min="2000"
                      max="2100"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="2026"
                    />
                    <p class="mt-1 text-xs text-gray-500">Periode otomatis: Agustus (tahun-1) s/d Juli (tahun).</p>
                  </div>

                  <div>
                    <label for="nama_pembimbing" class="block text-sm font-medium text-gray-700">Nama Pembimbing (opsional)</label>
                    <input
                      type="text"
                      id="nama_pembimbing"
                      name="nama_pembimbing"
                      value="{{ old('nama_pembimbing') }}"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="Ustadz/Ustadzah ..."
                    />
                  </div>

                  <div class="sm:col-span-2">
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                    <input
                      type="text"
                      id="nama_kelas"
                      name="nama_kelas"
                      value="{{ old('nama_kelas') }}"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="Contoh: Kelas Bimbingan 2026 - Gelombang 1"
                    />
                  </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                  <a
                    href="{{ route('admin.kelas.index') }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    Batal
                  </a>
                  <button
                    type="submit"
                    class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-900 focus:ring-offset-2"
                  >
                    Simpan Kelas
                  </button>
                </div>
              </form>
            </div>
          </div>

        </main>
      </div>
    </div>

  </body>
</html>
