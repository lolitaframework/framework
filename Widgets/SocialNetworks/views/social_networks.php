<?php echo $args['before_widget']; ?>
<?php if ('' !== $title) : ?>
    <?php echo $args['before_title'] . $title . $args['after_title'] ?>
<?php endif ?>
<?php if (count($icons)) : ?>
    <ul>
    <?php foreach ($icons as $icon) : ?>
        <li class="lf_interface_social_networks__item">
            <a href="<?php echo $icon['url']; ?>" target="__blank"><i class="<?php echo $icon[ 'icon_css' ]; ?>"></i><?php echo $icon['content'] ?></a>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>
<?php echo $args['after_widget']; ?>
