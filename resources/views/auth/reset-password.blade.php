@extends('layouts.guest')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
    <div>
        <!-- Header -->
        <div class="mb-6 text-center">
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <i class="fas fa-lock text-2xl text-green-600" aria-hidden="true"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Atur Ulang Kata Sandi</h2>
            <p class="mt-1 text-sm text-gray-500">Buat kata sandi baru untuk akun Anda</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('password.store') }}" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <!-- Email (hidden) -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <input type="hidden" name="email" value="{{ $request->email }}">

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Kata Sandi Baru" class="text-sm font-medium text-gray-700" />
                <x-text-input id="password" class="form-input mt-1 block w-full rounded-xl" type="password" name="password"
                    required autofocus autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi"
                    class="text-sm font-medium text-gray-700" />
                <x-text-input id="password_confirmation" class="form-input mt-1 block w-full rounded-xl" type="password"
                    name="password_confirmation" required autocomplete="new-password"
                    placeholder="Ketik ulang kata sandi baru" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Submit -->
            <button type="submit" :disabled="submitting"
                class="mt-6 w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
                <span x-show="!submitting">Atur Ulang Kata Sandi</span>
                <span x-show="submitting" x-cloak>
                    <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Memproses...
                </span>
            </button>
        </form>
    </div>
@endsection
