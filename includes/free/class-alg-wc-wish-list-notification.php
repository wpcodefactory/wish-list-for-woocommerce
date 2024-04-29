<?php
/**
 * Wishlist for WooCommerce - Notification
 *
 * @version 1.5.7
 * @since   1.1.1
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Pro_Notification' ) ) {

	class Alg_WC_Wish_List_Notification {

		/**
		 * Load notification options in js
		 *
		 * @version 1.5.7
		 * @since   1.1.1
		 * @param type $script
		 */
		public static function localize_script( $script ) {
			$options = array(
				'desktop'   => filter_var( get_option( Alg_WC_Wish_List_Settings_Notification::OPTION_ENABLE_DESKTOP, true ), FILTER_VALIDATE_BOOLEAN ),
				'mobile'    => filter_var( get_option( Alg_WC_Wish_List_Settings_Notification::OPTION_ENABLE_MOBILE, true ), FILTER_VALIDATE_BOOLEAN ),
				'ok_button' => filter_var( get_option( Alg_WC_Wish_List_Settings_Notification::OPTION_SHOW_OK_BUTTON, true ), FILTER_VALIDATE_BOOLEAN ),
				'copied_message' => __( 'Wishlist URL copied to clipboard', 'wish-list-for-woocommerce' ),
			);
			$options = apply_filters('alg_wc_wl_localize',$options,'alg_wc_wl_notification');
			wp_localize_script( $script, 'alg_wc_wl_notification', $options );
		}
	}

}