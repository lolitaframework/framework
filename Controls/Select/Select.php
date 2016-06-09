<?php
namespace duidluck\LolitaFramework\Controls\Select;

use \duidluck\LolitaFramework\Controls\Control as Control;
use \duidluck\LolitaFramework\Core\HelperArray as HelperArray;
use \duidluck\LolitaFramework\Core\HelperString as HelperString;

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
