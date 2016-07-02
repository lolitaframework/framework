<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine;

class Crumb
{
    /**
     * Crumb label.
     * It is required!
     * @var null
     */
    private $label = null;

    /**
     * Crumb link
     * @var null
     */
    private $link = null;

    /**
     * Crumb class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $label crumb label.
     * @param mixed $link  url.
     */
    public function __construct($label, $link = null)
    {
        $this->label = (string) $label;
        $this->link  = $link;
    }

    /**
     * Is this crumb have URL?
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return boolean true if have / false if not.
     */
    public function isHaveURL()
    {
        return null !== $this->link;
    }

    /**
     * Get crumb label
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string crumb label.
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get crumb link
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return mixed crumb link.
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link parameter
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param mixed $link link parameter.
     * @return Crumb instance.
     */
    public function setLink($link = null)
    {
        $this->link = $link;
        return $this;
    }
}
