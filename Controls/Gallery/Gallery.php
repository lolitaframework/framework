<?php
namespace duidluck\LolitaFramework\Controls\Gallery;

use \duidluck\LolitaFramework\Controls\Control as Control;
use \duidluck\LolitaFramework\Core\HelperImage as HelperImage;
use \duidluck\LolitaFramework\Core\HelperArray as HelperArray;
use \duidluck\LolitaFramework as LolitaFramework;

class Gallery extends Control
{
    /**
     * Control constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
    }

    /**
     * Add scripts and styles
     */
    public static function adminEnqueue()
    {
        // ==============================================================
        // Styles
        // ==============================================================
        wp_enqueue_style(
            'lolita-gallery-control',
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/css/gallery.css'
        );
        wp_enqueue_style(
            'lolita-controls',
            self::getURL() . '/assets/css/controls.css'
        );

        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script(
            'lolita-gallery-control',
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/js/gallery.js',
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
        $values = $this->getValue();
        $this->parameters['value'] = '';
        $this->parameters['l10n'] = HelperArray::l10n(
            'lolita_gallery_control_l10n',
            array(
                'items' => $this->getItems($values),
            )
        );
        return parent::render();
    }

    /**
     * All gallery items
     * @return array all items.
     */
    public function getItems($values)
    {
        $result = array();
        foreach ($values as $key => $value) {
            $p = get_post((int) $value);
            if (null !== $p) {
                $p->src = HelperImage::getURL($p->ID);
                array_push($result, $p);
            }
        }
        return $result;
    }
}
