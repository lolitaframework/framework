<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Configuration\Init;
use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;

class Taxonomies extends Init implements IModule
{
    private $taxonomies = array();
    /**
     * Taxonomies class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        if (is_array($this->data)) {
            foreach ($this->data as $tax) {
                $this->taxonomies[] = Ref::create(
                    __NAMESPACE__ . NS . 'Elements' . NS . 'Taxonomy',
                    $tax
                );
            }
        }
        $this->init();
    }

    /**
     * Initialize new LF_Init class
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @throws Exception JSON can be converted to Array.
     * @return void
     */
    protected function init()
    {
        if (null !== $this->data) {
            add_action('init', [&$this, 'install'], 0);
        } else {
            throw new \Exception(__($this->init_exp_string, 'lolita'));
        }
    }

    /**
     * Add prefix to name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $prefix prefix.
     * @param  string $name   name.
     * @return string         name with prefix.
     */
    private function controlNameWithPrefix($prefix, $name)
    {
        return sprintf(
            '%s_%s',
            $prefix,
            $name
        );
    }

    /**
     * Run by the 'init' hook.
     * Execute the "add_theme_support" function from WordPress.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function install()
    {
        if (is_array($this->taxonomies)) {
            foreach ($this->taxonomies as $tax) {
                $tax->register();
            }
        }
    }

    /**
     * Module priority
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return 99;
    }
}
