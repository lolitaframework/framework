<?php
namespace lolita\LolitaFramework\Controls\Text;

use \lolita\LolitaFramework\Controls\Control;
use \lolita\LolitaFramework\Core\Arr;

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
        if (false === $this->checkCondition()) {
            return '';
        }
        $this->setAttributes(
            array_merge(
                array(
                    'name'                        => $this->getName(),
                    'id'                          => $this->getId(),
                    'value'                       => esc_attr($this->getValue()),
                    'type'                        => 'text',
                    'data-customize-setting-link' => $this->getName(),
                ),
                $this->getAttributes()
            )
        );
        return parent::render();
    }
}
