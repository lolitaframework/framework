<?php
namespace MyProject\LolitaFramework\Controls\Gallery;

use \MyProject\LolitaFramework\Controls\Control;
use \MyProject\LolitaFramework\Controls\IHaveAdminEnqueue;
use \MyProject\LolitaFramework\Core\HelperImage;
use \MyProject\LolitaFramework\Core\HelperArray;
use \MyProject\LolitaFramework\Core\HelperString;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework as LolitaFramework;

class Gallery extends Control implements iHaveAdminEnqueue
{
    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->parameters['ID'] = $this->getID();
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
