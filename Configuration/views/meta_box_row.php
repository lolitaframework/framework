<tr class="lolita-field-container">
    <th class="lolita-label" scope="row">
        <label for="<?php echo $control->getName(); ?>"><?php echo $control->parameters['label']; ?></label>
    </th>
    <td>
        <?php echo $control->render(); ?>
    </td>
</tr>