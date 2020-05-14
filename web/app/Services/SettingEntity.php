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
     * Human friendly label for the web interface.
     * @var null
     */
    public $label = null;

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
     * @param string $label
     * @param string $group
     * @param null $value
     * @param string $inputType
     * @param array $commentLines
     */
    public function __construct(
        string $name,
        string $label,
        string $group,
        $value = null,
        $inputType = self::TYPE_TEXT,
        $commentLines = []
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->group = $group;
        $this->value = $value;
        $this->inputType = $inputType;
        $this->commentLines = $commentLines;
    }

}
