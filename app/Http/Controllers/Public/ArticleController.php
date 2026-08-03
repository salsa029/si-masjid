<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::published()
            ->with(['category', 'tags'])
            ->when($request->filled('search'), fn($query) => $query->where('title', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn($q) => $q->where('slug', $request->input('category')));
            })
            ->when($request->filled('tag'), function ($query) use ($request) {
                $query->whereHas('tags', fn($q) => $q->where('slug', $request->input('tag')));
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = ArticleCategory::orderBy('name')->get();
        $popularArticles = Article::published()->popular()->take(5)->get();

        return view('public.articles.index', compact('articles', 'categories', 'popularArticles'));
    }

    public function show(string $slug): View
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        $article->incrementViews();

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where(function ($query) use ($article) {
                $query->where('category_id', $article->category_id)
                    ->orWhereHas('tags', function ($q) use ($article) {
                        $q->whereIn('tags.id', $article->tags->pluck('id'));
                    });
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        $popularArticles = Article::published()->popular()->take(5)->get();

        return view('public.articles.show', compact('article', 'relatedArticles', 'popularArticles'));
    }
}
