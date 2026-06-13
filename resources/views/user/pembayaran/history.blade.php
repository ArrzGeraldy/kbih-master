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

    <main class="space-y-16 pb-12 w-full max-w-[90%] md:max-w-6xl mx-auto min-h-screen">
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-md border border-gray-200 sm:rounded-lg">
            <div class="p-6 space-y-4">
              <div class="text-sm text-gray-600">
                <div>
                  <span class="font-medium">Order ID:</span> {{ $order->id }}
                </div>
                @if(!empty($order->paket?->nama_paket))
                <div>
                  <span class="font-medium">Paket:</span> {{
                  $order->paket->nama_paket }}
                </div>
                @endif
              </div>

              @if ($pembayarans->isEmpty())
              <div class="text-gray-700">
                Belum ada history pembayaran untuk order ini.
              </div>
              @else
              <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                  <thead>
                    <tr class="text-left text-gray-600 border-b">
                      <th class="py-2 pr-4">Tanggal</th>
                      <th class="py-2 pr-4">Tipe</th>
                      <th class="py-2 pr-4">Jumlah</th>
                      <th class="py-2 pr-4">Status</th>
                      <th class="py-2 pr-4">Metode</th>
                      <th class="py-2 pr-4">Ref</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y">
                    @foreach ($pembayarans as $pembayaran) @php $status =
                    (string) ($pembayaran->status ?? ''); $badgeClass = match
                    ($status) { 'verify' => 'bg-green-100 text-green-800',
                    'pending' => 'bg-yellow-100 text-yellow-800', 'reject' =>
                    'bg-red-100 text-red-800', default => 'bg-gray-100
                    text-gray-800', }; $tanggal = $pembayaran->tanggal_bayar ?
                    \Carbon\Carbon::parse($pembayaran->tanggal_bayar) :
                    ($pembayaran->created_at ?
                    \Carbon\Carbon::parse($pembayaran->created_at) : null);
                    @endphp

                    <tr>
                      <td class="py-2 pr-4 whitespace-nowrap">
                        {{ $tanggal ? $tanggal->format('d/m/Y H:i') : '-' }}
                      </td>
                      <td class="py-2 pr-4 whitespace-nowrap">
                        {{ strtoupper((string) ($pembayaran->tipe ?? '-')) }}
                      </td>
                      <td class="py-2 pr-4 whitespace-nowrap">
                        Rp {{ number_format((int) ($pembayaran->jumlah ?? 0), 0,
                        ',', '.') }}
                      </td>
                      <td class="py-2 pr-4 whitespace-nowrap">
                        <span
                          class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}"
                        >
                          {{ $status !== '' ? $status : '-' }}
                        </span>
                      </td>
                      <td class="py-2 pr-4 whitespace-nowrap">
                        {{ $pembayaran->metode ?? '-' }}
                      </td>
                      <td
                        class="py-2 pr-4 whitespace-nowrap max-w-[280px] truncate"
                        title="{{ $pembayaran->midtrans_order_id ?? '' }}"
                      >
                        {{ $pembayaran->midtrans_order_id ?? '-' }}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </main>

    <x-footer />
  </body>
</html>
