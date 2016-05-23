<?php
namespace LiveEditor\LolitaFramework\Configuration\Modules;

use \LiveEditor\LolitaFramework\Core\HelperString as HelperString;
use \LiveEditor\LolitaFramework\Configuration\Init as Init;
use \LiveEditor\LolitaFramework\Configuration\Configuration as Configuration;
use \LiveEditor\LolitaFramework\Configuration\IModule as IModule;

class Supports extends Init implements IModule
{
    /**
     * Supports class constructor
     *
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data        = $data;
        $this->init_action = 'after_setup_theme';
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
        if (is_array($this->data) && ! empty($this->data)) {
            foreach ($this->data as $feature => $value) {
                // Allow theme features without options.
                if (is_int($feature)) {
                    add_theme_support($value);
                } else {
                    // Theme features with options.
                    add_theme_support($feature, $value);
                }
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
