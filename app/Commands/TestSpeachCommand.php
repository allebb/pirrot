<?php

namespace Ballen\Piplex\Commands;

use Ballen\Piplex\Services\AudioService;
use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Piplex\Commands
 */
class TestSpeachCommand extends PiplexBaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    private $audioService;

    /**
     * DaemonCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        $this->setTimezone();
        $this->audioService = new AudioService();
        $this->audioService->soundPath = rtrim(realpath(__DIR__), 'app/Commands') . '/resources/sound/';
        $this->audioService->audioPlayerBin = '/usr/local/sox/play -q';
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        var_dump($this->config->all());
        var_dump($this->config->get('timezone', false));
        var_dump($this->config->callsign);

        $this->audioService->announce('online.wav');
        while (true) {
            if ($this->config->get('auto_ident')) {
                $this->audioService->ident(
                    $this->config->get('callsign'),
                    $this->config->get('pl_tone'),
                    $this->config->get('ident_time'),
                    $this->config->get('ident_morse')
                );
            }
            sleep($this->config->get('ident_interval'));
            // After transmission we can call the tone like so:
            //$this->audioService->tone('3up');


        }
        $this->audioService->announce('deactivating.wav');
    }

    /**
     * Sets the default timezone for the application.
     *
     * @param string $timezone
     */
    private function setTimezone($timezone = 'Europe/London')
    {
        date_default_timezone_set($timezone);
    }

}