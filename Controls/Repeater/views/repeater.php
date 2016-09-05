<div id="<?php echo $me->getID(); ?>"  class="lolita-repeater-container" data-name="<?php echo $me->getName() ?>" data-small-name="<?php echo $me->old_name; ?>">
    <!-- underscore template -->
    <div id="<?php echo $me->getID();?>_template" class="underscore_template">
        <?php echo $me->getTemplate() ?>
    </div>
    <!-- /underscore template -->

    <table class="lolita-repeater">
        <tbody class="lolita-repeater-sortable ui-sortable">
            <?php foreach ($me->rows as $index => $controls) : ?>
                <tr class="lolita-repeater-row">
                    <td class="lolita-repeater-order">
                        <span><?php echo $index; ?></span>
                    </td>
                    <td class="lolita-repeater-inner">
                        <table>
                            <tbody>
                                <?php foreach ($controls->collection as $k => $control) : ?>
                                    <tr class="lolita-field-container">
                                        <td>
                                            <label for="<?php echo $control->getID(); ?>" class="lolita-label">
                                                <?php echo $control->label ?>
                                            </label>
                                            <?php echo $control->render(); ?>
                                            <?php if ('' !== $control->description) : ?>
                                                <small><?php echo $control->description; ?></small>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </td>
                    <td class="lolita-repeater-options">
                        <span class="lolita-repeater-add"></span>
                        <span class="lolita-repeater-remove"></span>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <div class="lolita-repeater-add-field-container">
        <button type="button" class="lolita-repeater-main-add button-primary"><?php _e('Add', 'lolita'); ?></button>
    </div>
</div>