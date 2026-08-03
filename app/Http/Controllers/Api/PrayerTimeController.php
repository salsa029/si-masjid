<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PrayerTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{
    protected PrayerTimeService $prayerService;

    public function __construct(PrayerTimeService $prayerService)
    {
        $this->prayerService = $prayerService;
    }

    /**
     * Get prayer times data via API (for AJAX/real-time updates)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $cityCode = $request->query('city_code');
            $prayerData = $this->prayerService->getFormattedPrayerData($cityCode);

            return response()->json([
                'success' => true,
                'data' => $prayerData,
                'server_time' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch prayer times: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh cache and get fresh prayer times
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $cityCode = $request->query('city_code');
            $this->prayerService->clearCache($cityCode);
            $prayerData = $this->prayerService->getFormattedPrayerData($cityCode);

            return response()->json([
                'success' => true,
                'data' => $prayerData,
                'server_time' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh prayer times: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of available cities
     */
    public function cities(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->prayerService->getCities()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cities: ' . $e->getMessage()
            ], 500);
        }
    }
}
