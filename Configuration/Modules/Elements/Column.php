<?php
namespace lolita\LolitaFramework\Configuration\Modules\Elements;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Data;
use \Exception;

class Column
{

    /**
     * Name
     * @var null
     */
    private $name = null;

    /**
     * Callback
     * @var null
     */
    private $callback = null;

    /**
     * Slug
     * @var null
     */
    private $slug = '';

    /**
     * Post type
     * @var string
     */
    private $post_type = '';

    /**
     * Table type
     * @var string
     */
    private $type = 'posts';

    /**
     * Class constructor
     *
     * @param string $name
     * @param mixed $callback
     * @param string $post_type
     * @param string $slug
     */
    public function __construct($name, $post_type = 'post', $slug = '', $content = null)
    {
        $this->setName($name)->setSlug($slug);
        $this->post_type = $post_type;

        add_action(sprintf('manage_%s_columns', $this->post_type), array(&$this, 'column'));
        if (is_array($content) && 4 === count($content)) {
            add_action($content[0], $content[1], $content[2], $content[3]);
        }
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
}
