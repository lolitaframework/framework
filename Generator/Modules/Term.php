<?php
namespace lolita\LolitaFramework\Generator\Modules;

use \lolita\LolitaFramework\Core\Arr;

class Term
{
    /**
     * Term properties
     * @var array
     */
    private $properties = array();

    /**
     * Meta data to add in to term
     * @var array
     */
    private $meta_data = array();

    /**
     * Inserted array
     * @var null
     */
    private $inserted = null;

    /**
     * Term title
     * @var null
     */
    private $title = null;

    /**
     * Term taxonomy
     * @var null
     */
    private $taxonomy = null;

    /**
     * Class constructor
     *
     * @param array $properties
     */
    public function __construct($title, $taxonomy, array $properties = array(), array $meta_data = array())
    {
        $this->properties = $properties;
        $this->meta_data  = array_merge(
            array(
                'lf_generator' => true,
            ),
            $meta_data
        );
        $this->title      = $title;
        $this->taxonomy   = $taxonomy;
    }

    /**
     * Insert term with all sutf ( meta )
     *
     * @return wp_insert_term result
     */
    public function insert()
    {
        $this->inserted = wp_insert_term(
            $this->title,
            $this->taxonomy,
            $this->properties
        );
        if (!is_wp_error($this->inserted) && 0 < $this->inserted['term_id']) {
            $this->addMeta();
        }
        return $this->inserted;
    }

    /**
     * Add meta to post
     *
     * @return Term instance
     */
    public function addMeta()
    {
        foreach ($this->meta_data as $key => $value) {
            $value = stripslashes_deep($value);
            if (is_serialized($value)) {
                $value = maybe_unserialize($value);
            }

            update_term_meta($this->inserted['term_id'], $key, $value);
        }
        return $this;
    }
}
