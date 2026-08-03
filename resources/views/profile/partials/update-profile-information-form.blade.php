      x-data="{ submitting: false }" @submit="submitting = true"
      class="space-y-4">
      @csrf
      @method('patch')

      <div>
          <x-input-label for="name" value="Nama Lengkap" class="text-sm font-medium text-gray-700" />
          <x-text-input id="name" class="form-input mt-1 block w-full rounded-xl" type="text" name="name"
              :value="old('name', $user->name)" required autofocus autocomplete="name" />
          <x-input-error :messages="$errors->get('name')" class="mt-1" />
      </div>

      <div>
          <x-input-label for="email" value="Email" class="text-sm font-medium text-gray-700" />
          <x-text-input id="email" class="form-input mt-1 block w-full rounded-xl" type="email" name="email"
              :value="old('email', $user->email)" required autocomplete="email" />
          <x-input-error :messages="$errors->get('email')" class="mt-1" />

          @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
              <div class="mt-2 text-sm text-amber-600">
                  Email Anda belum diverifikasi.
                  <form method="POST" action="{{ route('verification.send') }}" class="inline">
                      @csrf
                      <button type="submit" class="underline hover:text-amber-800">Kirim ulang verifikasi</button>
                  </form>
              </div>
          @endif
      </div>

      <!-- Phone -->
      <div>
          <x-input-label for="phone" value="Nomor Telepon (Opsional)" class="text-sm font-medium text-gray-700" />
          <x-text-input id="phone" class="form-input mt-1 block w-full rounded-xl" type="text" name="phone"
              :value="old('phone', $user->phone)" placeholder="0812-3456-7890" />
          <x-input-error :messages="$errors->get('phone')" class="mt-1" />
      </div>

      <button type="submit" :disabled="submitting"
          class="rounded-xl bg-gradient-to-r from-green-600 to-green-700 px-6 py-2.5 font-semibold text-white transition hover:from-green-700 hover:to-green-800 disabled:cursor-not-allowed disabled:opacity-50">
          <span x-show="!submitting">Simpan Perubahan</span>
          <span x-show="submitting" x-cloak>
              <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Menyimpan...
          </span>
      </button>

      @if (session('status') === 'profile-updated')
          <p class="mt-2 text-sm text-green-600">
              <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
              Profil berhasil diperbarui.
          </p>
      @endif
      </form>
