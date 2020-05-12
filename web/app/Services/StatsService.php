<?php

namespace App\Services;

class StatsService
{

    public function versions(): array
    {
        $data = shell_exec(env('PIRROT_PATH') . DIRECTORY_SEPARATOR . 'pirrot version --json');
        return json_decode($data, true);
    }

    public function temperature(): string
    {
        $data = shell_exec('vcgencmd measure_temp | egrep -o \'[0-9]*\.[0-9]*\'');

        if (!$data) {
            return '**not detected**';
        }

        return trim($data);
    }

    public function hostname(): string
    {
        return gethostname();
    }
}
