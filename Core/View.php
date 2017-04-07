<?php
namespace lolita\LolitaFramework\Core;

use \lolita\LolitaFramework;
use \Exception;

class View
{
    /**
     * Is path have extension?
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string  $path file.
     * @return boolean YES / NO.
     */
    public static function isHaveExtension($path)
    {
        if (!is_string($path)) {
            return false;
        }
        $parts = pathinfo($path);
        return array_key_exists('extension', $parts);
    }

    /**
     * Get default view folder
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return string default folder.
     */
    public static function getDefaultFolder()
    {
        return apply_filters(
            'lf_default_views_folder',
            Loc::lolita()->baseDir() . DS . 'app' . DS . 'views' . DS
        );
    }

    /**
     * Get default view folder path
     *
     * @param  string $path
     * @return string
     */
    public static function path($path)
    {
        if (is_array($path)) {
            $path = implode(DS, $path);
        }
        if (!is_string($path)) {
            throw new Exception('Wrong path: ' . print_r($path, true));
        }
        $path = (string) $path;
        // If path setted like this "someview" then
        // We need add default folder path and extension .php
        if (!self::isHaveExtension($path)) {
            $path = self::getDefaultFolder() . $path . '.php';
        }
        return $path;
    }

    /**
     * Render view
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param type $path view path.
     * @param  array $data include data.
     * @return rendered html
     */
    public static function make($path, array $data = array())
    {
        $path = self::path($path);
        $data = array_merge($data, Loc::helpers());
        
        // Add parameters to temporary query variable.
        $data['__View'] = new self;

        extract($data);
        ob_start();
        require($path);

        // Return the compiled view and terminate the output buffer.
        return self::minimize(ltrim(ob_get_clean()));
    }

    /**
     * Minimize before output
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $buffer to minimize.
     * @return string minimized.
     */
    public static function minimize($buffer)
    {
        if (true == WP_DEBUG) {
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

    /**
     * Generate HTML Tag
     * @param  string $tag
     * @param  string $attributes
     * @param  string $content
     * @return string
     */
    public static function tag($tag, $attributes = '', $content = '')
    {
        if (is_array($attributes)) {
            $attributes = Arr::join($attributes);
        }
        return sprintf(
            '<%1$s %2$s>%3$s<%1$s/>',
            $tag,
            $attributes,
            $content
        );
    }
}
