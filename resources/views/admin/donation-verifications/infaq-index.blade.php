@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Infaq')

@section('content')
    <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Jamaah</th>
                    <th class="px-5 py-3">Kategori/Campaign</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Diunggah Pada</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $infaq)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">{{ $infaq->display_name }}</td>
                        <td class="px-5 py-3">{{ $infaq->category->name ?? ($infaq->campaign->title ?? 'Umum') }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $infaq->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.donation-verifications.infaq.show', $infaq) }}"
                                class="text-emerald-700 hover:underline">Tinjau Bukti</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada pembayaran Infaq yang
                            menunggu verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection
