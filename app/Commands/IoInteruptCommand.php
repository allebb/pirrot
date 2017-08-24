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

        // COS (Carrier signal relay)
        $cos = $this->gpio->pin(18, GPIO::IN);

        // PTT (Transmit relay)
        $ptt = $this->gpio->pin(23, GPIO::OUT);

        // Device LEDS
        $power = $this->gpio->pin(17, GPIO::OUT);
        $rx = $this->gpio->pin(27, GPIO::OUT);
        $tx = $this->gpio->pin(22, GPIO::OUT);

        // Turn the power LED on...
        $power->setValue(GPIO::HIGH);

        // Output some debug information...
        $this->writeln('Starting test IO runner...');

        // Record how long the communication was for (then transmit that long!)
        $cosTimer = 0;

        try {
            while (true) {

                // Get the COS value (are we recieving?)
                $val = $cos->getValue();

                if ($cosTimer > 0 && $val == GPIO::LOW) {
                    // We're simply playing back the message and then resetting the counter and returning to the start...
                    $tx->setValue(GPIO::HIGH);
                    usleep(50000 * $cosTimer);
                    $cosTimer = 0;
                    $tx->setValue(GPIO::LOW);
                    continue; // Restart the loop again.
                }

                // If the COS is high we turn on the RX led otherwise we turn if off!
                if ($val == GPIO::HIGH) {
                    $cosTimer++; // Count the comminication time (we'll transmit for this long!)
                    $rx->setValue(GPIO::HIGH);
                } else {
                    $rx->setValue(GPIO::LOW);
                }

                // If the state has changed, we'll output it to the display!
                if ($val != $this->previousState) {
                    $this->previousState = $val;
                    $this->writeln($val);
                }
                usleep(50000); // Half a second to wait for new input...
            }
        } finally {
            // The try/catch loop helps us do stuff if we quit out of the loop!
            $power->setValue(GPIO::LOW);
        }
        $this->exitWithSuccess();
    }

}