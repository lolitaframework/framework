<?php
namespace lolita\LolitaFramework\Controls\Icons;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Controls\IHaveAdminEnqueue;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework;
use \lolita\LolitaFramework\Core\Url;

class Icons extends Control implements iHaveAdminEnqueue
{
    /**
     * Icon packs
     * @var array
     */
    public $packs = array();

    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     * @param mixed $value contro value.
     * @param array $attributes control attributes.
     * @param string $lable control label.
     * @param string $descriptions control description.
     */
    public function __construct($name, $value = '', $attributes = array(), $label = '', $description = '')
    {
        parent::__construct($name, $value, $attributes, $label, $description);
        $this->packs = self::getPacks();
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
                    'id'                          => $this->getID(),
                    'type'                        => 'text',
                    'data-customize-setting-link' => $this->getName(),
                )
            )
        );
        if (array_key_exists('class', $this->attributes)) {
            $this->attributes['class'] .= ' lf_icons_control';
        } else {
            $this->attributes['class'] = 'lf_icons_control';
        }

        return parent::render();
    }

    /**
     * Get all packs
     *
     * @return array
     */
    public static function getPacks()
    {
        $packs = array();
        $data_files = self::getDataFiles();
        foreach ($data_files as $file) {
            $pack = new Pack($file);
            $packs[ $pack->getName() ] = $pack;
        }
        return $packs;
    }

    /**
     * Get data files
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array data files.
     */
    public static function getDataFiles()
    {
        return (array) glob(__DIR__ . DS . 'data' . DS . '*.json');
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
            'lf-icons-control',
            Url::toUrl(__DIR__) . '/assets/css/icons.css'
        );
        wp_enqueue_style(
            'lf-controls',
            self::getURL() . '/assets/css/controls.css'
        );

        // ==============================================================
        // Scripts
        // ==============================================================
        wp_enqueue_script('jquery');
        wp_enqueue_script(
            'lf-icons-control',
            Url::toUrl(__DIR__) . '/assets/js/icon_control.js',
            array(),
            false,
            true
        );

        wp_enqueue_script(
            'lolita-customize-icons-control',
            Url::toUrl(__DIR__ . DS . 'assets' . DS . 'js' . DS . 'customize_icons.js'),
            array('jquery', 'lf-icons-control', 'customize-base', 'customize-controls')
        );

        foreach ((array) self::getPacks() as $pack) {
            wp_enqueue_style($pack->getName(), $pack->getURL());
        }
    }
}
