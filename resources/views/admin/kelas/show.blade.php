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
                <h2 class="text-xl font-semibold text-gray-900">Detail Kelas</h2>
                <p class="text-sm text-gray-600">Lihat informasi kelas dan daftar sesi bimbingan.</p>
              </div>

              <a
                href="{{ route('admin.kelas.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Kembali
              </a>
            </div>

            <x-flash-message />

            <div class="grid grid-cols-1 l gap-6">
              <div class="lg:col-span-1">
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                  <h4 class="font-semibold text-gray-900">Ringkasan</h4>

                  <div class="mt-4 space-y-4 grid grid-cols-3">
                    <div>
                      <div class="text-sm text-gray-600">Nama Kelas</div>
                      <div class="mt-1 font-medium text-gray-900">{{ $kelas->nama_kelas ?? '-' }}</div>
                      <div class="mt-1 text-xs text-gray-500">#{{ $kelas->id ?? '' }}</div>
                    </div>

                    <div>
                      <div class="text-sm text-gray-600">Paket</div>
                      <div class="mt-1 font-medium text-gray-900">{{ optional($kelas->paket)->nama_paket ?? '-' }}</div>
                    </div>

                    <div>
                      <div class="text-sm text-gray-600">Status</div>
                      <div class="mt-1">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 ring-1 ring-inset ring-gray-200">
                          {{ strtoupper((string) ($kelas->status ?? '')) }}
                        </span>
                      </div>
                    </div>

                    <div>
                      <div class="text-sm text-gray-600">Periode</div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($kelas->mulai_periode)->format('d M Y') ?? '-' }}
                        <span class="text-gray-500">s/d</span>
                        {{ optional($kelas->selesai_periode)->format('d M Y') ?? '-' }}
                      </div>
                    </div>

                    <div>
                      <div class="text-sm text-gray-600">Pembimbing</div>
                      <div class="mt-1 font-medium text-gray-900">{{ $kelas->nama_pembimbing ?? '-' }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="lg:col-span-2">
                <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
                  <div class="px-6 py-4 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-900">Daftar Sesi</h4>
                    <p class="text-sm text-gray-600">Total: {{ (int) optional($kelas->sesiBimbingans)->count() }} sesi</p>
                  </div>

                  @if (optional($kelas->sesiBimbingans)->count())
                    <div class="overflow-x-auto">
                      <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                          <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                          </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                          @foreach ($kelas->sesiBimbingans as $sesi)
                            <tr>
                              <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sesi->judul ?? '-' }}</div>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ optional($sesi->mulai_at)->format('d M Y H:i') ?? '-' }}
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ optional($sesi->selesai_at)->format('d M Y H:i') ?? '-' }}
                              </td>
                              <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $sesi->lokasi ?? '-' }}
                              </td>
                              <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $sesi->keterangan ?? '-' }}
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a
                                  href="{{ route('admin.sesi_bimbingan.edit', $sesi) }}"
                                  class="px-3 py-1.5 text-sm font-medium block bg-blue-600 text-white rounded w-fit hover:bg-blue-500 transition-all"
                                >
                                  Edit
                                </a>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @else
                    <div class="p-6 text-sm text-gray-600">Belum ada sesi untuk kelas ini.</div>
                  @endif
                </div>
              </div>
            </div>
          </div>

        </main>
      </div>
    </div>

  </body>
</html>
