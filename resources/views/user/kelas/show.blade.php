<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }} - KBIH</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-slate-50 text-slate-900">
    <x-nav-user />

    <main class="space-y-16 pb-12 w-full max-w-[90%] md:max-w-6xl mx-auto">
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="mb-6 flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">
                Kelas untuk Order #{{ $order->id ?? '' }}
              </h3>
              <p class="text-sm text-gray-600">
                Lihat detail kelas dan jadwal manasik.
              </p>
            </div>
            <a
              href="{{ url()->previous() }}"
              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Kembali
            </a>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
              <div
                class="rounded-lg border border-gray-200 bg-white p-6 shadow-md"
              >
                <h4 class="font-semibold text-gray-900">Ringkasan Kelas</h4>

                @if (!$kelas)
                <div class="mt-4 text-sm text-gray-600">
                  Kelas belum ditentukan oleh admin untuk order ini.
                </div>
                @else
                <div class="mt-4 grid grid-cols-1 gap-4">
                  <div>
                    <div class="text-sm text-gray-600">Nama Kelas</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ $kelas->nama_kelas ?? '-' }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600">Paket</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ optional($kelas->paket)->nama_paket ?? '-' }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600">Pembimbing</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ $kelas->nama_pembimbing ?? '-' }}
                    </div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600">Periode</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ optional($kelas->mulai_periode)->format('d M Y') ?? '-'
                      }}
                      <span class="text-gray-500">s/d</span>
                      {{ optional($kelas->selesai_periode)->format('d M Y') ??
                      '-' }}
                    </div>
                  </div>
                  <!-- <div>
                                    <div class="text-sm text-gray-600">Status</div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 ring-1 ring-inset ring-gray-200">
                                            {{ strtoupper((string) ($kelas->status ?? '')) }}
                                        </span>
                                    </div>
                                </div> -->
                </div>
                @endif
              </div>
            </div>

            <div class="lg:col-span-2">
              <div
                class="rounded-lg border border-gray-200 bg-white overflow-hidden shadow-md"
              >
                <div class="px-6 py-4 border-b border-gray-200">
                  <h4 class="font-semibold text-gray-900">Daftar Sesi</h4>
                  <p class="text-sm text-gray-600">
                    Jadwal manasik untuk kelas kamu.
                  </p>
                </div>

                @if (!$kelas)
                <div class="p-6 text-sm text-gray-600">
                  Belum ada sesi karena kelas belum ditentukan.
                </div>
                @elseif (optional($kelas->sesiBimbingans)->count())
                <div class="overflow-x-auto">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Judul
                        </th>
                        <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Mulai
                        </th>
                        <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Selesai
                        </th>
                        <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Lokasi
                        </th>
                        <th
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Keterangan
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      @foreach ($kelas->sesiBimbingans as $sesi)
                      <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm font-medium text-gray-900">
                            {{ $sesi->judul ?? '-' }}
                          </div>
                        </td>
                        <td
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                        >
                          {{ optional($sesi->mulai_at)->format('d M Y H:i') ??
                          '-' }}
                        </td>
                        <td
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
                        >
                          {{ optional($sesi->selesai_at)->format('d M Y H:i') ??
                          '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                          {{ $sesi->lokasi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                          {{ $sesi->keterangan ?? '-' }}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @else
                <div class="p-6 text-sm text-gray-600">
                  Belum ada sesi untuk kelas ini.
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <x-footer />
  </body>
</html>
