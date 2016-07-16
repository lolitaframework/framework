<?php if ($items) :?>
    <?php echo $args['before_widget']; ?>
    <div class="lf_carousel_style2">
        <div class="lf_carousel_style2__container">
            <ul class="clearfix">
                <?php foreach ($items as $el) :?>
                <li>
                    <img src="<?php echo $el['img_src']; ?>" style="width: 484px;height: 275px;" alt="<?php echo esc_attr($el['title']); ?>">
                    <div class="lf_carousel_style2__info">
                        <h4><?php echo $el['title']; ?></h4>
                        <a href="<?php echo $el['url']; ?>" class="lf_carousel_style2__link">Bekijk meer</a>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <ul class="pages">
        </ul>
    </div>
    <?php echo $args['after_widget']; ?>
<?php endif ?>
