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

    /**
     * The application base path.
     *
     * @var string
     */
    public $basePath;

    public function __construct(ArgumentsParser $argv)
    {
        $this->getBasePath();
        $this->retrieveConfiguration();
        parent::__construct($argv);
    }

    private function retrieveConfiguration()
    {
        $this->config = new Config($this->basePath . '/build/configs/piplex_default.conf');
    }

    private function getBasePath()
    {
        $path = rtrim(realpath(__DIR__), 'app/Commands');
        $this->basePath = $path;
    }
}