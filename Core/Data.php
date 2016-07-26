<?php

namespace MyProject\LolitaFramework\Core;

class Data{

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
}