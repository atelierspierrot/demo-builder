<?php

namespace FileSystem;

use \ArrayAccess;
use \FileSystem\ConfigFile;

abstract class AbstractConfigFileDependent implements ArrayAccess
{

    /**
     * The class default settings
     */
    public static $defaults = array();

    /**
     * The configuration file object received by the contsructor
     */
    protected $file;

    /**
     * Constructor receiving a configuration file entity
     */
    public function __construct(ConfigFile $file)
    {
        $this->file = $file;
        $this->init()->parse($this->file->getConfig());
    }

    /**
     * Initialize the object with default values and first settings
     *
     * @return self `$this` for method chaining
     */
    protected function init()
    {
        $clsname = get_called_class();
        if (property_exists($clsname, 'defaults')) {
            foreach($clsname::$defaults as $name=>$value) {
                $this->{$name} = $value;
            }
        }
        return $this;
    }

    /**
     * Parse the values with the confguration file data
     */
    abstract public function parse(array $data = array());

// ArrayAccess interface

    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }
    
    public function offsetGet($offset)
    {
        return isset($this->{$offset}) ? $this->{$offset} : null;
    }
    
    public function offsetSet($offset, $value)
    {
    }
    
    public function offsetUnset($offset)
    {
    }

}

// Endfile