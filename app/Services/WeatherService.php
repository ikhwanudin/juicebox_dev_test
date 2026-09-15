<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WeatherService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = config('services.openweather.url');
    }

    /**
     * Get current weather by city name.
     *
     * @param string $city
     * @param string $units
     * @return array|null
     */
    public function getWeatherByCity(string $city, string $units = 'metric'): ?array
    {
        $cacheKey = "weather:" . Str::slug($city) . ":{$units}";

        return Cache::remember($cacheKey, 1800, function () use ($city, $units) {
            $response = Http::get("{$this->baseUrl}/weather", [
                'q'     => $city,
                'appid' => $this->apiKey,
                'units' => $units,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }
}
