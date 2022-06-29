<?php

namespace App\Http\Controllers;

use App\Services\ConfManagerService;
use App\Services\DTO\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    /**
     * Field to group mappings (to identify which panel (category) they should exist under)
     * @var array
     */
    private $fieldGroups = [

        'timezone' => Setting::GROUP_GENERAL,
        'callsign' => Setting::GROUP_GENERAL,
        'enabled' => Setting::GROUP_GENERAL,
        'transmit_mode' => Setting::GROUP_GENERAL,
        'transmit_timeout' => Setting::GROUP_GENERAL,
        'courtesy_tone' => Setting::GROUP_GENERAL,
        'auto_ident' => Setting::GROUP_GENERAL,
        'ident_use_custom' => Setting::GROUP_GENERAL,
        'ident_interval' => Setting::GROUP_GENERAL,
        'ident_time' => Setting::GROUP_GENERAL,
        'pl_tone' => Setting::GROUP_GENERAL,
        'delayed_playback_interval' => Setting::GROUP_GENERAL,
        'vox_tuning' => Setting::GROUP_GENERAL,

        //'ident_morse' => Setting::GROUP_GENERAL,

        //'record_device' => SettingEntity::GROUP_AUDIO,

        //'morse_wpm' => Setting::GROUP_MORSE,
        //'morse_frequency' => Setting::GROUP_MORSE,
        //'morse_output_volume' => Setting::GROUP_MORSE,

        'store_recordings' => Setting::GROUP_STORAGE,
        'purge_recording_after' => Setting::GROUP_STORAGE,

        'web_interface_enabled' => Setting::GROUP_WEBINTERFACE,
        'web_interface_port' => Setting::GROUP_WEBINTERFACE,
        'web_interface_bind_ip' => Setting::GROUP_WEBINTERFACE,
        'web_interface_logging' => Setting::GROUP_WEBINTERFACE,
        'web_gps_enabled' => Setting::GROUP_WEBINTERFACE,

        'tripwire_enabled' => Setting::GROUP_TRIPWIRE,
        'tripwire_url' => Setting::GROUP_TRIPWIRE,
        'tripwire_ignore_interval' => Setting::GROUP_TRIPWIRE,
        'tripwire_request_timeout' => Setting::GROUP_TRIPWIRE,

        'in_cor_pin' => Setting::GROUP_GPIO,
        'out_ptt_pin' => Setting::GROUP_GPIO,
        'out_ready_led_pin' => Setting::GROUP_GPIO,
        'out_rx_led_pin' => Setting::GROUP_GPIO,
        'out_tx_led_pin' => Setting::GROUP_GPIO,
        'cos_pin_invert' => Setting::GROUP_GPIO,
        'ptt_pin_invert' => Setting::GROUP_GPIO,
        'ready_pin_invert' => Setting::GROUP_GPIO,
        'rx_pin_invert' => Setting::GROUP_GPIO,
        'tx_pin_invert' => Setting::GROUP_GPIO,

        'archive_enabled' => Setting::GROUP_ARCHIVE,
        'ftp_host' => Setting::GROUP_ARCHIVE,
        'ftp_ssl' => Setting::GROUP_ARCHIVE,
        'ftp_port' => Setting::GROUP_ARCHIVE,
        'ftp_passive' => Setting::GROUP_ARCHIVE,
        'ftp_user' => Setting::GROUP_ARCHIVE,
        'ftp_pass' => Setting::GROUP_ARCHIVE,
        'ftp_path' => Setting::GROUP_ARCHIVE,
        'ftp_delete_on_success' => Setting::GROUP_ARCHIVE,
        'ftp_timeout' => Setting::GROUP_ARCHIVE,

        'tts_api_key' => Setting::GROUP_TTS,
        'tts_language' => Setting::GROUP_TTS,
        'tts_custom_ident' => Setting::GROUP_TTS,

        'owm_api_key' => Setting::GROUP_WX,
        'owm_enabled' => Setting::GROUP_WX,
        'owm_locale' => Setting::GROUP_WX,
        'owm_template' => Setting::GROUP_WX,

    ];

    /**
     * A list of fields that will be rendered as a checkbox.
     * @var array
     */
    private $booleanFields = [
        'enabled',
        'auto_ident',
        'ident_use_custom',
        'ident_time',
        'ident_morse',
        'store_recordings',
        'web_interface_enabled',
        'web_interface_logging',
        'web_gps_enabled',
        'tripwire_enabled',
        'rx_pin_invert',
        'tx_pin_invert',
        'cos_pin_invert',
        'ptt_pin_invert',
        'ready_pin_invert',
        'archive_enabled',
        'ftp_ssl',
        'ftp_passive',
        'ftp_delete_on_success',
        'owm_enabled',
    ];

    /**
     * Optionally override a label
     * @var array
     */
    private $labelOverrides = [
        'enabled' => 'Enable Repeater',
        'ident_use_custom' => 'Override default ident',
        'tripwire_enabled' => 'Enable Tripwire',
        'purge_recording_after' => 'Purge recordings after (days)',
        'web_interface_bind_ip' => 'Web Interface Bind IP',
        'web_gps_enabled' => 'Web GPS Data Enabled',
        'archive_enabled' => 'Enable auto archiving',
        'ftp_host' => 'FTP Host',
        'ftp_ssl' => 'Use SSL (FTPS)',
        'ftp_port' => 'FTP Port',
        'ftp_passive' => 'FTP Passive (PASV) mode',
        'ftp_user' => 'FTP Username',
        'ftp_pass' => 'FTP Password',
        'ftp_path' => 'FTP Path',
        'ftp_delete_on_success' => 'Delete local recording on successful upload',
        'ftp_timeout' => 'FTP Timeout',
        'tts_api_key' => 'Google API Key',
        'tts_language' => 'Language',
        'tts_custom_ident' => 'Custom Interval Message',
        'owm_api_key' => 'OpenWeatherMap API Key',
        'owm_enabled' => 'Enable Weather Reports',
        'owm_locale' => 'Location Name',
        'owm_template' => 'Text-To-Speech Template',
        'vox_tuning' => 'VOX Tuning'
    ];

    /**
     * Settings that should be ignored (not outputted to the settings screen)
     * @var array
     */
    private $ignoredSettings = [
        'ident_morse', // Future feature
        'record_device', // Disabling as this should ALWAYS be 'alsa' when running on a RPi
        'morse_wpm', // Future feature
        'morse_frequency', // Future feature
        'morse_output_volume', // Future feature
    ];

    private $fieldComments = [

        // General
        'timezone' => ['The timezone you wish to use for logging, TTS services, and the web interface (if enabled)'],
        'callsign' => [
            'Simplex repeater (ident) code',
            'This is phonetically transmitted if you enable the "Auto Ident" feature below.'
        ],
        'enabled' => [
            'Enable the "repeat" functionality.',
            'Optionally you can disable the repeater and therefore disabling transmission. This is',
            'useful if you wanted to record received transmissions eg. running Pirrot in surveillance mode.'
        ],
        'auto_ident' => [
            'Enable the "basic" station automatic identification?',
            'This will automatically broadcast the repeater call sign, PL tone etc. at the ident_interval period.',
        ],
        'ident_use_custom' => [
            'This feature will override the computer spoken "auto_ident" repeater ident message and will instead play a custom',
            'uploaded MP3 file of your choice, this file should be uploaded to: /opt/pirrot/storage/input/custom.mp3',
        ],
        'ident_interval' => [
            'When automatic identification is enabled, Pirrot will transmit the repeater identification every X seconds.',
            'If you want to disable all interval transmissions (station, custom message and weather), set this value to 0.',
            'The default value is "600" seconds (every 10 minutes).',
        ],
        'delayed_playback_interval' => [
            'You can optionally add a delay (in seconds) between the received transmission being re-transmitted by Pirrot.',
            'The default value is "0.2" (if set to "0" there will be no delay and therefore immediately repeat the transmission which might lead to cut audio recordings)',
        ],
        'vox_tuning' => [
            'If you are using VOX mode, you can optionally fine tune your VOX levels as you require independently of having to adjust your mic/line-in volume.',
            '**This is considered an advanced setting and one that you should experiment with if the default values do not work for your setup!**',
            'Default value: "1 0.5 5% 1 1.0 5%"  - In this example, wait until it hears activity above the threshold (5%) for half a second (0.5) then start recording, stop recording when audible activity falls below the threshold (5%) to zero for one second (1.0).',
        ],
        'courtesy_tone' => [
            'To disable courtesy tones set to: false',
            'Otherwise use the filename of the courtesy tone, eg. BeeBoo (without the .wav extension)'
        ],
        'pl_tone' => [
            'The PL/CTCSS to access the repeater',
            'Set to "false" if you do not have a CTCSS/PL code to access the repeater, otherwise set',
            'the CTCSS/PL tone here eg. "110.9" this will be "spoken" when the repeater transmits it\'s ident',
        ],
        'transmit_mode' => [
            'The repeater operation mode',
            '\'simplex-vox\' = Simplex Mode - Voice Operated (auto-record and then transmit when it "hears" mic input on the USB sound card.)',
            '\'simplex-cor\' = Simplex Mode - Carrier Operated Relay/Switch (record and then transmit when the COR/COS GPIO pin is ON (aka. "high")',
            '\'duplex-cor\' = Duplex Mode - Carrier Operated Relay/Switch (pass-through transmission when the COR/COS GPIO pin is ON (aka. "high")',
        ],
        'transmit_timeout' => [
            'Protects the transmitter by automatically timing out (effectively releasing the PTT) after the specified number of seconds',
            'Default recommended value is 120 seconds (2 minutes)',
        ],
        'ident_time' => [
            'Transmit the time with the ident message.',
        ],
        'ident_morse' => [
            'Send morse code with the ident (coming in the future!)',
        ],

        // Storage
        'store_recordings' => [
            'Enable saving of recordings. These can then be played or downloaded from the "Audio Recordings" section ',
            'of the Pirrot Web Interface.',
        ],
        'purge_recording_after' => [
            'Purge recording after (X days), 0 to disable purging of recordings.',
        ],

        // Web Interface
        'web_interface_enabled' => ['Enable the light-weight web interface'],
        'web_interface_port' => ['The TCP port to listen on'],
        'web_interface_bind_ip' => ['The IP address to bind to (default: 0.0.0.0)'],
        'web_interface_logging' => ['Enable logging of web server access logs to /var/log/pirrot-web.log'],
        'web_gps_enabled' => [
            'Enable GPS position and other data on the web dashboard view.',
            '* You MUST setup and configure the device and ensure that the GPS receiver is connected to the RaspberryPi.',
            '* Having this setting enabled but no device connected will cause the web interface to become unresponsive!',
        ],

        // Tripwire
        'tripwire_enabled' => ['Enable the tripwire feature (sends a web hook when transmission is received)'],
        'tripwire_url' => [
            'The URL to send the HTTP request payload to when the "tripwire" is activated (a transmission is received)',
            'eg. http://yourwebsite.com/my-tripwire-handler-endpoint'
        ],
        'tripwire_ignore_interval' => [
            'This value will ensure that further transmissions within this time period (in seconds) do not trigger',
            'additional HTTP web hook requests (default value is 300)'
        ],
        'tripwire_request_timeout' => ['HTTP request timeout (in seconds)'],

        // GPIO
        'in_cor_pin' => ['The GPIO input pin (BCM) number to use for the COS relay (required if running in COS mode).'],
        'out_ptt_pin' => ['The GPIO output pin (BCM) number to use for the PTT relay.'],
        'out_ready_led_pin' => ['The "Ready status" LED output pin (BCM) number'],
        'out_rx_led_pin' => ['The "RX" LED output pin (BCM) number'],
        'out_tx_led_pin' => ['The "TX" LED output pin (BCM) number'],
        'cos_pin_invert' => ['COS Pin is inverted?'],
        'ptt_pin_invert' => ['PTT Pin is inverted?'],
        'ready_pin_invert' => ['Ready LED pin is inverted?'],
        'rx_pin_invert' => ['RX (Recieve) LED pin is inverted?'],
        'tx_pin_invert' => ['TX (Transmit) LED pin is inverted?'],

        // Audio Archives
        'archive_enabled' => [
            'Enables audio recording archiving to a remote FTP server.',
            'This will automatically archive all recordings nightly to a remote FTP server.'
        ],
        'ftp_host' => ['The FTP server address (FQDN or IP address)'],
        'ftp_ssl' => ['If the server implements FTP over SSL (FTPS), you should enable this feature for added security.'],
        'ftp_port' => ['The FTP server port'],
        'ftp_passive' => ['Use "Passive" FTP (PASV) mode?'],
        'ftp_user' => ['The FTP account username.'],
        'ftp_pass' => ['The FTP account password.'],
        'ftp_path' => ['Remote server path (directory, folder etc) to upload the files to.'],
        'ftp_delete_on_success' => ['Delete the local recording if the upload to FTP was successful?'],
        'ftp_timeout' => ['The number of seconds before the FTP connection to the server should timeout.'],

        // Google Translate API service
        'tts_api_key' => ['Your API key for the Google Translate Service.'],
        'tts_language' => [
            'The language (ISO-639-1 code) used for the TTS output.',
            'A list of valid options can be found here: https://cloud.google.com/translate/docs/languages'
        ],
        'tts_custom_ident' => [
            'An optional custom message to be broadcast at the ident interval.',
            'This could be used as a replacement for the \'auto_ident\' feature allowing for a custom station ident',
            'alternatively could be used to broadcast useful news/messages.',
            'Set this to \'null\' to disable this feature.'
        ],

        // OpenWeatherMap API service.
        'owm_api_key' => [
            'Your OpenWeatherMap.org API key',
            'Don\'t have one? Register for free here: https://openweathermap.org/'
        ],
        'owm_enabled' => [
            'Broadcast the current weather at the station identification interval?',
            '* This feature requires a valid Google API key (set it above) for the Text-To-Speech functionality.'
        ],
        'owm_locale' => [
            'The location of your station or where you want the weather report taken from.',
            'For example: "London,UK". You can test location names on the OpenWeatherMap.org site if you\'re not sure.'
        ],
        'owm_template' => [
            'The "spoken" format for the weather broadcast',
            'A full list of all "placeholder" tags can be found here: https://pirrot.hallinet.com/weather-tags'
        ],

    ];

    /**
     * Renders the Settings page.
     * @return \Illuminate\View\View
     */
    public function showSettingsPage()
    {

        $configFilePath = dirname(__DIR__) . '/../../../build/configs/pirrot_default.conf';
        if (file_exists('/etc/pirrot.conf')) {
            $configFilePath = '/etc/pirrot.conf';
        }

        // Get setting values from the configuration file.
        $config = new ConfManagerService($configFilePath);
        $configValues = $config->read();

        // Regex out the setting values and comments to provide a list of settings that we can render out.
        foreach ($this->fieldGroups as $field => $group) {

            if (!key_exists($field, $this->labelOverrides)) {
                $label = ucwords(str_replace('_', ' ', $field));
            } else {
                $label = $this->labelOverrides[$field];
            }

            // Get the value from the settings file...
            $value = $configValues[$field];

            $inputType = Setting::TYPE_TEXT;
            if (in_array($field, $this->booleanFields)) {
                $inputType = Setting::TYPE_BOOL;
            }

            $inputComments = null;
            if (key_exists($field, $this->fieldComments)) {
                $inputComments = $this->fieldComments[$field];
            }

            $panelInputs[$group][] = new Setting($field, $label, $group, $value, $inputType, $inputComments);
        }


        return view('_pages.settings')->with('panels', $panelInputs);
    }

    /**
     * Handles the updating of the settings and restarts the Pirrot daemon.
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function updateSettings(Request $request)
    {

        $configFilePath = dirname(__DIR__) . '/../../../build/configs/pirrot_default.conf';
        if (file_exists('/etc/pirrot.conf')) {
            $configFilePath = '/etc/pirrot.conf';
        }

        // Get setting values from the configuration file.
        $config = new ConfManagerService($configFilePath);
        $currentSettings = $config->read();
        $updateSettings = $request->json();
        $newSettings = [];
        foreach ($updateSettings as $setting) {
            $newSettings[$setting['name']] = $setting['value'];
        }

        // Set all "boolean" type config items to "false" if the checkbox is not checked.
        $falseBooleanValues = array_diff_key($currentSettings, $newSettings);
        foreach ($falseBooleanValues as $key => $value) {
            // Ignore settings that are on the "blacklist"/ignored list (to prevent them being overwritten with "false")
            if (!in_array($key, $this->ignoredSettings)) {
                $newSettings[$key] = "false"; // Yes, really set this to a string and NOT a boolean type (as we're witting it to a text file)
            }
        }
        $updatedConfig = $config->update($newSettings);

        // Get the current request URL so we can manipulate it for the auto-refresh after the service has been restarted.
        $url = parse_url(request()->root());
        $response =
            [
                'check_url' => $url['scheme'] . "://" . $url['host'] . ':' . $newSettings['web_interface_port'] . '/up',
                'after_url' => $url['scheme'] . "://" . $url['host'] . ':' . $newSettings['web_interface_port'] . '/settings',
            ];

        // We will only write the new configuration file and attempt to restart the Pirrot daemon ONLY if it's actually running on a RPi.
        if (env('APP_ENV') !== 'production') {
            $response =
                [
                    'check_url' => request()->root() . '/up',
                    'after_url' => request()->root() . '/settings',
                ];
            return response($response, 200);
        }

        // Backup the old configuration file and then write the new file...
        system("cp " . $configFilePath . " /opt/pirrot/storage/backups/pirrot-" . date("dmYHis") . ".conf");
        file_put_contents('/etc/pirrot.conf', $updatedConfig);

        // Trigger a daemon restart (after two seconds to give us enough time to respond to the HTTP request)
        system('sudo /opt/pirrot/web/resources/scripts/restart-pirrot.sh > /dev/null &');

        return response($response, 200);
    }

}
