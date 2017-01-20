<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Ref;

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
                $this->columns[] = Ref::create(
                    __NAMESPACE__ . NS . 'Elements' . NS . 'Column',
                    $col
                );
            }
        }
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
