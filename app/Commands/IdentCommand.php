<?php

namespace Ballen\Pirrot\Commands;

use Ballen\Clip\Interfaces\CommandInterface;
use Ballen\Clip\Traits\RecievesArgumentsTrait;
use Ballen\Clip\Utilities\ArgumentsParser;
use Ballen\GPIO\Exceptions\GPIOException;
use Ballen\GPIO\GPIO;
use Ballen\Pirrot\Services\TextToSpeechService;
use Ballen\Pirrot\Services\WeatherService;
use Mockery\Exception;

/**
 * Class DaemonCommand
 *
 * @package Ballen\Pirrot\Commands
 */
class IdentCommand extends AudioCommand implements CommandInterface
{

    use RecievesArgumentsTrait;

    const TTS_FILE_PATH = '/storage/tts/';

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

        $this->setProcessName('pirrot-beacon');

        // Do we need to 'key up'?
        $transmit = false;
        if ($broadcastBasicIdent = (!in_array($this->config->get('auto_ident'), [false, 'false']))) {
            $transmit = true;
        }
        if ($broadcastCustomTts = !in_array($this->config->get('tts_custom_ident', ''), [null, 'null', ''])) {
            $transmit = true;
        }
        if ($broadcastWeather = !in_array($this->config->get('owm_enabled', false), [null, 'null', ''])) {
            $transmit = true;
        }

        // If we have nothing to transmit (on an interval) then we'll exit the process (making sure the power LED is on!!)
        if (!$this->config->get('enabled') || !$transmit) {
            sleep(2);
            $this->setPowerLed();
            $this->exitWithSuccess();
        }

        $customTtsMessage = $this->config->get('tts_ident_custom', '');
        $loopInterval = $this->config->get('ident_interval');

        while (true) {

            // Delay to ensure IO is not confused at daemon start (due to Voice daemon starting too)
            if (!$this->isBooted) {
                sleep(2);
                $this->setPowerLed();
                $this->isBooted = true;
            }

            $this->outputPtt->setValue(GPIO::HIGH);
            $this->outputLedTx->setValue(GPIO::HIGH);

            if ($broadcastBasicIdent) {
                if (!$this->config->get('ident_use_custom')) {
                    $this->announceBasicIdent();
                } else {
                    $this->announceCustomRecording();
                }
            }

            if ($broadcastCustomTts) {
                try {
                    $this->announceCustomTtsMessage($customTtsMessage);
                } catch (\Exception $exception) {
                    $this->writeln($this->getCurrentLogTimestamp() . ' Exception thrown for custom TTS message [' . $exception->getMessage() . ']');
                }
            }

            if ($broadcastWeather) {
                try {
                    $this->announceWeather();
                } catch (\Exception $exception) {
                    $this->writeln($this->getCurrentLogTimestamp() . ' Exception thrown for weather announcement [' . $exception->getMessage() . ']');
                }
            }

            $this->outputPtt->setValue(GPIO::LOW);
            $this->outputLedTx->setValue(GPIO::LOW);

            sleep($loopInterval);
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
    public function announceCustomTtsMessage($message)
    {

        $ttsService = new TextToSpeechService($this->config->get('tts_api_key'));
        $ttsService->setLanguage($this->config->get('tts_language', 'en'));

        $message = $this->config->get('tts_custom_ident');
        $filename = $this->basePath . self::TTS_FILE_PATH . 'cm_' . md5($message) . '.mp3';

        if (file_exists($filename)) {
            $this->audioService->playMp3($filename);
            return;
        }

        $output = $ttsService->download($message);
        file_put_contents($filename, $output);

        $this->audioService->playMp3($filename);

    }

    /**
     * Announces a custom pre-recorded repeater/station identification or other message.
     * This could obviously be used for a custom broadcast message (if you didn't care about repeater identification) too!
     * This will play the custom file uploaded to /opt/pirrot/storage/input/custom.mp3
     */
    public function announceCustomRecording()
    {

        $filename = $this->basePath . '/storage/input/custom.mp3';
        if (file_exists($filename)) {
            $this->audioService->playMp3($filename);
            return;
        }

        $this->writeln($this->getCurrentLogTimestamp() . 'Custom recording file was not found at: ' . $filename);

    }

    /**
     * Announces the current weather conditions
     * @return void
     */
    public function announceWeather()
    {

        $weatherService = new WeatherService($this->config->get('owm_api_key'));
        $weatherService->fromLocationName($this->config->get('owm_locale'));

        $ttsService = new TextToSpeechService($this->config->get('tts_api_key'));
        $ttsService->setLanguage($this->config->get('tts_language', 'en'));

        // TTS it
        $report = $weatherService->toFormattedString($this->config->get('owm_template'));
        $filename = $this->basePath . self::TTS_FILE_PATH . 'wx_' . md5($report) . '.mp3'; // We'll MD5 the formatted string, if the file already exists, we'll play that instead of making another API request to Google ;)

        // Store the weather report data to the Pirrot SQLite database.
        $dbPath = $this->basePath . '/web/database/database.sqlite';
        if (file_exists('/opt/pirrot/storage/pirrot-web.database')) {
            $dbPath = '/opt/pirrot/storage/pirrot-web.database';
        }
        try {
            $weatherService->toSqliteDatabase($dbPath, 'weather_reports');
        } catch (\Exception $exception) {
            // We will ensure that any errors saving data to the database does not affect the broadcast!
            $this->writeln($this->getCurrentLogTimestamp() . 'Unable to write weather information to the Pirrot SQLite database.');
        }

        if (file_exists($filename)) {
            $this->audioService->playMp3($filename);
            return;
        }

        $output = $ttsService->download($report);
        file_put_contents($filename, $output);

        $this->audioService->playMp3($filename);

    }


}