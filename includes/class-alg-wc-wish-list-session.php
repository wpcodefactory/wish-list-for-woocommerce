<?php
/**
 * Wish List for WooCommerce - Session
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Session' ) ) {

	class Alg_WC_Wish_List_Session {

		/**
		 * Session var responsible for saving the unregistered user wishlist
		 *
		 * @since   1.0.0
		 */
		const WISH_LIST = 'alg-wc-wl';

		/**
		 * Get current unlogged user id from session
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return string
		 */
		public static function get_current_unlogged_user_id($encript=true){
			if($encript){
				return md5(session_id().AUTH_KEY);
			}else{
				return session_id();
			}

		}

	}

}