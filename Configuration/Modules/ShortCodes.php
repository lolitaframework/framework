<?php
namespace franken\LolitaFramework\Configuration\Modules;

use \franken\LolitaFramework\Configuration\Configuration as Configuration;
use \franken\LolitaFramework\Configuration\IModule as IModule;

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
        if (null !== $this->data) {
            $this->install();
        } else {
            throw new \Exception(__('JSON can be converted to Array', 'lolita'));
        }
    }

    /**
     * Add shortcodes
     */
    private function install()
    {
        if (is_array($this->data)) {
            foreach ($this->data as $tag => $func) {
                add_shortcode($tag, $func);
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
