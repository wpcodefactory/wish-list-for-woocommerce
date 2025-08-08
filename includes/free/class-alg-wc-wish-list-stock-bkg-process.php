<?php
/**
 * Wish List for WooCommerce Pro - Background process
 *
 * @version 3.2.7
 * @since   1.3.2
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'WP_Async_Request', false ) ) {
	include_once dirname( WC_PLUGIN_FILE ) . '/includes/libraries/wp-async-request.php';
}
if ( ! class_exists( 'WP_Background_Process', false ) ) {
	include_once dirname( WC_PLUGIN_FILE ) . '/includes/libraries/wp-background-process.php';
}

if ( ! class_exists( 'Alg_WC_Wish_List_Stock_Bkg_Process' ) ) {

	class Alg_WC_Wish_List_Stock_Bkg_Process extends \WP_Background_Process {

		/**
		 * @var string
		 */
		protected $action = 'alg_wcwl_stock_alert';

		protected function task( $item ) {
			$email = WC()->mailer()->emails['Alg_WC_Wish_List_Stock_Email'];
			$email->trigger_mail( $item['product_id'], $item['email'], $item['user_id'], $item['user_registered'] );

			return false;
		}
	}
}