<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;

class Home extends Trail
{

    /**
     * Compile home crumbs
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Home instance.
     */
    public function compile()
    {
        return $this;
    }
}
