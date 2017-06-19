<?php
namespace lolita\LolitaFramework\Configuration\Modules\Routing;

use \lolita\LolitaFramework\Core\Str;

class Route
{
    /**
     * Route path
     * @var string
     */
    protected $path;

    /**
     * Route css class
     * @var string
     */
    protected $css;

    /**
     * Title parts
     * @var array
     */
    protected $title_parts;

    /**
     * Template name
     *
     * @var string
     */
    protected $template_name;

    /**
     * Endpoint
     * @var mixed
     */
    protected $point;

    /**
     * Allowed HTTP methods
     * @var array
     */
    protected $methods = array();

    /**
     * Class constructor
     *
     * @param string $path
     * @param mixed $point
     * @param array  $allow_http_methods
     */
    public function __construct($path, $point, array $allow_http_methods = array(), $template_name = '', $css = '', $title_parts = [])
    {
        $this->path  = $path;
        $this->point = $point;
        $this->css   = $css;
        $this->title_parts = $title_parts;
        $this->methods = array_map('strtoupper', $allow_http_methods);
        $this->template_name = (string) $template_name;
        if (0 === count($this->methods)) {
            $this->methods = array('GET', 'PUT', 'POST', 'HEAD', 'DELETE', 'OPTIONS');
        }
    }

    /**
     * Get path
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Endpoint
     *
     * @return mixed
     */
    public function point()
    {
        $this->addCSSClass();
        $this->addTitleParts();
        return $this->point;
    }

    /**
     * Add css class to body_class() function
     */
    public function addCSSClass()
    {
        add_filter('body_class', array(&$this, 'bodyClass'), 10, 2);
    }

    /**
     * Add title parts to wp_get_document_title() function
     */
    public function addTitleParts()
    {
        add_filter('document_title_parts', [&$this, 'titleParts'], 10);
    }

    /**
     * Add css class
     *
     * @param array $classes An array of body classes.
     * @param array $class   An array of additional classes added to the body.
     * @return array
     */
    public function bodyClass($classes, $class)
    {
        $classes[] = 'route-' . Str::slug($this->path);
        $classes[] = $this->css;
        return $classes;
    }

    /**
     * Merge title parts
     * @param  array $title_parts
     * @return array
     */
    public function titleParts($title_parts)
    {
        return array_merge($title_parts, $this->title_parts);
    }
    
    /**
     * Get all methods
     *
     * @return array
     */
    public function methods()
    {
        return $this->methods;
    }

    /**
     * Get template name
     *
     * @return string
     */
    public function templateName()
    {
        return $this->template_name;
    }

    /**
     * Get regexp
     *
     * @return string
     */
    public function regExp()
    {
        $regexp = $this->path;
        if (preg_match_all('~{(.*)}~Uiu', $regexp, $placeholders)) {
            foreach ($placeholders[0] as $index => $match) {
                $name = $placeholders[1][$index];
                $replace = '(?<'.$name.'>.*)';
                $regexp = str_replace($match, $replace, $regexp);
            }
        }
        return str_replace('/', '\/', $regexp);
    }
}
