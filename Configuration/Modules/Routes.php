<?php
namespace duidluck\LolitaFramework\Configuration\Modules;

use \duidluck\LolitaFramework\Configuration\Configuration as Configuration;
use \duidluck\LolitaFramework\Configuration\IModule as IModule;
use \duidluck\LolitaFramework\Core\HelperString as HelperString;

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
        add_action('template_redirect', array(&$this, 'customRoutes'), 10, 0);
        return $this;
    }

    /**
     * Render custom routes.
     * @return void
     */
    public function customRoutes()
    {
        global $wp_query;
        $page = $wp_query->query_vars['name'];
        if (array_key_exists($page, $this->data)) {
            echo HelperString::compileVariables($this->data[ $page ]);
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
        $active = self::getActive();

        if (array_key_exists($active['type'], $this->data)) {
            echo HelperString::compileVariables($this->data[ $active['type'] ]);
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
            array(
                'path' => 'get_index_template',
                'is'   => '',
                'type' => '/',
            ),
            array(
                'path' => 'get_embed_template',
                'is'   => 'is_embed',
                'type' => 'embed',
            ),
            array(
                'path' => 'get_404_template',
                'is'   => 'is_404',
                'type' => '404',
            ),
            array(
                'path' => 'get_search_template',
                'is'   => 'is_search',
                'type' => 'search',
            ),
            array(
                'path' => 'get_front_page_template',
                'is'   => 'is_front_page',
                'type' => 'front_page',
            ),
            array(
                'path' => 'get_home_template',
                'is'   => 'is_home',
                'type' => 'home',
            ),
            array(
                'path' => 'get_post_type_archive_template',
                'is'   => 'is_post_type_archive',
                'type' => 'post_type_archive',
            ),
            array(
                'path' => 'get_taxonomy_template',
                'is'   => 'is_tax',
                'type' => 'taxonomy',
            ),
            array(
                'path' => 'get_attachment_template',
                'is'   => 'is_attachment',
                'type' => 'attachment',
            ),
            array(
                'path' => 'get_single_template',
                'is'   => 'is_single',
                'type' => 'single',
            ),
            array(
                'path' => 'get_page_template',
                'is'   => 'is_page',
                'type' => 'page',
            ),
            array(
                'path' => 'get_singular_template',
                'is'   => 'is_singular',
                'type' => 'singular',
            ),
            array(
                'path' => 'get_category_template',
                'is'   => 'is_category',
                'type' => 'category',
            ),
            array(
                'path' => 'get_tag_template',
                'is'   => 'is_tag',
                'type' => 'tag',
            ),
            array(
                'path' => 'get_author_template',
                'is'   => 'is_author',
                'type' => 'author',
            ),
            array(
                'path' => 'get_date_template',
                'is'   => 'is_date',
                'type' => 'date',
            ),
            array(
                'path' => 'get_archive_template',
                'is'   => 'is_archive',
                'type' => 'archive',
            ),
            array(
                'path' => 'get_paged_template',
                'is'   => 'is_paged',
                'type' => 'paged',
            ),
        );
    }

    /**
     * Get active template info
     * @return array template info.
     */
    public static function getActive()
    {
        $templates = self::getTempaltes();
        foreach ($templates as $template) {
            if (array_key_exists('is', $template) && '' !== $template['is']) {
                $template['is_result'] = $template['is']();
                if ($template['is_result']) {
                    $template['path_result'] = $template['path']();
                    if ('' !== $template['path_result']) {
                        return $template;
                    }
                }
            }
        }
        return $templates;
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
