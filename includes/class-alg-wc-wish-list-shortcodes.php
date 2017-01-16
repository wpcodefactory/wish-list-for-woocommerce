<?php

/**
 * Wish List for WooCommerce - Shortcodes
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Shortcodes' ) ) {

	class Alg_WC_Wish_List_Shortcodes {

		/**
		 * Shortcode for showing wishlist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function sc_alg_wc_wl( $atts ) {
			$atts = wp_parse_args( $atts, array(
			) );

			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();
				$user_id = $user->ID;
			} else {
				$user_id = null;
			}

			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id );
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				$the_query = new WP_Query( array(
					'post_type'      => 'product',
					'posts_per_page' => -1,
					'post__in'       => $wishlisted_items,
					'orderby'        => 'title',
					'order'          => 'asc'
				) );
			} else {
				$the_query = null;
			}

			$params = array( 'the_query' => $the_query );
			return alg_wc_ws_locate_template( 'wish-list.php', $params );
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