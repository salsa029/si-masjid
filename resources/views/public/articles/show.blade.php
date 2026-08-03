@extends('layouts.app')

@section('title', $article->title)

@push('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
    @if ($article->meta_keyword)
        <meta name="keywords" content="{{ $article->meta_keyword }}">
    @endif
@endpush

@section('content')
    <section class="bg-white py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                <!-- Main Content -->
                <article class="lg:col-span-3">
                    <!-- Back Link -->
                    <a href="{{ route('public.articles.index') }}"
                        class="mb-6 inline-flex items-center gap-2 text-green-600 transition hover:text-green-800">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Kembali ke Daftar Artikel
                    </a>

                    <!-- Thumbnail -->
                    @if ($article->thumbnail)
                        <div class="mb-8 overflow-hidden rounded-2xl shadow-lg">
                            <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}"
                                class="h-auto max-h-[450px] w-full object-cover" loading="lazy">
                        </div>
                    @endif

                    <!-- Category -->
                    @if ($article->category)
                        <span
                            class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">{{ $article->category->name }}</span>
                    @endif

                    <!-- Title -->
                    <h1 class="mt-2 text-3xl font-extrabold text-gray-800 md:text-4xl">{{ $article->title }}</h1>

                    <!-- Meta -->
                    <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-user text-green-600" aria-hidden="true"></i>
                            {{ $article->author->name }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-green-600" aria-hidden="true"></i>
                            {{ $article->published_at?->translatedFormat('d F Y') ?? $article->created_at->translatedFormat('d F Y') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="far fa-eye text-green-600" aria-hidden="true"></i>
                            {{ number_format($article->views_count) }}x dilihat
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="prose prose-green mt-8 max-w-none">
                        {!! $article->content !!}
                    </div>

                    <!-- Tags -->
                    @if ($article->tags->isNotEmpty())
                        <div class="mt-8 flex flex-wrap gap-2 border-t border-gray-100 pt-6">
                            @foreach ($article->tags as $tag)
                                <a href="{{ route('public.articles.index', ['tag' => $tag->slug]) }}"
                                    class="rounded-full bg-gray-100 px-3 py-1.5 text-xs text-gray-600 transition hover:bg-green-100 hover:text-green-700">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Share Buttons -->
                    @include('public.articles._share-buttons')

                    <!-- Related Articles -->
                    @if ($relatedArticles->isNotEmpty())
                        <div class="mt-12 border-t border-gray-200 pt-8">
                            <h2 class="mb-4 text-2xl font-bold text-gray-800">Artikel Terkait</h2>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                                @foreach ($relatedArticles as $related)
                                    <a href="{{ route('public.articles.show', $related->slug) }}"
                                        class="group overflow-hidden rounded-xl bg-white shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                        @if ($related->thumbnail)
                                            <img src="{{ Storage::url($related->thumbnail) }}" alt="{{ $related->title }}"
                                                class="h-32 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                                loading="lazy">
                                        @else
                                            <div
                                                class="flex h-32 w-full items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                                                <i class="fas fa-newspaper text-2xl text-green-400" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                        <div class="p-4">
                                            <h4
                                                class="line-clamp-2 text-sm font-semibold text-gray-800 transition group-hover:text-green-700">
                                                {{ $related->title }}</h4>
                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $related->published_at?->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </article>

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
                </aside>
            </div>
        </div>
    </section>
@endsection
