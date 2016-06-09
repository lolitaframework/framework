<?php
namespace duidluck\LolitaFramework\Configuration\Modules;

use \duidluck\LolitaFramework\Core\HelperString as HelperString;
use \duidluck\LolitaFramework\Configuration\Configuration as Configuration;
use \duidluck\LolitaFramework\Configuration\IModule as IModule;

class Assets implements IModule
{

    /**
     * Key prefix.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Save the data list
     *
     * @var array
     */
    private $data = array();

    /**
     * Assets class constructor
     *
     * @param string $data config file data.
     * @return void
     */
    public function __construct($data = null)
    {
        $this->data = $data;

        if (null !== $this->data) {
            add_action('wp_enqueue_scripts', array( $this, 'enqueue' ));
            add_action('admin_enqueue_scripts', array( $this, 'enqueue' ));
            add_action('login_enqueue_scripts', array( $this, 'enqueue' ));
        } else {
            throw new \Exception(__('JSON can be converted to Array', 'lolita'));
        }
    }

    /**
     * Get allowed keys to assets config
     *
     * @return array
     */
    public function getAllowedKeys()
    {
        $array = array(
            'deregister_scripts',
            'scripts',
            'styles',
            'localize',
            'custom',
        );
        return array_map(array(&$this, 'addPrefix'), $array);
    }

    /**
     * Add prefix to element
     *
     * @param [string] $el element.
     */
    public function addPrefix($el)
    {
        return $this->prefix.$el;
    }

    /**
     * Get prefix from action name
     *
     * @param  [string] $action name.
     * @return [string] prefix.
     */
    private function getPrefixFromAction($action)
    {
        $pieces = explode('_', $action);
        if ('wp' === $pieces[0]) {
            return '';
        }
        return $pieces[0] . '_';
    }

    /**
     * Run by wp_enqueue_scripts action
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue()
    {
        $this->prefix      = $this->getPrefixFromAction(current_filter());
        $allowed_functions = $this->getAllowedKeys();
        if (is_array($this->data) && count($this->data)) {
            foreach ($this->data as $func => $data) {
                if (in_array($func, $allowed_functions)) {
                    $func = str_replace($this->prefix, '', $func);
                    $called_func = HelperString::snakeToCamel($func);
                    $this->$func($data);
                }
            }
        }
    }

    /**
     * Deregistered some scripts
     *
     * @param [type] $handles deregister script handles.
     * @return void
     */
    public function deregisterScripts($handles)
    {
        if (is_array($handles) && count($handles)) {
            foreach ($handles as $handle) {
                wp_deregister_script($handle);
            }
        }
    }

    /**
     * Enqueue scripts
     *
     * @param  array $scripts parameters.
     * @return void
     */
    public function scripts($scripts)
    {
        $defaults = array('', false, array(), false, false);
        if (is_array($scripts) && count($scripts)) {
            foreach ($scripts as $script) {
                list($handle, $src, $deps, $ver, $in_footer) = $script + $defaults;
                wp_enqueue_script($handle, HelperString::compileVariables($src), $deps, $ver, $in_footer);
            }
        }
    }

    /**
     * Enqueue styles
     *
     * @param  array $styles parameters.
     * @return void
     */
    public function styles($styles)
    {
        $defaults = array( '', false, array(), false, 'all' );
        if (is_array($styles) && count($styles)) {
            foreach ($styles as $style) {
                list($handle, $src, $deps, $ver, $media) = $style + $defaults;
                wp_enqueue_style($handle, HelperString::compileVariables($src), $deps, $ver, $media);
            }
        }
    }

    /**
     * Localize scripts
     *
     * @param  array $localizes parameters.
     * @return void
     */
    public function localize($localizes)
    {
        $class_name = get_class(new HelperString);
        if (is_array($localizes) && count($localizes)) {
            foreach ($localizes as $localize) {
                if (is_array($localize) && 3 == count($localize)) {
                    list($handle, $object_name, $l10n) = $localize;
                    $l10n = array_map(
                        array(
                            $class_name,
                            'compileVariables'
                        ),
                        $l10n
                    );
                    wp_localize_script($handle, $object_name, $l10n);
                }
            }
        }
    }

    /**
     * Run custom functions in wp_enqueue_scripts action
     *
     * @param array $functions custom function.
     * @return void
     */
    public function custom($functions)
    {
        if (is_array($functions) && count($functions)) {
            foreach ($functions as $f) {
                if (is_callable($f)) {
                    $f();
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
