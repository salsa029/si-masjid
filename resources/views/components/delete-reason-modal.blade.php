{{-- resources/views/components/delete-reason-modal.blade.php --}}
@props(['id', 'title', 'message', 'formId'])
<div x-data="{ open: false, reason: '', otherReason: '' }" @open-modal-{{ $id }}.window="open = true">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl" @click.outside="open = false">
            <h3 class="mb-2 font-semibold text-gray-800">{{ $title }}</h3>
            <p class="mb-4 text-sm text-gray-500">{{ $message }}</p>

            <label class="mb-1 block text-xs font-medium text-gray-600">Alasan Penghapusan</label>
            <select x-model="reason" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">— Pilih alasan —</option>
                <option value="Salah nominal atau metode pembayaran">Salah nominal atau metode pembayaran</option>
                <option value="Tidak jadi berdonasi">Tidak jadi berdonasi</option>
                <option value="Transaksi uji coba / tidak sengaja">Transaksi uji coba / tidak sengaja</option>
                <option value="Ingin membuat transaksi baru">Ingin membuat transaksi baru</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <div x-show="reason === 'Lainnya'" x-cloak class="mt-2">
                <textarea x-model="otherReason" rows="2" placeholder="Jelaskan alasan Anda..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="open = false"
                    class="rounded-lg bg-gray-100 px-4 py-2 text-sm">Batal</button>
                <button type="button"
                    :disabled="!reason || (reason === 'Lainnya' && !otherReason.trim())"
                    @click="
                        document.getElementById('{{ $formId }}-reason').value = reason === 'Lainnya' ? otherReason : reason;
                        document.getElementById('{{ $formId }}').submit();
                    "
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white disabled:cursor-not-allowed disabled:opacity-50">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
