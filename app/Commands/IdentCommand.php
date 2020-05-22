<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\GPIO;
use Ballen\GPIO\Exceptions\GPIOException;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class IdentCommand extends AudioCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    private $isBooted = false;

    /**
     * IdentCommand constructor.
     * @param ArgumentsParser $argv
     * @throws GPIOException
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     * @return void
     * @throws GPIOException
     */
    public function handle()
    {


        // Detect if the repeater 'ident' is enabled/disabled...
        if (!$this->config->get('auto_ident')) {
            $this->writeln('Repeater Ident disabled in the configuration file!');
            $this->exitWithSuccess();
        }

        while (true) {

            // Delay to ensure IO is not confused at daemon start (due to Voice daemon starting too)
            if (!$this->isBooted) {
                sleep(5);
                $this->isBooted = true;
            }

            $this->outputPtt->setValue(GPIO::HIGH);
            $this->outputLedTx->setValue(GPIO::HIGH);
            $this->audioService->ident(
                $this->config->get('callsign'),
                $this->config->get('pl_tone', null),
                $this->config->get('ident_time'),
                $this->config->get('ident_morse')
            );
            $this->outputPtt->setValue(GPIO::LOW);
            $this->outputLedTx->setValue(GPIO::LOW);

            sleep($this->config->get('ident_interval'));
        }
    }


}