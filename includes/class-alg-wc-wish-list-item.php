<?php

/**
 * Wish List for WooCommerce - Wish list Item
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!class_exists('Alg_WC_Wish_List_Item')) {

	class Alg_WC_Wish_List_Item {

		/**
		 * Add item to wishlist user
		 * 
		 * @param type $user_id
		 * @param type $item_id
		 * @return int|false Meta ID on success, false on failure.
		 */
		public static function add_item_to_wish_list($user_id, $item_id) {
			$response = add_user_meta($user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false);
			return $response;
		}

		/**
		 * Remove item from wishlist user
		 *
		 * @param type $user_id
		 * @param type $item_id
		 * @return bool True on success, false on failure.
		 */
		public static function remove_item_from_wish_list($user_id, $item_id) {
			$response = delete_user_meta($user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, $item_id, false);
			return $response;
		}

		/**
		 * Check if an item is already in the user wish list
		 * @param type $user_id
		 * @param type $item_id
		 * @return boolean
		 */
		public static function is_item_in_wish_list($user_id, $item_id) {
			$wishlisted_items	 = get_user_meta($user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, false);
			$response			 = false;
			if (is_array($wishlisted_items) && count($wishlisted_items) > 0) {
				$index = array_search($item_id, $wishlisted_items);
				if ($index === false) {
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
		 */
		public static function toggle_item_from_wish_list($user_id, $item_id) {
			$wishlisted_items = get_user_meta($user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, false);
			if (self::is_item_in_wish_list($user_id, $item_id)) {
				$response = self::remove_item_from_wish_list($user_id, $item_id);
			} else {
				$response = self::add_item_to_wish_list($user_id, $item_id);
			}
			return $response;
		}

		/**
		 * Returns class name
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}

	}

}