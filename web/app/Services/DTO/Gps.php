<?php

namespace App\Services\DTO;

class Gps
{

    /**
     * The hardware device name (/dev/) that provided the GPS data.
     * @var null
     */
    public $device = null;

    /**
     * The satellite broadcast timestamp.
     * @var null
     */
    public $time = null;

    /**
     * Latitude
     * @var null
     */
    public $latitude = null;

    /**
     * Longitude
     * @var null
     */
    public $longitude = null;

    /**
     * Altitude in
     * @var null
     */
    public $altitude = null;

    /**
     * Speed in
     * @var null
     */
    public $speed = null;

    /**
     * The satellite PRN's that reported the positional data.
     * @var array
     */
    public $satellites = [];
}
