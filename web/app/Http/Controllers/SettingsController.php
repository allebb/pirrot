<?php

namespace App\Http\Controllers;

use App\Services\StatsService;

class SettingsController extends Controller
{

    public function showSettingsPage()
    {
        return view('_pages.settings');
    }

}
