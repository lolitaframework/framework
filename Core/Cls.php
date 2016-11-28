<?php
namespace lolita\LolitaFramework\Core;

class Cls
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
}
