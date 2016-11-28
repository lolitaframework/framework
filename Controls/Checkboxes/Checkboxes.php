<?php
namespace lolita\LolitaFramework\Controls\Checkboxes;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Core\Arr;

class Checkboxes extends Control
{
    /**
     * Checkboxes options
     * @var array
     */
    public $options = array();

    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     * @param mixed $value contro value.
     * @param array $attributes control attributes.
     * @param string $label label.
     * @param string $description description.
     * @param array $options checkboes {[key1 => value1], [key2 => value2]}
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
                    'name'                        => '',
                    'type'                        => 'checkbox',
                    'id'                          => '',
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
                    'checked' => checked(Arr::get($this->value, $key), 'on', false),
                    'name'    => sprintf('%s[%s]', $this->getName(), $key),
                )
            );
        }
        return $return;
    }
}
