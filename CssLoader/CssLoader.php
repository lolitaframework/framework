<?php
namespace duidluck\LolitaFramework\CssLoader;

use \duidluck\LolitaFramework as LolitaFramework;
use \duidluck\LolitaFramework\Core\View as View;

class CssLoader
{
    /**
     * CssLoader class constructor
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
        add_action('admin_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
        add_action('wp_head', array(&$this, 'renderTemplates'));
        add_action('admin_head', array(&$this, 'renderTemplates'));
    }

    /**
     * Add scripts and styles
     */
    public function addScriptsAndStyles()
    {
        $assets = LolitaFramework::getURLByDirectory(__DIR__) . DS . 'assets' . DS;
        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'lolita-css-loader',
            $assets . 'js' . DS . 'lolita_css_loader.js',
            array('jquery'),
            false,
            true
        );

        // ==============================================================
        // Styles
        // ==============================================================
        wp_enqueue_style(
            'lolita-css-loader',
            $assets . 'css' . DS . 'lolita_css_loader.css'
        );
    }

    /**
     * Add Loader templates
     */
    public function renderTemplates()
    {
        echo View::make(__DIR__ . DS . 'views' . DS . 'css_loader.php');
    }
}
