<?php
namespace lolita\LolitaFramework\Configuration\Modules\Columns;

class PostType extends Column
{
    /**
     * Get header action
     *
     * @return string
     */
    public function getHeaderAction()
    {
        return sprintf('manage_%s_posts_columns', $this->object_type);
    }

    /**
     * Get content action
     *
     * @return string
     */
    public function getContentAction()
    {
        return sprintf('manage_%s_posts_custom_column', $this->object_type);
    }
}
