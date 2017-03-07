<?php
/**
 * Wish List for WooCommerce - Wish list Item
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Item' ) ) {

	class Alg_WC_Wish_List_Item {

		/**
		 * Add item to wishlist user
		 *
		 * @version 1.1.5
		 * @since   1.0.0
		 * @param type $item_id
		 * @param type $user_id
		 * @return type
		 */
		public static function add_item_to_wish_list( $item_id, $user_id = null ) {
			if ( $user_id ) {
				$response = add_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false );
			} else {
				$user_id   = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
				$transient = Alg_WC_Wish_List_Transients::WISH_LIST;
				$wish_list = Alg_WC_Wish_List::get_wish_list( $user_id, true );
				if(!$wish_list){
					$wish_list=array();
				}
				array_push( $wish_list, $item_id );
				set_transient( "{$transient}{$user_id}", $wish_list, 1 * MONTH_IN_SECONDS );
				$response = $item_id;
			}

			return $response;
		}

		/**
		 * Remove item from wishlist user
		 *
		 * @version 1.1.5
		 * @since   1.0.0
		 * @param   type $item_id
		 * @param   type $user_id
		 * @return  boolean
		 */
		public static function remove_item_from_wish_list( $item_id, $user_id = null ) {
			if ( $user_id ) {
				$response = delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false );
			} else {
				$user_id   = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
				$transient = Alg_WC_Wish_List_Transients::WISH_LIST;
				$wish_list = Alg_WC_Wish_List::get_wish_list( $user_id, true );
				if(!$wish_list){
					$wish_list=array();
				}
				$index = array_search( $item_id, $wish_list );
				unset( $wish_list[$index] );
				set_transient( "{$transient}{$user_id}", $wish_list, 1 * MONTH_IN_SECONDS );
				$response = $item_id;
			}
			return $response;
		}

		/**
		 * Check if an item is already in the user wish list
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   type $item_id
		 * @param   type $user_id
		 * @return  boolean
		 */
		public static function is_item_in_wish_list( $item_id, $user_id = null ) {
			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id );
			$response = false;
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				$index = array_search( $item_id, $wishlisted_items );
				if ( $index === false ) {
					$response = false;
				} else {
					$response = true;
				}
			} else {
				$response = false;
			}
			return $response;
		}

		/**
		 * Remove or add an Item from User Wishlist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param   type $item_id
		 * @param   type $user_id
		 * @return  type
		 */
		public static function toggle_item_from_wish_list( $item_id, $user_id = null ) {
			if ( self::is_item_in_wish_list( $item_id, $user_id ) ) {
				$response = self::remove_item_from_wish_list( $item_id, $user_id );
			} else {
				$response = self::add_item_to_wish_list( $item_id, $user_id );
			}
			return $response;
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  type
		 */
		public static function get_class_name() {
			return get_called_class();
		}

	}

}