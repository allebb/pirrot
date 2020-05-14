<?php


namespace App\Services;


use Illuminate\Support\Collection;

class SettingsManifest
{
    /**
     * An array of Setting Entity objects.
     * @var Collection<SettingEntity>
     */
    public $settings = null;

    public function __construct()
    {
        $this->settings = new Collection();
    }

    /**
     * Adds a new setting to the Settings Manifest.
     * @param SettingEntity $entity
     */
    public function add(SettingEntity $entity)
    {
        $this->settings->add($entity);
    }

    /**
     * Returns a data structured list of settings grouped by their setting group.
     */
    public function getAllSettingsByGroup()
    {

        $sorted = [];



    }

    /**
     * Merges the new system setting values with the provided configuration file data.
     * @param string $config The contents of the existing configuration file.
     * @param array $values A key=>value array containing the setting key and new setting value.
     * @return string
     */
    public function generateConfigurationFile(string $config, array $values)
    {
        foreach ($values as $key => $value) {
            // Find and replace values with the
        }
        return $config;
    }


}
