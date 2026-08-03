@extends('layouts.admin')

@section('title', 'Ubah Data Hewan Kurban')

@section('content')

    <div class="mb-8 max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.sacrificial-animals.update', $sacrificialAnimal) }}"
            enctype="multipart/form-data">
            @method('PUT')
            @include('admin.sacrificial-animals._form')
        </form>
    </div>

    <div class="max-w-2xl rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="mb-4 font-semibold">Dokumentasi Penyembelihan</h2>

        <form method="POST" action="{{ route('admin.sacrificial-animals.documentations.store', $sacrificialAnimal) }}"
            enctype="multipart/form-data" class="mb-6 space-y-3">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium">Pilih Foto (bisa lebih dari 1)</label>
                <input type="file" name="photos[]" accept="image/*" multiple class="w-full text-sm">
                @error('photos')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Keterangan (Opsional)</label>
                <input type="text" name="description" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <button type="submit"
                class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
                Unggah Foto
            </button>
        </form>

        @if ($sacrificialAnimal->documentations->isEmpty())
            <p class="text-sm text-gray-400">Belum ada dokumentasi penyembelihan untuk hewan ini.</p>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($sacrificialAnimal->documentations as $documentation)
                    <div class="group relative">
                        <img src="{{ Storage::url($documentation->photo) }}"
                            class="h-28 w-full rounded-lg border border-gray-200 object-cover">

                        <form
                            action="{{ route('admin.sacrificial-animals.documentations.destroy', [$sacrificialAnimal, $documentation]) }}"
                            method="POST" onsubmit="return confirm('Hapus foto ini?');" class="absolute right-1 top-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-md bg-white/90 px-2 py-1 text-xs text-red-600 shadow hover:bg-white">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
