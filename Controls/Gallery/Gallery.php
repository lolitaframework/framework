<?php
namespace duidluck\LolitaFramework\Controls\Gallery;

use \duidluck\LolitaFramework\Controls\Control;
use \duidluck\LolitaFramework\Controls\IHaveAdminEnqueue;
use \duidluck\LolitaFramework\Core\HelperImage;
use \duidluck\LolitaFramework\Core\HelperArray;
use \duidluck\LolitaFramework\Core\HelperString;
use \duidluck\LolitaFramework\Core\View;
use \duidluck\LolitaFramework as LolitaFramework;

class Gallery extends Control implements iHaveAdminEnqueue
{
    /**
     * Control constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->parameters['ID'] = $this->getID();
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
        $this->parameters['items'] = $this->getItems($values);
        $this->parameters['template'] = base64_encode(
            View::make(
                __DIR__ . DS . 'views' . DS . 'template.php',
                $this->parameters
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
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $p = get_post((int) $value);
                if (null !== $p) {
                    $p->src = HelperImage::getURL($p->ID);
                    array_push($result, $p);
                }
            }
        }
        return $result;
    }
}
