<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\ConsoleApplication;
use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class HelpCommand
 *
 * @package Ballen\Piplex\Commands
 */
class HelpCommand extends ConsoleApplication implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->writeln();
        $this->writeln('Piplex - A simplex repeater controller for RaspberryPi');
        $this->writeln();
        $this->writeln('Usage: piplex [OPTIONS]');
        $this->writeln();
        $this->writeln('Commands:');
        $this->writeln('  help       - This information screen');
        $this->writeln('  daemon     - Runs the service daemon.');
        $this->writeln();
        $this->writeln('Options:');
        $this->writeln('  daemon --disable-gpio=true     - Disables the GPIO transmission.');
        $this->writeln();
        $this->exitWithSuccess();
    }
}