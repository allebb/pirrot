<?php

namespace Ballen\Piplex\Interfaces;

/**
 * Interface TransmittableInterface
 *
 * @package Ballen\Piplex\Interfaces
 */
interface RepeatableInterface
{
    /**
     * Initialises the repeater mode.
     *
     * @return mixed
     */
    public function initialise();
}