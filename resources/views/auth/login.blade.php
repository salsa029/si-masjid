@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="mx-auto w-full max-w-md rounded-2xl bg-white p-6 shadow-xl md:p-8">

        <!-- Header -->
        <div class="mb-6 text-center">
            <div
                class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-md shadow-emerald-200">
                <i class="fas fa-mosque text-2xl"></i>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight text-gray-800">Selamat Datang</h1>
            <p class="mt-1 text-xs text-gray-500">
                {{ $mosqueName ?? 'Masjid An-Nur' }}
            </p>
        </div>

        <!-- Alert Status -->
        @if (session('status'))
            <div
                class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-medium text-emerald-700">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Google Button -->
        @if (Route::has('auth.google'))
            <a href="{{ route('auth.google') }}"
                class="flex w-full items-center justify-center gap-2.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition duration-200 hover:bg-gray-50">
                <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
                        fill="#EA4335" />
                </svg>
                <span>Masuk dengan Google</span>
            </a>

            <!-- Divider -->
            <div class="relative flex items-center py-3">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="mx-3 flex-shrink text-[10px] font-bold uppercase tracking-wider text-gray-400">atau</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" x-data="{ loading: false }" @submit="loading = true"
            class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="mb-1 block text-xs font-bold text-gray-700">Email</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-3.5 text-xs text-emerald-500">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" placeholder="nama@email.com"
                        class="@error('email') border-red-500 bg-red-50 @enderror w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-4 text-xs outline-none transition duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />
                </div>
                @error('email')
                    <p class="mt-1 flex items-center gap-1 text-[11px] text-red-600">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div x-data="{ showPassword: false }">
                <div class="mb-1 flex items-center justify-between">
                    <label for="password" class="block text-xs font-bold text-gray-700">Kata Sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                            Lupa sandi?
                        </a>
                    @endif
                </div>

                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-3.5 text-xs text-emerald-500">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input id="password" x-bind:type="showPassword ? 'text' : 'password'" name="password" required
                        autocomplete="current-password" placeholder="••••••••"
                        class="@error('password') border-red-500 bg-red-50 @enderror w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-10 text-xs outline-none transition duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />

                    <button type="button"
                        class="absolute right-3 p-1 text-xs text-emerald-500 hover:text-emerald-700 focus:outline-none"
                        @click="showPassword = !showPassword" aria-label="Tampilkan Password">
                        <i class="fas" x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 flex items-center gap-1 text-[11px] text-red-600">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2 pt-1">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 cursor-pointer rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" />
                <label for="remember_me" class="cursor-pointer select-none text-xs text-gray-600">Ingat saya</label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 transition duration-200 hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-70"
                x-bind:disabled="loading">
                <template x-if="loading">
                    <i class="fas fa-spinner fa-spin text-xs"></i>
                </template>
                <template x-if="!loading">
                    <i class="fas fa-sign-in-alt text-xs"></i>
                </template>
                <span x-text="loading ? 'Memproses...' : 'Masuk'"></span>
            </button>

            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="pt-2 text-center text-xs text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                        class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                        Daftar
                    </a>
                </p>
            @endif
        </form>
    </div>
@endsection
