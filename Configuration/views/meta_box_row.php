<tr class="lolita-field-container">
    <th class="lolita-label" scope="row">
        <label for="<?php echo $control->getName(); ?>"><?php echo $control->label; ?></label>
    </th>
    <td>
        <?php echo $control->render(); ?>
        <?php if ('' !== $control->description) : ?>
            <small><?php echo $control->description; ?></small>
        <?php endif ?>
    </td>
</tr>