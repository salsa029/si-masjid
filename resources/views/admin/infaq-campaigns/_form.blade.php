@csrf

<div class="space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Kategori</label>
            <select name="category_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $infaqCampaign->category_id ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Status</label>
            @php $currentStatus = old('status', $infaqCampaign->status ?? 'active'); @endphp
            <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="active" @selected($currentStatus === 'active')>Aktif</option>
                <option value="completed" @selected($currentStatus === 'completed')>Selesai</option>
                <option value="closed" @selected($currentStatus === 'closed')>Ditutup</option>
            </select>
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium">Judul Campaign</label>
        <input type="text" name="title" value="{{ old('title', $infaqCampaign->title ?? '') }}"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('title')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium">Deskripsi</label>
        <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('description', $infaqCampaign->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Target Dana (Rp)</label>
            <input type="number" name="target_amount"
                value="{{ old('target_amount', $infaqCampaign->target_amount ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('target_amount')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Tanggal Mulai</label>
            <input type="date" name="start_date"
                value="{{ old('start_date', isset($infaqCampaign) ? $infaqCampaign->start_date?->format('Y-m-d') : '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('start_date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Tanggal Selesai (Opsional)</label>
            <input type="date" name="end_date"
                value="{{ old('end_date', isset($infaqCampaign) ? $infaqCampaign->end_date?->format('Y-m-d') : '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('end_date')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium">Thumbnail</label>
        @if (!empty($infaqCampaign) && $infaqCampaign->thumbnail)
            <img src="{{ Storage::url($infaqCampaign->thumbnail) }}"
                class="mb-2 h-20 w-32 rounded-lg border border-gray-200 object-cover">
        @endif
        <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm">
        <p class="mt-1 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
        @error('thumbnail')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
        class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-800">
        Simpan
    </button>
</div>
