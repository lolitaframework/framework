<?php
namespace LiveEditor\LolitaFramework\Core;

class HelperString
{
    /**
     * Take a string_like_this and return a StringLikeThis
     *
     * @param  [string] $val string_like_this.
     * @return [string] StringLikeThis.
     */
    public static function snakeToCamel($val)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $val)));
    }

    /**
     * Take a StringLikeThis and return a string_like_this
     * @param  [string] $val StringLikeThis.
     * @return [string] string_like_this.
     */
    public static function camelToSnake($val)
    {
        return preg_replace_callback(
            '/[A-Z]/',
            create_function('$match', 'return "_" . strtolower($match[0]);'),
            $val
        );
    }

    /**
     * Call model from string like this {{ Model::Method }}
     *
     * @param  string $path_str [description]
     * @return [type]           [description]
     */
    public static function compileVariables($str = '')
    {
        preg_match_all('/{{.*?}}/', $str, $matches);
        if (is_array($matches)) {
            foreach ($matches[0] as $value) {
                $func = str_replace(array( '{{', '}}' ), '', $value);
                $func = trim($func);
                if (strpos($func, '::')) {
                    $pieces = explode('::', $func);
                    $model  = $pieces[0];
                    $method = $pieces[1];
                    if (class_exists($model, false)) {
                        if (method_exists($model, $method)) {
                            $str = (string) $model::$method();
                        }
                    }
                } else {
                    $constants = get_defined_constants(true);
                    $constants = $constants['user'];
                    if (array_key_exists($func, $constants)) {
                        $str = str_replace($value, $constants[$func], $str);
                    }
                }
            }
        }
        return $str;
    }
}
