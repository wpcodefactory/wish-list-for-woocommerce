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
		<?php if ( $params['email']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a class="email" href="<?php echo esc_url( $params['email']['url'] ); ?>"
                   title="<?php _e( 'Email', 'wish-list-for-woocommerce' ) ?>">
                    <i class="fa fa-envelope-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>
    </ul>

	<?php // Email options ?>
	<?php if ( $params['email']['active'] ): ?>
        <div class="alg-wc-wl-email-options">
            <form method="POST">
                <input type="hidden" name="<?php echo Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL;?>" value="1"/>
                <label for="alg_wc_wl_emails"><?php echo __( 'Send wish list to these emails', 'wish-list-for-woocommerce' ); ?></label><br/>
                <input maxlength="254" type="email" id="alg_wc_wl_emails" name="alg_wc_wl_emails" placeholder="<?php echo __( 'Emails (comma separated)', 'wish-list-for-woocommerce' ); ?> "/>
                <textarea class="input-text" type="textarea" placeholder="<?php echo __( 'Message', 'wish-list-for-woocommerce' ); ?>"></textarea>
                <div class="alg-wc-wl-email-admin">
                    <input checked maxlength="254" type="checkbox" id="alg_wc_wl_email_admin" name="alg_wc_wl_email_admin" />
                    <label for="alg_wc_wl_email_admin"><?php echo __( 'Notify admin', 'wish-list-for-woocommerce' ); ?><span class="label-description"><?php echo __( '(The administrator will not be able to view the message nor the emails)', 'wish-list-for-woocommerce' ); ?></span></label><br/>
                </div>
                <input style="" type="submit" />
                <div style="clear:both"></div>
            </form>
        </div>
	<?php endif; ?>
</div>