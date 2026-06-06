<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden ">
                <div class="p-6 text-gray-900 ">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold">Order Saya</h3>
                            <p class="text-sm text-gray-600 ">Daftar paket yang pernah kamu pesan.</p>
                        </div>
                    </div>

                    <div class="mt-6  grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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

                            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm ">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <div class="text-base font-semibold text-gray-900 truncate">
                                                {{ $order->paket?->nama_paket ?? ('Paket #' . $order->paket_id) }}
                                            </div>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset {{ $badgeClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-600">
                                            Jamaah: {{ $order->jamaah?->nama_lengkap ?? '-' }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Order #{{ $order->id }} • {{ $order->created_at?->format('d M Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Total Tagihan</div>
                                        <div class="text-base font-semibold text-gray-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <a href="/order/{{ $order->id }}" class="block bg-green-700 text-sm rounded-md px-3 py-1.5 text-white w-fit mt-4 hover:bg-green-600 transition-all">Lihat Detail</a>
                            </div>
                        @empty
                            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                                <div class="text-sm font-medium text-gray-900">Belum ada order</div>
                                <div class="mt-1 text-sm text-gray-600">Silakan pilih paket dulu untuk membuat order.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
