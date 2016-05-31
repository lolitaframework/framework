<?php
namespace ECG\LolitaFramework\Controls\Media;

use \ECG\LolitaFramework\Controls\Control as Control;
use \ECG\LolitaFramework\Core\HelperImage as HelperImage;

class Media extends Control
{
    /**
     * Control constructor
     * @param string $name control name.
     */
    public function __construct($name)
    {
        parent::setName($name);
        add_action('admin_enqueue_scripts', array(&$this, 'addScriptsAndStyles'));
    }

    /**
     * Add scripts and styles
     */
    public function addScriptsAndStyles()
    {
        // ==============================================================
        // Styles
        // ==============================================================
        wp_enqueue_style('lolita-media-control', $this->getURL() . '/assets/css/media.css');

        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script(
            'lolita-media-control',
            $this->getURL() . '/assets/js/media.js',
            array('jquery'),
            false,
            true
        );
    }

    /**
     * Render our control
     * @return string HTML control code.
     */
    public function render()
    {
        $value      = $this->getValue();
        $src        = HelperImage::getURL($value);
        $attachment = get_post($value);

        $this->view_data = array(
            'add_button_hide' => $this->addButtonHide(),
            'preview_hide'    => $this->previewHide(),
            'value'           => $value,
            'src'             => $src,
            'title'           => $this->getAttachmentTitle($value),
        );
        $this->setAttributes(
            array(
                'name'            => $this->getName(),
                'value'           => $value,
                'type'            => 'hidden',
            )
        );
        return parent::render();
    }

    /**
     * Get attachment title
     * @param  integer $post_id post id
     * @return [type]        [description]
     */
    private function getAttachmentTitle($post_id)
    {
        $post = get_post($post_id);
        if (null !== $post) {
            return $post->post_title;
        }
        return '';
    }

    /**
     * Hide add button
     * @return string css class.
     */
    private function addButtonHide()
    {
        return '' === $this->getValue() ? '' : 'hide';
    }

    /**
     * Hide preview
     * @return string css class.
     */
    private function previewHide()
    {
        return '' === $this->getValue() ? 'hide' : '';
    }
}
