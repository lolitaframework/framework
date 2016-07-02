<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperWP;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\GlobalLocator;
use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Core\HelperString;

class BreadcrumbTrail
{
    const URL   = '__URL__';
    const LABEL = '__LABEL__';

    /**
     * Query arguments
     * @var array
     */
    private $args = array();

    /**
     * Compiled trail items
     * @var array
     */
    public $items = array();

    /**
     * Route type
     * @var string
     */
    private $route_type = '404';

    /**
     * BreadcrumbTrail constructor
     */
    public function __construct(array $args = array())
    {
        $this->args = array_merge(
            array(
                'post_taxonomy' => array(
                    'post' => 'category',
                )
            ),
            $args
        );
        $this->route_type = HelperWP::wpRouteType();
    }

    /**
     * Get route function
     * @return string route function.
     */
    private function getRouteFunction()
    {
        $route_page = HelperString::snakeToCamel($this->route_type);
        if ('404' === $route_page) {
            $route_page = 'page404';
        }
        return lcfirst($route_page);
    }

    /**
     * Compile breadcrumb trail
     * @return BreadcrumbTrail instance.
     */
    public function compile()
    {
        $this->siteHomeLink();
        $func = $this->getRouteFunction();
        if (!is_front_page()) {
            if (method_exists($this, $func)) {
                $this->{$func}();
            }
        }
        return $this;
    }

    /**
     * Add site home link if is paged front page
     * @return BreadcrumbTrail instance.
     */
    public function siteHomeLink()
    {
        $this->items[] = array(
            self::URL   => home_url('/'),
            self::LABEL => __('Homepage', 'lolita'),
        );
        return $this;
    }

    /**
     * Add blog page breadcrumbs item
     * @return BreadcrumbTrail instance.
     */
    public function home()
    {
        $items   = array();
        $post_id = get_queried_object_id();
        $post    = get_page($post_id);

        // If the post has parents, add them to the array.
        if (0 < $post->post_parent) {
            $items = $this->getPostParents($post->post_parent);
        }

        $url   = get_permalink($post_id);
        $label = get_the_title($post_id);

        if (is_paged()) {
            array_push(
                $items,
                array(
                    self::URL   => $url,
                    self::LABEL => $label,
                )
            );
        } elseif ($label) {
            array_push(
                $items,
                array(
                    self::LABEL => $label,
                )
            );
        }
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds singular post items to the items array.
     *
     * @return BreadcrumbTrail instance.
     */
    public function singular()
    {
        $items = array();
        /* Get the queried post. */
        $post    = get_queried_object();
        $post_id = get_queried_object_id();
        
        /* If the post has a parent, follow the parent trail. */
        if (0 < $post->post_parent) {
            $items = $this->getPostParents($post->post_parent);
        } else {
            /* If the post doesn't have a parent, get its hierarchy based off the post type. */
            $items = $this->getPostHierarchy($post_id);
        }

        /* Display terms for specific post type taxonomy if requested. */
        $items = array_merge(
            (array) $items,
            (array) $this->getPostTerms($post_id)
        );

        /* End with the post title. */
        if ($post_title = single_post_title('', false)) {
            if (1 < get_query_var('page')) {

                $items[] = array(
                    self::URL   => get_permalink($post_id),
                    self::LABEL => $post_title,
                );

            }

            $items[] = array(
                self::URL   => get_permalink($post_id),
                self::LABEL => $post_title,
            );
        }
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds page post items to the items array.
     *
     * @return BreadcrumbTrail instance.
     */
    public function page()
    {
        return $this->singular();
    }

    /**
     * Adds post items to the items array.
     *
     * @return BreadcrumbTrail instance.
     */
    public function single()
    {
        return $this->singular();
    }

    /**
     * Adds the items to the trail items array for post type archives.
     *
     * @return BreadcrumbTrail instance.
     */
    public function postTypeArchive()
    {
        $items = array();
        /* Get the post type object. */
        $post_type_object = get_post_type_object(get_query_var('post_type'));

        if (false !== $post_type_object->rewrite) {

            /* If 'with_front' is true, add $wp_rewrite->front to the trail. */
            if ($post_type_object->rewrite['with_front']) {
                $items = $this->getRewriteFrontItems();
            }

        }

        /* Add the post type [plural] name to the trail end. */
        if (is_paged()) {
            $items[] = array(
                self::URL   => esc_url(get_post_type_archive_link($post_type_object->name)),
                self::LABEL => post_type_archive_title('', false)
            );
        }

        $items[] = array(
            self::LABEL => post_type_archive_title('', false)
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Category
     * @return BreadcrumbTrain instance.
     */
    public function category()
    {
        return $this->termArchiveItems();
    }

    /**
     * Tag
     * @return BreadcrumbTrain instance.
     */
    public function tag()
    {
        return $this->termArchiveItems();
    }

    /**
     * Tax
     * @return BreadcrumbTrain instance.
     */
    public function tax()
    {
        return $this->termArchiveItems();
    }

    /**
     * Adds the items to the trail items array for taxonomy term archives.
     * @return BreadcrumbTrain instance.
     */
    public function termArchiveItems()
    {
        $items = array();
        $wp_rewrite = GlobalLocator::wpRewrite();

        /* Get some taxonomy and term variables. */
        $term     = get_queried_object();
        $taxonomy = get_taxonomy($term->taxonomy);

        /* If there are rewrite rules for the taxonomy. */
        if (false !== $taxonomy->rewrite) {

            $post_type_catched = false;

            /* If 'with_front' is true, dd $wp_rewrite->front to the trail. */
            if ($taxonomy->rewrite['with_front'] && $wp_rewrite->front) {
                $items = $this->getRewriteFrontItems();
            }

            /* Get parent pages by path if they exist. */
            $items = array_merge($items, $this->getPathParents($taxonomy->rewrite['slug']));

            /* Add post type archive if its 'has_archive' matches the taxonomy rewrite 'slug'. */
            if ($taxonomy->rewrite['slug']) {

                $slug = trim($taxonomy->rewrite['slug'], '/');

                /**
                 * Deals with the situation if the slug has a '/' between multiple strings. For
                 * example, "movies/genres" where "movies" is the post type archive.
                 */
                $matches = explode('/', $slug);

                /* If matches are found for the path. */
                if (isset($matches)) {

                    /* Reverse the array of matches to search for posts in the proper order. */
                    $matches = array_reverse($matches);

                    /* Loop through each of the path matches. */
                    foreach ($matches as $match) {

                        /* If a match is found. */
                        $slug = $match;

                        /* Get public post types that match the rewrite slug. */
                        $post_types = $this->getPostTypesBySlug($match);

                        if (!empty($post_types)) {

                            $post_type_object = $post_types[0];

                            $items[] = array(
                                self::URL   => get_post_type_archive_link($post_type_object->name),
                                self::LABEL => $this->getPostTypeObjectLabel($post_type_object),
                            );

                            $post_type_catched = true;
                            /* Break out of the loop. */
                            break;
                        }
                    }
                }
            }

            /* Add the post type archive link to the trail for custom post types */
            if (!$post_type_catched) {
                $post_type = isset($taxonomy->object_type[0]) ? $taxonomy->object_type[0] : false;

                if ($post_type && 'post' != $post_type) {
                    $post_type_object = get_post_type_object($post_type);

                    $items[] = array(
                        self::URL   => get_post_type_archive_link($post_type_object->name),
                        self::LABEL => $this->getPostTypeObjectLabel($post_type_object),
                    );

                }
            }

        }

        /* If the taxonomy is hierarchical, list its parent terms. */
        if (is_taxonomy_hierarchical($term->taxonomy) && $term->parent) {
            $items = array_merge($items, $this->getTermParents($term->parent, $term->taxonomy));
        }

        $label = single_term_title('', false);

        /* Add the term name to the trail end. */
        if (is_paged()) {
            $items[] = array(
                self::URL   => esc_url(get_term_link($term, $term->taxonomy)),
                self::LABEL => $label,
            );
        }

        $items[] = array(
            self::LABEL => $label,
        );

        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for user (author) archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function author()
    {
        $items = array();
        $wp_rewrite = GlobalLocator::wpRewrite();

        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Get the user ID. */
        $user_id = get_query_var('author');

        /* If $author_base exists, check for parent pages. */
        if (!empty($wp_rewrite->author_base)) {
            $items = array_merge($items, $this->getPathParents($wp_rewrite->author_base));
        }

        $label = get_the_author_meta('display_name', $user_id);

        /* Add the author's display name to the trail end. */
        if (is_paged()) {
            $items[] = array(
                self::URL   => esc_url(get_author_posts_url($user_id)),
                self::LABEL => $label,
            );
        }

        $items[] = array(
            self::LABEL => $label,
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for minute archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function minute()
    {
        $items = array();
        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Add the minute item. */
        $items[] = array(
            self::LABEL => get_the_time(_x('i', 'minute archives time format', 'lolita')),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for hour archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function hour()
    {
        $items = array();
        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Add the minute item. */
        $items[] = array(
            self::LABEL => get_the_time(_x('g a', 'hour archives time format', 'lolita')),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for day archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function day()
    {
        $items = array();
        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Add the year and month items. */
        $items[] = array(
            self::URL   => get_year_link(get_the_time('Y')),
            self::LABEL => get_the_time(_x('Y', 'yearly archives date format', 'lolita')),
        );
        $items[] = array(
            self::URL   => get_month_link(get_the_time('Y'), get_the_time('m')),
            self::LABEL => get_the_time(_x('F', 'monthly archives date format', 'lolita')),
        );

        /* Add the day item. */
        if (is_paged()) {
            $items[] = array(
                self::URL   => get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')),
                self::LABEL => get_the_time(_x('j', 'daily archives date format', 'lolita')),
            );
        }

        $items[] = array(
            self::LABEL => get_the_time(_x('j', 'daily archives date format', 'lolita')),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for week archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function week()
    {
        $items = array();
        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Get the year and week. */
        $items[] = array(
            self::URL   => get_year_link(get_the_time('Y')),
            self::LABEL => get_the_time(_x('Y', 'yearly archives date format', 'lolita')),
        );

        /* Add the week item. */
        if (is_paged()) {
            $items[] = array(
                self::URL => add_query_arg(
                    array(
                        'm' => get_the_time('Y'),
                        'w' => get_the_time('W')
                    ),
                    home_url('/')
                ),
                self::LABEL => get_the_time(_x('W', 'weekly archives date format', 'lolita')),
            );
        }

        $items[] = array(
            self::LABEL => get_the_time(_x('W', 'weekly archives date format', 'lolita')),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for month archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function month()
    {
        $items = array();
        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Get the year and month. */
        $items[] = array(
            self::URL   => get_year_link(get_the_time('Y')),
            self::LABEL => get_the_time(_x('Y', 'yearly archives date format', 'lolita')),
        );

        /* Add the month item. */
        if (is_paged()) {
            $items[] = array(
                self::URL   => get_month_link(get_the_time('Y'), get_the_time('m')),
                self::LABEL => get_the_time(_x('F', 'monthly archives date format', 'lolita')),
            );
        }

        $items[] = array(
            self::LABEL => get_the_time(_x('F', 'monthly archives date format', 'lolita')),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for year archives.
     *
     * @return BreadcrumbTrain instance.
     */
    public function year()
    {
        $items = array();
        /* Add $wp_rewrite->front to the trail. */
        $items = $this->getRewriteFrontItems();

        /* Add the year item. */
        if (is_paged()) {
            $items[] = array(
                self::URL   => get_year_link(get_the_time('Y')),
                self::LABEL => get_the_time(_x('Y', 'yearly archives date format', 'lolita')),
            );
        }

        $items[] = array(
            self::LABEL => get_the_time(_x('Y', 'yearly archives date format', 'lolita')),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for archives that don't have a more specific method
     * defined in this class.
     *
     * @return BreadcrumbTrain instance.
     */
    public function archive()
    {
        $items = array();

        /* If this is a date-/time-based archive, add $wp_rewrite->front to the trail. */
        if (is_date() || is_time()) {
            $items = $this->getRewriteFrontItems();
        }

        $items[] = array(
            self::LABEL => __('Archives', 'lolita'),
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for search results.
     *
     * @return BreadcrumbTrain instance.
     */
    public function search()
    {
        $items = array();
        $label = sprintf(__('Search results for &#8220;%s&#8221;', 'lolita'), get_search_query());

        if (is_paged()) {
            $items[] = array(
                self::URL   => get_search_link(),
                self::LABEL => $label,
            );
        }

        $items[] = array(
            self::LABEL => $label,
        );
        $this->items = array_merge($this->items, $items);
        return $this;
    }

    /**
     * Adds the items to the trail items array for 404 pages.
     *
     * @since  0.6.0
     * @access public
     * @return void
     */
    public function page404()
    {
        return array(
            array(
                self::LABEL => __('404 Not Found', 'lolita'),
            ),
        );
    }

    /**
     * Gets post types by slug.  This is needed because the get_post_types() function doesn't exactly
     * match the 'has_archive' argument when it's set as a string instead of a boolean.
     *
     * @since  4.0.0
     *
     * @param  int    $slug  The post type archive slug to search for.
     */
    public function getPostTypesBySlug($slug)
    {
        $return = array();

        $post_types = get_post_types(array(), 'objects');

        foreach ($post_types as $type) {
            if ($slug === $type->has_archive || (true === $type->has_archive && $slug === $type->rewrite['slug'])) {
                $return[] = $type;
            }
        }

        return $return;
    }

    /**
     * Add post parents link to breadcrumbs items
     *
     * @param integer $post_id first parent post ID
     */
    public function getPostParents($post_id)
    {
        $items     = array();
        $ancestors = get_post_ancestors($post_id);
        array_unshift($ancestors, $post_id);
        if (count($ancestors)) {
            foreach ($ancestors as $id) {
                $items[] = array(
                    self::URL   => get_permalink($id),
                    self::LABEL => get_the_title($id),
                );
            }
            $post_id = $ancestors[ count($ancestors) - 1 ];
        }
        // Get the post hierarchy based off the final parent post.
        $items = array_merge(
            array_reverse($items),
            (array) $this->getPostHierarchy($post_id),
            (array) $this->getPostTerms($post_id)
        );

        return $items;
    }

    /**
     * Get post hierarchy
     * @param  integer $post_id post id.
     * @return array items.
     */
    public function getPostHierarchy($post_id)
    {
        $items = array();
        // Get the post type.
        $post_type        = get_post_type($post_id);
        $post_type_object = get_post_type_object($post_type);

        // If this is the 'post' post type, get the rewrite front items and map the rewrite tags.
        if ('post' === $post_type) {
            // Get permalink specific breadcrumb items
            $items = array_merge(
                (array) $this->getRewriteFrontItems(),
                (array) $this->getMapRewriteTags($post_id, get_option('permalink_structure'))
            );
        } elseif (false !== $post_type_object->rewrite) {
            // Add post type specific items
            if (isset($post_type_object->rewrite['with_front']) && $post_type_object->rewrite['with_front']) {
                $items = $this->getRewriteFrontItems();
            }
        }

        /* If there's an archive page, add it to the trail. */
        if (!empty($post_type_object->has_archive)) {
            $items[] = array(
                self::URL   => get_post_type_archive_link($post_type),
                self::LABEL => $this->getPostTypeObjectLabel($post_type_object),
            );
        }
        return $items;
    }

    /**
     * Get post type object lable
     * @param  object $pto post type object
     * @return string label.
     */
    private function getPostTypeObjectLabel($pto)
    {
        return empty($pto->labels->archive_title) ? $pto->labels->name : $pto->labels->archive_title;
    }

    /**
     * Add front items based on $wp_rewrite->front.
     */
    public function getRewriteFrontItems()
    {
        $wp_rewrite = GlobalLocator::wpRewrite();
        $items = array();

        if ($wp_rewrite->front) {
            $items = $this->getPathParents($wp_rewrite->front);
        }
        return $items;
    }

    /**
     * Get parent posts by path. Currently, this method only supports getting parents of the 'page'
     * post type.  The goal of this function is to create a clear path back to home given what would
     * normally be a "ghost" directory.  If any page matches the given path, it'll be added.
     *
     * @param  string $path The path (slug) to search for posts by.
     */
    public function getPathParents($path)
    {
        $items = array();
        /* Trim '/' off $path in case we just got a simple '/' instead of a real path. */
        $path = trim($path, '/');

        /* If there's no path, return. */
        if (empty($path)) {
            return;
        }

        // process default Cherry permalinks by own way
        if (in_array($path, array( 'tag', 'category' ))) {
            return;
        }

        /* Get parent post by the path. */
        $post = get_page_by_path($path);

        if (!empty($post)) {
            $items = $this->getPostParents($post->ID);
        } elseif (is_null($post)) {

            /* Separate post names into separate paths by '/'. */
            $path = trim($path, '/');
            preg_match_all("/\/.*?\z/", $path, $matches);

            /* If matches are found for the path. */
            if (isset($matches)) {

                /* Reverse the array of matches to search for posts in the proper order. */
                $matches = array_reverse($matches);

                /* Loop through each of the path matches. */
                foreach ($matches as $match) {
                    /* If a match is found. */
                    if (isset($match[0])) {
                        /* Get the parent post by the given path. */
                        $path = str_replace($match[0], '', $path);
                        $post = get_page_by_path(trim($path, '/'));

                        /* If a parent post is found, set the $post_id and break out of the loop. */
                        if (!empty($post) && 0 < $post->ID) {
                            $items = $this->getPostParents($post->ID);
                            break;
                        }
                    }
                }
            }
        }
        return $items;
    }

    /**
     * Turns %tag% from permalink structures into usable links for the breadcrumb trail.
     * This feels kind of hackish for now because we're checking for specific %tag% examples and only doing
     * it for the 'post' post type. In the future, maybe it'll handle a wider variety of possibilities,
     * especially for custom post types.
     *
     * @param  int    $post_id ID of the post whose parents we want.
     * @param  string $path    Path of a potential parent page.
     */
    public function getMapRewriteTags($post_id, $path)
    {
        $items = array();
        /* Get the post based on the post ID. */
        $post = get_post($post_id);

        /* If no post is returned, an error is returned, or the post does not have a 'post' post type, return. */
        if (empty($post) || is_wp_error($post) || 'post' !== $post->post_type) {
            return $items;
        }

        /* Trim '/' from both sides of the $path. */
        $path = trim($path, '/');

        /* Split the $path into an array of strings. */
        $matches = explode('/', $path);

        /* If matches are found for the path. */
        if (!is_array($matches)) {
            return $items;
        }

        /* Loop through each of the matches, adding each to the $trail array. */
        foreach ($matches as $match) {
            $items = array_merge($items, (array) $this->getSingleTag($match, $post_id));
        }
        return $items;
    }

    /**
     * Service function to process single tag item
     *
     * @param  string $tag     single tag.
     * @param  int    $post_id processed post ID.
     */
    public function getSingleTag($tag, $post_id)
    {
        global $post;

        /* Trim any '/' from the $tag. */
        $tag = trim($tag, '/');

        /* If using the %year% tag, add a link to the yearly archive. */
        if ('%year%' == $tag) {
            return array(
                self::URL   => get_year_link(get_the_time('Y', $post_id)),
                self::LABEL => get_the_time(_x('Y', 'yearly archives date format', 'lolita')),
            );

        /* If using the %monthnum% tag, add a link to the monthly archive. */
        } elseif ('%monthnum%' == $tag) {
            return array(
                self::URL => get_month_link(
                    get_the_time('Y', $post_id),
                    get_the_time('m', $post_id)
                ),
                self::LABEL => get_the_time(
                    _x('F', 'monthly archives date format', 'lolita')
                ),
            );

        /* If using the %day% tag, add a link to the daily archive. */
        } elseif ('%day%' == $tag) {
            return array(
                self::URL => get_day_link(
                    get_the_time('Y', $post_id),
                    get_the_time('m', $post_id),
                    get_the_time('d', $post_id)
                ),
                self::LABEL => get_the_time(
                    _x('j', 'daily archives date format', 'lolita')
                ),
            );

        /* If using the %author% tag, add a link to the post author archive. */
        } elseif ('%author%' == $tag) {
            return array(
                self::URL   => get_author_posts_url($post->post_author),
                self::LABEL => get_the_author_meta('display_name', $post->post_author),
            );

        /* If using the %category% tag, add a link to the first category archive to match permalinks. */
        } elseif ('%category%' == $tag) {

            $items = array();
            /* Force override terms in this post type. */
            $this->args['post_taxonomy'][ $post->post_type ] = false;

            /* Get the post categories. */
            $terms = get_the_category($post_id);

            /* Check that categories were returned. */
            if ($terms) {

                /* Sort the terms by ID and get the first category. */
                usort($terms, '_usort_terms_by_ID');
                $term = get_term($terms[0], 'category');

                /* If the category has a parent, add the hierarchy to the trail. */
                if (0 < $term->parent) {
                    $items = $this->getTermParents($term->parent, 'category');
                }

                $items[] = array(
                    self::URL   => get_term_link($term, 'category'),
                    self::LABEL => $term->name,
                );

                return $items;
            }
        }
    }

    /**
     * Searches for term parents of hierarchical taxonomies.
     * This function is similar to the WordPress function get_category_parents() but handles any type of taxonomy.
     *
     * @param  int    $term_id  ID of the term to get the parents of.
     * @param  string $taxonomy Name of the taxonomy for the given term.
     */
    public function getTermParents($term_id, $taxonomy)
    {
        /* Set up some default arrays. */
        $parents = array();

        /* While there is a parent ID, add the parent term link to the $parents array. */
        while ($term_id) {

            /* Get the parent term. */
            $term = get_term($term_id, $taxonomy);

            $parents[] = array(
                self::URL   => get_term_link($term_id, $taxonomy),
                self::LABEL => esc_attr($term->name),
            );

            /* Set the parent term's parent as the parent ID. */
            $term_id = $term->parent;
        }

        return $parents;
    }

    /**
     * Adds a post's terms from a specific taxonomy to the items array.
     *
     * @param  int    $post_id The ID of the post to get the terms for.
     */
    public function getPostTerms($post_id)
    {
        $items = array();
        /* Get the post type. */
        $post_type = get_post_type($post_id);

        /* Add the terms of the taxonomy for this post. */
        if (!empty($this->args['post_taxonomy'][ $post_type ])) {
            $post_terms = wp_get_post_terms($post_id, $this->args['post_taxonomy'][ $post_type ]);

            if (is_array($post_terms) && isset($post_terms[0]) && is_object($post_terms[0])) {
                $term_id = $post_terms[0]->term_id;
                $items = $this->getTermParents($term_id, $this->args['post_taxonomy'][ $post_type ]);
            }
        }
        return $items;
    }
}
