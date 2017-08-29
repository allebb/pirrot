<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class HelpCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class HelpCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->writeln();
        $this->writeln('Pirrot - A simplex repeater controller for RaspberryPi');
        $this->writeln();
        $this->writeln('Usage: pirrot [COMMAND]');
        $this->writeln();
        $this->writeln('Commands:');
        $this->writeln('  help       - This information screen');
        $this->writeln('  ident      - Repeater Ident service daemon.');
        $this->writeln('  voice      - Repeater Voice I/O service daemon.');
        $this->writeln('  update     - Runs the software updater.');
        $this->writeln();
        $this->exitWithSuccess();
    }
}