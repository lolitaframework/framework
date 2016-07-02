<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;

class Home extends Trail
{

    /**
     * Compile home crumbs
     * @return Home instance.
     */
    public function compile()
    {
        return $this;
    }
}
