@extends('layouts.guest')

@section('title', 'Konfirmasi Kata Sandi')

@section('content')
    <div>
        <!-- Header -->
        <div class="mb-6 text-center">
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
                    <i class="fas fa-shield-alt text-2xl text-amber-600" aria-hidden="true"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Konfirmasi Kata Sandi</h2>
            <p class="mt-1 text-sm text-gray-500">Verifikasi identitas Anda untuk melanjutkan</p>
        </div>

        <p class="mb-6 text-sm text-gray-600">
            Ini adalah area aman aplikasi. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
        </p>

        <!-- Form -->
        <form method="POST" action="{{ route('password.confirm') }}" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Kata Sandi" class="text-sm font-medium text-gray-700" />
                <x-text-input id="password" class="form-input mt-1 block w-full rounded-xl" type="password" name="password"
                    required autofocus autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Submit -->
            <button type="submit" :disabled="submitting"
                class="mt-6 w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
                <span x-show="!submitting">Konfirmasi</span>
                <span x-show="submitting" x-cloak>
                    <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Memproses...
                </span>
            </button>
        </form>
    </div>
@endsection
