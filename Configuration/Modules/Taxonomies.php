<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Modules;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString as HelperString;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray as HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Init as Init;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Configuration as Configuration;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\IModule as IModule;

class Taxonomies extends Init implements IModule
{
    /**
     * Taxonomies class constructor
     *
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
     * @return void
     */
    public function install()
    {
        if (is_array($this->data)) {
            foreach ($this->data as $tax) {
                $this->checkForErrors($tax);
                register_taxonomy(
                    $this->getTaxonomySlug($tax),
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
     * Get taxonomy slug
     * @param  array $taxonomy taxonomy arguments.
     * @return string taxonomy slug.
     */
    private function getTaxonomySlug(array $taxonomy)
    {
        return HelperArray::get(
            $taxonomy,
            'slug',
            HelperString::sentenceToSnake($taxonomy['singular'])
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
        $args           = HelperArray::get($taxonomy, 'args', array());

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
