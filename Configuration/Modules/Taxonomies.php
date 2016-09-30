<?php
namespace lolitatheme\LolitaFramework\Configuration\Modules;

use \lolitatheme\LolitaFramework\Core\Str;
use \lolitatheme\LolitaFramework\Core\Arr;
use \lolitatheme\LolitaFramework\Configuration\Init;
use \lolitatheme\LolitaFramework\Configuration\Configuration;
use \lolitatheme\LolitaFramework\Configuration\IModule;

class Taxonomies extends Init implements IModule
{
    /**
     * Taxonomies class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        $this->init();
    }

    /**
     * Run by the 'init' hook.
     * Execute the "add_theme_support" function from WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function install()
    {
        if (is_array($this->data)) {
            foreach ($this->data as $tax) {
                if (array_key_exists('singular', $tax) && !array_key_exists('plural', $tax)) {
                    $tax['plural'] = Str::plural($tax['singular']);
                } else if (!array_key_exists('singular', $tax) && array_key_exists('plural', $tax)) {
                    $tax['singular'] = Str::singular($tax['plural']);
                }

                $this->checkForErrors($tax);
                register_taxonomy(
                    Str::slug($tax['singular']),
                    $tax['post_type_slug'],
                    $this->getArguments($tax)
                );
            }
        }
    }

    /**
     * Check taxonomy candidat for erros
     * @param  array $taxonomy [description]
     * @return [type]           [description]
     */
    private function checkForErrors(array $taxonomy)
    {
        foreach ($this->requiredKeys() as $key) {
            if (!array_key_exists($key, $taxonomy)) {
                throw new Exception("This key `$key` is required!");
            }
        }
    }

    /**
     * Get required keys
     * @return array required keys.
     */
    private function requiredKeys()
    {
        return array(
            'post_type_slug',
            'singular',
            'plural',
        );
    }

    /**
     * Get the taxonomy default arguments.
     *
     * @param string $taxonomy taxonomy candidat arguments.
     * @return array
     */
    public function getArguments(array $taxonomy)
    {
        $plural         = $taxonomy['plural'];
        $singular       = $taxonomy['singular'];
        $post_type_slug = $taxonomy['post_type_slug'];
        $args           = Arr::get($taxonomy, 'args', array());

        $labels = array(
            'name'              => $plural,
            'singular_name'     => $singular,
            'search_items'      => 'Search ' . $plural,
            'all_items'         => 'All ' . $plural,
            'parent_item'       => 'Parent ' . $singular,
            'parent_item_colon' => 'Parent ' . $singular . ' :',
            'edit_item'         => 'Edit ' . $singular,
            'update_item'       => 'Update ' . $singular,
            'add_new_item'      => 'Add New ' . $singular,
            'new_item_name'     => 'New ' . $singular . ' Name',
            'menu_name'         => $plural,
        );
        return array_merge(
            array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => $post_type_slug ),
            ),
            $args
        );
    }

    /**
     * Module priority
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
