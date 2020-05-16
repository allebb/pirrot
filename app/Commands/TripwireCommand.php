<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class HelpCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class TripwireCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {
        $url = $this->arguments()->getOption('url', null);

        if (!$url) {
            return $this->exitWithError();
        }

        $this->writeln('Sending tripwire hook to: ' . $url);

        // @todo Add code here to check if an optional interval has passed since the last HTTP hook request.
        // @todo Add code here to send the web hook.

        $this->exitWithSuccess();
    }
}