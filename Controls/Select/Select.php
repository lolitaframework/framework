<?php
namespace redbrook\LolitaFramework\Controls\Select;

use \redbrook\LolitaFramework\Controls\Control as Control;
use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;
use \redbrook\LolitaFramework\Core\HelperString as HelperString;

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
     * Prepare options
     * @return void
     */
    private function prepareOptions()
    {
        if (array_key_exists('options', $this->parameters)) {
            if (!is_array($this->parameters['options'])) {
                $this->parameters['options'] = HelperString::compileVariables($this->parameters['options']);
            }
        } else {
            $this->parameters['options'] = array();
        }
    }

    /**
     * Render control
     * @return string html code.
     */
    public function render()
    {
        $this->prepareOptions();
        $attributes = HelperArray::leaveRightKeys(
            $this->getAllowedAttributes(),
            $this->parameters
        );
        $this->parameters['attributes_str'] = HelperArray::join($attributes);
        return parent::render();
    }
}
