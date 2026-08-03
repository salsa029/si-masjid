@extends('layouts.guest')

@section('title', 'Lupa Kata Sandi')

@section('content')
    <div>
        <!-- Header -->
        <div class="mb-6 text-center">
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
                    <i class="fas fa-key text-2xl text-amber-600" aria-hidden="true"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Lupa Kata Sandi</h2>
            <p class="mt-1 text-sm text-gray-500">Tenang, kami akan kirimkan tautan reset password</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                {{ session('status') }}
            </div>
        @endif

        <p class="mb-6 text-sm text-gray-600">
            Lupa kata sandi? Tidak masalah. Masukkan alamat email Anda, dan kami akan mengirimkan
            tautan untuk membuat kata sandi baru.
        </p>

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" value="Email" class="text-sm font-medium text-gray-700" />
                <x-text-input id="email" class="form-input mt-1 block w-full rounded-xl" type="email" name="email"
                    :value="old('email')" required autofocus placeholder="contoh@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Submit -->
            <button type="submit" :disabled="submitting"
                class="mt-6 w-full rounded-xl bg-gradient-to-r from-green-600 to-green-700 py-3.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
                <span x-show="!submitting">
                    <i class="fas fa-paper-plane mr-2" aria-hidden="true"></i>
                    Kirim Tautan Reset
                </span>
                <span x-show="submitting" x-cloak>
                    <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Mengirim...
                </span>
            </button>

            <!-- Back Link -->
            <p class="mt-4 text-center text-sm text-gray-500">
                <a href="{{ route('login') }}" class="font-medium text-green-600 transition hover:text-green-800">
                    <i class="fas fa-arrow-left mr-1" aria-hidden="true"></i>
                    Kembali ke halaman masuk
                </a>
            </p>
        </form>
    </div>
@endsection
