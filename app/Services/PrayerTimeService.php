<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PrayerTimeService
{
    /**
     * Kode kota untuk API MyQuran
     * Default: Jakarta (1301)
     * Dapat diubah via .env atau database
     */
    private const DEFAULT_CITY_CODE = '1301';

    /**
     * Daftar kota yang didukung oleh API MyQuran
     */
    private const CITIES = [
        '1301' => 'Jakarta',
        '1302' => 'Bandung',
        '1303' => 'Bogor',
        '1304' => 'Tangerang',
        '1305' => 'Bekasi',
        '1306' => 'Depok',
        '1307' => 'Medan',
        '1308' => 'Surabaya',
        '1309' => 'Yogyakarta',
        '1310' => 'Semarang',
        '1311' => 'Makassar',
        '1312' => 'Palembang',
        '1313' => 'Pekanbaru',
        '1314' => 'Padang',
        '1315' => 'Banda Aceh',
        '1316' => 'Denpasar',
        '1317' => 'Bandar Lampung',
        '1318' => 'Pontianak',
        '1319' => 'Banjarmasin',
        '1320' => 'Manado',
        '1321' => 'Balikpapan',
        '1322' => 'Jambi',
        '1323' => 'Bengkulu',
        '1324' => 'Pangkal Pinang',
        '1325' => 'Tanjung Pinang',
        '1326' => 'Mataram',
        '1327' => 'Kupang',
        '1328' => 'Ambon',
        '1329' => 'Jayapura',
        '1330' => 'Kendari',
        '1331' => 'Palu',
        '1332' => 'Gorontalo',
        '1333' => 'Mamuju',
        '1334' => 'Tarakan',
        '1335' => 'Sorong',
        '1336' => 'Ternate',
    ];

    /**
     * Daftar jadwal sholat default (fallback jika API gagal)
     */
    private const DEFAULT_PRAYERS = [
        ['name' => 'Subuh', 'time' => '04:30'],
        ['name' => 'Dzuhur', 'time' => '11:45'],
        ['name' => 'Ashar', 'time' => '15:00'],
        ['name' => 'Maghrib', 'time' => '18:30'],
        ['name' => 'Isya', 'time' => '19:45'],
    ];

    /**
     * Mapping API key ke display name
     */
    private const PRAYER_MAP = [
        'subuh' => 'Subuh',
        'dzuhur' => 'Dzuhur',
        'ashar' => 'Ashar',
        'maghrib' => 'Maghrib',
        'isya' => 'Isya'
    ];

    /**
     * Mendapatkan jadwal sholat hari ini dari API atau cache
     */
    public function getTodaySchedule(?string $cityCode = null): ?array
    {
        $cityCode = $cityCode ?? $this->getCityCode();
        $cacheKey = 'prayer-schedule-' . $cityCode . '-' . now()->format('Y-m-d');

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($cityCode) {
            try {
                $response = Http::timeout(5)->get(
                    "https://api.myquran.com/v2/sholat/jadwal/{$cityCode}/" . now()->format('Y/m/d')
                );

                if ($response->successful()) {
                    $data = $response->json('data.jadwal');
                    if ($data) {
                        Log::info('Prayer times fetched successfully from API for city: ' . $cityCode);
                        return $data;
                    }
                }

                Log::warning('Failed to fetch prayer times from API, using default');
                return null;
            } catch (\Exception $e) {
                Log::error('Prayer time API error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Mendapatkan array jadwal sholat yang terformat
     */
    public function getPrayerTimes(?string $cityCode = null): array
    {
        $schedule = $this->getTodaySchedule($cityCode);

        if (!$schedule) {
            return self::DEFAULT_PRAYERS;
        }

        $result = [];
        foreach (self::PRAYER_MAP as $apiKey => $displayName) {
            if (isset($schedule[$apiKey])) {
                $result[] = [
                    'name' => $displayName,
                    'time' => $schedule[$apiKey]
                ];
            }
        }

        return !empty($result) ? $result : self::DEFAULT_PRAYERS;
    }

    /**
     * Mendapatkan jadwal sholat saat ini
     */
    public function getCurrentPrayer(?array $prayerTimes = null): ?array
    {
        $prayerTimes = $prayerTimes ?? $this->getPrayerTimes();
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        $current = null;
        foreach ($prayerTimes as $prayer) {
            if ($currentTime >= $prayer['time']) {
                $current = $prayer;
            }
        }

        return $current ?? end($prayerTimes);
    }

    /**
     * Mendapatkan jadwal sholat berikutnya
     */
    public function getNextPrayer(?array $prayerTimes = null): ?array
    {
        $prayerTimes = $prayerTimes ?? $this->getPrayerTimes();
        $currentPrayer = $this->getCurrentPrayer($prayerTimes);

        $index = array_search($currentPrayer, $prayerTimes);
        if ($index === false) {
            return $prayerTimes[0] ?? null;
        }

        return $prayerTimes[($index + 1) % count($prayerTimes)] ?? null;
    }

    /**
     * Mendapatkan sisa waktu menuju sholat berikutnya
     */
    public function getTimeRemaining(?array $prayerTimes = null): ?string
    {
        $prayerTimes = $prayerTimes ?? $this->getPrayerTimes();
        $nextPrayer = $this->getNextPrayer($prayerTimes);

        if (!$nextPrayer) return null;

        $now = Carbon::now();
        $nextTime = Carbon::createFromFormat('H:i', $nextPrayer['time']);

        if ($nextTime->lessThan($now)) {
            $nextTime->addDay();
        }

        $diff = $now->diff($nextTime);

        if ($diff->h > 0) {
            return $diff->h . 'j ' . $diff->i . 'm';
        }

        return $diff->i . 'm';
    }

    /**
     * Mendapatkan semua data prayer untuk view
     */
    public function getPrayerData(?string $cityCode = null): array
    {
        $cityCode = $cityCode ?? $this->getCityCode();
        $prayerTimes = $this->getPrayerTimes($cityCode);
        $currentPrayer = $this->getCurrentPrayer($prayerTimes);
        $nextPrayer = $this->getNextPrayer($prayerTimes);
        $timeRemaining = $this->getTimeRemaining($prayerTimes);

        return [
            'prayer_times' => $prayerTimes,
            'current_prayer' => $currentPrayer,
            'next_prayer' => $nextPrayer,
            'time_remaining' => $timeRemaining,
            'city_code' => $cityCode,
            'city_name' => $this->getCityName($cityCode),
            'available_cities' => self::CITIES,
            'last_updated' => now()->format('H:i')
        ];
    }

    /**
     * Mendapatkan nama kota dari kode
     */
    public function getCityName(string $cityCode): string
    {
        return self::CITIES[$cityCode] ?? 'Kota Lainnya';
    }

    /**
     * Mendapatkan daftar semua kota
     */
    public function getCities(): array
    {
        return self::CITIES;
    }

    /**
     * Mendapatkan kode kota dari config/prayer.php (yang dibaca dari .env) atau default
     */
    private function getCityCode(): string
    {
        return config('prayer.city_code', self::DEFAULT_CITY_CODE);
    }

    /**
     * Clear cache prayer times
     */
    public function clearCache(?string $cityCode = null): void
    {
        if ($cityCode) {
            Cache::forget('prayer-schedule-' . $cityCode . '-' . now()->format('Y-m-d'));
            Log::info('Prayer times cache cleared for city: ' . $cityCode);
        } else {
            // Clear all city caches
            foreach (array_keys(self::CITIES) as $code) {
                Cache::forget('prayer-schedule-' . $code . '-' . now()->format('Y-m-d'));
            }
            Log::info('All prayer times cache cleared');
        }
    }

    /**
     * Mendapatkan jadwal sholat yang sudah diformat untuk API response
     */
    public function getFormattedPrayerData(?string $cityCode = null): array
    {
        return $this->getPrayerData($cityCode);
    }
}
