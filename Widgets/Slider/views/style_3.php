<?php if ($instance): ?>
    <?php echo $args['before_widget']; ?>
    <ul class="bx_slider pager">
        <?php foreach ($instance as $el): ?>
            <li>
                <div class="left">
                    <img src="<?php echo $el['img_src']; ?>" alt="Image" />
                </div>
                <div class="right">
                    <h4><?php echo $el['title']; ?></h4>
                    <?php echo $el['content']; ?>
                    <a class="link" href="<?php echo $el['url']; ?>">Lees Meer</a>
                </div>
            </li>
        <?php endforeach ?> 
    </ul>
    <?php echo $args['after_widget']; ?>
<?php endif ?>
