@extends('layouts.admin')

@section('title', 'Kelola Artikel')

@section('content')
    {{-- Header --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Kelola Artikel</h2>
                <p class="text-sm text-gray-500">Total {{ $articles->total() }} artikel</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.articles.trash') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50">
                <i class="fas fa-trash-can"></i> Sampah
            </a>
            <a href="{{ route('admin.articles.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2.5 text-sm font-medium text-white shadow-md shadow-emerald-900/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/30">
                <i class="fas fa-pen-to-square"></i> Tulis Artikel
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div
            class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/70 px-4 py-3.5 text-sm text-emerald-700 shadow-sm">
            <span
                class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600"><i
                    class="fas fa-check text-xs"></i></span>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-4 flex items-center gap-3 rounded-xl border border-red-100 bg-red-50/70 px-4 py-3.5 text-sm text-red-700 shadow-sm">
            <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600"><i
                    class="fas fa-exclamation text-xs"></i></span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET"
        class="mb-5 flex flex-wrap items-end gap-3 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm shadow-gray-200/60">
        <div class="min-w-[180px] flex-1">
            <label class="mb-1 block text-xs font-medium text-gray-600">Cari Judul</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul artikel..."
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">Kategori</label>
            <select name="category"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-600">Status</label>
            <select name="status"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draf</option>
                <option value="published" @selected(request('status') === 'published')>Terbit</option>
                <option value="archived" @selected(request('status') === 'archived')>Diarsipkan</option>
            </select>
        </div>
        <button type="submit"
            class="rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md">
            <i class="fas fa-search mr-1"></i> Cari
        </button>
        @if (request()->hasAny(['search', 'category', 'status']))
            <a href="{{ route('admin.articles.index') }}"
                class="text-sm text-gray-400 transition hover:text-gray-600 hover:underline">
                <i class="fas fa-undo"></i> Reset
            </a>
        @endif
    </form>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm shadow-gray-200/60">
        <table class="w-full text-sm">
            <thead
                class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-4">Aksi</th>
                    <th class="px-5 py-4">#</th>
                    <th class="px-5 py-4">Judul</th>
                    <th class="px-5 py-4">Kategori</th>
                    <th class="px-5 py-4">Penulis</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-center">Dilihat</th>
                    <th class="px-5 py-4">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($articles as $article)
                    <tr class="transition-colors hover:bg-emerald-50/40">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.articles.edit', $article) }}"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800"
                                    title="Ubah">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form id="delete-article-{{ $article->id }}"
                                    action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" @click="$dispatch('open-modal-delete-article-{{ $article->id }}')"
                                    class="font-medium text-red-600">Hapus</button>
                                <x-confirm-modal id="delete-article-{{ $article->id }}" title="Hapus Artikel"
                                    message="Artikel &quot;{{ $article->title }}&quot; akan dipindahkan ke Sampah. Lanjutkan?"
                                    formId="delete-article-{{ $article->id }}" />
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-400">
                            {{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}
                        </td>
                        <td class="px-5 py-4 font-medium text-gray-800">{{ $article->title }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $article->category?->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $article->author->name }}</td>
                        <td class="px-5 py-4">
                            @php
                                $statusLabel = match ($article->status) {
                                    'draft' => 'Draf',
                                    'published' => 'Terbit',
                                    'archived' => 'Diarsipkan',
                                };
                                $statusColor = match ($article->status) {
                                    'draft' => 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-200',
                                    'published' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                                    'archived' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
                                };
                            @endphp
                            <span
                                class="{{ $statusColor }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center text-gray-500">{{ number_format($article->views_count) }}x
                        </td>
                        <td class="px-5 py-4 text-gray-500">{{ $article->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center text-gray-400">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-50">
                                <i class="fas fa-file-lines text-xl text-gray-300"></i>
                            </div>
                            Tidak ada artikel yang cocok dengan pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $articles->links() }}</div>
@endsection
