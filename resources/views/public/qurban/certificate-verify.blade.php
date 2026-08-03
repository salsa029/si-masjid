@extends('layouts.app')

@section('title', 'Verifikasi Sertifikat Kurban')

@section('content')
    <section class="bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto max-w-xl px-4">
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg">
                @if ($qurbanOrder)
                    <div class="bg-green-600 px-6 py-8 text-center text-white">
                        <div
                            class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-white/20">
                            <i class="fas fa-check text-2xl" aria-hidden="true"></i>
                        </div>
                        <h1 class="text-lg font-bold">Sertifikat Terverifikasi</h1>
                        <p class="mt-1 text-sm text-green-50">Sertifikat ini sah dan tercatat di sistem
                            {{ config('app.name') }}.</p>
                    </div>

                    <div class="space-y-4 p-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-sm text-gray-500">No. Sertifikat</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $qurbanOrder->certificate_number }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-sm text-gray-500">Diberikan Kepada</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $qurbanOrder->user->name }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-sm text-gray-500">Hewan Kurban</span>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ ucfirst($qurbanOrder->animal->animal_type) }} — {{ $qurbanOrder->animal->name }}
                            </span>
                        </div>
                        @if ($qurbanOrder->animal->activity)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <span class="text-sm text-gray-500">Kegiatan Qurban</span>
                                <span class="text-sm font-semibold text-gray-800">{{ $qurbanOrder->animal->activity->name }}</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <span class="text-sm text-gray-500">Tanggal</span>
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $qurbanOrder->animal->activity->date?->translatedFormat('d F Y') }}
                                </span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Diselenggarakan Oleh</span>
                            <span class="text-sm font-semibold text-gray-800">{{ config('app.name') }}</span>
                        </div>
                    </div>
                @else
                    <div class="bg-red-500 px-6 py-8 text-center text-white">
                        <div
                            class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-white/20">
                            <i class="fas fa-times text-2xl" aria-hidden="true"></i>
                        </div>
                        <h1 class="text-lg font-bold">Sertifikat Tidak Ditemukan</h1>
                        <p class="mt-1 text-sm text-red-50">Nomor sertifikat "{{ $certificateNumber }}" tidak
                            tercatat atau tidak valid.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
