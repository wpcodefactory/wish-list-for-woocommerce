<?php
/**
 * Wish List for WooCommerce - Toggle Buton Class
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Toggle_Btn' ) ) {

	class Alg_WC_Wish_List_Toggle_Btn {

		private static $toggle_btn_params = array(
//			'btn_class'       => 'alg-wc-wl-btn alg-wc-wl-toggle-btn',
			'btn_class'       => 'alg-wc-wl-btn',
			'btn_data_action' => 'alg-wc-wl-toggle',
			'btn_icon_class'  => 'fa fa-heart'
		);

		/**
		 * Show the default toggle button for adding or removing an Item from Wishlist
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public static function show_default_btn() {
			$toggle_btn_params = self::$toggle_btn_params;
			$item_id = get_the_ID();

			$is_item_in_wish_list = false;
			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user->ID );
			} else {
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, null );
			}

			if ( $is_item_in_wish_list ) {
				$toggle_btn_params['btn_class'].=' remove alg-wc-wl-toggle-btn';
			} else {
				$toggle_btn_params['btn_class'].=' add alg-wc-wl-toggle-btn';
			}

			$toggle_btn_params['add_label']    = __('Add to Wish list','wish-list-for-woocommerce');
			$toggle_btn_params['remove_label'] = __('Remove from Wish list','wish-list-for-woocommerce');
			echo alg_wc_wl_locate_template( 'default-button.php', $toggle_btn_params );
		}

		/**
		 * Show the thumb button for adding or removing an Item from Wishlist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function show_thumb_btn() {
			$toggle_btn_params = self::$toggle_btn_params;
			$item_id = get_the_ID();

			$is_item_in_wish_list = false;
			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user->ID );
			} else {
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, null );
			}

			if ( $is_item_in_wish_list ) {
				$toggle_btn_params['btn_class'].=' remove alg-wc-wl-thumb-btn';
			} else {
				$toggle_btn_params['btn_class'].=' add alg-wc-wl-thumb-btn';
			}
			echo alg_wc_wl_locate_template( 'thumb-button.php', $toggle_btn_params );
		}

		/**
		 * Load buttons vars on javascript
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param type $script
		 */
		public static function localize_script( $script ) {
			$toggle_btn_params = self::$toggle_btn_params;
			$btn_class_arr = $toggle_btn_params['btn_class'];
			$toggle_btn_params['btn_class'] = '.' . implode( '.', explode( ' ', $btn_class_arr ) );
			wp_localize_script( $script, 'alg_wc_wl_toggle_btn', $toggle_btn_params );
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

		/**
		 * get_toggle_btn_params.
		 */
		static function get_toggle_btn_params() {
			return self::$toggle_btn_params;
		}

	}

}