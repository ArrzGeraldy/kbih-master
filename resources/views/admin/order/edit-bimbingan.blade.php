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
                <h2 class="text-xl font-semibold text-gray-900">Edit Detail Bimbingan</h2>
                <p class="text-sm text-gray-600">Perbarui nomor porsi dan kelas untuk order ini.</p>
              </div>

              <a
                href="{{ route('admin.orders.show', $order) }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Kembali ke Order
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
              <form method="POST" action="{{ route('admin.orders.update-bimbingan', $order) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div class="">
                    <label class="block text-sm font-medium text-gray-700">Paket</label>
                    <input
                      type="text"
                      value="{{ optional($order->paket)->nama_paket ?? '-' }}"
                      disabled
                      class="mt-1 block w-full rounded-md border-gray-300 bg-slate-100 px-4 py-3 text-slate-900"
                    />
                  </div>

                  <div class="">
                    <label for="nomor_porsi" class="block text-sm font-medium text-gray-700">Nomor Porsi</label>
                    <input
                      type="text"
                      id="nomor_porsi"
                      name="nomor_porsi"
                      value="{{ old('nomor_porsi', optional($order->orderBimbinganDetail)->nomor_porsi) }}"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                      placeholder="Masukkan nomor porsi"
                    />
                  </div>

                  <div class="">
                    <label for="kelas_bimbingan_id" class="block text-sm font-medium text-gray-700">Kelas Bimbingan</label>
                    <select
                      id="kelas_bimbingan_id"
                      name="kelas_bimbingan_id"
                      required
                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                    >
                      <option value="" disabled {{ old('kelas_bimbingan_id', optional($order->orderBimbinganDetail)->kelas_bimbingan_id) ? '' : 'selected' }}>
                        Pilih kelas bimbingan
                      </option>
                      @foreach ($kelasBimbingans as $kelas)
                        <option
                          value="{{ $kelas->id }}"
                          @selected(old('kelas_bimbingan_id', optional($order->orderBimbinganDetail)->kelas_bimbingan_id) == $kelas->id)
                        >
                          {{ $kelas->nama_kelas }} - {{ optional($kelas->mulai_periode)->format('d M Y') ?? '-' }}
                        </option>
                      @endforeach
                    </select>
                    @if ($kelasBimbingans->isEmpty())
                      <p class="mt-2 text-sm text-amber-700">Belum ada kelas bimbingan untuk paket ini.</p>
                    @endif
                  </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                  <a
                    href="{{ route('admin.orders.index') }}"
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
