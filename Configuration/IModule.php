<?php
namespace lolita\LolitaFramework\Configuration;

interface IModule
{
    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority();
}
