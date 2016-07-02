<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\CssLoader;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework as LolitaFramework;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View as View;

class CssLoader
{
    /**
     * CssLoader class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
        add_action('admin_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
        add_action('wp_footer', array(&$this, 'renderTemplates'));
        add_action('admin_footer', array(&$this, 'renderTemplates'));
    }

    /**
     * Add scripts and styles
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function renderTemplates()
    {
        echo View::make(__DIR__ . DS . 'views' . DS . 'css_loader.php');
    }
}
