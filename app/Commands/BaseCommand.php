<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\ConsoleApplication;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\Adapters\VfsAdapter;
use Ballen\GPIO\GPIO;
use Ballen\GPIO\Pin;
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
    private $inputCos;

    /**
     * PTT/TX Relay Pin
     *
     * @var Pin
     */
    private $outputPtt;

    /**
     * Power LED Pin
     *
     * @var Pin
     */
    private $outputLedPwr;

    /**
     * Receive LED Pin
     *
     * @var Pin
     */
    private $outputLedRx;

    /**
     * Transmit LED Pin
     *
     * @var Pin
     */
    private $outputLedTx;

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
        $this->gpio = $this->initGpio();
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

    /**
     * Initialise the GPIO handler object.
     *
     * @return GPIO
     */
    private function initGpio()
    {
        $gpio = GPIO(VfsAdapter::class);
        if ($this->detectGpioFilesystem()) {
            $gpio = new GPIO();
        }
        $this->inputCos = $gpio->pin($this->config->get('in_cos_pin'), GPIO::IN);
        $this->outputPtt = $gpio->pin($this->config->get('out_ptt_pin'), GPIO::OUT);
        $this->outputLedPwr = $gpio->pin($this->config->get('out_ready_led_pin'), GPIO::OUT, true);
        $this->outputLedRx = $gpio->pin($this->config->get('out_rx_led_pin'), GPIO::OUT, true);
        $this->outputLedTx = $gpio->pin($this->config->get('out_tx_led_pin'), GPIO::OUT, true);

        $this->outputLedPwr = GPIO::HIGH;
    }

}