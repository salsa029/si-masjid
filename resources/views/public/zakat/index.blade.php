@extends('layouts.app')

@section('title', 'Zakat')

@section('content')
    <section class="bg-gray-50 py-12 md:py-5">
        <div class="container mx-auto max-w-6xl px-4 pb-14">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Tunaikan <span
                        class="text-green-600">Zakat</span></h2>
                <p class="mt-2 text-gray-500">Hitung dan tunaikan zakat Anda dalam satu halaman</p>
            </div>

            <!-- Grid Side-by-Side -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- KOLOM KIRI: Kalkulator -->
                <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
                    <h3 class="mb-4 text-lg font-bold text-green-800">
                        <i class="fas fa-calculator mr-2"></i> Kalkulator Zakat
                    </h3>

                    <!-- Kalkulator Content -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Jenis Zakat</label>
                        <select id="zakat_type"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                            <option value="">-- Pilih Jenis Zakat --</option>
                            @foreach ($zakatTypes as $type)
                                <option value="{{ $type->id }}" data-unit="{{ $type->calculation_unit }}"
                                    data-nishab="{{ $type->nishab_amount }}" data-rate="{{ $type->rate_percentage }}"
                                    data-name="{{ $type->name }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fitrah Input -->
                    <div id="fitrah-input" class="mt-5 hidden">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Jumlah Jiwa</label>
                        <input type="number" id="number_of_souls" min="1" value="1"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                        <p class="mt-1 text-xs text-gray-400">
                            Harga beras per kg saat ini: Rp
                            {{ number_format($settings->rice_price_per_kg ?? 15000, 0, ',', '.') }} (2,5 kg/jiwa)
                        </p>
                    </div>

                    <!-- Maal Input -->
                    <div id="maal-input" class="mt-5 hidden">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Total Harta (Rp)</label>
                        <input type="number" id="calculation_base" min="0" value="0"
                            class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                        <p class="mt-1 text-xs text-gray-400" id="nishab-info"></p>
                    </div>

                    <!-- Calculate Button -->
                    <button type="button" id="calculate-button"
                        class="mt-6 w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white shadow-lg transition hover:from-green-700 hover:to-green-800 hover:shadow-xl">
                        <i class="fas fa-calculator mr-2" aria-hidden="true"></i>
                        Hitung Zakat
                    </button>

                    <!-- Result -->
                    <div id="result" class="mt-6 hidden rounded-xl bg-green-50 p-5 text-center">
                        <p class="text-sm text-green-700" id="result-status"></p>
                        <p class="mt-2 text-3xl font-extrabold text-green-800" id="result-amount"></p>
                        <button type="button" id="apply-to-form"
                            class="mt-4 inline-block rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
                            Gunakan Nominal Ini →
                        </button>
                    </div>
                </div>

                <!-- KOLOM KANAN: Form Zakat -->
                <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
                    <h3 class="mb-4 text-lg font-bold text-green-800">
                        <i class="fas fa-hand-holding-heart mr-2"></i> Form Pembayaran Zakat
                    </h3>

                    @auth
                        <form method="POST" action="{{ route('public.zakat.store') }}" class="space-y-5"
                            x-data="{ submitting: false }" @submit="submitting = true">
                            @csrf

                            <!-- Hidden fields untuk data dari kalkulator -->
                            <input type="hidden" name="zakat_type_id" id="form_zakat_type_id"
                                value="{{ old('zakat_type_id', request('zakat_type_id')) }}">
                            <input type="hidden" name="amount" id="form_amount"
                                value="{{ old('amount', request('amount')) }}">
                            <input type="hidden" name="calculation_base"
                                value="{{ old('calculation_base', request('calculation_base')) }}">
                            <input type="hidden" name="number_of_souls"
                                value="{{ old('number_of_souls', request('number_of_souls')) }}">
                            <input type="hidden" name="is_above_nishab"
                                value="{{ old('is_above_nishab', request('is_above_nishab')) }}">

                            <!-- Display Jenis Zakat -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Jenis Zakat</label>
                                <p class="rounded-xl bg-gray-50 px-4 py-2.5 text-sm" id="display_zakat_type">
                                    {{ old('zakat_type_id') ? $zakatTypes->firstWhere('id', old('zakat_type_id'))?->name ?? 'Belum dipilih' : 'Belum dipilih' }}
                                </p>
                                @error('zakat_type_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Display Nominal -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Nominal Zakat (Rp)</label>
                                <p class="rounded-xl bg-gray-50 px-4 py-2.5 text-sm" id="display_amount">
                                    Rp
                                    {{ old('amount', request('amount')) ? number_format(old('amount', request('amount')), 0, ',', '.') : '0' }}
                                </p>
                                @error('amount')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-400">Gunakan kalkulator di samping untuk menghitung nominal
                                    zakat.</p>
                            </div>

                            <!-- Nama Muzakki -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Nama Muzakki (Opsional)</label>
                                <input type="text" name="muzakki_name"
                                    value="{{ old('muzakki_name', auth()->user()->name) }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500"
                                    placeholder="Isi jika membayar zakat atas nama orang lain">
                                <p class="mt-1 text-xs text-gray-400">Isi nama lain jika membayar zakat atas nama anggota
                                    keluarga.</p>
                            </div>

                            <!-- Anonim -->
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous'))>
                                <span class="text-gray-700">Sembunyikan nama saya (tampilkan sebagai "Hamba Allah")</span>
                            </label>

                            <!-- Catatan -->
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <textarea name="message" rows="2"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">{{ old('message') }}</textarea>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label
                                        class="flex cursor-pointer items-center gap-2 rounded-xl border p-3 transition hover:border-green-500 has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                                        <input type="radio" name="payment_method" value="midtrans"
                                            @checked(old('payment_method', 'midtrans') == 'midtrans')>
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

                            <!-- Submit -->
                            <button type="submit" :disabled="submitting"
                                class="w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
                                <span x-show="!submitting">Lanjutkan Pembayaran</span>
                                <span x-show="submitting" x-cloak>
                                    <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Memproses...
                                </span>
                            </button>
                        </form>
                    @else
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-center text-amber-700">
                            <i class="fas fa-info-circle mb-2 block text-2xl" aria-hidden="true"></i>
                            <p class="font-medium">Silakan <a href="{{ route('login') }}"
                                    class="underline hover:text-amber-900">masuk</a> atau <a href="{{ route('register') }}"
                                    class="underline hover:text-amber-900">daftar</a> terlebih dahulu untuk melanjutkan zakat.
                            </p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript untuk sinkronisasi -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ricePricePerKg = {{ $settings->rice_price_per_kg ?? 15000 }};
            const zakatTypeSelect = document.getElementById('zakat_type');
            const fitrahInput = document.getElementById('fitrah-input');
            const maalInput = document.getElementById('maal-input');
            const nishabInfo = document.getElementById('nishab-info');

            // Elemen form
            const formZakatTypeId = document.getElementById('form_zakat_type_id');
            const formAmount = document.getElementById('form_amount');
            const displayZakatType = document.getElementById('display_zakat_type');
            const displayAmount = document.getElementById('display_amount');

            // Fungsi untuk update display form
            function updateFormDisplay(typeId, typeName, amount) {
                if (typeId !== undefined && typeId !== null) {
                    formZakatTypeId.value = typeId;
                }
                if (typeName !== undefined && typeName !== null) {
                    displayZakatType.textContent = typeName;
                }

                if (amount !== undefined && amount !== null && !isNaN(amount)) {
                    const roundedAmount = Math.round(amount);
                    const formatted = new Intl.NumberFormat('id-ID').format(roundedAmount);
                    displayAmount.textContent = 'Rp ' + formatted;
                    formAmount.value = roundedAmount;
                }
            }

            // Event: Pilih jenis zakat
            zakatTypeSelect.addEventListener('change', function() {
                const selected = zakatTypeSelect.options[zakatTypeSelect.selectedIndex];
                const unit = selected.dataset.unit;
                const nishab = parseFloat(selected.dataset.nishab || 0);
                const typeName = selected.dataset.name || selected.text;
                const typeId = selected.value;

                // Toggle input berdasarkan unit
                fitrahInput.classList.toggle('hidden', unit !== 'fixed_per_soul');
                maalInput.classList.toggle('hidden', unit !== 'nishab_percentage');

                if (unit === 'nishab_percentage') {
                    nishabInfo.textContent = 'Batas nishab: Rp ' + nishab.toLocaleString('id-ID');
                } else {
                    nishabInfo.textContent = '';
                }

                // Update display form
                updateFormDisplay(typeId, typeName, null);

                // Reset result
                document.getElementById('result').classList.add('hidden');
            });

            // Event: Hitung zakat
            document.getElementById('calculate-button').addEventListener('click', function() {
                const selected = zakatTypeSelect.options[zakatTypeSelect.selectedIndex];
                if (!selected.value) {
                    alert('Pilih jenis zakat terlebih dahulu.');
                    return;
                }

                const unit = selected.dataset.unit;
                const rate = parseFloat(selected.dataset.rate);
                const nishab = parseFloat(selected.dataset.nishab || 0);
                const typeName = selected.dataset.name || selected.text;
                const typeId = selected.value;

                let amount = 0;
                let statusText = '';
                let calculationBase = null;
                let numberOfSouls = null;
                let isAboveNishab = null;

                if (unit === 'fixed_per_soul') {
                    numberOfSouls = parseInt(document.getElementById('number_of_souls').value || 0);
                    if (numberOfSouls < 1) {
                        alert('Isi jumlah jiwa terlebih dahulu.');
                        return;
                    }
                    amount = numberOfSouls * ricePricePerKg * 2.5;
                    statusText = 'Zakat Fitrah untuk ' + numberOfSouls + ' jiwa';

                    // Update hidden fields
                    document.querySelector('input[name="number_of_souls"]').value = numberOfSouls;
                    document.querySelector('input[name="calculation_base"]').value = '';
                    document.querySelector('input[name="is_above_nishab"]').value = '';
                } else {
                    calculationBase = parseFloat(document.getElementById('calculation_base').value || 0);
                    if (calculationBase <= 0) {
                        alert('Isi total harta terlebih dahulu.');
                        return;
                    }
                    isAboveNishab = calculationBase >= nishab;
                    if (isAboveNishab) {
                        amount = calculationBase * (rate / 100);
                        statusText = '✅ Wajib Zakat (harta Anda di atas nishab)';
                    } else {
                        amount = 0;
                        statusText = 'ℹ️ Harta Anda belum mencapai nishab (Rp ' + nishab.toLocaleString(
                            'id-ID') + '), belum wajib zakat.';
                    }

                    // Update hidden fields
                    document.querySelector('input[name="calculation_base"]').value = calculationBase;
                    document.querySelector('input[name="number_of_souls"]').value = '';
                    document.querySelector('input[name="is_above_nishab"]').value = isAboveNishab ? 1 : 0;
                }

                // Tampilkan hasil
                const displayAmountValue = Math.round(amount);
                document.getElementById('result-status').textContent = statusText;
                document.getElementById('result-amount').textContent = 'Rp ' + displayAmountValue
                    .toLocaleString('id-ID');
                document.getElementById('result').classList.remove('hidden');

                // Sinkronisasi ke form
                updateFormDisplay(typeId, typeName, displayAmountValue);
            });

            // Event: Tombol "Gunakan Nominal Ini"
            const applyButton = document.getElementById('apply-to-form');
            if (applyButton) {
                applyButton.addEventListener('click', function() {
                    // Scroll ke form
                    const formSection = document.querySelector('.grid > div:last-child');
                    if (formSection) {
                        formSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        // Highlight form section
                        formSection.classList.add('ring-2', 'ring-green-500', 'ring-offset-2',
                            'transition-all', 'rounded-2xl');
                        setTimeout(() => {
                            formSection.classList.remove('ring-2', 'ring-green-500',
                                'ring-offset-2');
                        }, 2000);
                    }
                });
            }

            // Trigger initial state jika ada old value atau request
            const oldTypeId = '{{ old('zakat_type_id', request('zakat_type_id')) }}';
            const oldAmount = '{{ old('amount', request('amount')) }}';

            if (oldTypeId) {
                // Set select value
                zakatTypeSelect.value = oldTypeId;
                zakatTypeSelect.dispatchEvent(new Event('change'));

                if (oldAmount) {
                    const selected = zakatTypeSelect.options[zakatTypeSelect.selectedIndex];
                    const typeName = selected?.dataset?.name || selected?.text || 'Zakat';
                    updateFormDisplay(oldTypeId, typeName, parseFloat(oldAmount));
                    document.getElementById('result').classList.remove('hidden');
                    document.getElementById('result-status').textContent = 'Dari perhitungan sebelumnya';
                    document.getElementById('result-amount').textContent = 'Rp ' + parseFloat(oldAmount)
                        .toLocaleString('id-ID');
                }
            } else {
                // Initial state
                zakatTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
