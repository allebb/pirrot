<?php

/**
 * A signal handler to cleanup IO and other Pirrot processes.
 */
function ioCleanup()
{
    file_put_contents('/tmp/testing.txt', date('c') . PHP_EOL, FILE_APPEND);
    exit(0);
}