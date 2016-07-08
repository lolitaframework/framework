<?php
namespace MyProject\LolitaFramework\Controls\Select;

use \MyProject\LolitaFramework\Controls\Control as Control;
use \MyProject\LolitaFramework\Core\HelperArray as HelperArray;
use \MyProject\LolitaFramework\Core\HelperString as HelperString;

class Select extends Control
{
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
        );
    }

    /**
     * Prepare options
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
