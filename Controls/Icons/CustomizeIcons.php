<?php
namespace lolita\LolitaFramework\Controls\Icons;

/**
 * Customize Media Control class.
 *
 * @since 4.2.0
 *
 * @see WP_Customize_Control
 */
class CustomizeIcons extends \WP_Customize_Control
{
    /**
     * Control type.
     *
     * @since 4.2.0
     * @access public
     * @var string
     */
    public $type = 'icons';

    /**
     * Control
     * @var null
     */
    private $icons_control = null;

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
        $this->icons_control = new Icons(
            $this->settings['default']->id,
            $this->value(),
            array(),
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
        $this->icons_control->renderStylesForIcons();
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
        echo $this->icons_control->render();
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
