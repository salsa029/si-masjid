@extends('layouts.admin')

@section('title', 'Kelola Qurban Activity')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Qurban Activity</h2>
                <p class="text-sm text-gray-500">Total {{ $qurbanActivities->total() }} kegiatan qurban</p>
            </div>
        </div>
        <a href="{{ route('admin.qurban-activities.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-plus"></i> Tambah Qurban Activity
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">Aksi</th>
                    <th class="px-5 py-4">Qurban Nama</th>
                    <th class="px-5 py-4">Tanggal</th>
                    <th class="px-5 py-4">Ketua DKM</th>
                    <th class="px-5 py-4">Ketua Kurban</th>
                    <th class="px-5 py-4">Jml Hewan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($qurbanActivities as $activity)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.qurban-activities.edit', $activity) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="delete-qurban-activity-{{ $activity->id }}"
                                    action="{{ route('admin.qurban-activities.destroy', $activity) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button"
                                    @click="$dispatch('open-modal-delete-qurban-activity-{{ $activity->id }}')"
                                    class="font-medium text-red-600">Hapus</button>
                                <x-confirm-modal id="delete-qurban-activity-{{ $activity->id }}"
                                    title="Hapus Qurban Activity"
                                    message="Data kegiatan qurban ini akan dipindahkan ke Sampah. Lanjutkan?"
                                    formId="delete-qurban-activity-{{ $activity->id }}" />
                            </div>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $activity->name }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $activity->date?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $activity->dkm_chairman_name ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $activity->qurban_chairman_name ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $activity->animals_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-calendar-check text-xl text-gray-300"></i>
                            </div>
                            Belum ada data Qurban Activity.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $qurbanActivities->links() }}
    </div>
@endsection
