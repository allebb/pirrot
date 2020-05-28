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
        if (env('APP_ENV') != 'production') {
            $data = json_decode(file_get_contents(app('path') . '/../resources/dev/dummy_sysinfo.cache'));
            $data->version_pirrot = trim(file_get_contents(app('path') . '/../../VERSION'));
            return $data;
        }

        $systemInfoCache = env('PIRROT_PATH') . '/storage/sysinfo.cache';
        $versionInfoCache = env('PIRROT_PATH') . '/storage/version.cache';

        $systemInfo = [];
        if (file_exists($systemInfoCache)) {
            $systemInfo = file_get_contents($systemInfoCache);
        }
        if(file_exists($versionInfoCache)){
            $latest_version = trim(file_get_contents($versionInfoCache));
            $last_check = date('jS F Y at H:i:s',filemtime($versionInfoCache));
            $systemInfo = array_merge($systemInfo, ['version_latest' => $latest_version, 'version_checked' => $last_check]);
        }
        return json_decode($systemInfo);
    }

}
