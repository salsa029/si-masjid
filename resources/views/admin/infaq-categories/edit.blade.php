@extends('layouts.admin')

@section('title', 'Ubah Kategori Infaq')

@section('content')
    <div class="max-w-md rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.infaq-categories.update', $infaqCategory) }}">
            @csrf
            @method('PUT')
            <label class="mb-1 block text-sm font-medium">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $infaqCategory->name) }}"
                class="mb-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('name')
                <p class="mb-3 text-xs text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="mt-3 rounded-lg bg-emerald-700 px-5 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                Simpan
            </button>
        </form>
    </div>
@endsection
