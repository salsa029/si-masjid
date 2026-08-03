{{-- resources/views/admin/events/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Kegiatan')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Kegiatan</h2>
                <p class="text-sm text-gray-500">Total {{ $events->total() }} kegiatan</p>
            </div>
        </div>
        <a href="{{ route('admin.events.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-plus"></i> Tambah Kegiatan
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET"
        class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm shadow-gray-200/60">
        <div class="min-w-[200px] flex-1">
            <label class="mb-1 block text-xs font-medium text-gray-600">Cari Judul</label>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">Kategori</label>
            <select name="category"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">Waktu</label>
            <select name="time_status"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua Waktu</option>
                <option value="upcoming" @selected(request('time_status') === 'upcoming')>Akan Datang</option>
                <option value="ongoing" @selected(request('time_status') === 'ongoing')>Sedang Berlangsung</option>
                <option value="finished" @selected(request('time_status') === 'finished')>Selesai</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">Publikasi</label>
            <select name="status"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua</option>
                <option value="draft" @selected(request('status') === 'draft')>Draf</option>
                <option value="published" @selected(request('status') === 'published')>Dipublikasikan</option>
            </select>
        </div>
        <button type="submit"
            class="rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md">
            <i class="fas fa-search mr-1"></i> Cari
        </button>
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">Judul</th>
                    <th class="px-5 py-4">Kategori</th>
                    <th class="px-5 py-4">Jadwal</th>
                    <th class="px-5 py-4">Waktu</th>
                    <th class="px-5 py-4">Publikasi</th>
                    <th class="px-5 py-4">Dilihat</th>
                    <th class="px-5 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($events as $event)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-4 font-medium text-gray-800">
                            {{ $event->title }}
                            @if ($event->is_featured)
                                <i class="fas fa-star ml-1 text-amber-400" title="Event Unggulan"></i>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-500">{{ $event->category?->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $event->start_at->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $timeColor = match ($event->time_status) {
                                    'upcoming' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
                                    'ongoing' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                                    'finished' => 'bg-gray-50 text-gray-500 ring-1 ring-inset ring-gray-200',
                                };
                            @endphp
                            <span
                                class="{{ $timeColor }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">{{ $event->time_status_label }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if ($event->status === 'published')
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Dipublikasikan</span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">Draf</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-500">{{ number_format($event->views_count) }}x</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.events.edit', $event) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="delete-event-{{ $event->id }}"
                                    action="{{ route('admin.events.destroy', $event) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" @click="$dispatch('open-modal-delete-event-{{ $event->id }}')"
                                    class="font-medium text-red-600">Hapus</button>
                                <x-confirm-modal id="delete-event-{{ $event->id }}" title="Hapus Kegiatan"
                                    message="Kegiatan &quot;{{ $event->title }}&quot; akan dipindahkan ke Sampah. Lanjutkan?"
                                    formId="delete-event-{{ $event->id }}" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-calendar-alt text-xl text-gray-300"></i>
                            </div>
                            Tidak ada kegiatan yang cocok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $events->links() }}</div>
@endsection
