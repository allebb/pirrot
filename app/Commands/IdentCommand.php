<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Piplex\Commands
 */
class IdentCommand extends AudioBaseCommand implements CommandInterface
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
        while (true) {
            if ($this->config->get('auto_ident')) {
                $this->audioService->ident(
                    $this->config->get('callsign'),
                    $this->config->get('pl_tone', null),
                    $this->config->get('ident_time'),
                    $this->config->get('ident_morse')
                );
            }
            sleep($this->config->get('ident_interval'));
        }
    }


}