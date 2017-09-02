<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class UpdateCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class UpdateCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->writeln('Downloading latest source from GitHub...');
        system("sudo git reset --hard");
        system("sudo git pull");
        $this->writeln('Upgrade completed!');
        $this->writeln();
        $this->writeln('** REMEMBER TO RESTART THE PIRROT DAEMON **');
        $this->writeln('   Use: sudo /etc/init.d/pirrot stop && sudo /etc/init.d/pirrot start');
        $this->exitWithSuccess();
    }
}