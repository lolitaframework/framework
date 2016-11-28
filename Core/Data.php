<?php

namespace lolita\LolitaFramework\Core;

class Data
{
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed   $target
     * @param  string|array  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (($segment = array_shift($key)) !== null) {
            if ($segment === '*') {
                if (! is_array($target)) {
                    return self::interpret($default);
                }

                $result = Arr::pluck($target, $key);

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return self::interpret($default);
            }
        }

        return $target;
    }

    /**
     * Set an item on an array or object using dot notation.
     *
     * @param  mixed  $target
     * @param  string|array  $key
     * @param  mixed  $value
     * @param  bool  $overwrite
     * @return mixed
     */
    public static function set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if (! Arr::accessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    self::set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (Arr::accessible($target)) {
            if ($segments) {
                if (! Arr::exists($target, $segment)) {
                    $target[$segment] = [];
                }

                self::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || ! Arr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (! isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                self::set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        } else {
            $target = [];

            if ($segments) {
                self::set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite) {
                $target[$segment] = $value;
            }
        }

        return $target;
    }

    /**
     * Interpret data to value
     *
     * @param  mixed data
     * @return mixed
     */
    public static function interpret($data)
    {
        if (is_callable($data)) {
            return $data();
        }
        if (!is_string($data)) {
            return $data;
        }
        preg_match_all('/{{.*?}}/', $data, $matches);
        if (is_array($matches)) {
            foreach ($matches[0] as $value) {
                $func = str_replace(array( '{{', '}}' ), '', $value);
                $func = trim($func);
                if (strpos($func, '::')) {
                    list($model, $method) = explode('::', $func);
                    if (class_exists($model, true)) {
                        if (method_exists($model, $method)) {
                            $func_result = $model::$method();
                            if (is_string($func_result)) {
                                $data = str_replace($value, $func_result, $data);
                            } else {
                                $data = $func_result;
                            }
                        }
                    }
                } else {
                    if (array_key_exists($func, self::constants())) {
                        $data = str_replace($value, constant($func), $data);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Get defined constants
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array
     */
    public static function constants()
    {
        $constants = get_defined_constants(true);
        return $constants['user'];
    }

    /**
     * Is JSON data
     *
     * @param  mixed  $data
     * @return boolean
     */
    public static function isJSON($data)
    {
        if (!is_string($data)) {
            return false;
        }
        $result = json_decode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        return true;
    }

    /**
     * Maybe JSON decode
     * Decode JSON or return inputed data.
     *
     * @param  mixed $data
     * @return mixed
     */
    public static function maybeJSONDecode($data)
    {
        if (self::isJSON($data)) {
            return json_decode($data, true);
        }
        return $data;
    }

    /**
     * Maybe JSON encode
     * @param  mixed $data
     * @return mixed
     */
    public static function maybeJSONEncode($data)
    {
        if (is_array($data) || is_object($data)) {
            return json_encode($data);
        }
        return $data;
    }
}
