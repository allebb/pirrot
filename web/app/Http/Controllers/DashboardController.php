<?php

namespace App\Http\Controllers;

use App\Services\SystemResourceService;

class DashboardController extends Controller
{

    public function showDashboardPage()
    {


        return view('_pages.dashboard')
            ->with('system', $this->retrieveSystemInformation())
            ->with('version_updates', $this->retrieveUpdateInformation());
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

        $systemInfo = [];
        if (file_exists($systemInfoCache)) {
            $systemInfo = file_get_contents($systemInfoCache);
        }
        return json_decode($systemInfo);
    }

    /**
     * Retrieves that last version check information from the cache or falls back to "safe" defaults.
     * @return array
     */
    private function retrieveUpdateInformation()
    {

        $versionInfoCache = env('PIRROT_PATH') . '/storage/version.cache';
        $updates = [
            'version_update_available' => false,
            'version_latest' => file_get_contents(env('PIRROT_PATH').'/VERSION'),
            'version_checked' => 'never',
        ];

        if (file_exists($versionInfoCache)) {
            $latest_version = json_decode(trim(file_get_contents($versionInfoCache)));
            $last_check = date('jS F Y at H:i:s', filemtime($versionInfoCache));
            $updates = [
                'version_update_available' => $latest_version->updates_available,
                'version_latest' => $latest_version->latest_version->number,
                'version_checked' => $last_check
            ];
        }

        return $updates;
    }

}
