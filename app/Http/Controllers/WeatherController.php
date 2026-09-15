<?php


namespace App\Http\Controllers;

use App\Services\WeatherService;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     *
     * Get weather
     *
     * @return JsonResponse
     */

    //#[PathParameter('city', description: 'city', type: 'string', default: 'perth', example: 'perth')]
    public function show(): JsonResponse
    {
        $weatherData = $this->weatherService->getWeatherByCity( 'Perth');

        if (!$weatherData) {
            return response()->json(['error' => 'Could not fetch weather data'], 500);
        }

        // Example of reading specific metrics safely
        return response()->json([
            'data' => [
                'city' => $weatherData['name'],
                'temperature' => $weatherData['main']['temp'] ?? null,
                'description' => $weatherData['weather'][0]['description'] ?? null,
                'humidity' => $weatherData['main']['humidity'] ?? null,
            ]
        ]);
    }
}
