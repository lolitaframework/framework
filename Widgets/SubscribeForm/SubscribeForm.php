<?php
namespace redbrook\LolitaFramework\Widgets\SubscribeForm;

use \redbrook\LolitaFramework\Widgets\AbstractWithControls\AbstractWithControls as AbstractWithControls;
use \redbrook\LolitaFramework\Core\View as View;
use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;
use redbrook\LolitaFramework as LolitaFramework;

class SubscribeForm extends AbstractWithControls
{
    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            __('Lolita subscribe form', 'lolita'),
            array('description' => __('Subscribe form widget', 'lolita'))
        );
        add_action('wp_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
        add_action('wp_ajax_lolita_subscribe', array(&$this, 'subscribe'));
        add_action('wp_ajax_nopriv_lolita_subscribe', array(&$this, 'subscribe'));
    }

    /**
     * Subscribe our user
     */
    public function subscribe()
    {
        $result = wp_mail(
            get_bloginfo('admin_email'),
            'New subscriber',
            'Thank you for leave us your email!'
        );
        if (true === $result) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
        
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
            'lolita-widget-subscribe-form',
            $assets . 'js' . DS . 'lolita_widget_subscribe_form.js',
            array('jquery'),
            false,
            true
        );

        // ==============================================================
        // Localize
        // ==============================================================
        wp_localize_script(
            'lolita-widget-subscribe-form',
            'lolita_widget_subscribe_form',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'security' => LF_NONCE,
            )
        );
    }

    /**
     * Get controls data
     * @return array data to generate controls.
     */
    public function getControlsData()
    {
        return array(
            array(
                'name'     => 'title',
                '__TYPE__' => 'Input',
                'type'     => 'text',
                'label'    => 'Title',
            ),
            array(
                'name'     => 'description',
                '__TYPE__' => 'Textarea',
                'type'     => 'text',
                'label'    => 'Description',
                'rows'     => '10',
            ),
            array(
                'name'     => 'success_message',
                '__TYPE__' => 'Textarea',
                'type'     => 'text',
                'label'    => 'Sueccess message',
                'rows'     => '10',
            ),
            array(
                'name'     => 'error_message',
                '__TYPE__' => 'Textarea',
                'type'     => 'text',
                'label'    => 'Error message',
                'rows'     => '10',
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
        $instance['success_message'] = HelperArray::get(
            $instance,
            'success_message',
            'Message sent successfully.'
        );
        $instance['error_message'] = HelperArray::get(
            $instance,
            'success_message',
            'Message not sent. Please contact the administrator for help.'
        );
        echo View::make(
            dirname(__FILE__) . DS . 'views' . DS . $this->id_base . '.php',
            array(
                'instance' => $instance,
                'args'     => $args,
                'id_base'  => $this->id_base,
            )
        );
    }
}
