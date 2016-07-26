<?php
namespace MyProject\LolitaFramework\Core;

class Wp
{
    /**
     * Get branch with largest ancestors
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array  $object_ids    ids.
     * @param  string $object_type   object type.
     * @param  string $resource_type resource type.
     * @return array largest branch.
     */
    public static function getBranchWithLargestAncestors(array $object_ids, $object_type, $resource_type)
    {
        $sorting_list = array();
        $ancestors = self::getAncestors($object_ids, $object_type, $resource_type);
        if (count($ancestors)) {
            foreach ($ancestors as $key => $value) {
                $sorting_list[ $key ] = count((array) $value);
            }
            arsort($sorting_list);
            reset($sorting_list);
        }
        return array(key($sorting_list) => $ancestors[ key($sorting_list) ]);
    }

    /**
     * Get ancestors
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array  $object_ids    object ids.
     * @param  string $object_type   object type.
     * @param  string $resource_type resource type.
     * @return array result.
     */
    public static function getAncestors(array $object_ids, $object_type, $resource_type = 'taxonomy')
    {
        $result = array();
        foreach ($object_ids as $id) {
            $result[$id] = get_ancestors($id, $object_type, $resource_type);
        }
        return $result;
    }

    /**
     * Get wp route type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string route type.
     */
    public static function wpRouteType()
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
                    $post_type => function () use ($post_type) { return is_singular($post_type); }
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
