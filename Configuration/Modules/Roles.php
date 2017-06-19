<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Init;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \WP_Role;

class Roles extends Init implements IModule
{

    /**
     * Initialize action
     *
     * @var string
     */
    protected $init_action = 'switch_theme';

    /**
     * Menus class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $data config file data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        if (array_key_exists('update_roles', $_GET)) {
            $this->install();
        }
    }

    /**
     * Run by the 'init' hook.
     * Execute the "register_nav_menus" function from WordPress
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function install()
    {
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->allowedKeys() as $key) {
                if (array_key_exists($key, $this->data) && is_array($this->data[ $key ])) {
                    $this->$key($this->data[ $key ]);
                }
            }
        }
    }

    /**
     * Remove roles
     * @param  array  $data
     * @return Roles instance
     */
    public function remove(array $data)
    {
        foreach ($data as $remove) {
            remove_role($remove);
        }
        return $this;
    }

    /**
     * Update role
     * @param  array  $data
     * @return Roles instance
     */
    public function update(array $data)
    {
        $wp_roles = wp_roles();
        foreach ($data as $el) {
            if (!array_key_exists('slug', $el)) {
                $el['slug'] = Str::slug($el['name']);
            }
            $slug         = $el['slug'];
            $name         = $el['name'];
            $capabilities = Arr::get($el, 'capabilities', array());
            $role         = get_role($slug);

            if ($role instanceof WP_Role) {
                $capabilities = array_merge($role->capabilities, $capabilities);
                remove_role($slug);
            }
            add_role($slug, $name, $capabilities);
        }
        return $this;
    }

    /**
     * Get allowed keys
     * @return array
     */
    public function allowedKeys()
    {
        return ['remove', 'update'];
    }

    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
