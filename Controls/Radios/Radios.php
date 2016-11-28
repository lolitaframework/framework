<?php
namespace lolita\LolitaFramework\Controls\Radios;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Core\Arr;

class Radios extends Control
{
    /**
     * Radio options
     * @var array
     */
    public $options = array();

    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     * @param mixed $value contro value.
     */
    public function __construct($name, array $options, $value = '', $attributes = array(), $label = '', $description = '')
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setAttributes($attributes);
        $this->label       = $label;
        $this->description = $description;
        $this->options     = $options;
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
                    'type' => 'radio',
                    'id'   => '',
                    'data-customize-setting-link' => $this->getName(),

                )
            )
        );
        return parent::render();
    }

    /**
     * Get checkbox options
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array checkbox options.
     */
    public function getOptions()
    {
        $return = array();
        foreach ($this->options as $key => $value) {
            array_push(
                $return,
                array(
                    'key'     => $key,
                    'label'   => $value,
                    'id'      => sprintf('%s_%s', $this->getName(), $key),
                    'checked' => checked($this->value, $key, false),
                )
            );
        }
        return $return;
    }
}
