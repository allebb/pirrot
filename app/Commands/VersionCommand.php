<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class HelpCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class VersionCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * The Pirrot release/version number
     */
    const PIRROT_VERSION = "1.5.0";

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {
        $this->writeln('Pirrot v' . self::PIRROT_VERSION . ' ');
        $this->writeln(' Runtime: ' . phpversion() . ' (' . php_uname('v') . ') on ' . php_uname('v'));
        $this->exitWithSuccess();
    }
}