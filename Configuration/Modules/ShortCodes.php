<?php
namespace redbrook\LolitaFramework\Configuration\Modules;

use \redbrook\LolitaFramework\Configuration\Configuration as Configuration;
use \redbrook\LolitaFramework\Configuration\IModule as IModule;

class ShortCodes implements IModule
{
    /**
     * ShortCodes class constructor
     *
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        $this->install();
    }

    /**
     * Add shortcodes
     */
    private function install()
    {
        foreach ($this->data as $tag => $func) {
            add_shortcode($tag, $func);
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
