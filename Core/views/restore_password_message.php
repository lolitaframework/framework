<?php _e('Someone has requested a password reset for the following account:', 'codingninjas') ?>

<?php echo network_home_url('/') ?>

<?php printf(__('Username: %s'), $user_login) ?>

<?php _e('If this was a mistake, just ignore this email and nothing will happen.') ?>

<?php _e('To reset your password, visit the following address:') ?>

<?php if ('' != $reset_url) : ?>
    <?php echo $reset_url; ?>
<?php else : ?>
    <<?php echo network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') ?>>
<?php endif ?>
