<?php
/**
 * Wish List for WooCommerce - Transients
 *
 * @version 1.1.5
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Transients' ) ) {

	class Alg_WC_Wish_List_Transients {

		/**
		 * Transient responsible for saving the wish list of unlogged users
		 *
		 * @version 1.1.5
		 * @since   1.0.0
		 */
		const WISH_LIST = 'alg_wc_wl_';

	}

}