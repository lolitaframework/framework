<?php
namespace MyProject\LolitaFramework\Controls\Gallery;

use \MyProject\LolitaFramework\Controls\Control;
use \MyProject\LolitaFramework\Controls\IHaveAdminEnqueue;
use \MyProject\LolitaFramework\Core\Img;
use \MyProject\LolitaFramework\Core\View;
use \MyProject\LolitaFramework;

class Gallery extends Control implements iHaveAdminEnqueue
{
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
     * All gallery items
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array all items.
     */
    public function getItems()
    {
        $values = $this->getValue();
        $result = array();
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $p = get_post((int) $value);
                if (null !== $p) {
                    $p->src = Img::getURL($p->ID);
                    array_push($result, $p);
                }
            }
        }
        return $result;
    }

    /**
     * Get template
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string underscore template.
     */
    public function getTemplate()
    {
        return base64_encode(View::make(__DIR__ . DS . 'views' . DS . 'template.php'));
    }

    /**
     * Render control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string html code.
     */
    public function render()
    {
        $this->setAttributes(
            array_merge(
                $this->getAttributes(),
                array(
                    'name' => $this->getName(),
                )
            )
        );
        return View::make(
            $this->getDefaultViewPath(),
            array(
                'me'    => $this,
                'items' => $this->getItems(),
                'name'  => $this->getName(),
            )
        );
    }
}
