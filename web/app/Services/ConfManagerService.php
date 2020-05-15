<?php


namespace App\Services;


class ConfManagerService
{

    private $configPath;

    private $configurationContents;

    private $configSettingsLines;

    private $configSettingsKeys;

    private $configSettingsValues;

    private $readSettings = [];

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
        $this->configurationContents = file_get_contents($configPath);
    }

    /**
     * Reads and returns an array of settings and values in the configuration file.
     * @return array
     */
    public function read()
    {

        preg_match_all('/(.*)\s?=\s?(.*)/', $this->configurationContents, $output_array);

        $this->configSettingsLines = $output_array[0];
        $this->configSettingsKeys = $output_array[1];
        $this->configSettingsValues = $output_array[2];

        foreach ($this->configSettingsKeys as $index => $line) {
            $line = trim($line);
            if(substr($line,0,1) == ';'){
                continue;
            }
            $this->readSettings[$line] = $this->configSettingsValues[$index];
        }

        return $this->readSettings;
    }


    /**
     * Merges configuration file updates into the original file contents and returns the file as a string.
     * @param array $updates
     * @return false|string
     */
    public function update(array $updates)
    {
        $fileLines = explode(PHP_EOL, $this->configurationContents);

        // Lets now try to update it from our form array:
        // $updates = [
        //   'enabled' => 'false',
        //   'pl_tone' => '110.9',
        // ];

        $newConfig = $this->configurationContents;

        // Check each line if there is an update to be applied and if so,
        foreach ($fileLines as $index => $line) {
            foreach ($updates as $name => $value) {
                if (substr($line, 0, strlen($name)) == $name) {
                    $fileLines[$index] = str_replace($line, "{$name} = {$value}", $line);
                }
            }
        }

        return $newConfig;
    }
}
