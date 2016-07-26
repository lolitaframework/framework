<?php
namespace MyProject\LolitaFramework\Core;

class Wp
{

    /**
     * Get wp route type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string route type.
     */
    public static function routeType()
    {
        $types = array(
            '404'               => 'is_404',
            'search'            => 'is_search',
            'post_type_archive' => 'is_post_type_archive',
            'front_page'        => 'is_front_page',
            'home'              => 'is_home',
            'tax'               => 'is_tax',
            'attachment'        => 'is_attachment',
            'single'            => 'is_single',
            'page'              => 'is_page',
            'singular'          => 'is_singular',
            'category'          => 'is_category',
            'tag'               => 'is_tag',
            'author'            => 'is_author',
            'month'             => 'is_month',
            'year'              => 'is_year',
            'date'              => 'is_date',
            'archive'           => 'is_archive',
            'paged'             => 'is_paged',
        );

        $post_type  = get_post_type();
        $post_types = (array) get_post_types(array('_builtin' => false));

        if (in_array($post_type, $post_types)) {
            $types = array_merge(
                array(
                    $post_type => function () use ($post_type) {
                        return is_singular($post_type);
                    }
                ),
                $types
            );
        }

        $qo = get_queried_object();
        if (!empty($qo->slug)) {
            $types = array_merge(array($qo->taxonomy => 'is_tax'), $types);
        }

        foreach ($types as $type => $func) {
            if ($func()) {
                return $type;
            }
        }
        return '404';
    }
}
