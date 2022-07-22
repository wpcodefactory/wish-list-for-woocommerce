<?php
/**
 * Wish List for WooCommerce - Unlogged User.
 *
 * @version 1.8.7
 * @since   1.1.5
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Unlogged_User' ) ) {

	class Alg_WC_Wish_List_Unlogged_User {

		/**
		 * Cookie var responsible for saving the unregistered user wishlist.
		 *
		 * @since   1.1.5
		 */
		const VAR_UNLOGGED_USER_ID = 'alg-wc-wl-user-id';

		public static $unlogged_user_id = '';

		/**
		 * Gets the user id from unlogged user.
		 *
		 * @version 1.8.7
		 * @since   1.1.5
		 *
		 * @param bool $create_if_empty
		 *
		 * @return string
		 * @throws Exception
		 */
		public static function get_unlogged_user_id( $create_if_empty = true ) {
			self::$unlogged_user_id = ! empty( self::$unlogged_user_id ) ? self::$unlogged_user_id : ( isset( $_COOKIE[ self::VAR_UNLOGGED_USER_ID ] ) ? $_COOKIE[ self::VAR_UNLOGGED_USER_ID ] : '' );
			if ( empty( self::$unlogged_user_id ) ) {
				if( ! headers_sent() ){
					self::$unlogged_user_id = $user_id = version_compare( PHP_VERSION, '7.0.0' ) >= 0 ? bin2hex( random_bytes( 5 ) ) : uniqid();
					setcookie( self::VAR_UNLOGGED_USER_ID, $user_id, time() + ( 90 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
				}
			}
			return self::$unlogged_user_id;
		}

	}

}