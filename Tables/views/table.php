<?php if ($table->show_chart) : ?>
    <table class="wp-list-table widefat fixed striped task-transferring">
        <thead>
            <tr>
                <th>Chart</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="background: white">
                    <canvas id="chart-<?php echo $table->slug ?>" width="800" height="400"></canvas>
                </td>
            </tr>
        </tbody>
    </table>
<?php endif ?>

<table class="<?php echo $classes ?>">
    <thead>
        <?php if (count($table->tfoot)) : ?>
            <?php foreach ($table->thead as $row) : ?>
                <tr>
                    <?php foreach ($row as $col) : ?>
                        <th><?php echo $col; ?></th>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
    </thead>
    <tbody>
        <?php if (count($table->tbody)) : ?>
            <?php foreach ($table->tbody as $index => $rows) : ?>
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
        <?php if (count($table->tfoot)) : ?>
            <?php foreach ($table->tfoot as $row) : ?>
                <tr>
                    <?php foreach ($row as $col) : ?>
                        <th><?php echo $col; ?></th>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
    </tfoot>
</table>
