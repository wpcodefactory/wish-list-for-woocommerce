<?php

/**
 * Wish List for WooCommerce - Shortcodes
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!class_exists('Alg_WC_Wish_List_Shortcodes')) {

	class Alg_WC_Wish_List_Shortcodes {

		/**
		 * Shortcode for showing wishlist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function sc_alg_wc_wl() {
			
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