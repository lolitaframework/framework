<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\GoogleMap;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperImage;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework;

class GoogleMap extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita Google Map widget', 'lolita'),
            array(
                'description' => __('Lolita Google map widget', 'lolita'),
                'classname' => 'lf_google_map',
            )
        );
    }

    /**
     * This function run before widgets_init hook
     * @return void
     */
    public static function beforeInit()
    {
        parent::beforeInit();
        add_action('wp_enqueue_scripts', array(__CLASS__, 'wpAddScriptsAndStyles'));
    }

    /**
     * Add scripts and styles to wp admin
     */
    public static function wpAddScriptsAndStyles()
    {
        $assets = LolitaFramework::getURLByDirectory(__DIR__) . DS . 'assets' . DS;
        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'lf_google_map',
            $assets . 'js' . DS . 'lf_google_map.js',
            array('jquery'),
            false,
            true
        );
    }

    /**
     * Get controls data
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array(
            array(
                "name"     => "api_key",
                "__TYPE__" => "input",
                "label"    => "API key",
                'required' => 'required',
            ),
            array(
                "name"     => "address",
                "__TYPE__" => "Textarea",
                "label"    => "Address",
            ),
            array(
                "name"     => "pin_img",
                "__TYPE__" => "Media",
                "label"    => "Pin image",
            ),

        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        $pin_img_id = HelperArray::get($instance, 'pin_img');
        $pin_img    = HelperImage::getURL($pin_img_id);

        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . 'google_map.php',
            array(
                'address' => HelperArray::get($instance, 'address'),
                'pin_id'  => $pin_img_id,
                'pin_img' => $pin_img,
                'api_key' => HelperArray::get($instance, 'api_key'),
            )
        );
    }
}
