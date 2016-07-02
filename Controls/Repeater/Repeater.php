<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Repeater;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Control;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Controls;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\IHaveAdminEnqueue;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View;

class Repeater extends Control implements iHaveAdminEnqueue
{
    /**
     * Repeater constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->parameters['me'] = $this;
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
            'lolita-repeater-control',
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/css/repeater.css'
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
            LolitaFramework::getURLByDirectory(__DIR__) . '/assets/js/repeater.js',
            array('jquery', 'underscore'),
            false,
            true
        );
    }

    /**
     * Get control value
     * @return string value.
     */
    public function getValue()
    {
        if (!array_key_exists('value', $this->parameters)) {
            $this->parameters['value'] = array();
        }
        return $this->parameters['value'];
    }

    /**
     * Prepare control data
     * @return Repeater instance
     */
    private function prepare()
    {
        $this->parameters['value']             = $this->getValue();
        $this->parameters['rows']              = array();
        $this->parameters['template_controls'] = array();

        if (!array_key_exists('controls', $this->parameters)) {
            return $this;
        }
        for ($i = 1; $i <= $this->getValueCount(); $i++) {
            $value_by_index = HelperArray::get($this->parameters['value'], $i, array());
            foreach ($this->parameters['controls'] as $control) {
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
                $control['value'] = HelperArray::get($value_by_index, $control['small_name']);
                // ==============================================================
                // Fill new attributes
                // ==============================================================
                $control = array_merge(
                    $control,
                    array(
                        'class' => 'widefat',
                    )
                );
                $this->parameters['rows'][ $i ][] = $control;
            }
            $controls = new Controls;
            $this->parameters['rows'][ $i ] = $controls->generateControls(
                (array) $this->parameters['rows'][ $i ]
            );
        }
        // ==============================================================
        // Prepare controls for underscore template
        // ==============================================================
        foreach ($this->parameters['controls'] as $template_control) {
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
            $template_control = array_merge(
                $template_control,
                array(
                    'class' => 'widefat',
                )
            );
            $this->parameters['template_controls'][] = $template_control;
        }

        $controls = new Controls;
        $this->parameters['template_controls'] = $controls->generateControls(
            (array) $this->parameters['template_controls']
        );

        return $this;
    }

    /**
     * Render control
     * @return string html code.
     */
    public function render()
    {
        if (array_key_exists('controls', $this->parameters)) {
            $this->prepare();
        } else {
            throw new \Exception('Repeater control must have at least on control!');
        }
        $this->parameters['template'] = base64_encode(
            View::make(
                __DIR__ . DS . 'views' . DS . 'template.php',
                $this->parameters
            )
        );
        return parent::render();
    }

    /**
     * Get value count
     *
     * @return integer count.
     */
    private function getValueCount()
    {
        $count = HelperArray::get($this->parameters, 'value', array());
        return max(1, count($count));
    }
}
