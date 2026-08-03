@extends('layouts.admin')

@section('title', 'Pengaturan Donasi')

@section('content')
    <div class="max-w-3xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.donation-settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $settings->bank_name ?? '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('bank_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nomor Rekening</label>
                    <input type="text" name="bank_account_number"
                        value="{{ old('bank_account_number', $settings->bank_account_number ?? '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('bank_account_number')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="mb-1 block text-sm font-medium text-gray-700">Nama Pemilik Rekening</label>
                <input type="text" name="bank_account_name"
                    value="{{ old('bank_account_name', $settings->bank_account_name ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('bank_account_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="mb-1 block text-sm font-medium text-gray-700">Gambar QRIS</label>
                @if ($settings && $settings->qris_image)
                    <img src="{{ Storage::url($settings->qris_image) }}"
                        class="mb-2 h-32 w-32 rounded-lg border border-gray-200 object-contain">
                @endif
                <input type="file" name="qris_image" accept="image/*" class="w-full text-sm">
                @error('qris_image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nominal Minimum Infaq</label>
                    <input type="number" name="min_infaq_amount"
                        value="{{ old('min_infaq_amount', $settings->min_infaq_amount ?? 10000) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('min_infaq_amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Nominal Minimum Zakat</label>
                    <input type="number" name="min_zakat_amount"
                        value="{{ old('min_zakat_amount', $settings->min_zakat_amount ?? 10000) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('min_zakat_amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Harga Beras per Kg</label>
                    <input type="number" name="rice_price_per_kg"
                        value="{{ old('rice_price_per_kg', $settings->rice_price_per_kg ?? 15000) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('rice_price_per_kg')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Harga Emas per Gram</label>
                    <input type="number" name="gold_price_per_gram"
                        value="{{ old('gold_price_per_gram', $settings->gold_price_per_gram ?? 1200000) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('gold_price_per_gram')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="mb-1 block text-sm font-medium text-gray-700">Pesan Terima Kasih</label>
                <textarea name="thank_you_message" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('thank_you_message', $settings->thank_you_message ?? '') }}</textarea>
            </div>

            <div class="mt-4">
                <label class="mb-1 block text-sm font-medium text-gray-700">Nomor WhatsApp Konfirmasi</label>
                <input type="text" name="confirmation_whatsapp"
                    value="{{ old('confirmation_whatsapp', $settings->confirmation_whatsapp ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('confirmation_whatsapp')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="mt-6 rounded-lg bg-emerald-700 px-6 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
