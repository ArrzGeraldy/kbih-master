<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Order #{{ $order->id ?? '' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Detail Order</h3>
                    <p class="text-sm text-gray-600">Lihat ringkasan order, data jamaah, dan status dokumen.</p>
                </div>
                <a
                    href="{{ url()->previous() }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    {{-- ringkasan order --}}
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <h4 class="font-semibold text-gray-900">Ringkasan Order</h4>

                        @php
                            $totalTagihan = (int) ($order->total_tagihan ?? 0);
                            $totalDibayar = (int) ($order->total_dibayar ?? 0);
                            $sisaTagihan = max(0, $totalTagihan - $totalDibayar);
                        @endphp

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Status</div>
                                <div class="mt-1 font-medium text-gray-900">{{ strtoupper((string) $order->status) }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Total Tagihan</div>
                                <div class="mt-1 font-medium text-gray-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Total Dibayar</div>
                                <div class="mt-1 font-medium text-gray-900">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Sisa Tagihan</div>
                                <div class="mt-1 font-medium text-gray-900">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Dibuat</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->created_at)->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    {{-- data jamaah --}}
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <h4 class="font-semibold text-gray-900">Data Jamaah</h4>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Nama Lengkap</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->jamaah)->nama_lengkap ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">NIK</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->jamaah)->nik ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Jenis Kelamin</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->jamaah)->jenis_kelamin ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">No. Telepon</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->jamaah)->no_tlpn ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Tempat, Tanggal Lahir</div>
                                <div class="mt-1 font-medium text-gray-900">
                                    {{ optional($order->jamaah)->tempat_lahir ?? '-' }},
                                    {{ optional(optional($order->jamaah)->tanggal_lahir)->format('d M Y') ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Status Jamaah</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->jamaah)->status ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-sm text-gray-600">Alamat</div>
                                <div class="mt-1 font-medium text-gray-900">{{ optional($order->jamaah)->alamat ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h5 class="font-semibold text-gray-900">Detail Bimbingan / Umroh</h5>
                            
                            @if ($order->paket->type === 'BIMBINGAN_HAJI' && $order->orderBimbinganDetail)
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm text-gray-600">No. Porsi</div>
                                        <div class="mt-1 font-medium text-gray-900">{{ $order->orderBimbinganDetail->nomor_porsi ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Kelas Bimbingan</div>
                                        <div class="mt-1 font-medium text-gray-900">{{ optional($order->orderBimbinganDetail->kelasBimbingan)->nama_kelas ?? '-' }}</div>
                                    </div>
                                </div>
                            @elseif ($order->paket->type === 'UMROH' && $order->orderUmrohDetail)
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm text-gray-600">Tanggal Keberangkatan</div>
                                        <div class="mt-1 font-medium text-gray-900">{{ optional($order->orderUmrohDetail->tanggal_keberangkatan)->format('d M Y') ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Kelas Bimbingan</div>
                                        <div class="mt-1 font-medium text-gray-900">{{ optional($order->orderUmrohDetail->kelasBimbingan)->nama_kelas ?? '-' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- dokument jamaah --}}
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <h4 class="font-semibold text-gray-900">Dokumen Jamaah</h4>

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

                                    $dbadge = $status
                                        ? match ($status) {
                                            'proses' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-900', 'ring' => 'ring-yellow-200'],
                                            'verify' => ['bg' => 'bg-green-50', 'text' => 'text-green-900', 'ring' => 'ring-green-200'],
                                            'reject' => ['bg' => 'bg-red-50', 'text' => 'text-red-900', 'ring' => 'ring-red-200'],
                                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-200'],
                                        }
                                        : null;
                                @endphp

                                <div class="border border-gray-300 rounded-lg p-4">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="flex gap-2 items-center flex-wrap">
                                            <div class="font-semibold text-gray-900">{{ $label }}</div>

                                            @if ($fileUrl)
                                                <a
                                                    href="{{ $fileUrl }}"
                                                    target="_blank"
                                                    rel="noopener"
                                                    class="underline text-blue-600 text-sm"
                                                >
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-500">Belum ada file</span>
                                            @endif

                                            @if ($status && $dbadge)
                                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $dbadge['bg'] }} {{ $dbadge['text'] }} ring-1 ring-inset {{ $dbadge['ring'] }}">
                                                    {{ strtoupper($status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($dok && $dok->status === 'reject' && $dok->alasan_penolakan)
                                        <div class="mt-2 text-sm text-red-700">
                                            Alasan penolakan: {{ $dok->alasan_penolakan }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- dp (hide kalau sudah verify/terpenuhi) --}}
                    @if (!($dpAlreadyPaid ?? false))
                        <div class="rounded-lg border border-gray-200 bg-white p-6">
                            <h4 class="font-semibold text-gray-900">Pembayaran DP</h4>

                            <div class="mt-4">
                                <div class="text-sm text-gray-600">DP</div>
                                <div class="mt-1 font-medium text-gray-900">Rp {{ number_format((int) ($dpAmount ?? 0), 0, ',', '.') }}</div>
                            </div>

                            <div class="mt-4">
                                <div class="text-sm text-gray-600">Status Dokumen</div>
                                <div class="mt-1 font-medium text-gray-900">
                                    {{ ($dokumenVerified ?? false) ? 'Terverifikasi' : 'Belum lengkap / belum diverifikasi' }}
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="text-sm text-gray-600">Status DP</div>
                                <div class="mt-1 font-medium text-gray-900">Belum dibayar</div>
                            </div>

                            <div class="mt-5">
                                @if (($canPayDp ?? false) && !empty($midtransClientKey))
                                    <button
                                        type="button"
                                        id="payDpBtn"
                                        class="inline-flex w-full items-center justify-center rounded-md bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-600"
                                    >
                                        Bayar DP
                                    </button>
                                    <p class="mt-2 text-xs text-gray-500">
                                        Setelah sukses, status akan ter-update otomatis.
                                    </p>
                                @elseif (empty($midtransClientKey))
                                    <div class="text-sm text-red-700">
                                        MIDTRANS_CLIENT_KEY belum diset di .env
                                    </div>
                                @else
                                    <div class="text-sm text-gray-600">
                                        DP hanya bisa dibayar setelah dokumen diverifikasi.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-900">Pembayaran Cicilan</h4>
                            <a href="{{ route('order.pembayaran.history', $order->id) }}" class="block text-blue-600 underline text-xs hover:text-blue-500 transition-all">Riwayat Pembayaran</a>
                        </div>

                    @if($order->status !== 'done')
                        <div class="mt-4">
                            <div class="text-sm text-gray-600">Nominal cicilan (Rp)</div>
                            <input
                                type="number"
                                inputmode="numeric"
                                id="cicilanAmount"
                                min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-green-900 focus:ring-green-900"
                                placeholder="1000000"
                            />
                            <p class="mt-2 text-xs text-gray-500">Masukkan nominal lalu klik bayar.</p>
                            <div class="mt-5">
                                <button
                                    type="button"
                                    id="payCicilanBtn"
                                    class="inline-flex w-full items-center justify-center rounded-md bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-600"
                                >
                                    Bayar Cicilan
                                </button>
                                <p class="mt-2 text-xs text-gray-500">Cicilan hanya bisa jika DP sudah <span class="font-medium">verify</span>.</p>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-900">Kelas Bimbingan</h4>
                            @if (($order->orderBimbinganDetail && $order->paket->type === 'BIMBINGAN_HAJI') || ($order->orderUmrohDetail && $order->paket->type === 'UMROH'))
                                <a href="{{ route('order.kelas', $order->id) }}" class="block text-blue-600 underline text-xs hover:text-blue-500 transition-all">Lihat Detail</a>
                            @endif
                        </div>

                        @php
                            $kelasBimbingan = null;
                            if ($order->paket->type === 'BIMBINGAN_HAJI' && $order->orderBimbinganDetail) {
                                $kelasBimbingan = optional($order->orderBimbinganDetail)->kelasBimbingan;
                            } elseif ($order->paket->type === 'UMROH' && $order->orderUmrohDetail) {
                                $kelasBimbingan = optional($order->orderUmrohDetail)->kelasBimbingan;
                            }
                        @endphp

                        @if ($kelasBimbingan)
                            <div class="mt-4">
                                <div class="text-sm text-gray-600">Nama Kelas</div>
                                <div class="mt-1 font-medium text-gray-900">{{ $kelasBimbingan->nama_kelas }}</div>
                            </div>
                            @if ($kelasBimbingan->nama_pembimbing)
                                <div class="mt-4">
                                    <div class="text-sm text-gray-600">Pembimbing</div>
                                    <div class="mt-1 font-medium text-gray-900">{{ $kelasBimbingan->nama_pembimbing }}</div>
                                </div>
                            @endif
                            <div class="mt-4">
                                <div class="text-sm text-gray-600">Periode</div>
                                <div class="mt-1 font-medium text-gray-900">
                                    {{ optional($kelasBimbingan->mulai_periode)->format('d M Y') ?? '-' }}
                                    s/d
                                    {{ optional($kelasBimbingan->selesai_periode)->format('d M Y') ?? '-' }}
                                </div>
                            </div>
                        @else
                            <div class="mt-4 text-sm text-gray-600">Belum ada kelas bimbingan yang ditugaskan.</div>
                        @endif
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <h4 class="font-semibold text-gray-900">Paket</h4>

                        <div class="mt-4">
                            <div class="text-sm text-gray-600">Nama Paket</div>
                            <div class="mt-1 font-medium text-gray-900">{{ optional($order->paket)->nama_paket ?? '-' }}</div>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm text-gray-600">Tipe</div>
                            <div class="mt-1 font-medium text-gray-900">{{ optional($order->paket)->type ?? '-' }}</div>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm text-gray-600">Harga</div>
                            <div class="mt-1 font-medium text-gray-900">Rp {{ number_format((int) ($order->paket->harga ?? 0), 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-6">
                        <h4 class="font-semibold text-gray-900">Akun Pemesan</h4>

                        <div class="mt-4">
                            <div class="text-sm text-gray-600">Nama</div>
                            <div class="mt-1 font-medium text-gray-900">{{ optional($order->user)->name ?? '-' }}</div>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="mt-1 font-medium text-gray-900">{{ optional($order->user)->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                    body: JSON.stringify({})
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
                                                body: JSON.stringify(result || {})
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
                                    }
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
                                    body: JSON.stringify({ amount })
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
                                                body: JSON.stringify(result || {})
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
                                    }
                                });
                            } catch (e) {
                                alert(e?.message || 'Terjadi kesalahan');
                                setLoading(false);
                            }
                        });
                    })();
                </script>
        @endif
</x-app-layout>
