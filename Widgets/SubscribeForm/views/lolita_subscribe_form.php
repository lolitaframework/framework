<?php echo $args['before_widget']; ?>
<?php if ('' !== $instance['success_message']) : ?>
    <span class="lolita-subscribe-success-message" style="display:none;"><?php echo $instance['success_message']; ?></span>
<?php else : ?>
    <span class="lolita-subscribe-success-message" style="display:none;"><?php _e('Message sent successfully.', 'redbrook') ?></span>
<?php endif; ?>

<?php if ('' !== $instance['error_message']) : ?>
    <span class="lolita-subscribe-error-message" style="display:none;"><?php $instance['error_message'] ?></span>
<?php else : ?>
    <span class="lolita-subscribe-error-message" style="display:none;"><?php _e('Message not sent. Please contact the administrator for help.', 'redbrook'); ?></span>
<?php endif; ?>
<div class="lolita-subscribe">
    <?php if ('' !== $instance['title']) : ?>
        <?php echo $args['before_title'] . $instance['title'] . $args['after_title']; ?>
    <?php endif; ?>

    <?php if ('' !== $instance['description']) : ?>
        <span class="description"><?php echo $instance['description']; ?></span>
    <?php endif; ?>
    <form id="<?php echo $id_base; ?>" class="lolita-subscribe-form" action="" method="post" accept-charset="utf-8">
        <input type="email" name="lolita_subscribe_email" required="required" class="lolita-subscribe-email">
    </form>
</div>
<?php echo $args['after_widget']; ?>
