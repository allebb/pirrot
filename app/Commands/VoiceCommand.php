<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;

/**
 * Class VoiceCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class VoiceCommand extends AudioCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * The TX/RX mode for voice comms.
     *
     * @var string
     */
    private $mode;

    /**
     * VoiceCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);

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
            $this->storeRecording();
            $this->write("Starting TX...");
            $this->audioService->play($this->basePath . '/storage/input/buffer.ogg');
            $this->sendCourtesyTone();
        }
    }

    private function mainCos()
    {
        while (true) {
            $this->writeln('Running COS main loop!');
            sleep(10); // Temp until i've implemented the logic for handling the I/O.
        }
    }

    /**
     * If enabled, archives/stores the audio transmission.
     *
     * @return void
     */
    private function storeRecording()
    {
        if ($this->config->get('store_recordings')) {
            $date = date('YmdHis');
            system('cp ' . $this->basePath . '/storage/input/buffer.ogg ' . $this->basePath . '/storage/recordings/' . $date . '.ogg');
        }
    }

    /**
     * If enabled, plays the courtesy tone.
     *
     * @return void
     */
    private function sendCourtesyTone()
    {
        if ($this->config->get('courtesy_tone', false)) {
            $this->audioService->tone($this->config->get('courtesy_tone', 'Beep'));
        }
    }


}