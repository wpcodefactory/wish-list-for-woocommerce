<?php

/**
 * Wish List for WooCommerce - Ajax
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Ajax' ) ) {

	class Alg_WC_Wish_List_Ajax {

		const ACTION_TOGGLE_WISH_LIST_ITEM = 'alg_wc_wl_toggle_item';

		/**
		 * Ajax method for toggling items to user wishlist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function toggle_wish_list_item() {
			if ( ! isset( $_POST['alg_wc_wl_item_id'] ) ) {
				die();
			}

			$item_id = intval( sanitize_text_field( $_POST['alg_wc_wl_item_id'] ) );
			$all_ok = true;
			$action = 'added'; // 'added' | 'removed' | error

			if ( ! is_user_logged_in() ) {
				$response = Alg_WC_Wish_List_Item::toggle_item_from_wish_list( $item_id, null );
			} else {
				$user = wp_get_current_user();
				$response = Alg_WC_Wish_List_Item::toggle_item_from_wish_list( $item_id, $user->ID );
			}

			if ( $response === false ) {
				$message = __( 'Sorry, Some error ocurred. Please, try again later.', ALG_WC_WL_DOMAIN );
				$all_ok = false;
				$action = 'error';
			} elseif ( $response === true ) {
				$message = __( 'Your item was removed from wishlist with success.', ALG_WC_WL_DOMAIN );
				$action = 'removed';
			} elseif ( is_numeric( $response ) ) {
				$message = __( 'Your item was added to wishlist with success.', ALG_WC_WL_DOMAIN );
				$action = 'added';
			}

			if ( $all_ok ) {
				wp_send_json_success( array( 'message' => $message, 'action' => $action ) );
			} else {
				wp_send_json_error( array( 'message' => $message, 'action' => $action ) );
			}
		}

		/**
		 * Load ajax actions on javascript
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param type $script
		 */
		public static function localize_script( $script ) {
			wp_localize_script( $script, 'alg_wc_wl_ajax', array( 'action_toggle_item' => self::ACTION_TOGGLE_WISH_LIST_ITEM ) );
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}

	}

}