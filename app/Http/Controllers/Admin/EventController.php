<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventRequest;
use App\Models\Event;
use App\Models\EventCategory;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(protected ImageUploadService $imageUploadService) {}

    public function index(Request $request): View
    {
        $events = Event::with('category')
            ->withCount('documentations')
            ->when($request->filled('search'), fn($query) => $query->where('title', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('category'), fn($query) => $query->where('category_id', $request->input('category')))
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('time_status'), function ($query) use ($request) {
                match ($request->input('time_status')) {
                    'upcoming' => $query->upcoming(),
                    'ongoing' => $query->ongoing(),
                    'finished' => $query->finished(),
                    default => null,
                };
            })
            ->latest('start_at')
            ->paginate(10)
            ->withQueryString();

        $categories = EventCategory::orderBy('name')->get();

        return view('admin.events.index', compact('events', 'categories'));
    }

    public function create(): View
    {
        $categories = EventCategory::orderBy('name')->get();

        return view('admin.events.create', compact('categories'));
    }

    public function store(EventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageUploadService->upload(
                $request->file('thumbnail'),
                folder: 'events/thumbnails',
                maxWidth: 1200
            );
        }

        if ($request->hasFile('poster')) {
            $validated['poster'] = $this->imageUploadService->upload(
                $request->file('poster'),
                folder: 'events/posters',
                maxWidth: 1600
            );
        }

        $event = Event::create($validated);

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('success', 'Kegiatan berhasil ditambahkan. Sekarang Anda bisa menambahkan foto dokumentasi.');
    }

    public function edit(Event $event): View
    {
        $event->load('documentations');
        $categories = EventCategory::orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($event->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], ignoreId: $event->id);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $this->imageUploadService->upload(
                $request->file('thumbnail'),
                folder: 'events/thumbnails',
                maxWidth: 1200,
                oldPath: $event->thumbnail,
            );
        }

        if ($request->hasFile('poster')) {
            $validated['poster'] = $this->imageUploadService->upload(
                $request->file('poster'),
                folder: 'events/posters',
                maxWidth: 1600,
                oldPath: $event->poster,
            );
        }

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        foreach ($event->documentations as $documentation) {
            $this->imageUploadService->delete($documentation->photo);
        }

        $this->imageUploadService->delete($event->thumbnail);
        $this->imageUploadService->delete($event->poster);
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Event::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
