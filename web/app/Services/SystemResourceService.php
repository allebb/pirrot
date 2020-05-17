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


    public function toArray()
    {

        $tempDegreesC = $this->getTemperature();
        $tempDegreesF = round((($tempDegreesC / 5) * 9) + 32, 1);
        $ramUsage = $this->getRamUsage();
        $diskUsage = $this->getDiskUsage();
        $gpsData = $this->getGpsData();

        return [

            // Times
            'booted' => $this->getBootTime(),
            'uptime_time' => $this->getUptime(),
            'system_time' => date(self::DATE_OUTPUT_FORMAT),

            // Usage Percentages
            'cpu_percent' => $this->getCpuUsage(),
            'ram_percent' => ceil(($ramUsage / $this->ramTotal) * 100),
            'ram_usage' => $ramUsage,
            'ram_total' => $this->ramTotal,
            'disk_percent' => ceil(($diskUsage / $this->diskTotal) * 100),
            'disk' => $this->diskUsed,
            'disk_total' => $this->diskTotal,

            // Temperature
            'temp_c' => $tempDegreesC,
            'temp_f' => $tempDegreesF,

            // GPS Data
            'gps_lat' => $gpsData->latitude,
            'gps_lng' => $gpsData->longitude,
            'gps_alt' => $gpsData->altitude,
            'gps_spd' => $gpsData->speed,
        ];
    }

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
        $cpuUsage = trim(shell_exec("grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage \"%\"}'"));
        return rtrim($cpuUsage, "%");
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
        $data = shell_exec('df -m /');
        $line = explode(PHP_EOL, $data);
        preg_match('/(.*)\b(.*)\b(.*)\b(.*)\b(.*)\b(.*)/', $line[1], $columnArray);
        $this->diskUsed = $columnArray[3];
        $this->diskAvailable = $columnArray[4];
        $this->diskTotal = $this->diskUsed + $this->diskAvailable;
        return $this->diskUsed;
    }

    public function getUptime(): string
    {
        $str = @file_get_contents('/proc/uptime');
        $num = floatval($str);
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
        $gpsData = trim(shell_exec("gpspipe -w -n 10"));

        // Data format buffer...
        $gpsDataArray = [];

        // Loop over each line from the feed, JSON decode each line and associate in an array.
        foreach (explode(PHP_EOL, $gpsData) as $line) {
            $dataClass = json_decode(trim($line), true);
            $gpsDataArray[$dataClass->class] = $line;
        }

        // Get satellite PRN's reporting the positional data..
        foreach ($gpsDataArray['SKY']['satellites'] as $satellite) {
            if ($satellite['used']) {
                $gps->satellites[][$satellite['PRN']];
            }
        }

        // Any satellite fix data available as yet?
        if(isset($gpsDataArray['TPV'])){
            return $gps;
        }
        $gps->device = $gpsDataArray['TPV']['device'];
        $gps->time = $gpsDataArray['TPV']['time'];
        $gps->latitude = $gpsDataArray['TPV']['lat'];
        $gps->longitude = $gpsDataArray['TPV']['lon'];
        $gps->altitude = $gpsDataArray['TPV']['alt'];
        $gps->speed = $gpsDataArray['TVP']['speed'];

        return $gps;
    }

    private function removeKbSuffix(string $string)
    {
        return str_replace(' kB', '', trim($string));
    }


}
