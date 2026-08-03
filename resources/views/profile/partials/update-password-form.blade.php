<form method="POST" action="{{ route('password.update') }}" x-data="{ submitting: false }" @submit="submitting = true"
    class="space-y-4">
    @csrf
    @method('put')

    <div>
        <x-input-label for="current_password" value="Kata Sandi Saat Ini" class="text-sm font-medium text-gray-700" />
        <x-text-input id="current_password" class="form-input mt-1 block w-full rounded-xl" type="password"
            name="current_password" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('current_password')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="password" value="Kata Sandi Baru" class="text-sm font-medium text-gray-700" />
        <x-text-input id="password" class="form-input mt-1 block w-full rounded-xl" type="password" name="password"
            required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru"
            class="text-sm font-medium text-gray-700" />
        <x-text-input id="password_confirmation" class="form-input mt-1 block w-full rounded-xl" type="password"
            name="password_confirmation" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
    </div>

    <button type="submit" :disabled="submitting"
        class="rounded-xl bg-gradient-to-r from-green-600 to-green-700 px-6 py-2.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
        <span x-show="!submitting">Ubah Kata Sandi</span>
        <span x-show="submitting" x-cloak>
            <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Memproses...
        </span>
    </button>

    @if (session('status') === 'password-updated')
        <p class="mt-2 text-sm text-green-600">
            <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
            Kata sandi berhasil diubah.
        </p>
    @endif
</form>
