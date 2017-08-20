<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Piplex\Services\AudioService;

class AudioBaseCommand extends PiplexBaseCommand
{

    protected $audioService;

    public function __construct(ArgumentsParser $argv)
    {

        $this->audioService = new AudioService();
        $this->audioService->soundPath = $this->basePath . '/resources/sound/';
        /**
         * @todo Make the audioPlayerBin path read from a system config of load using 'which sox/play'
         */
        $this->audioService->audioPlayerBin = '/usr/local/sox/play -q';

        parent::__construct($argv);
    }

}