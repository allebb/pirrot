<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;

/**
 * Class VoiceCommand
 *
 * @package Ballen\Piplex\Commands
 */
class VoiceCommand extends AudioBaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * VoiceCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        // Split the config setting on '_' and then check a class exists...
    }


}