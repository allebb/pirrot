<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class SetAdminPwdCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class SetAdminPwdCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {

        $password = $this->arguments()->getOption('password', null);

        if (!$password) {
            $this->writeln('No password specified, please set using this command syntax:');
            $this->writeln(' sudo pirrot setwebpwd --password="YourNewPasswordHere"');
            $this->writeln('');
            $this->exitWithError();
        }

        $securedPassword = password_hash($password, PASSWORD_BCRYPT);

        if (!file_put_contents('/opt/pirrot/storage/password.vault', $securedPassword)) {
            $this->writeln('Error, could not store updated password!');
            $this->exitWithError();
        }

        $this->writeln("Password has been updated successfully!");
        $this->exitWithSuccess();
    }
}