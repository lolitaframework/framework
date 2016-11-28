<?php
namespace lolita\LolitaFramework\Controls;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework;

abstract class Control
{
    /**
     * Control name
     * @var string
     */
    protected $name = null;

    /**
     * Control old name
     * @var string
     */
    public $old_name = null;

    /**
     * Control value
     * @var mixed
     */
    protected $value = null;

    /**
     * Control label.
     * @var string
     */
    public $label = '';

    /**
     * Description
     * @var string
     */
    public $description = '';

    /**
     * Control attributes
     * @var array
     */
    protected $attributes = array();

    /**
     * Control constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     * @param mixed $value contro value.
     * @param array $attributes control attributes.
     * @param string $lable control label.
     * @param string $descriptions control description.
     */
    public function __construct($name, $value = '', $attributes = array(), $label = '', $description = '')
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setAttributes($attributes);
        $this->label       = $label;
        $this->description = $description;
    }

    /**
     * Control attributes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array attributes.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Control set attributes
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Control $instance.
     */
    public function setAttributes(array $attributes = array())
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Control attributes string
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string attributes.
     */
    public function getAttributesString()
    {
        return Arr::join($this->getAttributes());
    }

    /**
     * Set name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $name control name.
     * @return Control instance.
     */
    public function setName($name)
    {
        if ('' === trim($name)) {
            throw new \Exception("Name is empty! Name parameter is required!");
        }
        $this->old_name = $this->name;
        $this->name = $name;
        return $this;
    }

    /**
     * Get control name
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string control name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set control value
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $value control value.
     * @return Control instance.
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get control value
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string value.
     */
    public function getValue()
    {
        return $this->value;
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
        $view_name  = Str::snake($view_name);
        $filter_tag = sprintf('lf_%s_view', $view_name);

        return apply_filters($filter_tag, __DIR__ . DS . $class_name . DS . 'views' . DS . $view_name . '.php');
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
        return Url::toUrl(__DIR__);
    }

    /**
     * Get HTML ID attribute
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string ID attribute
     */
    public function getID()
    {
        $id = Str::bracesToUnderline($this->name);
        $id = str_replace('-', '_', $id);
        return $id;
    }

    /**
     * Render control
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string html code.
     */
    public function render()
    {
        return View::make(
            $this->getDefaultViewPath(),
            array('me' => $this)
        );
    }
}
