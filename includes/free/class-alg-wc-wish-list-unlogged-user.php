<?php
/**
 * Wishlist for WooCommerce - Unlogged User.
 *
 * @version 1.9.1
 * @since   1.1.5
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Unlogged_User' ) ) {

	class Alg_WC_Wish_List_Unlogged_User {

		/**
		 * Cookie var responsible for saving the unregistered user wishlist.
		 *
		 * @since 1.1.5
		 */
		const VAR_UNLOGGED_USER_ID = 'alg-wc-wl-user-id';

		/**
		 * $unlogged_user_id.
		 *
		 * @since 1.1.5
		 *
		 * @var string
		 */
		public static $unlogged_user_id = '';

		/**
		 * guest_user_data_type.
		 *
		 * @since 1.8.9
		 *
		 * @var null
		 */
		protected static $guest_user_data_type = null;

		/**
		 * get_guest_user_data_type.
		 *
		 * @version 1.8.9
		 * @since   1.8.9
		 *
		 * @return string
		 */
		public static function get_guest_user_data_type() {
			if ( is_null( self::$guest_user_data_type ) ) {
				self::$guest_user_data_type = get_option( 'alg_wc_wl_guest_user_data_type', 'cookie' );
			}

			return self::$guest_user_data_type;
		}

		/**
		 * set_unlogged_user_id.
		 *
		 * @version 1.9.0
		 * @since   1.9.0
		 *
		 * @param $unlogged_user_id
		 */
		public static function set_unlogged_user_id( $unlogged_user_id ) {
			if ( 'cookie' === self::get_guest_user_data_type() ) {
				if ( ! headers_sent() ) {
					setcookie( self::VAR_UNLOGGED_USER_ID, $unlogged_user_id, time() + ( 90 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
				}
			} elseif ( 'wc_session' === self::get_guest_user_data_type() ) {
				if ( ! is_user_logged_in() && isset( WC()->session ) ) {
					if ( ! WC()->session->has_session() ) {
						WC()->session->set_customer_session_cookie( true );
					}
					WC()->session->set( self::VAR_UNLOGGED_USER_ID, $unlogged_user_id );
				}
			}
			self::$unlogged_user_id = $unlogged_user_id;
		}

		/**
		 * Gets the user id from unlogged user.
		 *
		 * @version 1.9.1
		 * @since   1.1.5
		 *
		 * @return string
		 * @throws Exception
		 */
		public static function get_unlogged_user_id( $force_id_creation = false ) {
			if ( 'cookie' === self::get_guest_user_data_type() ) {
				self::$unlogged_user_id = ! empty( self::$unlogged_user_id ) ? self::$unlogged_user_id : ( isset( $_COOKIE[ self::VAR_UNLOGGED_USER_ID ] ) ? $_COOKIE[ self::VAR_UNLOGGED_USER_ID ] : '' );
				if ( empty( self::$unlogged_user_id ) && $force_id_creation ) {
					self::set_unlogged_user_id( self::generate_user_id() );
				}
			} elseif ( 'wc_session' === self::get_guest_user_data_type() ) {
				if ( ! is_user_logged_in() && isset( WC()->session ) ) {
					if ( empty( self::$unlogged_user_id ) && ! WC()->session->has_session() && $force_id_creation ) {
						WC()->session->set_customer_session_cookie( true );
					}
					self::$unlogged_user_id = ! empty( self::$unlogged_user_id ) ? self::$unlogged_user_id : ( ! empty( $id = WC()->session->get( self::VAR_UNLOGGED_USER_ID ) ) ? $id : '' );
					if ( empty( self::$unlogged_user_id ) && $force_id_creation ) {
						self::set_unlogged_user_id( WC()->session->get_customer_id() );
					}
				}
			}
			return self::$unlogged_user_id;
		}

		/**
		 * generate_user_id.
		 *
		 * @version 1.8.9
		 * @since   1.8.9
		 *
		 * @return int|string
		 * @throws Exception
		 */
		public static function generate_user_id() {
			$user_id = version_compare( PHP_VERSION, '7.0.0' ) >= 0 ? bin2hex( random_bytes( 5 ) ) : uniqid();

			return $user_id;
		}

	}

}