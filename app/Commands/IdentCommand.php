<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\GPIO;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class IdentCommand extends AudioCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * DaemonCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     */
    public function handle()
    {

        // Detect if the repeater 'ident' is enabled/disabled...
        if (!$this->config->get('auto_ident')) {
            $this->writeln('Repeater disabled in the configuration file!');
            $this->exitWithSuccess();
        }

        while (true) {
            $this->outputLedTx->setValue(GPIO::HIGH);
            $this->audioService->ident(
                $this->config->get('callsign'),
                $this->config->get('pl_tone', null),
                $this->config->get('ident_time'),
                $this->config->get('ident_morse')
            );
            $this->outputLedTx->setValue(GPIO::LOW);
            sleep($this->config->get('ident_interval'));
        }
    }


}