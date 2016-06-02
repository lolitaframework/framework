<!-- .lolita-media-control -->
<div class="lolita-media-control">
    
    <button type="button" class="button button-primary lolita-media-add <?php echo $add_button_hide; ?>">
        <?php _e('Add'); ?>
    </button>
    
    <!-- .media-preview -->
    <div class="media-preview <?php echo $preview_hide; ?>">
        <div class="left">
            <div class="media-preview-inner">
                <img class="media-thumbnail " alt="<?php echo esc_attr($title); ?>" src="<?php echo $src; ?>">
            </div>
        </div>
        <div class="right">
            <div class="media-infos">
                <h4><?php _e('Attachment ID:', 'lolita'); ?></h4>
                <p class="info info-id"><?php echo $value; ?></p>
                <h4><?php _e('Attachment Path:', 'lolita'); ?></h4>
                <p class="info info-path"><?php echo $src; ?></p>
                <div class="media-buttons">
                    <button id="lolita-media-remove"  class="button lolita-button-remove" type="button"><?php _e('Delete'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- .media-preview END -->
    
    <input <?php echo $attributes_str; ?> >
</div>
<!-- .lolita-media-control END -->
