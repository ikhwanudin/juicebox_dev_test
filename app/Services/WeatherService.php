<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        $response = Http::get("{$this->baseUrl}/weather", [
            'q'    => $city,
            'appid'=> $this->apiKey,
            'units'=> $units, // 'metric' for Celsius, 'imperial' for Fahrenheit
        ]);


        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
