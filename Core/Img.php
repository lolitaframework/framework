<?php
namespace lolita\LolitaFramework\Core;

class Img
{
    /**
     * Get image url
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  integer $attachment_id attachment id.
     * @param  string  $size          image size
     * @return string image url.
     */
    public static function url($attachment_id = 0, $size = 'thumbnail')
    {
        if (0 === $attachment_id) {
            $attachment_id = get_post_thumbnail_id();
        }
        $thumb_url = wp_get_attachment_image_src($attachment_id, $size, true);
        if (is_array($thumb_url)) {
            return $thumb_url[0];
        }
        return '';
    }

    /**
     * Get post id from thumbnail id
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  integer $thumbnail_id
     * @return mixed
     */
    public static function getPostID($thumbnail_id)
    {
        $args = array(
            'posts_per_page'   => -1,
            'meta_key'         => '_thumbnail_id',
            'meta_value'       => $thumbnail_id,
            'post_type'        => 'any',
            'post_status'      => 'any',
        );
        $posts_array = get_posts($args);
        if (count($posts_array)) {
            return $posts_array[0]->ID;
        }
        return false;
    }
}
