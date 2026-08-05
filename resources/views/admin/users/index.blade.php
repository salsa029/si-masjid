{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-users-gear"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola User</h2>
                <p class="text-sm text-gray-500">Daftar seluruh akun yang terdaftar &amp; kelola admin</p>
            </div>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-user-plus"></i> Tambah User
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total User Terdaftar</p>
            <p class="mt-1 text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Admin</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $totalAdmins }}</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Jamaah</p>
            <p class="mt-1 text-2xl font-bold text-emerald-700">{{ $totalJamaah }}</p>
        </div>
    </div>

    {{-- Form Pencarian & Filter --}}
    <form method="GET" class="mb-5 rounded-xl border border-gray-100 bg-white p-4 shadow-sm shadow-gray-200/60">
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-[220px] flex-1">
                <label for="search" class="mb-1 block text-xs font-medium text-gray-600">Cari Nama/Email</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Ketik nama atau email..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
            </div>
            <div class="min-w-[150px]">
                <label for="role" class="mb-1 block text-xs font-medium text-gray-600">Role</label>
                <select name="role" id="role"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </div>

        @if (request('search') || request('role'))
            <div class="mt-3 text-sm text-gray-500">
                <span class="font-medium">Filter aktif</span>
                <span class="text-gray-400">({{ $users->total() }} ditemukan)</span>
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
                    <th class="px-5 py-4">User</th>
                    <th class="px-5 py-4">Role</th>
                    <th class="px-5 py-4">Terdaftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form id="delete-user-{{ $user->id }}"
                                        action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" @click="$dispatch('open-modal-delete-user-{{ $user->id }}')"
                                        class="font-medium text-red-600">Hapus</button>
                                    <x-confirm-modal id="delete-user-{{ $user->id }}" title="Hapus User"
                                        message="Akun {{ $user->name }} akan dihapus permanen. Lanjutkan?"
                                        formId="delete-user-{{ $user->id }}" />
                                @else
                                    <span class="text-xs text-gray-300" title="Tidak bisa menghapus akun sendiri">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if ($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                        class="h-9 w-9 rounded-full object-cover shadow-sm ring-2 ring-white">
                                @else
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-300">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $user->name }}
                                        @if ($user->id === auth()->id())
                                            <span class="text-xs font-normal text-gray-400">(Anda)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @forelse ($user->roles as $role)
                                <span
                                    class="{{ $role->name === 'admin' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }} rounded-full px-3 py-1 text-xs font-medium">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @empty
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                    Belum ada role
                                </span>
                            @endforelse
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-users text-xl text-gray-300"></i>
                            </div>
                            @if (request('search') || request('role'))
                                Tidak ditemukan user dengan filter yang dipilih.
                            @else
                                Belum ada user terdaftar.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $users->links() }}
    </div>
@endsection
