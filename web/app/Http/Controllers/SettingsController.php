<?php

namespace App\Http\Controllers;

use App\Services\SettingEntity;
use App\Services\SettingsManifest;
use App\Services\StatsService;
use Hamcrest\Core\Set;

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

    public function showSettingsPage()
    {

        // Read the current settings file
        $settings = (object)parse_ini_file(dirname(__DIR__) . '/../../../build/configs/pirrot_default.conf');
        if (file_exists($config = '/etc/pirrot.conf')) {
            $settings = (object)parse_ini_file($config);
        }

        // Regex out the setting values and comments to provide a list of settings that we can render out.


        return view('_pages.settings');
    }

}
