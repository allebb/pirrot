<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Pirrot\Services\VersionCheckService;

/**
 * Class WebCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class WebCommand extends BaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * The PHP binary path
     *
     * @var string
     */
    public $phpBin = '/usr/bin/php';

    /**
     * IdentCommand constructor.
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {
        $webInterfacePort = $this->config->get('web_interface_port', 8440);
        $webInterfaceBindIp = $this->config->get('web_interface_bind_ip', '0.0.0.0');

        $this->setProcessName('pirrot-web');

        $logger = '/dev/null'; // Default the logger to use /dev/null
        if ($this->config->get('web_interface_logging', false)) {
            $logger = '/var/log/pirrot-web.log';
        }

        if ($this->config->get('web_interface_enabled')) {
            $this->writeln($this->getCurrentLogTimestamp() . 'Starting web interface on ' . $webInterfaceBindIp . ':' . $webInterfacePort);
            $this->readAndCacheSystemInfo();
            $this->readAndLatestVersionInfo();
            system($this->phpBin . ' -S ' . $webInterfaceBindIp . ':' . $webInterfacePort . ' -t ' . $this->basePath . '/web/public 2> ' . $logger . ' &');
            $this->exitWithSuccess();
        }
    }

    /**
     * Reads and caches system information on web interface init.
     * @return void
     */
    private function readAndCacheSystemInfo()
    {
        $sysInfo = shell_exec($this->basePath . '/pirrot version --dump');
        file_put_contents($this->basePath . '/storage/sysinfo.cache', trim($sysInfo));
    }

    /**
     * Reads and caches latest version information to improve performance on the on web interface dashboard..
     * @return void
     */
    private function readAndLatestVersionInfo()
    {
        $vcs = new VersionCheckService();
        if ($version = $vcs->getLatestVersion()) { // Only update the cache file if we was able to contact the version checker service!
            file_put_contents($this->basePath . '/storage/version.cache', $vcs->getLatestVersion());
        }
    }


}