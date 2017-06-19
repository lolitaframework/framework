<?php

namespace lolita\LolitaFramework\Core;

class Request
{
    /**
     * Get request data
     * @return mixed
     */
    public static function data()
    {
        if (self::isPOST()) {
            return $_POST;
        }
        return $_GET;
    }

    /**
     * Is POST ?
     * @return boolean
     */
    public static function isPOST()
    {
        return 'POST' === Arr::get($_SERVER, 'REQUEST_METHOD');
    }

    /**
     * Is GET ?
     * @return boolean
     */
    public static function isGET()
    {
        return 'GET' === Arr::get($_SERVER, 'REQUEST_METHOD');
    }
}
