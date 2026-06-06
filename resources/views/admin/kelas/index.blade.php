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
          <div class="max-w-7xl">
            <div class="flex items-start justify-between gap-4 mb-6">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Kelas Bimbingan</h2>
                <p class="text-sm text-gray-600">Kelola kelas bimbingan dan sesi.</p>
              </div>

              <a
                href="{{ route('admin.kelas.create') }}"
                class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-900 focus:ring-offset-2"
              >
                Tambah Kelas
              </a>
            </div>

            <x-flash-message />

            <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
              @if (($kelasList ?? null) && $kelasList->count())
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sesi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      @foreach ($kelasList as $kelas)
                        <tr>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $kelas->nama_kelas }}</div>
                            <div class="text-xs text-gray-500">#{{ $kelas->id }}</div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ optional($kelas->paket)->nama_paket ?? '-' }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div>{{ optional($kelas->mulai_periode)->format('d M Y') ?? '-' }}</div>
                            <div class="text-xs text-gray-500">s/d {{ optional($kelas->selesai_periode)->format('d M Y') ?? '-' }}</div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $kelas->nama_pembimbing ?? '-' }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 ring-1 ring-inset ring-gray-200">
                              {{ strtoupper((string) ($kelas->status ?? '')) }}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ (int) ($kelas->sesi_bimbingans_count ?? 0) }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ (int) ($kelas->order_bimbingan_details_count ?? 0) }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a
                              href="{{ route('admin.kelas.show', $kelas) }}"
                              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                              Lihat Detail
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                  {{ $kelasList->links() }}
                </div>
              @else
                <div class="p-6 text-sm text-gray-600">
                  Belum ada kelas. Klik <span class="font-medium">Tambah Kelas</span> untuk membuat.
                </div>
              @endif
            </div>
          </div>

        </main>
      </div>
    </div>

  </body>
</html>
