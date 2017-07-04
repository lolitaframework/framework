<?php

namespace lolita\LolitaFramework\Core;

use \WP_Role;

class Role extends WP_Role
{

    /**
     * Caption
     * @var boolean
     */
    protected $caption = '';

    /**
     * Constructor - Set up object properties.
     *
     * The list of capabilities, must have the key as the name of the capability
     * and the value a boolean of whether it is granted to the role.
     *
     * @since 2.0.0
     * @access public
     *
     * @param string $role Role name.
     * @param array $capabilities List of capabilities.
     */
    public function __construct($role, array $capabilities = [])
    {
        if ($role instanceof WP_Role) {
            $this->name = $role->name;
            $this->capabilities = $role->capabilities;
        } else {
            $this->name = $role;
            $this->capabilities = $capabilities;
        }
    }

    /**
     * Role caption
     * @return string
     */
    public function caption()
    {
        if ('' === $this->caption) {
            if (array_key_exists($this->name, wp_roles()->roles)) {
                $this->caption = wp_roles()->roles[ $this->name ]['name'];
            }
        }
        return $this->caption;
    }
}
