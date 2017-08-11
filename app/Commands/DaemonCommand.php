<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\ConsoleApplication;
use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Piplex\Commands
 */
class DaemonCommand extends ConsoleApplication implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * Disables GPIO functionality (great for dev/testing purposes.)
     *
     * @var bool
     */
    private $disableGPIO = false;

    /**
     * DaemonCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {

        // Disable GPIO.
        if ($argv->options()->has('disable-gpio')) {
            $this->disableGPIO = true;
        }

        parent::__construct($argv);
    }

    /**
     * Handle the command.
     */
    public function handle()
    {

        // Set the timezone...
        $this->setTimezone();

        // Output some debug information.
        $this->writeln("Piplex server is running...");

        // The main application service loop...
        while (true) {

            $this->mainLoop();
        }

    }

    /**
     * The main application loop.
     */
    private function mainLoop()
    {

    }

    /**
     * Sets the default timezone for the application.
     *
     * @param string $timezone
     */
    private function setTimezone($timezone = 'Europe/London')
    {
        date_default_timezone_set($timezone);
    }

}