<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Executioner\Executioner;
use Ballen\Piplex\Interfaces\RepeatableInterface;

/**
 * Class VoiceCommand
 *
 * @package Ballen\Piplex\Commands
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

        // Sets the transmit/recieve mode
        $this->mode = ucwords($this->config->get('transmit_mode'));
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        $modeHandler = "main{$this->mode}";
        var_dump($modeHandler);
        if (method_exists($this, $modeHandler)) {
            return $this->{$modeHandler}();
        }
        $this->writeln("RX/TX mode ({$this->mode}) now supported!");
        $this->exitWithError();
    }

    private function mainVox()
    {
        while (true) {

            $recorder = $this->executioner->setApplication('/usr/local/sox/sox');
            $recorder->addArgument('-q');
            $recorder->addArgument('-t coreaudio');
            $recorder->addArgument('default');
            $recorder->addArgument('buffer.ogg');
            $recorder->addArgument('-V0');
            $recorder->addArgument('silence');
            $recorder->addArgument('1 0.1 5% 1 1.0 5%');
            $recorder->execute();

            /**
             * @todo Enable ability to generate graphs and store the audio for later playback...
             */
            //$DATE = date('YmdHis');
            //$DPATH = date('Y') . '/' . date('M') . '/' . date('d');
            //system('mkdir -p ./spectro/' . $DPATH);
            //system('mkdir -p ./voice/' . $DPATH);
            //system('/usr/local/sox/sox buffer.ogg -n spectrogram -x 300 -y 200 -z 100 -t $DATE.ogg -o ./spectro/' . $DPATH . '/' . $DATE . '.png');
            //system('/usr/local/sox/sox buffer.ogg normbuffer.ogg gain -n -2');
            //system('/usr/local/sox/sox normbuffer.ogg -n spectrogram -x 300 -y 200 -z 100 -t $DATE.norm.ogg -o ./spectro/' . $DPATH . '/' . $DATE . '.norm.png');
            //system('mv normbuffer.ogg ./voice/' . $DPATH . '/' . $DATE . '.ogg');
            //updateCli("Starting TX...");

            $playback = $this->executioner->setApplication('/usr/local/sox/play');
            $playback->addArgument('-q');
            $playback->addArgument('./voice/' . $DPATH . '/' . $DATE . '.ogg');
            $playback->addArgument('RC2103.wav');
            $playback->execute();
        }
    }

    private function mainCos()
    {
        $this->writeln('Running COS main loop!');
    }


}