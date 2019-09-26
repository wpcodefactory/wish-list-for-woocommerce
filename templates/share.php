<?php
/**
 * Social networks template
 * Share wish listed items on social networks
 *
 * @author  Thanks to IT
 * @version 1.3.4
 * @since   1.0.0
 */
?>

<?php
// Email params
$email_active            = isset( $params['email']['active'] ) ? $params['email']['active'] : false;
$email_values            = isset( $params['email']['emails'] ) ? $params['email']['emails'] : '';
$email_message           = isset( $params['email']['message'] ) ? $params['email']['message'] : '';
$email_to_admin          = isset( $params['email']['send_to_admin'] ) ? $params['email']['send_to_admin'] : true;
$need_admin_opt          = isset( $params['email']['need_admin_opt'] ) ? $params['email']['need_admin_opt'] : false;
$from_name               = isset( $params['email']['fromname'] ) ? $params['email']['fromname'] : '';
$from_email              = isset( $params['email']['fromemail'] ) ? $params['email']['fromemail'] : '';
$share_txt               = isset( $params['share_txt'] ) ? $params['share_txt'] : '';
$share_email_friends_txt = isset( $params['email']['share_email_friends_txt'] ) ? $params['email']['share_email_friends_txt'] : '';
$share_email_admin_txt   = isset( $params['email']['share_email_admin_txt'] ) ? $params['email']['share_email_admin_txt'] : '';
?>

<div class="alg-wc-wl-social">
    <span class="alg-wc-wl-share-on"><?php echo $share_txt; ?></span>
    <ul class="alg-wc-wl-social-ul">

        <?php // Facebook ?>
        <?php if ( $params['facebook']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['facebook']['url'] ); ?>"
                   title="<?php _e( 'Facebook', 'wish-list-for-woocommerce' ) ?>">
                    <i class="<?php echo apply_filters( 'alg_wc_wl_fa_icon_class', '', 'facebook' ); ?>" aria-hidden="true"></i>
                </a>
            </li>
        <?php endif; ?>

        <?php // Twitter ?>
        <?php if ( $params['twitter']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['twitter']['url'] ); ?>"
                   title="<?php _e( 'Twitter', 'wish-list-for-woocommerce' ) ?>">
                    <i class="<?php echo apply_filters( 'alg_wc_wl_fa_icon_class', '', 'twitter' ); ?>" aria-hidden="true"></i>
                </a>
            </li>
        <?php endif; ?>

        <?php // Google ?>
        <?php if ( $params['google']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['google']['url'] ); ?>"
                   title="<?php _e( 'Google', 'wish-list-for-woocommerce' ) ?>">
                    <i class="<?php echo apply_filters( 'alg_wc_wl_fa_icon_class', '', 'google_plus' ); ?>" aria-hidden="true"></i>
                </a>
            </li>
        <?php endif; ?>

        <?php // Email ?>
        <?php if ( $email_active ): ?>
            <li class="alg-wc-wl-social-li">
                <a class="email" href=""
                   title="<?php _e( 'Email', 'wish-list-for-woocommerce' ) ?>">
                    <i class="<?php echo apply_filters( 'alg_wc_wl_fa_icon_class', '', 'email' ); ?>" aria-hidden="true"></i>
                </a>
            </li>
        <?php endif; ?>

	    <?php // Copy ?>
	    <?php if ( $params['copy']['active'] ): ?>
            <li class="alg-wc-wl-social-li" style="font-size:26px">
                <a target="_blank" class="copy" href="<?php echo esc_url( $params['copy']['url'] ); ?>"
                   title="<?php _e( 'Copy', 'wish-list-for-woocommerce' ) ?>">
                    <i class="<?php echo apply_filters( 'alg_wc_wl_fa_icon_class', '', 'copy' ); ?>" aria-hidden="true"></i>
                </a>
            </li>
	    <?php endif; ?>
    </ul>

    <?php // Email options ?>
    <?php if ( $email_active ): ?>
        <div class="alg-wc-wl-email-options">
            <form method="POST">

                <div class="alg-wc-email-from">
                    <input type="hidden" name="<?php echo Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL; ?>" value="1"/>
                    <label class="alg-wc-wl-email-section"><?php echo __( 'From:', 'wish-list-for-woocommerce' ); ?></label><br/>

                    <div class="display-flex">
                    <input value="<?php echo esc_attr( $from_name ) ?>" class="alg-wc-wl-input alg-wc-wl-from-name" maxlength="254"
                           type="text" id="alg_wc_wl_from_name" name="alg_wc_wl_from_name"
                           placeholder="<?php echo __( 'Name', 'wish-list-for-woocommerce' ); ?> "/>

                    <input value="<?php echo esc_attr( $from_email ) ?>" class="alg-wc-wl-input alg-wc-wl-from-email" maxlength="254"
                           type="text" id="alg_wc_wl_from_email" name="alg_wc_wl_from_email"
                           placeholder="<?php echo __( 'Email', 'wish-list-for-woocommerce' ); ?> "/>
                    </div>
                </div>

                <div class="alg-wc-email-message">
                    <label for="alg_wc_wl_email_message" class="alg-wc-wl-email-section"><?php echo __( 'Message:', 'wish-list-for-woocommerce' ); ?></label><br/>
                    <textarea id="alg_wc_wl_email_message" name="alg_wc_wl_email_message" class="input-text" type="textarea" placeholder="<?php echo __( 'Message', 'wish-list-for-woocommerce' ); ?>"><?php echo esc_html( $email_message ); ?></textarea>
                </div>

                <div class="alg-wc-email-send-to">
                    <input type="hidden" name="<?php echo Alg_WC_Wish_List_Query_Vars::SEND_BY_EMAIL; ?>" value="1"/>
                    <label class="alg-wc-wl-email-section"><?php echo __( 'Send to:', 'wish-list-for-woocommerce' ); ?></label>

                    <input class="alg-wc-wl-radio" checked type="radio" id="alg_wc_wl_email_friends"
                           name="alg_wc_wl_email_send_to" value="friends">
                    <label class="alg-wc-wl-radio-label"
                           for="alg_wc_wl_email_friends"><?php echo $share_email_friends_txt; ?></label>

                    <?php if ( $need_admin_opt ) : ?>
                        <input class="alg-wc-wl-radio" type="radio" id="alg_wc_wl_email_admin"
                               name="alg_wc_wl_email_send_to" value="admin">
                        <label class="alg-wc-wl-radio-label"
                               for="alg_wc_wl_email_admin"><?php echo $share_email_admin_txt; ?>
                        </label>
                    <?php endif; ?>
                    <input value= "<?php echo esc_attr($email_values)?>" class="alg-wc-wl-emails-input alg-wc-wl-input" maxlength="254" type="text" id="alg_wc_wl_emails" name="alg_wc_wl_emails" placeholder="<?php echo __( 'Emails (comma separated)', 'wish-list-for-woocommerce' ); ?> "/>
                </div>

                <input style="" type="submit" class="button" value="<?php echo __( 'Submit', 'wish-list-for-woocommerce' ); ?>"/>
                <div style="clear:both"></div>
            </form>
        </div>
    <?php endif; ?>
</div>