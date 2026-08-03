{{-- resources/views/admin/event-categories/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ubah Kategori Event')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.event-categories.index') }}"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700"
                aria-label="Kembali ke daftar kategori event">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Ubah Kategori Event</h2>
                <p class="text-sm text-gray-500">Perbarui informasi kategori</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
            <div
                class="flex items-center gap-3 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white px-8 py-5">
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                    <i class="fas fa-pen"></i>
                </div>
                <p class="text-sm text-gray-600">Perubahan nama akan memperbarui slug kategori.</p>
            </div>

            <form method="POST" action="{{ route('admin.event-categories.update', $eventCategory) }}" class="p-8">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $eventCategory->name) }}"
                        class="@error('name') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                    @error('name')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                                class="fas fa-circle-exclamation"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 flex items-center gap-3 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.event-categories.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
