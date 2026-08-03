@extends('layouts.admin')

@section('title', 'Dashboard Status Kurban')

@section('content')

    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Hewan Kurban</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $summary['total_animals'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Pesanan Sukses</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $summary['total_successful_orders'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Dana Terkumpul</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">Rp
                {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('admin.qurban-verifications.index') }}"
            class="rounded-xl border border-amber-100 bg-amber-50 p-5 shadow-sm transition hover:bg-amber-100">
            <p class="text-sm text-amber-700">Menunggu Verifikasi</p>
            <p class="mt-1 text-2xl font-bold text-amber-700">{{ $summary['pending_verifications'] }}</p>
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-3 text-sm font-semibold">Progress Kuota per Hewan</div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Hewan</th>
                    <th class="px-5 py-3">Jenis</th>
                    <th class="px-5 py-3">Progress</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($animals as $animal)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 font-medium">{{ $animal->name }}</td>
                        <td class="px-5 py-3 capitalize text-gray-500">{{ $animal->animal_type }}</td>
                        <td class="w-64 px-5 py-3">
                            <x-quota-progress :booked="$animal->booked_slots_count" :total="$animal->max_participants" />
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $label = match ($animal->status) {
                                    'available' => 'Tersedia',
                                    'fully_booked' => 'Kuota Penuh',
                                    'slaughtered' => 'Sudah Disembelih',
                                };
                                $color = match ($animal->status) {
                                    'available' => 'bg-emerald-100 text-emerald-700',
                                    'fully_booked' => 'bg-amber-100 text-amber-700',
                                    'slaughtered' => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span
                                class="{{ $color }} rounded-full px-2 py-1 text-xs font-medium">{{ $label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada data hewan kurban.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
