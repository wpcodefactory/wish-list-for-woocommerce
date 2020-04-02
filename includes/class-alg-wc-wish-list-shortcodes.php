<?php
/**
 * Wish List for WooCommerce - Shortcodes
 *
 * @version 1.6.4
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Shortcodes' ) ) {

	class Alg_WC_Wish_List_Shortcodes {

		const SHORTCODE_WISH_LIST='alg_wc_wl';
		const SHORTCODE_WISH_LIST_COUNT='alg_wc_wl_counter';

		/**
		 * Shortcode for showing wishlist
		 *
		 * @version 1.6.4
		 * @since   1.2.10
		 */
		public static function sc_alg_wc_wl_counter( $atts ) {
			$atts = shortcode_atts( array(
				'ignore_excluded_items' => false,
				'amount' => false,
			), $atts, self::SHORTCODE_WISH_LIST_COUNT );

			if ( empty( $atts['amount'] ) ) {
				$atts['ignore_excluded_items'] = filter_var( $atts['ignore_excluded_items'], FILTER_VALIDATE_BOOLEAN );

				$user_id_from_query_string = get_query_var( Alg_WC_Wish_List_Query_Vars::USER, null );
				$user_id                   = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
				$use_id_from_unlogged_user = filter_var( get_query_var( Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED, false ), FILTER_VALIDATE_BOOLEAN );
				if ( is_user_logged_in() && $user_id == null ) {
					$user    = wp_get_current_user();
					$user_id = $user->ID;
				}

				$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
				$amount           = 0;

				if ( $atts['ignore_excluded_items'] && is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
					$posts = get_posts( array(
						'post_type'      => 'product',
						'posts_per_page' => - 1,
						'post__in'       => $wishlisted_items,
						'orderby'        => 'post__in',
						'order'          => 'asc',
					) );
					if ( is_array( $posts ) ) {
						$amount = count( $posts );
					}
				} else {
					if ( is_array( $wishlisted_items ) ) {
						$amount = count( $wishlisted_items );
					}
				}
			} else {
				$amount = $atts['amount'];
			}
			return "<span class='alg-wc-wl-counter'>$amount</span>";
		}

		/**
		 * Shortcode for showing wishlist
		 *
		 * @version 1.6.0
		 * @since   1.0.0
		 */
		public static function sc_alg_wc_wl( $atts ) {
			$atts = shortcode_atts( array(
				'is_email' => false,
			), $atts, self::SHORTCODE_WISH_LIST );

			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
			$user_id                   = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
			$user_id                   = empty( $user_id ) ? $user_id_from_query_string : $user_id;
			$can_remove_items          = $user_id && Alg_WC_Wish_List_Cookies::get_unlogged_user_id() != $user_id ? false : true;
			$show_stock                = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK, false ), FILTER_VALIDATE_BOOLEAN );
			$show_price                = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_PRICE, false ), FILTER_VALIDATE_BOOLEAN );
			$use_id_from_unlogged_user = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED ] ) : false;
			$use_id_from_unlogged_user = empty( $use_id_from_unlogged_user ) ? false : filter_var( $use_id_from_unlogged_user, FILTER_VALIDATE_BOOLEAN );
			$show_add_to_cart_btn      = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_TO_CART_BUTTON, false ), FILTER_VALIDATE_BOOLEAN );
			$is_email                  = filter_var( $atts['is_email'], FILTER_VALIDATE_BOOLEAN );

			if ( is_user_logged_in() && $user_id == null ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			}

			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				$the_query = new WP_Query( array(
					//'post_type'      => 'product',
					'post_type'      => array( 'product', 'product_variation' ),
					'posts_per_page' => - 1,
					'post__in'       => $wishlisted_items,
					'orderby'        => 'post__in',
					'order'          => 'asc'
				) );
			} else {
				$the_query = null;
			}

			$params = array(
				'the_query'            => $the_query,
				'can_remove_items'     => $can_remove_items,
				'show_stock'           => $show_stock,
				'show_add_to_cart_btn' => $show_add_to_cart_btn,
				'show_price'           => $show_price,
				'is_email'             => $is_email
			);

			return alg_wc_wl_locate_template( 'wish-list.php', $params );
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  type
		 */
		public static function get_class_name() {
			return get_called_class();
		}

	}

}