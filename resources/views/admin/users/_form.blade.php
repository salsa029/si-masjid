{{-- resources/views/admin/users/_form.blade.php --}}
@csrf

@php
    $currentRole = old('role', isset($user) ? $user->getRoleNames()->first() : 'jamaah');
@endphp

<div class="space-y-6">
    <div>
        <label for="name" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-id-badge text-xs text-emerald-600"></i> Nama
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}"
            class="@error('name') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        @error('name')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-envelope text-xs text-emerald-600"></i> Email
        </label>
        <input type="email" name="email" id="email" autocomplete="off"
            value="{{ old('email', $user->email ?? '') }}"
            class="@error('email') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        @error('email')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="password" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-lock text-xs text-emerald-600"></i> Kata Sandi
                @if (isset($user))
                    <span class="font-normal text-gray-400">(kosongkan jika tidak diubah)</span>
                @endif
            </label>
            <input type="password" name="password" id="password" autocomplete="new-password"
                class="@error('password') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @error('password')
                <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i
                        class="fas fa-circle-exclamation"></i>{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation"
                class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
                <i class="fas fa-lock text-xs text-emerald-600"></i> Konfirmasi Kata Sandi
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                autocomplete="new-password"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
    </div>

    <div>
        <label for="role" class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-gray-700">
            <i class="fas fa-user-shield text-xs text-emerald-600"></i> Role
        </label>
        <select name="role" id="role"
            class="@error('role') border-red-400 ring-2 ring-red-100 @enderror w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected($currentRole === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <p class="mt-1.5 text-xs text-gray-400">Pilih "Admin" untuk memberi akses penuh ke Admin Panel.</p>
        @error('role')
            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600"><i class="fas fa-circle-exclamation"></i>
                {{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</div>
