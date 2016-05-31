<?php
namespace ECG\LolitaFramework\Controls\Input;

use \ECG\LolitaFramework\Controls\Control as Control;

class Input extends Control
{
    /**
     * Input type
     * @var string
     */
    private $type = 'text';

    /**
     * Set input type
     * @param string $type new input type.
     */
    private function setType($type = 'text')
    {
        $allowed = $this->getAllowedTypes();
        if (in_array($type, $allowed)) {
            $this->type = $type;
        } else {
            $this->type = $allowed[0];
        }
    }

    /**
     * Get type
     * @return [string] input type.
     */
    private function getType()
    {
        return $this->type;
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
     * Render our control
     * @return string HTML control code.
     */
    public function render()
    {
        $this->setAttributes(
            array(
                'name'  => $this->getName(),
                'value' => $this->getValue(),
                'type'  => $this->getType(),
            )
        );
        return parent::render();
    }
}
