<?php echo $args['before_widget']; ?>
<?php if ('' !== $title) : ?>
    <?php echo $args['before_title'] . $title . $args['after_title'] ?>
<?php endif ?>
<?php if (count($instance)) : ?>
    <ul>
    <?php foreach ($instance as $key => $url) : ?>
        <li class="lf_social_networks__item">
            <a href="<?php echo $url; ?>"><i class="<?php echo $icons[ $key ]; ?>"></i></a>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>
<?php echo $args['after_widget']; ?>
