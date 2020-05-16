<?php

namespace App\Http\Controllers;

use App\Services\SystemResourceService;

class WeatherController extends Controller
{

    public function showWeatherPage()
    {
        return view('_pages.weather-reports');
    }

}
