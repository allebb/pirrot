<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\ConsoleApplication;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Piplex\Foundation\Config;

class PiplexBaseCommand extends ConsoleApplication
{
    /**
     * The software configuration.
     *
     * @var Config
     */
    public $config;

    public function __construct(ArgumentsParser $argv)
    {
        $this->retrieveConfiguration();
        parent::__construct($argv);
    }

    private function retrieveConfiguration()
    {
        $this->config = new Config('/Users/ballen/Code/piplex/build/configs/piplex_default.conf');
    }
}