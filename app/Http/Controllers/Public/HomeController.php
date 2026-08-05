<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Committee;
use App\Models\DonationSetting;
use App\Models\Event;
use App\Models\Infaq;
use App\Models\InfaqCampaign;
use App\Models\MosqueProfile;
use App\Models\QurbanParticipant;
use App\Models\SacrificialAnimal;
use App\Models\Zakat;
use App\Services\PrayerTimeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected PrayerTimeService $prayerTimeService) {}

    public function index(): View
    {
        $mosqueProfile = MosqueProfile::with('galleryImages')->first();

        // Foto untuk slideshow hero: pakai galeri jika ada, fallback ke Hero Image tunggal.
        $heroImages = $mosqueProfile?->galleryImages->isNotEmpty()
            ? $mosqueProfile->galleryImages->map(fn ($image) => Storage::url($image->photo))->all()
            : ($mosqueProfile?->hero_image ? [Storage::url($mosqueProfile->hero_image)] : []);

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

        // Galeri kegiatan: semua kegiatan yang sudah dipublikasikan, terbaru dulu (beda dari $latestEvents yang hanya upcoming)
        $galleryEvents = Event::published()->latest()->take(6)->get();

        $donationSettings = DonationSetting::firstOrNew();

        // Campaign infaq yang sedang berjalan, ditampilkan di Beranda
        $activeCampaigns = InfaqCampaign::where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            })
            ->withSum(['infaqs as collected_amount' => function ($query) {
                $query->where('payment_status', 'success');
            }], 'amount')
            ->latest('start_date')
            ->take(3)
            ->get();

        // Statistik ringkas untuk section Hero (hanya pengurus yang masa jabatannya masih berjalan)
        $activeCommitteeCount = Committee::where(function ($query) {
            $query->whereNull('term_end')->orWhere('term_end', '>=', now());
        })->count();
        $publishedEventCount = Event::published()->count();
        $publishedArticleCount = Article::where('status', 'published')->count();

        // Highlight capaian donasi & kurban untuk homepage
        $totalDonationCollected = Infaq::where('payment_status', 'success')->sum('amount')
            + Zakat::where('payment_status', 'success')->sum('amount');

        $totalDonors = collect([
            Infaq::where('payment_status', 'success')->whereNotNull('user_id')->pluck('user_id'),
            Zakat::where('payment_status', 'success')->whereNotNull('user_id')->pluck('user_id'),
        ])->flatten()->unique()->count();

        $totalSacrificedAnimals = SacrificialAnimal::where('status', 'slaughtered')->count();

        $totalQurbanParticipants = QurbanParticipant::whereHas('order', function ($query) {
            $query->where('payment_status', 'success');
        })->count();

        $impactStats = [
            'total_donation' => $totalDonationCollected,
            'total_donors' => $totalDonors,
            'total_sacrificed_animals' => $totalSacrificedAnimals,
            'total_qurban_participants' => $totalQurbanParticipants,
        ];

        return view('public.home', compact(
            'mosqueProfile',
            'prayerData',
            'latestArticles',
            'latestEvents',
            'featuredEvent',
            'galleryEvents',
            'donationSettings',
            'activeCampaigns',
            'activeCommitteeCount',
            'publishedEventCount',
            'publishedArticleCount',
            'impactStats',
            'heroImages'
        ));
    }
}
