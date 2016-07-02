<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Modules;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString as HelperString;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Init as Init;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Configuration as Configuration;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\IModule as IModule;

class Menus extends Init implements IModule
{

    /**
     * Menus class constructor
     *
     * @param string $data config file data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        $this->init();
    }

    /**
     * Run by the 'init' hook.
     * Execute the "register_nav_menus" function from WordPress
     *
     * @return void
     */
    public function install()
    {
        if (is_array($this->data) && !empty($this->data)) {
            $locations = array();

            foreach ($this->data as $slug => $desc) {
                $locations[ $slug ] = $desc;
            }

            register_nav_menus($locations);
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
