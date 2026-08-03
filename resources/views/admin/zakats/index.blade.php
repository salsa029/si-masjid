@extends('layouts.admin')

@section('title', 'Daftar Zakat')

@section('content')

    <div class="mb-5 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total {{ $zakats->total() }} transaksi zakat</p>
    </div>

    <form method="GET" class="mb-5 flex flex-wrap items-end gap-3 rounded-xl border border-gray-100 bg-white p-4 shadow-sm">
        <div class="min-w-[200px] flex-1">
            <label class="mb-1 block text-xs font-medium">Cari No. Transaksi</label>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium">Jenis Zakat</label>
            <select name="zakat_type_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Semua Jenis</option>
                @foreach ($zakatTypes as $type)
                    <option value="{{ $type->id }}" @selected(request('zakat_type_id') == $type->id)>{{ $type->name }}</option>
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
            <a href="{{ route('admin.zakats.index') }}"
                class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">No. Transaksi</th>
                    <th class="px-5 py-3">Muzakki</th>
                    <th class="px-5 py-3">Jenis Zakat</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zakats as $zakat)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                @if ($zakat->payment_status === 'awaiting_verification' && $zakat->payment_method === 'manual_transfer')
                                    <a href="{{ route('admin.donation-verifications.zakat.show', $zakat) }}"
                                        class="font-medium text-emerald-700 hover:underline">
                                        Tinjau Bukti
                                    </a>

                                    {{-- Form dan Tombol Setujui --}}
                                    <form id="approve-zakat-{{ $zakat->id }}"
                                        action="{{ route('admin.donation-verifications.zakat.approve', $zakat) }}"
                                        method="POST" class="hidden">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                    <button type="button"
                                        @click="$dispatch('open-modal-approve-zakat-{{ $zakat->id }}')"
                                        class="font-medium text-emerald-600 transition-colors hover:text-emerald-800">
                                        Setujui
                                    </button>
                                    <x-confirm-modal id="approve-zakat-{{ $zakat->id }}" title="Setujui Zakat"
                                        message="Konfirmasi zakat sebesar Rp {{ number_format($zakat->amount, 0, ',', '.') }} sebagai berhasil?"
                                        formId="approve-zakat-{{ $zakat->id }}" />
                                @elseif ($zakat->payment_method === 'midtrans' && $zakat->payment_status === 'pending')
                                    <span class="text-xs text-gray-400">Menunggu pembayaran</span>
                                @elseif ($zakat->payment_method === 'manual_transfer' && $zakat->payment_status === 'pending')
                                    <span class="text-xs text-gray-400">Menunggu bukti transfer</span>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada aksi</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3">{{ $zakat->transaction_number }}</td>
                        <td class="px-5 py-3">{{ $zakat->display_name }}</td>
                        <td class="px-5 py-3">{{ $zakat->zakatType->name }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($zakat->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @php
                                $color = match ($zakat->payment_status) {
                                    'success' => 'bg-emerald-100 text-emerald-700',
                                    'pending', 'awaiting_verification' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-red-100 text-red-700',
                                };
                                $label = match ($zakat->payment_status) {
                                    'pending' => 'Menunggu Pembayaran',
                                    'awaiting_verification' => 'Menunggu Verifikasi',
                                    'success' => 'Berhasil',
                                    'failed' => 'Gagal',
                                    'expired' => 'Kedaluwarsa',
                                };
                            @endphp
                            <span
                                class="{{ $color }} rounded-full px-2 py-1 text-xs font-medium">{{ $label }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $zakat->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada data transaksi Zakat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $zakats->links() }}</div>
@endsection
