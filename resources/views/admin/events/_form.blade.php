{{-- resources/views/admin/events/_form.blade.php --}}
@csrf

<div class="space-y-8">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="title" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-heading text-xs text-emerald-600"></i> Judul Kegiatan
            </label>
            <input type="text" name="title" id="title" value="{{ old('title', $event->title ?? '') }}"
                class="@error('title') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @error('title')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="category_id" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-tags text-xs text-emerald-600"></i> Kategori
            </label>
            <select name="category_id" id="category_id"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Tanpa Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $event->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="excerpt" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-align-left text-xs text-emerald-600"></i> Ringkasan (Excerpt)
        </label>
        <input type="text" name="excerpt" id="excerpt" maxlength="255"
            value="{{ old('excerpt', $event->excerpt ?? '') }}"
            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
            placeholder="Ringkasan singkat, maksimal 1-2 kalimat">
        <p class="mt-1 text-xs text-gray-400">Tampil di kartu daftar kegiatan &amp; hasil pencarian, maksimal 255
            karakter.</p>
    </div>

    <div>
        <label for="description" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-file-lines text-xs text-emerald-600"></i> Deskripsi Lengkap
        </label>
        <textarea name="description" id="description" rows="5"
            class="@error('description') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">{{ old('description', $event->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="location" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-location-dot text-xs text-emerald-600"></i> Lokasi
            </label>
            <input type="text" name="location" id="location" value="{{ old('location', $event->location ?? '') }}"
                class="@error('location') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @error('location')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="speaker_name" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-microphone text-xs text-emerald-600"></i> Nama Pemateri (Opsional)
            </label>
            <input type="text" name="speaker_name" id="speaker_name"
                value="{{ old('speaker_name', $event->speaker_name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="latitude" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-map-pin text-xs text-emerald-600"></i> Latitude Lokasi (Opsional)
            </label>
            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $event->latitude ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                placeholder="-6.200000">
            <p class="mt-1 text-xs text-gray-400">Kosongkan jika ingin peta mencari otomatis dari teks Lokasi.</p>
        </div>
        <div>
            <label for="longitude" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-map-pin text-xs text-emerald-600"></i> Longitude Lokasi (Opsional)
            </label>
            <input type="text" name="longitude" id="longitude"
                value="{{ old('longitude', $event->longitude ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                placeholder="106.816666">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="start_at" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-calendar-day text-xs text-emerald-600"></i> Waktu Mulai
            </label>
            <input type="datetime-local" name="start_at" id="start_at"
                value="{{ old('start_at', isset($event) ? $event->start_at->format('Y-m-d\TH:i') : '') }}"
                class="@error('start_at') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @error('start_at')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="end_at" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-calendar-check text-xs text-emerald-600"></i> Waktu Selesai
            </label>
            <input type="datetime-local" name="end_at" id="end_at"
                value="{{ old('end_at', isset($event) ? $event->end_at->format('Y-m-d\TH:i') : '') }}"
                class="@error('end_at') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @error('end_at')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="registration_status"
                class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-clipboard-list text-xs text-emerald-600"></i> Status Pendaftaran
            </label>
            @php $currentRegistration = old('registration_status', $event->registration_status ?? 'open'); @endphp
            <select name="registration_status" id="registration_status"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="open" @selected($currentRegistration === 'open')>Dibuka</option>
                <option value="closed" @selected($currentRegistration === 'closed')>Ditutup</option>
                <option value="full" @selected($currentRegistration === 'full')>Kuota Penuh</option>
            </select>
        </div>
        <div>
            <label for="status" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-circle-check text-xs text-emerald-600"></i> Status Publikasi
            </label>
            @php $currentStatus = old('status', $event->status ?? 'draft'); @endphp
            <select name="status" id="status"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="draft" @selected($currentStatus === 'draft')>Draf</option>
                <option value="published" @selected($currentStatus === 'published')>Dipublikasikan</option>
            </select>
        </div>
    </div>

    <label
        class="flex cursor-pointer items-center gap-2.5 rounded-lg border border-gray-200 bg-gray-50/60 px-4 py-3 text-sm text-gray-700 transition hover:bg-emerald-50/40">
        <input type="checkbox" name="is_featured" value="1"
            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
            {{ old('is_featured', $event->is_featured ?? false) ? 'checked' : '' }}>
        Jadikan Event Unggulan (Featured) — akan tampil menonjol di Beranda
    </label>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-image text-xs text-emerald-600"></i> Thumbnail (untuk kartu daftar)
            </label>
            @if (!empty($event) && $event->thumbnail)
                <div class="mb-3">
                    <img src="{{ Storage::url($event->thumbnail) }}"
                        class="h-24 w-full rounded-lg border border-gray-200 object-cover shadow-sm">
                </div>
            @endif
            <div
                class="rounded-xl border border-dashed border-gray-300 bg-gray-50/60 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                <input type="file" name="thumbnail" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white file:transition hover:file:bg-emerald-700">
            </div>
            @error('thumbnail')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-panorama text-xs text-emerald-600"></i> Poster (banner halaman detail)
            </label>
            @if (!empty($event) && $event->poster)
                <div class="mb-3">
                    <img src="{{ Storage::url($event->poster) }}"
                        class="h-24 w-full rounded-lg border border-gray-200 object-cover shadow-sm">
                </div>
            @endif
            <div
                class="rounded-xl border border-dashed border-gray-300 bg-gray-50/60 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                <input type="file" name="poster" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white file:transition hover:file:bg-emerald-700">
            </div>
            <p class="mt-1 text-xs text-gray-400">Disarankan resolusi lebih besar/lebar dari thumbnail.</p>
            @error('poster')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('admin.events.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</div>
