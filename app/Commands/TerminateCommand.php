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

    /**
     * Handle the command.
     * @return void
     * @throws \Ballen\GPIO\Exceptions\GPIOException
     */
    public function handle()
    {
        // Force the outputs back to their default values...
        //$this->outputLedPwr->setValue(GPIO::HIGH);
        //$this->outputLedRx->setValue(GPIO::LOW);
        //$this->outputLedTx->setValue(GPIO::LOW);
        //$this->outputPtt->setValue(GPIO::LOW);

        $this->writeln();
        $this->writeln('Caught and exited cleanly...');
        file_put_contents('/tmp/testing.txt', date('c') . PHP_EOL, FILE_APPEND);
        $this->exitWithSuccess();
    }
}