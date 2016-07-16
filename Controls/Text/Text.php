<?php
namespace MyProject\LolitaFramework\Controls\Text;

use \MyProject\LolitaFramework\Controls\Control;
use \MyProject\LolitaFramework\Core\Arr;

class Text extends Control
{
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
                    'name'  => $this->getName(),
                    'value' => $this->getValue(),
                    'type'  => 'text',
                )
            )
        );
        return parent::render();
    }
}
