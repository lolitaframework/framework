<!-- .lolita-media-control -->
<div class="lolita-media-control" data-control="media" data-name="<?php echo $me->getName(); ?>">
    
    <button type="button" class="button button-primary lolita-media-add <?php echo $me->addButtonHide() ?>">
        <?php _e('Add', 'lolita'); ?>
    </button>
    
    <!-- .media-preview -->
    <div class="media-preview <?php echo $me->previewHide() ?>">
        <div class="left">
            <div class="media-preview-inner">
                <img class="media-thumbnail " alt="<?php echo esc_attr($title); ?>" src="<?php echo $src; ?>" data-id="<?php echo $me->getValue(); ?>" data-src="<?php echo $src; ?>">
            </div>
        </div>
        <div class="right">
            <div class="media-infos">
                <div class="media-buttons">
                    <button id="lolita-media-remove"  class="button lolita-button-remove" type="button"><?php _e('Delete', 'lolita'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- .media-preview END -->
    
    <input <?php echo $me->getAttributesString(); ?> >
</div>
<!-- .lolita-media-control END -->
