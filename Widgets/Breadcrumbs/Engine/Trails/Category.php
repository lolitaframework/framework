<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\GlobalLocator;

class Category extends Trail {

    /**
     * Compile Category crumbs
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
