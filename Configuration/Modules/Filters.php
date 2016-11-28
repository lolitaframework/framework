<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Arr;

class Filters implements IModule
{
    /**
     * Filters class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        if (is_array($this->data)) {
            $this->prepare()->install();
        }
    }

    /**
     * Add shortcodes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Filters instance.
     */
    private function install()
    {
        foreach ($this->data as $data) {
            add_filter($data[0], $data[1], $data[2], $data[3]);
        }
        return $this;
    }

    /**
     * Prepare data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Filters instance.
     */
    private function prepare()
    {
        foreach ($this->data as &$data) {
            // Priority
            $data[2] = Arr::get($data, 2, 10);

            // Accepted tags
            $data[3] = Arr::get($data, 3, 1);
        }
        return $this;
    }

    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
