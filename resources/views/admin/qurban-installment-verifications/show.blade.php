@extends('layouts.admin')

@section('title', 'Tinjau Bukti Cicilan')

@section('content')

    <div class="max-w-2xl">
        <div class="mb-5 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Jamaah</dt>
                    <dd class="font-medium">{{ $installment->order->user->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Hewan</dt>
                    <dd class="font-medium">{{ $installment->order->animal->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Cicilan Ke-</dt>
                    <dd class="font-medium">{{ $installment->installment_number }} dari {{ $installment->order->installment_count }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nominal Cicilan Ini</dt>
                    <dd class="font-bold text-emerald-700">Rp {{ number_format($installment->amount, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Total Pesanan</dt>
                    <dd class="font-medium">Rp {{ number_format($installment->order->total_amount, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">No. Transaksi</dt>
                    <dd class="font-medium">{{ $installment->midtrans_order_id }}</dd>
                </div>
            </dl>
        </div>

        <div class="mb-5 rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="mb-3 text-sm font-medium">Bukti Transfer</p>
            <img src="{{ Storage::url($installment->payment_proof) }}" class="w-full rounded-lg border border-gray-200">
        </div>

        <div class="flex gap-3">
            <form action="{{ route('admin.qurban-installment-verifications.approve', $installment) }}" method="POST"
                onsubmit="return confirm('Konfirmasi pembayaran cicilan ini sebagai valid?');" class="flex-1">
                @csrf
                @method('PUT')
                <button type="submit"
                    class="w-full rounded-lg bg-emerald-700 py-2.5 text-sm font-medium text-white hover:bg-emerald-800">
                    Setujui Pembayaran
                </button>
            </form>

            <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                class="flex-1 rounded-lg border border-red-300 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
                Tolak Pembayaran
            </button>
        </div>

        <form id="reject-form" action="{{ route('admin.qurban-installment-verifications.reject', $installment) }}"
            method="POST" class="mt-4 hidden rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            @csrf
            @method('PUT')
            <label class="mb-1 block text-sm font-medium">Alasan Penolakan</label>
            <textarea name="verification_note" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                placeholder="Contoh: Nominal transfer tidak sesuai, bukti tidak jelas, dsb."></textarea>
            @error('verification_note')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-xs text-gray-400">Jamaah akan bisa mengunggah ulang bukti pembayaran untuk cicilan ini
                setelah ditolak.</p>
            <button type="submit"
                class="mt-3 rounded-lg bg-red-600 px-5 py-2 text-sm font-medium text-white hover:bg-red-700">
                Kirim Penolakan
            </button>
        </form>
    </div>
@endsection
