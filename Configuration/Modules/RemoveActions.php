<?php
namespace lolitatheme\LolitaFramework\Configuration\Modules;

use \lolitatheme\LolitaFramework\Configuration\Configuration;
use \lolitatheme\LolitaFramework\Configuration\IModule;
use \lolitatheme\LolitaFramework\Core\Arr;

class RemoveActions extends Actions
{
    /**
     * Remove actions
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Actions instance.
     */
    protected function install()
    {
        foreach ($this->data as $data) {
            remove_action($data[0], $data[1], $data[2], $data[3]);
        }
        return $this;
    }
}
