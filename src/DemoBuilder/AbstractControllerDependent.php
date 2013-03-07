<?php

namespace DemoBuilder;

use \DemoBuilder\Controller;

abstract class AbstractControllerDependent
{

    /**
     * The global controller instance
     */
    protected $container = null;

    /**
     * Get the global controller instance
     */
    public function getContainer()
    {
        if (is_null($this->container)) {
            $this->container = Controller::getInstance();
        }
        return $this->container;
    }

    public static function __set_state(array $properties)
    {
        $clsname = get_called_class();
        $obj = new $clsname;
        foreach($properties as $name=>$value) {
            if ('container'!==$name) {
                $obj->{$name} = $value;
            }
        }
        return $obj;
    }

}

// Endfile