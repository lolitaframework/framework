<?php if ($instance): ?>
    <?php echo $args['before_widget']; ?>
    <section id="main_slider">
        <ul class="bx_slider auto pager">
            <?php foreach ($instance as $el): ?>
                <li style="background-image:url('<?php echo $el['img_src']; ?>'), url('<?php echo $el['background_src']; ?>')">
                    <div class="container">
                        <span class="duidluck"><?php echo $el['subtitle']; ?></span>
                        <h3><?php echo $el['title']; ?></h3>
                        <?php echo $el['content']; ?>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    </section>
    <?php echo $args['after_widget']; ?>
<?php endif ?>
