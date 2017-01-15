<?php

namespace lolita\LolitaFramework\Core;

use Countable;

class Validation
{
    /**
     * Validate a value with only alphabetic characters.
     *
     * @param string $data       The data to validate.
     *
     * @return string
     */
    public static function alpha($data)
    {
        return ctype_alpha($data);
    }

    /**
     * Validate a value with only numeric characters.
     *
     * @param string $data       The data to validate.
     *
     * @return string
     */
    public static function num($data, array $attributes = [])
    {
        return ctype_digit($data);
    }

    /**
     * Validate a negative full number.
     *
     * @param string $data
     *
     * @return string
     */
    public static function negnum($data)
    {
        $data = (int) $data;

        return (0 > $data);
    }

    /**
     * Validate a value with alphanumeric characters.
     *
     * @param string $data
     *
     * @return string
     */
    public static function Alnum($data)
    {
        return ctype_alnum($data);
    }

    /**
     * Validate an email value.
     *
     * @param string $data       The data to validate.
     *
     * @return boolean
     */
    public static function email($data)
    {
        return is_email($email);
    }

    /**
     * Validate a URL value.
     *
     * @param string $data       The URL to validate.
     * @param array  $attributes
     *
     * @return boolean
     */
    public static function url($data)
    {
        return filter_var($data, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate a MIN length of string.
     *
     * @param string $data       The string to evaluate.
     * @param array  $min
     *
     * @return boolean
     */
    public static function min($data, $min = 0)
    {
        $min  = (int) $min;
        $data = trim($data);

        if ($min <= strlen($data)) {
            return false;
        }
        return true;
    }

    /**
     * Validate a MAX length of string.
     *
     * @param string $data
     * @param int  $max
     *
     * @return boolean
     */
    public static function max($data, $max = 0)
    {
        $max  = (int) $max;
        $data = trim($data);

        if ($max >= strlen($data)) {
            return false;
        }
        return true;
    }

    /**
     * Validate a boolean value.
     * Return TRUE for '1', 'on', 'yes', 'true'. Else FALSE.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return boolean
     */
    public static function bool($data)
    {
        return filter_var(
            $data,
            FILTER_VALIDATE_BOOLEAN,
            array('flags' => FILTER_NULL_ON_FAILURE)
        ) !== false;
    }

    /**
     * Validate an hexadecimal value.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return boolean
     */
    public static function hex($data)
    {
        return ctype_xdigit($data);
    }

    /**
     * Validate a color hexadecimal value.
     *
     * @param string $data
     *
     * @return boolean
     */
    public static function color($data)
    {
        return preg_match('/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/', $data) === 1;
    }

    /**
     * Validate a file extension.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return boolean
     */
    public static function file($data, array $attributes = array())
    {
        $ext = pathinfo($data, PATHINFO_EXTENSION);

        return in_array($ext, $attributes);
    }

    /**
     * Validate a required data.
     *
     * @param string|array $data
     *
     * @return boolean
     */
    public static function required($data)
    {
        if (is_null($data)) {
            return false;
        } else if (is_string($data) && trim($data) === '') {
            return false;
        } else if ((is_array($data) || $data instanceof Countable) && count($data) < 1) {
            return false;
        }

        return true;
    }

    /**
     * Validate a date
     *
     * @param  string $date
     * @param  string $format
     * @return string
     */
    public static function date($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
