<?php


namespace App\Services;


class SettingEntity
{

    const GROUP_GENERAL = 'general';
    const GROUP_AUDIO = 'audio';
    const GROUP_MORSE = 'morse';
    const GROUP_STORAGE = 'storage';
    const GROUP_WEBINTERFACE = 'web-interface';
    const GROUP_GPIO = 'gpio';

    const TYPE_TEXT = 'text';
    const TYPE_BOOL = 'bool';


    /**
     * The setting group that it belongs to (use SettingEntity::GROUP_XXXXX)..
     * @var string
     */
    public $group = self::GROUP_GENERAL;

    /**
     * The setting name (key) as it appears in the /etc/pirrot.conf file.
     * @var string
     */
    public $name = null;

    /**
     * The setting value
     * @var string
     */
    public $value = null;

    /**
     * The input type used to display the setting information in the web settings panel (use SettingEntity::TYPE_XXXXX).
     * @var string
     */
    public $inputType = self::TYPE_TEXT;

    /**
     * Comment lines that appear above the setting in the /etc/pirrot.conf file.
     * @var array
     */
    public $commentLines = [];

    /**
     * SettingEntity constructor.
     * @param string $name
     */
    public function __construct(string $name, string $group)
    {
        $this->name = $name;
        $this->group = $group;
    }

}
