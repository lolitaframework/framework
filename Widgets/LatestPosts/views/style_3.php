<?php if ($items) : ?>
    <ul>
    <?php foreach ($items as $item) : ?>
        <li>
            <?php if (has_post_thumbnail($item->ID)) : ?>
            <div class="related_posts__image">
                <div>
                    <?php echo get_the_post_thumbnail($item->ID, 'thumb') ?>
                </div>
            </div>
            <?php endif ?>
            <div class="related_posts__info">
                <h5><?php echo get_the_title($item->ID) ?></h5>
                <div class="related_posts__text">
                    <?php echo get_the_excerpt($item->ID) ?>
                </div>
                <a href="<?php echo get_permalink($item->ID) ?>" class="related_posts__read_more"><?php _e('Read More', 'lolita') ?></a>
            </div>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>