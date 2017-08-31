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
     * GPIO object
     *
     * @var GPIO
     */
    public $gpio;

    /**
     * COS Input Pin
     *
     * @var Pin
     */
    protected $inputCos;

    /**
     * PTT/TX Relay Pin
     *
     * @var Pin
     */
    protected $outputPtt;

    /**
     * Power LED Pin
     *
     * @var Pin
     */
    protected $outputLedPwr;

    /**
     * Receive LED Pin
     *
     * @var Pin
     */
    protected $outputLedRx;

    /**
     * Transmit LED Pin
     *
     * @var Pin
     */
    protected $outputLedTx;

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
        parent::__construct($argv);
    }

    /**
     * Retrieve and merge the software configuration.
     *
     * @return void
     */
    private function retrieveConfiguration()
    {
        $this->config = new Config('/etc/pirrot.conf', $this->basePath . '/build/configs/pirrot_default.conf');
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