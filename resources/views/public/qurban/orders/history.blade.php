@extends('layouts.app')

@section('title', 'Pesanan Kurban Saya')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-4xl px-4">
            <!-- Header -->
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <span
                        class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Riwayat</span>
                    <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Pesanan <span
                            class="text-green-600">Kurban</span></h2>
                    <p class="mt-1 text-gray-500">Semua pesanan kurban yang pernah Anda lakukan</p>
                </div>
                <a href="{{ route('public.qurban.index') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700 hover:shadow-lg">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Pesan Kurban
                </a>
            </div>

            <!-- Filter Status -->
            <div class="mb-6 rounded-2xl bg-white p-4 shadow-md">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <div class="min-w-[200px]">
                        <label for="status" class="mb-1 block text-xs font-medium text-gray-600">Filter Status</label>
                        <div class="flex flex-wrap items-center gap-2">
                            <select name="status" id="status" onchange="this.form.submit()"
                                class="flex-1 rounded-xl border border-gray-300 px-4 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Status</option>
                                <option value="pending" @selected(request('status') === 'pending')>Menunggu Pembayaran</option>
                                <option value="awaiting_verification" @selected(request('status') === 'awaiting_verification')>Menunggu Verifikasi
                                </option>
                                <option value="success" @selected(request('status') === 'success')>Berhasil</option>
                                <option value="failed" @selected(request('status') === 'failed')>Gagal</option>
                                <option value="expired" @selected(request('status') === 'expired')>Kedaluwarsa</option>
                            </select>
                            @if (request()->has('status') && request('status') !== '')
                                <a href="{{ route('public.qurban.orders.history') }}"
                                    class="inline-flex items-center gap-1 rounded-xl border border-gray-300 px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-50 hover:text-gray-800">
                                    <i class="fas fa-times" aria-hidden="true"></i>
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Tampilkan status filter aktif --}}
                    @if (request('status'))
                        <div class="ml-auto text-sm text-gray-500">
                            <span class="font-medium">Filter:</span>
                            @php
                                $statusLabel = match (request('status')) {
                                    'pending' => 'Menunggu Pembayaran',
                                    'awaiting_verification' => 'Menunggu Verifikasi',
                                    'success' => 'Berhasil',
                                    'failed' => 'Gagal',
                                    'expired' => 'Kedaluwarsa',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs text-green-700">
                                <i class="fas fa-filter text-[10px]" aria-hidden="true"></i>
                                {{ $statusLabel }}
                            </span>
                            <span class="text-gray-400">({{ $qurbanOrders->total() }} ditemukan)</span>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold">No. Invoice</th>
                                <th class="px-4 py-3 font-semibold">Hewan</th>
                                <th class="px-4 py-3 font-semibold">Jenis Pesanan</th>
                                <th class="px-4 py-3 text-right font-semibold">Nominal</th>
                                <th class="px-4 py-3 font-semibold">Metode</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($qurbanOrders as $order)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-700">{{ $order->invoice_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $order->animal->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $order->order_type === 'full' ? 'Penuh' : 'Patungan' }}</td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $order->payment_method === 'midtrans' ? 'Online' : ($order->payment_method === 'manual_transfer' ? 'Transfer Manual' : '-') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusLabel = match ($order->payment_status) {
                                                'pending' => 'Menunggu Pembayaran',
                                                'awaiting_verification' => 'Menunggu Verifikasi',
                                                'success' => 'Berhasil',
                                                'failed' => 'Gagal',
                                                'expired' => 'Kedaluwarsa',
                                            };
                                            $statusColor = match ($order->payment_status) {
                                                'pending', 'awaiting_verification' => 'bg-amber-100 text-amber-700',
                                                'success' => 'bg-green-100 text-green-700',
                                                'failed', 'expired' => 'bg-red-100 text-red-700',
                                            };
                                        @endphp
                                        <span
                                            class="{{ $statusColor }} inline-block rounded-full px-2.5 py-1 text-xs font-medium">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if (in_array($order->payment_status, ['pending', 'awaiting_verification']))
                                            <a href="{{ route('public.qurban.orders.pay', $order) }}"
                                                class="font-medium text-green-600 transition hover:text-green-800">
                                                Lihat
                                            </a>
                                        @elseif($order->payment_status === 'success')
                                            <div class="flex flex-wrap items-center justify-end gap-2">
                                                <a href="{{ route('public.qurban.orders.receipt', $order) }}"
                                                    class="text-xs font-medium text-green-600 transition hover:text-green-800">
                                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Kuitansi
                                                </a>
                                                @if ($order->animal && $order->animal->status === 'slaughtered')
                                                    <a href="{{ route('public.qurban.orders.certificate', $order) }}"
                                                        class="text-xs font-medium text-amber-600 transition hover:text-amber-800">
                                                        <i class="fas fa-certificate" aria-hidden="true"></i> Sertifikat
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                        <i class="fas fa-inbox mb-3 block text-4xl" aria-hidden="true"></i>
                                        @if (request('status'))
                                            <p class="text-sm">Tidak ada pesanan dengan status "{{ $statusLabel ?? '' }}".
                                            </p>
                                        @else
                                            <p class="text-sm">Belum ada pesanan kurban.</p>
                                        @endif
                                        <a href="{{ route('public.qurban.index') }}"
                                            class="mt-2 inline-block text-sm font-medium text-green-600 hover:text-green-800">
                                            Lihat Katalog Kurban →
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $qurbanOrders->links() }}
            </div>
        </div>
    </section>
@endsection
