<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Controls\Controls;

class Pages implements IModule
{
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
            if (array_key_exists('parent_slug', $add_page)) {
                add_submenu_page(
                    $add_page['parent_slug'],
                    $add_page['page_title'],
                    $add_page['menu_title'],
                    $add_page['capability'],
                    $add_page['menu_slug'],
                    $add_page['function']
                );
            } else {
                add_menu_page(
                    $add_page['page_title'],
                    $add_page['menu_title'],
                    $add_page['capability'],
                    $add_page['menu_slug'],
                    Arr::get($add_page, 'function'),
                    Arr::get($add_page, 'icon_url'),
                    Arr::get($add_page, 'position', null)
                );
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
