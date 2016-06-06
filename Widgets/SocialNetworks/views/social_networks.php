<?php echo $args['before_widget']; ?>
<?php if (count($instance)) : ?>
    <ul class="<?php echo $id_base; ?>">
    <?php foreach ($instance as $key => $url) : ?>
        <li>
            <a href="<?php echo $url; ?>"><i class="<?php echo $icons[ $key ]; ?>"></i></a>
        </li>
    <?php endforeach ?>
    </ul>
<?php endif ?>
<?php echo $args['after_widget']; ?>
