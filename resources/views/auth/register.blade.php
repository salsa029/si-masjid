@extends('layouts.guest')

@section('title', 'Daftar Akun')

@section('content')
    <div class="mx-auto w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl md:p-8">

        <!-- Header -->
        <div class="mb-6 text-center">
            <div
                class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-700 to-emerald-500 shadow-lg shadow-emerald-200 transition-transform duration-300 hover:scale-110">
                <i class="fas fa-mosque text-2xl text-white drop-shadow-md" aria-hidden="true"></i>
            </div>
            <h2 class="text-xl font-extrabold tracking-tight text-gray-800">Daftar Akun</h2>
        </div>

        <!-- Google Register -->
        <a href="{{ route('auth.google') }}"
            class="flex w-full items-center justify-center gap-2.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-sm transition duration-200 hover:bg-gray-50">
            <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M24 9.5C27.6 9.5 30.8 10.8 33.3 13.1L41.5 4.9C37.2 1.1 31.1 -0.5 24 0.3C14.9 1.2 7.1 7.5 3.8 15.7L13.9 23.4C16.2 15.3 22.1 9.5 24 9.5Z"
                    fill="#FBBC05" />
                <path
                    d="M47 24.5C47 22.5 46.8 20.7 46.4 19H24V30.4H37.2C36.2 34.5 33.6 38.1 30 40.6L39.5 49.4C44.8 44.5 47 35.4 47 24.5Z"
                    fill="#4285F4" />
                <path
                    d="M14.1 28.7C13.3 26.5 13.3 24.1 14.1 21.9L4 14.2C1.3 19.6 0 25.6 0 32C0 38.4 1.3 44.4 4 49.8L14.1 42.1C13.3 39.9 13.3 37.5 14.1 35.3C14.9 33.1 16.3 31.3 18.2 30.1C16.3 28.9 14.9 27.1 14.1 24.9V28.7Z"
                    fill="#EA4335" />
                <path
                    d="M24 47.5C21.1 47.5 18.3 46.6 15.9 45L6.9 53.2C11.1 56.5 16.6 48.5 24 48.5C30.6 48.5 36.6 46.2 41.5 41.4L32 32.6C29.4 35.2 26.2 37.1 24 37.1C21.8 37.1 18.6 35.2 16 32.6L7.3 23.9C5.3 27.7 5.3 32.3 7.3 36.1L16 44.8C18.6 47.4 21.8 49.3 24 49.3C26.2 49.3 29.4 47.4 32 44.8L41.5 36.1C44.8 32.8 46.9 28.8 47.5 24.5H24V47.5Z"
                    fill="#34A853" />
            </svg>
            <span>Daftar dengan Google</span>
        </a>

        <!-- Divider -->
        <div class="relative flex items-center py-3">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="mx-3 flex-shrink text-[10px] font-bold uppercase tracking-wider text-gray-400">atau</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" x-data="{ loading: false }" @submit="loading = true"
            class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="mb-1 block text-xs font-bold text-gray-700">Nama Lengkap</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-3.5 text-xs text-emerald-500">
                        <i class="fas fa-user" aria-hidden="true"></i>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        autocomplete="name" placeholder="Masukkan nama lengkap Anda"
                        class="@error('name') border-red-500 bg-red-50 @enderror w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-4 text-xs outline-none transition duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />
                </div>
                @error('name')
                    <p class="mt-1 flex items-center gap-1 text-[11px] text-red-600">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="mb-1 block text-xs font-bold text-gray-700">Alamat Email</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-3.5 text-xs text-emerald-500">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        autocomplete="email" placeholder="contoh@email.com"
                        class="@error('email') border-red-500 bg-red-50 @enderror w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-4 text-xs outline-none transition duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />
                </div>
                @error('email')
                    <p class="mt-1 flex items-center gap-1 text-[11px] text-red-600">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div x-data="{ showPassword: false, strength: 0, text: '', class: '' }">
                <label for="password" class="mb-1 block text-xs font-bold text-gray-700">Kata Sandi</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-3.5 text-xs text-emerald-500">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                    </div>
                    <input id="password" x-bind:type="showPassword ? 'text' : 'password'" name="password" required
                        autocomplete="new-password" placeholder="Minimal 8 karakter"
                        class="@error('password') border-red-500 bg-red-50 @enderror w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-10 text-xs outline-none transition duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                        x-on:input="
                            let s = 0;
                            let val = $event.target.value;
                            if (val.length >= 8) s++;
                            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) s++;
                            if (/\d/.test(val)) s++;
                            if (/[^a-zA-Z\d]/.test(val)) s++;
                            const levels = ['', 'Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
                            const classes = ['', 'weak', 'weak', 'medium', 'strong', 'very-strong'];
                            strength = s;
                            text = levels[s] || '';
                            class = classes[s] || '';
                        " />
                    <button type="button"
                        class="absolute right-3 p-1 text-xs text-emerald-500 hover:text-emerald-700 focus:outline-none"
                        @click="showPassword = !showPassword" aria-label="Tampilkan Password">
                        <i class="fas" x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Password Strength -->
                <div class="mt-1">
                    <div class="h-1 w-full overflow-hidden rounded-full bg-gray-200" x-show="strength > 0">
                        <div class="h-full rounded-full transition-all duration-500"
                            x-bind:class="{
                                'bg-red-500': class === 'weak',
                                'bg-yellow-500': class === 'medium',
                                'bg-emerald-500': class === 'strong',
                                'bg-emerald-600': class === 'very-strong'
                            }"
                            x-bind:style="'width: ' + (strength * 25) + '%'"></div>
                    </div>
                    <p class="mt-1 text-[10px] font-medium transition-colors duration-300"
                        x-bind:class="{
                            'text-red-500': class === 'weak',
                            'text-yellow-500': class === 'medium',
                            'text-emerald-500': class === 'strong',
                            'text-emerald-600': class === 'very-strong'
                        }"
                        x-text="text" x-show="strength > 0"></p>
                </div>
                @error('password')
                    <p class="mt-1 flex items-center gap-1 text-[11px] text-red-600">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1 text-[10px] text-gray-400">
                    <i class="fas fa-info-circle mr-1" aria-hidden="true"></i>
                    Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol
                </p>
            </div>

            <!-- Confirm Password -->
            <div x-data="{ showConfirmPassword: false }">
                <label for="password_confirmation" class="mb-1 block text-xs font-bold text-gray-700">Konfirmasi Kata
                    Sandi</label>
                <div class="relative flex items-center">
                    <div class="pointer-events-none absolute left-3.5 text-xs text-emerald-500">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                    </div>
                    <input id="password_confirmation" x-bind:type="showConfirmPassword ? 'text' : 'password'"
                        name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ketik ulang kata sandi Anda"
                        class="@error('password_confirmation') border-red-500 bg-red-50 @enderror w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-10 text-xs outline-none transition duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" />
                    <button type="button"
                        class="absolute right-3 p-1 text-xs text-emerald-500 hover:text-emerald-700 focus:outline-none"
                        @click="showConfirmPassword = !showConfirmPassword" aria-label="Tampilkan Konfirmasi Password">
                        <i class="fas" x-bind:class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"
                            aria-hidden="true"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-1 flex items-center gap-1 text-[11px] text-red-600">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Terms -->
            <div class="flex items-start gap-2 pt-1">
                <input id="terms" type="checkbox" name="terms" required
                    class="mt-0.5 h-4 w-4 cursor-pointer rounded border-gray-300 text-emerald-600 shadow-sm transition focus:ring-emerald-500 focus:ring-offset-0" />
                <label for="terms" class="cursor-pointer select-none text-xs text-gray-600">
                    Saya menyetujui
                    <a href="#" class="font-medium text-emerald-600 hover:text-emerald-800 hover:underline">Syarat &
                        Ketentuan</a>
                    dan
                    <a href="#"
                        class="font-medium text-emerald-600 hover:text-emerald-800 hover:underline">Kebijakan Privasi</a>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-emerald-700 to-emerald-500 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 transition duration-200 hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-70"
                x-bind:disabled="loading">
                <template x-if="loading">
                    <i class="fas fa-spinner fa-spin text-xs" aria-hidden="true"></i>
                </template>
                <template x-if="!loading">
                    <i class="fas fa-user-plus text-xs" aria-hidden="true"></i>
                </template>
                <span x-text="loading ? 'Memproses...' : 'Daftar Sekarang'"></span>
            </button>

            <!-- Login Link -->
            <p class="pt-2 text-center text-xs text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                    Masuk sekarang
                </a>
            </p>
        </form>
    </div>
@endsection
