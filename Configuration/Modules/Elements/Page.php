<?php
namespace lolita\LolitaFramework\Configuration\Modules\Elements;

use \Exception;
use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Controls\Controls;
use \lolita\LolitaFramework\Configuration\Configuration;

class Page
{
    /**
     * Nonce
     */
    const NONCE = 'LolitaFramework';

    /**
     * Page title
     * @var null
     */
    private $title = null;

    /**
     * Menu title
     * @var string
     */
    private $menu_title = '';

    /**
     * Menu slug
     * @var string
     */
    private $menu_slug  = '';

    /**
     * Render function
     * @var string
     */
    private $func = null;

    /**
     * Page capability
     * @var string
     */
    private $capability = 'read';

    /**
     * Icon url
     * @var string
     */
    private $icon_url = '';

    /**
     * Menu item position
     * @var null
     */
    private $position = null;

    /**
     * Parent slug
     * @var null
     */
    private $parent_slug = null;

    /**
     * Controls
     * @var array
     */
    private $controls = [];

    /**
     * Class constructor
     * @param string $page_title
     * @param string $menu_title
     * @param string $menu_slug
     * @param string $capability
     * @param string $icon_url
     * @param int $position
     * @param string $parent_slug
     */
    public function __construct($page_title, $condition = null, $menu_title = '', $menu_slug = '', $function = '', $capability = '', $icon_url = '', $position = null, $parent_slug = null, $controls = [])
    {
        if ($this->checkCondition($condition)) {
            $this->setTitle($page_title)
                ->setMenuTitle($menu_title)
                ->setMenuSlug($menu_slug)
                ->setFunction($function)
                ->setCapability($capability)
                ->setIconUrl($icon_url)
                ->setPosition($position)
                ->setParentSlug($parent_slug)
                ->setControls($controls)
                ->action();
        }
    }

    /**
     * Launch action
     * @return Instance
     */
    public function action()
    {
        if (null !== $this->parent_slug) {
            add_submenu_page(
                $this->parent_slug,
                $this->title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                $this->func
            );
        } else {
            add_menu_page(
                $this->title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                $this->func,
                $this->icon_url,
                $this->position
            );
        }
        return $this;
    }

    /**
     * Default function for render
     * @return void
     */
    public function defaultFunc()
    {
        echo View::make(
            [dirname(dirname(__DIR__)), 'views', 'default_page.php']
        );
    }

    /**
     * Check condition
     * @param  mixed $condition
     * @return boolean
     */
    private function checkCondition($condition)
    {
        if (is_callable($condition)) {
            return call_user_func($condition);
        }
        return true;
    }

    /**
     * Set controls
     * @param array $controls
     * @return Instance
     */
    private function setControls(array $controls_arr)
    {
        if (count($controls_arr)) {
            $controls = new Controls;
            $controls->generateControls($controls_arr);
            $this->controls = $controls;
            $this->func = [&$this, 'renderControls'];
        }
        return $this;
    }

    /**
     * Render controls
     * @return Instance
     */
    public function renderControls()
    {
        $this->saveControlsValue();
        if ($this->controls instanceof Controls) {
            foreach ($this->controls->collection as $control) {
                // ==============================================================
                // Set new value
                // ==============================================================
                $control->setValue(get_option($control->getName()));
            }
            echo View::tag('h1', [], $this->title);
            echo $this->controls->render(
                Configuration::getFolder() . DS . 'views' . DS . 'page_controls.php',
                Configuration::getFolder() . DS . 'views' . DS . 'page_row.php'
            );
        } else {
            throw new \Exception('Wrong $controls object');
        }
        return $this;
    }

    /**
     * Save controls value
     * @return boolean
     */
    private function saveControlsValue()
    {
        $data = $_POST;
        if (array_key_exists('submit', $data)) {
            foreach ($this->controls->collection as $control) {
                $this->toggleSave($data, $control->getName());
            }
            echo View::make([dirname(dirname(__DIR__)), 'views', 'page_successfully_updated.php']);
            return true;
        }
        return false;
    }

    /**
     * Toggle Save
     * @param  array $data
     * @param  string $name
     * @param  mixed $value
     * @return boolean
     */
    private function toggleSave($data, $name)
    {
        if (array_key_exists($name, $data)) {
            update_option($name, $data[ $name ]);
            return true;
        } else {
            delete_option($name);
            return false;
        }
    }

    /**
     * Set parent slug
     * @param string $slug
     * @return Instance
     */
    private function setParentSlug($slug)
    {
        if (null !== $slug && '' !== $slug) {
            $this->parent_slug = $slug;
        }
        return $this;
    }

    /**
     * Set position
     * @param string $position
     * @return Instance
     */
    private function setPosition($position)
    {
        if (null !== $position) {
            $this->position = $position;
        }
        return $this;
    }

    /**
     * Set icon url
     * @param string $url
     */
    private function setIconUrl($url)
    {
        $url = trim($url);
        if ('' !== $url) {
            $this->icon_url = $url;
        }
        return $this;
    }

    /**
     * Set capability
     * @param string $capability
     * @return Instance
     */
    private function setCapability($capability)
    {
        $capability = trim($capability);
        if ('' !== $capability) {
            $this->capability = $capability;
        }
        return $this;
    }

    /**
     * Set function
     * @param mixed $function
     * @return Instance
     */
    private function setFunction($function)
    {
        if (is_callable($function)) {
            $this->func = $function;
        } else {
            $this->func = [&$this, 'defaultFunc'];
        }
        return $this;
    }

    /**
     * Set menu slug
     * @param string $slug
     * @return Instance
     */
    private function setMenuSlug($slug)
    {
        $slug = trim($slug);
        if ('' !== $slug) {
            $this->menu_slug = $slug;
        }
        return $this;
    }

    /**
     * Set menu title
     * @param string $title
     * @return Instance
     */
    private function setMenuTitle($title)
    {
        $title = trim($title);
        if ('' !== $title) {
            $this->menu_title = $title;
            $this->setMenuSlug(Str::slug($title));
        }
        return $this;
    }

    /**
     * Set title
     * @param string $title
     * @return Instance
     */
    private function setTitle($title)
    {
        $title = trim($title);
        if ('' === $title) {
            throw new Exception('Title can not be empty');
        }
        $this->title = $title;
        $this->setMenuTitle($this->title);
        return $this;
    }
}
