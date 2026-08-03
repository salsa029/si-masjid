@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Zakat')

@section('content')
    <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">Jamaah</th>
                    <th class="px-5 py-3">Jenis Zakat</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Diunggah Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $zakat)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.donation-verifications.zakat.show', $zakat) }}"
                                class="text-emerald-700 hover:underline">Tinjau Bukti</a>
                        </td>
                        <td class="px-5 py-3">{{ $zakat->display_name }}</td>
                        <td class="px-5 py-3">{{ $zakat->zakatType->name }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($zakat->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $zakat->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada pembayaran Zakat yang
                            menunggu verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection
