<?php
namespace lolita\LolitaFramework\Widgets;

use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Controls\Controls;
use \lolita\LolitaFramework;

class Widgets
{

    /**
     * Paths to widgets JSON file
     * @var null
     */
    private $paths = null;

    /**
     * JSONS data in array type
     * @var null
     */
    private $parsers = null;

    /**
     * Prepared data
     * @var null
     */
    private $data = null;

    /**
     * All loaded widgets
     * @var null
     */
    private $widgets = null;

    /**
     * Widgets class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        $this->allWidgetsPaths()->parse()->init();
        add_action('widgets_init', array($this, 'register'));
    }

    /**
     * Init widgets classes
     *
     * @return Widgets instance.
     */
    private function init()
    {
        if (is_array($this->parsers)) {
            foreach ($this->parsers as $parser) {
                $widget = Ref::create(__NAMESPACE__ . NS . 'Widget', $parser->data());
                $this->widgets[] = $widget;
                if ($widget->controls) {
                    Controls::loadScriptsAndStyles($widget->controls);
                }
            }
        }
        return $this;
    }

    /**
     * Load classes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function register()
    {
        if (is_array($this->parsers)) {
            foreach ($this->widgets as $widget) {
                $widget->_register();
            }
        }
    }

    /**
     * Get default widgets settings path
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return default widgets settings path.
     */
    private function defaultPath()
    {
        return apply_filters(
            'lf_widgets_settings_path',
            Loc::lolita()->baseDir() . '/app' . DS . 'widgets' . DS
        );
    }

    /**
     * Get all widgets jsons
     *
     * @return Widgets instance.
     */
    private function allWidgetsPaths()
    {
        $folders = (array) glob($this->defaultPath() . '*', GLOB_ONLYDIR);
        foreach ($folders as $folder) {
            $json_path = $this->jsonPath($folder);
            if (false !== $json_path) {
                $this->paths[] = $json_path;
            }
        }
        return $this;
    }

    /**
     * Parse data
     *
     * @return Widgets instance.
     */
    private function parse()
    {
        if (is_array($this->paths)) {
            foreach ($this->paths as $path) {
                $this->parsers[] = new Parser($path);
            }
        }
        return $this;
    }

    /**
     * JSON path from folder path
     *
     * @param  string $folder
     * @return string json path
     */
    private function jsonPath($folder)
    {
        $json_path = $folder . DS . basename($folder) . '.json';
        if (is_file($json_path)) {
            return $json_path;
        }
        return false;
    }
}
