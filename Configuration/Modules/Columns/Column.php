<?php
namespace lolita\LolitaFramework\Configuration\Modules\Columns;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \Exception;

abstract class Column
{

    /**
     * Name
     * @var null
     */
    protected $name = null;

    /**
     * Header callback
     * @var null
     */
    protected $header_callback = null;

    /**
     * Callback
     * @var null
     */
    protected $content_callback = null;

    /**
     * Slug
     * @var null
     */
    protected $slug = '';

    /**
     * Object type
     * @var string
     */
    protected $object_type = '';

    /**
     * Class constructor
     *
     * @param string $name
     * @param string $object_type
     * @param mixed $hc
     * @param mixed $cc
     * @param string $slug
     */
    public function __construct($name, $object_type, $content_callback, $header_callback = null, $slug = '')
    {
        $this
            ->setName($name)
            ->setSlug($slug)
            ->setObjectType($object_type)
            ->setHeaderCallback($header_callback);
        $this->content_callback = $content_callback;

        add_action(
            $this->getHeaderAction(),
            $this->header_callback[0],
            Arr::get($this->header_callback, 1, 10),
            Arr::get($this->header_callback, 2, 1)
        );
        add_action(
            $this->getContentAction(),
            $this->content_callback[0],
            Arr::get($this->content_callback, 1, 10),
            Arr::get($this->content_callback, 2, 1)
        );
    }

    /**
     * Get header action
     *
     * @return string
     */
    abstract public function getHeaderAction();

    /**
     * Get content action
     *
     * @return string
     */
    abstract public function getContentAction();

    /**
     * Set header callback
     *
     * @param mixed $hc
     */
    public function setHeaderCallback($hc)
    {
        if (null === $hc) {
            $this->header_callback = array(array(&$this, 'column'), 10, 1);
        } else {
            $this->header_callback = $hc;
        }
        return $this;
    }

    /**
     * Set object type
     *
     * @param string $o_type
     */
    public function setObjectType($o_type)
    {
        $this->object_type = $o_type;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Column instance
     */
    public function setName($name)
    {
        if ('' === trim($name)) {
            throw new Exception('Column name can not be empty!');
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        if ('' === trim($slug)) {
            $slug = Str::slug($this->name);
        }
        $this->slug = $slug;
        return $this;
    }

    /**
     * Add column to table
     *
     * @param  array $defaults
     * @return array
     */
    public function column($defaults)
    {
        $defaults[ $this->slug ] = $this->name;
        return $defaults;
    }
}
