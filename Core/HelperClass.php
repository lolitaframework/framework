<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Core;

class HelperClass
{

    /**
     * Is $interface implements in this $class
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  mixed  $class     to check.
     * @param  mixed  $interface implements.
     * @return boolean true if implements | false if not.
     */
    public static function isImplements($class, $interface)
    {
        return in_array($interface, class_implements($class));
    }

    /**
     * Get all methods from class
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  mixed $class   name
     * @param  integer $options methods access.
     * @return array methods.
     */
    public static function getAllMethods($class, $options = null)
    {
        if (null === $options) {
            $options = ReflectionMethod::IS_STATIC
                | ReflectionMethod::IS_PUBLIC
                | ReflectionMethod::IS_PROTECTED
                | ReflectionMethod::IS_PRIVATE
                | ReflectionMethod::IS_ABSTRACT
                | ReflectionMethod::IS_FINAL;
        }
        $result     = array();
        $reflection = new \ReflectionClass($class);
        $methods    = $reflection->getMethods($options);

        if (is_array($methods)) {
            foreach ($methods as $obj) {
                array_push($result, $obj->name);
            }
        }
        return $result;
    }
}
