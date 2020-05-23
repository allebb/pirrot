<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\Collection\Collection;

/**
 * Class ArchiveCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class ArchiveCommand extends BaseCommand implements CommandInterface
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
        if (!$this->config->get('archive_enabled', false)) {
            $this->writeln('The archive recording setting is not enabled, exiting!');
            $this->exitWithSuccess();
        }

        $recordingsStoragePath = $this->basePath . '/resources/storage/recordings/';
        $recordingsStoragePath = '/Volumes/DataSSD/Users/ballen/Desktop/recordings/';

        // Get a list of recording to upload...
        $filesToArchive = new Collection();
        $recordingsInDirectory = array_diff(scandir($recordingsStoragePath), array('.', '..'));

        // Create a new File object from each file and add to our file collection.
        foreach ($recordingsInDirectory as $file) {
            $filesToArchive->push(new \SplFileInfo($recordingsStoragePath . $file));
        }

        if (count($recordingsInDirectory) < 1) {
            $this->writeln('No recordings found, exiting!');
        }

        // Resolve FTP server details from the Pirrot configuration file.
        $ftpHost = $this->config->get('ftp_host');
        $ftpUser = $this->config->get('ftp_user');
        $ftpPass = $this->config->get('ftp_pass');
        $ftpPath = $this->config->get('ftp_path');
        $deleteLocal = $this->config->get('ftp_delete_on_success');

        if (!$connection = ftp_connect($ftpHost)) {
            $this->writeln('Unable to connect to the FTP (recording archive) server at: ' . $ftpHost);
            $this->exitWithError();
        }

        if (!$session = ftp_login($connection, $ftpUser, $ftpPass)) {
            $this->writeln('Invalid user credentials provided, check username and password and try again!');
            $this->exitWithError();
        }

        // Attempt to upload (and delete locally, if set) each of the audio recordings found on disk.
        foreach ($filesToArchive as $file) {

            var_dump($file); die();
            if (!ftp_put($connection, $ftpPath . $file->filename, $recordingsStoragePath . $file->filename, FTP_BINARY)) {
                $this->writeln('An error occurred attempting to upload the file: ' . $recordingsStoragePath . $file->filename);
            }
            if($deleteLocal){
                unlink($recordingsStoragePath . $file->filename);
            }

        }

        ftp_close($connection);

    }


}