<?php
namespace ECG\LolitaFramework\Controls\Gallery;

use \ECG\LolitaFramework\Controls\Control as Control;
use \ECG\LolitaFramework\Core\HelperImage as HelperImage;
use \ECG\LolitaFramework\Core\HelperArray as HelperArray;

class Gallery extends Control
{
    /**
     * Control constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        add_action(
            'admin_enqueue_scripts',
            array(&$this, 'addScriptsAndStyles')
        );
    }

    /**
     * Add scripts and styles
     */
    public function addScriptsAndStyles()
    {
        // ==============================================================
        // Styles
        // ==============================================================
        wp_enqueue_style(
            'lolita-gallery-control',
            $this->getURL() . '/assets/css/gallery.css'
        );

        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script(
            'lolita-gallery-control',
            $this->getURL() . '/assets/js/gallery.js',
            array('jquery', 'underscore'),
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
        $this->attributes['value'] = '';
        $this->parameters['l10n'] = HelperArray::l10n(
            'lolita_gallery_control_l10n',
            array(
                'items' => $this->getItems(),
            )
        );
        return parent::render();
    }

    /**
     * All gallery items
     * @return array all items.
     */
    public function getItems()
    {
        $result = array();
        $values = $this->getValue();
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $post = get_post((int) $value);
                if (null !== $post) {
                    $post->src = HelperImage::getURL($value);
                    array_push($result, $post);
                }
            }
        }
        return $result;
    }
}
