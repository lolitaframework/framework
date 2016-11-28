<?php
namespace lolita\LolitaFramework\Controls\Repeater;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Controls\Controls;
use \lolita\LolitaFramework\Controls\IHaveAdminEnqueue;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework;
use \lolita\LolitaFramework\Core\View;

class Repeater extends Control implements iHaveAdminEnqueue
{
    /**
     * Repeater controls
     * @var null
     */
    public $controls = null;

    /**
     * [$rows description]
     * @var array
     */
    public $rows = array();

    /**
     * Controls for template
     * @var array
     */
    public $template_controls = array();

    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     * @param mixed $value contro value.
     */
    public function __construct($name, array $controls, $value = '', $attributes = array(), $label = '', $description = '')
    {
        $this->controls = $controls;
        parent::__construct($name, $value, $attributes, $label, $description);
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
            'lolita-repeater-control',
            Url::toUrl(__DIR__) . '/assets/css/repeater.css'
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
            'lolita-repeater-control',
            Url::toUrl(__DIR__) . '/assets/js/repeater.js',
            array('jquery', 'underscore'),
            false,
            true
        );
    }

    /**
     * Prepare control data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Repeater instance
     */
    private function prepare()
    {
        for ($i = 1; $i <= $this->getValueCount(); $i++) {
            $value_by_index = Arr::get($this->value, $i, array());
            foreach ($this->controls as $control) {
                // ==============================================================
                // Set name with prefix
                // ==============================================================
                $control['small_name'] = $control['name'];
                $control['name'] = sprintf(
                    '%s[%s][%s]',
                    $this->getName(),
                    $i,
                    $control['name']
                );
                // ==============================================================
                // Set value
                // ==============================================================
                $control['value'] = Arr::get($value_by_index, $control['small_name'], '');
                // ==============================================================
                // Fill new attributes
                // ==============================================================
                $control['attributes'] = Arr::get($control, 'attributes', array());
                $control['attributes'] = array_merge(
                    array(
                        'class' => 'widefat',
                    ),
                    $control['attributes']
                );
                $this->rows[ $i ][] = $control;
            }
            $controls = new Controls;
            $this->rows[ $i ] = $controls->generateControls(
                (array) $this->rows[ $i ]
            );
        }
        // ==============================================================
        // Prepare controls for underscore template
        // ==============================================================
        foreach ($this->controls as $template_control) {
            // ==============================================================
            // Set name with prefix
            // ==============================================================
            $template_control['small_name'] = $template_control['name'];
            $template_control['name'] = sprintf(
                '%s[%s][%s]',
                $this->getName(),
                '__row_index__',
                $template_control['name']
            );
            // ==============================================================
            // Fill new attributes
            // ==============================================================
            $template_control['attributes'] = Arr::get($template_control, 'attributes', array());
            $template_control['attributes'] = array_merge(
                array(
                    'class' => 'widefat',
                ),
                $template_control['attributes']
            );
            $this->template_controls[] = $template_control;
        }

        $controls = new Controls;
        $this->template_controls = $controls->generateControls(
            (array) $this->template_controls
        );
        return $this;
    }

    /**
     * Get template
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return strign underscore template.
     */
    public function getTemplate()
    {
        return base64_encode(
            View::make(
                __DIR__ . DS . 'views' . DS . 'template.php',
                array('me' => $this)
            )
        );
    }

    /**
     * Get value count
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return integer count.
     */
    private function getValueCount()
    {
        return max(1, count((array) $this->getValue()));
    }

    /**
     * Render control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string html code.
     */
    public function render()
    {
        $this->prepare();
        return parent::render();
    }
}
