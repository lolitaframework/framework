<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Controls\Controls;
use \lolita\LolitaFramework\Configuration\Modules\Elements\Page;

class Pages implements IModule
{
    /**
     * Pages
     * @var array
     */
    private $pages = [];

    /**
     * Metaboxes class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        if (null !== $data) {
            $this->data = (array) $data;
            $this->init();
        } else {
            throw new \Exception(__('JSON can be converted to Array', 'lolita'));
        }
    }

    /**
     * Init hooks
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    private function init()
    {
        add_action('admin_menu', array($this, 'addPages'));
    }

    /**
     * Add pages
     */
    public function addPages()
    {
        foreach ($this->data as $add_page) {
            $this->pages[] = Ref::create(
                __NAMESPACE__ . NS . 'Elements' . NS . 'Page',
                $add_page
            );
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
