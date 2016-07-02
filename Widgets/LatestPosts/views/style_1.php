<?php if ($items) : ?>
    <?php $i = 1; ?>
    <?php foreach ($items as $item) : ?>
        <div class="preview row item_<?php echo $i ?>">
            <div class="inner">
                <h3><?php echo get_the_title($item->ID) ?></h3>
                <?php $subtitle = (string) get_post_meta($item->ID, 'post_additional_settings_subtitle', true) ?>
                <?php if ('' !== $subtitle): ?>
                    <h5><?php echo $subtitle ?></h5>
                <?php endif ?>
                <div class="text">
                    <?php echo $item->post_excerpt; ?>
                </div>
                <a href="<?php echo get_permalink($item->ID) ?>" class="more_info">More info</a>
                <?php if (has_post_thumbnail($item->ID)) : ?>
                    <?php echo get_the_post_thumbnail($item->ID) ?>
                <?php endif ?>
            </div>
        </div>
        <?php $i++; ?>
    <?php endforeach ?>
<?php endif ?>
