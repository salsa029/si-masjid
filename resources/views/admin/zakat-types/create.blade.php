@extends('layouts.admin')

@section('title', 'Tambah Jenis Zakat')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.zakat-types.index') }}"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700"
                aria-label="Kembali ke daftar jenis zakat">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Tambah Jenis Zakat</h2>
                <p class="text-sm text-gray-500">Buat jenis zakat beserta cara perhitungannya</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
            <div
                class="flex items-center gap-3 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white px-8 py-5">
                <div
                    class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                    <i class="fas fa-tag"></i>
                </div>
                <p class="text-sm text-gray-600">Tentukan cara perhitungan zakat, dengan atau tanpa nishab.</p>
            </div>

            <form method="POST" action="{{ route('admin.zakat-types.store') }}" class="p-8">
                @include('admin.zakat-types._form')
            </form>
        </div>
    </div>
@endsection
