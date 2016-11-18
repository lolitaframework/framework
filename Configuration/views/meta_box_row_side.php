<p>
    <label for="<?php echo $control->getName(); ?>"><?php echo $control->label; ?></label>
</p>
<p>
    <?php echo $control->render(); ?>
    <?php if ('' !== $control->description) : ?>
        <small><?php echo $control->description; ?></small>
    <?php endif ?>
</p>