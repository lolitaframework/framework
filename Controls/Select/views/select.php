<select <?php echo $attributes_str; ?>>
    <?php if (is_array($options)) : ?>
        <?php foreach ($options as $key => $label) : ?>
            <option value="<?php echo $key; ?>" <?php selected($key, $value, true); ?> >
                <?php echo $label; ?>
            </option>
        <?php endforeach ?>
    <?php endif ?>
</select>