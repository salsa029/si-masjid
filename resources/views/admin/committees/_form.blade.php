{{-- resources/views/admin/committees/_form.blade.php --}}
@csrf

<div class="space-y-6">
    <div>
        <label for="name" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-id-badge text-xs text-emerald-600"></i> Nama Pengurus
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $committee->name ?? '') }}"
            class="@error('name') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        @error('name')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="position" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-briefcase text-xs text-emerald-600"></i> Jabatan
        </label>
        <input type="text" name="position" id="position" value="{{ old('position', $committee->position ?? '') }}"
            class="@error('position') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        @error('position')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="bio" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-align-left text-xs text-emerald-600"></i> Bio Singkat
        </label>
        <textarea name="bio" id="bio" rows="3"
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('bio', $committee->bio ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="term_start" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-calendar-day text-xs text-emerald-600"></i> Awal Masa Jabatan
            </label>
            <input type="date" name="term_start" id="term_start"
                value="{{ old('term_start', $committee->term_start?->format('Y-m-d') ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
        <div>
            <label for="term_end" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-calendar-check text-xs text-emerald-600"></i> Akhir Masa Jabatan
            </label>
            <input type="date" name="term_end" id="term_end"
                value="{{ old('term_end', $committee->term_end?->format('Y-m-d') ?? '') }}"
                class="@error('term_end') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @error('term_end')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-image text-xs text-emerald-600"></i> Foto
        </label>

        @if (!empty($committee) && $committee->photo)
            <div class="mb-3">
                <img src="{{ Storage::url($committee->photo) }}"
                    class="h-20 w-20 rounded-lg border border-gray-200 object-cover shadow-sm">
            </div>
        @endif

        <div
            class="rounded-xl border border-dashed border-gray-300 bg-gray-50/60 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/40">
            <input type="file" name="photo" accept="image/*"
                class="w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white file:transition hover:file:bg-emerald-700">
        </div>
        <p class="mt-1.5 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
        @error('photo')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('admin.committees.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</div>
