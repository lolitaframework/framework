<?php
namespace lolita\LolitaFramework\Configuration\Modules\Elements;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Controls\Controls;

class Taxonomy
{

    /**
     * Post type slug
     * @var null
     */
    private $post_type_slug = 'post';

    /**
     * Singular
     * @var null
     */
    private $singular = null;

    /**
     * Plural
     * @var null
     */
    private $plural = null;

    /**
     * Controls data
     * @var null
     */
    private $controls_data = null;

    /**
     * Controls
     * @var null
     */
    private $controls = null;

    /**
     * Arguments
     * @var array
     */
    private $args = array();

    /**
     * Slug
     * @var null
     */
    private $slug = null;

    /**
     * Class constructor
     *
     * @param string $post_type_slug
     * @param string $singular
     * @param mixed $plural
     * @param mixed $controls
     * @param array  $args
     */
    public function __construct($post_type_slug, $singular, $plural = null, $controls = null, $args = array())
    {
        $this->post_type_slug = $post_type_slug;
        $this->controls_data  = $controls;

        $this->setSingular($singular)
            ->setPlural($plural, $singular)
            ->setSlug($args)
            ->setArguments($args);

        if (is_array($this->controls_data)) {
            $this->initControls();
        }
    }

    /**
     * Init controls
     *
     * @return Taxonomy instance
     */
    private function initControls()
    {
        foreach ($this->controls_data as &$data) {
            $data['old_name'] = $data['name'];
            $data['name'] = $this->controlName($data['name']);
        }
        $this->controls = new Controls;
        $this->controls->generateControls((array) $this->controls_data);


        add_action($this->slug . '_add_form_fields', array(&$this, 'addControls'), 10, 2);
        add_action('created_' . $this->slug, array(&$this, 'save'), 10, 2);
        add_action($this->slug . '_edit_form_fields', array(&$this, 'edit'), 100, 2);
        add_action('edited_' . $this->slug, array(&$this, 'update'), 10, 2);

        return $this;
    }

    /**
     * Add controls to taxonomy
     * Action: {$slug}_add_form_fields
     *
     * @param mixed $taxonomy
     */
    public function addControls($taxonomy)
    {
        echo $this->controls->render(
            Configuration::getFolder() . DS . 'views' . DS . 'taxonomy_controls.php',
            Configuration::getFolder() . DS . 'views' . DS . 'taxonomy_row.php'
        );
    }

    /**
     * Save
     * Action: created_{$slug}
     *
     * @param  int $term_id Term ID
     * @param  int $tt_id   Term taxonomy ID.
     * @return void
     */
    public function save($term_id, $tt_id)
    {
        foreach ($this->controls->collection as $control) {
            $name = $control->getName();
            if (array_key_exists($name, $_POST)) {
                add_term_meta($term_id, $name, $_POST[ $name ], true);
            }
        }
    }

    /**
     * Update
     * Action: edited_{$slug}
     *
     * @param  int $term_id Term ID
     * @param  int $tt_id   Term taxonomy ID.
     * @return void
     */
    public function update($term_id, $tt_id)
    {
        foreach ($this->controls->collection as $control) {
            $this->toggleSave($control->getName(), $term_id);
        }
    }

    /**
     * Toggle save
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $name    $_POST key.
     * @param  int $term_id Term ID
     * @return boolean true = saved / false = deleted.
     */
    private function toggleSave($name, $term_id)
    {
        if (array_key_exists($name, $_POST)) {
            update_term_meta($term_id, $name, $_POST[ $name ]);
            return true;
        } else {
            delete_term_meta($term_id, $name);
            return false;
        }
    }

    /**
     * Edit
     * Action: {$slug}_edit_form_fields
     *
     * @param  object $term     Current taxonomy term object.
     * @param  string $taxonomy Current taxonomy slug.
     * @return void
     */
    public function edit($term, $taxonomy)
    {
        if ($this->controls instanceof Controls) {
            foreach ($this->controls->collection as $control) {
                // ==============================================================
                // Set new value
                // ==============================================================
                $control->setValue(get_term_meta($term->term_id, $control->getName(), true));
            }
        }
                    
        echo $this->controls->render(
            Configuration::getFolder() . DS . 'views' . DS . 'taxonomy_controls.php',
            Configuration::getFolder() . DS . 'views' . DS . 'taxonomy_edit_row.php'
        );
    }

    /**
     * Add prefix to name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $prefix prefix.
     * @param  string $name   name.
     * @return string         name with prefix.
     */
    private function controlName($name)
    {
        return sprintf(
            '%s_%s',
            $this->slug,
            $name
        );
    }

    /**
     * Set singular
     *
     * @param mixed $singular
     * @return Taxonomy instance
     */
    public function setSingular($singular)
    {
        $this->singular = $singular;
        return $this;
    }

    /**
     * Set plural
     *
     * @param mixed $singular
     * @param mixed $plural
     * @return Taxonomy instance
     */
    public function setPlural($plural, $singular = null)
    {
        if (null === $plural && null !== $singular) {
            $this->plural = Str::plural($singular);
        } else {
            $this->plural = $plural;
        }
        return $this;
    }

    /**
     * Set slug
     *
     * @param array $args
     * @return Taxonomy instance
     */
    public function setSlug(array $args = array())
    {
        if (array_key_exists('slug', $args)) {
            $this->slug = $args['slug'];
        } else {
            $this->slug = Str::slug($this->singular);
        }
        return $this;
    }

    /**
     * Register taxonomy
     *
     * @return Taxonomy instance
     */
    public function register()
    {
        register_taxonomy(
            $this->slug,
            $this->post_type_slug,
            $this->args
        );
        return $this;
    }

    /**
     * Set arguments
     *
     * @param array $args
     * @return Taxonomy instance
     */
    private function setArguments(array $args)
    {
        $labels = array(
            'name'              => $this->plural,
            'singular_name'     => $this->singular,
            'search_items'      => 'Search ' . $this->plural,
            'all_items'         => 'All ' . $this->plural,
            'parent_item'       => 'Parent ' . $this->singular,
            'parent_item_colon' => 'Parent ' . $this->singular . ' :',
            'edit_item'         => 'Edit ' . $this->singular,
            'update_item'       => 'Update ' . $this->singular,
            'add_new_item'      => 'Add New ' . $this->singular,
            'new_item_name'     => 'New ' . $this->singular . ' Name',
            'menu_name'         => $this->plural,
        );
        $this->args = array_merge(
            array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => $this->slug . "_tax"),
            ),
            $args
        );
        return $this;
    }
}
