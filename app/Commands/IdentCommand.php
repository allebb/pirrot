<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Piplex\Services\AudioService;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Piplex\Commands
 */
class IdentCommand extends PiplexBaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    private $audioService;

    /**
     * DaemonCommand constructor.
     *
     * @param ArgumentsParser $args
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
        $this->audioService = new AudioService();
        $this->audioService->soundPath = $this->basePath . '/resources/sound/';
        /**
         * @todo Make the audioPlayerBin path read from a system config of load using 'which sox/play'
         */
        $this->audioService->audioPlayerBin = '/usr/local/sox/play -q';
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