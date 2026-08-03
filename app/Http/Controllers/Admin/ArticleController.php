<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request): View
    {
        $articles = Article::with(['author', 'category'])
            ->when($request->filled('search'), fn($query) => $query->where('title', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('category'), fn($query) => $query->where('category_id', $request->input('category')))
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = ArticleCategory::orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create(): View
    {
        $categories = ArticleCategory::orderBy('name')->get();

        return view('admin.articles.create', compact('categories'));
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['content'] = Purifier::clean($validated['content']);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageUploadService->upload(
                $request->file('thumbnail'),
                folder: 'articles',
                maxWidth: 1200
            );
        }

        $tagNames = $this->parseTagNames($validated['tags'] ?? null);
        unset($validated['tags']);

        $article = Article::create($validated);
        $this->syncTags($article, $tagNames);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Article $article): View
    {
        $article->load('tags');
        $categories = ArticleCategory::orderBy('name')->get();

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();
        $validated['content'] = Purifier::clean($validated['content']);

        if ($article->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], ignoreId: $article->id);
        }

        if ($validated['status'] === 'published' && ! $article->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageUploadService->upload(
                $request->file('thumbnail'),
                folder: 'articles',
                maxWidth: 1200,
                oldPath: $article->thumbnail,
            );
        }

        $tagNames = $this->parseTagNames($validated['tags'] ?? null);
        unset($validated['tags']);

        $article->update($validated);
        $this->syncTags($article, $tagNames);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel dipindahkan ke Sampah (Trash).');
    }

    public function trash(): View
    {
        $articles = Article::onlyTrashed()->with('author')->latest('deleted_at')->paginate(10);

        return view('admin.articles.trash', compact('articles'));
    }

    public function restore(string $id): RedirectResponse
    {
        $article = Article::onlyTrashed()->findOrFail($id);
        $article->restore();

        return redirect()
            ->route('admin.articles.trash')
            ->with('success', 'Artikel berhasil dipulihkan.');
    }

    public function forceDelete(string $id): RedirectResponse
    {
        $article = Article::onlyTrashed()->findOrFail($id);

        $this->imageUploadService->delete($article->thumbnail);
        $article->tags()->detach();
        $article->forceDelete();

        return redirect()
            ->route('admin.articles.trash')
            ->with('success', 'Artikel berhasil dihapus permanen.');
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Article::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Mengubah teks tag "ramadhan, kajian, zakat" menjadi array bersih ['ramadhan', 'kajian', 'zakat'].
     */
    private function parseTagNames(?string $tagsInput): array
    {
        if (! $tagsInput) {
            return [];
        }

        return collect(explode(',', $tagsInput))
            ->map(fn($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Mencari tag yang sudah ada atau membuat baru jika belum ada, lalu menghubungkannya ke artikel.
     */
    private function syncTags(Article $article, array $tagNames): void
    {
        $tagIds = collect($tagNames)->map(function (string $name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        });

        $article->tags()->sync($tagIds);
    }
}
