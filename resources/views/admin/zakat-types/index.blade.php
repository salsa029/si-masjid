@extends('layouts.admin')

@section('title', 'Jenis Zakat')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total {{ $zakatTypes->total() }} jenis zakat</p>
        <a href="{{ route('admin.zakat-types.create') }}"
            class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
            + Tambah Jenis Zakat
        </a>
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Nama</th>
                    <th class="px-5 py-3">Cara Perhitungan</th>
                    <th class="px-5 py-3">Nishab</th>
                    <th class="px-5 py-3">Persentase</th>
                    <th class="px-5 py-3">Jumlah Transaksi</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zakatTypes as $type)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 font-medium">{{ $type->name }}</td>
                        <td class="px-5 py-3">
                            {{ $type->calculation_unit === 'nishab_percentage' ? 'Persentase dgn Nishab' : 'Tetap per Jiwa' }}
                        </td>
                        <td class="px-5 py-3">
                            {{ $type->nishab_amount ? 'Rp ' . number_format($type->nishab_amount, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-5 py-3">{{ $type->rate_percentage ? $type->rate_percentage . '%' : '-' }}</td>
                        <td class="px-5 py-3">{{ $type->zakats_count }}</td>
                        <td class="space-x-2 px-5 py-3 text-right">
                            <a href="{{ route('admin.zakat-types.edit', $type) }}"
                                class="text-emerald-700 hover:underline">Ubah</a>

                            {{-- Form Hapus dengan Modal Konfirmasi --}}
                            <form id="delete-zakattype-{{ $type->id }}"
                                action="{{ route('admin.zakat-types.destroy', $type) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" @click="$dispatch('open-modal-delete-zakattype-{{ $type->id }}')"
                                class="font-medium text-red-600 transition-colors hover:text-red-800">
                                Hapus
                            </button>
                            <x-confirm-modal id="delete-zakattype-{{ $type->id }}" title="Hapus Jenis Zakat"
                                message="Jenis zakat &quot;{{ $type->name }}&quot; akan dihapus. Lanjutkan?"
                                formId="delete-zakattype-{{ $type->id }}" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada jenis zakat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $zakatTypes->links() }}</div>
@endsection
