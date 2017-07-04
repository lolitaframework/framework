<?php

namespace lolita\LolitaFramework\Core;

use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\View;
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
     * Delete user meta
     * @param  string $meta_key
     * @param  mixed $meta_value
     * @return boolean
     */
    public function deleteMeta($meta_key, $meta_value = '')
    {
        return delete_user_meta($this->ID, $meta_key, $meta_value);
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

    /**
     * Lost password
     * @param  string $login
     * @return mixed
     */
    public static function lostPassword($login = '')
    {
        if ('' === $login) {
            return new Error('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
        }

        if (strpos($login, '@')) {
            $user_data = get_user_by('email', trim(wp_unslash($login)));
            if (empty($user_data)) {
                return new Error('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
            }
        } else {
            $user_data = get_user_by('login', $login);
        }

        $login = trim($login);

        if (!$user_data) {
            return new Error('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
        }

        // Redefining user_login ensures we return the right case in the email.
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key = get_password_reset_key($user_data);

        if (is_wp_error($key)) {
            return $key;
        }

        $message = View::make(
            [dirname(__DIR__), 'views', 'restore_password_message.php'],
            [
                'key'        => $key,
                'user_login' => $user_login,
            ]
        );

        if (is_multisite()) {
            $blogname = get_network()->site_name;
        } else {
            /*
             * The blogname option is escaped with esc_html on the way into the database
             * in sanitize_option we want to reverse this for the plain text arena of emails.
             */
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        }

        /* translators: Password reset email subject. 1: Site name */
        $title = sprintf(__('[%s] Password Reset'), $blogname);

        /**
         * Filters the subject of the password reset email.
         *
         * @since 2.8.0
         * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
         *
         * @param string  $title      Default email title.
         * @param string  $user_login The username for the user.
         * @param WP_User $user_data  WP_User object.
         */
        $title = apply_filters('retrieve_password_title', $title, $user_login, $user_data);

        /**
         * Filters the message body of the password reset mail.
         *
         * @since 2.8.0
         * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
         *
         * @param string  $message    Default mail message.
         * @param string  $key        The activation key.
         * @param string  $user_login The username for the user.
         * @param WP_User $user_data  WP_User object.
         */
        $message = apply_filters('retrieve_password_message', $message, $key, $user_login, $user_data);

        if ($message && !wp_mail($user_email, wp_specialchars_decode($title), $message)) {
            wp_die(__('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.'));
        }

        return true;
    }

    /**
     * Reset password
     * @param  string $key
     * @param  strinf $login
     * @param  string $pass
     * @param  string $confirm_pass
     * @param  string $new_name
     * @return mixed
     */
    public static function resetPassword($key, $login, $pass = '', $confirm_pass = '', $new_name = '')
    {
        $u = check_password_reset_key($key, $login);
        if (is_wp_error($u)) {
            return $u;
        }
        if ('' === $pass) {
            return new Error('password_reset_empty', __("Sorry, we don't accept empty passwords.", 'codingninjas'));
        }

        if ($pass !== $confirm_pass) {
            return new Error('password_reset_mismatch', __('The passwords do not match.'));
        }

        reset_password($u, $pass);

        if ('' !== $new_name) {
            wp_update_user(
                array(
                    'ID'           => $u->ID,
                    'display_name' => $new_name,
                )
            );
        }

        return true;
    }
}
