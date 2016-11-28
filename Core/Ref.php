<?php
namespace lolita\LolitaFramework\Core;

use \ReflectionClass;
use \Exception;

class Ref
{
    /**
     * Create instance from class
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $class_name
     * @param  array $parameters
     * @return mixed
     */
    public static function create($class_name, $parameters)
    {
        if (class_exists($class_name)) {
            $reflection  = new ReflectionClass($class_name);
            return $reflection->newInstanceArgs(self::prepareClassParameters($class_name, $parameters));
        } else {
            throw new Exception("Class [$class_name] doesn't exists!");
        }
    }

    /**
     * Prepare class parameters
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $class_name
     * @param  array  $parameters class parameters.
     * @return array  prepared parameters.
     */
    public static function prepareClassParameters($class_name, array $parameters = array())
    {
        $return = array();
        $reflection     = new ReflectionClass($class_name);
        $constructor    = $reflection->getConstructor();
        $default_params = $constructor->getParameters();
        foreach ($default_params as $parameter) {
            if (array_key_exists($parameter->getName(), $parameters)) {
                array_push($return, $parameters[ $parameter->getName() ]);
            } else if ($parameter->isDefaultValueAvailable()) {
                array_push($return, $parameter->getDefaultValue());
            } else {
                throw new Exception("Parameter [{$parameter->getName()}] is required!");
            }
        }

        return $return;
    }
}
