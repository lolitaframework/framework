<?php
namespace MyProject\LolitaFramework\Configuration\Modules;

use \MyProject\LolitaFramework\Configuration\Configuration;
use \MyProject\LolitaFramework\Configuration\IModule;
use \MyProject\LolitaFramework\Core\Data;
use \MyProject\LolitaFramework\Core\Wp;
use \MyProject\LolitaFramework\Core\Loc;

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
            $this->install();
        }
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
     * @param  mixed $element route element.
     * @return string HTML code.
     */
    private function getHTML($element)
    {
        if (is_array($element)) {
            if (array_key_exists('html', $element)) {
                $element = $element['html'];
            }
        }
        return Data::interpret($element);
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
            echo $this->getHTML($this->data[ $page ]);
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
        $post          = Loc::post();
        $route_type    = Wp::wpRouteType();
        $templates     = $this->getTempaltes();
        $page_template = '';

        if (null !== $post) {
            $page_template = (string) get_post_meta($post->ID, '_wp_page_template', true);
        }

        if (array_key_exists($page_template, $this->data)) {
            // ==============================================================
            // Page from template option
            // ==============================================================

            echo $this->getHTML($this->data[ $page_template ]);
        } else if (array_key_exists($route_type, $this->data)) {
            // ==============================================================
            // Page from routes.json
            // ==============================================================

            echo $this->getHTML($this->data[ $route_type ]);
        } else if (array_key_exists($route_type, $templates)) {
            // ==============================================================
            // Page from native wordpress route system
            // ==============================================================

            return $templates[ $route_type ]();
        }
        return '';
    }

    /**
     * Get all potential templates
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return array potential templates
     */
    public static function getTempaltes()
    {
        return array(
            '/'                 => 'get_index_template',
            'embed'             => 'get_embed_template',
            '404'               => 'get_404_template',
            'search'            => 'get_search_template',
            'front_page'        => 'get_front_page_template',
            'home'              => 'get_home_template',
            'post_type_archive' => 'get_post_type_archive_template',
            'taxonomy'          => 'get_taxonomy_template',
            'attachment'        => 'get_attachment_template',
            'single'            => 'get_single_template',
            'page'              => 'get_page_template',
            'singular'          => 'get_singular_template',
            'category'          => 'get_category_template',
            'tag'               => 'get_tag_template',
            'author'            => 'get_author_template',
            'date'              => 'get_date_template',
            'archive'           => 'get_archive_template',
            'paged'             => 'get_paged_template',
        );
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
