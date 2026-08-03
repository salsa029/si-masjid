@extends('layouts.app')

@section('title', 'Pembayaran Infaq')

@section('content')
    <div class="mx-auto max-w-md text-center">

        <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            <p class="text-sm text-gray-500">Infaq {{ $infaq->category->name ?? ($infaq->campaign->title ?? 'Umum') }}</p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">Rp {{ number_format($infaq->amount, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-gray-400">No. Transaksi: {{ $infaq->transaction_number }}</p>

            @if ($infaq->payment_status === 'pending' && $infaq->payment_method === 'midtrans')
                <button id="pay-button"
                    class="mt-6 w-full rounded-lg bg-emerald-700 py-2.5 text-sm font-medium text-white hover:bg-emerald-800">
                    Bayar Sekarang
                </button>
            @elseif ($infaq->payment_status === 'pending' && $infaq->payment_method === 'manual_transfer')
                <div class="mt-6 space-y-1 rounded-lg bg-gray-50 p-4 text-left text-sm">
                    <p class="font-medium">Silakan transfer ke rekening berikut:</p>
                    <p>{{ $settings->bank_name }} — <strong>{{ $settings->bank_account_number }}</strong></p>
                    <p>a.n. {{ $settings->bank_account_name }}</p>
                    <p class="text-xs text-gray-400">Cantumkan No. Transaksi di atas sebagai berita transfer.</p>
                    <p class="text-xs text-amber-600">Batas waktu:
                        {{ $infaq->reserved_until?->translatedFormat('d F Y, H:i') }} WIB</p>
                </div>

                <form method="POST" action="{{ route('public.infaq.upload-proof', $infaq) }}" enctype="multipart/form-data"
                    class="mt-4 space-y-3 text-left">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium">Unggah Bukti Transfer</label>
                        <input type="file" name="payment_proof" accept="image/*" class="w-full text-sm">
                        @error('payment_proof')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full rounded-lg bg-emerald-700 py-2.5 text-sm font-medium text-white hover:bg-emerald-800">
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            @elseif ($infaq->payment_status === 'awaiting_verification')
                <div class="mt-6 rounded-lg bg-amber-50 p-4 text-sm text-amber-700">
                    Bukti pembayaran sudah diterima dan sedang menunggu verifikasi Admin. Silakan cek kembali dalam 1x24
                    jam.
                </div>
            @elseif ($infaq->payment_status === 'success')
                <div class="mt-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700">
                    Pembayaran berhasil. Terima kasih atas infaq Anda.
                </div>
                <a href="{{ route('public.infaq.receipt', $infaq) }}"
                    class="mt-4 block text-sm font-medium text-emerald-700 underline">
                    Unduh E-Kuitansi
                </a>
            @else
                <div class="mt-6 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Transaksi {{ $infaq->payment_status === 'expired' ? 'telah kedaluwarsa' : 'gagal diproses' }}.
                    @if ($infaq->verification_note)
                        <br><span class="text-xs">Catatan Admin: {{ $infaq->verification_note }}</span>
                    @endif
                </div>
            @endif

            <a href="{{ route('public.infaq.history') }}" class="mt-4 block text-xs text-gray-400 underline">
                Lihat Riwayat Infaq Saya
            </a>
        </div>

    </div>

    @if ($snapToken)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script>
            document.getElementById('pay-button').addEventListener('click', function() {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function() {
                        window.location.reload();
                    },
                    onPending: function() {
                        window.location.reload();
                    },
                    onError: function() {
                        alert('Terjadi kesalahan saat memproses pembayaran.');
                    },
                });
            });
        </script>
    @endif
@endsection
