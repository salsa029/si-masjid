@extends('layouts.app')

@section('title', 'Riwayat Infaq Saya')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-10">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Riwayat Infaq Saya</h1>

        <form method="GET" class="mb-4">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach (['pending' => 'Menunggu Pembayaran', 'awaiting_verification' => 'Menunggu Verifikasi', 'success' => 'Berhasil', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'] as $val => $lbl)
                    <option value="{{ $val }}" @selected(request('status') == $val)>{{ $lbl }}</option>
                @endforeach
            </select>
        </form>

        <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-5 py-3">No. Transaksi</th>
                        <th class="px-5 py-3">Kategori/Campaign</th>
                        <th class="px-5 py-3">Nominal</th>
                        <th class="px-5 py-3">Metode</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($infaqs as $infaq)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-3 text-gray-500">{{ $infaq->transaction_number }}</td>
                            <td class="px-5 py-3 font-medium">
                                {{ $infaq->category->name ?? ($infaq->campaign->title ?? 'Umum') }}</td>
                            <td class="px-5 py-3">Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-gray-500">
                                {{ $infaq->payment_method === 'midtrans' ? 'Online' : 'Transfer Manual' }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $label = match ($infaq->payment_status) {
                                        'pending' => 'Menunggu Pembayaran',
                                        'awaiting_verification' => 'Menunggu Verifikasi',
                                        'success' => 'Berhasil',
                                        'failed' => 'Gagal',
                                        'expired' => 'Kedaluwarsa',
                                    };
                                    $color = match ($infaq->payment_status) {
                                        'pending', 'awaiting_verification' => 'bg-amber-100 text-amber-700',
                                        'success' => 'bg-emerald-100 text-emerald-700',
                                        'failed', 'expired' => 'bg-red-100 text-red-700',
                                    };
                                @endphp
                                <span
                                    class="{{ $color }} rounded-full px-2 py-1 text-xs font-medium">{{ $label }}</span>
                            </td>
                            <td class="space-x-2 px-5 py-3 text-right">
                                @if (in_array($infaq->payment_status, ['pending', 'awaiting_verification']))
                                    <a href="{{ route('public.infaq.pay', $infaq) }}"
                                        class="text-emerald-700 hover:underline">Lihat</a>
                                @elseif ($infaq->payment_status === 'success')
                                    <a href="{{ route('public.infaq.receipt', $infaq) }}"
                                        class="text-emerald-700 hover:underline">Kuitansi</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400">Anda belum pernah berinfaq.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $infaqs->links() }}</div>
    </div>
@endsection
