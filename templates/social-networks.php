<?php
/**
 * Social networks template
 * Share wish listed items on social networks
 *
 * @author  Algoritmika Ltd.
 * @version 1.0.0
 */
?>

<div class="alg-wc-wl-social">
    <span class="alg-wc-wl-share-on"><?php _e( 'Share on', ALG_WC_WL_DOMAIN ); ?></span>
    <ul class="alg-wc-wl-social-ul">
		<?php if ( $params['facebook']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['facebook']['url'] ); ?>"
                   title="<?php _e( 'Facebook', ALG_WC_WL_DOMAIN ) ?>">
                    <i class="fa fa-facebook-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>

		<?php if ( $params['twitter']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['twitter']['url'] ); ?>"
                   title="<?php _e( 'Twitter', ALG_WC_WL_DOMAIN ) ?>">
                    <i class="fa fa-twitter-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>

		<?php if ( $params['google']['active'] ): ?>
            <li class="alg-wc-wl-social-li">
                <a target="_blank" class="facebook" href="<?php echo esc_url( $params['google']['url'] ); ?>"
                   title="<?php _e( 'Google', ALG_WC_WL_DOMAIN ) ?>">
                    <i class="fa fa-google-plus-square" aria-hidden="true"></i>
                </a>
            </li>
		<?php endif; ?>
    </ul>
</div>