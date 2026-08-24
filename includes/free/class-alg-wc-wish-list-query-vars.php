<?php
/**
 * Wishlist for WooCommerce - Query vars
 *
 * @version 3.4.7
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Query_Vars' ) ) {

	class Alg_WC_Wish_List_Query_Vars {

		/**
		 * Query var for passing wishlist user id
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		const USER = 'alg_wc_wl_user';

		/**
		 * Query var for wishlist tab and current page id
		 *
		 * @version 3.1.6
		 * @since   3.1.6
		 */
		const USER_TAB = 'alg_wc_wl_user_tab';

		const CURRENT_PAGE_ID = 'alg_wc_wl_current_page_id';

		/**
		 * Query var informing 1 or 0 if the user is registered or not
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		const USER_UNLOGGED = 'alg_wc_wl_uunlogged';

		const SEND_BY_EMAIL = 'alg_wc_wl_send_by_email';

		/*
		* Encrypts and decrypts
		* @version 3.4.7
		* @since   1.0.0
		* @author Nazmul Ahsan <n.mukto@gmail.com>
		* @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
		*
		* @param string $string string to be encrypted/decrypted
		* @param string $action what to do with this? e for encrypt, d for decrypt
		*/
		public static function crypt_user( $string, $action = 'e' ) {
			$secret_key = defined( 'AUTH_SALT' ) && AUTH_SALT ? AUTH_SALT : ' ';
			$secret_iv  = defined( 'SECURE_AUTH_SALT' ) && SECURE_AUTH_SALT ? SECURE_AUTH_SALT : ' ';

			$output         = false;
			$encrypt_method = 'AES-256-CBC';
			$key            = hash( 'sha256', $secret_key );
			$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

			if ( 'e' === $action ) {
				// A random IV is prepended to the ciphertext so the same input never produces the same token.
				// The token is base64url-encoded (no +, / or padding) so it survives URL query strings intact.
				$random_iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( $encrypt_method ) );
				$encoded   = strtr( base64_encode( $random_iv . openssl_encrypt( $string, $encrypt_method, $key, OPENSSL_RAW_DATA, $random_iv ) ), '+/', '-_' );
				$output    = 'wl2.' . rtrim( $encoded, '=' );
			} elseif ( 'd' === $action ) {
				$string = (string) $string;
				if ( 0 === strpos( $string, 'wl2.' ) ) {
					$encoded = strtr( substr( $string, 4 ), '-_', '+/' );
					$padding = strlen( $encoded ) % 4;
					if ( 0 !== $padding ) {
						$encoded .= str_repeat( '=', 4 - $padding );
					}
					$data = base64_decode( $encoded, true );
					if ( false !== $data && strlen( $data ) > openssl_cipher_iv_length( $encrypt_method ) ) {
						$random_iv  = substr( $data, 0, openssl_cipher_iv_length( $encrypt_method ) );
						$ciphertext = substr( $data, openssl_cipher_iv_length( $encrypt_method ) );
						$output     = openssl_decrypt( $ciphertext, $encrypt_method, $key, OPENSSL_RAW_DATA, $random_iv );
					}
				} else {
					// Legacy static-IV format so share links created before 3.4.7 keep working.
					$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
				}
			}

			return $output;
		}

		/**
		 * parse_shared_user_id.
		 *
		 * Resolves a shared wishlist identifier from a raw query-string value.
		 *
		 * A raw value is only trusted when it decrypts to a numeric user ID
		 * (registered-user share token) or matches the guest wishlist ID format
		 * (plain hex string from an unlogged share link). Anything else is rejected,
		 * so raw input can never be resolved as a registered user.
		 *
		 * @version 3.4.7
		 * @since   3.4.7
		 *
		 * @param string $user_id_from_query_string Raw value from the request.
		 *
		 * @return array {
		 *     @type string $user_id  Decrypted numeric user ID ('' when the value is not a registered-user share token).
		 *     @type string $guest_id Validated guest wishlist ID ('' when the value is not a guest share ID).
		 * }
		 */
		public static function parse_shared_user_id( $user_id_from_query_string ) {
			$user_id_from_query_string = sanitize_text_field( $user_id_from_query_string );
			$user_id                   = ! empty( $user_id_from_query_string ) ? self::crypt_user( $user_id_from_query_string, 'd' ) : null;
			$user_id                   = ! empty( $user_id ) && ctype_digit( (string) $user_id ) ? $user_id : '';
			$guest_id                  = empty( $user_id ) && 1 === preg_match( '/^[a-f0-9]{10,13}$/i', $user_id_from_query_string ) ? $user_id_from_query_string : '';

			return array(
				'user_id'  => $user_id,
				'guest_id' => $guest_id,
			);
		}
	}

}