<?php
namespace MyProject\LolitaFramework\CssLoader;

use \MyProject\LolitaFramework;
use \MyProject\LolitaFramework\Core\HelperArray;
use \MyProject\LolitaFramework\Core\View;

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
        $this->addShortcodes();
    }

    /**
     * Add all shortcodes
     */
    public function addShortcodes()
    {
        add_shortcode('lf_css_loader_hide', array(&$this, 'renderHide'));
        for ($i = 1; $i <= 11; $i++) {
            add_shortcode('lf_css_loader_' . $i, array(&$this, 'renderTemplate'));
        }
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
    }

    /**
     * Add Loader templates
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function renderTemplates()
    {
        echo View::make(__DIR__ . DS . 'views' . DS . 'lf_css_loader.php');

        $assets = LolitaFramework::getURLByDirectory(__DIR__) . DS . 'assets' . DS;
        // ==============================================================
        // Styles
        // ==============================================================
        wp_enqueue_style(
            'lolita-css-loader',
            $assets . 'css' . DS . 'lolita_css_loader.css'
        );
    }

    /**
     * Render loader template
     * @param  array $atts attributes.
     * @param  mixed $tmp  some shit.
     * @param  string $tag  shortcode name.
     * @return string html code.
     */
    public function renderTemplate($atts, $tmp, $tag)
    {
        $GLOBALS['lf_start_time'] = microtime(true);
        return View::make(
            __DIR__ . DS . 'views' . DS . $tag . '.php',
            array(
                'class' => HelperArray::get($atts, 'class'),
                'bg'    => HelperArray::get($atts, 'bg', '#fff'),
                'color' => HelperArray::get($atts, 'color', '#000'),
            )
        );
    }

    /**
     * Hide loader
     * @param  array $atts attributes.
     * @return string html code.
     */
    public function renderHide($atts)
    {
        echo View::make(
            __DIR__ . DS . 'views' . DS . 'lf_css_loader_hide.php',
            array(
                'delay' => HelperArray::get($atts, 'delay', 0),
                'spent_time' => sprintf(
                    'Time spent %.4F seconds.',
                    microtime(true) - HelperArray::get($GLOBALS, 'lf_start_time', 0)
                ),
            )
        );
    }
}
