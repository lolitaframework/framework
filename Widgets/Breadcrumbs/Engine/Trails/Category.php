<?php
namespace MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;
use \MyProject\LolitaFramework\Core\GlobalLocator;

class Category extends Trail
{

    /**
     * Compile Category crumbs
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Category instance.
     */
    public function compile()
    {
        $wp_rewrite = GlobalLocator::wpRewrite();

        /* Get some taxonomy and term variables. */
        $term     = get_queried_object();
        $taxonomy = get_taxonomy($term->taxonomy);

        $this->termParents($term->term_id, $taxonomy->name);
        
        return $this;
    }
}