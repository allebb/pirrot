<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\GPIO\GPIO;

/**
 * Class HelpCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class TerminateCommand extends AudioCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    private $managedProcessPaths = [
        '/usr/bin/play',
        '/usr/bin/sox',
    ];

    /**
     * Handle the command.
     * @return void
     * @throws \Ballen\GPIO\Exceptions\GPIOException
     */
    public function handle()
    {

        // Kill any other processes that Pirrot spawns/manages
        foreach ($this->managedProcessPaths as $process) {
            system("pkill -f " . $process);
        }

        // Set the GPIO LED's back to default and stop transmitting (all outputs should be off)
        $this->outputLedPwr->setValue(GPIO::LOW);
        $this->outputPtt->setValue(GPIO::LOW);
        $this->outputLedRx->setValue(GPIO::LOW);
        $this->outputLedTx->setValue(GPIO::LOW);

        exit(0);
    }
}