<?php
namespace lolita\LolitaFramework\Configuration\Modules\Routing;

class Route
{
    /**
     * Route path
     * @var string
     */
    protected $path;

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
    public function __construct($path, $point, array $allow_http_methods = array(), $template_name = '')
    {
        $this->path = $path;
        $this->point = $point;
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
        return $this->point;
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
