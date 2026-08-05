@csrf

<div class="space-y-5">
    {{-- Row 0: Qurban Activity (wajib dipilih dahulu) --}}
    <div>
        <label class="mb-1 block text-sm font-medium">Qurban Activity</label>
        @php $currentActivityId = old('qurban_activity_id', $sacrificialAnimal->qurban_activity_id ?? request('qurban_activity_id', '')); @endphp
        <select name="qurban_activity_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <option value="">— Pilih Qurban Activity —</option>
            @foreach ($qurbanActivities as $activity)
                <option value="{{ $activity->id }}" @selected((string) $currentActivityId === (string) $activity->id)>
                    {{ $activity->name }} ({{ $activity->start_date?->format('d/m/Y') }} &ndash;
                    {{ $activity->end_date?->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-400">Hewan kurban harus terhubung ke sebuah Qurban Activity.
            <a href="{{ route('admin.qurban-activities.create') }}" class="text-emerald-700 hover:underline">Belum ada?
                Tambah Qurban Activity baru.</a>
        </p>
        @error('qurban_activity_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Row 1: Jenis Hewan & Nama/Kode Hewan --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Jenis Hewan</label>
            @php $currentType = old('animal_type', $sacrificialAnimal->animal_type ?? 'sapi'); @endphp
            <select name="animal_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="sapi" @selected($currentType === 'sapi')>Sapi</option>
                <option value="kambing" @selected($currentType === 'kambing')>Kambing</option>
                <option value="domba" @selected($currentType === 'domba')>Domba</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Nama/Kode Hewan</label>
            <input type="text" name="name" value="{{ old('name', $sacrificialAnimal->name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 2: Bobot, Usia, Harga --}}
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Bobot (kg)</label>
            <input type="number" step="0.01" name="weight"
                value="{{ old('weight', $sacrificialAnimal->weight ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('weight')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Usia (bulan)</label>
            <input type="number" name="age" value="{{ old('age', $sacrificialAnimal->age ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('age')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $sacrificialAnimal->price ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('price')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ✅ Row 3: Nama Paket & Rincian Paket (DITAMBAHKAN) --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Nama Paket (Opsional)</label>
            <input type="text" name="package_name"
                value="{{ old('package_name', $sacrificialAnimal->package_name ?? '') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                placeholder="Contoh: Paket Hemat, Paket Premium">
            @error('package_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Rincian Paket (Opsional)</label>
            <textarea name="package_description" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                placeholder="Contoh: Termasuk pemotongan, pengemasan 20 kantong, dan distribusi ke warga sekitar masjid.">{{ old('package_description', $sacrificialAnimal->package_description ?? '') }}</textarea>
            @error('package_description')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row 4: Maksimal Peserta & Status --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Maksimal Peserta Patungan</label>
            <input type="number" name="max_participants" min="1" max="7"
                value="{{ old('max_participants', $sacrificialAnimal->max_participants ?? 1) }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            <p class="mt-1 text-xs text-gray-400">Isi 7 untuk sapi (patungan), isi 1 untuk kambing/domba (per ekor).</p>
            @error('max_participants')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Status</label>
            @php $currentStatus = old('status', $sacrificialAnimal->status ?? 'available'); @endphp
            <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="available" @selected($currentStatus === 'available')>Tersedia</option>
                <option value="fully_booked" @selected($currentStatus === 'fully_booked')>Kuota Penuh</option>
                <option value="slaughtered" @selected($currentStatus === 'slaughtered')>Sudah Disembelih</option>
            </select>
        </div>
    </div>

    {{-- Row 5: Foto Hewan --}}
    <div>
        <label class="mb-1 block text-sm font-medium">Foto Hewan</label>

        @if (!empty($sacrificialAnimal) && $sacrificialAnimal->photo)
            <img src="{{ Storage::url($sacrificialAnimal->photo) }}"
                class="mb-2 h-24 w-32 rounded-lg border border-gray-200 object-cover">
        @endif

        <input type="file" name="photo" accept="image/*" class="w-full text-sm">
        <p class="mt-1 text-xs text-gray-400">Format JPG/PNG/WebP, maksimal 2 MB.</p>
        @error('photo')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit Button --}}
    <button type="submit"
        class="rounded-lg bg-emerald-700 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-800">
        Simpan
    </button>
</div>
