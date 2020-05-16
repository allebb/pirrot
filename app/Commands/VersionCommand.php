<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Pirrot\Services\SystemInfoService;

/**
 * Class HelpCommand
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

        $systemInfo->detect();

        if ($this->arguments()->isFlagSet('debug')) {
            $systemInfo = new SystemInfoService();
            $systemInfo->detect();
            $this->write((string)$systemInfo);
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