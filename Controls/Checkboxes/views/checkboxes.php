<?php foreach ($me->getOptions() as $option) : ?>
    <label for="<?php echo $option['id'] ?>" >
        <input name="<?php echo $option['name'] ?>" <?php echo $me->getAttributesString(); ?> id="<?php echo $option['id'] ?>" <?php echo $option['checked'] ?> >
        <?php echo $option['label'] ?>
    </label>
<?php endforeach ?>