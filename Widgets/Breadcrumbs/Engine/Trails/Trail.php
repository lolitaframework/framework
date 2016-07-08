<?php
namespace MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \MyProject\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;
use \MyProject\LolitaFramework\Core\HelperWP;

abstract class Trail
{

    /**
     * Crumbs
     * @var array
     */
    protected $crumbs = array();

    /**
     * Compile trail
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return Trail instance.
     */
    abstract public function compile();

    /**
     * Crumb class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param string $label crumb label.
     * @param mixed $link  url.
     */
    public function __construct()
    {
        $this->home();
    }

    /**
     * Add site home link if is paged front page
     * @return Trail instance.
     */
    public function home()
    {
        $this->crumbs[] = new Crumb(__('Homepage', 'lolita'), home_url('/'));
        return $this;
    }

    /**
     * Get all crumbs
     * @return array crumbs.
     */
    public function getCrumbs()
    {
        return $this->crumbs;
    }

    /**
     * Add post parents link to breadcrumbs items
     *
     * @param integer $post_id first parent post ID
     * @return Trail instance.
     */
    public function postParents($post_id)
    {
        $ancestors = get_post_ancestors($post_id);
        if (count($ancestors)) {
            foreach ($ancestors as $id) {
                $this->crumbs[] = new Crumb(get_the_title($id), get_permalink($id));
            }
        }

        return $this;
    }

    /**
     * Searches for term parents of hierarchical taxonomies.
     * This function is similar to the WordPress function get_category_parents() but handles any type of taxonomy.
     *
     * @param  int    $term_id  ID of the term to get the parents of.
     * @param  string $taxonomy Name of the taxonomy for the given term.
     * @return Trail instance.
     */
    public function termParents($term_id, $taxonomy)
    {
        $ancestors = get_ancestors($term_id, $taxonomy, 'taxonomy');
        array_unshift($ancestors, $term_id);
        if (count($ancestors)) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $term = get_term($ancestor, $taxonomy);
                $this->crumbs[] = new Crumb(esc_attr($term->name), get_term_link($term->term_id, $taxonomy));
            }
        }

        return $this;
    }

    /**
     * Adds a post's terms from a specific taxonomy to the items array.
     *
     * @param  int    $post_id The ID of the post to get the terms for.
     */
    public function postTerms($post_id)
    {
        $taxonomy_name = $this->getMostUsedTaxonomy($post_id);
        $post_terms = wp_get_object_terms($post_id, $taxonomy_name, array('fields' => 'ids'));

        if (is_array($post_terms) && isset($post_terms[0])) {
            $branch = HelperWP::getBranchWithLargestAncestors(
                $post_terms,
                $taxonomy_name,
                'taxonomy'
            );
            $this->termParents(key($branch), $taxonomy_name);
        }
        return $this;
    }

    /**
     * Get the most used taxonomy
     * @param  integer $post_id post id.
     * @return string most used taxonomy.
     */
    public function getMostUsedTaxonomy($post_id)
    {
        $term_count_taxonomies = array();
        $taxonomies = get_object_taxonomies(get_post($post_id));
        foreach ($taxonomies as $taxonomy) {
            $terms = get_object_term_cache($post_id, $taxonomy);
            if (false === $terms) {
                $terms = wp_get_object_terms($post_id, $taxonomy, array());
            }
            $term_count_taxonomies[count($terms)] = $taxonomy;
        }
        krsort($term_count_taxonomies);
        reset($term_count_taxonomies);
        return current($term_count_taxonomies);
    }
}
