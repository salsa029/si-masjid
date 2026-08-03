@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Kurban')

@section('content')

    <p class="mb-5 text-sm text-gray-500">Menampilkan transaksi transfer manual yang menunggu diverifikasi.</p>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Jamaah</th>
                    <th class="px-5 py-3">Hewan</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Diunggah Pada</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingVerifications as $order)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">{{ $order->user->name }}</td>
                        <td class="px-5 py-3">{{ $order->animal->name }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.qurban-verifications.show', $order) }}"
                                class="text-emerald-700 hover:underline">
                                Tinjau Bukti
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Tidak ada pembayaran yang menunggu
                            verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pendingVerifications->links() }}</div>
@endsection
