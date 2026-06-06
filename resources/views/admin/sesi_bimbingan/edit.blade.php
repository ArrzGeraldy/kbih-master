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
                <h2 class="text-xl font-semibold text-gray-900">Edit Sesi Bimbingan</h2>
                <p class="text-sm text-gray-600">Perbarui jadwal dan informasi sesi. Kelas tidak bisa diubah.</p>
              </div>

              <a
                href="{{ route('admin.kelas.show', $sesi->kelas_bimbingan_id) }}"
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
              <form method="POST" action="{{ route('admin.sesi_bimbingan.update', $sesi) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div class="sm:col-span-2">
                    <div class="text-sm text-gray-600">Kelas (tidak bisa diubah)</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ optional($sesi->kelasBimbingan)->nama_kelas ?? '-' }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500">
                      Paket: {{ optional(optional($sesi->kelasBimbingan)->paket)->nama_paket ?? '-' }} · Kelas ID: {{ $sesi->kelas_bimbingan_id }}
                    </div>
                  </div>

                  <div class="sm:col-span-2">
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input
                      type="text"
                      id="judul"
                      name="judul"
                      value="{{ old('judul', $sesi->judul) }}"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="Manasik 1"
                    />
                  </div>

                  <div>
                    <label for="mulai_at" class="block text-sm font-medium text-gray-700">Mulai</label>
                    <input
                      type="datetime-local"
                      id="mulai_at"
                      name="mulai_at"
                      value="{{ old('mulai_at', optional($sesi->mulai_at)->format('Y-m-d\\TH:i')) }}"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    />
                  </div>

                  <div>
                    <label for="selesai_at" class="block text-sm font-medium text-gray-700">Selesai</label>
                    <input
                      type="datetime-local"
                      id="selesai_at"
                      name="selesai_at"
                      value="{{ old('selesai_at', optional($sesi->selesai_at)->format('Y-m-d\\TH:i')) }}"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    />
                  </div>

                  <div class="sm:col-span-2">
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi (opsional)</label>
                    <textarea
                      id="lokasi"
                      name="lokasi"
                      rows="2"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="Masjid / Aula / Alamat lengkap"
                    >{{ old('lokasi', $sesi->lokasi) }}</textarea>
                  </div>

                  <div class="sm:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan (opsional)</label>
                    <textarea
                      id="keterangan"
                      name="keterangan"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="Catatan tambahan untuk sesi"
                    >{{ old('keterangan', $sesi->keterangan) }}</textarea>
                  </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                  <a
                    href="{{ route('admin.kelas.show', $sesi->kelas_bimbingan_id) }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                  >
                    Batal
                  </a>
                  <button
                    type="submit"
                    class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-900 focus:ring-offset-2"
                  >
                    Simpan Perubahan
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
