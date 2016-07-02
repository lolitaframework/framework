<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine;

class Crumb {
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
     * @return boolean true if have / false if not.
     */
    public function isHaveURL()
    {
        return null !== $this->link;
    }

    /**
     * Get crumb label
     * @return string crumb label.
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get crumb link
     * @return mixed crumb link.
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set link parameter
     * @param mixed $link link parameter.
     * @return Crumb instance.
     */
    public function setLink($link = null)
    {
        $this->link = $link;
        return $this;
    }
}
