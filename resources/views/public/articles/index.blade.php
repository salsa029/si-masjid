@extends('layouts.app')

@section('title', 'Artikel Dakwah')

@section('content')
    <section class="bg-white py-12 md:py-20">
        <div class="container mx-auto px-4">
            <!-- Section Header -->
            <div class="mb-8">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1 text-sm font-semibold text-green-700">Artikel</span>
                <h2 class="mt-2 text-3xl font-extrabold text-green-800 md:text-4xl">Artikel <span
                        class="text-green-600">Dakwah</span></h2>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Search & Filter -->
                    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl bg-gray-50 p-4">
                        <div class="min-w-[200px] flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari artikel..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <select name="category"
                                class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-transparent focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">
                            <i class="fas fa-search mr-2" aria-hidden="true"></i> Cari
                        </button>
                    </form>

                    <!-- Articles Grid -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        @forelse($articles as $article)
                            <a href="{{ route('public.articles.show', $article->slug) }}"
                                class="group overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                                @if ($article->thumbnail)
                                    <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}"
                                        class="h-48 w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy">
                                @else
                                    <div
                                        class="flex h-48 w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                        <i class="fas fa-newspaper text-4xl text-green-400" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div class="p-5">
                                    @if ($article->category)
                                        <span
                                            class="text-xs font-semibold text-green-700">{{ $article->category->name }}</span>
                                    @endif
                                    <h3
                                        class="mt-1 line-clamp-2 text-lg font-bold text-gray-800 transition group-hover:text-green-700">
                                        {{ $article->title }}</h3>
                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                                        <span>{{ $article->published_at?->translatedFormat('d F Y') ?? $article->created_at->translatedFormat('d F Y') }}</span>
                                        <span><i class="far fa-eye mr-1" aria-hidden="true"></i>
                                            {{ number_format($article->views_count) }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-12 text-center">
                                <div class="mb-4 text-6xl text-gray-300">
                                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-500">Tidak Ada Artikel</h3>
                                <p class="mt-2 text-gray-400">Belum ada artikel yang diterbitkan saat ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $articles->links() }}
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="space-y-6">
                    <!-- Popular Articles -->
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-md">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold text-gray-800">
                            <i class="fas fa-fire text-orange-500" aria-hidden="true"></i>
                            Artikel Populer
                        </h3>
                        <ul class="space-y-4">
                            @forelse($popularArticles ?? [] as $popular)
                                <li>
                                    <a href="{{ route('public.articles.show', $popular->slug) }}"
                                        class="line-clamp-2 block text-sm font-medium text-gray-700 transition hover:text-green-700">
                                        {{ $popular->title }}
                                    </a>
                                    <span class="mt-1 block text-xs text-gray-400">
                                        <i class="far fa-eye mr-1" aria-hidden="true"></i>
                                        {{ number_format($popular->views_count) }}
                                    </span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-400">Belum ada data artikel populer.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Categories -->
                    @if ($categories->isNotEmpty())
                        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-md">
                            <h3 class="mb-4 flex items-center gap-2 font-semibold text-gray-800">
                                <i class="fas fa-tags text-green-600" aria-hidden="true"></i>
                                Kategori
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($categories as $category)
                                    <a href="{{ route('public.articles.index', ['category' => $category->slug]) }}"
                                        class="rounded-full bg-gray-100 px-3 py-1.5 text-xs text-gray-600 transition hover:bg-green-100 hover:text-green-700">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection
