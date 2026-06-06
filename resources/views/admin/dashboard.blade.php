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
            <div class="mb-6">
              <h2 class="text-xl font-semibold text-gray-900">Dashboard</h2>
              <p class="text-sm text-gray-600">Ringkasan data pendaftaran dan pembayaran.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4 mb-6">
              <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-sm text-gray-600">Total Orders</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ (int) ($totalOrders ?? 0) }}</div>
                <div class="mt-2 text-xs text-gray-500">Draft: {{ (int) ($ordersByStatus['draft'] ?? 0) }} · Pending: {{ (int) ($ordersByStatus['pending'] ?? 0) }}</div>
              </div>

              <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-sm text-gray-600">Orders Aktif</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ (int) ($ordersByStatus['active'] ?? 0) }}</div>
                <div class="mt-2 text-xs text-gray-500">Done: {{ (int) ($ordersByStatus['done'] ?? 0) }} · Cancel: {{ (int) ($ordersByStatus['cancel'] ?? 0) }}</div>
              </div>

              <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-sm text-gray-600">Total Jamaah</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">{{ (int) ($totalJamaah ?? 0) }}</div>
                <div class="mt-2 text-xs text-gray-500">Total Paket: {{ (int) ($totalPakets ?? 0) }}</div>
              </div>

              <div class="rounded-lg border border-gray-200 bg-white p-4">
                <div class="text-sm text-gray-600">Pembayaran (Verified)</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900">Rp {{ number_format((int) ($totalPembayaranVerified ?? 0), 0, ',', '.') }}</div>
                <div class="mt-2 text-xs text-gray-500">Akumulasi seluruh pembayaran terverifikasi.</div>
              </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
              <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div>
                  <div class="text-sm font-medium text-gray-900">Order Terbaru</div>
                  <div class="text-xs text-gray-500">5 order terakhir yang masuk.</div>
                </div>
                <a
                  href="{{ route('admin.orders.index') }}"
                  class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >Lihat Semua</a>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Order</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Jamaah</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Paket</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Dibuat</th>
                      <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse (($recentOrders ?? []) as $order)
                      <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                          <div class="font-medium text-gray-900">#{{ $order->id }}</div>
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
                          {{ strtoupper((string) ($order->status ?? '-')) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                          {{ optional($order->created_at)->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                          <div class="flex items-center justify-end">
                            <a
                              href="{{ route('admin.orders.show', $order) }}"
                              class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            >Detail</a>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                          <div class="text-sm font-medium text-gray-900">Belum ada order</div>
                          <div class="mt-1 text-sm text-gray-600">Order baru akan muncul setelah user submit form pendaftaran.</div>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </main>
      </div>
    </div>

  </body>
</html>
