<?php
namespace MyProject\LolitaFramework\Controls\Icons;

use \MyProject\LolitaFramework\Controls\Control;
use \MyProject\LolitaFramework\Core\HelperArray;
use \MyProject\LolitaFramework\Controls\IHaveAdminEnqueue;
use \MyProject\LolitaFramework;

class Icons extends Control implements iHaveAdminEnqueue
{
    /**
     * Icon packs
     * @var array
     */
    public $packs = array();

    /**
     * Icons constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->parameters['type'] = 'text';

        $data_files = $this->getDataFiles();
        foreach ($data_files as $file) {
            $pack = new Pack($file);
            $this->packs[ $pack->getName() ] = $pack;
        }

        add_action('admin_footer', array(&$this, 'renderStylesForIcons'));
    }

    /**
     * Render css files
     * @return Icons instance
     */
    public function renderStylesForIcons()
    {
        foreach ($this->packs as $pack) {
            wp_enqueue_style($pack->getName(), $pack->getURL());
        }
        return $this;
    }

    /**
     * Get data files
     *
     * @return array data files.
     */
    private function getDataFiles()
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
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/css/icons.css'
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
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/js/icon_control.js',
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
            'class',
            'id',
            'value',
            'required',
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
        $this->parameters['id'] = $this->getID();
        $this->parameters['class'].= ' lf_icons_control';
        $attributes = HelperArray::leaveRightKeys(
            $this->getAllowedAttributes(),
            $this->parameters
        );
        $this->parameters['attributes_str'] = HelperArray::join($attributes);
        return parent::render();
    }
}
