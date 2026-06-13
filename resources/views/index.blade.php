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

    <main class="space-y-24">
      <section class="relative overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(134,239,172,0.2),_transparent_45%)]">
        <div class="max-w-[90%] md:max-w-5xl mx-auto grid gap-12 lg:grid-cols-[1.1fr_.9fr] items-center py-20">
          <div class="space-y-8">
            <span class="inline-flex items-center rounded-full bg-green-50 px-4 py-1 text-sm font-semibold text-green-700">
              Paket Bimbingan Haji & Umroh Terpercaya
            </span>

            <div class="space-y-6">
              <h1 class="text-4xl md:text-5xl font-semibold tracking-tight text-slate-900">
                Persiapkan perjalanan ibadah Anda dengan paket layanan lengkap dan mudah.
              </h1>
              <p class="max-w-2xl text-slate-600 leading-8">
                Dari pendaftaran paket, bimbingan, hingga pembayaran cicilan — semua dikelola dalam satu aplikasi KBIH yang modern.
              </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
              <a href="#paket" class="inline-flex items-center justify-center rounded-full bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-green-500">
                Jelajahi Paket
              </a>
              <a href="#cara-kerja" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                Cara Pendaftaran
              </a>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
              <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase text-slate-500">Proses cepat</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">Daftar & kelola order hanya dalam beberapa menit</p>
              </div>
              <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase text-slate-500">Pembayaran fleksibel</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">Dukungan DP dan cicilan untuk setiap paket</p>
              </div>
            </div>
          </div>

          <div class="rounded-[32px] border border-slate-200 bg-white p-8 shadow-xl">
            <div class="rounded-3xl bg-green-600/10 p-6">
              <p class="text-sm uppercase tracking-[0.25em] text-green-700">Paket populer</p>
              <h2 class="mt-4 text-3xl font-semibold text-slate-900">Bimbingan Haji Premium</h2>
              <p class="mt-3 text-slate-600">Bimbingan lengkap dan pendampingan perjalanan ibadah yang terstruktur.</p>

              <div class="mt-6 grid gap-3">
                <div class="rounded-2xl bg-white p-4 shadow-sm">
                  <p class="text-xs text-slate-500">Harga mulai</p>
                  <p class="mt-2 text-2xl font-semibold text-slate-900">Rp 15.000.000</p>
                </div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">
                  <p class="text-xs text-slate-500">DP</p>
                  <p class="mt-2 text-lg font-semibold text-slate-900">Rp 3.000.000</p>
                </div>
              </div>

              <div class="mt-6 rounded-3xl border border-green-100 bg-green-50 p-4">
                <p class="text-sm font-semibold text-green-700">Siap berangkat dengan bimbingan profesional</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="paket" class="max-w-[90%] md:max-w-5xl mx-auto space-y-8">
        <div class="space-y-3 text-center">
          <p class="text-sm uppercase tracking-[0.3em] text-green-600">Pilih paket</p>
          <h2 class="text-3xl font-semibold text-slate-900">Paket unggulan kami</h2>
          <p class="mx-auto max-w-2xl text-slate-600">
            Temukan paket Haji dan Umroh yang sesuai dengan kebutuhan Anda, lengkap dengan fasilitas, DP, dan opsi pembayaran.
          </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          @forelse (($pakets ?? collect()) as $paket)
            @php
              $typeLabel = $paket->type === 'BIMBINGAN_HAJI' ? 'Bimbingan Haji' : 'Umroh';
              $fasilitasRaw = $paket->fasilitas;
              $fasilitasItems = collect(is_array($fasilitasRaw) ? $fasilitasRaw : preg_split('/\r\n|\r|\n|,|;/', (string) $fasilitasRaw))
                ->map(fn ($item) => trim((string) $item))
                ->filter();
              $type = $paket->type == 'BIMBINGAN_HAJI' ? 'bimbingan' : 'umroh';
            @endphp

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
              <div class="flex items-center justify-between gap-4">
                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-green-700">{{ $typeLabel }}</span>
                <span class="text-sm text-slate-500">Paket #{{ $paket->id }}</span>
              </div>

              <div class="mt-5 space-y-3">
                <h3 class="text-xl font-semibold text-slate-900">{{ $paket->nama_paket }}</h3>
                <p class="text-2xl font-semibold text-slate-900">Rp {{ number_format((int) $paket->harga, 0, ',', '.') }}</p>
              </div>

              <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-2xl bg-slate-50 p-4">
                  <p class="text-xs text-slate-500">DP</p>
                  <p class="mt-1 font-semibold text-slate-900">Rp {{ number_format((int) $paket->dp, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                  <p class="text-xs text-slate-500">Min. Bayar</p>
                  <p class="mt-1 font-semibold text-slate-900">Rp {{ number_format((int) $paket->minimum_pembayaran, 0, ',', '.') }}</p>
                </div>
              </div>

              <div class="mt-6">
                <p class="text-sm font-medium text-slate-700">Fasilitas</p>
                @if ($fasilitasItems->isEmpty())
                  <p class="mt-2 text-sm text-slate-500">Belum ada fasilitas</p>
                @else
                  <ul class="mt-3 space-y-2">
                    @foreach ($fasilitasItems as $item)
                      <li class="flex items-start gap-2 text-sm text-slate-700">
                        <span class="mt-1 inline-flex h-4 w-4 items-center justify-center rounded-full bg-green-100 text-green-700">✓</span>
                        <span>{{ $item }}</span>
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>

              <a href="/{{ $type }}/daftar/{{ $paket->id }}" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-green-600 px-4 py-3 text-sm font-semibold text-white hover:bg-green-500">
                Daftar Sekarang
              </a>
            </article>
          @empty
            <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-12 text-center">
              <p class="text-lg font-semibold text-slate-900">Belum ada paket tersedia</p>
              <p class="mt-2 text-slate-600">Silakan kembali lagi nanti untuk melihat paket terbaru.</p>
            </div>
          @endforelse
        </div>
      </section>

      <section id="keunggulan" class="bg-slate-100 py-20">
        <div class="max-w-[90%] md:max-w-5xl mx-auto space-y-10">
          <div class="text-center">
            <p class="text-sm uppercase tracking-[0.3em] text-green-600">Kenapa pilih kami</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">Layanan KBIH yang membuat perjalanan ibadah lebih tenang</h2>
          </div>

          <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-8 shadow-sm">
              <p class="text-4xl">📌</p>
              <h3 class="mt-6 text-xl font-semibold text-slate-900">Pendampingan Lengkap</h3>
              <p class="mt-3 text-slate-600">Bimbingan dan arahan dari tahap persiapan hingga pelaksanaan ibadah.</p>
            </div>
            <div class="rounded-3xl bg-white p-8 shadow-sm">
              <p class="text-4xl">💳</p>
              <h3 class="mt-6 text-xl font-semibold text-slate-900">Pembayaran Cicilan</h3>
              <p class="mt-3 text-slate-600">Bayar sesuai kemampuan dengan opsi DP dan cicilan terkelola.</p>
            </div>
            <div class="rounded-3xl bg-white p-8 shadow-sm">
              <p class="text-4xl">📄</p>
              <h3 class="mt-6 text-xl font-semibold text-slate-900">Pengelolaan Dokumen</h3>
              <p class="mt-3 text-slate-600">Unggah dokumen jamaah secara mudah dan aman melalui sistem.</p>
            </div>
          </div>
        </div>
      </section>

      <section id="cara-kerja" class="max-w-[90%] md:max-w-5xl mx-auto space-y-10">
        <div class="text-center">
          <p class="text-sm uppercase tracking-[0.3em] text-green-600">Langkah mudah</p>
          <h2 class="mt-3 text-3xl font-semibold text-slate-900">Cara pendaftaran KBIH</h2>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
          <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-green-600/10 text-green-700 text-xl">1</div>
            <h3 class="mt-6 text-xl font-semibold text-slate-900">Pilih paket</h3>
            <p class="mt-3 text-slate-600">Cari paket Haji atau Umroh sesuai kebutuhan Anda.</p>
          </article>
          <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-green-600/10 text-green-700 text-xl">2</div>
            <h3 class="mt-6 text-xl font-semibold text-slate-900">Isi formulir</h3>
            <p class="mt-3 text-slate-600">Lengkapi data jamaah, pilih tanggal, dan upload dokumen.</p>
          </article>
          <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-green-600/10 text-green-700 text-xl">3</div>
            <h3 class="mt-6 text-xl font-semibold text-slate-900">Konfirmasi & bayar</h3>
            <p class="mt-3 text-slate-600">Bayar DP atau cicilan, lalu pantau status pembayaran di dashboard.</p>
          </article>
        </div>
      </section>

      <section id="tentang" class="bg-slate-900 text-white py-20">
        <div class="max-w-[90%] md:max-w-5xl mx-auto grid gap-10 lg:grid-cols-2 items-center">
          <div class="space-y-6">
            <p class="text-sm uppercase tracking-[0.3em] text-emerald-300">Tentang KBIH</p>
            <h2 class="text-3xl font-semibold">Membawa ketenangan dalam setiap langkah ibadah Anda</h2>
            <p class="text-slate-300 leading-8">
              Aplikasi ini dirancang untuk memudahkan calon jamaah dalam memilih paket, mendaftar, dan melacak pembayaran. Semuanya dalam satu platform KBIH yang terintegrasi.
            </p>
            <div class="grid gap-4 sm:grid-cols-2">
              <div class="rounded-3xl bg-slate-800 p-6">
                <p class="text-sm text-emerald-300">Mudah digunakan</p>
                <p class="mt-2 text-slate-200">Antarmuka sederhana untuk semua usia.</p>
              </div>
              <div class="rounded-3xl bg-slate-800 p-6">
                <p class="text-sm text-emerald-300">Aman & terpercaya</p>
                <p class="mt-2 text-slate-200">Data jamaah dan pembayaran tersimpan dengan baik.</p>
              </div>
            </div>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-xl">
            <div class="space-y-5">
              <div class="rounded-3xl bg-white/10 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-emerald-200">Statistik</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                  <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-3xl font-semibold">120+</p>
                    <p class="mt-1 text-sm text-slate-300">Jamaah terbantu</p>
                  </div>
                  <div class="rounded-2xl bg-white/10 p-4">
                    <p class="text-3xl font-semibold">30+</p>
                    <p class="mt-1 text-sm text-slate-300">Paket tersedia</p>
                  </div>
                </div>
              </div>

              <div class="rounded-3xl bg-white/5 p-6">
                <p class="text-sm uppercase tracking-[0.3em] text-emerald-200">Dukungan</p>
                <p class="mt-3 text-slate-300">Tim admin siap membantu proses pendaftaran dan verifikasi dokumen Anda.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

   <x-footer />
  </body>
</html>