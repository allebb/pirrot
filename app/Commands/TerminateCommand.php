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
        $this->cleanup(null);
    }

    /**
     * Clean up our IO and other Pirrot spawned/managed processes.
     * @param $signal
     * @return void
     */
    public function cleanup($signal)
    {
        $this->writeln();
        $this->writeln('Caught and exited cleanly...');
        file_put_contents('/tmp/testing.txt', date('c') . PHP_EOL, FILE_APPEND);
        exit(0);
    }
}