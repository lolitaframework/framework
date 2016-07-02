<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Controls;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString as HelperString;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperArray as HelperArray;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\View as View;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework as LolitaFramework;

abstract class Control
{
    /**
     * Data to insert in view
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @var array
     */
    public $parameters = array();

    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->setName(HelperArray::get($parameters, 'name'));
    }

    /**
     * Set name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     */
    public function setName($name)
    {
        if ('' === trim($this->parameters['name'])) {
            throw new \Exception("Name is empty! Name parameter is required!");
        }
        $this->parameters['name'] = $name;
    }

    /**
     * Get control name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string control name.
     */
    public function getName()
    {
        return $this->parameters['name'];
    }

    /**
     * Set control value
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $value control value.
     */
    public function setValue($value)
    {
        $this->parameters['value'] = $value;
    }

    /**
     * Get control value
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string value.
     */
    public function getValue()
    {
        return $this->parameters['value'];
    }

    /**
     * Get default view path
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string default view path
     */
    public function getDefaultViewPath()
    {
        $class_path = str_replace(NS, DS, NS . get_class($this));
        $class_name = basename($class_path);
        $view_name  = lcfirst($class_name);
        $view_name  = HelperString::camelToSnake($view_name);

        return __DIR__ . DS . $class_name . DS . 'views' . DS . $view_name . '.php';
    }

    /**
     * Get current class directory
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string directory path.
     */
    public static function getDIR()
    {
        return __DIR__;
    }

    /**
     * Get url to control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string url.
     */
    public static function getURL()
    {
        return LolitaFramework::getURLByDirectory(__DIR__);
    }

    /**
     * Get HTML ID attribute
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string ID attribute
     */
    public function getID()
    {
        $name = $this->parameters['name'];
        $name = HelperString::bracesToUnderline($name);
        $name = str_replace('-', '_', $name);
        return $name;
    }

    /**
     * Render control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string html code.
     */
    public function render()
    {
        $this->parameters['me'] = $this;
        return View::make(
            $this->getDefaultViewPath(),
            $this->parameters
        );
    }
}
