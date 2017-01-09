<?php

/**
 * Wish List for WooCommerce - Ajax
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!class_exists('Alg_WC_Wish_List_Ajax')) {

	class Alg_WC_Wish_List_Ajax {

		const ACTION_TOGGLE_WISH_LIST_ITEM = 'alg_wc_wl_toggle_item';

		/**
		 * Ajax method for toggling items to user wishlist
		 */
		public static function toggle_wish_list_item() {
			if (!isset($_POST['alg_wc_wl_item_id'])) {
				die();
			}

			$item_id = intval(sanitize_text_field($_POST['alg_wc_wl_item_id']));
			$all_ok	 = true;

			if (!is_user_logged_in()) {
				die();
			} else {
				$user		 = wp_get_current_user();
				$response	 = Alg_WC_Wish_List_Item::toggle_item_from_wish_list($user->ID, $item_id);
				if ($response === false) {
					$message = __('Sorry, Some error ocurred. Please, try again later.');
					$all_ok	 = false;
				} else if ($response === true) {
					$message = __('Your item was removed from wishlist with success.');
				} else if (is_numeric($response)) {
					$message = __('Your item was added to wishlist with success.');
				}
			}

			if ($all_ok) {
				wp_send_json_success(array('message' => $message));
			} else {
				wp_send_json_error(array('message' => $message));
			}
		}

		/**
		 * Load ajax actions on javascript
		 * @param type $script
		 */
		public static function localize_script($script) {
			wp_localize_script($script, 'alg_wc_wl_ajax', array('action_toggle_item' => self::ACTION_TOGGLE_WISH_LIST_ITEM));
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