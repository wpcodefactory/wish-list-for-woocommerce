<?php
/**
 * Wish List for WooCommerce - Query vars
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Query_Vars' ) ) {

	class Alg_WC_Wish_List_Query_Vars {

		/**
		 * Query var for passing wishlist user id
		 *
		 * @since   1.0.0
		 */
		const USER = 'alg_wc_wl_user';

		/**
		 * Query var informing 1 or 0 if the user is registered or not
		 *
		 * @since   1.0.0
		 */
		const USER_UNLOGGED = 'alg_wc_wl_uunlogged';

	}

}