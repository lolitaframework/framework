<?php

namespace lolita\LolitaFramework\Core\Decorators;

use \lolita\LolitaFramework\Core\Str;
use \Exception;

class Img
{
    /**
     * Image url index.
     */
    const URL = 0;

    /**
     * Image width index
     */
    const WIDTH = 1;

    /**
     * Image height index
     */
    const HEIGHT = 2;

    /**
     * Current image ID
     * @var null
     */
    public $ID = 0;

    /**
     * Class consructor
     *
     * @param mixed $iid
     */
    public function __construct($iid)
    {
        if (!is_integer($iid) || $iid <= 0) {
            $this->ID = false;
        }
        $this->ID = $iid;
    }

    /**
     * Image ID is correct
     *
     * @return boolean
     */
    public function isInitialized()
    {
        return is_integer($this->ID) && $this->ID > 0;
    }

    /**
     * Image data
     *
     * @param  integer $index 0 - url, 1 - width, 2 - height
     * @param  string $size
     * @return Boolean|String
     */
    public function data($index = 0, $size = 'thumbnail')
    {
        if ($this->isInitialized()) {
            $img = image_downsize($this->ID, $size);
            if (is_array($img)) {
                return $img[ $index ];
            }
        }
        return false;
    }

    /**
     * Get image mime type
     *
     * @return mixed
     */
    public function mime()
    {
        if ($this->isInitialized()) {
            return get_post_mime_type($this->ID);
        }
        return false;
    }

    /**
     * Image source
     *
     * @param  string $size
     * @return Boolean|String
     */
    public function src($size = 'thumbnail')
    {
        return $this->data(self::URL, $size);
    }

    /**
     * Image width
     *
     * @param  string $size
     * @return Boolean|String
     */
    public function width($size = 'thumbnail')
    {
        return $this->data(self::WIDTH, $size);
    }

    /**
     * Image height
     *
     * @param  string $size
     * @return Boolean|String
     */
    public function height($size = 'thumbnail')
    {
        return $this->data(self::HEIGHT, $size);
    }

    /**
     * Alt text stored in WordPress
     *
     * @return string alt text stored in WordPress
     */
    public function alt()
    {
        return trim(strip_tags(get_post_meta($this->ID, '_wp_attachment_image_alt', true)));
    }

    /**
     * Convert object to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->src();
    }
}
