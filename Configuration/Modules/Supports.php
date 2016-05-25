<?php
namespace ECG\LolitaFramework\Configuration\Modules;

use \ECG\LolitaFramework\Core\HelperString as HelperString;
use \ECG\LolitaFramework\Configuration\Init as Init;
use \ECG\LolitaFramework\Configuration\Configuration as Configuration;
use \ECG\LolitaFramework\Configuration\IModule as IModule;

class Supports extends Init implements IModule
{
    /**
     * Supports class constructor
     *
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data        = (array) $data;
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
                if (is_array($value)) {
                    // Theme features with options.
                    add_theme_support($value[0], $value[1]);
                } else {
                    // Allow theme features without options.
                    add_theme_support($value);
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
