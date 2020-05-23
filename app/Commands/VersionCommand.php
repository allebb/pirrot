<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Pirrot\Services\SystemInfoService;

/**
 * Class VersionCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class VersionCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {

        $systemInfo = new SystemInfoService();

        $systemInfo->detect($this->config->get('web_gps_enabled', false));

        if ($this->arguments()->isFlagSet('dump')) {
            $systemInfo = new SystemInfoService();
            $systemInfo->detect($this->config->get('web_gps_enabled', false));
            $this->write(json_encode([
                'hostname' => $systemInfo->hostname,
                'hardware_model' => $systemInfo->hardwareModel,
                'hardware_serial' => $systemInfo->hardwareSerial,
                'hardware_cpu_arch' => $systemInfo->hardwareCpuArch,
                'hardware_cpu_count' => $systemInfo->hardwareCpuCount,
                'hardware_cpu_freq' => $systemInfo->hardwareCpuFrequency,
                'version_kernel' => $systemInfo->kernelVersion,
                'version_raspbian' => $systemInfo->raspbainVersion,
                'version_php' => $systemInfo->phpVersion,
                'version_pirrot' => $systemInfo->pirrotVersion,
                'gps_configured' => $systemInfo->hasGpsConfigured,

            ]));
            $this->exitWithSuccess();
        }

        if ($this->arguments()->isFlagSet('json')) {
            $this->write(json_encode([
                'version' => $systemInfo->pirrotVersion,
                'hw_version' => $systemInfo->hardwareModel,
                'os_version' => $systemInfo->raspbainVersion,
                'compiler_version' => $systemInfo->phpVersion . ' on ' . $systemInfo->hardwareCpuArch,
            ]));
            $this->exitWithSuccess();
        }

        $this->writeln('Pirrot v' . $systemInfo->pirrotVersion);
        $this->writeln('    - HW version: ' . $systemInfo->hardwareModel);
        $this->writeln('    - OS version: ' . $systemInfo->raspbainVersion);
        $this->writeln('    - Compiler version: ' . $systemInfo->phpVersion . ' on ' . $systemInfo->hardwareCpuArch);
        $this->exitWithSuccess();
    }


}