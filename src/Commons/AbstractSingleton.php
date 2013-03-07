<?php

namespace Commons;

use \ReflectionClass, \Exception;

abstract class AbstractSingleton 
{

    private static $_instances = array();

    private function __construct(){}

    protected function init(){}

    public static function &getInstance() 
    {
        $classname = get_called_class(); 
        if (!isset(self::$_instances[ $classname ])) 
        {
            $reflection_obj = new ReflectionClass($classname);
            $callable = $reflection_obj->getMethod('__construct')->isPublic();
            if ($callable) {
                self::$_instances[ $classname ] = call_user_func_array(array($reflection_obj, 'newInstance'), func_get_args());
            } else {
                self::$_instances[ $classname ] = new $classname;
            }
            self::$_instances[ $classname ]->init();
        }
        $instance = self::$_instances[ $classname ];
        return $instance;
    }

    public function __clone()
    {
        throw new Exception(
            sprintf('Cloning of a "%s" instance is not allowed!', get_called_class())
        );
    }

}

// Endfile