<?php
namespace lolita\LolitaFramework\Generator;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Generator\Modules\Post;
use \lolita\LolitaFramework\Generator\Modules\Term;

class Generator
{
    /**
     * Create few posts
     *
     * @return array
     */
    public static function posts($count, $args = array(), $unique = true, $image_args = array(), $meta_data = array())
    {
        $count  = max(1, (int) $count);
        $return = array();
        $args   = array_merge(
            array(
                'post_type'    => 'post',
                'post_title'   => 'Sample post {{ n }}',
                'post_content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil, magnam.',
                'post_status'  => 'publish',
                'post_excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil, magnam',
            ),
            $args
        );
        $image_args = array_merge(
            array(
                'image_type' => 'random',
                'image_id'   => '',
            ),
            $image_args
        );

        for ($i = 0; $i < $count; $i++) {
            $new_args = $args;
            $new_args['post_title'] = str_replace('{{ n }}', $i, $new_args['post_title']);
            $new_args['post_title'] = Data::interpret($new_args['post_title']);
            $post     = new Post($new_args, $image_args, $meta_data);
            $return[] = $post->insert($unique);
        }
        return $return;
    }

    /**
     * Delete all generated posts
     *
     * @param  array $args
     * @return array
     */
    public static function deletePosts($args)
    {
        $result    = array();
        $post_type = Arr::get($args, 'post_type', 'post');
        $args      = array(
            'posts_per_page'   => -1,
            'meta_key'         => 'lf_generator',
            'post_type'        => $post_type,
            'post_status'      => 'publish',
        );

        $items = get_posts($args);
        foreach ($items as $item) {
            $result[] = array(
                'id'      => $item->ID,
                'deleted' => false !== wp_delete_post($item->ID),
            );
        }
        return $result;
    }

    /**
     * Create few terms
     *
     * @param  integer  $count
     * @param  array    $args
     * @param  boolean  $unique
     * @param  array    $meta_data
     * @return array
     */
    public static function terms($count, $title, $taxonomy, array $args = array(), array $meta_data = array())
    {
        $return = array();
        $count  = max(1, (int) $count);
        for ($i = 0; $i < $count; $i++) {
            $insert_title     = str_replace('{{ n }}', $i, $title);
            $insert_title     = Data::interpret($insert_title);
            $new_args         = $args;
            $new_args['slug'] = Str::slug($title, '_');
            $term             = new Term($insert_title, $taxonomy, $args, $meta_data);
            $return[]         = $term->insert();
        }
        return $return;
    }

    /**
     * Delete terms
     *
     * @param  array $args
     * @return array deleted terms.
     */
    public static function deleteTerms($args)
    {
        $result   = array();
        $taxonomy = Arr::get($args, 'taxonomy', 'category');
        $args     = array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'meta_key'   => 'lf_generator',
        );
        $terms = get_terms($args);
        if (!is_wp_error($terms)) {
            foreach ((array) $terms as $t) {
                $result[] = array(
                    'term_id' => $t->term_id,
                    'deleted' => false !== wp_delete_term($t->term_id, $t->taxonomy),
                );
            }
        }
        return $result;
    }
}
