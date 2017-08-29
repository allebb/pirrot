<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\ConsoleApplication;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Pirrot\Foundation\Config;

/**
 * Class PirrotxBaseCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class BaseCommand extends ConsoleApplication
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
     * Used to determine if the computer/device that Pirrot is running on is GPIO enabled or not.
     *
     * @var bool
     */
    public $gpioEnabledDevice = false;

    /**
     * BaseCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        $this->getBasePath();
        $this->retrieveConfiguration();
        $this->setTimezone($this->config->get('timezone'));
        $this->gpioEnabledDevice = $this->detectGpioFilesystem();
        parent::__construct($argv);
    }

    /**
     * Retrieve and merge the software configuration.
     *
     * @return void
     */
    private function retrieveConfiguration()
    {
        $this->config = new Config($this->basePath . '/build/configs/pirrot_default.conf');
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

    /**
     * Used to determine if the machine is GPIO enabled.
     *
     * @return false
     */
    private function detectGpioFilesystem()
    {
        if (file_exists('/sys/class/gpio')) {
            return true;
        }
        return false;
    }

}