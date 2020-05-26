<?php

namespace App\Http\Controllers;

use App\Http\ViewModels\WeatherReportViewModel;
use Illuminate\Support\Collection;

class WeatherController extends Controller
{

    public function showWeatherPage()
    {

        $reports = app('db')->select("SELECT * FROM weather_reports ORDER BY id DESC");

        $reportVms = new Collection();
        foreach ($reports as $report) {
            $reportVms->add(new WeatherReportViewModel($report));
        }

        return view('_pages.weather-reports')
            ->with('reports', $reportVms);
    }

}
