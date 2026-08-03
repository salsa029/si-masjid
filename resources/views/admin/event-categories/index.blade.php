{{-- resources/views/admin/event-categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kategori Event')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kategori Event</h2>
                <p class="text-sm text-gray-500">Total {{ $eventCategories->total() }} kategori</p>
            </div>
        </div>
        <a href="{{ route('admin.event-categories.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    @if (session('error'))
        <div
            class="mb-4 flex items-center gap-3 rounded-xl border border-red-100 bg-red-50/70 px-4 py-3.5 text-sm text-red-700 shadow-sm">
            <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"><i
                    class="fas fa-exclamation text-xs"></i></span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">Aksi</th>
                    <th class="px-5 py-4">Nama Kategori</th>
                    <th class="px-5 py-4">Slug</th>
                    <th class="px-5 py-4">Jumlah Event</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($eventCategories as $category)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.event-categories.edit', $category) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.event-categories.destroy', $category) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                        title="Hapus">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-medium text-gray-800">{{ $category->name }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $category->slug }}</td>
                        <td class="px-5 py-4">
                            <span
                                class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                {{ $category->events_count }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-folder-open text-xl text-gray-300"></i>
                            </div>
                            Belum ada kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $eventCategories->links() }}</div>
@endsection
