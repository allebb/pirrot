<?php

namespace Ballen\Piplex\Services;


class AudioService
{

    /**
     * The audio player binary path (with trailing slash)
     *
     * @var string
     */
    public $audioPlayerBin = '/usr/bin/play';

    /**
     * The sound path (with trailing slash)
     *
     * @var string
     */
    public $soundPath = 'resources/sounds/';

    /**
     * Array of pheonetic characters that the audio service can output.
     *
     * @var array
     */
    private $pheonetics = [
        '0' => '0.wav',
        '1' => '1.wav',
        '2' => '2.wav',
        '3' => '3.wav',
        '4' => '4.wav',
        '5' => '5.wav',
        '6' => '6.wav',
        '7' => '7.wav',
        '8' => '8.wav',
        '9' => '9.wav',
        '-' => 'dash.wav',
        '.' => 'decimal.wav',
        '/' => 'slash.wav',
        '\\' => 'slash.wav',
        '*' => 'star/wav',
        'a' => 'phonetic_a.wav',
        'b' => 'phonetic_b.wav',
        'c' => 'phonetic_c.wav',
        'd' => 'phonetic_d.wav',
        'e' => 'phonetic_e.wav',
        'f' => 'phonetic_f.wav',
        'g' => 'phonetic_g.wav',
        'h' => 'phonetic_h.wav',
        'i' => 'phonetic_i.wav',
        'j' => 'phonetic_j.wav',
        'k' => 'phonetic_k.wav',
        'l' => 'phonetic_l.wav',
        'm' => 'phonetic_m.wav',
        'n' => 'phonetic_n.wav',
        'o' => 'phonetic_o.wav',
        'p' => 'phonetic_p.wav',
        'q' => 'phonetic_q.wav',
        'r' => 'phonetic_r.wav',
        's' => 'phonetic_s.wav',
        't' => 'phonetic_t.wav',
        'u' => 'phonetic_u.wav',
        'v' => 'phonetic_v.wav',
        'w' => 'phonetic_w.wav',
        'x' => 'phonetic_x.wav',
        'y' => 'phonetic_y.wav',
        'z' => 'phonetic_z.wav',
    ];

    /**
     * Play the specified courtesy tone.
     *
     * @param $tone The tone filename (without the file extenion)
     * @return void
     */
    public function tone($tone)
    {
        if (file_exists($this->soundPath . 'courtesy_tones/' . $tone . '.wav')) {
            $this->play(' ' . $this->soundPath . 'courtesy_tones/' . $tone . '.wav');
        }
    }

    /**
     * Output the repeater identification.
     *
     * @param $callsign The repeater callsign.
     * @param null $pl The PL/CTCSS tone to access the repeater on (optional)
     * @param bool $withTime Specify if to speak the time with the ident.
     * @param bool $withMorse Specify if to output the morse code translation for the callsign.
     * @return void
     */
    public function ident($callsign, $pl = null, $withTime = false, $withMorse = false)
    {
        $speakArray = [];
        $speakArray[] = $this->soundPath . 'core/repeater.wav';
        $speakArray = array_merge($speakArray, [$this->speak($callsign)]);
        if ($pl) {
            $speakArray[] = $this->soundPath . 'core/pl_is.wav';
            $speakArray = array_merge($speakArray, [$this->speak($pl)]);
        }
        if ($withTime) {
            $speakArray[] = $this->soundPath . 'core/the_time_is.wav';
            $speakArray = array_merge($speakArray,
                [$this->speak(date('Hi'))]); // Could update this later to include "AM" or "PM"
        }
        if ($withMorse) {
            $speakArray[] = $this->morse($callsign);
        }

        $this->play($this->sequenceOutput($speakArray));
    }

    /**
     * Converts a string of text to a morse code
     *
     * @param $string
     * @return string
     */
    public function morse($string)
    {
        // @TODO - Find a morse code generator binary.

        // Generate the morse code and play and return the file name and path.
        return '';
    }

    /**
     * Reads the given string in the pheonetic alphabet.
     *
     * @param $string The string of characters to read.
     * @return void
     */
    public function say($string)
    {
        $this->play($this->speak($string));
    }

    /**
     * Converts text characters to file array.
     *
     * @param string $string The input string
     * @return void
     */
    private function speak($string)
    {
        $speakArray = [];
        foreach (str_split($string) as $character) {
            $character = strtolower($character);
            if (isset($this->pheonetics[$character])) {
                $speakArray[] = $this->soundPath . 'pheonetics/' . $this->pheonetics[$character];
            }
        }
        return $this->sequenceOutput($speakArray);
    }

    /**
     * Returns sequence of audio files.
     *
     * @param array $files
     * @return array
     */
    private function sequenceOutput($files)
    {
        if (is_array($files)) {
            $cliArgs = '';
            foreach ($files as $file) {
                $cliArgs .= ' ' . $file;
            }
        } else {
            $cliArgs = ' ' . $files;
        }
        // Now play the sequence audio...
        return $cliArgs;
    }

    /**
     * Execute the audio player command
     *
     * @param $files The string of audio files to play in order.
     * @return void
     */
    private function play($files)
    {
        system($this->audioPlayerBin . $files);
    }

}