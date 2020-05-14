<?php

namespace App\Http\Controllers;

use App\Services\StatsService;

class WeatherController extends Controller
{

    public function showWeatherPage()
    {
        return view('_pages.weather-reports');
    }

}
