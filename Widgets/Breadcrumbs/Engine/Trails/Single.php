<?php
namespace zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Trails;

use \zorgboerderij_lenteheuvel_wp\LolitaFramework\Widgets\Breadcrumbs\Engine\Crumb;

class Single extends Trail
{

    /**
     * Compile single crumbs
     * @return Single instance.
     */
    public function compile()
    {
        // Get the queried post.
        $post = get_queried_object();

        /* If the post has a parent, follow the parent trail. */
        if (0 < $post->post_parent) {
            $this->postParents($post->ID);
        } else {
            /* If the post doesn't have a parent, get its hierarchy based off the post type. */
            //$crumbs = $this->getPostHierarchy($post_id);
        }

        /* Display terms for specific post type taxonomy if requested. */
        $this->postTerms($post->ID);

        /* End with the post title. */
        if ($post_title = single_post_title('', false)) {
            if (1 < get_query_var('page')) {
                $this->crumbs[] = new Crumb($post_title, get_permalink($post->ID));
            }

            $this->crumbs[] = new Crumb($post_title, get_permalink($post->ID));
        }

        return $this;
    }
}
