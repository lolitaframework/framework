<div id="<?php echo $me->getID() ?>_iw" class="lf_icons_wrapper">
    <input <?php echo $me->getAttributesString() ?> value="<?php echo $me->getValue() ?>">
    <div class="lf_icons_control_icon"><i></i></div>
    <div class="lf_icon_packs">
        <?php foreach ($me->packs as $pack) : ?>
            <ul class="<?php echo str_replace('.json', '', $pack->getName()) ?>">
                <?php echo $pack->render() ?>
            </ul>
        <?php endforeach ?>
    </div>
</div>