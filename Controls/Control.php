<?php
namespace redbrook\LolitaFramework\Controls;

use \redbrook\LolitaFramework\Core\HelperString as HelperString;
use \redbrook\LolitaFramework\Core\HelperArray as HelperArray;
use \redbrook\LolitaFramework\Core\View as View;
use \redbrook\LolitaFramework as LolitaFramework;

abstract class Control
{
    /**
     * Data to insert in view
     * @var array
     */
    public $parameters = array();

    /**
     * Control constructor
     * @param array $parameters control parameters.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->setName(HelperArray::get($parameters, 'name'));
    }

    /**
     * Set name
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
     * @return string control name.
     */
    public function getName()
    {
        return $this->parameters['name'];
    }

    /**
     * Set control value
     * @param string $value control value.
     */
    public function setValue($value)
    {
        $this->parameters['value'] = $value;
    }

    /**
     * Get control value
     * @return string value.
     */
    public function getValue()
    {
        return $this->parameters['value'];
    }

    /**
     * Get default view path
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
     * @return string directory path.
     */
    public function getDIR()
    {
        $reflector = new \ReflectionClass(get_class($this));
        return dirname($reflector->getFileName());
    }

    /**
     * Get url to control
     * @return string url.
     */
    public function getURL()
    {
        return LolitaFramework::getURLByDirectory($this->getDIR());
    }

    /**
     * Control class folder URL
     * @return string folder URL.
     */
    public static function controlURL()
    {
        return LolitaFramework::getURLByDirectory(__DIR__);
    }

    /**
     * Render control
     * @return string html code.
     */
    public function render()
    {
        return View::make(
            $this->getDefaultViewPath(),
            $this->parameters
        );
    }
}
