<?php
namespace lolita\LolitaFramework\Generator;

use \lolita\LolitaFramework\Core\Arr;

class Data
{
    /**
     * Get all lists
     *
     * @return array
     */
    public static function lists()
    {
        $return = array();
        $files  = glob(__DIR__ . DS . 'data' . DS . '*.json');

        if (is_array($files)) {
            foreach ($files as $file) {
                $file = str_replace(__DIR__ . DS . 'data' . DS, '', $file);
                $file = str_replace('.json', '', $file);
                $return[] = $file;
            }
        }
        return $return;
    }

    /**
     * Generate dummy data
     *
     * @param  string $list
     * @return string
     */
    public static function dummy($list = 'skills')
    {
        $path = __DIR__ . DS . 'data' . DS . $list . '.json';
        if (is_file($path)) {
            $data = @file_get_contents($path);
            $data = json_decode($data);
            $rand = rand(0, count($data));
            return $data[ $rand ];
        }
        return '';
    }

    /**
     * Interpret list
     *
     * @param  string $list
     * @return string
     */
    public static function interpret($data)
    {
        preg_match_all('/{{.*?}}/', $data, $matches);
        if (is_array($matches)) {
            foreach ($matches[0] as $value) {
                $func = str_replace(array( '{{', '}}' ), '', $value);
                $func = trim($func);
                return self::dummy($func);
            }
        }
        return $data;
    }
}
