<?php
namespace lolita\LolitaFramework\Min;

use \WP_Styles;
use \_WP_Dependency;
use \lolita\LolitaFramework\Core\Arr;
use \lolita\LolitaFramework\Core\Url;
use \lolita\LolitaFramework\Core\View;
use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Str;
use \lolita\LolitaFramework\Min\Models\ModelAssets;
use \lolita\LolitaFramework\Min\Controllers\ControllerAssets;
use \lolita\LolitaFramework\Configuration\Modules\Routes;

class Min
{
    /**
     * Class constructor.
     * @param $app_path
     * @param $app_url
     */
    public function __construct()
    {
        if (!is_admin() && !Str::startsWith($_SERVER['REQUEST_URI'], '/wp-login.php') && !Str::startsWith($_SERVER['REQUEST_URI'], '/wp-register.php')) {
            add_filter(Routes::ROUTES_FILTER, [&$this, 'appendRoute']);
            add_action('wp_print_styles', [&$this, 'printStyles'], 9999);
            add_action('wp_print_scripts', [&$this, 'printScripts'], 9999);
        }
    }

    /**
     * Print styles
     * @return void
     */
    public function printStyles()
    {
        $value = ModelAssets::styles();
        $transient_key = md5(json_encode($value));
        set_transient($transient_key, $value, DAY_IN_SECONDS * 7);
        foreach ($value as $handler => $el) {
            wp_deregister_style($handler);
        }
        wp_enqueue_style('min-styles', home_url('/min/styles/' . $transient_key), [], 0);
    }

    /**
     * Print scripts
     * @return void
     */
    public function printScripts()
    {
        $value = ModelAssets::scripts();
        $transient_key = md5(json_encode($value));
        set_transient($transient_key, $value, DAY_IN_SECONDS * 7);
        foreach ($value as $handler => $el) {
            wp_deregister_script($handler);
        }
        wp_enqueue_script('min-scripts', home_url('/min/scripts/' . $transient_key), [], false, true);
    }

    /**
     * Add rout for minify script
     * @param $routes
     * @return mixed
     */
    public function appendRoute($routes)
    {
        $routes['min/styles/{hash}'] = [
            'html' => ['\\lolita\\LolitaFramework\\Min\\Controllers\\ControllerAssets', 'styles']
        ];
        $routes['min/scripts/{hash}'] = [
            'html' => ['\\lolita\\LolitaFramework\\Min\\Controllers\\ControllerAssets', 'scripts']
        ];

        return $routes;
    }
}
