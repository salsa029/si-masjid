<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\MosqueProfile;
use App\Services\PrayerTimeService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected PrayerTimeService $prayerTimeService) {}

    public function index(): View
    {
        $mosqueProfile = MosqueProfile::first();

        // Get prayer data dengan city code dari mosque profile atau default
        $cityCode = $mosqueProfile?->city_code ?? null;
        $prayerData = $this->prayerTimeService->getPrayerData($cityCode);

        $latestArticles = Article::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $latestEvents = Event::published()
            ->upcoming()
            ->orderBy('start_at')
            ->take(3)
            ->get();

        $featuredEvent = Event::published()
            ->featured()
            ->upcoming()
            ->orderBy('start_at')
            ->first();

        return view('public.home', compact(
            'mosqueProfile',
            'prayerData',
            'latestArticles',
            'latestEvents',
            'featuredEvent'
        ));
    }
}
