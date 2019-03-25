<?php
/**
 * Wish List for WooCommerce - Query vars
 *
 * @version 1.4.0
 * @since   1.0.0
 * @author  Thanks to IT
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
		 * Query var informing 1 or 0 if the user is registered or not
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		const USER_UNLOGGED = 'alg_wc_wl_uunlogged';

		const SEND_BY_EMAIL = 'alg_wc_wl_send_by_email';

		/*
		* Encrypts and decrypts
		* @version 1.4.0
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
			$encrypt_method = "AES-256-CBC";
			$key            = hash( 'sha256', $secret_key );
			$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

			if ( $action == 'e' ) {
				$output = base64_encode(openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ));
			} else if ( $action == 'd' ) {
				$output = openssl_decrypt( base64_decode($string), $encrypt_method, $key, 0, $iv );
			}

			return $output;
		}
	}

}