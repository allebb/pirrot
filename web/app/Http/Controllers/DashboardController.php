<?php

namespace App\Http\Controllers;

use App\Services\SystemResourceService;

class DashboardController extends Controller
{

    public function showDashboardPage()
    {
        return view('_pages.dashboard')
            ->with('system', $this->retrieveSystemInformation());
    }

    public function ajaxGetDashboardStats()
    {
        $statsService = new SystemResourceService();
        return response()->json($statsService->toArray());
    }

    /**
     * Retrieves the system information from the cache data.
     * @return mixed
     */
    private function retrieveSystemInformation()
    {
        $systemInfoCache = env('PIRROT_PATH') . '/storage/sysinfo.cache';
        $systemInfo = [];
        if (file_exists($systemInfoCache)) {
            $systemInfo = file_get_contents($systemInfoCache);
        }
        return json_decode($systemInfo);
    }

    private function retrieveSystemUtilisation()
    {
        // Return an object of system utilisation.
    }

}
