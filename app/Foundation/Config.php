<?php

namespace Ballen\Piplex\Foundation;

/**
 * Class Config
 */
class Config
{

    /**
     * Stores the parsed software configuration variables.
     *
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = array_merge(
            //$this->loadDefault(),
            $this->loadCustom($config)
        );
    }

    /**
     * Loads the default (shipped) configuration.
     *
     * @param $config The path to the configuration file.
     * @return array
     */
    private function loadDefault($config)
    {
        return parse_ini_file($config);
    }

    /**
     * Loads and merges the user configuration.
     *
     * @param $config The path to the configuration file.
     * @return array
     */
    private function loadCustom($config)
    {
        return parse_ini_file($config);
    }

    /**
     *  Return a configuration option.
     *
     * @param $key The configuration option key name.
     * @param mixed $default The default value to return if not set.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!isset($this->config[$key])) {
            return $default;
        }
        return $this->config[$key];
    }
}