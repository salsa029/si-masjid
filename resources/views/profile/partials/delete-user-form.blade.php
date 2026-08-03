<div x-data="{ confirmDelete: false }">
    <p class="mb-4 text-sm text-gray-600">
        Setelah akun Anda dihapus, semua data dan transaksi Anda akan dihapus secara permanen.
        Sebelum menghapus akun, silakan unduh data atau informasi yang Anda perlukan.
    </p>

    <button type="button" @click="confirmDelete = true"
        class="rounded-xl bg-red-600 px-6 py-2.5 font-semibold text-white transition hover:bg-red-700">
        <i class="fas fa-trash-alt mr-2" aria-hidden="true"></i>
        Hapus Akun
    </button>

    <!-- Confirmation Modal -->
    <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" @click.outside="confirmDelete = false">
            <h3 class="mb-2 text-xl font-bold text-gray-800">Hapus Akun</h3>
            <p class="mb-6 text-sm text-gray-600">
                Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf
                @method('delete')

                <div>
                    <x-input-label for="password" value="Kata Sandi" class="text-sm font-medium text-gray-700" />
                    <x-text-input id="password" class="form-input mt-1 block w-full rounded-xl" type="password"
                        name="password" required placeholder="Masukkan kata sandi untuk konfirmasi" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" @click="confirmDelete = false"
                        class="flex-1 rounded-xl border border-gray-300 py-2.5 font-medium text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" :disabled="submitting"
                        class="flex-1 rounded-xl bg-red-600 py-2.5 font-medium text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-show="!submitting">Hapus Permanen</span>
                        <span x-show="submitting" x-cloak>
                            <i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i> Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
