<?php
/**
 * Wish List for WooCommerce - Shortcodes.
 *
 * @version 2.0.0
 * @since   1.0.0
 * @author  WPFactory
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
		 * Counts the amount of wish listed items.
		 *
		 * @version 2.0.0
		 * @since   1.2.10
		 */
		public static function sc_alg_wc_wl_counter( $atts ) {
			if ( 'no' === get_option( 'alg_wc_wl_sc_counter', 'yes' ) ) {
				return '[' . self::SHORTCODE_WISH_LIST_COUNT . ']';
			}
			$atts = shortcode_atts( array(
				'ignore_excluded_items' => 'false',
				'template'              => '<span class="alg-wc-wl-counter">{content}</span>',
				'amount'                => '',
			), $atts, self::SHORTCODE_WISH_LIST_COUNT );
			$ignore_excluded_items = filter_var( $atts['ignore_excluded_items'], FILTER_VALIDATE_BOOLEAN );

			if ( empty( $atts['amount'] ) ) {
				$user_id_from_query_string = get_query_var( Alg_WC_Wish_List_Query_Vars::USER, null );
				$user_id                   = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
				$use_id_from_unlogged_user = filter_var( get_query_var( Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED, false ), FILTER_VALIDATE_BOOLEAN );
				if ( is_user_logged_in() && $user_id == null ) {
					$user    = wp_get_current_user();
					$user_id = $user->ID;
				}

				$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user, $ignore_excluded_items );
				$amount = is_array( $wishlisted_items ) ? count( $wishlisted_items ) : 0;
			} else {
				$amount = $atts['amount'];
			}

			$array_from_to = array (
				'{content}' => $amount,
			);
			$output = str_replace(array_keys($array_from_to), $array_from_to, $atts['template']);
			return $output;
		}

		/**
		 * Shortcode for showing wishlist.
		 *
		 * @version 1.8.9
		 * @since   1.0.0
		 */
		public static function sc_alg_wc_wl( $atts ) {
			if ( 'no' === get_option( 'alg_wc_wl_sc_alg_wc_wl', 'yes' ) ) {
				return '[' . self::SHORTCODE_WISH_LIST . ']';
			}
			$atts = shortcode_atts( array(
				'is_email' => 'false',
                'ignore_excluded_items' => 'true'
			), $atts, self::SHORTCODE_WISH_LIST );

			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
			$user_id                   = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
			$user_id                   = empty( $user_id ) ? $user_id_from_query_string : $user_id;
			$can_remove_items          = $user_id && Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id() != $user_id ? false : true;
			$show_stock                = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK, false ), FILTER_VALIDATE_BOOLEAN );
			$show_price                = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_PRICE, false ), FILTER_VALIDATE_BOOLEAN );
			$use_id_from_unlogged_user = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED ] ) : false;
			$use_id_from_unlogged_user = empty( $use_id_from_unlogged_user ) ? false : filter_var( $use_id_from_unlogged_user, FILTER_VALIDATE_BOOLEAN );
			$show_add_to_cart_btn      = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_TO_CART_BUTTON, false ), FILTER_VALIDATE_BOOLEAN );
			$is_email                  = filter_var( $atts['is_email'], FILTER_VALIDATE_BOOLEAN );
			$ignore_excluded_items     = filter_var( $atts['ignore_excluded_items'], FILTER_VALIDATE_BOOLEAN );

			if ( is_user_logged_in() && $user_id == null ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			}

			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user, $ignore_excluded_items );
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				$the_query = new WP_Query( array(
					'post_type'      => array( 'product', 'product_variation' ),
					'post_status'    => array( 'publish','trash' ),
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
		 * Format shortcode parameters.
		 *
		 * Use an array for each param having the key as the shortcode param.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @see self::format_shortcode_param() for a list of argument for each param.
		 *
		 * @param $params
		 *
		 * @return void
		 */
		public static function format_shortcode_params( $params = array() ) {
			$output               = ! empty( $params ) ? __( 'Params:', 'wish-list-for-woocommerce' ) . '<br />' . '<ul style="list-style: inside">%s</ul>' : '';
			$formatted_params_arr = array();
			foreach ( $params as $param_key => $param_data ) {
				$sc_param_data                    = array( 'param' => $param_key );
				$sc_param_data['desc']            = $param_data['desc'] ?? '';
				$sc_param_data['default']         = $param_data['default'] ?? '';
				$sc_param_data['possible_values'] = $param_data['possible_values'] ?? '';
				$formatted_params_arr         []  = self::format_shortcode_param( $sc_param_data );
			}
			$output = sprintf( $output, Alg_WC_Wish_List_Array::to_string(
				$formatted_params_arr, array(
					'item_template' => '<li>{value}</li>',
				)
			) );

			return $output;
		}

		/**
		 * format_shortcode_param.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @param $args
		 *
		 * @return string
		 */
		public static function format_shortcode_param( $args = null ) {
			$args   = wp_parse_args( $args, array(
				'param'           => '',
				'desc'            => '',
				'default'         => '',
				'possible_values' => array(),
			) );
			$output = '';
			$output .= '<code>' . $args['param'] . '</code>';
			if ( ! empty( $args['desc'] ) ) {
				$output .= ' - ' . $args['desc'] . '.';
			}
			if ( ! empty( $args['default'] ) ) {
				$output .= ' ' . __( 'Default value:', 'wish-list-for-woocommerce' ) . ' ' . '<code>' . esc_html($args['default']) . '</code>' . '.';
			}
			if ( ! empty( $args['possible_values'] ) ) {
				$output .= ' ' . __( 'Possible values:', 'wish-list-for-woocommerce' ) . ' ' .
				           Alg_WC_Wish_List_Array::to_string(
					           $args['possible_values'], array(
						           'item_template' => '<code>{value}</code>',
						           'glue'          => ', '
					           )
				           )
				           . '.';
			}

			return $output;
		}

		/**
		 * sc_alg_wc_wl_remove_btn.
		 *
		 * @version 2.0.0
		 * @since   1.7.3
		 */
		public static function sc_alg_wc_wl_remove_all_btn( $atts = null ) {
			if ( 'no' === get_option( 'alg_wc_wl_sc_remove_all_btn', 'yes' ) ) {
				return '[' . self::SHORTCODE_WISH_LIST_REMOVE_ALL_BTN . ']';
			}
			$atts            = shortcode_atts( array(
				'show_loading' => 'false',
				'tag'          => 'button',
				'btn_class'    => 'alg-wc-wl-btn2 alg-wc-wl-remove-all',
				'remove_label' => apply_filters( 'alg_wc_wl_remove_all_btn_label', __( 'Remove all', 'wish-list-for-woocommerce' ) ),
				'auto_hide'    => 'false'
			), $atts, self::SHORTCODE_WISH_LIST_REMOVE_ALL_BTN );
			$auto_hide_param = filter_var( $atts['auto_hide'], FILTER_VALIDATE_BOOLEAN ) ? 'data-auto_hide="true"' : '';
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
            <<?php echo esc_attr( $atts['tag'] ) ?><?php echo ' ' . $auto_hide_param; ?> class="<?php echo esc_attr( $atts['btn_class'] ); ?>">
            <span class="alg-wc-wl-btn-text"><?php echo esc_html( $atts['remove_label'] ); ?></span>
			<?php if ( filter_var( $atts['show_loading'], FILTER_VALIDATE_BOOLEAN ) ): ?>
                <i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
			<?php endif; ?>
            </<?php echo esc_attr( $atts['tag'] ) ?>>
			<?php

			return apply_filters( 'alg_wc_wl_remove_all_btn_html', ob_get_clean() );
		}

	}

}