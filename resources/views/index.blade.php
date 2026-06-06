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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="font-sans antialiased bg-gray-100">
 <x-nav-user />

    <main class="max-w-[90%] md:max-w-3xl lg:max-w-6xl mx-auto py-8">
      <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Paket</h2>
        <p class="text-sm text-gray-600">Pilih paket yang tersedia.</p>
      </div>

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse (($pakets ?? collect()) as $paket)
          @php
            $typeLabel = $paket->type === 'BIMBINGAN_HAJI' ? 'Bimbingan Haji' : 'Umroh';

            $fasilitasRaw = $paket->fasilitas;
            $fasilitasItems = collect(is_array($fasilitasRaw) ? $fasilitasRaw : preg_split('/\r\n|\r|\n|,|;/', (string) $fasilitasRaw))
              ->map(fn ($item) => trim((string) $item))
              ->filter();
          @endphp


          <!-- card -->
          <div class="rounded border border-green-200 bg-white p-5 shadow-sm">

            <div class="mb-1">
                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-900 ring-1 ring-inset ring-green-200">
                  {{ $typeLabel }}
                </span>
              </div>
                
            <h1 class="text-lg font-semibold">  {{ $paket->nama_paket }}</h1>
            <h2 class="text-lg font-medium">  Rp {{ number_format((int) $paket->harga, 0, ',', '.') }}</h2>

              <div class="mt-4 grid grid-cols-2 gap-3 border-b border-gray-400 pb-4">
              <div class="rounded-md bg-gray-100 p-3">
                <div class="text-xs text-gray-500">DP</div>
                <div class="text-sm font-medium text-gray-900">Rp {{ number_format((int) $paket->dp, 0, ',', '.') }}</div>
              </div>
              <div class="rounded-md bg-gray-100 p-3">
                <div class="text-xs text-gray-500">Min. Bayar</div>
                <div class="text-sm font-medium text-gray-900">Rp {{ number_format((int) $paket->minimum_pembayaran, 0, ',', '.') }}</div>
              </div>
            </div>
            
            <!-- fasilitas -->
            <div class="mt-4">
              <div class="text-xs font-medium text-gray-500">Fasilitas</div>

              @if ($fasilitasItems->isEmpty())
                <div class="mt-2 text-sm text-gray-600">-</div>
              @else
                <ul class="mt-2 space-y-2">
                  @foreach ($fasilitasItems as $item)
                    <li class="flex items-start gap-2 text-sm text-gray-700">
                      <svg viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-4 w-4 text-green-600 flex-none">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.03 4.167-1.47-1.47a.75.75 0 10-1.06 1.06l2.1 2.1a.75.75 0 001.137-.089l3.437-4.886z" clip-rule="evenodd" />
                      </svg>
                      <span class="leading-5">{{ $item }}</span>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
              @php
                $type = $paket->type == 'BIMBINGAN_HAJI' ? 'bimbingan' : 'umroh'                
              @endphp
             <a href="/{{ $type }}/daftar/{{ $paket->id }}" class="font-medium text-white bg-green-600 text-sm px-4 py-1.5 rounded mt-4 w-full hover:bg-green-500 transition-all block text-center">Daftar</a>
          </div>
        @empty
          <div class="col-span-full">
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
              <div class="text-sm font-medium text-gray-900">Belum ada paket tersedia</div>
              <div class="mt-1 text-sm text-gray-600">Silakan coba lagi nanti.</div>
            </div>
          </div>
        @endforelse
      </div>
    </main>
  </body>
</html>
