<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Modules;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\Configuration;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Configuration\IModule;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperWP;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\GlobalLocator;

class Routes implements IModule
{
    /**
     * Routes class constructor
     *
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
        return HelperString::compileVariables($element);
    }

    /**
     * Render custom routes.
     * @return void
     */
    public function customRoutes()
    {
        $wp_query = GlobalLocator::wpQuery();
        $page = $wp_query->query_vars['name'];
        if (array_key_exists($page, $this->data)) {
            echo $this->getHTML($this->data[ $page ]);
            exit;
        }
    }

    /**
     * Block default template
     * @param  string $template_path default template path.
     * @return string empty.
     */
    public function blockDefaultTemplates($template_path)
    {
        $post          = GlobalLocator::post();
        $page_template = '';

        if (null !== $post) {
            $page_template = (string) get_post_meta($post->ID, '_wp_page_template', true);
        }
        $active = self::getActive();

        if (array_key_exists($page_template, $this->data)) {
            echo $this->getHTML($this->data[ $page_template ]);
        } else if (array_key_exists($active['type'], $this->data)) {
            echo $this->getHTML($this->data[ $active['type'] ]);
        } else {
            return $active['path_result'];
        }
        return '';
    }

    /**
     * Get all potential templates
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
     * Get active template info
     * @return array template info.
     */
    public static function getActive()
    {
        $route_type = HelperWP::wpRouteType();
        $templates = self::getTempaltes();

        $type          = key($templates);
        $template_func = current($templates);
        $template_path = $template_func();

        if (array_key_exists($route_type, $templates)) {
            $type = $route_type;
            $template_func = $templates[ $route_type ];
            $template_path = $template_func();
        }
        return array(
            'type'        => $type,
            'path'        => $template_func,
            'path_result' => $template_path,
        );
    }

    /**
     * Module priority
     * @return [int] priority, the smaller number the faster boot.
     */
    public static function getPriority()
    {
        return Configuration::DEFAULT_PRIORITY;
    }
}
