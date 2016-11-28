<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Core\Route;
use \lolita\LolitaFramework\Core\Loc;

class Routes implements IModule
{
    /**
     * Routes class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = $data;
        if (is_array($this->data)) {
            $this->prepare()->install();
        }
    }

    /**
     * Prepare data
     *
     * @return Routes instance
     */
    private function prepare()
    {
        $prepared = array();
        foreach ($this->data as $key => $value) {
            $prepared[ Data::interpret($key) ] = $value;
        }
        $this->data = $prepared;
        return $this;
    }

    /**
     * Install our configuration
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Routes instance.
     */
    private function install()
    {
        add_filter('template_include', array($this, 'blockDefaultTemplates'));
        add_filter('theme_page_templates', array($this, 'templates'), 10, 3);
        add_action('template_redirect', array(&$this, 'customRoutes'), 10, 0);
        return $this;
    }

    /**
     * Filter theme_page_templates
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  array $page_templates page templates.
     * @param  object $me WP_Theme class instance.
     * @param  WP_Post $post post object.
     * @return array page templates.
     */
    public function templates($page_templates, $me, $post)
    {
        return array_merge($page_templates, $this->getTemplateNames());
    }

    /**
     * Get all templates from data
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array templates.
     */
    private function getTemplateNames()
    {
        $templates = array();
        foreach ($this->data as $key => $el) {
            if (is_array($el) && array_key_exists('template_name', $el)) {
                $templates[$key] = $el['template_name'];
            }
        }
        return $templates;
    }

    /**
     * Get HTML from route element
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @throws Route function %s not found!
     * @param  mixed $element route element.
     * @return string HTML code.
     */
    private function render($element)
    {
        if (is_callable($element)) {
            return $element();
        }
        if (is_array($element)) {
            if (array_key_exists('class', $element)) {
                $class = $element['class'];
                add_filter(
                    'body_class',
                    function ($classes) use ($class) {
                        return $this->addBodyClass($classes, $class);
                    }
                );
            }
            if (array_key_exists('html', $element)) {
                $element = $element['html'];
            }
        }
        $rendered_data = Data::interpret($element);
        if (!is_string($rendered_data)) {
            throw new \Exception(
                sprintf(
                    __('Route function %s not found!', 'lolita'),
                    json_encode($element)
                )
            );
        }
        return $rendered_data;
    }

    /**
     * Add css class to body
     *
     * @param array $classes
     * @param string $class
     * @return array
     */
    public function addBodyClass($classes, $class)
    {
        if (is_array($class)) {
            $class = implode(' ', $class);
        }
        $classes[] = $class;
        return $classes;
    }

    /**
     * Render custom routes.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function customRoutes()
    {
        $wp_query = Loc::wpQuery();
        $page = $wp_query->query_vars['name'];
        if (array_key_exists($page, $this->data)) {
            status_header(200);
            echo $this->render($this->data[ $page ]);
            exit;
        }
    }

    /**
     * Block default template
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $template_path default template path.
     * @return string empty.
     */
    public function blockDefaultTemplates($template_path)
    {
        $types_conditions = Route::typesConditions(Loc::post());
        foreach ($this->data as $type => $route) {
            if (array_key_exists($type, $types_conditions)) {
                if ($types_conditions[$type]()) {
                    echo $this->render($route);
                    exit;
                }
            }
        }
        return $template_path;
    }

    /**
     * Module priority
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
