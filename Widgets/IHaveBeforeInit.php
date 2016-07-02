<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets;

interface IHaveBeforeInit
{
    /**
     * This function run before widgets_init hook
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public static function beforeInit();
}
