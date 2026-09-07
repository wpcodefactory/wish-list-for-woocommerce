<?php
/**
 * Wishlist for WooCommerce - Order metas
 *
 * @version 3.5.1
 * @since   1.2.9
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Order_Metas' ) ) {

	class Alg_WC_Wish_List_Order_Metas {
		const WISH_LIST_USER_ID       = 'alg_wl_wc_user_id';
		const WISH_LIST_USER_UNLOGGED = 'alg_wc_wl_uunlogged';
	}
}