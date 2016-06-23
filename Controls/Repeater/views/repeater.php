<div id="<?php echo $me->getID(); ?>"  class="lolita-repeater-container" data-name="<?php echo $name; ?>" data-small-name="<?php echo $small_name; ?>">

    <!-- underscore template -->
    <div id="<?php echo $me->getID();?>_template" class="underscore_template">
        <?php echo $template; ?>
    </div>
    <!-- /underscore template -->

    <table class="lolita-repeater">
        <tbody class="lolita-repeater-sortable ui-sortable">
            <?php foreach ($rows as $index => $controls) : ?>
                <tr class="lolita-repeater-row">
                    <td class="lolita-repeater-order">
                        <span><?php echo $index; ?></span>
                    </td>
                    <td class="lolita-repeater-inner">
                        <table>
                            <tbody>
                                <?php foreach ($controls->collection as $k => $control): ?>
                                    <tr class="lolita-field-container">
                                        <th class="lolita-label">
                                            <label for="<?php echo $control->getID(); ?>"><?php echo $control->parameters['label']; ?></label>
                                        </th>
                                        <td>
                                            <?php echo $control->render(); ?>
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