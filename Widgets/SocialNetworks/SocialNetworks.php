<?php
namespace redbrook\LolitaFramework\Widgets\SocialNetworks;

use \redbrook\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls as AbstractWithControls;
use \redbrook\LolitaFramework\Core\View as View;
use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;
use \redbrook\LolitaFramework as LolitaFramework;

class SocialNetworks extends AbstractWithControls{
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            __('Lolita Social networks', 'lolita'),
            array('description' => __('Social networks widget', 'lolita'))
        );
        add_action('wp_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
    }

    /**
     * Add scripts and styles
     */
    public function addScriptsAndStyles()
    {
        $font_awesome_url = 
            LolitaFramework::getURLByDirectory(__DIR__) . DS .
            'assets' . DS .
            'font-awesome-4.6.3' . DS .
            'css' . DS .
            'font-awesome.min.css';
       
        wp_enqueue_style('font-awesome', $font_awesome_url);
    }

    /**
     * Get controls data
     * @return array data to generate controls.
     */
    public function getControlsData()
    {
        return array(
            array(
                "name"     => "twitter",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "Twitter",
            ),
            array(
                "name"     => "facebook",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "Facebook",
            ),
            array(
                "name"     => "google-plus",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "Google plus",
            ),
            array(
                "name"     => "vk",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "Vkontakte",
            ),
            array(
                "name"     => "instagram",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "Instagram",
            ),
            array(
                "name"     => "pinterest",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "Pinterest",
            ),
            array(
                "name"     => "youtube",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "YouTube",
            ),
            array(
                "name"     => "linkedin",
                "__TYPE__" => "Input",
                "type"     => "text",
                "label"    => "LinkedIn",
            ),
        );
    }

    /**
     * Get icons
     * @return array font awesome icons classes
     */
    private function getIconsClasses()
    {
        return array(
            'twitter'     => 'fa fa-twitter',
            'facebook'    => 'fa fa-facebook',
            'google-plus' => 'fa fa-google-plus',
            'vk'          => 'fa fa-vk',
            'instagram'   => 'fa fa-instagram',
            'pinterest'   => 'fa fa-pinterest-p',
            'youtube'     => 'fa fa-youtube',
            'linkedin'    => 'fa fa-linkedin',
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
    public function widget( $args, $instance ) {
        echo View::make(
            dirname(__FILE__) . DS . 'views' . DS . 'social_networks.php',
            array(
                'instance' => HelperArray::removeEmpty($instance),
                'args'     => $args,
                'icons'    => $this->getIconsClasses(),
                'id_base'  => $this->id_base,
            )
        );
    }
}
