<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Executioner\Exceptions\ExecutionException;
use Ballen\Executioner\Executioner;
use Ballen\Pirrot\Services\AudioService;

/**
 * Class AudioBaseCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class AudioBaseCommand extends PirrotBaseCommand
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
     * AudioBaseCommand constructor.
     *
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {

        parent::__construct($argv);

        $this->detectExternalBinaries(['sox', 'play']);

        $this->audioService = new AudioService();
        $this->audioService->soundPath = $this->basePath . '/resources/sound/';
        //$this->audioService->audioPlayerBin = '/usr/local/sox/play -q';
        //$this->audioService->audioRecordBin = '/usr/local/sox/sox -q';
        $this->audioService->audioPlayerBin = $this->binPaths['play'] . ' -q';
        $this->audioService->audioRecordBin = $this->binPaths['sox'] . ' -q';
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

}