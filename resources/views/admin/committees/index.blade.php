{{-- resources/views/admin/committees/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pengurus')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Pengurus</h2>
                <p class="text-sm text-gray-500">Total {{ $committees->total() }} pengurus terdaftar</p>
            </div>
        </div>
        <a href="{{ route('admin.committees.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-plus"></i> Tambah Pengurus
        </a>
    </div>

    {{-- Form Pencarian --}}
    <form method="GET" class="mb-5 rounded-xl border border-gray-100 bg-white p-4 shadow-sm shadow-gray-200/60">
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-[200px] flex-1">
                <label for="search" class="mb-1 block text-xs font-medium text-gray-600">Cari Nama Pengurus</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Ketik nama pengurus..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('admin.committees.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </div>

        {{-- Tampilkan keyword pencarian aktif --}}
        @if (request('search'))
            <div class="mt-3 text-sm text-gray-500">
                <span class="font-medium">Hasil pencarian:</span> "{{ request('search') }}"
                <span class="text-gray-400">({{ $committees->total() }} ditemukan)</span>
            </div>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">Aksi</th>
                    <th class="px-5 py-4">Foto</th>
                    <th class="px-5 py-4">Nama</th>
                    <th class="px-5 py-4">Jabatan</th>
                    <th class="px-5 py-4">Masa Jabatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($committees as $committee)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.committees.edit', $committee) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="delete-committee-{{ $committee->id }}"
                                    action="{{ route('admin.committees.destroy', $committee) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button"
                                    @click="$dispatch('open-modal-delete-committee-{{ $committee->id }}')"
                                    class="font-medium text-red-600">Hapus</button>
                                <x-confirm-modal id="delete-committee-{{ $committee->id }}" title="Hapus Pengurus"
                                    message="Data pengurus ini akan dipindahkan ke Sampah. Lanjutkan?"
                                    formId="delete-committee-{{ $committee->id }}" />
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($committee->photo)
                                <img src="{{ Storage::url($committee->photo) }}"
                                    class="h-10 w-10 rounded-full object-cover shadow-sm ring-2 ring-white">
                            @else
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-300">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $committee->name }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $committee->position }}</td>
                        <td class="px-5 py-3.5 text-gray-500">
                            {{ $committee->term_start?->format('d/m/Y') ?? '-' }} s/d
                            {{ $committee->term_end?->format('d/m/Y') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-users text-xl text-gray-300"></i>
                            </div>
                            @if (request('search'))
                                Tidak ditemukan pengurus dengan nama "{{ request('search') }}".
                            @else
                                Belum ada data pengurus.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $committees->links() }}
    </div>
@endsection
