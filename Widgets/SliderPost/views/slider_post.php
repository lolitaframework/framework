<?php echo $args['before_widget']; ?>
    <?php if ($items) : ?>
        <ul class="lf_slider_post__bx_slider auto">
            <?php foreach ($items as $item) : ?>
                <?php if (has_post_thumbnail($item->ID)) : ?>
                    <li class="lf_slider_post__item" style="background-image: url('<?php echo $item->img ?>');">
                        <div class="lf_slider_post_outer_container">
                            <div class="lf_slider_post_inner_container">
                                <h4 class="lf_slider_post__title">
                                    <span><?php echo get_the_title($item->ID); ?></span>
                                </h4>
                                <h5 class="lf_slider_post__sub_title">
                                    <span><?php echo $item->subtitle ?></span>
                                </h5>
                                <div class="lf_slider_post__html">
                                    <?php echo $item->post_content ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
<?php echo $args['after_widget']; ?>
