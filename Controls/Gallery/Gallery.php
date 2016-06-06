<?php
namespace redbrook\LolitaFramework\Controls\Gallery;

use \redbrook\LolitaFramework\Controls\Control as Control;
use \redbrook\LolitaFramework\Core\HelperImage as HelperImage;
use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;

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
        wp_enqueue_style(
            'lolita-controls',
            self::controlURL() . '/assets/css/controls.css'
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
