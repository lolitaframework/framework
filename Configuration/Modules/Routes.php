<?php
namespace lolita\LolitaFramework\Configuration\Modules;

use \lolita\LolitaFramework\Configuration\Configuration;
use \lolita\LolitaFramework\Configuration\IModule;
use \lolita\LolitaFramework\Core\Data;
use \lolita\LolitaFramework\Core\Route as R;
use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Ref;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework\Configuration\Modules\Routing\Route;

class Routes implements IModule
{
    const ROUTES_FILTER = 'lolita_routes_filter';

    /**
     * All routes
     * @var array
     */
    private $routes = array();

    /**
     * Routes class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param array $data engine data.
     */
    public function __construct($data = null)
    {
        $this->data = apply_filters(self::ROUTES_FILTER, $data);

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
        foreach ($this->data as $key => $value) {
            if (is_array($value)) {
                $this->routes[ $key ] = new Route(
                    Data::interpret($key),
                    Arr::get($value, 'html', ''),
                    (array) Arr::get($value, 'methods', array()),
                    Arr::get($value, 'template_name'),
                    Arr::get($value, 'css', ''),
                    Arr::get($value, 'title_parts', [])
                );
            } else {
                $this->routes[ $key ] = new Route(Data::interpret($key), $value);
            }
        }
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
        add_action('template_redirect', array(&$this, 'customRoutes'), 0, 0);
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
        if ($this->routes) {
            foreach ($this->routes as $r) {
                if ('' !== $r->templateName()) {
                    $page_templates[ $r->path() ] = $r->templateName();
                }
            }
        }
        return $page_templates;
    }

    /**
     * Render custom routes.
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return void
     */
    public function customRoutes()
    {
        if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
            header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
            status_header(200);
            exit(0);
        }

        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        } else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $origin = $_SERVER['HTTP_REFERER'];
        } else {
            $origin = $_SERVER['REMOTE_ADDR'];
        }

        $allowed_http_origins   = [
            'http://localhost:4200',
            'http://app.codingninjas.net'
        ];
        if (in_array($origin, $allowed_http_origins)) {
            header("Access-Control-Allow-Origin: " . $origin);
        }
        header('Access-Control-Allow-Credentials: true');
        $route = Url::route();
        foreach ($this->routes as $r_obj) {
            preg_match('/^' . $r_obj->regExp() . '/', $route, $values);
            $filterValues = array_filter(array_keys($values), 'is_string');
            $arguments = array_intersect_key($values, array_flip($filterValues));
            if (count($values) > 0) {
                $method = $_SERVER['REQUEST_METHOD'];
                if (in_array($method, $r_obj->methods())) {
                    status_header(200);
                    if (is_callable($r_obj->point())) {
                        echo call_user_func_array($r_obj->point(), $arguments);
                    } else {
                        echo $r_obj->point();
                    }
                    exit;
                }
            }
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
        $types_conditions = R::typesConditions(Loc::post());
        foreach ($this->routes as $type => $route) {
            if (array_key_exists($type, $types_conditions)) {
                if ($types_conditions[$type]()) {
                    echo call_user_func($route->point());
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
