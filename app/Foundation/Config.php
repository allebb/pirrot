<?php

namespace Ballen\Pirrot\Foundation;

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
     * @param $config The user/working configuration file (eg. /etc/myapp.conf)
     * @param null $defaultConfig Optional 'default' configuration to merge with.
     */
    public function __construct($config, $defaultConfig = null)
    {

        $this->config = $this->parseConfig($config);
        if ($defaultConfig) {
            $this->config = array_merge(
                $this->parseConfig($defaultConfig),
                $this->parseConfig($config)
            );
        }

    }

    /**
     * Loads a configuration file.
     *
     * @param $config The path to the configuration file.
     * @return array
     */
    private function parseConfig($config)
    {
        $parsed = [];
        $configArray = parse_ini_file($config);
        foreach ($configArray as $key => $value) {
            $parsed[$key] = $value;
            if (is_integer($value)) {
                $parsed[$key] = (int)$value;
            }
        }
        return $parsed;
    }

    /**
     *  Return a configuration option.
     *
     * @param $key The configuration option key name.
     * @param mixed $default The default value to return if not set.
     * @return mixed
     */
    public
    function get($key,
        $default = null
    ) {
        if (!isset($this->config[$key])) {
            return $default;
        }
        return $this->config[$key];
    }

    /**
     * Returns the in-memory configuration array.
     *
     * @return array
     */
    public
    function all()
    {
        return $this->config;
    }

    /**
     * Magic method for accessing configuration items as a property.
     *
     * @param $name The configuration property name.
     * @return mixed|null
     */
    public
    function __get($name
    ) {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }
}