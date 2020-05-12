<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\GPIO;
use Ballen\GPIO\Exceptions\GPIOException;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class WebCommand extends AudioCommand implements CommandInterface
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
     * @throws GPIOException
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     * @return void
     * @throws GPIOException
     */
    public function handle()
    {
        $webInterfacePort = $this->config->get('web_interface_port', 8440);
        $webInterfaceBindIp = $this->config->get('web_interface_bind_ip', '0.0.0.0');

        $logger = '/dev/null'; // Default the logger to use /dev/null
        if ($this->config->get('web_interface_logging', false)) {
            $logger = '/var/log/pirrot-web.log';
        }

        if ($this->config->get('web_interface_enabled')) {
            $this->writeln('Starting web interface on ' . $webInterfaceBindIp . ':' . $webInterfacePort);
            system($this->phpBin . ' -S ' . $webInterfaceBindIp . ':' . $webInterfacePort . ' -t /opt/pirrot/web/public 2> ' . $logger . ' &');
            $this->exitWithSuccess();
        }
    }


}