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

    /**
     * Resize image
     *
     * @param  int $image_id
     * @param  int $width
     * @param  int $height
     * @param  boolean $crop
     * @return array
     */
    public static function resize($image_id, $width, $height, $crop)
    {
        // Temporarily create an image size
        $size_id = sprintf('%sx%s', $width, $height);
        add_image_size($size_id, $width, $height, $crop);

        // Get the attachment data
        $meta = wp_get_attachment_metadata($image_id);

        // If the size does not exist
        if(!isset($meta['sizes'][$size_id])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            $file = get_attached_file($image_id);
            $new_meta = wp_generate_attachment_metadata($image_id, $file);

            // Merge the sizes so we don't lose already generated sizes
            $new_meta['sizes'] = array_merge($meta['sizes'], $new_meta['sizes']);

            // Update the meta data
            wp_update_attachment_metadata($image_id, $new_meta);
        }

        // Fetch the sized image
        $sized = wp_get_attachment_image_src($image_id, $size_id);

        // Remove the image size so new images won't be created in this size automatically
        remove_image_size($size_id);
        return $sized;
    }

    /**
     * Get src from image string
     *
     * @param  string $str
     * @return src
     */
    public static function srcFromStr($str)
    {
        $image_src = preg_match('/src="([^"]+)"/', $str, $match_src) ? $match_src[1] : '';
        list($image_src) = explode('?', $image_src);

        // Return early if we couldn't get the image source.
        if (!$image_src) {
            return $str;
        }
        return $image_src;
    }

    /**
     * Get width from str
     *
     * @param  string $str
     * @return int
     */
    public static function widthFromStr($str)
    {
        return preg_match('/ width="([0-9]+)"/',  $str, $match_width) ? (int) $match_width[1]  : 0;
    }

    /**
     * Get height from str
     *
     * @param  string $str
     * @return int
     */
    public static function heightFromStr($str)
    {
        return preg_match('/ height="([0-9]+)"/', $str, $match_height) ? (int) $match_height[1] : 0;
    }

    /**
     * Reduce image size proportionaly
     *
     * @author Vitaliy Shebela
     * @param  integer $base
     * @param  integer  $width
     * @param  integer  $height
     * @return array [$width, $height]
     */
    public static function reducedProportionallySize($width, $height, $base = 100)
    {
        return array($base, (int) ($height * ($base / $width)));
    }
}
