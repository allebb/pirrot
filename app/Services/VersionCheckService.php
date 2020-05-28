<?php


namespace Ballen\Pirrot\Services;


class VersionCheckService
{

    const VERSION_CHECK_ENDPOINT = 'https://pirrot.hallinet.com/version-check';

    /**
     * Checks the Pirrot version web service to see if this has the latest version of Pirrot.
     * @return string|null
     */
    public function getLatestVersion()
    {
        $latestVersionInfo = null;
        $meta = trim(system('/opt/pirrot/pirrot version --dump'));
        if ($version = $this->checkWebservice($meta)) {
            $latestVersionInfo = $version;
        }
        return $latestVersionInfo;
    }

    /**
     * Queries the version checker API to get the latest version information.
     * @param $meta
     * @return bool|string
     */
    private function checkWebservice($meta)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::VERSION_CHECK_ENDPOINT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $meta);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($meta)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}