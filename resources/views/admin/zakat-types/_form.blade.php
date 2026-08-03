@csrf

<div class="space-y-5">
    <div>
        <label class="mb-1 block text-sm font-medium">Nama Jenis Zakat</label>
        <input type="text" name="name" value="{{ old('name', $zakatType->name ?? '') }}"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="Contoh: Zakat Maal">
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('description', $zakatType->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium">Cara Perhitungan</label>
        @php $currentUnit = old('calculation_unit', $zakatType->calculation_unit ?? 'nishab_percentage'); @endphp
        <select name="calculation_unit" id="calculation_unit"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="nishab_percentage" @selected($currentUnit === 'nishab_percentage')>Persentase dari Harta (dengan Nishab) — cth:
                Maal, Penghasilan</option>
            <option value="fixed_per_soul" @selected($currentUnit === 'fixed_per_soul')>Tetap per Jiwa (tanpa Nishab) — cth: Fitrah
            </option>
        </select>
    </div>

    <div id="nishab-fields">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium">Nilai Nishab (Rp)</label>
                <input type="number" name="nishab_amount"
                    value="{{ old('nishab_amount', $zakatType->nishab_amount ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('nishab_amount')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Persentase Zakat (%)</label>
                <input type="number" step="0.1" name="rate_percentage"
                    value="{{ old('rate_percentage', $zakatType->rate_percentage ?? 2.5) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>
        <label class="mb-1 mt-4 block text-sm font-medium">Penjelasan Nishab</label>
        <textarea name="nishab_description" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
            placeholder="Contoh: Setara 85 gram emas">{{ old('nishab_description', $zakatType->nishab_description ?? '') }}</textarea>
    </div>

    <button type="submit"
        class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-800">
        Simpan
    </button>
</div>

<script>
    // Sembunyikan field Nishab jika Admin memilih "Tetap per Jiwa" — murni bantuan visual, validasi tetap di server
    const unitSelect = document.getElementById('calculation_unit');
    const nishabFields = document.getElementById('nishab-fields');

    function toggleNishabFields() {
        nishabFields.style.display = unitSelect.value === 'nishab_percentage' ? 'block' : 'none';
    }
    unitSelect.addEventListener('change', toggleNishabFields);
    toggleNishabFields();
</script>
