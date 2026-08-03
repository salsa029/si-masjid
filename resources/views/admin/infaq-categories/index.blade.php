@extends('layouts.admin')

@section('title', 'Kategori Infaq')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total {{ $infaqCategories->total() }} kategori</p>
        <a href="{{ route('admin.infaq-categories.create') }}"
            class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-800">
            + Tambah Kategori
        </a>
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-5 py-3">Nama Kategori</th>
                    <th class="px-5 py-3">Slug</th>
                    <th class="px-5 py-3">Jumlah Transaksi Infaq</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($infaqCategories as $category)
                    <tr class="border-t border-gray-100">
                        <td class="px-5 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $category->slug }}</td>
                        <td class="px-5 py-3">{{ $category->infaqs_count }}</td>
                        <td class="space-x-2 px-5 py-3 text-right">
                            <a href="{{ route('admin.infaq-categories.edit', $category) }}"
                                class="text-emerald-700 hover:underline">Ubah</a>
                            <form action="{{ route('admin.infaq-categories.destroy', $category) }}" method="POST"
                                class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $infaqCategories->links() }}</div>
@endsection
