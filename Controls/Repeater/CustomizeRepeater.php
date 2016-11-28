<?php
namespace lolita\LolitaFramework\Controls\Repeater;

use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Controls\Controls;

/**
 * Customize Media Control class.
 *
 * @since 4.2.0
 *
 * @see WP_Customize_Control
 */
class CustomizeRepeater extends \WP_Customize_Control
{
    /**
     * Control type.
     *
     * @since 4.2.0
     * @access public
     * @var string
     */
    public $type = 'repeater';

    /**
     * Control
     */
    private $control = null;

    /**
     * Constructor.
     *
     * @param WP_Customize_Manager $manager Customizer bootstrap instance.
     * @param string               $id      Control ID.
     * @param array                $args    Optional. Arguments to override class property defaults.
     */
    public function __construct($manager, $id, $args = array())
    {
        parent::__construct($manager, $id, $args);
        $this->control = new Repeater(
            $this->settings['default']->id,
            Arr::get($args, 'controls', array()),
            $this->value(),
            array(
                'data-customize-setting-link' => $this->settings['default']->id,
            ),
            $this->label,
            $this->description
        );
    }

    /**
     * Enqueue control related scripts/styles.
     */
    public function enqueue()
    {
        wp_enqueue_media();
        Repeater::adminEnqueue();
        wp_enqueue_script(
            'jquery-bbq',
            Url::toUrl(dirname(__DIR__) . DS . 'assets' . DS . 'js' . DS . 'jquery.ba-bbq.min.js'),
            array('jquery')
        );
        wp_enqueue_script(
            'lolita-customize-repeater-control',
            Url::toUrl(__DIR__ . DS . 'assets' . DS . 'js' . DS . 'customize_repeater.js'),
            array('jquery', 'lolita-repeater-control', 'customize-base', 'customize-controls')
        );

        Controls::adminEnqueue($this->control->controls);
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 3.4.0
     * @since 4.2.0 Moved from WP_Customize_Upload_Control.
     *
     * @see WP_Customize_Control::to_json()
     */
    public function to_json()
    {
        parent::to_json();
        $this->json['label'] = html_entity_decode($this->label, ENT_QUOTES, get_bloginfo('charset'));
        $this->json['canUpload'] = current_user_can('upload_files');

        $value = $this->value();
    }

    /**
     * Don't render any content for this control from PHP.
     *
     * @since 3.4.0
     * @since 4.2.0 Moved from WP_Customize_Upload_Control.
     *
     * @see WP_Customize_Media_Control::content_template()
     */
    public function render_content()
    {
        echo View::make(
            __DIR__ . DS . 'views' . DS . 'customize_repeater.php',
            array(
                'control' => $this->control,
            )
        );
    }

    /**
     * Render a JS template for the content of the media control.
     *
     * @since 4.1.0
     * @since 4.2.0 Moved from WP_Customize_Upload_Control.
     */
    public function content_template()
    {
    }
}
