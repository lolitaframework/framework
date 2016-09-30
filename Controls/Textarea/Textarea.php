<?php
namespace lolitatheme\LolitaFramework\Controls\Textarea;

use \lolitatheme\LolitaFramework\Controls\Control;
use \lolitatheme\LolitaFramework\Core\Arr;

class Textarea extends Control
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
                    'data-customize-setting-link' => $this->getName(),
                )
            )
        );
        return parent::render();
    }
}
