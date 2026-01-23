<?php
/**
 * Wishlist for WooCommerce - Unlogged User.
 *
 * @version 3.3.2
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
		 * @version 3.3.2
		 * @since   1.9.0
		 *
		 * @param $unlogged_user_id
		 */
		public static function set_unlogged_user_id( $unlogged_user_id ) {
			if ( 'cookie' === self::get_guest_user_data_type() ) {
				if ( ! headers_sent() ) {
					$cookie_expire_time = time() + YEAR_IN_SECONDS;
					setcookie( self::VAR_UNLOGGED_USER_ID, $unlogged_user_id, $cookie_expire_time, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
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
		 * @throws Exception
		 * @return string
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
		 * @throws Exception
		 * @return int|string
		 */
		public static function generate_user_id() {
			$user_id = version_compare( PHP_VERSION, '7.0.0' ) >= 0 ? bin2hex( random_bytes( 5 ) ) : uniqid();

			return $user_id;
		}

		/**
		 * save guest data.
		 *
		 * @version 3.3.2
		 * @since   3.3.2
		 *
		 */
		public static function save_guest_wishlist( $key, $user_id, $wishlist_list_data ) {
			$guest_timeout = self::get_custom_date_range_in_seconds();
			set_transient( $key, $wishlist_list_data, $guest_timeout );

			$transients = array(
				Alg_WC_Wish_List_Transients::WISH_LIST_METAS,
				Alg_WC_Wish_List_Transients::WISH_LIST_METAS_MULTIPLE_STORE,
				Alg_WC_Wish_List_Transients::WISH_LIST,
				Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE,
				Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE_STORE,
				'alg_wc_wl_sort_order_',
			);

			// Synchronizes the expiration time for all wishlist transients from the same guest user.
			foreach ( $transients as $transient_key ) {
				$key_id = $transient_key . $user_id;
				if ( $key_id !== $key ) {
					$key_data = get_transient( $key_id );
					if ( false !== $key_data ) {
						set_transient( $key_id, $key_data, $guest_timeout );
					}
				}
			}
		}

		/**
		 * get guest expire time.
		 *
		 * @version 3.3.2
		 * @since   3.3.2
		 */
		public static function get_custom_date_range_in_seconds() {
			$value = max( 1, absint( get_option( 'alg_wc_wl_guest_user_expire_time_number', 1 ) ) );
			$unit  = strtolower( get_option( 'alg_wc_wl_guest_user_expire_time_unit', 'months' ) );
			switch ( $unit ) {
				case 'days':
					return $value * DAY_IN_SECONDS;
				case 'weeks':
					return $value * WEEK_IN_SECONDS;
				case 'months':
					return $value * MONTH_IN_SECONDS;
				case 'years':
					return $value * YEAR_IN_SECONDS;
				default: // 'months'
					return $value * MONTH_IN_SECONDS;
			}
		}

	}

}