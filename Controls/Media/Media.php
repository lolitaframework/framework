<?php
namespace lolita\LolitaFramework\Controls\Media;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Img;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework\Controls\IHaveAdminEnqueue;
use \lolita\LolitaFramework;

class Media extends Control implements iHaveAdminEnqueue
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
            'lf-media-control',
            Url::toUrl(__DIR__) . '/assets/css/media.css'
        );
        wp_enqueue_style(
            'lf-controls',
            self::getURL() . '/assets/css/controls.css'
        );

        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        wp_enqueue_script('underscore');
        wp_enqueue_script(
            'lf-media-control',
            Url::toUrl(__DIR__) . '/assets/js/media.js',
            array('jquery'),
            false,
            true
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
        $this->setAttributes(
            array_merge(
                $this->getAttributes(),
                array(
                    'name'                        => $this->getName(),
                    'type'                        => 'hidden',
                    'value'                       => $this->getValue(),
                    'data-customize-setting-link' => $this->getName(),
                )
            )
        );
        return View::make(
            $this->getDefaultViewPath(),
            array(
                'me'    => $this,
                'title' => $this->getAttachmentTitle($this->getValue()),
                'src'   => Img::url($this->getValue()),
            )
        );
    }

    /**
     * Get attachment title
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  integer $post_id post id
     * @return mixed
     */
    public function getAttachmentTitle($post_id)
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
    public function addButtonHide()
    {
        return '' === $this->getValue() ? '' : 'hide';
    }

    /**
     * Hide preview
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string css class.
     */
    public function previewHide()
    {
        return '' === $this->getValue() ? 'hide' : '';
    }
}
