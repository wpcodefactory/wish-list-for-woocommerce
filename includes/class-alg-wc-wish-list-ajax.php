<?php

/**
 * Wish List for WooCommerce - Ajax
 *
 * @version 1.1.0
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
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public static function toggle_wish_list_item() {
			if ( ! isset( $_POST['alg_wc_wl_item_id'] ) ) {
				die();
			}

			$item_id = intval( sanitize_text_field( $_POST['alg_wc_wl_item_id'] ) );
			$product = wc_get_product( $item_id );
			$all_ok = true;
			$action = 'added'; // 'added' | 'removed' | error

			$params = apply_filters( 'alg_wc_wl_toggle_item_texts', array(
				'added'   => __( '%s was successfully added to wish list', 'alg-wish-list-for-woocommerce' ),
				'removed' => __( '%s was successfully removed from wish list', 'alg-wish-list-for-woocommerce' ),
				'error'   => __( 'Sorry, Some error ocurred. Please, try again later.', 'alg-wish-list-for-woocommerce' )
			) );

			if ( ! is_user_logged_in() ) {
				$response = Alg_WC_Wish_List_Item::toggle_item_from_wish_list( $item_id, null );
			} else {
				$user = wp_get_current_user();
				$response = Alg_WC_Wish_List_Item::toggle_item_from_wish_list( $item_id, $user->ID );
			}

			if ( $response === false ) {
				$message = __( $params['error'], 'alg-wish-list-for-woocommerce' );
				$all_ok = false;
				$action = 'error';
			} elseif ( $response === true ) {
				$message = sprintf (
					__( $params['removed'], 'alg-wish-list-for-woocommerce' ),
					'<b>'.$product->get_title().'</b>'
				);
				$action = 'removed';
			} elseif ( is_numeric( $response ) ) {
				$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
				$wish_list_permalink = get_permalink($wish_list_page_id);
				$see_your_wishlist_message = __('See your wish list', 'alg-wish-list-for-woocommerce' );
				$added_message = sprintf (
					__( $params['added'], 'alg-wish-list-for-woocommerce' ),
					'<b>'.$product->get_title().'</b>'
				);
				$message = __( "{$added_message}<br /> <a class='alg-wc-wl-notification-link' href='{$wish_list_permalink}'>{$see_your_wishlist_message}</a>", 'alg-wish-list-for-woocommerce' );
				$action = 'added';
			}

			$response = array( 'message' => $message, 'action' => $action );

			if ( $all_ok ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error( $response );
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