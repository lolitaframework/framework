<?php
namespace lolitatheme\LolitaFramework\Controls\Text;

use \lolitatheme\LolitaFramework\Controls\Control;
use \lolitatheme\LolitaFramework\Core\Arr;

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
                    'name'                        => $this->getName(),
                    'id'                          => $this->getId(),
                    'value'                       => $this->getValue(),
                    'type'                        => 'text',
                    'data-customize-setting-link' => $this->getName(),
                )
            )
        );
        return parent::render();
    }
}
