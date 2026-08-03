{{-- resources/views/admin/events/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ubah Kegiatan')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.events.index') }}"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700"
                aria-label="Kembali ke daftar kegiatan">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Ubah Kegiatan</h2>
                <p class="text-sm text-gray-500">Perbarui detail dan dokumentasi kegiatan</p>
            </div>
        </div>

        {{-- Form Detail Kegiatan --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
            <div
                class="flex items-center gap-3 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white px-8 py-5">
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                    <i class="fas fa-pen"></i>
                </div>
                <p class="text-sm text-gray-600">Perubahan akan tersimpan sesuai status yang dipilih.</p>
            </div>

            <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data"
                class="p-8">
                @method('PUT')
                @include('admin.events._form')
            </form>
        </div>

        {{-- Dokumentasi Foto --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
            <div
                class="flex items-center gap-3 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white px-8 py-5">
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                    <i class="fas fa-images"></i>
                </div>
                <p class="text-sm font-medium text-gray-700">Dokumentasi Foto Kegiatan</p>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('admin.events.documentations.store', $event) }}"
                    enctype="multipart/form-data" class="mb-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="fas fa-images text-xs text-emerald-600"></i> Pilih Foto (bisa lebih dari 1)
                        </label>
                        <div
                            class="rounded-xl border border-dashed border-gray-300 bg-gray-50/60 p-4 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                            <input type="file" name="photos[]" accept="image/*" multiple
                                class="w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white file:transition hover:file:bg-emerald-700">
                        </div>
                        @error('photos')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                        @error('photos.*')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                                    class="fas fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            <i class="fas fa-message text-xs text-emerald-600"></i> Keterangan (Opsional, berlaku
                            untuk semua foto yang diunggah)
                        </label>
                        <input type="text" name="caption"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
                        <i class="fas fa-upload"></i> Unggah Foto
                    </button>
                </form>

                @if ($event->documentations->isEmpty())
                    <div class="rounded-xl border border-dashed border-gray-200 py-10 text-center text-sm text-gray-400">
                        <i class="fas fa-image mb-2 block text-2xl text-gray-300"></i>
                        Belum ada foto dokumentasi untuk kegiatan ini.
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        @foreach ($event->documentations as $documentation)
                            <div class="group relative overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                                <img src="{{ Storage::url($documentation->photo) }}" class="h-28 w-full object-cover">

                                <form action="{{ route('admin.events.documentations.destroy', [$event, $documentation]) }}"
                                    method="POST" onsubmit="return confirm('Hapus foto ini?');"
                                    class="absolute right-1.5 top-1.5">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/90 text-red-600 shadow-sm transition hover:bg-white">
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </form>

                                @if ($documentation->caption)
                                    <p class="truncate bg-white px-2 py-1.5 text-xs text-gray-500">
                                        {{ $documentation->caption }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
