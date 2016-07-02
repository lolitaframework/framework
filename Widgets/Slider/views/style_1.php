<?php if ($instance) : ?>
    <?php echo $args['before_widget']; ?>
    <ul class="bx_slider auto pager">
        <?php foreach ($instance as $el) : ?>
            <li style="background-image:url('<?php echo $el['img_src']; ?>')">
                <a href="<?php echo $el['url']; ?>">
                <span class="lf_slider_style1__logo" style="background-image:url('<?php echo $el['logo_src']; ?>')">Logo</span>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
    <?php echo $args['after_widget']; ?>
<?php endif ?>
