<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework;

class Assets implements IModule
{

    /**
     * Key prefix.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @var string
     */
    private $prefix = '';

    /**
     * Save the data list
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @var array
     */
    private $data = array();

    /**
     * Assets class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
            add_action('wp_footer', array( $this, 'enqueue' ));
            add_action('login_footer', array( $this, 'enqueue' ));
            add_action('customize_controls_enqueue_scripts', array( $this, 'enqueue' ));
        } else {
            throw new \Exception(__('JSON can be converted to Array', 'lolita'));
        }
        add_action('wp_footer', array(&$this, 'base'));
        add_action('admin_footer', array(&$this, 'base'));
        add_action('login_footer', array(&$this, 'base'));
        add_action('customize_controls_print_footer_scripts', array(&$this, 'base'));
    }

    /**
     * Base js
     *
     * @return void
     */
    public function base()
    {
        if (!defined('LF_BASE_DATA')) {
            define('LF_BASE_DATA', true);
            echo Arr::l10n(
                'lolita_framework',
                array(
                    'LF_NONCE'  => LF_NONCE,
                    'SITE_URL'  => SITE_URL,
                    'ADMIN_URL' => admin_url(),
                )
            );
        }
    }

    /**
     * Get allowed keys to assets config
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
            'customize',
        );
        return array_map(array(&$this, 'addPrefix'), $array);
    }

    /**
     * Add prefix to element
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param [string] $el element.
     */
    public function addPrefix($el)
    {
        return $this->prefix.$el;
    }

    /**
     * Get prefix from action name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  [string] $action name.
     * @return [string] prefix.
     */
    private function getPrefixFromAction($action)
    {
        $dictionary = array(
            'wp_footer'    => 'async_',
            'login_footer' => 'async_login_',
            'admin_footer' => 'async_admin_',
        );
        if (array_key_exists($action, $dictionary)) {
            return $dictionary[$action];
        }

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
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
                    $called_func = Str::camel($func);
                    $called_func = lcfirst($called_func);
                    $this->$called_func($data);
                }
            }
        }
    }

    /**
     * Deregistered some scripts
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $scripts parameters.
     * @return void
     */
    public function scripts($scripts)
    {
        $defaults = array('', false, array(), false, false);
        if (is_array($scripts) && count($scripts)) {
            foreach ($scripts as $script) {
                list($handle, $src, $deps, $ver, $in_footer) = $script + $defaults;
                wp_enqueue_script($handle, Data::interpret($src), $deps, $ver, $in_footer);
            }
        }
    }

    /**
     * Enqueue scripts
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $scripts parameters.
     * @return void
     */
    public function customizeScripts($scripts)
    {
        $defaults = array('', false, array(), false, false);
        if (is_array($scripts) && count($scripts)) {
            foreach ($scripts as $script) {
                list($handle, $src, $deps, $ver, $in_footer) = $script + $defaults;
                wp_enqueue_script($handle, Data::interpret($src), $deps, $ver, $in_footer);
            }
        }
    }

    /**
     * Enqueue styles
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $styles parameters.
     * @return void
     */
    public function styles($styles)
    {
        $defaults = array( '', false, array(), false, 'none' );
        if (is_array($styles) && count($styles)) {
            foreach ($styles as $style) {
                list($handle, $src, $deps, $ver, $media) = $style + $defaults;
                wp_enqueue_style($handle, Data::interpret($src), $deps, $ver, $media);
            }
        }
    }

    /**
     * Enqueue styles
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $styles parameters.
     * @return void
     */
    public function customizeStyles($styles)
    {
        $defaults = array( '', false, array(), false, 'all' );
        if (is_array($styles) && count($styles)) {
            foreach ($styles as $style) {
                list($handle, $src, $deps, $ver, $media) = $style + $defaults;
                wp_enqueue_style($handle, Data::interpret($src), $deps, $ver, $media);
            }
        }
    }

    /**
     * Localize scripts
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $localizes parameters.
     * @return void
     */
    public function localize($localizes)
    {
        $class_name = get_class(new Data);
        if (is_array($localizes) && count($localizes)) {
            foreach ($localizes as $localize) {
                if (is_array($localize) && 3 == count($localize)) {
                    list($handle, $object_name, $l10n) = $localize;
                    if (is_array($l10n)) {
                        $l10n = array_map(
                            array(
                                $class_name,
                                'interpret'
                            ),
                            $l10n
                        );
                    } else {
                        $l10n = Data::interpret($l10n);
                    }
                    wp_localize_script($handle, $object_name, $l10n);
                }
            }
        }
    }

    /**
     * Run custom functions in wp_enqueue_scripts action
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
