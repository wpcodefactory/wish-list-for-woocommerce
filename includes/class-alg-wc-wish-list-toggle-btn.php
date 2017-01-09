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

		private static $toggle_btn_params;

		/**
		 * Initialize class
		 */
		public static function init() {
			self::init_toggle_btn_params();
		}

		/**
		 * Initialize toggle button params
		 */
		protected static function init_toggle_btn_params() {
			$params					 = array(
				'btn_class'			 => 'alg-wc-wl-btn alg-wc-wl-toggle-btn',
				'btn_data_action'	 => 'alg-wc-wl-toggle',
			);
			self::$toggle_btn_params = $params;
		}

		/**
		 * Show the toggle button for adding or removing an Item from Wishlist 
		 */
		public static function show_toggle_btn() {
			$toggle_btn_params = self::$toggle_btn_params;
			$item_id = get_the_ID();
			
			$user = wp_get_current_user();
			if(Alg_WC_Wish_List_Item::is_item_in_wish_list($user->ID, $item_id)){
				$toggle_btn_params['btn_class'].=' remove';
			}else{
				$toggle_btn_params['btn_class'].=' add';
			}
			echo alg_wc_ws_locate_template('toggle-wish-list-button.php', $toggle_btn_params);
		}

		/**
		 * Load buttons vars on javascript
		 * @param type $script
		 */
		public static function localize_script($script) {
			$toggle_btn_params = self::$toggle_btn_params;
			$btn_class_arr = $toggle_btn_params['btn_class'];
			$toggle_btn_params['btn_class']= '.'.implode('.',explode(' ',$btn_class_arr));
			wp_localize_script($script, 'alg_wc_wl_toggle_btn', $toggle_btn_params);
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