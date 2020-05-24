<?php


namespace Ballen\Pirrot\Services;


class TextToSpeechService
{

    /**
     * The Google Cloud API key.
     * @var string
     */
    public $apiKey;

    /**
     * The language to translate the text to.
     * @see https://cloud.google.com/translate/docs/languages
     * @var string
     */
    public $language = 'En-gb';

    /**
     * TextToSpeechService constructor.
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * The language to translate the text into.
     * @see https://cloud.google.com/translate/docs/languages
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Translate the text and retrieve the MP3 data file content.
     * @param string $text The text to synthesise
     * @retun string
     * @return false|string
     */
    public function download($text)
    {
        return $this->makeApiRequest(urlencode($text));
    }

    /**
     * Executes the HTTP API request against the Google API.
     * @param $text
     * @return false|string
     */
    private function makeApiRequest($text)
    {
        return file_get_contents("http://translate.google.com/translate_tts?ie=UTF-8&total=1&idx=0&textlen=32&client=tw-ob&q={$text}&tl={$this->language}&key={$this->apiKey}");
    }

}