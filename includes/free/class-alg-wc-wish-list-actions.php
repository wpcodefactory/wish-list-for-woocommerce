<?php
/**
 * Wishlist for WooCommerce - Custom Actions
 *
 * @version 1.5.8
 * @since   1.0.0
 * @author  WPFactory
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Actions' ) ) {

	class Alg_WC_Wish_List_Actions {

		/**
		 * Before wishlist table
		 *
		 * @since   1.0.0
		 */
		const WISH_LIST_TABLE_BEFORE='alg_wc_wl_table_before';

		/**
		 * After wishlist table
		 *
		 * @since   1.0.0
		 */
		const WISH_LIST_TABLE_AFTER='alg_wc_wl_table_after';
	}
}