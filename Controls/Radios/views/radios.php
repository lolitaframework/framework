<?php foreach ($me->getOptions() as $option) : ?>
    <label for="<?php echo $option['id'] ?>" >
        <input name="<?php echo $me->getName() ?>" <?php echo $me->getAttributesString(); ?> id="<?php echo $option['id'] ?>" value="<?php echo $option['key'] ?>" <?php echo $option['checked'] ?> >
        <?php echo $option['label'] ?>
    </label>
<?php endforeach ?>