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
     * Class constructor
     *
     * @param string $name
     * @param mixed $callback
     * @param string $post_type
     * @param string $slug
     */
    public function __construct($name, $callback, $post_type = 'post', $slug = '')
    {
        $this->setName($name)
            ->setCallback($callback)
            ->setSlug($slug);
        $this->post_type = $post_type;

        add_action($this->actionColumn(), array(&$this, 'column'));
        add_action($this->actionContent(), array(&$this, 'content'), 10, 2);
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
     * Add content in column
     *
     * @param  string $column_name
     * @param  integer $post_ID
     * @return void
     */
    public function content($column_name, $post_ID)
    {
        if ($this->slug == $column_name) {
            call_user_func_array($this->callback, array($column_name, $post_ID));
        }
    }

    /**
     * Action for add column
     *
     * @return string
     */
    public function actionColumn()
    {
        return sprintf('manage_%s_posts_columns', $this->post_type);
    }

    /**
     * Action for add content
     *
     * @return string
     */
    public function actionContent()
    {
        return sprintf('manage_%s_posts_custom_column', $this->post_type);
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
     * Set callback
     *
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new Exception(
                sprintf(
                    __('Callback "%s" is not callable!', 'lolita'),
                    Data::maybeJSONEncode($callback)
                )
            );
        }
        $this->callback = $callback;
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
