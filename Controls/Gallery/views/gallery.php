<div id="<?php echo $me->getID() ?>" class="lolita-collection-wrapper" data-name="<?php echo $name ?>" data-control="gallery">
    <!-- underscore template -->
    <div id="<?php echo $me->getID() ?>_template" class="underscore_template">
        <?php echo $me->getTemplate() ?>
    </div>
    <!-- /underscore template -->

    <div class="lolita-collection-container">
        <!-- Collection -->
        <div class="lolita-collection">
            <ul class="lolita-collection-list ui-sortable">
                <?php if (is_array($items) && count($items)) : ?>
                    <?php foreach ($items as $item) : ?>
                        <li>
                            <input type="hidden" name="<?php echo $name;?>[]" value="<?php echo $item->ID; ?>"/>
                            <div class="lolita-collection__item">
                                <div class="centered">
                                    <img src="<?php echo $item->src; ?>" alt="Collection Item">
                                </div>
                                <a class="check" title="Remove" href="#">
                                    <div class="media-modal-icon"></div>
                                </a>
                            </div>
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        </div>
        <!-- End collection -->
    </div>

    <div class="lolita-collection-buttons">
        <button id="lolita-collection-add" type="button" class="button button-primary"><?php _e('Add', 'lolita'); ?></button>
        <button id="lolita-collection-remove" type="button" class="button lolita-button-remove hide"><?php _e('Remove', 'lolita'); ?></button>
    </div>
</div>