<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Interfaces\CommandInterface;

/**
 * Class TripwireCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class TripwireCommand extends BaseCommand implements CommandInterface
{
    use RecievesArgumentsTrait;

    protected const INTERVAL_CACHE_FILE = '/storage/tripwire.cache';

    /**
     * Handle the command.
     */
    public function handle()
    {
        $url = $this->arguments()->getOption('url', null);

        if (!$url) {
            $this->writeln($this->getCurrentLogTimestamp() . 'No --url parameter set, exiting!');
            return $this->exitWithError();
        }

        if (!$this->checkIntervalHasPassed($this->config->get('tripwire_ignore_interval', 300))) {
            $this->exitWithSuccess(); // Interval has not passed, don't send another HTTP request... yet!
        }

        $this->writeln($this->getCurrentLogTimestamp() . 'Sending tripwire hook to: ' . $url);
        $this->sendHttpWebhookRequest($this->config->get('tripwire_url', null), $this->config->get('tripwire_request_timeout', 30));

        touch($this->basePath . self::INTERVAL_CACHE_FILE);
        $this->exitWithSuccess();
    }

    /**
     * Checks if the tripwire request TTL has passed (indicating we should now initiate a new web hook request)
     * @param int $ttl The number of seconds that define the request interval.
     * @return bool
     */
    private function checkIntervalHasPassed($ttl): bool
    {
        if (!file_exists($this->basePath . self::INTERVAL_CACHE_FILE)) {
            return true;
        }
        if (filemtime($this->basePath . self::INTERVAL_CACHE_FILE) > (time() - $ttl)) {
            return false;
        }
        return true;
    }

    /**
     * Send the HTTP payload to the "Tripwire" endpoint.
     * @param string $url The URL to send the JSON payload to.
     * @param int $timeout The connection timeout value.
     * @return bool
     */
    private function sendHttpWebhookRequest($url, $timeout = 10): bool
    {
        $ch = curl_init($url);
        $payload = json_encode([
            'action' => 'tripwire.notify',
            'hostname' => gethostname(),
            'timestamp' => date('c'),
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout + 2);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) {
            return true;
        }
        return false;
    }
}