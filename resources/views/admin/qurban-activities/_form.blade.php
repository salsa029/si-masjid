@csrf

<div class="space-y-5">
    {{-- Row 1: Nama Kegiatan & Tanggal --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Qurban Nama</label>
            <input type="text" name="name" value="{{ old('name', $qurbanActivity->name ?? '') }}"
                placeholder="Contoh: Qurban 2026"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Tanggal</label>
            <input type="date" name="date"
                value="{{ old('date', isset($qurbanActivity) ? $qurbanActivity->date?->format('Y-m-d') : '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 2: Deskripsi --}}
    <div>
        <label class="mb-1 block text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
            placeholder="Contoh: Terima kasih kami ucapkan atas kepercayaannya menitipkan hewan qurban di Masjid...">{{ old('description', $qurbanActivity->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Row 3: Ketua DKM & TTD Ketua DKM --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Ketua DKM</label>
            <input type="text" name="dkm_chairman_name"
                value="{{ old('dkm_chairman_name', $qurbanActivity->dkm_chairman_name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('dkm_chairman_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">TTD Ketua DKM</label>

            @if (!empty($qurbanActivity) && $qurbanActivity->dkm_chairman_signature)
                <img src="{{ Storage::url($qurbanActivity->dkm_chairman_signature) }}"
                    class="mb-2 h-16 w-32 rounded-lg border border-gray-200 object-contain bg-white">
            @endif

            <input type="file" name="dkm_chairman_signature" accept="image/*" class="w-full text-sm">
            <p class="mt-1 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
            @error('dkm_chairman_signature')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 4: Ketua Kurban & Foto Ketua Kurban --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Ketua Kurban</label>
            <input type="text" name="qurban_chairman_name"
                value="{{ old('qurban_chairman_name', $qurbanActivity->qurban_chairman_name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('qurban_chairman_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Foto/TTD Ketua Kurban</label>

            @if (!empty($qurbanActivity) && $qurbanActivity->qurban_chairman_photo)
                <img src="{{ Storage::url($qurbanActivity->qurban_chairman_photo) }}"
                    class="mb-2 h-16 w-32 rounded-lg border border-gray-200 object-contain bg-white">
            @endif

            <input type="file" name="qurban_chairman_photo" accept="image/*" class="w-full text-sm">
            <p class="mt-1 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
            @error('qurban_chairman_photo')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 5: Total Balance (Kg) - hanya tampil saat edit, dihitung otomatis dari total bobot Hewan Kurban terkait --}}
    @if (!empty($qurbanActivity))
        <div>
            <label class="mb-1 block text-sm font-medium">Total Balance (Kg)</label>
            <input type="text" value="{{ number_format($qurbanActivity->total_balance_kg, 2, ',', '.') }} kg" disabled
                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-600">
            <p class="mt-1 text-xs text-gray-400">Dihitung otomatis dari total bobot seluruh Hewan Kurban pada Activity
                ini.</p>
        </div>
    @endif

    {{-- Submit Button --}}
    <button type="submit"
        class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-800">
        Simpan
    </button>
</div>
