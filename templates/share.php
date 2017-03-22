<?php
/**
 * Social networks template
 * Share wish listed items on social networks
 *
 * @author  Algoritmika Ltd.
 * @version 1.2.2
 * @since   1.0.0
 */
?>

<?php
// Email params
$email_active   = isset( $params['email']['active'] ) ? $params['email']['active'] : false;
$email_values   = isset( $params['email']['emails'] ) ? $params['email']['emails'] : '';
$email_message  = isset( $params['email']['message'] ) ? $params['email']['message'] : '';
$email_to_admin = isset( $params['email']['send_to_admin'] ) ? $params['email']['send_to_admin'] : true;
?>

<div class="alg-wc-wl-social">
    <span class="alg-wc-wl-share-on"><?php _e( 'Share on', 'wish-list-for-woocommerce' ); ?></span>
    <ul class="alg-wc-wl-social-ul">

		<?php // Facebook ?>
		<?php if ( $params['facebook']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['facebook']['url'] ); ?>"
                   title="<?php _e( 'Facebook', 'wish-list-for-woocommerce' ) ?>">
                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>

		<?php // Twitter ?>
		<?php if ( $params['twitter']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['twitter']['url'] ); ?>"
                   title="<?php _e( 'Twitter', 'wish-list-for-woocommerce' ) ?>">
                    <i class="fa fa-twitter-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>

		<?php // Google ?>
		<?php if ( $params['google']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['google']['url'] ); ?>"
                   title="<?php _e( 'Google', 'wish-list-for-woocommerce' ) ?>">
                    <i class="fa fa-google-plus-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>

		<?php // Email ?>
		<?php if ( $email_active ): ?>
            <li class="alg-wc-wl-social-li">
                <a class="email" href="<?php echo esc_url( $params['email']['url'] ); ?>"
                   title="<?php _e( 'Email', 'wish-list-for-woocommerce' ) ?>">
                    <i class="fa fa-envelope-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>
    </ul>

	<?php // Email options ?>
	<?php if ( $email_active ): ?>
        <div class="alg-wc-wl-email-options">
            <form method="POST">
                <input type="hidden" name="<?php echo Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL; ?>" value="1"/>
                <label for="alg_wc_wl_emails"><?php echo __( 'Send wish list to these emails', 'wish-list-for-woocommerce' ); ?></label><br/>
                <input value= "<?php echo esc_attr($email_values)?>" class="alg-wc-wl-emails-input" maxlength="254" type="text" id="alg_wc_wl_emails" name="alg_wc_wl_emails" placeholder="<?php echo __( 'Emails (comma separated)', 'wish-list-for-woocommerce' ); ?> "/>
                <textarea name="alg_wc_wl_email_message" class="input-text" type="textarea" placeholder="<?php echo __( 'Message', 'wish-list-for-woocommerce' ); ?>"><?php echo esc_html( $email_message ); ?></textarea>
                <div class="alg-wc-wl-email-admin">
                    <input <?php echo $email_to_admin ? 'checked' : '' ?> type="checkbox" id="alg_wc_wl_email_admin" name="alg_wc_wl_email_admin"/>
                    <label for="alg_wc_wl_email_admin"><?php echo __( 'Notify admin', 'wish-list-for-woocommerce' ); ?>
                        <span class="label-description"><?php echo __( '(The administrator will not be able to view the message nor the email address)', 'wish-list-for-woocommerce' ); ?></span>
                    </label>
                </div>
                <input style="" type="submit"/>
                <div style="clear:both"></div>
            </form>
        </div>
	<?php endif; ?>
</div>