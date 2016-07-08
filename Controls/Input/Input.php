<?php
namespace MyProject\LolitaFramework\Controls\Input;

use \MyProject\LolitaFramework\Controls\Control;
use \MyProject\LolitaFramework\Core\HelperArray;

class Input extends Control
{
    /**
     * Input constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->prepareType();
    }

    /**
     * Prepare Input type
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Input instance.
     */
    private function prepareType()
    {
        $allowed = $this->getAllowedTypes();
        $this->parameters['type'] = $allowed[0];
        if (array_key_exists('type', $this->parameters)) {
            if (in_array($this->parameters['type'], $allowed)) {
                $this->parameters['type'] = $this->parameters['type'];
            }
        }
        return $this;
    }

    /**
     * Get all allowed types
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array allowed types.
     */
    private function getAllowedTypes()
    {
        return array(
            'text',
            'button',
            'checkbox',
            'file',
            'hidden',
            'image',
            'password',
            'radio',
            'reset',
            'submit',
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
        $attributes = HelperArray::leaveRightKeys(
            $this->getAllowedAttributes(),
            $this->parameters
        );
        $this->parameters['attributes_str'] = HelperArray::join($attributes);
        return parent::render();
    }
}
