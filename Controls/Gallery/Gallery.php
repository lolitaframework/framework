<?php
namespace lolita\LolitaFramework\Controls\Gallery;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Controls\IHaveAdminEnqueue;
use \lolita\LolitaFramework\Core\Img;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework;

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
            Url::toUrl(__DIR__) . '/assets/css/gallery.css'
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
            Url::toUrl(__DIR__) . '/assets/js/gallery.js',
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
                    $p->src = Img::url($p->ID);
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
                    'name'                        => $this->getName(),
                    'data-customize-setting-link' => $this->getName(),
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
