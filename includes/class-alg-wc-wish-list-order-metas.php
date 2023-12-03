<?php
/**
 * Wish List for WooCommerce Pro - Order metas
 *
 * @version 1.2.9
 * @since   1.2.9
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Order_Metas' ) ) {

	class Alg_WC_Wish_List_Order_Metas {
		CONST WISH_LIST_USER_ID='alg_wl_wc_user_id';
		CONST WISH_LIST_USER_UNLOGGED='alg_wc_wl_uunlogged';
	}
}