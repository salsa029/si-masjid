@extends('layouts.app')

@section('title', 'Pesan Kurban')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-lg px-4">
            <!-- Header -->
            <div class="mb-6 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Pemesanan</span>
                <h2 class="mt-2 text-2xl font-extrabold text-green-800">Pesan <span class="text-green-600">Kurban</span></h2>
                <p class="mt-1 text-sm text-gray-500">{{ $sacrificialAnimal->name }}</p>
                <p class="text-lg font-bold text-green-700">Rp {{ number_format($sacrificialAnimal->price, 0, ',', '.') }}
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <i class="fas fa-exclamation-circle mr-2" aria-hidden="true"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('public.qurban.orders.store', $sacrificialAnimal) }}"
                class="space-y-5 rounded-2xl bg-white p-6 shadow-lg" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <!-- Order Type -->
                @if ($sacrificialAnimal->max_participants > 1)
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jenis Pemesanan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                                <input type="radio" name="order_type" value="full" @checked(old('order_type') === 'full')>
                                <div>
                                    <span class="block text-sm font-medium">Beli Penuh</span>
                                    <span class="text-xs text-gray-500">Rp
                                        {{ number_format($sacrificialAnimal->price, 0, ',', '.') }}</span>
                                </div>
                            </label>
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                                <input type="radio" name="order_type" value="patungan" @checked(old('order_type', 'patungan') === 'patungan')>
                                <div>
                                    <span class="block text-sm font-medium">Patungan</span>
                                    <span class="text-xs text-gray-500">Rp
                                        {{ number_format(round($sacrificialAnimal->price / $sacrificialAnimal->max_participants), 0, ',', '.') }}
                                        / org</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Slot Selection -->
                    <div id="slot-selection">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Pilih Slot Patungan</label>
                        <div class="grid grid-cols-7 gap-2">
                            @for ($slot = 1; $slot <= $sacrificialAnimal->max_participants; $slot++)
                                <label
                                    class="{{ in_array($slot, $takenSlots ?? []) ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200' : 'hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50' }} cursor-pointer rounded-xl border py-3 text-center text-sm transition">
                                    <input type="radio" name="slot_number" value="{{ $slot }}" class="hidden"
                                        {{ in_array($slot, $takenSlots ?? []) ? 'disabled' : '' }}
                                        {{ old('slot_number', 1) == $slot ? 'checked' : '' }}>
                                    {{ $slot }}
                                </label>
                            @endfor
                        </div>
                        <p class="mt-2 text-xs text-gray-400">
                            <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                            Kotak abu-abu berarti slot sudah dipesan Jamaah lain
                        </p>
                        @error('slot_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="order_type" value="full">
                    <div class="rounded-xl bg-gray-50 p-4 text-center text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-2 text-green-600" aria-hidden="true"></i>
                        Hewan ini dipesan secara penuh (1 pemesan = 1 ekor)
                    </div>
                @endif

                <!-- Payment Type (Penuh / Cicilan) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Cara Bayar</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                            <input type="radio" name="payment_type" value="full"
                                @checked(old('payment_type', 'full') === 'full')>
                            <span class="text-sm">Bayar Penuh</span>
                        </label>
                        <label
                            class="{{ $installmentEligible ? '' : 'cursor-not-allowed opacity-50' }} flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                            <input type="radio" name="payment_type" value="installment"
                                {{ $installmentEligible ? '' : 'disabled' }} @checked(old('payment_type') === 'installment')>
                            <span class="text-sm">Cicilan</span>
                        </label>
                    </div>
                    @if (! $installmentEligible)
                        <p class="mt-2 text-xs text-gray-400">
                            <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                            Cicilan tidak tersedia untuk hewan ini (kegiatan kurban belum ditentukan atau batas
                            pendaftarannya sudah terlalu dekat).
                        </p>
                    @endif
                    @error('payment_type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @if ($installmentEligible)
                    <div id="installment-count-selection">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jumlah Cicilan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                                <input type="radio" name="installment_count" value="2"
                                    @checked(old('installment_count', '2') == '2')>
                                <span class="text-sm">2x Cicilan</span>
                            </label>
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                                <input type="radio" name="installment_count" value="3"
                                    @checked(old('installment_count') == '3')>
                                <span class="text-sm">3x Cicilan</span>
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">
                            <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                            Cicilan pertama dibayar sekarang, cicilan terakhir paling lambat
                            {{ $installmentDeadline->translatedFormat('d F Y') }}. Jika tidak lunas sampai tanggal
                            tersebut, slot akan dilepas dan dana yang sudah dibayar dialihkan menjadi infaq (kecuali
                            Anda mengajukan refund sebelumnya).
                        </p>
                        @error('installment_count')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Payment Method -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                            <input type="radio" name="payment_method" value="midtrans" @checked(old('payment_method', 'midtrans') == 'midtrans')>
                            <span class="text-sm">Bayar Online (Midtrans)</span>
                        </label>
                        <label
                            class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                            <input type="radio" name="payment_method" value="manual_transfer"
                                @checked(old('payment_method') == 'manual_transfer')>
                            <span class="text-sm">Transfer Manual</span>
                        </label>
                    </div>
                </div>

                <!-- Info -->
                <div class="rounded-xl bg-green-50 p-4 text-sm text-green-700">
                    <i class="fas fa-info-circle mr-2" aria-hidden="true"></i>
                    @if ($sacrificialAnimal->max_participants > 1)
                        Untuk patungan, Anda hanya perlu membayar bagian Anda.
                        Slot akan dikunci selama 24 jam untuk penyelesaian pembayaran.
                    @else
                        Slot akan dikunci selama 24 jam untuk penyelesaian pembayaran.
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" :disabled="submitting"
                    class="w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
                    <span x-show="!submitting">Lanjutkan Pemesanan</span>
                    <span x-show="submitting" x-cloak>
                        <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Memproses...
                    </span>
                </button>
            </form>

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('public.qurban.show', $sacrificialAnimal) }}"
                    class="text-sm text-gray-500 transition hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                    Kembali ke Detail Hewan
                </a>
            </div>
        </div>
    </section>

    <script>
        // Toggle slot visibility based on order type
        document.querySelectorAll('input[name="order_type"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const slotSelection = document.getElementById('slot-selection');
                if (this.value === 'patungan') {
                    slotSelection.style.display = 'block';
                } else {
                    slotSelection.style.display = 'none';
                }
            });
        });

        // Trigger initial state
        const initialOrderType = document.querySelector('input[name="order_type"]:checked');
        if (initialOrderType) {
            initialOrderType.dispatchEvent(new Event('change'));
        }

        // Toggle jumlah cicilan berdasarkan cara bayar yang dipilih
        const installmentCountSelection = document.getElementById('installment-count-selection');
        if (installmentCountSelection) {
            document.querySelectorAll('input[name="payment_type"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    installmentCountSelection.style.display = this.value === 'installment' ? 'block' : 'none';
                });
            });

            const initialPaymentType = document.querySelector('input[name="payment_type"]:checked');
            installmentCountSelection.style.display = initialPaymentType && initialPaymentType.value === 'installment' ? 'block' : 'none';
        }
    </script>
@endsection
