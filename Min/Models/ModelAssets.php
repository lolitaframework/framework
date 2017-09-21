<?php
namespace lolita\LolitaFramework\Min\Models;

use \WP_Styles;
use \WP_Scripts;
use \lolita\LolitaFramework\Core\Arr;

class ModelAssets
{

    /**
     * Get all scripts
     * @return mixed
     */
    public static function scripts()
    {
        $cache = wp_cache_get('wp_scripts');
        if (false === $cache) {
            $scripts = wp_scripts();
            if ($scripts instanceof WP_Scripts) {
                return self::queue($scripts, (array) $scripts->queue);
            }
        } else {
            return $cache;
        }
        return false;
    }

    /**
     * Get all styles
     * @return mixed
     */
    public static function styles()
    {
        $styles = wp_styles();
        if ($styles instanceof WP_Styles) {
            return self::queue($styles, (array) $styles->queue);
        }
        return false;
    }

    /**
     * Queue
     * @return mixed
     */
    public static function queue($obj, $handles, $queue = [])
    {
        foreach ($handles as $handler) {
            if (array_key_exists($handler, $obj->registered)) {
                $deps = $obj->registered[ $handler ]->deps;
                if (count($deps) && is_array($deps)) {
                    if (!count(array_diff($deps, array_keys($obj->registered)))) {
                        $queue = self::queue($obj, $deps, $queue);
                        $queue[ $handler ] = $obj->registered[ $handler ];
                    }
                } else {
                    $queue[ $handler ] = $obj->registered[ $handler ];
                }
            }
        }
        return $queue;
    }
}
