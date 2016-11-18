<select <?php echo $me->getAttributesString() ?>>
    <?php if (is_array($me->options)) : ?>
        <?php foreach ($me->options as $key => $label) : ?>
            <option value="<?php echo $key; ?>" <?php selected($key, $me->getValue(), true); ?> >
                <?php echo $label; ?>
            </option>
        <?php endforeach ?>
    <?php endif ?>
</select>