<?php
/**
 * Wish List for WooCommerce - Shortcodes
 *
 * @version 1.7.5
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Shortcodes' ) ) {

	class Alg_WC_Wish_List_Shortcodes {

		const SHORTCODE_WISH_LIST = 'alg_wc_wl';
		const SHORTCODE_WISH_LIST_COUNT = 'alg_wc_wl_counter';
		const SHORTCODE_WISH_LIST_REMOVE_ALL_BTN = 'alg_wc_wl_remove_all_btn';

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
		 * @version 1.7.2
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

			$btn_params                          = Alg_WC_Wish_List_Toggle_Btn::get_toggle_btn_params();
			$btn_params['btn_class']             .= ' remove alg-wc-wl-remove-item-from-wl';
			$btn_params['remove_btn_icon_class'] = apply_filters( 'alg_wc_wl_fa_icon_class', '', 'remove_btn' );
			$params = array(
				'the_query'             => $the_query,
				'can_remove_items'      => $can_remove_items,
				'show_stock'            => $show_stock,
				'remove_btn_params'     => $btn_params,
				'show_add_to_cart_btn'  => $show_add_to_cart_btn,
				'show_price'            => $show_price,
				'is_email'              => $is_email
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

		/**
		 * sc_alg_wc_wl_remove_btn.
		 *
		 * @version 1.7.5
		 * @since   1.7.3
		 */
		public static function sc_alg_wc_wl_remove_all_btn( $atts = null ) {
			$atts = shortcode_atts( array(
				'show_loading' => false,
				'tag'          => 'button',
				'btn_class'    => 'alg-wc-wl-btn2 alg-wc-wl-remove-all',
				'remove_label' => apply_filters( 'alg_wc_wl_remove_all_btn_label', __( 'Remove all', 'wish-list-for-woocommerce' ) ),
				'auto_hide'    => false
			), $atts, self::SHORTCODE_WISH_LIST_REMOVE_ALL_BTN );
			$auto_hide_param = $atts['auto_hide'] ? 'data-auto_hide="true"' : '';
			if ( $auto_hide_param ) {
				$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
				$user_id                   = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
				$user_id                   = empty( $user_id ) ? $user_id_from_query_string : $user_id;
				$use_id_from_unlogged_user = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED ] ) : false;
				$use_id_from_unlogged_user = empty( $use_id_from_unlogged_user ) ? false : filter_var( $use_id_from_unlogged_user, FILTER_VALIDATE_BOOLEAN );
				if ( is_user_logged_in() && $user_id == null ) {
					$user    = wp_get_current_user();
					$user_id = $user->ID;
				}
				$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user );
				if ( empty( $wishlisted_items ) ) {
					return apply_filters( 'alg_wc_wl_remove_all_btn_html', '' );
				}
			}
			ob_start();
			?>
            <<?php echo esc_attr( $atts['tag'] ) ?> <?php echo $auto_hide_param; ?> class="<?php echo esc_attr( $atts['btn_class'] ); ?>">
            <span class="alg-wc-wl-btn-text"><?php echo esc_html( $atts['remove_label'] ); ?></span>
			<?php if ( $atts['show_loading'] ): ?>
                <i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
			<?php endif; ?>
            </<?php echo esc_attr( $atts['tag'] ) ?>>
			<?php

			return apply_filters( 'alg_wc_wl_remove_all_btn_html', ob_get_clean() );
		}

	}

}