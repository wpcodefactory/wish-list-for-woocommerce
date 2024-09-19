<?php
/**
 * Wishlist for WooCommerce - Wishlist User Metas
 *
 * @version 3.0.8
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_User_Metas' ) ) {

	class Alg_WC_Wish_List_User_Metas {

		/**
		 * Meta responsible for pointing what item is in user wishlist
		 *
		 * @since   1.0.0
		 */
		const WISH_LIST_ITEM = '_alg_wc_wl_item';

		/**
		 * Meta responsible for saving metas to a wishlist item
		 *
		 * @since   1.2.6
		 */
		const WISH_LIST_ITEM_METAS = '_alg_wc_wl_item_metas';
		
		/**
		 * Meta responsible for pointing what item is in user multiple wishlist
		 *
		 * @since   3.0.8
		 */
		const WISH_LIST_ITEM_MULTIPLE = '_alg_wc_wl_item_multiple';

		/**
		 * Meta responsible for saving metas to a multiple wishlist item
		 *
		 * @since   3.0.8
		 */
		const WISH_LIST_ITEM_METAS_MULTIPLE = '_alg_wc_wl_item_metas_multiple';
		
		/**
		 * Meta is responsible for indicating which items are in the user's multiple wishlist names (used when displaying multiple wishlist names across the entire website).
		 *
		 * @since   3.0.8
		 */
		const WISH_LIST_ITEM_MULTIPLE_NAME = '_alg_wc_wl_item_multiple_name';

		/**
		 * Meta is responsible for saving metas to multiple wishlist names (used when displaying multiple wishlist names across the entire website).
		 *
		 * @since   3.0.8
		 */
		const WISH_LIST_ITEM_METAS_MULTIPLE_NAME = '_alg_wc_wl_item_metas_multiple_name';

	}

}