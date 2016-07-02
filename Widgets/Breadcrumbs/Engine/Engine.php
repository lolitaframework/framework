<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperWP;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails\Trail;

class Engine
{

    /**
     * Get trail
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return mixed Trail class or null.
     */
    public static function getTrail()
    {
        $route_class = self::getRouteClass();
        if (class_exists($route_class)) {
            return new $route_class();
        }
        return null;
    }

    /**
     * Compile trail
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return mixed Trail or Null.
     */
    public static function compile()
    {
        $trail  = self::getTrail();
        $crumbs = array();
        if ($trail instanceof Trail) {
            $crumbs = $trail->compile()->getCrumbs();
            $crumbs[ count($crumbs) - 1 ]->setLink();
        }
        return $crumbs;
    }

    /**
     * Get route function
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string route function.
     */
    public static function getRouteClass()
    {
        $route_class = HelperString::snakeToCamel(HelperWP::wpRouteType());
        if ('404' === $route_class) {
            $route_class = 'Trail404';
        }
        return __NAMESPACE__ . NS . 'Trails' . NS . $route_class;
    }
}
