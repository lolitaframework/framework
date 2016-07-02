<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Core;

class View
{
    /**
     * Is path have extension?
     *
     * @param  [type]  $path file.
     * @return boolean YES / NO.
     */
    public static function isHaveExtension($path)
    {
        $parts = pathinfo($path);
        return array_key_exists('extension', $parts);
    }

    /**
     * Get default view folder
     *
     * @return [string] default folder.
     */
    public static function getDefaultFolder()
    {
        if (defined('BASE_DIR')) {
            return BASE_DIR . DS . 'app' . DS . 'views' . DS;
        }
        return '';
    }

    /**
     * Render view
     *
     * @param type $path view path.
     * @param  array $data include data.
     * @return rendered html
     */
    public static function make($path, array $data = array())
    {
        // If path setted like this "someview" then
        // We need add default folder path and extension .php
        if (!self::isHaveExtension($path)) {
            $path = self::getDefaultFolder() . $path . '.php';
        }
        // Add parameters to temporary query variable.
        if (array_key_exists('wp_query', $GLOBALS)) {
            if (is_array($GLOBALS['wp_query']->query_vars)) {
                $old_query_vars = $GLOBALS['wp_query']->query_vars;
                $data['view'] = new self;
                $GLOBALS['wp_query']->query_vars = $data;
            }
        }

        ob_start();
        load_template($path, false);
        $result = ltrim(ob_get_clean());

        /**
         * Remove temporary wp query variable
         */
        if (array_key_exists('wp_query', $GLOBALS)) {
            if (is_array($GLOBALS['wp_query']->query_vars)) {
                $GLOBALS['wp_query']->query_vars = $old_query_vars;
            }
        }

        // Return the compiled view and terminate the output buffer.
        return self::minimizeBeforeOutput($result);
    }

    /**
     * Minimize before output
     * @param  string $buffer to minimize.
     * @return string minimized.
     */
    public static function minimizeBeforeOutput($buffer)
    {
        if (true === WP_DEBUG) {
            return $buffer;
        }
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s',      // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1',
        );

        return preg_replace($search, $replace, $buffer);
    }
}
