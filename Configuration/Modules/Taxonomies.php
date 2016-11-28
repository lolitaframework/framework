<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Configuration\Init;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;

class Taxonomies extends Init implements IModule
{
    private $taxonomies = array();
    /**
     * Taxonomies class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        if (is_array($this->data)) {
            foreach ($this->data as $tax) {
                $this->taxonomies[] = Ref::create(
                    __NAMESPACE__ . NS . 'Elements' . NS . 'Taxonomy',
                    $tax
                );
            }
        }

        $this->init();
    }

    /**
     * Prepare data
     *
     * @return Taxonomies instance
     */
    public function prepareData()
    {
        // if (is_array($this->data)) {
        //     foreach ($this->data as &$tax) {
        //         if (array_key_exists('singular', $tax) && !array_key_exists('plural', $tax)) {
        //             $tax['plural'] = Str::plural($tax['singular']);
        //         } else if (!array_key_exists('singular', $tax) && array_key_exists('plural', $tax)) {
        //             $tax['singular'] = Str::singular($tax['plural']);
        //         }
        //         $this->checkForErrors($tax);
        //         $tax['slug'] = Str::slug($tax['singular']);

        //         if (array_key_exists('controls', $tax)) {

        //             add_action($tax['slug'] . '_add_form_fields', array(&$this, 'addControls'), 10, 2 );

        //             foreach ($tax['controls'] as &$control) {
        //                 $name = Arr::get($control, 'name', '');
        //                 $name = trim($name);

        //                 if ('' === $name) {
        //                     throw new \Exception("Name is empty! Name parameter is required!");
        //                 }

        //                 $control['old_name'] = $name;
        //                 $control['name']     = $this->controlNameWithPrefix($tax['slug'], $name);
        //             }
        //             $controls = new Controls;
        //             $controls->generateControls((array) $tax['controls']);
        //             $tax['collection'] => $controls;
        //         } else {
        //             $tax['collection'] = null;
        //         }
        //     }
        // }
        // return $this;
    }

    /**
     * Add prefix to name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $prefix prefix.
     * @param  string $name   name.
     * @return string         name with prefix.
     */
    private function controlNameWithPrefix($prefix, $name)
    {
        return sprintf(
            '%s_%s',
            $prefix,
            $name
        );
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
        if (is_array($this->taxonomies)) {
            foreach ($this->taxonomies as $tax) {
                $tax->register();
            }
        }
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
