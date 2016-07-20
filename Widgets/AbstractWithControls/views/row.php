<div id="<?php echo $control->getID(); ?>_row" class="widget_control_row">
    <p>
        <label for="<?php echo $control->getID(); ?>">
            <?php echo $control->label; ?>:
        </label>
    </p>
    <?php echo $control->render(); ?>
</div>