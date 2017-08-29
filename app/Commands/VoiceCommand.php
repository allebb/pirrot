<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Executioner\Executioner;

//use Ballen\Pirrot\Interfaces\RepeatableInterface;

/**
 * Class VoiceCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class VoiceCommand extends AudioBaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * The TX/RX mode for voice comms.
     *
     * @var string
     */
    private $mode;

    /**
     * The system executioner library.
     *
     * @var Executioner
     */
    private $executioner;

    /**
     * VoiceCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);

        // Create a new instance of executioner
        $this->executioner = new Executioner();

        // Sets the transmit/receive mode
        $this->mode = ucwords($this->config->get('transmit_mode'));
    }

    /**
     * Handle the command.
     */
    public function handle()
    {

        // Detect if the repeater is enabled/disabled...
        if (!$this->config->get('enabled', false)) {
            $this->writeln('Repeater disabled in the configuration file!');
            $this->exitWithSuccess();
        }

        // Detect and handle the current RX/TX mode...
        $modeHandler = "main{$this->mode}";
        if (method_exists($this, $modeHandler)) {
            return $this->{$modeHandler}();
        }
        $this->writeln("RX/TX mode ({$this->mode}) not supported!");
        $this->exitWithError();
    }

    private function mainVox()
    {
        while (true) {
            $this->writeln("Starting RX...");
            system($this->audioService->audioRecordBin . ' -t ' . $this->config->get('record_device',
                    'alsa') . ' default ' . $this->basePath . '/storage/input/buffer.ogg -V0 silence 1 0.1 5% 1 1.0 5%');

            if ($this->config->get('store_recordings')) {
                $date = date('YmdHis');
                system('mv ' . $this->basePath . '/storage/input/buffer.ogg ' . $this->basePath . '/storage/recordings/' . $date . '.ogg');
            }

            $this->write("Starting TX...");
            $this->audioService->play($this->basePath . ' / storage / input / buffer . ogg');

            // If a courtesy tone is enabled, lets play that now...
            if ($this->config->get('courtesy_tone', false)) {
                $this->audioService->tone($this->config->get('courtesy_tone', 'Beep'));
            }
        }
    }

    private function mainCos()
    {
        $this->writeln('Running COS main loop!');
    }


}