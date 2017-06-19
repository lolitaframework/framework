<?php

namespace lolita\LolitaFramework\Core\Decorators;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \Exception;
use \WP_User;

class User extends WP_User
{
    /**
     * Update user in db
     *
     * @param  array  $args
     * @return mixed
     */
    public function update(array $args)
    {
        return wp_update_user(
            array_merge(
                array(
                    'ID' => $this->ID,
                ),
                $args
            )
        );
    }

    /**
     * Is user
     *
     * @param  mixed  $user_candidate
     * @return boolean
     */
    public static function itsMe($user_candidate)
    {
        if ($user_candidate instanceof WP_User) {
            return true;
        }
        return false;
    }

    /**
     * Update user meta
     *
     * @param  string $meta_key
     * @param  mixed $meta_value
     * @return mixed
     */
    public function updateMeta($meta_key, $meta_value)
    {
        return update_user_meta($this->ID, $meta_key, $meta_value);
    }

    /**
     * Suicide
     * @return void
     */
    public function suicide()
    {
        require_once(ABSPATH . DS . 'wp-admin' . DS . 'includes' . DS . 'user.php');
        return wp_delete_user($this->ID);
    }

    /**
     * Link in admin panel
     * @return string
     */
    public function adminLink()
    {
        return add_query_arg(
            ['user_id' => $this->ID],
            admin_url('user-edit.php')
        );
    }
}
