<tr class="lolita-repeater-row">
    <td class="lolita-repeater-order">
        <span>__i__</span>
    </td>
    <td class="lolita-repeater-inner">
        <table>
            <tbody>
                <?php foreach ($template_controls->collection as $k => $control): ?>
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
