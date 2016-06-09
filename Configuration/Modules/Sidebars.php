<?php
namespace duidluck\LolitaFramework\Configuration\Modules;

use \duidluck\LolitaFramework\Core\HelperString as HelperString;
use \duidluck\LolitaFramework\Configuration\Init as Init;
use \duidluck\LolitaFramework\Configuration\Configuration as Configuration;
use \duidluck\LolitaFramework\Configuration\IModule as IModule;

class Sidebars extends Init implements IModule
{

    /**
     * Sidebars class constructor
     *
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = (array) $data;
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
            foreach ($this->data as $sidebar) {
                register_sidebar($sidebar);
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
