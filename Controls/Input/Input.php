<?php
namespace ECG\LolitaFramework\Controls\Input;

use \ECG\LolitaFramework\Controls\Control as Control;
use \ECG\LolitaFramework\Core\HelperArray as HelperArray;

class Input extends Control
{
    /**
     * Input constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
        $this->prepareType();
    }

    /**
     * Prepare Input type
     * @return Input instance.
     */
    private function prepareType()
    {
        $allowed = $this->getAllowedTypes();
        if (in_array($this->parameters['type'], $allowed)) {
            $this->parameters['type'] = $this->parameters['type'];
        } else {
            $this->parameters['type'] = $allowed[0];
        }
        return $this;
    }

    /**
     * Get all allowed types
     * @return [array] allowed types.
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
        );
    }

    /**
     * Render control
     * @return string html code.
     */
    public function render()
    {
        $attributes = HelperArray::leaveRightKeys(
            $this->getAllowedAttributes(),
            $this->parameters
        );
        $this->parameters['attributes_str'] = HelperArray::join($attributes);
        return parent::render();
    }
}
