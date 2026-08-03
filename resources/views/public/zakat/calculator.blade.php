@extends('layouts.app')

@section('title', 'Kalkulator Zakat')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-xl px-4">
            <!-- Header -->
            <div class="mb-8 text-center">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Kalkulator</span>
                <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Kalkulator <span
                        class="text-green-600">Zakat</span></h2>
                <p class="mt-2 text-gray-500">Pilih jenis zakat dan isi data yang diminta untuk menghitung nominal zakat</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-lg md:p-8">
                <!-- Jenis Zakat -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Jenis Zakat</label>
                    <select id="zakat_type"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                        <option value="">-- Pilih Jenis Zakat --</option>
                        @foreach ($zakatTypes as $type)
                            <option value="{{ $type->id }}" data-unit="{{ $type->calculation_unit }}"
                                data-nishab="{{ $type->nishab_amount }}" data-rate="{{ $type->rate_percentage }}">
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Zakat Fitrah Input (fixed_per_soul) -->
                <div id="fitrah-input" class="mt-5 hidden">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Jumlah Jiwa</label>
                    <input type="number" id="number_of_souls" min="1" value="1"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                    <p class="mt-1 text-xs text-gray-400">Harga beras per kg saat ini: Rp
                        {{ number_format($settings->rice_price_per_kg ?? 15000, 0, ',', '.') }} (2,5 kg/jiwa)</p>
                </div>

                <!-- Zakat Maal Input (nishab_percentage) -->
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
                    <a href="#" id="pay-now-link"
                        class="mt-4 inline-block text-sm font-semibold text-green-700 underline transition hover:text-green-900">
                        Lanjutkan Bayar Zakat Ini →
                    </a>
                </div>

                <!-- Back Link -->
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <a href="{{ route('public.zakat.index') }}"
                        class="text-sm text-gray-500 transition hover:text-gray-700">
                        <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                        Kembali ke Halaman Zakat
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        const ricePricePerKg = {{ $settings->rice_price_per_kg ?? 15000 }};
        const zakatTypeSelect = document.getElementById('zakat_type');
        const fitrahInput = document.getElementById('fitrah-input');
        const maalInput = document.getElementById('maal-input');
        const nishabInfo = document.getElementById('nishab-info');

        zakatTypeSelect.addEventListener('change', function() {
            const selected = zakatTypeSelect.options[zakatTypeSelect.selectedIndex];
            const unit = selected.dataset.unit;
            const nishab = parseFloat(selected.dataset.nishab || 0);

            fitrahInput.classList.toggle('hidden', unit !== 'fixed_per_soul');
            maalInput.classList.toggle('hidden', unit !== 'nishab_percentage');

            if (unit === 'nishab_percentage') {
                nishabInfo.textContent = 'Batas nishab: Rp ' + nishab.toLocaleString('id-ID');
            }

            document.getElementById('result').classList.add('hidden');
        });

        document.getElementById('calculate-button').addEventListener('click', function() {
            const selected = zakatTypeSelect.options[zakatTypeSelect.selectedIndex];
            if (!selected.value) {
                alert('Pilih jenis zakat terlebih dahulu.');
                return;
            }

            const unit = selected.dataset.unit;
            const rate = parseFloat(selected.dataset.rate);
            const nishab = parseFloat(selected.dataset.nishab || 0);

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
                    statusText = 'ℹ️ Harta Anda belum mencapai nishab (Rp ' + nishab.toLocaleString('id-ID') +
                        '), belum wajib zakat.';
                }
            }

            const displayAmount = Math.round(amount);
            document.getElementById('result-status').textContent = statusText;
            document.getElementById('result-amount').textContent = 'Rp ' + displayAmount.toLocaleString('id-ID');
            document.getElementById('result').classList.remove('hidden');

            const params = new URLSearchParams({
                zakat_type_id: selected.value,
                amount: displayAmount,
                ...(calculationBase !== null ? {
                    calculation_base: calculationBase
                } : {}),
                ...(numberOfSouls !== null ? {
                    number_of_souls: numberOfSouls
                } : {}),
                ...(isAboveNishab !== null ? {
                    is_above_nishab: isAboveNishab ? 1 : 0
                } : {}),
            });

            document.getElementById('pay-now-link').href = "{{ route('public.zakat.index') }}?" + params.toString();
        });

        // Trigger initial state
        zakatTypeSelect.dispatchEvent(new Event('change'));
    </script>
@endsection
