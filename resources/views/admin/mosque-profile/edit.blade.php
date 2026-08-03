@extends('layouts.admin')

@section('title', 'Profil Masjid')

@section('content')
    <div class="max-w-3xl">
        <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm">
            {{-- Header --}}
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <i class="fas fa-mosque text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Profil Masjid</h2>
                    <p class="text-sm text-gray-500">Kelola informasi dan tampilan profil masjid Anda</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.mosque-profile.update') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Masjid --}}
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
                        <i class="fas fa-building mr-1.5 text-emerald-600"></i> Nama Masjid <span
                            class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $mosqueProfile?->name ?? '') }}"
                        class="@error('name') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Masukkan nama masjid" required>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Sejarah --}}
                <div>
                    <label for="history" class="mb-1.5 block text-sm font-medium text-gray-700">
                        <i class="fas fa-scroll mr-1.5 text-emerald-600"></i> Sejarah
                    </label>
                    <textarea id="history" name="history" rows="4"
                        class="@error('history') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Tulis sejarah singkat berdirinya masjid">{{ old('history', $mosqueProfile?->history ?? '') }}</textarea>
                    @error('history')
                        <p class="mt-1.5 text-xs text-red-600"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Visi & Misi --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="vision" class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="fas fa-eye mr-1.5 text-emerald-600"></i> Visi
                        </label>
                        <textarea id="vision" name="vision" rows="3"
                            class="@error('vision') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            placeholder="Visi masjid">{{ old('vision', $mosqueProfile?->vision ?? '') }}</textarea>
                        @error('vision')
                            <p class="mt-1.5 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mission" class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="fas fa-bullseye mr-1.5 text-emerald-600"></i> Misi
                        </label>
                        <textarea id="mission" name="mission" rows="3"
                            class="@error('mission') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            placeholder="Misi masjid">{{ old('mission', $mosqueProfile?->mission ?? '') }}</textarea>
                        @error('mission')
                            <p class="mt-1.5 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700">
                        <i class="fas fa-location-dot mr-1.5 text-emerald-600"></i> Alamat <span
                            class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2"
                        class="@error('address') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        placeholder="Alamat lengkap masjid" required>{{ old('address', $mosqueProfile?->address ?? '') }}</textarea>
                    @error('address')
                        <p class="mt-1.5 text-xs text-red-600"><i
                                class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kontak & Rekening --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="contact" class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="fas fa-phone mr-1.5 text-emerald-600"></i> Kontak
                        </label>
                        <input type="text" id="contact" name="contact"
                            value="{{ old('contact', $mosqueProfile?->contact ?? '') }}"
                            class="@error('contact') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            placeholder="Nomor telepon/WA">
                        @error('contact')
                            <p class="mt-1.5 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="bank_account_number" class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="fas fa-university mr-1.5 text-emerald-600"></i> Nomor Rekening
                        </label>
                        <input type="text" id="bank_account_number" name="bank_account_number"
                            value="{{ old('bank_account_number', $mosqueProfile?->bank_account_number ?? '') }}"
                            class="@error('bank_account_number') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            placeholder="Contoh: 1234567890">
                        @error('bank_account_number')
                            <p class="mt-1.5 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Koordinat --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="latitude" class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="fas fa-arrow-up mr-1.5 text-emerald-600"></i> Latitude
                        </label>
                        <input type="text" id="latitude" name="latitude"
                            value="{{ old('latitude', $mosqueProfile?->latitude ?? '') }}"
                            class="@error('latitude') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            placeholder="Contoh: -6.200000">
                        <p class="mt-1 text-xs text-gray-400">Koordinat untuk Google Maps</p>
                        @error('latitude')
                            <p class="mt-1.5 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="longitude" class="mb-1.5 block text-sm font-medium text-gray-700">
                            <i class="fas fa-arrow-right mr-1.5 text-emerald-600"></i> Longitude
                        </label>
                        <input type="text" id="longitude" name="longitude"
                            value="{{ old('longitude', $mosqueProfile?->longitude ?? '') }}"
                            class="@error('longitude') border-red-500 ring-2 ring-red-200 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            placeholder="Contoh: 106.816666">
                        @error('longitude')
                            <p class="mt-1.5 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Hero Image --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        <i class="fas fa-image mr-1.5 text-emerald-600"></i> Hero Image
                    </label>
                    @if ($mosqueProfile?->hero_image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $mosqueProfile->hero_image) }}"
                                class="h-28 w-48 rounded-lg border border-gray-200 object-cover shadow-sm"
                                alt="Hero Image Masjid">
                        </div>
                    @endif
                    <input type="file" id="hero_image" name="hero_image" accept="image/*"
                        class="w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="mt-1 text-xs text-gray-400">Format JPG, PNG, atau WebP. Maksimal 2 MB. Kosongkan jika tidak
                        ingin mengganti.</p>
                    @error('hero_image')
                        <p class="mt-1.5 text-xs text-red-600"><i
                                class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-800 hover:shadow-md">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
