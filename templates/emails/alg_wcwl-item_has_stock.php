<?php
/**
 * Wish List for WooCommerce Pro - Stock email
 *
 * Template used to display the wishlist on user profile page.
 *
 * @author  WPFactory.
 * @version 1.3.2
 * @since   1.3.2
 */
?>

<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <?php echo $message; ?>

<?php

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
