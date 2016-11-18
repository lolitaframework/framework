<li class="title"><span class="title"><?php echo $me->getTitle() ?></span></li>
<?php foreach ($me->getIcons() as $icon) : ?>
    <li><a href="#<?php echo $icon ?>"><i class="<?php echo $icon ?>"></i></a></li>
<?php endforeach ?>
