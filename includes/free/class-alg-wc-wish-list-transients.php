<?php
/**
 * Wishlist for WooCommerce - Transients
 *
 * @version 3.0.8
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Transients' ) ) {

	class Alg_WC_Wish_List_Transients {

		/**
		 * Transient responsible for saving the wishlist of unlogged users
		 *
		 * @version 1.1.5
		 * @since   1.0.0
		 */
		const WISH_LIST = 'alg_wc_wl_';

		/**
		 * Transient responsible for saving the wishlist metas of unlogged users
		 *
		 * @version 1.2.6
		 * @since   1.2.6
		 */
		const WISH_LIST_METAS = 'alg_wc_wlm_';
		
		/**
		 * Transient responsible for saving the multiple wishlist name.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		const WISH_LIST_MULTIPLE = 'alg_wc_wl_multiple_';

		/**
		 * Transient responsible for saving the multiple wishlist meta.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		const WISH_LIST_METAS_MULTIPLE = 'alg_wc_wlm_multiple_';
		
		/**
		 * Transient responsible for the saving multiple wishlist item.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		const WISH_LIST_MULTIPLE_STORE = 'alg_wc_wl_multiple_store_';

		/**
		 * Transient responsible for saving multiple wishlist item meta data.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		const WISH_LIST_METAS_MULTIPLE_STORE = 'alg_wc_wlm_multiple_store_';

	}

}