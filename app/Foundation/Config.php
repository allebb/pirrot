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
     * @return array
     */
    private function loadDefault()
    {
        return parse_ini_file();
    }

    /**
     * Loads and merges the user configuration.
     *
     * @param $config
     * @return arrayx
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