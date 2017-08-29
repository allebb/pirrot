<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\GPIO;

/**
 * Class IoCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class IoInteruptCommand extends BaseCommand implements CommandInterface
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
        $ptt = $this->gpio->pin(23, GPIO::OUT, true);

        // Device LEDS
        $power = $this->gpio->pin(17, GPIO::OUT, true);
        $rx = $this->gpio->pin(27, GPIO::OUT, true);
        $tx = $this->gpio->pin(22, GPIO::OUT, true);

        // Turn the power LED on (and set other defaults to off)...
        $power->setValue(GPIO::HIGH);
        $rx->setValue(GPIO::LOW);
        $tx->setValue(GPIO::LOW);
        $ptt->setValue(GPIO::LOW);

        // Output some debug information...
        $this->writeln('Starting test Repeater Card Tool...');

        // Record how long the communication was for (then transmit that long!)
        $cosTimer = 0;

        while (true) {

            $cosInput = $cos->getValue(); // Get the COS value (are we receiving?)

            // Do we have an unplayed "message" and is the PTT button now depressed?
            if ($cosTimer > 0 && $cosInput == GPIO::LOW) {
                // We're simply playing back the message and then resetting the counter and returning to the start...
                $rx->setValue(GPIO::LOW); // Turn OFF the receive LED
                $tx->setValue(GPIO::HIGH); // Turn ON the transmit LED
                $ptt->setValue(GPIO::HIGH); // Turn ON the PTT relay (start transmitting!)
                $messageTime = (50000 * $cosTimer); // Multiple the "ticks" by the number of times we had pressed the COS input
                usleep($messageTime); // Basically dummy load for replaying the message! (this would normally be SOX playing the message back!)
                $ptt->setValue(GPIO::LOW); // Turn the PTT output to OFF
                $tx->setValue(GPIO::LOW); // Turn the TX led to OFF
                $cosTimer = 0; // Reset the cos timer after we've replayed the message!
                continue; // Restart the loop again.
            }

            // If the COS is high we turn on the RX led otherwise we turn if off!
            if ($cosInput == GPIO::HIGH) {
                $cosTimer++; // Increment the communication time (we'll transmit for this long!)
                $rx->setValue(GPIO::HIGH); // Keep setting the RX led to ON!
            } else {
                $rx->setValue(GPIO::LOW); // The COS input is LOW, turn OFF the RX LED!
            }

            // If the state has changed, we'll output it to the display!
            if ($cosInput != $this->previousState) {
                $this->previousState = $cosInput; // Set the new state!
                $this->writeln($cosInput); // Just output the new state of the COS signal!
            }
            usleep(50000); // Half a second to wait for new input...
        }
        $this->exitWithSuccess();
    }

}