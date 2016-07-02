<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Core;

class HelperWP
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
            'embed'             => 'is_embed',
            '404'               => 'is_404',
            'search'            => 'is_search',
            'home'              => 'is_home',
            'front_page'        => 'is_front_page',
            'post_type_archive' => 'is_post_type_archive',
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
            'minute'            => array(__CLASS__, 'isMinute'),
            'hour'              => array(__CLASS__, 'isHour'),
            'week'              => array(__CLASS__, 'isWeek'),
            'archive'           => 'is_archive',
            'paged'             => 'is_paged',
        );

        foreach ($types as $type => $func) {
            if ($func()) {
                return $type;
            }
        }
        return '404';
    }

    /**
     * Is minute?
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return boolean true / false.
     */
    public static function isMinute()
    {
        return false !== get_query_var('minute', false);
    }

    /**
     * Is hour?
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return boolean true / false.
     */
    public static function isHour()
    {
        return false !== get_query_var('hour', false);
    }

    /**
     * Is week?
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return boolean true / false.
     */
    public static function isWeek()
    {
        return false !== get_query_var('w', false);
    }
}
