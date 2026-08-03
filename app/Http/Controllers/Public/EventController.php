<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::published()
            ->with('category')
            ->when($request->filled('search'), fn($query) => $query->where('title', 'like', '%' . $request->input('search') . '%'))
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn($q) => $q->where('slug', $request->input('category')));
            })
            ->when($request->filled('time_status'), function ($query) use ($request) {
                match ($request->input('time_status')) {
                    'upcoming' => $query->upcoming(),
                    'ongoing' => $query->ongoing(),
                    'finished' => $query->finished(),
                    default => null,
                };
            })
            ->orderBy('start_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        $categories = EventCategory::orderBy('name')->get();
        $featuredEvents = Event::published()->featured()->upcoming()->orderBy('start_at')->take(3)->get();

        return view('public.events.index', compact('events', 'categories', 'featuredEvents'));
    }

    public function show(string $slug): View
    {
        $event = Event::where('slug', $slug)
            ->published()
            ->with(['category', 'documentations'])
            ->firstOrFail();

        $event->incrementViews();

        $relatedEvents = Event::published()
            ->where('id', '!=', $event->id)
            ->where('category_id', $event->category_id)
            ->orderBy('start_at', 'desc')
            ->take(3)
            ->get();

        return view('public.events.show', compact('event', 'relatedEvents'));
    }

    public function calendar(): View
    {
        return view('public.events.calendar');
    }

    public function calendarData(): JsonResponse
    {
        $events = Event::published()->get()->map(function (Event $event) {
            return [
                'title' => $event->title,
                'start' => $event->start_at->toIso8601String(),
                'end' => $event->end_at->toIso8601String(),
                'url' => route('public.events.show', $event->slug),
                'color' => match ($event->time_status) {
                    'upcoming' => '#2563eb',
                    'ongoing' => '#047857',
                    'finished' => '#9ca3af',
                },
            ];
        });

        return response()->json($events);
    }
}
