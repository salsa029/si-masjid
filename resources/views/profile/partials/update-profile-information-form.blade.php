<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
      x-data="{ submitting: false, preview: null }" @submit="submitting = true"
      class="space-y-5">
      @csrf
      @method('patch')

      <!-- Avatar -->
      <div class="flex items-center gap-4">
          <div class="h-20 w-20 flex-shrink-0">
              <template x-if="preview">
                  <img :src="preview" alt="Pratinjau foto profil" class="h-20 w-20 rounded-full object-cover ring-2 ring-green-100">
              </template>
              <template x-if="!preview">
                  <div>
                      @if ($user->avatar_url)
                          <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                              class="h-20 w-20 rounded-full object-cover ring-2 ring-green-100">
                      @else
                          <div
                              class="flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-2xl font-bold text-green-700 ring-2 ring-green-100">
                              {{ strtoupper(substr($user->name, 0, 1)) }}
                          </div>
                      @endif
                  </div>
              </template>
          </div>
          <div class="flex-1">
              <x-input-label for="avatar" value="Foto Profil" class="text-sm font-medium text-gray-700" />
              <input id="avatar" type="file" name="avatar" accept="image/png,image/jpeg,image/webp"
                  @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                  class="mt-1 block w-full text-xs text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-green-700 hover:file:bg-green-100" />
              <p class="mt-1 text-xs text-gray-400">
                  @if ($user->google_id)
                      Foto mengikuti akun Google saat login. Unggah foto di sini untuk memakai foto kustom (maks. 2MB).
                  @else
                      Format JPG, PNG, atau WebP. Maks. 2MB.
                  @endif
              </p>
              <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
          </div>
      </div>

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
