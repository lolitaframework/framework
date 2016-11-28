<?php
namespace lolita\LolitaFramework\Core;

class Route
{

    /**
     * Base route info
     *
     * @return array
     */
    public static function base()
    {
        return array(
            array(
                'type'      => '404',
                'condition' => 'is_404',
                'template'  => 'get_404_template',
            ),
            array(
                'type'      => 'archive',
                'condition' => 'is_archive',
                'template'  => 'get_archive_template',
            ),
            array(
                'type'      => 'attachment',
                'condition' => 'is_attachment',
                'template'  => 'get_attachment_template',
            ),
            array(
                'type'      => 'author',
                'condition' => 'is_author',
                'template'  => 'get_author_template',
            ),
            array(
                'type'      => 'category',
                'condition' => 'is_category',
                'template'  => 'get_category_template',
            ),
            array(
                'type'      => 'date',
                'condition' => 'is_date',
                'template'  => 'get_date_template',
            ),
            array(
                'type'      => 'day',
                'condition' => 'is_day',
                'template'  => 'get_date_template',
            ),
            array(
                'type'      => 'front',
                'condition' => 'is_front_page',
                'template'  => 'get_front_page_template',
            ),
            array(
                'type'      => 'home',
                'condition' => 'is_home',
                'template'  => 'get_home_template',
            ),
            array(
                'type'      => 'month',
                'condition' => 'is_month',
                'template'  => 'get_date_template',
            ),
            array(
                'type'      => 'page',
                'condition' => 'is_page',
                'template'  => 'get_page_template',
            ),
            array(
                'type'      => 'paged',
                'condition' => 'is_paged',
                'template'  => 'get_paged_template',
            ),
            array(
                'type'      => 'postTypeArchive',
                'condition' => 'is_post_type_archive',
                'template'  => 'get_post_type_archive_template',
            ),
            array(
                'type'      => 'search',
                'condition' => 'is_search',
                'template'  => 'get_search_template',
            ),
            array(
                'type'      => 'single',
                'condition' => 'is_single',
                'template'  => 'get_single_template',
            ),
            array(
                'type'      => 'sticky',
                'condition' => 'is_sticky',
                'template'  => 'get_single_template',
            ),
            array(
                'type'      => 'singular',
                'condition' => 'is_singular',
                'template'  => 'get_singular_template',
            ),
            array(
                'type'      => 'tag',
                'condition' => 'is_tag',
                'template'  => 'get_tag_template',
            ),
            array(
                'type'      => 'tax',
                'condition' => 'is_tax',
                'template'  => 'get_taxonomy_template',
            ),
            array(
                'type'      => 'time',
                'condition' => 'is_time',
                'template'  => 'get_date_template',
            ),
            array(
                'type'      => 'year',
                'condition' => 'is_year',
                'template'  => 'get_date_template',
            ),
        );
    }

    /**
     * Get all types and conditions $type => $condition
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param mixes $post
     * @return array
     */
    public static function typesConditions($post = null)
    {
        $types = Arr::pluck(self::base(), 'condition', 'type');

        // ==============================================================
        // Add post types
        // ==============================================================
        foreach ((array) get_post_types(array('_builtin' => false)) as $post_type) {
            $types = Arr::prepend(
                $types,
                function () use ($post_type) {
                    return is_singular($post_type);
                },
                $post_type
            );
        }

        // ==============================================================
        // Add taxonomies
        // ==============================================================
        foreach ((array) get_taxonomies(array('_builtin' => false), 'objects') as $tax) {
            $tax_name = $tax->name;
            $types = Arr::prepend(
                $types,
                function () use ($tax_name) {
                    return is_tax($tax_name);
                },
                $tax_name
            );
        }

        // ==============================================================
        // Add template page
        // ==============================================================
        if (null !== $post) {
            $page_template = trim((string) get_post_meta($post->ID, '_wp_page_template', true));
            if ('' !== $page_template) {
                $types = Arr::prepend(
                    $types,
                    function () {
                        return true;
                    },
                    $page_template
                );
            }
        }
        return $types;
    }

    /**
     * Get wp route type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string route type.
     */
    public static function type()
    {
        foreach (self::typesConditions() as $type => $condition) {
            if ($condition()) {
                return $type;
            }
        }
        return '404';
    }
}
