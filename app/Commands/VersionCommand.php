<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class HelpCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class VersionCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * The file where the OS information can be retrieved from.
     */
    const OS_BUILD_INFO_FILE = "/etc/os-release";

    /**
     * The file where the Raspberry Pi Hardware version can be retrieved from.
     */
    const HARDWARE_VERSION_FILE = "/sys/firmware/devicetree/base/model";

    /**
     * The detected operating system version that Pirrot is running on.
     * @var string
     */
    private $raspbainVersion = "**not detected**";

    /**
     * The detected Raspberry Pi hardware version that Pirrot is running on.
     * @var string
     */
    private $hardwareVersion = "**not detected**";

    /**
     * The Pirrot version (release) number.
     * @var string
     */
    private $pirrotVersion = "**not detected**";

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {
        $this->detectPirrotVersion();
        $this->detectRaspbianVersion();
        $this->detectHardwareVersion();
        
        if ($this->arguments()->isFlagSet('json')) {
            $this->write(json_encode([
                'version' => $this->pirrotVersion,
                'hw_version' => $this->hardwareVersion,
                'os_version' => $this->raspbainVersion,
                'compiler_version' => phpversion() . ' (' . php_uname('v') . ') on ' . php_uname('m'),
            ]));
            $this->exitWithSuccess();
        }

        $this->writeln('Pirrot v' . $this->pirrotVersion);
        $this->writeln('    - HW version: ' . $this->hardwareVersion);
        $this->writeln('    - OS version: ' . $this->raspbainVersion);
        $this->writeln('    - Compiler version: ' . phpversion() . ' (' . php_uname('v') . ') on ' . php_uname('m'));
        $this->exitWithSuccess();
    }

    /**
     * Detects the Raspberry Pi hardware version.
     * @return void
     */
    private function detectHardwareVersion()
    {
        if (!file_exists('/sys/firmware/devicetree/base/model')) {
            return;
        }
        $this->hardwareVersion = file_get_contents(self::HARDWARE_VERSION_FILE);
    }

    /**
     * Detects the operating system version.
     * @return void
     */
    private function detectRaspbianVersion()
    {
        $osMatches = [];
        $versionMatches = [];
        if (!file_exists(self::OS_BUILD_INFO_FILE)) {
            return;
        }
        $osReleaseFile = file_get_contents(self::OS_BUILD_INFO_FILE);
        preg_match("/\bID=(.+)/i", $osReleaseFile, $osMatches);
        preg_match("/\bVERSION=(.+)/i", $osReleaseFile, $versionMatches);

        if (isset($versionMatches[1])) {
            $this->raspbainVersion = trim(ucwords($osMatches[1]), '"') . " " . trim($versionMatches[1], '"');
        }
    }

    /**
     * Detects the Pirrot release version.
     * @return void
     */
    private function detectPirrotVersion()
    {
        $pirrotVersionInfo = '/opt/pirrot/VERSION';
        if (!file_exists($pirrotVersionInfo)) {
            return;
        }
        $this->pirrotVersion = trim(file_get_contents($pirrotVersionInfo));
    }
}