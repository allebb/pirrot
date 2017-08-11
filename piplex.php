#!/usr/bin/env php
<?php
use Ballen\Clip\Utilities\CommandRouter;
use Ballen\Clip\Exceptions\CommandNotFoundException;
use Ballen\Piplex\Commands\DaemonCommand;
use Ballen\Piplex\Commands\UpdateCommand;

// Set the current directory of the CLI script.
$bindir = dirname(__FILE__);

// Initiate the Composer autoloader.
require_once $bindir . '/vendor/autoload.php';

$app = new CommandRouter($argv);

// Add our commands and their handler class mappings
$app->add('daemon', DaemonCommand::class);
$app->add('update', UpdateCommand::class);
$app->add('help', HelpCommand::class);
try {
    $app->dispatch();
} catch (CommandNotFoundException $exception) {
    // If the requested command is not found we'll display the 'help' text by default?
    $app->dispatch('help');
}