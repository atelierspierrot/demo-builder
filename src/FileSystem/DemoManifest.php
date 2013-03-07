<?php

namespace FileSystem;

use \DateTime, \InvalidArgumentException;
use \FileSystem\AbstractConfigFileDependent;
use \DemoBuilder\Page;

class DemoManifest extends AbstractConfigFileDependent
{

    public static $defaults = array(
        'name'          => '@DEMO_NAME@',
        'urls'          => array(),
    );

    public function parse(array $data = array())
    {
        $global_defaults = array_merge(Page::$defaults, self::$defaults);

        // with default page values
        foreach(Page::$defaults as $name=>$value) {
            $this->{$name} = $value;
        }

        // with config manifest values
        foreach($global_defaults as $name=>$value) {
            if (isset($data[$name])) {
                if (is_array($value)) {
                    if (!is_array($data[$name])) {
                        $this->{$name} = array_merge($this->{$name}, explode(',', $data[$name]));
                    } else {
                        $this->{$name} = array_merge($this->{$name}, $data[$name]);
                    }
                } else {
                    $this->{$name} = $data[$name];
                }
            }
        }

    }

}

// Endfile