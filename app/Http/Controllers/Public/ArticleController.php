<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function show(Request $request, string $slug): View
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->with(['author', 'category', 'tags'])
            ->firstOrFail();

        if ($this->shouldCountView($request, $article)) {
            $article->incrementViews();
        }

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

    /**
     * Tentukan apakah kunjungan ini layak dihitung sebagai 1 view artikel.
     * Menyaring: bot/crawler, admin yang membuka artikel miliknya sendiri,
     * dan kunjungan berulang dari session browser yang sama untuk artikel yang sama.
     */
    protected function shouldCountView(Request $request, Article $article): bool
    {
        if ($this->isBotRequest($request)) {
            return false;
        }

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return false;
        }

        $viewedArticleIds = $request->session()->get('viewed_articles', []);

        if (in_array($article->id, $viewedArticleIds, true)) {
            return false;
        }

        $viewedArticleIds[] = $article->id;
        $request->session()->put('viewed_articles', $viewedArticleIds);

        return true;
    }

    /**
     * Deteksi sederhana berbasis User-Agent untuk menyaring bot/crawler/tool otomatis,
     * agar tidak ikut menaikkan hitungan "dilihat".
     */
    protected function isBotRequest(Request $request): bool
    {
        $userAgent = $request->userAgent() ?? '';

        return (bool) preg_match(
            '/bot|crawl|slurp|spider|mediapartners|facebookexternalhit|whatsapp|telegrambot|discordbot|slackbot|curl|wget|python-requests|go-http-client|headlesschrome/i',
            $userAgent
        );
    }
}
