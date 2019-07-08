<?php
/**
 * Wish List for WooCommerce - Ajax
 *
 * @version 1.5.8
 * @since   1.0.0
 * @author  Thanks to IT
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Ajax' ) ) {

	class Alg_WC_Wish_List_Ajax {

		const ACTION_TOGGLE_WISH_LIST_ITEM = 'alg_wc_wl_toggle_item';
		const ACTION_GET_WISH_LIST         = 'alg_wc_wl_get_wish_list';

		/**
		 * Ajax method for toggling items to user wishlist
		 *
		 * @version 1.5.2
		 * @since   1.0.0
		 */
		public static function toggle_wish_list_item() {
			if ( ! isset( $_POST['alg_wc_wl_item_id'] ) ) {
				die();
			}

			$response = Alg_WC_Wish_List::toggle_wish_list_item( array(
				'item_id'          => intval( sanitize_text_field( $_POST['alg_wc_wl_item_id'] ) ),
				'unlogged_user_id' => sanitize_text_field( $_POST['unlogged_user_id'] )
			) );
			$response = apply_filters( 'alg_wc_wl_toggle_item_ajax_response', $response );
			if ( $response['ok'] ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error( $response );
			}
		}

		/**
		 * Ajax method for get wish list
		 *
		 * @version 1.3.0
		 * @since   1.3.0
		 */
		public static function get_wish_list() {
			$args = wp_parse_args( $_POST, array(
				'ignore_excluded_items' => false,
			) );

			$use_id_from_unlogged_user = false;
			if ( is_user_logged_in() ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			} else {
				$use_id_from_unlogged_user = true;
				$user_id                   = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
			}

			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
			$only_valid_items = array();

			if ( $args['ignore_excluded_items'] && is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				$posts = get_posts( array(
					'post_type'      => 'product',
					'posts_per_page' => - 1,
					'post__in'       => $wishlisted_items,
					'orderby'        => 'post__in',
					'order'          => 'asc',
				) );
				if ( is_array( $posts ) ) {
					foreach ($posts as $post){
						$only_valid_items[] = $post->ID;
					}
					$response = array( 'wishlist' => $only_valid_items );
				}else{
					$response = array( 'wishlist' => array() );
				}
			}else{
				if ( !is_array( $wishlisted_items ) ) {
					$response = array( 'wishlist' => array() );
				}else{
					$response = array( 'wishlist' => $wishlisted_items );
				}
			}

			//$response = array( 'wishlist' => $wishlisted_items );
			wp_send_json_success( $response );
		}

		/**
		 * Load ajax actions on javascript
		 *
		 * @version 1.3.0
		 * @since   1.0.0
		 * @param type $script
		 */
		public static function localize_script( $script ) {
			wp_localize_script( $script, 'alg_wc_wl_ajax', array( 'action_toggle_item' => self::ACTION_TOGGLE_WISH_LIST_ITEM ) );
			wp_localize_script( $script, 'alg_wc_wl_get_wl_ajax_action', self::ACTION_GET_WISH_LIST );
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