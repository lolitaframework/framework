<?php
namespace MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;

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
