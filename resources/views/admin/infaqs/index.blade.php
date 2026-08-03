@extends('layouts.admin')

@section('title', 'Daftar Infaq')

@section('content')

    <div class="mb-5 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total {{ $infaqs->total() }} transaksi infaq</p>
    </div>

    <form method="GET" class="mb-5 flex flex-wrap items-end gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
        <div class="min-w-[200px] flex-1">
            <label class="mb-1 block text-xs font-medium">Cari No. Transaksi</label>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Kategori</label>
            <select name="category" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Status</label>
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach (['pending' => 'Menunggu', 'awaiting_verification' => 'Menunggu Verifikasi', 'success' => 'Berhasil', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'] as $val => $lbl)
                    <option value="{{ $val }}" @selected(request('status') == $val)>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit"
                class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Terapkan</button>
            <a href="{{ route('admin.infaqs.index') }}"
                class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">No. Transaksi</th>
                    <th class="px-5 py-3">Donatur</th>
                    <th class="px-5 py-3">Kategori/Campaign</th>
                    <th class="px-5 py-3"><x-sortable-header field="amount" label="Nominal" /></th>
                    <th class="px-5 py-3"><x-sortable-header field="payment_status" label="Status" /></th>
                    <th class="px-5 py-3"><x-sortable-header field="created_at" label="Tanggal" /></th>
                </tr>
            </thead>
            <tbody>
                @forelse($infaqs as $infaq)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                {{-- Tombol Aksi untuk Transaksi Manual Transfer yang Menunggu Verifikasi --}}
                                @if ($infaq->payment_status === 'awaiting_verification' && $infaq->payment_method === 'manual_transfer')
                                    <form id="approve-infaq-{{ $infaq->id }}"
                                        action="{{ route('admin.donation-verifications.infaq.approve', $infaq) }}"
                                        method="POST" class="hidden">
                                        @csrf @method('PUT')
                                    </form>
                                    <button type="button"
                                        @click="$dispatch('open-modal-approve-infaq-{{ $infaq->id }}')"
                                        class="font-medium text-emerald-600 transition-colors hover:text-emerald-800">
                                        Setujui
                                    </button>
                                    <x-confirm-modal id="approve-infaq-{{ $infaq->id }}" title="Setujui Infaq"
                                        message="Konfirmasi infaq sebesar Rp {{ number_format($infaq->amount, 0, ',', '.') }} sebagai berhasil?"
                                        formId="approve-infaq-{{ $infaq->id }}" />

                                    <form id="reject-infaq-{{ $infaq->id }}"
                                        action="{{ route('admin.donation-verifications.infaq.reject', $infaq) }}"
                                        method="POST" class="hidden">
                                        @csrf @method('PUT')
                                    </form>
                                    <button type="button"
                                        @click="$dispatch('open-modal-reject-infaq-{{ $infaq->id }}')"
                                        class="font-medium text-red-600 transition-colors hover:text-red-800">
                                        Tolak
                                    </button>
                                    <x-confirm-modal id="reject-infaq-{{ $infaq->id }}" title="Tolak Infaq"
                                        message="Tolak konfirmasi infaq ini? Status akan diubah menjadi Gagal."
                                        formId="reject-infaq-{{ $infaq->id }}" />

                                    {{-- Transaksi Midtrans yang masih pending --}}
                                @elseif ($infaq->payment_method === 'midtrans' && $infaq->payment_status === 'pending')
                                    <span class="text-xs text-gray-400">Menunggu pembayaran</span>

                                    {{-- Transaksi Manual Transfer yang masih pending (belum upload bukti) --}}
                                @elseif ($infaq->payment_method === 'manual_transfer' && $infaq->payment_status === 'pending')
                                    <span class="text-xs text-gray-400">Menunggu bukti transfer</span>

                                    {{-- Status lain yang sudah final --}}
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada aksi</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3">{{ $infaq->transaction_number }}</td>
                        <td class="px-5 py-3">{{ $infaq->display_name }}</td>
                        <td class="px-5 py-3">{{ $infaq->category->name ?? ($infaq->campaign->title ?? 'Umum') }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @php
                                $color = match ($infaq->payment_status) {
                                    'success' => 'bg-emerald-100 text-emerald-700',
                                    'pending', 'awaiting_verification' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-red-100 text-red-700',
                                };
                            @endphp
                            <span
                                class="{{ $color }} rounded-full px-2 py-1 text-xs font-medium">{{ ucfirst(str_replace('_', ' ', $infaq->payment_status)) }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $infaq->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada data transaksi Infaq.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $infaqs->links() }}</div>
@endsection
