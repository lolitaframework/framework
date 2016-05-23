<?php
namespace LiveEditor\LolitaFramework\Core;

class HelperArray
{
    /**
     * Try get value by key from array
     *
     * @param  array $array values list.
     * @param  type  $key value key.
     * @param  type  $default default value.
     * @return mixed value by key
     */
    public static function get($array, $key, $default = '')
    {
        $array = (array) $array;
        if (is_null($key)) {
            return $array;
        }
        if (array_key_exists($key, $array)) {
            return $array[ $key ];
        }
        return $default;
    }

    /**
     * Join array to string
     *
     * @param  array $arr --- array like 'key' => 'value'.
     * @return string --- joined string
     */
    public static function join($arr = array())
    {
        $arr    = self::removeEmpty($arr);
        $result = array();
        foreach ($arr as $key => $value) {
            $result[] = sprintf('%s="%s"', $key, $value);
        }
        return implode(' ', $result);
    }

    /**
     * Remove empty elements
     *
     * @param  array $arr --- array with empty elements.
     * @return array --- array without empty elements
     */
    public static function removeEmpty($arr)
    {
        return array_filter($arr, array( __CLASS__, 'removeEmptyCheck'));
    }

    /**
     * Check if empty.
     * It's need for PHP 5.2.4 version
     *
     * @param  [type] $var variable.
     * @return boolean
     */
    public static function removeEmptyCheck($var)
    {
        return '' != $var;
    }

    /**
     * Lave just right keys in array
     *
     * @param  array $right_keys right keys to leave.
     * @param  array $array list.
     * @return array
     */
    public static function leaveRightKeys($right_keys, $array)
    {
        $right_keys = (array) $right_keys;
        $array      = (array) $array;
        if (count($array)) {
            foreach ($array as $key => $value) {
                if (! in_array($key, $right_keys)) {
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }

    /**
     * Remove some keys form array
     *
     * @param  [type] $right_keys keys to remove.
     * @param  [type] $array      where we want remove this keys.
     * @return array without keys
     */
    public static function removeRightKeys($right_keys, $array)
    {
        $right_keys = (array) $right_keys;
        $array      = (array) $array;
        if (count($right_keys)) {
            foreach ($right_keys as $key) {
                if (array_key_exists($key, $array)) {
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }
}
