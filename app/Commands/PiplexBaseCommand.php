<?php

namespace Ballen\Piplex\Commands;

use Ballen\Clip\ConsoleApplication;
use Ballen\Clip\Utilities\ArgumentsParser;

class PiplexBaseCommand extends ConsoleApplication
{
    public $config = [];

    public function __construct(ArgumentsParser $argv)
    {
        $this->retrieveConfiguration();
        parent::__construct($argv);
    }

    private function retrieveConfiguration()
    {
        // To be merged with the user defined one (living in /etc/piplex.conf) later!!
        $this->config = parse_ini_file('build/configs/piplex_default.conf');
    }
}