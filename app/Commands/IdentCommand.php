<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\Exceptions\GPIOException;
use Ballen\GPIO\GPIO;
use Ballen\Pirrot\Services\TextToSpeechService;
use Ballen\Pirrot\Services\WeatherService;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class IdentCommand extends AudioCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    const TTS_FILE_PATH = '';

    private $isBooted = false;

    /**
     * IdentCommand constructor.
     * @param ArgumentsParser $argv
     * @throws GPIOException
     */
    public function __construct(ArgumentsParser $argv)
    {
        parent::__construct($argv);
    }

    /**
     * Handle the command.
     * @return void
     * @throws GPIOException
     */
    public function handle()
    {

        $this->setPowerLed();

        // Detect if the repeater 'ident' is enabled/disabled...
        if (!$this->config->get('auto_ident')) {
            $this->exitWithSuccess();
        }

        $this->setProcessName('pirrot-beacon');

        while (true) {

            // Delay to ensure IO is not confused at daemon start (due to Voice daemon starting too)
            if (!$this->isBooted) {
                sleep(2);
                $this->setPowerLed();
                $this->isBooted = true;
            }

            $this->outputPtt->setValue(GPIO::HIGH);
            $this->outputLedTx->setValue(GPIO::HIGH);

            $this->announceBasicIdent();
            $this->announceCustomMessage($this->config->get('tts_ident_custom', ''));
            $this->announceWeather();

            $this->outputPtt->setValue(GPIO::LOW);
            $this->outputLedTx->setValue(GPIO::LOW);

            sleep($this->config->get('ident_interval'));
        }
    }

    /**
     * Announces the basic station (repeater) identification.
     * Perfect for offline mode as it does not require Google TTS but is restricted to english spoken identification.
     * @return void
     */
    public function announceBasicIdent()
    {
        $this->audioService->ident(
            $this->config->get('callsign'),
            $this->config->get('pl_tone', null),
            $this->config->get('ident_time'),
            $this->config->get('ident_morse')
        );
    }

    /**
     * Announces a custom (translatable) repeater/station identification or other message.
     * This could obviously be used for a custom broadcast message (if you didn't care about repeater identification) too!
     * @param string $message The message that should be TTS converted and broadcast.
     */
    public function announceCustomMessage($message)
    {

    }

    /**
     * Announces the current weather conditions
     * @return void
     */
    public function announceWeather()
    {

        if (!$this->config->get('owm_enabled', false)) {
            return;
        }

        $weatherService = new WeatherService($this->config->get('owm_api_key'));
        $weatherService->fromLocationName($this->config->get('owm_locale'));

        $ttsService = new TextToSpeechService($this->config->get('tts_api_key'));
        $ttsService->setLanguage($this->config->get('tts_language', 'en'));

        // Add it to the SQLite database (so can see previous weather reports)
        // @todo Will add this later!!
        // $weatherService->toObject();

        // TTS it
        $report = $weatherService->toFormattedString($this->config->get('owm_template'));
        $filename = md5($report); // We'll MD5 the formatted string, if the file already exists, we'll play that instead of making another API request to Google ;)

        if(file_exists($filename)){
            // Let's play this file and return!!
            return;
        }

        $output = $ttsService->download($report);
        file_put_contents($filename,$output);

        // Play the file..

    }


}