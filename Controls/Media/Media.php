<?php
namespace ECG\LolitaFramework\Controls\Media;

use \ECG\LolitaFramework\Controls\Control as Control;
use \ECG\LolitaFramework\Core\HelperImage as HelperImage;
use \ECG\LolitaFramework\Core\HelperArray as HelperArray;

class Media extends Control
{
    /**
     * Control constructor
     * @param string $name control name.
     */
    public function __construct(array $parameters)
    {
        $parameters['value'] = HelperArray::get($parameters, 'value', '');
        $parameters['type']  = HelperArray::get($parameters, 'type', 'hidden');
        parent::__construct($parameters);
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
        wp_enqueue_style('lolita-controls', self::controlURL() . '/assets/css/controls.css');

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
     * Get allowed attributes
     * @return array allowed list.
     */
    private function getAllowedAttributes()
    {
        return array(
            'type',
            'name',
            'value',
        );
    }

    /**
     * Render control
     * @return string html code.
     */
    public function render()
    {
        $value      = $this->getValue();
        $attributes = HelperArray::leaveRightKeys(
            $this->getAllowedAttributes(),
            $this->parameters
        );
        $this->parameters['attributes_str'] = HelperArray::join($attributes);

        $this->parameters['src']   = HelperImage::getURL($value);
        $this->parameters['title'] = $this->getAttachmentTitle($value);
        $this->parameters['add_button_hide'] = $this->addButtonHide();
        $this->parameters['preview_hide'] = $this->previewHide();
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
