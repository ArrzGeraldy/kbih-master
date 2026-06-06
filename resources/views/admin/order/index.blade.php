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
          <x-flash-message />

          <div class="max-w-6xl">
            <div class="flex items-start justify-between gap-4 mb-6">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Orders</h2>
                <p class="text-sm text-gray-600">Daftar pendaftaran/order paket dari jamaah.</p>
              </div>

              <a
                href="{{ route('admin.paket.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
              >
                Lihat Paket
              </a>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
              <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                  <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select
                      id="status"
                      name="status"
                      class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                      <option value="">Semua</option>
                      @foreach (($statusOptions ?? []) as $opt)
                        <option value="{{ $opt }}" {{ (($filters['status'] ?? '') === $opt) ? 'selected' : '' }}>
                          {{ strtoupper($opt) }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div>
                    <label for="tipe_paket" class="block text-sm font-medium text-gray-700">Tipe Paket</label>
                    <select
                      id="tipe_paket"
                      name="tipe_paket"
                      class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                      <option value="">Semua</option>
                      @foreach (($paketTypeOptions ?? []) as $type)
                        <option value="{{ $type }}" {{ (($filters['tipe_paket'] ?? '') === $type) ? 'selected' : '' }}>
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
                      href="{{ route('admin.orders.index') }}"
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
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Order</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jamaah</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Paket</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No. Porsi</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                      <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Tagihan</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Dibuat</th>
                      <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($orders as $order)
                      <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                          <div class="font-medium text-gray-900">#{{ $order->id }}</div>
                          <div class="text-xs text-gray-500">User: {{ optional($order->user)->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                          <div class="font-medium text-gray-900">{{ optional($order->jamaah)->nama_lengkap ?? '-' }}</div>
                          <div class="text-xs text-gray-500">NIK: {{ optional($order->jamaah)->nik ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                          <div class="font-medium text-gray-900">{{ optional($order->paket)->nama_paket ?? '-' }}</div>
                          <div class="text-xs text-gray-500">{{ optional($order->paket)->type ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                          {{ optional($order->orderBimbinganDetail)->nomor_porsi ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                          @php
                            $status = $order->status;
                            $badge = match ($status) {
                              'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                              'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-900', 'ring' => 'ring-yellow-200'],
                              'active' => ['bg' => 'bg-green-50', 'text' => 'text-green-900', 'ring' => 'ring-green-200'],
                              'done' => ['bg' => 'bg-green-50', 'text' => 'text-green-900', 'ring' => 'ring-green-200'],
                              'cancel' => ['bg' => 'bg-red-50', 'text' => 'text-red-900', 'ring' => 'ring-red-200'],
                              default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                            };
                          @endphp
                          <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badge['bg'] }} {{ $badge['text'] }} ring-1 ring-inset {{ $badge['ring'] }}">
                            {{ strtoupper($status) }}
                          </span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                          Rp {{ number_format((int) $order->total_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                          {{ optional($order->created_at)->format('d M Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                          <div class="flex items-center justify-end">
                            <a
                              href="{{ route('admin.orders.show', $order) }}"
                              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >
                              Detail
                            </a>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                          <div class="text-sm font-medium text-gray-900">Belum ada order</div>
                          <div class="mt-1 text-sm text-gray-600">Order baru akan muncul setelah user submit form pendaftaran.</div>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <div class="border-t border-gray-200 bg-white px-6 py-4">
                {{ $orders->links() }}
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

  </body>
</html>
