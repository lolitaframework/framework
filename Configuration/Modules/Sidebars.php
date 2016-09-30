<?php
namespace lolitatheme\LolitaFramework\Configuration\Modules;

use \lolitatheme\LolitaFramework\Core\Str;
use \lolitatheme\LolitaFramework\Configuration\Init;
use \lolitatheme\LolitaFramework\Configuration\Configuration;
use \lolitatheme\LolitaFramework\Configuration\IModule;

class Sidebars extends Init implements IModule
{

    /**
     * Sidebars class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        $this->init();
    }

    /**
     * Run by the 'init' hook.
     * Execute the "register_sidebar" function from WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
