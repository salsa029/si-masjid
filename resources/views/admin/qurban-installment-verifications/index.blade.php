@extends('layouts.admin')

@section('title', 'Verifikasi Cicilan Kurban')

@section('content')

    <p class="mb-5 text-sm text-gray-500">Menampilkan pembayaran cicilan (transfer manual) yang menunggu diverifikasi.</p>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Aksi</th>
                    <th class="px-5 py-3">Jamaah</th>
                    <th class="px-5 py-3">Hewan</th>
                    <th class="px-5 py-3">Cicilan Ke-</th>
                    <th class="px-5 py-3">Nominal</th>
                    <th class="px-5 py-3">Diunggah Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingVerifications as $installment)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.qurban-installment-verifications.show', $installment) }}"
                                class="text-emerald-700 hover:underline">
                                Tinjau Bukti
                            </a>
                        </td>
                        <td class="px-5 py-3">{{ $installment->order->user->name }}</td>
                        <td class="px-5 py-3">{{ $installment->order->animal->name }}</td>
                        <td class="px-5 py-3">{{ $installment->installment_number }} dari {{ $installment->order->installment_count }}</td>
                        <td class="px-5 py-3">Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $installment->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">Tidak ada cicilan yang menunggu
                            verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pendingVerifications->links() }}</div>
@endsection
