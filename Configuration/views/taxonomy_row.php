<div class="form-field term-group">
    <label for="<?php echo $control->getName(); ?>"><?php echo $control->label; ?></label>
    <?php echo $control->render(); ?>
    <?php if ('' !== $control->description) : ?>
        <small><?php echo $control->description; ?></small>
    <?php endif ?>
</div>