<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Core\Arr;

class Columns implements IModule
{
    /**
     * All columns
     * @var array
     */
    private $columns = array();

    /**
     * Images class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = (array) $data;
        if (is_array($this->data)) {
            foreach ($this->data as $col) {
                if (!$this->checkCondition($data)) {
                    continue;
                }

                $this->columns[] = Ref::create(
                    __NAMESPACE__ . NS . 'Columns' . NS . Arr::get($col, 'type', 'PostType'),
                    $col
                );
            }
        }
    }

    /**
     * Check condition
     *
     * @param  array $data
     * @return bool
     */
    public function checkCondition(array $data)
    {
        if (array_key_exists('condition', $data) && is_callable($data['condition'])) {
            return $data['condition']();
        }
        return true;
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
