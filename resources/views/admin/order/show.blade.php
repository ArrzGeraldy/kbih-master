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
    @vite(['resources/css/app.css', 'resources/js/app.js',
    'resources/js/dashboard-admin.js'])
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
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between mb-6">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">
                  Detail Order #{{ $order->id }}
                </h2>
                <p class="text-sm text-gray-600">
                  Cek data jamaah dan dokumen yang dikirim.
                </p>
              </div>

              <div class="flex flex-wrap items-center gap-3">
                @if (optional($order->paket)->type === 'BIMBINGAN_HAJI')
                  <a
                    href="{{ route('admin.orders.edit-bimbingan', $order) }}"
                    class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800"
                  >
                    Edit Bimbingan
                  </a>
                @elseif (optional($order->paket)->type === 'UMROH')
                  <a
                    href="{{ route('admin.orders.edit-umroh', $order) }}"
                    class="inline-flex items-center rounded-md bg-green-900 px-4 py-2 text-sm font-medium text-white hover:bg-green-800"
                  >
                    Edit Umroh
                  </a>
                @endif
                <a
                  href="{{ route('admin.orders.index') }}"
                  class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                  Kembali
                </a>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <div class="lg:col-span-2 space-y-6">
                {{-- ringkasan order --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                  <h3 class="font-semibold text-gray-900">Ringkasan Order</h3>
                  <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <div class="text-sm text-gray-600">Status</div>
                      <div class="mt-1">
                        @php
                          $statusBadge = match ($order->status) {
                            'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                            'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-900', 'ring' => 'ring-yellow-200'],
                            'active' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-900', 'ring' => 'ring-blue-200'],
                            'done' => ['bg' => 'bg-green-50', 'text' => 'text-green-900', 'ring' => 'ring-green-200'],
                            'cancel' => ['bg' => 'bg-red-50', 'text' => 'text-red-900', 'ring' => 'ring-red-200'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                          };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} ring-1 ring-inset {{ $statusBadge['ring'] }}">
                          {{ strtoupper($order->status) }}
                        </span>
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">Total Tagihan</div>
                      <div class="mt-1 font-medium text-gray-900">
                        Rp
                        {{ number_format((int) $order->total_tagihan, 0, ',', '.') }}
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">Total Dibayar</div>
                      <div class="mt-1 font-medium text-gray-900">
                        Rp
                        {{ number_format((int) $order->total_dibayar, 0, ',', '.') }}
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">Dibuat</div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($order->created_at)->format('d M Y H:i') }}
                      </div>
                    </div>
                  </div>
                </div>
                <!-- data jamaah -->
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                  <h3 class="font-semibold text-gray-900">Data Jamaah</h3>

                  <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <div class="text-sm text-gray-600">Nama Lengkap</div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($order->jamaah)->nama_lengkap ?? '-' }}
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">NIK</div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($order->jamaah)->nik ?? '-' }}
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">Jenis Kelamin</div>
                      <div class="mt-1">
                        @php
                          $genderBadge = (optional($order->jamaah)->jenis_kelamin === 'L') 
                            ? ['bg' => 'bg-blue-50', 'text' => 'text-blue-900', 'ring' => 'ring-blue-200', 'label' => 'Laki-laki']
                            : ['bg' => 'bg-pink-50', 'text' => 'text-pink-900', 'ring' => 'ring-pink-200', 'label' => 'Perempuan'];
                        @endphp
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $genderBadge['bg'] }} {{ $genderBadge['text'] }} ring-1 ring-inset {{ $genderBadge['ring'] }}">
                          {{ $genderBadge['label'] ?? '-' }}
                        </span>
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">No. Telepon</div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($order->jamaah)->no_tlpn ?? '-' }}
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">
                        Tempat, Tanggal Lahir
                      </div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($order->jamaah)->tempat_lahir ?? '-' }},
                        {{ optional(optional($order->jamaah)->tanggal_lahir)->format('d M Y') ?? '-' }}
                      </div>
                    </div>
                    <div>
                      <div class="text-sm text-gray-600">Status Jamaah</div>
                      <div class="mt-1">
                        @php
                          $jamaahStatusBadge = match (optional($order->jamaah)->status) {
                            'aktif' => ['bg' => 'bg-green-50', 'text' => 'text-green-900', 'ring' => 'ring-green-200'],
                            'nonaktif' => ['bg' => 'bg-red-50', 'text' => 'text-red-900', 'ring' => 'ring-red-200'],
                            'proses' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-900', 'ring' => 'ring-yellow-200'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                          };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $jamaahStatusBadge['bg'] }} {{ $jamaahStatusBadge['text'] }} ring-1 ring-inset {{ $jamaahStatusBadge['ring'] }}">
                          {{ optional($order->jamaah)->status ?? '-' }}
                        </span>
                      </div>
                    </div>
                    <div class="md:col-span-2">
                      <div class="text-sm text-gray-600">Alamat</div>
                      <div class="mt-1 font-medium text-gray-900">
                        {{ optional($order->jamaah)->alamat ?? '-' }}
                      </div>
                    </div>
                  </div>
                  <!-- detail bimbingan / umroh -->
                  @if ($order->paket->type === 'BIMBINGAN_HAJI' && $order->orderBimbinganDetail)
                    <div class="mt-6">
                      <h4 class="font-semibold text-gray-900">
                        Detail Bimbingan
                      </h4>
                      <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <div class="text-sm text-gray-600">No. Porsi</div>
                          <div class="mt-1 font-medium text-gray-900">
                            {{ $order->orderBimbinganDetail->nomor_porsi ?? '-' }}
                          </div>
                        </div>
                        <div>
                          <div class="text-sm text-gray-600">Kelas Bimbingan</div>
                          <div class="mt-1 font-medium text-gray-900">
                            {{ optional($order->orderBimbinganDetail->kelasBimbingan)->nama_kelas ?? '-' }}
                          </div>
                        </div>
                      </div>
                    </div>
                  @elseif ($order->paket->type === 'UMROH' && $order->orderUmrohDetail)
                    <div class="mt-6">
                      <h4 class="font-semibold text-gray-900">
                        Detail Umroh
                      </h4>
                      <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <div class="text-sm text-gray-600">Tanggal Keberangkatan</div>
                          <div class="mt-1 font-medium text-gray-900">
                            {{ optional($order->orderUmrohDetail->tanggal_keberangkatan)->format('d M Y') ?? '-' }}
                          </div>
                        </div>
                        <div>
                          <div class="text-sm text-gray-600">Kelas Bimbingan</div>
                          <div class="mt-1 font-medium text-gray-900">
                            {{ optional($order->orderUmrohDetail->kelasBimbingan)->nama_kelas ?? '-' }}
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>

                <!-- dokumen -->
                <div
                  class="rounded-lg border border-gray-200 bg-white overflow-hidden p-6"
                >
                  <h3 class="font-semibold text-gray-900">Dokumen Jamaah</h3>

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

                  <div class="mt-4 space-y-4">
                    @foreach ($dokumenLabels as $jenis => $label)
                      @php
                        $dok = $dokumenByJenis->get($jenis);
                        $fileUrl = $dok?->file_path ? asset('storage/' . $dok->file_path) : null;
                        $status = $dok?->status;
                      @endphp

                      <div class="border border-gray-300 rounded-lg p-4">
                        <div class="flex justify-between items-center gap-4">
                          <div class="flex gap-2 items-center">
                            <h1 class="font-semibold text-gray-900">{{ $label }}</h1>

                            @if ($fileUrl)
                              <a
                                href="{{ $fileUrl }}"
                                target="_blank"
                                rel="noopener"
                                class="underline text-blue-500 text-sm"
                              >
                                Lihat
                              </a>
                            @else
                              <span class="text-sm text-gray-500">Belum ada file</span>
                            @endif

                            @if ($status)
                              @php
                                $dbadge = match ($status) {
                                  'proses' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-900', 'ring' => 'ring-yellow-200'],
                                  'verify' => ['bg' => 'bg-green-50', 'text' => 'text-green-900', 'ring' => 'ring-green-200'],
                                  'reject' => ['bg' => 'bg-red-50', 'text' => 'text-red-900', 'ring' => 'ring-red-200'],
                                  default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                                };
                              @endphp
                              <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $dbadge['bg'] }} {{ $dbadge['text'] }} ring-1 ring-inset {{ $dbadge['ring'] }}">
                                {{ strtoupper($status) }}
                              </span>
                            @endif
                          </div>
                        </div>

                        @if ($dok && $dok->status !== 'verify')
                          <form class="mt-3" method="POST" action="{{ route('admin.dokumen.update', $dok) }}">
                            @csrf
                            @method('PATCH')

                            <input
                              type="text"
                              name="alasan_penolakan"
                              class="w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                              placeholder="Alasan penolakan (wajib jika ditolak)"
                              value="{{ old('alasan_penolakan', $dok->alasan_penolakan) }}"
                            />

                            <div class="mt-3 flex gap-2 justify-end items-center">
                              <button
                                type="submit"
                                name="action"
                                value="reject"
                                class="text-sm font-medium bg-red-500 text-white rounded-md px-3 py-1.5"
                              >
                                Tolak
                              </button>
                              <button
                                type="submit"
                                name="action"
                                value="verify"
                                class="text-sm font-medium bg-green-900 text-white rounded-md px-3 py-1.5"
                              >
                                Verify
                              </button>
                            </div>
                          </form>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>

              <div class="space-y-6">
                {{-- paket --}}
                <div class="rounded-lg border border-gray-200 bg-white p-6">
                  <h3 class="font-semibold text-gray-900">Paket</h3>
                  <div class="mt-4">
                    <div class="text-sm text-gray-600">Nama Paket</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ optional($order->paket)->nama_paket ?? '-' }}
                    </div>
                  </div>
                  <div class="mt-4">
                    <div class="text-sm text-gray-600">Tipe</div>
                    <div class="mt-1">
                      @php
                        $typeBadge = (optional($order->paket)->type === 'BIMBINGAN_HAJI')
                          ? ['bg' => 'bg-purple-50', 'text' => 'text-purple-900', 'ring' => 'ring-purple-200', 'label' => 'Bimbingan Haji']
                          : ['bg' => 'bg-orange-50', 'text' => 'text-orange-900', 'ring' => 'ring-orange-200', 'label' => 'Umroh'];
                      @endphp
                      <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $typeBadge['bg'] }} {{ $typeBadge['text'] }} ring-1 ring-inset {{ $typeBadge['ring'] }}">
                        {{ $typeBadge['label'] ?? '-' }}
                      </span>
                    </div>
                  </div>
                  <div class="mt-4">
                    <div class="text-sm text-gray-600">Harga</div>
                    <div class="mt-1 font-medium text-gray-900">
                      Rp
                      {{ number_format((int) ($order->paket->harga ?? 0), 0, ',', '.') }}
                    </div>
                  </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6">
                  <h3 class="font-semibold text-gray-900">Akun Pemesan</h3>
                  <div class="mt-4">
                    <div class="text-sm text-gray-600">Nama</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ optional($order->user)->name ?? '-' }}
                    </div>
                  </div>
                  <div class="mt-4">
                    <div class="text-sm text-gray-600">Email</div>
                    <div class="mt-1 font-medium text-gray-900">
                      {{ optional($order->user)->email ?? '-' }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
