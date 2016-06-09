<?php
namespace duidluck\LolitaFramework\Configuration\Modules;

use \duidluck\LolitaFramework\Configuration\Configuration as Configuration;
use \duidluck\LolitaFramework\Configuration\IModule as IModule;
use \duidluck\LolitaFramework\Core\HelperArray as HelperArray;

class Actions implements IModule
{
    /**
     * Actions class constructor
     *
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
     * @return Actions instance.
     */
    private function install()
    {
        foreach ($this->data as $data) {
            add_action($data[0], $data[1], $data[2], $data[3]);
        }
        return $this;
    }

    /**
     * Prepare data
     * @return Actions instance.
     */
    private function prepare()
    {
        foreach ($this->data as &$data) {
            // Priority
            $data[2] = HelperArray::get($data, 2, 10);

            // Accepted tags
            $data[3] = HelperArray::get($data, 3, 1);
        }
        return $this;
    }

    /**
     * Module priority
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
