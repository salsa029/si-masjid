{{-- resources/views/admin/audit-trail/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Audit Trail')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Audit Trail</h2>
                <p class="text-sm text-gray-500">Riwayat aktivitas admin di seluruh modul</p>
            </div>
        </div>
        <div class="text-sm text-gray-400">
            Total <span class="font-semibold text-emerald-600">{{ $activities->total() }}</span> aktivitas
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET"
        class="mb-5 flex flex-wrap items-end gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm shadow-gray-200/60">
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">
                <i class="fas fa-cubes mr-1 text-emerald-600"></i> Modul
            </label>
            <select name="module"
                class="rounded-lg border border-gray-300 px-3 py-2.5 pr-8 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua Modul</option>
                @foreach ($availableModules as $module)
                    <option value="{{ $module }}" @selected(request('module') === $module)>{{ ucfirst($module) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">
                <i class="fas fa-calendar-day mr-1 text-emerald-600"></i> Dari
            </label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">
                <i class="fas fa-calendar-day mr-1 text-emerald-600"></i> Sampai
            </label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-filter"></i> Terapkan
        </button>
        @if (request()->hasAny(['module', 'date_from', 'date_to']))
            <a href="{{ route('admin.audit-trail.index') }}"
                class="text-sm text-gray-400 transition hover:text-gray-600 hover:underline">
                <i class="fas fa-undo"></i> Reset
            </a>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">#</th>
                    <th class="px-5 py-4">Admin</th>
                    <th class="px-5 py-4">Modul</th>
                    <th class="px-5 py-4">Aktivitas</th>
                    <th class="px-5 py-4">Waktu</th>
                    <th class="px-5 py-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($activities as $activity)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-4 text-gray-400">
                            {{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}
                        </td>
                        <td class="px-5 py-4 font-medium text-gray-800">
                            <div class="flex items-center gap-2.5">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-xs font-semibold text-white shadow-sm">
                                    {{ strtoupper(substr($activity->causer?->name ?? 'S', 0, 1)) }}
                                </span>
                                {{ $activity->causer?->name ?? 'Sistem' }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span
                                class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium capitalize text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                {{ $activity->log_name }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-700">{{ $activity->description }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                            {{ $activity->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-5 py-4 font-mono text-xs text-gray-500">
                            {{ $activity->properties['ip_address'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-clipboard-list text-xl text-gray-300"></i>
                            </div>
                            Belum ada aktivitas tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $activities->links() }}</div>
@endsection
