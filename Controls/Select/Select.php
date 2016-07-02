<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Select;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Control as Control;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray as HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString as HelperString;

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
