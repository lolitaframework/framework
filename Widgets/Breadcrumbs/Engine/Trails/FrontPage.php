<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;

class FrontPage extends Trail {

    /**
     * Compile front page crumbs
     * @return FrontPage instance.
     */
    public function compile()
    {
        return $this;
    }
}
