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
          <div class="max-w-6xl">
            <div class="flex items-start justify-between gap-4 mb-6">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Paket</h2>
                <p class="text-sm text-gray-600">Kelola paket bimbingan haji dan umroh.</p>
              </div>

              <a
                href="{{ route('admin.paket.create') }}"
                class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-900 focus:ring-offset-2"
              >
                Tambah Paket
              </a>
            </div>

            <x-flash-message />

            <form method="GET" action="{{ route('admin.paket.index') }}" class="mb-4">
              <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                  <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Paket</label>
                    <select
                      id="type"
                      name="type"
                      class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                      <option value="">Semua</option>
                      @foreach (($typeOptions ?? []) as $type)
                        <option value="{{ $type }}" {{ (($filters['type'] ?? '') === $type) ? 'selected' : '' }}>
                          {{ str_replace('_', ' ', $type) }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="flex items-end gap-2">
                    <button
                      type="submit"
                      class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                      Filter
                    </button>
                    <a
                      href="{{ route('admin.paket.index') }}"
                      class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                      Reset
                    </a>
                  </div>
                </div>
              </div>
            </form>


            <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                      <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Harga</th>
                      <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">DP</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Terakhir Update</th>
                      <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($pakets as $paket)
                      <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                          <div class="font-medium text-gray-900">{{ $paket->nama_paket }}</div>
                          <div class="text-xs text-gray-500">Min bayar: Rp {{ number_format((int) $paket->minimum_pembayaran, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4">
                          @php
                            $typeLabel = $paket->type === 'BIMBINGAN_HAJI' ? 'Bimbingan Haji' : 'Umroh';
                          @endphp
                          <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-900 ring-1 ring-inset ring-green-200">
                            {{ $typeLabel }}
                          </span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                          Rp {{ number_format((int) $paket->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-700">
                          Rp {{ number_format((int) $paket->dp, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                          {{ optional($paket->updated_at)->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                          <div class="flex items-center justify-end gap-2">
                            <a
                              href="{{ route('admin.paket.edit', $paket) }}"
                              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                              Edit
                            </a>

                            <form method="POST" action="{{ route('admin.paket.destroy', $paket) }}" onsubmit="return confirm('Hapus paket ini?');">
                              @csrf
                              @method('DELETE')
                              <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-green-900 px-3 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-900 focus:ring-offset-2"
                              >
                                Hapus
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                          <div class="text-sm font-medium text-gray-900">Belum ada paket</div>
                          <div class="mt-1 text-sm text-gray-600">Klik <span class="font-medium">Tambah Paket</span> untuk membuat paket baru.</div>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <div class="border-t border-gray-200 bg-white px-6 py-4">
                {{ $pakets->links() }}
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

  </body>
</html>
