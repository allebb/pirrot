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
     * @return void
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
        $this->writeln('  update     - Runs the software updater.');
        $this->writeln('  version    - Output version and runtime information.');
        $this->writeln('');
        $this->writeln('  setwebpwd  - Set new web interface admin password.');
        $this->writeln();
        $this->exitWithSuccess();
    }
}