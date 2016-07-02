<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Textarea;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls\Control as Control;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray as HelperArray;

class Textarea extends Control
{
    /**
     * Textarea constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);
    }

    /**
     * Get allowed attributes
     * @return array allowed list.
     */
    private function getAllowedAttributes()
    {
        return array(
            'name',
            'class',
            'id',
            'rows',
            'cols'
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
