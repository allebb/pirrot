<?php


namespace Ballen\Pirrot\Services;


class WeatherService
{

    /**
     * The OpenWeatherMap API key
     * @var string
     */
    private $apiKey;

    /**
     * The API response body.
     * @var string
     */
    protected $response = '';

    /**
     * TextToSpeechService constructor.
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Converts degrees to compass cardinal heading.
     * @param $degrees
     * @return mixed
     */
    protected static function degreesToCardinals($degrees)
    {
        $caridnals = [
            "North",
            "North East",
            "East",
            "South East",
            "South",
            "South West",
            "West",
            "North West",
            "North"
        ];
        return $caridnals[(int)round(($degrees % 360) / 45)];
    }

    /**
     * Download weather data for a location name.
     * Eg. "Ipswich,UK", "New York City".
     * @param $location
     * @return WeatherService
     */
    public function fromLocationName($location)
    {
        $this->response = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q={$location}}&units=metric&appid={$this->apiKey}");
        return $this;
    }

    /**
     * Download weather data for a specific location (using Latitude and Longitude coordinates)
     * @param $lat
     * @param $lon
     * @return WeatherService
     */
    public function fromLatLon($lat, $lon)
    {
        $this->response = file_get_contents("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&appid={$this->apiKey}");
        return $this;
    }

    /**
     * Formats a string of data by replacing weather service data with placeholder tags.
     * @param string $template The template string (containing placeholder tags for inline replacement)
     * @return string
     */
    public function toFormattedString($template)
    {

        $this->checkDataIsValid();

        $data = $this->toObject();

        // Unit conversions
        $tempC = $data->main->temp;
        $tempF = ($data->main->temp * 1.8) + 32;
        $windMps = $data->wind->speed;
        $windMph = $data->wind->speed * 2.23694;
        $windKph = $data->wind->speed * 3.6;
        $windKts = $data->wind->speed * 1.9438445;

        // Template tags
        $tags = [
            '{description}' => $data->weather[0]->description,
            '{temp_c}' => round($tempC, 1),
            '{temp_f}' => round($tempF, 1),
            '{pressure}' => $data->main->pressure,
            '{humidity}' => $data->main->humidity,
            '{wind_mps}' => round($windMps),
            '{wind_mph}' => round($windMph),
            '{wind_kph}' => round($windKph),
            '{wind_kts}' => round($windKts),
            '{wind_dir_cardinal}' => self::degreesToCardinals($data->wind->deg),
            '{wind_dir_heading}' => $data->wind->deg,
        ];

        foreach ($tags as $tag => $value) {
            $template = str_replace($tag, $value, $template);
        }

        return $template;

    }

    /**
     * Returns the API response as an array.
     * @return array
     */
    public function toArray()
    {
        $this->checkDataIsValid();
        return json_decode($this->response, true);
    }

    /**
     * Returns the API response as a JSON string.
     * @return false|string
     */
    public function toJson()
    {
        $this->checkDataIsValid();
        return json_encode($this->toArray());
    }

    /**
     * Returns the API response as a stdClass object.
     * @return mixed
     */
    public function toObject()
    {
        $this->checkDataIsValid();
        return json_decode($this->response);
    }

    /**
     * Checks that there is response data that can be used.
     * @return bool
     */
    private function checkDataIsValid()
    {
        if ($this->apiKey == null || $this->apiKey === '' || $this->apiKey == 'null') {
            throw new \InvalidArgumentException('No API key has been set, an API key must be set first!');
        }

        if ($this->response == '') {
            throw new \RuntimeException('No API response data available, did you call fromLatLon() or fromLocationName()?');
        }
        return true;
    }

    /**
     * Write the weather data to an SQLite database file.
     * @param string $path The path to the SQLite3 database.
     * @param string $table The database table name to write the data to.
     */
    public function toSqliteDatabase($path, $table)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException('The SQLite database does not exist at the path (' . $path . ') specified.');
        }

        $data = $this->toObject();

        $db = new \SQLite3($path);

        $stm = $db->prepare("INSERT INTO {$table} (description, temp, wind_dir, wind_spd, pressure, humidity, reported_lat, reported_lon, reported_at, created_at) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stm->bindParam(1, $data->weather[0]->description);
        $stm->bindParam(2, $data->main->temp);
        $stm->bindParam(3, $data->wind->deg);
        $stm->bindParam(4, $data->wind->speed);
        $stm->bindParam(5, $data->main->pressure);
        $stm->bindParam(6, $data->main->humidity);
        $stm->bindParam(7, $data->coord->lat);
        $stm->bindParam(8, $data->coord->lon);
        $stm->bindParam(9, $data->dt);
        $stm->bindParam(10, time());
        $stm->execute();

    }

}