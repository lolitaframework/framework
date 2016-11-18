<tr class="lolita-repeater-row">
    <td class="lolita-repeater-order">
        <span>__i__</span>
    </td>
    <td class="lolita-repeater-inner">
        <table>
            <tbody>
                <?php foreach ($me->template_controls->collection as $k => $control) :?>
                    <tr class="lolita-field-container">
                        <td>
                            <label for="<?php echo $control->getID(); ?>"><?php echo $control->label ?></label>
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
