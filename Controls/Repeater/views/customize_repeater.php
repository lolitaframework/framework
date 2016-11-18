<?php if (!empty($control->label)) : ?>
    <span class="customize-control-title"><?php echo esc_html($control->label); ?></span>
<?php endif ?>
<?php if (!empty($control->description)) : ?>
    <span class="description customize-control-description"><?php echo $control->description ; ?></span>
<?php endif ?>
<?php echo $control->render(); ?>