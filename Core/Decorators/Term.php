<?php

namespace lolitatheme\LolitaFramework\Core\Decorators;

use \lolitatheme\LolitaFramework\Core\Str;
use \Exception;

class Term
{
    /**
     * Get tax_query for get_posts() from terms
     *
     * @param  array  $terms
     * @param  string $relation
     * @return array
     */
    public static function termsToQuery(array $terms = array(), $relation = 'OR')
    {
        $query  = array();
        $by_tax = array();

        foreach ($terms as $term) {
            $by_tax[ $term->taxonomy ][] = $term->term_id;
        }

        foreach ($by_tax as $tax => $terms) {
            $query[] = array(
                'taxonomy' => $tax,
                'field'    => 'term_id',
                'terms'    => $terms,
            );
        }
        $query['relation'] = $relation;
        return $query;
    }
}
