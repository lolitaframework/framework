<?php
namespace lolita\LolitaFramework\Configuration\Modules\Columns;

use \lolita\LolitaFramework\Core\Arr;

class Users extends Column
{
    /**
     * Class constructor
     *
     * @param string $name
     * @param string $object_type
     * @param mixed $hc
     * @param mixed $cc
     * @param string $slug
     */
    public function __construct($name, $content_callback, $header_callback = null, $sortable = '', $slug = '')
    {
        $this
            ->setName($name)
            ->setSlug($slug)
            ->setObjectType('users')
            ->setHeaderCallback($header_callback)
            ->setSortable($sortable);
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
    public function getHeaderAction()
    {
        return 'manage_users_columns';
    }

    /**
     * Get content action
     *
     * @return string
     */
    public function getContentAction()
    {
        return 'manage_users_custom_column';
    }
}
