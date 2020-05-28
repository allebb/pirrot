<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Collection\Collection;
use Ballen\Pirrot\Services\VersionCheckService;

/**
 * Class PurgeCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class PurgeCommand extends BaseCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    /**
     * IdentCommand constructor.
     * @param ArgumentsParser $argv
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     * @return void
     */
    public function handle()
    {

        // Check for new versions...
        $vcs = new VersionCheckService();
        if ($version = $vcs->getLatestVersion()) { // Only update the cache file if we was able to contact the version checker service!
            file_put_contents($this->basePath . '/storage/version.cache', $vcs->getLatestVersion());
        }

        // Purge recordings and general cleanup tasks.
        $purge_after_days = $this->config->get('purge_recording_after', 0);

        if ($purge_after_days < 1) {
            $this->writeln($this->getCurrentLogTimestamp() . 'Purging of recordings is disabled in the configuration, exiting!');
            $this->exitWithSuccess();
        }

        $recordings_storage_path = $this->basePath . '/storage/recordings/';
        $purge_after_timestamp = time() - (86400 * $purge_after_days);

        // Get a list of recordings that should be checked for age...
        $files_to_check = new Collection();
        $recordings_in_directory = array_diff(scandir($recordings_storage_path), array('.', '..'));

        // Create a new File object from each file and add to our file collection.
        foreach ($recordings_in_directory as $file) {
            $files_to_check->push(new \SplFileInfo($recordings_storage_path . $file));
        }

        // Check each file to see if the created date is greater than the purge_after_days value.
        $total_purged = 0;
        foreach ($files_to_check->all()->toArray() as $file) {
            if ($file->getMTime() < $purge_after_timestamp) {
                unlink($file->getRealPath());
                $total_purged++;
            }
        }

        $this->writeln($this->getCurrentLogTimestamp() . 'Recordings purge task deleted ' . $total_purged . ' files.');

    }


}