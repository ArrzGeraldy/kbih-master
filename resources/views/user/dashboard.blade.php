

 



<!DOCTYPE html>
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
      <section class=" pt-8">
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
              <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-md">
                <p class="text-sm text-slate-500">Total Order</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $totalOrders }}</p>
              </div>
              <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-md">
                <p class="text-sm text-slate-500">Order Aktif</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $activeOrders }}</p>
              </div>
          
            </div>
      </section>

      <section id="orders" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <div>
            <h2 class="text-2xl font-semibold text-slate-900">Order Saya</h2>
            <p class="mt-2 text-sm text-slate-600">Semua pesanan paket Haji dan Umroh yang dibuat oleh akun Anda.</p>
          </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-3">
          @forelse (($orders ?? collect()) as $order)
            @php
              $status = (string) $order->status;
              $statusLabel = match ($status) {
                  'draft' => 'Draft',
                  'pending' => 'Menunggu',
                  'active' => 'Aktif',
                  'done' => 'Selesai',
                  'cancel' => 'Batal',
                  default => strtoupper($status),
              };

              $badgeClass = match ($status) {
                  'active' => 'bg-green-50 text-green-700 ring-green-200',
                  'done' => 'bg-blue-50 text-blue-700 ring-blue-200',
                  'cancel' => 'bg-red-50 text-red-700 ring-red-200',
                  'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
                  default => 'bg-gray-50 text-gray-700 ring-gray-200',
              };

              $totalTagihan = (int) ($order->total_tagihan ?? 0);
              $totalDibayar = (int) ($order->total_dibayar ?? 0);
              $sisaTagihan = max(0, $totalTagihan - $totalDibayar);
            @endphp

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
              <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                  <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-base font-semibold text-slate-900 truncate">{{ $order->paket?->nama_paket ?? ('Paket #' . $order->paket_id) }}</h3>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">
                      {{ $statusLabel }}
                    </span>
                  </div>
                  <p class="mt-3 text-sm text-slate-600">Jamaah: {{ $order->jamaah?->nama_lengkap ?? '-' }}</p>
                  <p class="mt-1 text-sm text-slate-500">Order #{{ $order->id }} • {{ $order->created_at?->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                  <p class="text-xs text-slate-500">Sisa Tagihan</p>
                  <p class="mt-2 text-lg font-semibold text-slate-900">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</p>
                </div>
              </div>

              <div class="mt-4 grid gap-3 border-t border-slate-200 pt-4 text-sm text-slate-600">
                <div class="flex items-center justify-between">
                  <span>Total Tagihan</span>
                  <span>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Terbayar</span>
                  <span>Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                </div>
              </div>

              <a href="/order/{{ $order->id }}" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-green-600 px-4 py-3 text-sm font-semibold text-white hover:bg-green-500">
                Lihat Detail
              </a>
            </article>
          @empty
            <div class="rounded-3xl border border-slate-200 bg-white p-12 text-center lg:col-span-3">
              <p class="text-lg font-semibold text-slate-900">Belum ada order</p>
              <p class="mt-2 text-slate-600">Silakan pilih paket terlebih dahulu untuk membuat order baru.</p>
              <a href="/" class="mt-6 inline-flex items-center justify-center rounded-full bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-500">
                Pilih Paket Sekarang
              </a>
            </div>
          @endforelse
        </div>
      </section>

  
    </main>

    <x-footer />
  </body>
</html>
