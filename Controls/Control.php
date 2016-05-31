<?php
namespace ECG\LolitaFramework\Controls;

use \ECG\LolitaFramework\Core\HelperString as HelperString;
use \ECG\LolitaFramework\Core\HelperArray as HelperArray;
use \ECG\LolitaFramework\Core\View as View;
use \ECG\LolitaFramework as LolitaFramework;

abstract class Control
{
    /**
     * Control name
     * @var string
     */
    protected $name = null;

    /**
     * Control value
     * @var string
     */
    protected $value = null;

    /**
     * Control attributes
     * @var array
     */
    protected $attributes = array();

    /**
     * Data to insert in view
     * @var array
     */
    protected $view_data = array();

    /**
     * Control constructor
     * @param string $name control name.
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * Set name
     * @param string $name control name.
     */
    public function setName($name)
    {
        if ('' === trim($name)) {
            throw new \Exception("Name is empty!");
        }
        $this->name = $name;
    }

    /**
     * Get control name
     * @return string control name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set control value
     * @param string $value control value.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get control value
     * @return string value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set attributes
     * @param array  $new_attributes new attributes.
     * @param boolean $merge         will merging or no?
     */
    public function setAttributes(array $new_attributes, $merge = true)
    {
        if (true === $merge) {
            $this->attributes = array_merge($this->attributes, $new_attributes);
        } else {
            $this->attributes = $new_attributes;
        }
        return $this;
    }

    /**
     * Get attributes
     * @return array control attributes.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Render control
     * @return string HTML control code.
     */
    public function render()
    {
        $class_path = str_replace(NS, DS, NS . get_class($this));
        $class_name = basename($class_path);
        $view_name  = HelperString::camelToSnake($class_name);
        $attributes = $this->getAttributes();
        $this->view_data = array_merge(
            array(
                'attributes'     => $attributes,
                'attributes_str' => HelperArray::join($attributes),
            ),
            $this->view_data
        );

        return View::make(
            __DIR__ . DS . $class_name . DS . 'views' . DS . $view_name . '.php',
            $this->view_data
        );
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
}
