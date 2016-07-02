<?php echo $args['before_widget']; ?>
    <?php if ($crumbs) : ?>
        <nav>
            <ul>
            <?php foreach ($crumbs as $crumb) : ?>
                <?php if (null === $crumb->getLink()) : ?>
                    <li class="lf_breadcrumbs__item"><span><?php echo $crumb->getLabel() ?></span></li>
                <?php else : ?>
                    <li class="lf_breadcrumbs__item"><a href="<?php echo $crumb->getLink() ?>"><?php echo $crumb->getLabel() ?></a></li>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        </nav>
    <?php endif ?>
<?php echo $args['after_widget']; ?>
