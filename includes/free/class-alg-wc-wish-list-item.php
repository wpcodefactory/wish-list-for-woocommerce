<?php
/**
 * Wishlist for WooCommerce - Wishlist Item.
 *
 * @version 1.9.2
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Item' ) ) {

	class Alg_WC_Wish_List_Item {

		/**
		 * handle_wishlist_counting.
		 *
		 * @version 1.7.0
		 * @since   1.7.0
		 *
		 * @param null $args
		 */
		private static function handle_wishlist_counting( $args = null ) {
			$args = wp_parse_args( $args, array(
				'item_id'     => null,
				'logged_user' => true,
				'action'      => 'increase' // increase || decrease,
			) );
			if ( empty( $args['item_id'] ) ) {
				return;
			}
			$meta  = $args['logged_user'] ? '_alg_wc_wl_added_by_registered_users_count' : '_alg_wc_wl_added_by_unregistered_users_count';
			$count = ! empty( $meta_value = get_post_meta( $args['item_id'], $meta, true ) ) ? (int) $meta_value : 0;
			if ( 'increase' === $args['action'] ) {
				$count ++;
			} else {
				$count --;
			}
			if ( $count < 0 ) {
				$count = 0;
			}
			update_post_meta( $args['item_id'], $meta, $count );
		}

		/**
		 * Add item to wishlist user.
		 *
		 * @version 1.9.2
		 * @since   1.0.0
		 *
		 * @param $item_id
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 *
		 * @return false|int
		 * @throws Exception
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
			// Product meta
			self::handle_wishlist_counting( array(
				'item_id'     => $item_id,
				'logged_user' => ! $use_id_from_unlogged_user,
				'action'      => 'increase',
			) );
			do_action( 'alg_wc_wl_item_added', $item_id, $use_id_from_unlogged_user, $user_id );

			return $response;
		}

		/**
		 * Add metas to wishlist item.
		 *
		 * @version 3.0.8
		 * @since   1.2.6
		 *
		 * @param $item_id
		 * @param $meta_key
		 * @param $meta_value
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 *
		 * @return bool|int
		 * @throws Exception
		 */
		public static function update_wish_list_item_metas( $item_id, $meta_key, $meta_value, $user_id = null, $use_id_from_unlogged_user = false ) {
			$response = false;
			
			// multiple wishlist
			$tab_id = 0;
			
			if ( isset( $_POST['wltab_id'] ) && $_POST['wltab_id'] > 0 ) {
				$tab_id = $_POST['wltab_id'];
			}
			
			// Get a meta from user meta (if is logged) or from transient if isn't logged
			if ( ! $use_id_from_unlogged_user ) {
				$old_user_meta = get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS, true );
			} else {
				$transient     = Alg_WC_Wish_List_Transients::WISH_LIST_METAS;
				if(!$user_id){
					$user_id = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
				}
				$old_user_meta = get_transient( "{$transient}{$user_id}" );
			}
			
			// multiple wishlist
			if ( $tab_id > 0 ) {
				
					if ( is_int( $user_id ) && $user_id > 0 ) {
						
						// get only multiple wishlist items
						$old_user_meta_multiple =  get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS_MULTIPLE, true );
						
					} else {
						$transient = Alg_WC_Wish_List_Transients::WISH_LIST_METAS_MULTIPLE_STORE;
						$old_user_meta_multiple = get_transient( "{$transient}{$user_id}" );
					}
					
					$old_user_meta = ( isset( $old_user_meta_multiple[$tab_id] ) ? $old_user_meta_multiple[$tab_id] : array() );

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
			
			
			
			
			// multiple wishlist
			
			if ( $tab_id > 0 ) {
				$new_user_meta_multiple[$tab_id] = $new_user_meta;
				
				if( is_int( $user_id ) && $user_id > 0 ) {
					
					// update only multiple wishlist items
					update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS_MULTIPLE, $new_user_meta_multiple );
				} else {
					
					set_transient( "{$transient}{$user_id}", $new_user_meta_multiple, 1 * MONTH_IN_SECONDS );
				}
				$response = $item_id;
			} else {			
				// Update meta
				if ( ! $use_id_from_unlogged_user ) {
					$response = update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS, $new_user_meta );
				} else {
					set_transient( "{$transient}{$user_id}", $new_user_meta, 1 * MONTH_IN_SECONDS );
					$response = $item_id;
				}
			}

			return $response;
		}

		/**
		 * Remove item from wishlist user.
		 *
		 * @version 1.9.2
		 * @since   1.0.0
		 *
		 * @param $item_id
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 *
		 * @return bool
		 * @throws Exception
		 */
		public static function remove_item_from_wish_list( $item_id, $user_id = null, $use_id_from_unlogged_user = false ) {
			if ( ! $use_id_from_unlogged_user ) {
				$response = delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false );
			} else {
				if ( ! $user_id ) {
					$user_id = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
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
			self::handle_wishlist_counting( array(
				'item_id'     => $item_id,
				'logged_user' => ! $use_id_from_unlogged_user,
				'action'      => 'decrease',
			) );
			do_action( 'alg_wc_wl_item_removed', $item_id, $use_id_from_unlogged_user, $user_id );
			return $response;
		}

		/**
		 * Check if an item is already in the user wishlist.
		 *
		 * @version 3.0.10
		 * @since   1.0.0
		 *
		 * @param $item_id
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 *
		 * @return bool
		 * @throws Exception
		 */
		public static function is_item_in_wish_list( $item_id, $user_id = null, $use_id_from_unlogged_user = false ) {
			
			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
			
			if ( 'yes' === get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ) ) {
			
				$multiple_wishlisted_items = Alg_WC_Wish_List::get_multiple_wishlist_unique_items( $user_id );
				
				if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 && is_array( $multiple_wishlisted_items ) && count( $multiple_wishlisted_items ) > 0 ) {
					// Merge the arrays
					$merged_items = array_merge($wishlisted_items, $multiple_wishlisted_items);

					// Remove duplicates
					$wishlisted_items = array_unique($merged_items);
				} else if ( is_array( $multiple_wishlisted_items ) && count( $multiple_wishlisted_items ) > 0 ) {
					$wishlisted_items = $multiple_wishlisted_items;
				}
				
			}
			
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
		 *
		 * @param $item_id
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 *
		 * @return bool|false|int
		 * @throws Exception
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