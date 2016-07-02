<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\SocialNetworks;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls as AbstractWithControls;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View as View;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray as HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework as LolitaFramework;

class SocialNetworks extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita Social networks', 'lolita'),
            array(
                'description' => __('Social networks widget', 'lolita'),
                'classname'   => 'lf_social_networks',
            )
        );
        
    }

    /**
     * This function run before widgets_init hook
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public static function beforeInit()
    {
        parent::beforeInit();
        add_action('wp_footer', array(__CLASS__, 'addScriptsAndStyles'));
    }

    /**
     * Add scripts and styles
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public static function addScriptsAndStyles()
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array data to generate controls.
     */
    public static function getControlsData()
    {
        return array(
            array(
                "name"     => "title",
                "__TYPE__" => "Input",
                "label"    => "Title",
            ),
            array(
                "name"     => "twitter",
                "__TYPE__" => "Input",
                "label"    => "Twitter",
            ),
            array(
                "name"     => "facebook",
                "__TYPE__" => "Input",
                "label"    => "Facebook",
            ),
            array(
                "name"     => "google-plus",
                "__TYPE__" => "Input",
                "label"    => "Google plus",
            ),
            array(
                "name"     => "vk",
                "__TYPE__" => "Input",
                "label"    => "Vkontakte",
            ),
            array(
                "name"     => "instagram",
                "__TYPE__" => "Input",
                "label"    => "Instagram",
            ),
            array(
                "name"     => "pinterest",
                "__TYPE__" => "Input",
                "label"    => "Pinterest",
            ),
            array(
                "name"     => "youtube",
                "__TYPE__" => "Input",
                "label"    => "YouTube",
            ),
            array(
                "name"     => "linkedin",
                "__TYPE__" => "Input",
                "label"    => "LinkedIn",
            ),
        );
    }

    /**
     * Get icons
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array font awesome icons classes.
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        $title = HelperArray::get($instance, 'title', '');
        $instance['title'] = '';
        echo View::make(
            dirname(__FILE__) . DS . 'views' . DS . 'social_networks.php',
            array(
                'title'    => $title,
                'instance' => HelperArray::removeEmpty($instance),
                'args'     => $args,
                'icons'    => $this->getIconsClasses(),
                'id_base'  => $this->id_base,
            )
        );
    }
}
