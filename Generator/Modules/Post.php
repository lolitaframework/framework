<?php
namespace lolita\LolitaFramework\Generator\Modules;

use \lolita\LolitaFramework\Core\Arr;

class Post
{
    /**
     * Post properties
     * @var array
     */
    private $properties = array();

    /**
     * Meta data to add in to post
     * @var array
     */
    private $meta_data = array();

    /**
     * Image data to add in to post
     * @var array
     */
    private $image_data = array();

    /**
     * Inserted post id
     * @var null
     */
    private $inserted_id = null;

    /**
     * Class constructor
     *
     * @param array $properties
     */
    public function __construct($properties, $image_data = array(), $meta_data = array())
    {
        $this->properties = $properties;
        $this->meta_data  = array_merge(
            array(
                'lf_generator' => true,
            ),
            $meta_data
        );
        $this->image_data = $image_data;
    }

    /**
     * Insert post with all sutf ( meta, terms, images )
     *
     * @param boolen  $unique
     * @return Post instance.
     */
    public function insert($unique = true)
    {
        $this->properties['post_type'] = Arr::get($this->properties, 'post_type', 'post');
        if ($unique && array_key_exists('post_title', $this->properties)) {
            $post = get_page_by_path(
                sanitize_title($this->properties['post_title']),
                OBJECT,
                $this->properties['post_type']
            );
            if (null !== $post) {
                $this->properties['ID'] = $post->ID;
            }
        }
        $this->inserted_id = wp_insert_post($this->properties);
        if (!is_wp_error($this->inserted_id) && 0 < $this->inserted_id) {
            $this->addMeta()->addImage();
        }
        return $this->inserted_id;
    }

    /**
     * Add meta to post
     *
     * @return Post instance
     */
    public function addMeta()
    {
        foreach ($this->meta_data as $key => $value) {
            update_post_meta($this->inserted_id, $key, $value);
        }
        return $this;
    }

    /**
     * Prepare image data
     *
     * @return Post instance.
     */
    public function prepareImage()
    {
        if ($this->isImageType('random')) {
            $this->randomImage();
        }
        return $this;
    }

    /**
     * Is image type
     *
     * @param  string  $image_type
     * @return boolean
     */
    public function isImageType($image_type = 'custom')
    {
        if (array_key_exists('image_type', $this->image_data)) {
            $this->image_data['image_type'] = strtolower($this->image_data['image_type']);
            $image_type                     = strtolower($image_type);
            return $image_type === $this->image_data['image_type'];
        }
        return false;
    }

    /**
     * Set random image
     *
     * @return Post instance.
     */
    public function randomImage()
    {
        $args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => 1,
            'orderby'        => 'rand',
        );
        $image = get_posts($args);
        if (count($image)) {
            $image = $image[0];
            $this->image_data['image_id'] = $image->ID;
        }
        return $this;
    }

    /**
     * Add featured image to the post
     */
    public function addImage()
    {
        $this->prepareImage();
        if (array_key_exists('image_id', $this->image_data)) {
            set_post_thumbnail($this->inserted_id, $this->image_data['image_id']);
        }
        return $this;
    }
}
