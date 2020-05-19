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
        'courtesy_tone' => Setting::GROUP_GENERAL,
        'auto_ident' => Setting::GROUP_GENERAL,
        'ident_interval' => Setting::GROUP_GENERAL,
        'delayed_playback_interval' => Setting::GROUP_GENERAL,
        'pl_tone' => Setting::GROUP_GENERAL,
        'transmit_mode' => Setting::GROUP_GENERAL,
        'ident_time' => Setting::GROUP_GENERAL,
        'ident_morse' => Setting::GROUP_GENERAL,

        //'record_device' => SettingEntity::GROUP_AUDIO,

        'morse_wpm' => Setting::GROUP_MORSE,
        'morse_frequency' => Setting::GROUP_MORSE,
        'morse_output_volume' => Setting::GROUP_MORSE,

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

    ];

    /**
     * A list of fields that will be rendered as a checkbox.
     * @var array
     */
    private $booleanFields = [
        'enabled',
        'auto_ident',
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
    ];

    /**
     * Optionally override a label
     * @var array
     */
    private $labelOverrides = [
        'enabled' => 'Enable Repeater',
        'tripwire_enabled' => 'Enable Tripwire',
        'web_gps_enabled' => 'Web GPS Data Enabled'
    ];

    private $fieldComments = [
        'timezone' => ['The timezone you wish to use for logging, TTS services, and the web interface (if enabled)'],
        'enabled' => [
            'Enable the "repeat" functionality.',
            'Optionally you can disable the repeater thus not "repeating" the communications received.'
        ],
        'courtesy_tone' => [
            'To disable courtesy tones set to: false',
            'Otherwise use the filename of the courtesy tone, eg. BeeBoo (without the .wav extension)'
        ],
        'web_gps_enabled' => [
            'Enable GPS position and other data on the web dashboard view.',
            '** You MUST setup and configure the device and ensure that the GPS receiver is connected to the RaspberryPi.',
            '** Having this setting enabled but no device connected will cause the web interface to become unresponsive!'
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
            $newSettings[$key] = "false";
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
