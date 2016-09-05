<?php if (!empty($control->description)) :?>
    <span class="description customize-control-description"><?php echo $control->description ; ?></span>
<?php endif ?>
<?php echo $control->render(); ?>