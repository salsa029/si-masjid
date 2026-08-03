@extends('layouts.admin')

@section('title', 'Detail Verifikasi Infaq')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <table class="mb-6 w-full text-sm">
            <tr>
                <td class="w-40 py-1 text-gray-500">Jamaah</td>
                <td>: {{ $infaq->display_name }}</td>
            </tr>
            <tr>
                <td class="py-1 text-gray-500">Kategori/Campaign</td>
                <td>: {{ $infaq->category->name ?? ($infaq->campaign->title ?? 'Umum') }}</td>
            </tr>
            <tr>
                <td class="py-1 text-gray-500">Nominal</td>
                <td>: Rp {{ number_format($infaq->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="py-1 text-gray-500">No. Transaksi</td>
                <td>: {{ $infaq->transaction_number }}</td>
            </tr>
        </table>

        <img src="{{ Storage::url($infaq->payment_proof) }}" class="mb-6 w-full rounded-lg border border-gray-200"
            alt="Bukti Transfer">

        <form method="POST" action="{{ route('admin.donation-verifications.infaq.approve', $infaq) }}" class="inline"
            onsubmit="return confirm('Setujui pembayaran Infaq ini?')">
            @csrf
            @method('PUT')
            <button type="submit"
                class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">Setujui
                Pembayaran</button>
        </form>

        <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')"
            class="ml-2 rounded-lg bg-red-100 px-4 py-2 text-sm font-medium text-red-700">Tolak Pembayaran</button>

        <form id="reject-form" method="POST" action="{{ route('admin.donation-verifications.infaq.reject', $infaq) }}"
            class="mt-4 hidden">
            @csrf
            @method('PUT')
            <textarea name="verification_note" rows="3" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Alasan penolakan..."></textarea>
            @error('verification_note')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <button type="submit" class="mt-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white">Kirim
                Penolakan</button>
        </form>
    </div>
@endsection
