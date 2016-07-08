<?php echo $args['before_widget']; ?>
<?php if ($img) : ?>
    <a href="<?php echo $url; ?>">
        <?php echo wp_get_attachment_image($img, 'full'); ?>
        <?php if ($title) : ?>
            <span class="title"><?php echo $title ?></span>
        <?php endif ?>
    </a>
<?php endif ?>
<?php echo $args['after_widget']; ?>