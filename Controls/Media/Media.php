<?php
namespace MyProject\LolitaFramework\Controls\Media;

use \MyProject\LolitaFramework\Controls\Control;
use \MyProject\LolitaFramework\Controls\IHaveAdminEnqueue;
use \MyProject\LolitaFramework\Core\HelperImage;
use \MyProject\LolitaFramework\Core\HelperArray;
use \MyProject\LolitaFramework as LolitaFramework;

class Media extends Control implements iHaveAdminEnqueue
{
    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     */
    public function __construct(array $parameters)
    {
        $parameters['value'] = HelperArray::get($parameters, 'value', '');
        $parameters['type']  = HelperArray::get($parameters, 'type', 'hidden');
        parent::__construct($parameters);
    }

    /**
     * Add scripts and styles
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public static function adminEnqueue()
    {
        // ==============================================================
        // Styles
        // ==============================================================
        wp_enqueue_style('lolita-media-control', LolitaFramework::getURLByDirectory(__DIR__) . '/assets/css/media.css');
        wp_enqueue_style('lolita-controls', self::getURL() . '/assets/css/controls.css');

        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script(
            'lolita-media-control',
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/js/media.js',
            array('jquery'),
            false,
            true
        );
    }

    /**
     * Get allowed attributes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  integer $post_id post id
     * @return mixed
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string css class.
     */
    private function addButtonHide()
    {
        return '' === $this->getValue() ? '' : 'hide';
    }

    /**
     * Hide preview
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string css class.
     */
    private function previewHide()
    {
        return '' === $this->getValue() ? 'hide' : '';
    }
}
