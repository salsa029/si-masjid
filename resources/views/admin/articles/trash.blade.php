@extends('layouts.admin')

@section('title', 'Sampah Artikel')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-700 text-white shadow-md shadow-gray-900/20">
                <i class="fas fa-trash-can"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Sampah Artikel</h2>
                <p class="text-sm text-gray-500">Artikel yang dihapus akan tersimpan di sini sebelum dihapus permanen.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.articles.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Artikel
        </a>
    </div>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">#</th>
                    <th class="px-5 py-4">Aksi</th>
                    <th class="px-5 py-4">Judul</th>
                    <th class="px-5 py-4">Penulis</th>
                    <th class="px-5 py-4">Dihapus Pada</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($articles as $article)
                    <tr class="transition-colors hover:bg-gray-50">
                        <td class="px-5 py-4 text-gray-400">
                            {{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                <form action="{{ route('admin.articles.restore', $article->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-50 hover:text-emerald-800"
                                        title="Pulihkan">
                                        <i class="fas fa-rotate-left"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.articles.force-delete', $article->id) }}" method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Artikel akan dihapus PERMANEN dan tidak bisa dikembalikan. Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                        title="Hapus Permanen">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="px-5 py-4 font-medium text-gray-800">{{ $article->title }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $article->author->name }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $article->deleted_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-trash-can text-xl text-gray-300"></i>
                            </div>
                            Sampah artikel kosong.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $articles->links() }}</div>
@endsection
