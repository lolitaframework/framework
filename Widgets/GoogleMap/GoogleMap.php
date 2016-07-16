<?php
namespace MyProject\LolitaFramework\Widgets\GoogleMap;

use \MyProject\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls;
use \MyProject\LolitaFramework\Core\Arr;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework\Core\Img;
use \MyProject\LolitaFramework;

class GoogleMap extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * Add scripts and styles to wp admin
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
        wp_localize_script(
            'lf_google_map',
            'lf_google_map_l10n',
            array(
                'is_mobile' => wp_is_mobile(),
                'styles'    => self::getGMapStyles(),
            )
        );
    }

    /**
     * Get gmap styles
     * @return array object.
     */
    public static function getGMapStyles()
    {
        return apply_filters(
            'lf_google_map_styles',
            json_decode(
                View::make(
                    __DIR__ . DS . 'views' . DS . 'default_styles.json'
                )
            )
        );
    }

    /**
     * Get controls data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array(
            array(
                "name"     => "api_key",
                "__TYPE__" => "Text",
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        self::wpAddScriptsAndStyles();

        $pin_img    = '';
        $pin_img_id = Arr::get($instance, 'pin_img');
        $api_key    = Arr::get($instance, 'api_key', 'AIzaSyCjQ9_UJojrZjefOFVVp6YBvoZ1Sd00_Lg');
        if ('' === $api_key) {
            $api_key = 'AIzaSyCjQ9_UJojrZjefOFVVp6YBvoZ1Sd00_Lg';
        }
        if ($pin_img_id) {
            $pin_img = Img::getURL($pin_img_id);
        }

        $this->view(
            dirname(__FILE__) . DS . 'views' . DS . 'google_map.php',
            array(
                'address' => Arr::get($instance, 'address'),
                'pin_id'  => $pin_img_id,
                'pin_img' => $pin_img,
                'api_key' => $api_key,
            )
        );
    }
}
