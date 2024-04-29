<?php
/**
 * Wish List for WooCommerce Pro - Sharing
 *
 * @version 1.5.7
 * @since   1.3.1
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Sharing' ) ) {

	class Alg_WC_Wish_List_Sharing {

		/**
		 * Changes share params based on admin settings
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @version 1.5.7
		 * @since   1.3.1
		 * @return mixed
		 */
		public static function handle_share_params( $params, $final_file, $path ) {
			$url = add_query_arg( array_filter( array(
				Alg_WC_Wish_List_Query_Vars::USER          => is_user_logged_in() ? get_current_user_id() : Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id(),
				Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED => is_user_logged_in() ? 0 : 1,
			) ), wp_get_shortlink( Alg_WC_Wish_List_Page::get_wish_list_page_id() ) );

			$params['share_txt']                        = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_SHARE ) );
			$params['email']['message']                 = sprintf( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_EMAIL_TEXTAREA ) ), $url );
			$params['email']['share_email_friends_txt'] = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_SHARE_FRIENDS, __( 'Friend(s)', 'wish-list-for-woocommerce' ) ) );
			$params['email']['share_email_admin_txt']   = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_SHARE_ADMIN, __( 'Admin', 'wish-list-for-woocommerce' ) ) );

			$url                      = add_query_arg( array_filter( array(
				Alg_WC_Wish_List_Query_Vars::USER          => is_user_logged_in() ? Alg_WC_Wish_List_Query_Vars::crypt_user( get_current_user_id() ) : Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id(),
				Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED => is_user_logged_in() ? 0 : 1,
			) ), wp_get_shortlink() );
			$params['twitter']['url'] = add_query_arg( array(
				'url'  => urlencode( $url ),
				'text' => get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_TWITTER_SHARE, __( 'Check my Wish List', 'wish-list-for-woocommerce' ) ),
			), 'https://twitter.com/intent/tweet' );

			return $params;
		}
	}
}