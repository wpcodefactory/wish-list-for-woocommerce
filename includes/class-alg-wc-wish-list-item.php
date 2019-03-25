<?php
/**
 * Wish List for WooCommerce - Wish list Item
 *
 * @version 1.4.1
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Item' ) ) {

	class Alg_WC_Wish_List_Item {

		/**
		 * Add item to wishlist user
		 *
		 * @version 1.1.6
		 * @since   1.0.0
		 * @param type $item_id
		 * @param type $user_id
		 * @return type
		 */
		public static function add_item_to_wish_list( $item_id, $user_id = null, $use_id_from_unlogged_user = false ) {
			if ( ! $use_id_from_unlogged_user ) {
				$response = add_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false );
			} else {
				$transient = Alg_WC_Wish_List_Transients::WISH_LIST;
				$wish_list = Alg_WC_Wish_List::get_wish_list( $user_id, true );
				if ( ! $wish_list ) {
					$wish_list = array();
				}
				array_push( $wish_list, $item_id );
				set_transient( "{$transient}{$user_id}", $wish_list, 1 * MONTH_IN_SECONDS );
				$response = $item_id;
			}

			return $response;
		}

		/**
		 * Add metas to wish list item
		 *
		 * @version 1.2.6
		 * @since   1.2.6
		 *
		 * @param      $item_id
		 * @param      $meta_key
		 * @param      $meta_value
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 *
		 * @return bool|false|int
		 */
		public static function update_wish_list_item_metas( $item_id, $meta_key, $meta_value, $user_id = null, $use_id_from_unlogged_user = false ) {
			$response = false;

			// Get a meta from user meta (if is logged) or from transient if isn't logged
			if ( ! $use_id_from_unlogged_user ) {
				$old_user_meta = get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS, true );
			} else {
				$transient     = Alg_WC_Wish_List_Transients::WISH_LIST_METAS;
				if(!$user_id){
					$user_id = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
				}
				$old_user_meta = get_transient( "{$transient}{$user_id}" );
			}

			// If there is an old meta, update only that product id with a specific meta
			if ( $old_user_meta ) {
				$old_user_meta[ $item_id ][ $meta_key ] = $meta_value;
				$new_user_meta                          = $old_user_meta;
				$old_user_meta_item_id = array_filter( $old_user_meta[ $item_id ] );

				// If a item id is empty, erase it from database
				if ( empty( $old_user_meta_item_id ) ) {
					$new_user_meta = $old_user_meta;
					unset( $new_user_meta[ $item_id ] );
				}
			} else {

				// If there isn't a meta, create it
				$new_user_meta = array(
					$item_id => array(
						$meta_key => $meta_value,
					),
				);
			}

			// Update meta
			if ( ! $use_id_from_unlogged_user ) {
				$response = update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS, $new_user_meta );
			} else {
				set_transient( "{$transient}{$user_id}", $new_user_meta, 1 * MONTH_IN_SECONDS );
				$response = $item_id;
			}

			return $response;
		}

		/**
		 * Remove item from wishlist user
		 *
		 * @version 1.4.1
		 * @since   1.0.0
		 * @param   type $item_id
		 * @param   type $user_id
		 * @return  boolean
		 */
		public static function remove_item_from_wish_list( $item_id, $user_id = null, $use_id_from_unlogged_user = false ) {
			if ( ! $use_id_from_unlogged_user ) {
				$response = delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false );
			} else {
				if ( ! $user_id ) {
					$user_id = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
				}
				$transient = Alg_WC_Wish_List_Transients::WISH_LIST;
				$wish_list = Alg_WC_Wish_List::get_wish_list( $user_id, true );
				if ( ! $wish_list ) {
					$wish_list = array();
				}
				$index = array_search( $item_id, $wish_list );
				unset( $wish_list[ $index ] );
				$response = set_transient( "{$transient}{$user_id}", $wish_list, 1 * MONTH_IN_SECONDS );
			}

			return $response;
		}

		/**
		 * Check if an item is already in the user wish list
		 *
		 * @version 1.1.6
		 * @since   1.0.0
		 * @param   type $item_id
		 * @param   type $user_id
		 * @return  boolean
		 */
		public static function is_item_in_wish_list( $item_id, $user_id = null, $use_id_from_unlogged_user = false ) {
			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
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
		 * @version 1.1.6
		 * @since   1.0.0
		 * @param   type $item_id
		 * @param   type $user_id
		 * @return  type
		 */
		public static function toggle_item_from_wish_list( $item_id, $user_id = null, $use_id_from_unlogged_user = false ) {
			if ( self::is_item_in_wish_list( $item_id, $user_id, $use_id_from_unlogged_user ) ) {
				$response = self::remove_item_from_wish_list( $item_id, $user_id, $use_id_from_unlogged_user );
			} else {
				$response = self::add_item_to_wish_list( $item_id, $user_id, $use_id_from_unlogged_user );
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