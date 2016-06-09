<?php
namespace duidluck\LolitaFramework\Core;

class HelperString
{
    /**
     * Sentence like : "Hello i'm here" to snake "hello_im_here"
     * @param  string $val    sentence
     * @param  string $symbol snake symbol, default _.
     * @return sentence in snake case.
     */
    public static function sentenceToSnake($val, $symbol = '_')
    {
        $title = strip_tags($val);
        // Preserve escaped octets.
        $val = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $val);
        // Remove percent signs that are not part of an octet.
        $val = str_replace('%', '', $val);
        // Restore octets.
        $val = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $val);

        if (seems_utf8($val)) {
            if (function_exists('mb_strtolower')) {
                $val = mb_strtolower($val, 'UTF-8');
            }
            $val = utf8_uri_encode($val, 200);
        }

        $val = strtolower($val);

        // kill entities
        $val = preg_replace('/&.+?;/', '', $val);
        $val = str_replace('.', $symbol, $val);

        $val = preg_replace('/[^%a-z0-9 _-]/', '', $val);
        $val = preg_replace('/\s+/', $symbol, $val);
        $val = preg_replace('|-+|', $symbol, $val);
        $val = trim($val, $symbol);

        return $val;
    }

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
                    if (class_exists($model, true)) {
                        if (method_exists($model, $method)) {
                            $str = $model::$method();
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
