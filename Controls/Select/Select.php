<?php
namespace lolita\LolitaFramework\Controls\Select;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Core\Arr;

class Select extends Control
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
        $this->setOptions($options);
        $this->label       = $label;
        $this->description = $description;
    }

    /**
     * Set options
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param mixed $options options to set. It can be array or function.
     */
    public function setOptions($options)
    {
        if (is_callable($options)) {
            $this->options = $options();
        } else {
            $this->options = $options;
        }
        return $this;
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
                    'data-customize-setting-link' => $this->getName(),
                )
            )
        );
        return parent::render();
    }
}
