@extends('layouts.admin')

@section('title', 'Campaign Infaq')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total {{ $campaigns->total() }} campaign</p>
        <a href="{{ route('admin.infaq-campaigns.create') }}"
            class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
            + Tambah Campaign
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Progress Dana</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($campaigns as $campaign)
                    <tr class="border-t border-gray-100">
                        <td class="space-x-2 px-5 py-3">
                            <a href="{{ route('admin.infaq-campaigns.edit', $campaign) }}"
                                class="text-emerald-700 hover:underline">Ubah</a>
                            <form action="{{ route('admin.infaq-campaigns.destroy', $campaign) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus campaign ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                        <td class="px-5 py-3 font-medium">{{ $campaign->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $campaign->category?->name ?? '-' }}</td>
                        <td class="w-64 px-5 py-3">
                            <p class="mb-1 text-xs text-gray-500">Rp
                                {{ number_format($campaign->collected_amount, 0, ',', '.') }} / Rp
                                {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                            <x-quota-progress :booked="$campaign->collected_amount" :total="$campaign->target_amount" />
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $color = match ($campaign->status) {
                                    'active' => 'bg-emerald-100 text-emerald-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    'closed' => 'bg-gray-100 text-gray-600',
                                };
                                $label = match ($campaign->status) {
                                    'active' => 'Aktif',
                                    'completed' => 'Selesai',
                                    'closed' => 'Ditutup',
                                };
                            @endphp
                            <span
                                class="{{ $color }} rounded-full px-2 py-1 text-xs font-medium">{{ $label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada campaign infaq.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $campaigns->links() }}</div>
@endsection
