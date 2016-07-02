<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration;

interface IModule
{
    /**
     * Module priority
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority();
}
