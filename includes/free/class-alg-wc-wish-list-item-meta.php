<?php
/**
 * Wish List for WooCommerce Pro - Item meta
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Item_Meta' ) ) {

	class Alg_WC_Wish_List_Item_Meta {

		/**
		 * Reason for the user has added the item on Wish list
		 *
		 * @since   1.1.2
		 */
		const META_KEY_ITEM_REASON_FOR_ADDING = 'reason_for_adding';
	}
}