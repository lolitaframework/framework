<?php
namespace lolita\LolitaFramework\Tables;

use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Str;
use \Exception;

class Tables
{
    /**
     * Make table
     *
     * @param  string $name
     * @param  array  $rows
     * @return Table isntance
     */
    public static function make($name, array $rows, $show_chart = true)
    {
        return new Table($name, $rows, $show_chart);
    }

    /**
     * Render table
     *
     * @param  array  $rows
     * @param  array  $attributes [description]
     * @return string
     */
    public static function one(array $rows, array $attributes = array())
    {
        $rows = array_merge(
            array(
                'thead' => array(),
                'tbody' => array(),
                'tfoot' => array(),
            ),
            $rows
        );
        $attributes = array_merge(
            array(
                'class' => 'wp-list-table widefat fixed striped'
            ),
            $attributes
        );
        return View::make(
            __DIR__ . DS . 'views' . DS . 'one.php',
            array(
                'thead'      => $rows['thead'],
                'tbody'      => $rows['tbody'],
                'tfoot'      => $rows['tfoot'],
                'attributes' => Arr::join($attributes),
            )
        );
    }
}
