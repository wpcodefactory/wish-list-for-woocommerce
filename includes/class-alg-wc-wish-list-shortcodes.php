<?php
/**
 * Wish List for WooCommerce - Shortcodes
 *
 * @version 1.1.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Shortcodes' ) ) {

	class Alg_WC_Wish_List_Shortcodes {

		/**
		 * Shortcode for showing wishlist
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public static function sc_alg_wc_wl( $atts ) {
			$atts = wp_parse_args( $atts, array() );

			$user_id                   = get_query_var( Alg_WC_Wish_List_Query_Vars::USER, null );
			$can_remove_items          = $user_id && Alg_WC_Wish_List_Session::get_current_unlogged_user_id() != $user_id ? false : true;
			$show_stock                = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK, false ), FILTER_VALIDATE_BOOLEAN );
			$show_price                = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_PRICE, false ), FILTER_VALIDATE_BOOLEAN );
			$use_id_from_unlogged_user = filter_var( get_query_var( Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED, false ), FILTER_VALIDATE_BOOLEAN );
			$show_add_to_cart_btn      = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_TO_CART_BUTTON, false ), FILTER_VALIDATE_BOOLEAN );

			if ( is_user_logged_in() && $user_id == null ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			}

			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				$the_query = new WP_Query( array(
					'post_type'      => 'product',
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