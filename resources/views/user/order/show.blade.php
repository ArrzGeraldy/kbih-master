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

    <main class="pb-16 w-full max-w-[90%] md:max-w-6xl mx-auto">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-10">
          <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
              <p class="text-sm uppercase tracking-[0.3em] text-green-600">Detail Order</p>
              <h1 class="mt-2 text-3xl font-semibold text-slate-900">Ringkasan Pesanan</h1>
              <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                Informasi lengkap order, status pembayaran, dokumen, dan detail paket untuk perjalanan ibadah Anda.
              </p>
            </div>
          </div>

          <div class="mt-8 grid gap-6 xl:grid-cols-[1.8fr_1.2fr]">
            <div class="space-y-6">
              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                  <div class="space-y-3">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Order #{{ $order->id }}</p>
                    <h2 class="text-2xl font-semibold text-slate-900">{{ optional($order->paket)->nama_paket ?? 'Paket tidak tersedia' }}</h2>
                    <div class="flex flex-wrap gap-3 text-sm">
                      <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-slate-700">Status: {{ strtoupper((string) $order->status) }}</span>
                      <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">Tipe: {{ optional($order->paket)->type ?? '-' }}</span>
                    </div>
                  </div>
                  <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-3xl bg-slate-50 p-4 text-center">
                      <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Tagihan</p>
                      <p class="mt-3 text-lg font-semibold text-slate-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 text-center">
                      <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Terbayar</p>
                      <p class="mt-3 text-lg font-semibold text-slate-900">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 text-center">
                      <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Sisa</p>
                      <p class="mt-3 text-lg font-semibold text-slate-900">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</p>
                    </div>
                  </div>
                </div>
              </section>

              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                  <div>
                    <h3 class="text-lg font-semibold text-slate-900">Data Jamaah</h3>
                    <p class="mt-1 text-sm text-slate-600">Detail informasi calon jamaah.</p>
                  </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                  <div class="space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Nama Lengkap</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->nama_lengkap ?? '-' }}</p>
                  </div>
                  <div class="space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">NIK</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->nik ?? '-' }}</p>
                  </div>
                  <div class="space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Jenis Kelamin</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->jenis_kelamin ?? '-' }}</p>
                  </div>
                  <div class="space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">No. Telepon</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->no_tlpn ?? '-' }}</p>
                  </div>
                  <div class="space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Tempat, Tanggal Lahir</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->tempat_lahir ?? '-' }}, {{ optional(optional($order->jamaah)->tanggal_lahir)->format('d M Y') ?? '-' }}</p>
                  </div>
                  <div class="space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Status Jamaah</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->status ?? '-' }}</p>
                  </div>
                  <div class="md:col-span-2 space-y-2 rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Alamat</p>
                    <p class="text-base font-medium text-slate-900">{{ optional($order->jamaah)->alamat ?? '-' }}</p>
                  </div>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                  <h4 class="text-base font-semibold text-slate-900">Detail Bimbingan / Umroh</h4>
                  @if ($order->paket->type === 'BIMBINGAN_HAJI' && $order->orderBimbinganDetail)
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                      <div class="space-y-2 rounded-2xl bg-white p-4">
                        <p class="text-sm text-slate-500">No. Porsi</p>
                        <p class="text-base font-medium text-slate-900">{{ $order->orderBimbinganDetail->nomor_porsi ?? '-' }}</p>
                      </div>
                      <div class="space-y-2 rounded-2xl bg-white p-4">
                        <p class="text-sm text-slate-500">Kelas Bimbingan</p>
                        <p class="text-base font-medium text-slate-900">{{ optional($order->orderBimbinganDetail->kelasBimbingan)->nama_kelas ?? '-' }}</p>
                      </div>
                    </div>
                  @elseif ($order->paket->type === 'UMROH' && $order->orderUmrohDetail)
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                      <div class="space-y-2 rounded-2xl bg-white p-4">
                        <p class="text-sm text-slate-500">Tanggal Keberangkatan</p>
                        <p class="text-base font-medium text-slate-900">{{ optional($order->orderUmrohDetail->tanggal_keberangkatan)->format('d M Y') ?? '-' }}</p>
                      </div>
                      <div class="space-y-2 rounded-2xl bg-white p-4">
                        <p class="text-sm text-slate-500">Kelas Bimbingan</p>
                        <p class="text-base font-medium text-slate-900">{{ optional($order->orderUmrohDetail->kelasBimbingan)->nama_kelas ?? '-' }}</p>
                      </div>
                    </div>
                  @else
                    <p class="mt-4 text-sm text-slate-600">Detail kelas belum tersedia.</p>
                  @endif
                </div>
              </section>

              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                  <div>
                    <h3 class="text-lg font-semibold text-slate-900">Dokumen Jamaah</h3>
                    <p class="mt-1 text-sm text-slate-600">Periksa status dan file dokumen Anda.</p>
                  </div>
                </div>

                @php
                  $dokumenLabels = [
                    'ktp' => 'KTP',
                    'kk' => 'KK',
                    'surat_nikah' => 'Surat Nikah',
                    'foto' => 'Foto',
                    'passport' => 'Passport',
                  ];

                  $dokumenByJenis = optional($order->jamaah)->dokumenJamaahs
                      ? optional($order->jamaah)->dokumenJamaahs->keyBy('jenis')
                      : collect();
                @endphp

                <div class="mt-6 grid gap-4">
                  @foreach ($dokumenLabels as $jenis => $label)
                    @php
                      $dok = $dokumenByJenis->get($jenis);
                      $fileUrl = $dok?->file_path ? asset('storage/' . $dok->file_path) : null;
                      $status = $dok?->status;
                      $dbadge = $status
                          ? match ($status) {
                              'proses' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-900', 'ring' => 'ring-yellow-200'],
                              'verify' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-900', 'ring' => 'ring-emerald-200'],
                              'reject' => ['bg' => 'bg-red-50', 'text' => 'text-red-900', 'ring' => 'ring-red-200'],
                              default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'ring' => 'ring-slate-200'],
                            }
                          : null;
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-1">
                          <p class="text-sm font-semibold text-slate-900">{{ $label }}</p>
                          <p class="text-sm text-slate-600">{{ $fileUrl ? 'Tersedia' : 'Belum diunggah' }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                          @if ($fileUrl)
                            <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                              Lihat File
                            </a>
                          @endif

                          @if ($status && $dbadge)
                            <span class="inline-flex items-center rounded-full px-3 py-2 text-sm font-semibold {{ $dbadge['bg'] }} {{ $dbadge['text'] }} ring-1 ring-inset {{ $dbadge['ring'] }}">
                              {{ strtoupper($status) }}
                            </span>
                          @endif
                        </div>
                      </div>

                      @if ($dok && $dok->status === 'reject' && $dok->alasan_penolakan)
                        <div class="mt-3 rounded-2xl bg-red-50 p-3 text-sm text-red-700">
                          <span class="font-semibold">Alasan penolakan:</span> {{ $dok->alasan_penolakan }}
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>
              </section>
            </div>

            <aside class="space-y-6">
              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Pembayaran</h3>
                <p class="mt-1 text-sm text-slate-600">Status DP dan cicilan saat ini.</p>

                <div class="mt-6 rounded-3xl bg-slate-50 p-4">
                  <div class="flex items-center justify-between text-sm text-slate-500">
                    <span>Persentase Bayar</span>
                    <span>{{ $totalTagihan ? round(min(100, $totalDibayar / max(1, $totalTagihan) * 100)) : 0 }}%</span>
                  </div>
                  <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">
                    <div class="h-2 rounded-full bg-green-600" style="width: {{ $totalTagihan ? min(100, round($totalDibayar / max(1, $totalTagihan) * 100)) : 0 }}%"></div>
                  </div>
                </div>

                @if (!($dpAlreadyPaid ?? false))
                  <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex items-center justify-between gap-3">
                      <div>
                        <p class="text-sm text-slate-500">Pembayaran DP</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900">Rp {{ number_format((int) ($dpAmount ?? 0), 0, ',', '.') }}</p>
                      </div>
                      <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Belum Lunas</span>
                    </div>
                    <p class="mt-4 text-sm text-slate-600">{{ ($dokumenVerified ?? false) ? 'Dokumen sudah diverifikasi.' : 'Dokumen belum lengkap atau belum terverifikasi.' }}</p>
                    <p class="mt-2 text-sm text-slate-600">Status DP: Belum dibayar</p>
                    <div class="mt-4">
                      @if (($canPayDp ?? false) && !empty($midtransClientKey))
                        <button
                          type="button"
                          id="payDpBtn"
                          class="inline-flex w-full items-center justify-center rounded-full bg-green-700 px-4 py-3 text-sm font-semibold text-white hover:bg-green-600"
                        >
                          Bayar DP
                        </button>
                        <p class="mt-2 text-xs text-slate-500">Setelah sukses, status akan ter-update otomatis.</p>
                      @elseif (empty($midtransClientKey))
                        <div class="text-sm text-red-700">MIDTRANS_CLIENT_KEY belum diset di .env</div>
                      @else
                        <div class="text-sm text-slate-600">DP hanya bisa dibayar setelah dokumen diverifikasi.</div>
                      @endif
                    </div>
                  </div>
                @endif

                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-5">
                  <div class="flex items-center justify-between gap-3">
                    <div>
                      <p class="text-sm text-slate-500">Cicilan</p>
                      <p class="mt-2 text-base font-semibold text-slate-900">Bayar secara fleksibel</p>
                    </div>
                    <a href="{{ route('order.pembayaran.history', $order->id) }}" class="text-sm font-semibold text-green-700 hover:text-green-600">Riwayat</a>
                  </div>

                  @if($order->status !== 'done')
                    <div class="mt-5 space-y-3">
                      <label for="cicilanAmount" class="text-sm font-medium text-slate-700">Nominal cicilan (Rp)</label>
                      <input
                        type="number"
                        inputmode="numeric"
                        id="cicilanAmount"
                        min="1"
                        class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-200"
                        placeholder="1000000"
                      />
                      <button
                        type="button"
                        id="payCicilanBtn"
                        class="inline-flex w-full items-center justify-center rounded-full bg-green-700 px-4 py-3 text-sm font-semibold text-white hover:bg-green-600"
                      >
                        Bayar Cicilan
                      </button>
                      <p class="text-xs text-slate-500">Cicilan hanya dapat dibayar jika DP sudah <span class="font-semibold">verify</span>.</p>
                    </div>
                  @else
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Order sudah selesai, tidak ada cicilan yang dapat dibayar.</div>
                  @endif
                </div>
              </section>

              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Kelas Bimbingan</h3>
                <div>
                    <a href="{{ route('order.kelas', $order->id) }}" class="text-sm font-semibold text-green-700 hover:text-green-600 block mt-1">Lihat</a>
                </div>
              </section>
              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Informasi Paket</h3>
                <div class="mt-5 grid gap-4">
                  <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Nama Paket</p>
                    <p class="mt-2 text-base font-medium text-slate-900">{{ optional($order->paket)->nama_paket ?? '-' }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Tipe</p>
                    <p class="mt-2 text-base font-medium text-slate-900">{{ optional($order->paket)->type ?? '-' }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Harga</p>
                    <p class="mt-2 text-base font-medium text-slate-900">Rp {{ number_format((int) ($order->paket->harga ?? 0), 0, ',', '.') }}</p>
                  </div>
                </div>
              </section>

              <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Akun Pemesan</h3>
                <div class="mt-5 grid gap-4">
                  <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Nama</p>
                    <p class="mt-2 text-base font-medium text-slate-900">{{ optional($order->user)->name ?? '-' }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Email</p>
                    <p class="mt-2 text-base font-medium text-slate-900">{{ optional($order->user)->email ?? '-' }}</p>
                  </div>
                </div>
              </section>
            </aside>
          </div>
        </div>
      </div>
    </main>

    @if (!empty($midtransClientKey))
      <script src="{{ $midtransSnapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
      <script>
        (function () {
          const payBtn = document.getElementById('payDpBtn');
          if (!payBtn) return;

          const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          const orderId = @json($order->id);

          const setLoading = (isLoading) => {
            payBtn.disabled = isLoading;
            payBtn.textContent = isLoading ? 'Memproses...' : 'Bayar DP';
            payBtn.classList.toggle('opacity-60', isLoading);
            payBtn.classList.toggle('cursor-not-allowed', isLoading);
          };

          payBtn.addEventListener('click', async function () {
            try {
              setLoading(true);

              const res = await fetch(`/order/${orderId}/dp/snap-token`, {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrf || '',
                },
                body: JSON.stringify({}),
              });

              const data = await res.json().catch(() => ({}));
              if (!res.ok) {
                throw new Error(data.message || 'Gagal membuat snap token');
              }

              const snapToken = data.snap_token;
              if (!snapToken || !window.snap) {
                throw new Error('Snap tidak siap / token kosong');
              }

              window.snap.pay(snapToken, {
                onSuccess: async function (result) {
                  try {
                    await fetch(`/order/${orderId}/dp/success`, {
                      method: 'POST',
                      headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf || '',
                      },
                      body: JSON.stringify(result || {}),
                    });
                  } finally {
                    window.location.reload();
                  }
                },
                onPending: function () {
                  alert('Pembayaran pending. Silakan selesaikan pembayaran.');
                  setLoading(false);
                },
                onError: function () {
                  alert('Pembayaran gagal. Coba lagi.');
                  setLoading(false);
                },
                onClose: function () {
                  setLoading(false);
                },
              });
            } catch (e) {
              alert(e?.message || 'Terjadi kesalahan');
              setLoading(false);
            }
          });
        })();

        (function () {
          const payBtn = document.getElementById('payCicilanBtn');
          const amountInput = document.getElementById('cicilanAmount');
          if (!payBtn || !amountInput) return;

          const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          const orderId = @json($order->id);

          const setLoading = (isLoading) => {
            payBtn.disabled = isLoading;
            payBtn.textContent = isLoading ? 'Memproses...' : 'Bayar Cicilan';
            payBtn.classList.toggle('opacity-60', isLoading);
            payBtn.classList.toggle('cursor-not-allowed', isLoading);
          };

          payBtn.addEventListener('click', async function () {
            try {
              const amount = parseInt(amountInput.value || '0', 10);
              if (!Number.isFinite(amount) || amount <= 0) {
                alert('Nominal cicilan tidak valid');
                return;
              }

              setLoading(true);

              const res = await fetch(`/order/${orderId}/cicilan/snap-token`, {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrf || '',
                },
                body: JSON.stringify({ amount }),
              });

              const data = await res.json().catch(() => ({}));
              if (!res.ok) {
                throw new Error(data.message || 'Gagal membuat snap token');
              }

              const snapToken = data.snap_token;
              if (!snapToken || !window.snap) {
                throw new Error('Snap tidak siap / token kosong');
              }

              window.snap.pay(snapToken, {
                onSuccess: async function (result) {
                  try {
                    await fetch(`/order/${orderId}/cicilan/success`, {
                      method: 'POST',
                      headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf || '',
                      },
                      body: JSON.stringify(result || {}),
                    });
                  } finally {
                    window.location.reload();
                  }
                },
                onPending: function () {
                  alert('Pembayaran pending. Silakan selesaikan pembayaran.');
                  setLoading(false);
                },
                onError: function () {
                  alert('Pembayaran gagal. Coba lagi.');
                  setLoading(false);
                },
                onClose: function () {
                  setLoading(false);
                },
              });
            } catch (e) {
              alert(e?.message || 'Terjadi kesalahan');
              setLoading(false);
            }
          });
        })();
      </script>
    @endif

    <x-footer />
  </body>
</html>

