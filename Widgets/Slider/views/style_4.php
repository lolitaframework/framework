<?php if ($prepared) : ?>
    <?php echo $args['before_widget']; ?>
    <ul class="lf_interface_slider_style1__container">
        <?php foreach ($prepared as $el) : ?>
            <li>
                <img src="<?php echo $el['img_src']; ?>" alt="Image">
            </li>
        <?php endforeach ?> 
    </ul>
    <?php echo $args['after_widget']; ?>
<?php endif ?>
