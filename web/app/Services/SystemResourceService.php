<?php

namespace App\Services;

use App\Services\DTO\Gps;

class SystemResourceService
{

    const DATE_OUTPUT_FORMAT = 'H:i jS M Y';

    private $ramTotal = 0;

    private $ramFree = 0;

    private $ramAvailable = 0;

    private $diskTotal = 0;

    private $diskUsed = 0;

    private $diskAvailable = 0;

    public function getTemperature(): string
    {
        $data = shell_exec('vcgencmd measure_temp | cut -d \'=\' -f2');
        if (!$data) {
            return '**not detected**';
        }
        return rtrim(trim($data), '\'C');
    }

    public function getCpuUsage(): int
    {
        $cpuUsage = shell_exec("echo \$(vmstat 1 2 | tail -1 | awk '{print $15}')");
        return 100 - trim($cpuUsage);
    }

    function getRamUsage(): int
    {
        $this->ramTotal = $this->removeKbSuffix(shell_exec("grep 'MemTotal' /proc/meminfo | cut -d : -f2")) / 1024;
        $this->ramFree = $this->removeKbSuffix(shell_exec("grep 'MemFree' /proc/meminfo | cut -d : -f2")) / 1024;
        $this->ramAvailable = $this->removeKbSuffix(shell_exec("grep 'MemAvailable' /proc/meminfo | cut -d : -f2")) / 1024;
        return $this->ramTotal - $this->ramAvailable;
    }

    public function getDiskUsage(): int
    {
        $data = shell_exec("df -l | grep '/dev/root' | awk '{print $1,$2,$3,$4,$5}'");
        $parts = explode(' ', $data);
        $this->diskUsed = $parts[2];
        $this->diskTotal = $parts[1];
        $this->diskAvailable = $parts[1] - $parts[2];
        return rtrim($parts[4], '%');
    }

    public function getUptime(): string
    {
        if (file_exists('/proc/uptime')) {
            return '**not detected**';
        }
        $data = file_get_contents('/proc/uptime');
        $num = floatval($data);
        $num = intdiv($num, 60);
        $mins = $num % 60;
        $num = intdiv($num, 60);
        $hours = $num % 24;
        $num = intdiv($num, 24);
        $days = $num;
        return "{$days}d {$hours}h {$mins}m";
    }

    public function getBootTime(): string
    {
        $data = shell_exec('uptime -s');
        return \DateTime::createFromFormat('Y-m-d H:i:s', trim($data))->format(self::DATE_OUTPUT_FORMAT);
    }

    public function getGpsData(): Gps
    {
        $gps = new Gps();
        if (!file_exists('/etc/default/gpsd')) {
            return $gps;
        }
        $data = trim(shell_exec("gpspipe -w -n 10"));

        // Data format buffer...
        $gpsDataArray = [];

        // Loop over each line from the feed, JSON decode each line and associate in an array.
        foreach (explode(PHP_EOL, $data) as $line) {
            $dataClass = json_decode(trim($line), true);
            $gpsDataArray[$dataClass['class']] = $dataClass;
        }

        // No GPS fix available as yet, we'll return early (so "Loading" appears) this will update as soon as the GPS device has a satellite fix.
        if (!isset($gpsDataArray['SKY'])) {
            return $gps;
        }

        // Get satellite PRN's reporting the positional data..
        foreach ($gpsDataArray['SKY']['satellites'] as $satellite) {
            if ($satellite['used']) {
                $gps->satellites[] = $satellite['PRN'];
            }
        }

        // Any satellite fix data available as yet?
        if (!isset($gpsDataArray['TPV'])) {
            return $gps;
        }
        $gps->device = $gpsDataArray['TPV']['device'];
        $gps->time = $gpsDataArray['TPV']['time'];
        $gps->latitude = $gpsDataArray['TPV']['lat'];
        $gps->longitude = $gpsDataArray['TPV']['lon'];
        $gps->altitude = $gpsDataArray['TPV']['alt'];
        $gps->speed = $gpsDataArray['TPV']['speed'];

        return $gps;
    }

    private function removeKbSuffix(string $string)
    {
        return str_replace(' kB', '', trim($string));
    }

    public function toArray()
    {

        $tempDegreesC = $this->getTemperature();
        $tempDegreesF = round((($tempDegreesC / 5) * 9) + 32, 1);
        $tempDegreesC = round($tempDegreesC, 1);

        $cpuUsage = $this->getCpuUsage();
        $ramUsage = $this->getRamUsage();
        $diskUsage = $this->getDiskUsage();
        $gpsData = $this->getGpsData();

        return [

            // Times
            'booted' => $this->getBootTime(),
            'uptime_time' => $this->getUptime(),
            'system_time' => date(self::DATE_OUTPUT_FORMAT),

            // Usage Percentages
            'cpu_percent' => round($cpuUsage, 1),
            'ram_percent' => ceil(($ramUsage / $this->ramTotal) * 100),
            'ram_usage' => $ramUsage,
            'ram_total' => $this->ramTotal,
            'disk_percent' => $diskUsage,
            'disk' => $this->diskUsed / 1048576,
            'disk_total' => $this->diskTotal / 1048576,

            // Temperature
            'temp_c' => $tempDegreesC,
            'temp_f' => $tempDegreesF,

            // GPS Data
            'gps_device' => $gpsData->device,
            'gps_time' => $gpsData->time,
            'gps_lat' => number_format($gpsData->latitude, 7),
            'gps_lng' => number_format($gpsData->longitude, 7),
            'gps_alt_msl' => round($gpsData->altitude, 1),
            'gps_alt_fsl' => round($gpsData->altitude * 3.28084, 1),
            'gps_spd_mps' => round($gpsData->speed, 1),
            'gps_spd_mph' => round($gpsData->speed * 2.23694, 1),
            'gps_spd_kph' => round($gpsData->speed * 3.6, 1),
            'gps_fixes' => count($gpsData->satellites),
        ];
    }

}
