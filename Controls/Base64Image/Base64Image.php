<?php
namespace lolita\LolitaFramework\Controls\Base64Image;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Core\Arr;

class Base64Image extends Control
{
    /**
     * Render control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string html code.
     */
    public function render()
    {
        if (false === $this->checkCondition()) {
            return '';
        }
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
