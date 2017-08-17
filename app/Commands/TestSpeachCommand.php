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
        while (true) {
            //$this->audioService->tone('3up');
            //$this->audioService->say('bobby06');
            //$this->audioService->ident('W123', '110.9', true, false);
            //$this->audioService->sayNumber(109);
            //$this->audioService->say('.');
            //$this->audioService->sayNumber(23);

            $this->audioService->sayNumber(6);
            $this->audioService->sayNumber(29);
            $this->audioService->sayNumber(123);
            $this->audioService->sayNumber(4355);
            sleep(5);
        }
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