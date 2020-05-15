<?php

namespace App\Http\Controllers;

use App\Services\ConfManagerService;
use App\Services\SettingEntity;
use App\Services\SettingsManifest;
use App\Services\StatsService;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    /**
     * Field to group mappings (to identify which panel (category) they should exist under)
     * @var array
     */
    private $fieldGroups = [

        'timezone' => SettingEntity::GROUP_GENERAL,
        'callsign' => SettingEntity::GROUP_GENERAL,
        'enabled' => SettingEntity::GROUP_GENERAL,
        'courtesy_tone' => SettingEntity::GROUP_GENERAL,
        'auto_ident' => SettingEntity::GROUP_GENERAL,
        'ident_interval' => SettingEntity::GROUP_GENERAL,
        'delayed_playback_interval' => SettingEntity::GROUP_GENERAL,
        'pl_tone' => SettingEntity::GROUP_GENERAL,
        'transmit_mode' => SettingEntity::GROUP_GENERAL,
        'ident_time' => SettingEntity::GROUP_GENERAL,
        'ident_morse' => SettingEntity::GROUP_GENERAL,

        //'record_device' => SettingEntity::GROUP_AUDIO,

        'morse_wpm' => SettingEntity::GROUP_MORSE,
        'morse_frequency' => SettingEntity::GROUP_MORSE,
        'morse_output_volume' => SettingEntity::GROUP_MORSE,

        'store_recordings' => SettingEntity::GROUP_STORAGE,
        'purge_recording_after' => SettingEntity::GROUP_STORAGE,

        'web_interface_enabled' => SettingEntity::GROUP_WEBINTERFACE,
        'web_interface_port' => SettingEntity::GROUP_WEBINTERFACE,
        'web_interface_bind_ip' => SettingEntity::GROUP_WEBINTERFACE,
        'web_interface_logging' => SettingEntity::GROUP_WEBINTERFACE,

        'in_cor_pin' => SettingEntity::GROUP_GPIO,
        'out_ptt_pin' => SettingEntity::GROUP_GPIO,
        'out_ready_led_pin' => SettingEntity::GROUP_GPIO,
        'out_rx_led_pin' => SettingEntity::GROUP_GPIO,
        'out_tx_led_pin' => SettingEntity::GROUP_GPIO,
        'cos_pin_invert' => SettingEntity::GROUP_GPIO,
        'ptt_pin_invert' => SettingEntity::GROUP_GPIO,
        'ready_pin_invert' => SettingEntity::GROUP_GPIO,
        'rx_pin_invert' => SettingEntity::GROUP_GPIO,
        'tx_pin_invert' => SettingEntity::GROUP_GPIO,

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
        'enabled' => 'Enable Repeater'
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

            $inputType = SettingEntity::TYPE_TEXT;
            if (in_array($field, $this->booleanFields)) {
                $inputType = SettingEntity::TYPE_BOOL;
            }

            $inputComments = null;
            if (key_exists($field, $this->fieldComments)) {
                $inputComments = $this->fieldComments[$field];
            }

            $panelInputs[$group][] = new SettingEntity($field, $label, $group, $value, $inputType, $inputComments);
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
        $updatedConfig = $config->update(array_merge($currentSettings, $newSettings));

        // We will only write the new configuration file and attempt to restart the Pirrot daemon ONLY if it's actually running on a RPi.
        if (env('APP_ENV') != 'production') {
            sleep(15); // Sleep for a while to simulate the service restarting (in a local development environment)
            return response('', 200);
        }

        // Backup the old configuration file and then write the new file...
        system("cp " . $configFilePath . " /opt/pirrot/storage/backups/pirrot-" . date("dmYHis") . ".conf");
        file_put_contents('/etc/pirrot.conf', $updatedConfig);

        // Trigger a daemon restart
        system('sudo /opt/pirrot/web/resources/restart-pirrot.sh > /dev/null &');

        return response('', 200);
    }

}
