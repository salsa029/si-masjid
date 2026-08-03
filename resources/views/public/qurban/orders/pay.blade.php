@extends('layouts.app')

@section('title', 'Bayar Pesanan Kurban')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-md px-4">
            <a href="{{ route('public.qurban.orders.history') }}"
                class="mb-6 inline-flex items-center gap-2 text-green-600 transition hover:text-green-800">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                Kembali ke Riwayat
            </a>

            <div class="rounded-2xl bg-white p-6 text-center shadow-lg md:p-8">
                <!-- Header -->
                <div class="mb-6">
                    <span
                        class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Kurban</span>
                    <p class="mt-1 text-sm text-gray-500">{{ $qurbanOrder->animal->name ?? 'Hewan Kurban' }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst($qurbanOrder->order_type ?? '') }} -
                        {{ $qurbanOrder->order_type === 'patungan' ? 'Patungan' : 'Penuh' }}</p>
                    <p class="mt-1 text-3xl font-extrabold text-green-700">Rp
                        {{ number_format($qurbanOrder->total_amount, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-400">No. Transaksi: {{ $qurbanOrder->midtrans_order_id }}</p>
                </div>

                <!-- Payment Status -->
                @if ($qurbanOrder->payment_status === 'pending' && $qurbanOrder->payment_method === 'midtrans')
                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">Klik tombol di bawah untuk melanjutkan pembayaran melalui Midtrans.
                        </p>
                        <button id="pay-button"
                            class="w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white shadow-lg transition hover:from-green-700 hover:to-green-800 hover:shadow-xl">
                            <i class="fas fa-credit-card mr-2" aria-hidden="true"></i>
                            Bayar Sekarang
                        </button>
                        <a href="{{ route('public.qurban.orders.check-status', $qurbanOrder) }}"
                            class="block text-xs text-gray-400 underline">
                            Sudah bayar? Cek Status Pembayaran
                        </a>
                    </div>
                @elseif($qurbanOrder->payment_status === 'pending' && $qurbanOrder->payment_method === 'manual_transfer')
                    <div class="space-y-4 text-left">
                        <div class="space-y-2 rounded-xl bg-gray-50 p-4 text-sm">
                            @if (($settings->bank_account_number ?? null))
                                <p class="font-medium text-gray-700">Silakan transfer ke rekening berikut:</p>
                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <p><span class="text-gray-500">Bank:</span>
                                        <strong>{{ $settings->bank_name }}</strong></p>
                                    <p><span class="text-gray-500">No. Rekening:</span>
                                        <strong>{{ $settings->bank_account_number }}</strong></p>
                                    <p><span class="text-gray-500">Atas Nama:</span>
                                        <strong>{{ $settings->bank_account_name }}</strong></p>
                                </div>
                                <p class="text-xs text-gray-400">Cantumkan No. Transaksi di atas sebagai berita transfer.</p>
                            @else
                                <p class="text-amber-600">Rekening transfer belum diatur oleh Admin. Silakan hubungi
                                    pengurus masjid untuk info rekening.</p>
                            @endif
                            <p class="text-xs font-medium text-amber-600">
                                <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                                Batas waktu: {{ $qurbanOrder->reserved_until?->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>

                        <form method="POST" action="{{ route('public.qurban.orders.upload-proof', $qurbanOrder) }}"
                            enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true">
                            @csrf
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Unggah Bukti Transfer</label>
                                <input type="file" name="payment_proof" accept="image/*"
                                    class="w-full rounded-xl border border-gray-300 p-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                                @error('payment_proof')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" :disabled="submitting"
                                class="w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
                                <span x-show="!submitting">Kirim Bukti Pembayaran</span>
                                <span x-show="submitting" x-cloak>
                                    <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Mengunggah...
                                </span>
                            </button>
                        </form>
                    </div>
                @elseif($qurbanOrder->payment_status === 'awaiting_verification')
                    <div class="rounded-xl bg-amber-50 p-5 text-sm text-amber-700">
                        <i class="fas fa-clock mb-2 block text-xl" aria-hidden="true"></i>
                        <p class="font-medium">Menunggu Verifikasi Admin</p>
                        <p class="mt-1 text-xs">Bukti pembayaran Anda sedang diperiksa. Proses ini maksimal 1x24 jam.</p>
                    </div>
                @elseif($qurbanOrder->payment_status === 'success')
                    <div class="rounded-xl bg-green-50 p-5 text-sm text-green-700">
                        <i class="fas fa-check-circle mb-2 block text-2xl" aria-hidden="true"></i>
                        <p class="font-medium">Pembayaran Berhasil!</p>
                        <p class="mt-1 text-xs">Terima kasih atas kurban Anda. Semoga Allah menerima amal ibadah Anda.</p>
                    </div>
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('public.qurban.orders.receipt', $qurbanOrder) }}"
                            class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-center text-sm font-medium text-white transition hover:bg-emerald-700">
                            <i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>
                            Unduh E-Kuitansi
                        </a>
                        @if ($qurbanOrder->animal && $qurbanOrder->animal->status === 'slaughtered')
                            <a href="{{ route('public.qurban.orders.certificate', $qurbanOrder) }}"
                                class="flex-1 rounded-xl bg-amber-600 py-2.5 text-center text-sm font-medium text-white transition hover:bg-amber-700">
                                <i class="fas fa-certificate mr-2" aria-hidden="true"></i>
                                Unduh Sertifikat
                            </a>
                        @endif
                    </div>
                @else
                    <div class="rounded-xl bg-red-50 p-5 text-sm text-red-700">
                        <i class="fas fa-times-circle mb-2 block text-xl" aria-hidden="true"></i>
                        <p class="font-medium">Transaksi
                            {{ $qurbanOrder->payment_status === 'expired' ? 'Kedaluwarsa' : 'Gagal' }}</p>
                        @if ($qurbanOrder->verification_note)
                            <p class="mt-1 text-xs">Catatan: {{ $qurbanOrder->verification_note }}</p>
                        @endif
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('public.qurban.index') }}"
                            class="block w-full rounded-xl bg-green-600 py-2.5 text-center text-sm font-medium text-white transition hover:bg-green-700">
                            <i class="fas fa-redo mr-2" aria-hidden="true"></i>
                            Pilih Hewan Lain
                        </a>
                    </div>
                @endif

                <a href="{{ route('public.qurban.orders.history') }}"
                    class="mt-4 block text-xs text-gray-400 transition hover:text-gray-600">
                    <i class="fas fa-list mr-1" aria-hidden="true"></i>
                    Lihat Pesanan Kurban Saya
                </a>
            </div>
        </div>
    </section>

    @if ($snapToken)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script>
            document.getElementById('pay-button').addEventListener('click', function() {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function() {
                        window.location.href = '{{ route('public.qurban.orders.check-status', $qurbanOrder) }}';
                    },
                    onPending: function() {
                        window.location.href = '{{ route('public.qurban.orders.check-status', $qurbanOrder) }}';
                    },
                    onError: function() {
                        alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                    }
                });
            });
        </script>
    @endif
@endsection
