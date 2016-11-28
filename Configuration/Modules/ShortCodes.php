<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration as Configuration;
use \lolita\LolitaFramework\Configuration\IModule as IModule;

class ShortCodes implements IModule
{
    /**
     * ShortCodes class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
