<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\GPIO;

/**
 * Class IoCommand
 *
 * @package Ballen\Piplex\Commands
 */
class IoInteruptCommand extends PiplexBaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * The GPIO Driver
     *
     * @var GPIO
     */
    private $gpio;

    /**
     * Disables GPIO functionality (great for dev/testing purposes.)
     *
     * @var bool
     */
    private $disableGPIO = false;

    private $previousState = 0;

    /**
     * DaemonCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);

        // Disable GPIO.
        if ($argv->options()->has('disable-gpio')) {
            $this->disableGPIO = true;
        } else {
            $this->gpio = new GPIO();
        }

    }

    /**
     * Handle the command.
     */
    public function handle()
    {

        if ($this->disableGPIO) {
            $this->writeln('GPIO is disabled, exiting...');
            $this->exitWithError();
        }

        $switch = $this->gpio->pin(23, GPIO::IN);

        // Output some debug information...
        $this->writeln('Starting test IO runner...');


        while (true) {
            $val = $switch->getValue();
            if ($val != $this->previousState) {
                $this->previousState = $val;
                $this->writeln('Switch state changed to ' . $val);
            }
            usleep(50000); // Half a second
        }
        $this->exitWithSuccess();
    }

}