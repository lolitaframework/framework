<?php
namespace ECG\LolitaFramework\Controls\Select;

use \ECG\LolitaFramework\Controls\Control as Control;
use \ECG\LolitaFramework\Core\HelperArray as HelperArray;

class Select extends Control
{
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
        );
    }

    /**
     * Render control
     * @return string html code.
     */
    public function render()
    {
        $this->parameters['options'] = HelperArray::get($this->parameters, 'options', array());
        $attributes = HelperArray::leaveRightKeys(
            $this->getAllowedAttributes(),
            $this->parameters
        );
        $this->parameters['attributes_str'] = HelperArray::join($attributes);
        return parent::render();
    }
}
