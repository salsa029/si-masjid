@props(['id', 'title', 'message', 'formId'])
<div x-data="{ open: false }" @open-modal-{{ $id }}.window="open = true">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl" @click.outside="open = false">
            <h3 class="mb-2 font-semibold text-gray-800">{{ $title }}</h3>
            <p class="mb-6 text-sm text-gray-500">{{ $message }}</p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="open = false"
                    class="rounded-lg bg-gray-100 px-4 py-2 text-sm">Batal</button>
                <button type="button" @click="document.getElementById('{{ $formId }}').submit()"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
