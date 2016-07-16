<?php echo $args['before_widget']; ?>
<?php if ('' !== $instance['success_message']) : ?>
    <span class="lolita-subscribe-success-message" style="display:none;"><?php echo $instance['success_message']; ?></span>
<?php else : ?>
    <span class="lolita-subscribe-success-message" style="display:none;"><?php _e('Message sent successfully.', 'redbrook') ?></span>
<?php endif ?>
<?php if ('' !== $instance['error_message']) : ?>
    <span class="lolita-subscribe-error-message" style="display:none;">
        <?php echo $instance['error_message'] ?>
    </span>
<?php else : ?>
    <span class="lolita-subscribe-error-message" style="display:none;"><?php _e('Message not sent. Please contact the administrator for help.', 'redbrook'); ?></span>
<?php endif ?>
<div class="lf_subscribe">
    <?php if ('' !== $instance['title']) : ?>
        <?php echo $args['before_title'] . $instance['title'] . $args['after_title']; ?>
    <?php endif ?>

    <?php if ('' !== $instance['description']) : ?>
        <span class="description"><?php echo $instance['description']; ?></span>
    <?php endif ?>
    <form id="<?php echo $id_base; ?>" class="lolita-subscribe-form" action="/" method="post" accept-charset="utf-8">
        <input type="email" name="lolita_subscribe_email" required="required" class="lolita-subscribe-email" placeholder="E-mail addres">
        <button type="submit" name="lolita_subscribe_submit" class="animated_button" data-text="Versturen">Versturen</button>
        <input type="hidden" name="lolita_subscribe_mailchimp_api_key" value="<?php echo $instance['mailchimp_api_key']; ?>" class="lolita-subscribe-mailchimp-api-key">
        <input type="hidden" name="lolita_subscribe_mailchimp_list_id" value="<?php echo $instance['mailchimp_list_id']; ?>" class="lolita-subscribe-mailchimp-list-id">
        <input type="hidden" name="lolita_subscribe_type" value="<?php echo $instance['type']; ?>" class="lolita-subscribe-type">
    </form>
</div>
<?php echo $args['after_widget']; ?>
