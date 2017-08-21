<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
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
     * VoiceCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);

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
        $this->writeln('Running VOX main loop!');
    }

    private function mainCos()
    {
        $this->writeln('Running COS main loop!');
    }


}