<?php
namespace duidluck\LolitaFramework\Configuration\Modules;

use \duidluck\LolitaFramework\Core\HelperString as HelperString;
use \duidluck\LolitaFramework\Configuration\Init as Init;
use \duidluck\LolitaFramework\Configuration\Configuration as Configuration;
use \duidluck\LolitaFramework\Configuration\IModule as IModule;

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
