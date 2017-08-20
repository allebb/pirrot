<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Piplex\Services\AudioService;

/**
 * Class AudioBaseCommand
 *
 * @package Ballen\Piplex\Commands
 */
class AudioBaseCommand extends PiplexBaseCommand
{

    /**
     * The audio service class.
     *
     * @var AudioService
     */
    protected $audioService;

    /**
     * AudioBaseCommand constructor.
     *
     * @param ArgumentsParser $argv
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

}