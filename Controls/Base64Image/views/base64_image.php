<?php if ('' !== $me->getValue()) : ?>
    <img src="<?php echo $me->getValue() ?>" alt="<?php echo $me->getName() ?>" class="base64-image">
<?php endif ?>
<textarea <?php echo $me->getAttributesString() ?>><?php echo $me->getValue(); ?></textarea>