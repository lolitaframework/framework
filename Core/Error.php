<?php

namespace lolita\LolitaFramework\Core;

use \WP_Error;
use \lolita\LolitaFramework\Core\View;

class Error extends WP_Error
{
    /**
     * Initialize the error.
     *
     * If `$code` is empty, the other parameters will be ignored.
     * When `$code` is not empty, `$message` will be used even if
     * it is empty. The `$data` parameter will be used only if it
     * is not empty.
     *
     * Though the class is constructed with a single error code and
     * message, multiple codes can be added using the `add()` method.
     *
     * @since 2.1.0
     *
     * @param string|int $code Error code
     * @param string $message Error message
     * @param mixed $data Optional. Error data.
     */
    public function __construct($code = '', $message = '', $data = '')
    {
        if (empty($code)) {
            return;
        }
        if ('' === $message) {
            $error_view_path = View::path(['errors', $code]);
            if (file_exists($error_view_path)) {
                $message = View::make($error_view_path);
            }
        }
        $this->errors[$code][] = $message;

        if (!empty($data)) {
            $this->error_data[$code] = $data;
        }
    }

    /**
     * Add an error or append additional message to an existing error.
     *
     * @since 2.1.0
     * @access public
     *
     * @param string|int|WP_Error $code Error code.
     * @param string $message Error message.
     * @param mixed $data Optional. Error data.
     */
    public function add($code, $message = '', $data = '')
    {
        if (is_wp_error($code)) {
            $this->errors = array_merge($this->errors, $code->errors);
        } else {
            $this->errors[$code][] = $message;
            if (!empty($data)) {
                $this->error_data[$code] = $data;
            }
        }
    }

    /**
     * Maybe aadd
     * @param  mixed $code
     * @param  string $message
     * @param  string $data
     * @return boolean
     */
    public function maybeAdd($code, $message = '', $data = '')
    {
        if (self::is($code) || '' !== $message) {
            $this->add($code, $message, $data);
            return true;
        }
        return false;
    }

    /**
     * Has errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * Is wp error?
     *
     * @param  mixed  $mixed
     * @return boolean
     */
    public static function is($mixed)
    {
        return is_wp_error($mixed);
    }
}
