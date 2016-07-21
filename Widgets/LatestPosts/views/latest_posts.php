<?php if ($items) : ?>
    <ul class="products_previews">
    <?php foreach ($items as $item) : ?>
        <li>
            <a href="<?php echo get_permalink($item->ID) ?>" class="products_previews__inner">
                <?php if (has_post_thumbnail($item->ID)) : ?>
                    <?php echo get_the_post_thumbnail($item->ID) ?>
                <?php endif ?>
                <div class="products_previews__info">
                    <span class="products_previews__caption"><?php echo get_the_title($item->ID) ?></span>
                </div>
            </a>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>