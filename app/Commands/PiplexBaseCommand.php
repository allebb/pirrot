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

    /**
     * PiplexBaseCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        $this->getBasePath();
        $this->retrieveConfiguration();
        $this->setTimezone($this->config->get('timezone'));
        parent::__construct($argv);
    }

    /**
     * Retrieve and merge the software configuration.
     *
     * @return void
     */
    private function retrieveConfiguration()
    {
        $this->config = new Config($this->basePath . '/build/configs/piplex_default.conf');
    }

    /**
     * Computes the base path of the applicaiton.
     *
     * @return void
     */
    private function getBasePath()
    {
        $path = rtrim(realpath(__DIR__), 'app/Commands');
        $this->basePath = $path;
    }

    /**
     * Sets the default timezone for the application.
     *
     * @param string $timezone
     * @return void
     */
    private function setTimezone($timezone = 'Europe/London')
    {
        date_default_timezone_set($timezone);
    }
}