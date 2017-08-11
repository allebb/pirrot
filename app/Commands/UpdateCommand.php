<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class UpdateCommand
 *
 * @package Ballen\Piplex\Commands
 */
class UpdateCommand extends PiplexBaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->writeln('Downloading latest source from GitHub...');
        system("git reset --hard");
        system("git pull");
        system("/opt/piplex/build.sh");
        $this->writeln('Upgrade completed!');
        $this->writeln();
        $this->writeln('** REMEMBER TO RESTART THE PIPLEX DAEMON **');
        $this->writeln('   Use: sudo /etc/init.d/piplex stop && sudo /etc/init.d/piplex start');
        $this->exitWithSuccess();
    }
}