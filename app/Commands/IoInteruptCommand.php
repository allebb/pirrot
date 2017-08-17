<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\PinInterface;
use PiPHP\GPIO\Pin\InputPinInterface;
use PiPHP\GPIO\Pin\OutputPinInterface;

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

    private $counter = 0;

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

        // Output some debug information...
        $this->writeln('Starting test IO runner...');

        // Set pin types...
        $pin = $this->gpio->getInputPin(23);

        // Configure interrupts for both rising and falling edges
        $pin->setEdge(InputPinInterface::EDGE_BOTH);

        // Create an interrupt watcher
        $interruptWatcher = $this->gpio->createWatcher();

        // Register a callback to be triggered on pin interrupts
        $interruptWatcher->register($pin, function (InputPinInterface $pin, $value) {
            $this->counter++;
            $this->writeln('[' . $this->counter . '] Pin ' . $pin->getNumber() . ' changed to: ' . $value);
            // Returning false will make the watcher return false immediately
            //usleep(300); // prevent debouncing on the button.
            return true;
        });

        // Watch for interrupts, timeout after 5000ms (5 seconds)
        while ($interruptWatcher->watch(5000)) {
            // Looping...
            ;
        }
        // Output that we've finished the loop...
        $this->writeln('Completed the test IO runner!');
        $this->exitWithSuccess();
    }

}