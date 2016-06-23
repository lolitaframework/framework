<?php if ($instance): ?>
    <?php echo $args['before_widget']; ?>
    <div id="sly_carousel" class="sly basic horizontal mouse touch">
        <div class="frame">
            <ul class="items">
                <?php foreach ($instance as $el): ?>
                <li>
                    <img src="<?php echo $el['img_src']; ?>" alt="<?php echo esc_attr($el['title']); ?>">
                    <div class="info">
                        <h4><?php echo $el['title']; ?></h4>
                        <p><?php echo $el['content']; ?></p>
                    </div>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="controls container">
            <button class="next_slide">Next</button>
        </div>
    </div>
    <?php echo $args['after_widget']; ?>
<?php endif ?>
