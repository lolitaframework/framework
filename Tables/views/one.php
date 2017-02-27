<table <?php echo $attributes ?>>
    <thead>
        <?php if (count($thead)) : ?>
            <?php foreach ($thead as $row) : ?>
                <tr>
                    <?php foreach ($row as $col) : ?>
                        <th><?php echo $col; ?></th>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
    </thead>
    <tbody>
        <?php if (count($tbody)) : ?>
            <?php foreach ($tbody as $index => $rows) : ?>
                <tr data-index="<?php echo $index ?>">
                    <?php foreach ($rows as $col) : ?>
                        <td><?php echo $col ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        <?php else : ?>
            <tr><td><?php _e('No rows avaliable.', 'codingninjas'); ?></td></tr>
        <?php endif ?>
    </tbody>
    <tfoot>
        <?php if (count($tfoot)) : ?>
            <?php foreach ($tfoot as $row) : ?>
                <tr>
                    <?php foreach ($row as $col) : ?>
                        <th><?php echo $col; ?></th>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
    </tfoot>
</table>
