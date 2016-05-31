<?php
namespace ECG\LolitaFramework\Configuration\Modules;

use \ECG\LolitaFramework\Core\HelperString as HelperString;
use \ECG\LolitaFramework\Configuration\Init as Init;
use \ECG\LolitaFramework\Configuration\Configuration as Configuration;
use \ECG\LolitaFramework\Configuration\IModule as IModule;

class PostTypes extends Init implements IModule
{

    /**
     * Sidebars class constructor
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
     * Execute the "register_sidebar" function from WordPress.
     *
     * @return void
     */
    public function install()
    {
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $post_type) {
                $args = $this->compileParameters($post_type);
                register_post_type(
                    $post_type['slug'],
                    $args
                );
            }
        }
    }

    /**
     * Check post type parameters by errors
     * @param  [array] $args parameters
     */
    private function checkPostTypeParams($args)
    {
        $args = (array) $args;
        foreach ($this->getRequiredParameters() as $parameter => $value) {
            if (array_key_exists($parameter, $args)) {
                throw new \Exception('Parameter "'.$parameter.'" is required!');
            }
        }

        foreach ($args as $parameter => $value) {
            if (!is_string($parameter)) {
                throw new \Exception(
                    sprintf(
                        'Invalid custom post type parameter "%s". Accepts string only.',
                        $parameter
                    )
                );
            }
        }
    }

    /**
     * Get required post type parameters
     * @return [array] required parameters.
     */
    private function getRequiredParameters() 
    {
        return array(
            'slug',
            'plural',
            'singular',
        );
    }

    /**
     * Get arguments to registering our post type.
     * @param  [array] $post_type_args parameters.
     * @return [array] 
     */
    private function compileParameters($post_type_args)
    {
        $this->checkPostTypeParams($post_type_args);

        $args = $this->getDefaultArguments(
            $post_type_args['plural'],
            $post_type_args['singular']
        );
        if (array_key_exists('native', $post_type_args)) {
            $args = array_merge($args, $post_type_args['native']);
        }
        return $args;
    }

    /**
     * Get the custom post type default arguments.
     *
     * @param [type] $plural The post type plural display name.
     * @param [type] $singular The post type singular display name.
     * @return array
     */
    private function getDefaultArguments($plural, $singular)
    {
        $labels = array(
            'name'               => $plural,
            'singular_name'      => $singular,
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New '. $singular,
            'edit_item'          => 'Edit '. $singular,
            'new_item'           => 'New ' . $singular,
            'all_items'          => 'All ' . $plural,
            'view_item'          => 'View ' . $singular,
            'search_items'       => 'Search ' . $singular,
            'not_found'          => 'No '. $singular .' found',
            'not_found_in_trash' => 'No '. $singular .' found in Trash',
            'parent_item_colon'  => '',
            'menu_name'          => $plural,
        );
        $defaults = array(
            'label'         => $plural,
            'labels'        => $labels,
            'description'   => '',
            'public'        => true,
            'menu_position' => 20,
            'has_archive'   => true,
        );
        return $defaults;
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
