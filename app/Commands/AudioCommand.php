<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Executioner\Exceptions\ExecutionException;
use Ballen\Executioner\Executioner;
use Ballen\GPIO\Adapters\VfsAdapter;
use Ballen\GPIO\Exceptions\GPIOException;
use Ballen\GPIO\GPIO;
use Ballen\Pirrot\Services\AudioService;

/**
 * Class AudioCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class AudioCommand extends BaseCommand
{

    /**
     * The audio service class.
     *
     * @var AudioService
     */
    protected $audioService;

    /**
     * Auto-detected Binary path locations.
     *
     * @var array
     */
    protected $binPaths = [];

    /**
     * AudioCommand constructor.
     *
     * @param ArgumentsParser $argv
     * @throws GPIOException
     */
    public function __construct(ArgumentsParser $argv)
    {

        parent::__construct($argv);

        $this->detectExternalBinaries([
            'sox',
            'play',
        ]);

        $this->audioService = new AudioService();
        $this->audioService->soundPath = $this->basePath . '/resources/sound/';
        $this->audioService->audioPlayerBin = $this->binPaths['play'] . ' -q';
        $this->audioService->audioRecordBin = $this->binPaths['sox'] . ' -q';

        $this->gpio = $this->initGpio();
    }

    /**
     * Used to detect external binraries required.
     *
     * @param array $binaries
     */
    private function detectExternalBinaries(array $binaries)
    {
        foreach ($binaries as $bin) {
            $executioner = new Executioner();
            $executioner->setApplication('which')->addArgument($bin);
            try {
                $executioner->execute();
            } catch (ExecutionException $ex) {
                $this->writeln("ERROR: The dependency \"{$bin}\" was not found; please install and/or reference it in your \$PATH!");
                $this->exitWithError();
            }
            $this->binPaths[$bin] = trim($executioner->resultAsText());
        }
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
     * @throws GPIOException
     */
    private function initGpio()
    {
        $gpio = new GPIO(new VfsAdapter());
        if ($this->detectGpioFilesystem()) {
            $gpio = new GPIO();
        }

        // Configure GPIO pin types.
        $this->inputCor = $gpio->pin(
            $this->config->get('in_cor_pin'),
            GPIO::IN,
            $this->config->get('cos_pin_invert', false)
        );
        $this->outputPtt = $gpio->pin(
            $this->config->get('out_ptt_pin'),
            GPIO::OUT,
            $this->config->get('ptt_pin_invert', false)
        );
        $this->outputLedPwr = $gpio->pin(
            $this->config->get('out_ready_led_pin'),
            GPIO::OUT,
            $this->config->get('ready_pin_invert', false)
        );
        $this->outputLedRx = $gpio->pin(
            $this->config->get('out_rx_led_pin'),
            GPIO::OUT,
            $this->config->get('rx_pin_invert', false)
        );
        $this->outputLedTx = $gpio->pin(
            $this->config->get('out_tx_led_pin'),
            GPIO::OUT,
            $this->config->get('tx_pin_invert', false)
        );

        // Set I/O defaults
        $this->outputLedPwr->setValue(GPIO::HIGH);
        $this->outputLedRx->setValue(GPIO::LOW);
        $this->outputLedTx->setValue(GPIO::LOW);
        $this->outputPtt->setValue(GPIO::LOW);

        return $gpio;
    }

}