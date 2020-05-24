<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\GPIO\Exceptions\GPIOException;
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
        '/opt/pirrot/web/public', # Will kill the Pirrot Web Interface (if it's running)
        '/usr/bin/play',
        '/usr/bin/sox',
    ];

    /**
     * Handle the command.
     * @return void
     * @throws GPIOException
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

        $this->writeln($this->getCurrentLogTimestamp() . 'Pirrot has been stopped!');

        exit(0);
    }
}