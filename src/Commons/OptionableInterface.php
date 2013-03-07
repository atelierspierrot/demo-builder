<?php

namespace Commons;

interface OptionableInterface
{

    /**
     * Set an array of options
     */
    public static function setOptions(array $options);

    /**
     * Set the value of a specific option
     */
    public static function setOption($name, $value);

    /**
     * Get the array of options
     */
    public static function getOptions();

    /**
     * Get the value of a specific option
     */
    public static function getOption($name, $default=null);

}

// Endfile