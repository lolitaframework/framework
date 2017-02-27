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
    public function is($user_candidate)
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
}
