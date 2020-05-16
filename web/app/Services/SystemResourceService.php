<?php

namespace App\Services;

class SystemResourceService
{

    const DATE_OUTPUT_FORMAT = 'jS M Y H:m';

    public function toArray()
    {
        return [

            // Times
            'booted' => date(self::DATE_OUTPUT_FORMAT, 4322), // @todo update with the detected system boot timestamp.
            'uptime_time' => '0d 4h 34m', // @todo Update with the calculated (day hour minutes) that the system has bee online since.
            'system_time' => date(self::DATE_OUTPUT_FORMAT),

            // Usage Percentages
            'cpu_percent' => '32',
            'ram_percent' => '67',
            'disk_percent' => '23',

            // Temperature
            'temp_c' => $this->getTemperature(),
            'temp_f' => $this->getTemperature(),

            // GPS Data
            'gps_lat' => '0.0',
            'gps_lng' => '0.0.',
            'gps_alt' => '0',
            'gps_spd' => '0',
        ];
    }


    public function getTemperature(): string
    {
        $data = shell_exec('vcgencmd measure_temp | egrep -o \'[0-9]*\.[0-9]*\'');

        if (!$data) {
            return '**not detected**';
        }
        return trim($data);
    }

}
