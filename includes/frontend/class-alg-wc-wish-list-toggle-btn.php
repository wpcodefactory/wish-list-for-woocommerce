<?php

/**
 * Wish List for WooCommerce - Toggle Buton Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!class_exists('Alg_WC_Wish_List_Toggle_Btn')) {

	class Alg_WC_Wish_List_Toggle_Btn {

		/**
		 * Show the toggle button for adding or removing an Item from Wishlist 
		 */
		public static function show_toggle_btn() {
			$params = array(
				'btn_label'	 => __('Add to Wishlist', ALG_WC_WS_DOMAIN),
				'btn_class'	 => 'alg-wc-wishlist-toggle-btn'
			);
			echo alg_wc_ws_locate_template('toggle-wish-list-button.php', $params);
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