@extends('layouts.admin')

@section('title', 'Kelola Hewan Kurban')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-cow"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Hewan Kurban</h2>
                <p class="text-sm text-gray-500">Total {{ $animals->total() }} hewan kurban terdaftar</p>
            </div>
        </div>
        <a href="{{ route('admin.sacrificial-animals.create') }}{{ $selectedActivityId ? '?qurban_activity_id=' . $selectedActivityId : '' }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-plus"></i> Tambah Hewan Kurban
        </a>
    </div>

    @if ($qurbanActivities->isEmpty())
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
            <i class="fas fa-triangle-exclamation mr-1" aria-hidden="true"></i>
            Belum ada Qurban Activity. Silakan
            <a href="{{ route('admin.qurban-activities.create') }}" class="font-semibold underline">tambah Qurban
                Activity</a> terlebih dahulu sebelum mengelola hewan kurban.
        </div>
    @else
        {{-- Pilih Qurban Activity — wajib dipilih, hanya hewan dari kegiatan ini yang ditampilkan --}}
        <div class="mb-5 rounded-xl border border-emerald-100 bg-emerald-50/60 p-4">
            <label for="qurban_activity_id" class="mb-1 block text-xs font-semibold text-emerald-800">
                <i class="fas fa-calendar-check mr-1" aria-hidden="true"></i> Menampilkan Hewan Kurban untuk Kegiatan
            </label>
            <select name="qurban_activity_id" id="qurban_activity_id" form="filter-animals-form"
                onchange="document.getElementById('filter-animals-form').submit()"
                class="w-full max-w-md rounded-lg border border-emerald-300 bg-white px-3 py-2 text-sm font-medium text-emerald-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 sm:w-auto">
                @foreach ($qurbanActivities as $activity)
                    <option value="{{ $activity->id }}" @selected((string) $selectedActivityId === (string) $activity->id)>
                        {{ $activity->name }} ({{ $activity->start_date->format('d/m/Y') }} &ndash;
                        {{ $activity->end_date->format('d/m/Y') }}){{ ! $activity->is_open ? ' — Selesai' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Form Pencarian & Filter --}}
    <form method="GET" id="filter-animals-form" class="mb-5 rounded-xl border border-gray-100 bg-white p-4 shadow-sm shadow-gray-200/60">
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-[180px] flex-1">
                <label for="search" class="mb-1 block text-xs font-medium text-gray-600">Cari Nama/Kode Hewan</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Ketik nama atau kode..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div class="min-w-[150px]">
                <label for="animal_type" class="mb-1 block text-xs font-medium text-gray-600">Jenis Hewan</label>
                <select name="animal_type" id="animal_type"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">Semua Jenis</option>
                    <option value="sapi" @selected(request('animal_type') === 'sapi')>Sapi</option>
                    <option value="kambing" @selected(request('animal_type') === 'kambing')>Kambing</option>
                    <option value="domba" @selected(request('animal_type') === 'domba')>Domba</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label for="status" class="mb-1 block text-xs font-medium text-gray-600">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">Semua Status</option>
                    <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                    <option value="fully_booked" @selected(request('status') === 'fully_booked')>Kuota Penuh</option>
                    <option value="slaughtered" @selected(request('status') === 'slaughtered')>Sudah Disembelih</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('admin.sacrificial-animals.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </div>

        {{-- Tampilkan filter aktif --}}
        @if (request('search') || request('animal_type') || request('status'))
            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                <span class="font-medium">Filter aktif:</span>
                @if (request('search'))
                    <span
                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">
                        <i class="fas fa-search text-[10px]"></i> "{{ request('search') }}"
                    </span>
                @endif
                @if (request('animal_type'))
                    <span
                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs capitalize text-emerald-700">
                        <i class="fas fa-tag text-[10px]"></i> {{ request('animal_type') }}
                    </span>
                @endif
                @if (request('status'))
                    <span
                        class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs text-emerald-700">
                        <i class="fas fa-circle text-[8px]"></i>
                        @php
                            $statusLabel = match (request('status')) {
                                'available' => 'Tersedia',
                                'fully_booked' => 'Kuota Penuh',
                                'slaughtered' => 'Sudah Disembelih',
                            };
                        @endphp
                        {{ $statusLabel }}
                    </span>
                @endif
                <span class="text-gray-400">({{ $animals->total() }} ditemukan)</span>
            </div>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">Aksi</th>
                    <th class="px-5 py-4">Nama/Kode</th>
                    <th class="px-5 py-4">Jenis</th>
                    <th class="px-5 py-4">Bobot</th>
                    <th class="px-5 py-4">Harga</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Dokumentasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($animals as $animal)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.sacrificial-animals.edit', $animal) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="delete-animal-{{ $animal->id }}"
                                    action="{{ route('admin.sacrificial-animals.destroy', $animal) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" @click="$dispatch('open-modal-delete-animal-{{ $animal->id }}')"
                                    class="font-medium text-red-600">Hapus</button>
                                <x-confirm-modal id="delete-animal-{{ $animal->id }}" title="Hapus Hewan Kurban"
                                    message="Data hewan kurban ini akan dipindahkan ke Sampah. Lanjutkan?"
                                    formId="delete-animal-{{ $animal->id }}" />
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $animal->name }}</td>
                        <td class="px-5 py-3.5 capitalize text-gray-600">{{ $animal->animal_type }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $animal->weight }} kg</td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">Rp
                            {{ number_format($animal->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5">
                            @php
                                $label = match ($animal->status) {
                                    'available' => 'Tersedia',
                                    'fully_booked' => 'Kuota Penuh',
                                    'slaughtered' => 'Sudah Disembelih',
                                };
                                $color = match ($animal->status) {
                                    'available' => 'bg-emerald-100 text-emerald-700',
                                    'fully_booked' => 'bg-amber-100 text-amber-700',
                                    'slaughtered' => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span
                                class="{{ $color }} rounded-full px-3 py-1 text-xs font-medium">{{ $label }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-image text-gray-300"></i>
                                {{ $animal->documentations_count }} foto
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-cow text-xl text-gray-300"></i>
                            </div>
                            @if (request('search') || request('animal_type') || request('status'))
                                Tidak ditemukan hewan kurban dengan filter yang dipilih.
                            @else
                                Belum ada data hewan kurban.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $animals->links() }}
    </div>
@endsection
