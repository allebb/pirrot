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
     * @return void
     */
    public function handle()
    {

        if (!file_exists($this->basePath . '/.git')) {
            $this->writeln('UPDATE ABORTED! Pirrot can only be upgraded installed using Git...');
            $this->exitWithSuccess();
        }

        $this->writeln('Downloading latest source from GitHub...');
        system("cd /opt/pirrot && sudo git reset --hard");
        system("cd /opt/pirrot && sudo git pull");
        system("sudo composer install --working-dir /opt/pirrot --no-dev --no-interaction");
        system("cd /opt/pirrot/web && sudo git reset --hard");
        system("cd /opt/pirrot/web && sudo git pull");
        system("sudo composer install --working-dir /opt/pirrot/web --no-dev --no-interaction");
        $this->writeln('Upgrade completed!');
        $this->writeln();
        $this->writeln('** REMEMBER TO RESTART THE PIRROT DAEMON **');
        $this->writeln('   Use: sudo service pirrot restart');
        $this->exitWithSuccess();
    }
}