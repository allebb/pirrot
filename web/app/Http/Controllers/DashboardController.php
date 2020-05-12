<?php

namespace App\Http\Controllers;

use App\Services\StatsService;

class DashboardController extends Controller
{

    public function showDashboardPage()
    {
        return view('dashboard')->with('test', 'Bobby Allen');
    }

    public function ajaxGetDashboardStats()
    {
        $statsService = new StatsService();
        return response()->json([
            'version' => $statsService->versions(),
            'temperature' => $statsService->temperature(),
            'hostname' => $statsService->hostname()
        ]);
    }

}
