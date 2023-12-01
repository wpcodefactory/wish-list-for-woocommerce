<?php
/**
 * Wishlist for WooCommerce - Toggle Buton Class
 *
 * @version 1.8.0
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Toggle_Btn' ) ) {

	class Alg_WC_Wish_List_Toggle_Btn {

		private static $toggle_btn_params = array(
			'btn_class'            => 'alg-wc-wl-btn',
			'btn_data_action'      => 'alg-wc-wl-toggle',
			'btn_icon_class'       => 'fas fa-heart',
			'btn_icon_class_added' => 'fas fa-heart'
		);

		/**
		 * Show the default toggle button for adding or removing an Item from Wishlist.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param null $args
		 */
		public static function show_default_btn( $args = null ) {
			$args = wp_parse_args( $args, array(
				'product_id' => ''
			) );
			if ( false === apply_filters( 'alg_wc_wl_btn_enabled', true ) ) {
				return;
			}
			if ( empty( $args['product_id'] ) ) {
				global $product;
				$args['product_id'] = is_a( $product, 'WC_Product' ) ? $product->get_id() : '';
			}
			if ( empty( $args['product_id'] ) ) {
				return '';
			}
			$toggle_btn_params = self::$toggle_btn_params;
			$product           = wc_get_product( $args['product_id'] );
			if ( is_a( $product, 'WC_Product_Variation' ) ) {
				$item_id = $product->get_parent_id();
			} elseif ( is_a( $product, 'WC_Product' ) ) {
				$item_id = $product->get_id();
			} else {
				return '';
			}
			$toggle_btn_params['product_id'] = $item_id;
			if ( filter_var( apply_filters('alg_wc_wl_show_default_btn', true, $item_id ), FILTER_VALIDATE_BOOLEAN ) === false ) {
				return;
			}
			$is_item_in_wish_list = false;
			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user->ID );
			} else {
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, null );
			}
			$toggle_btn_params['btn_class'].=' button';

			if ( $is_item_in_wish_list ) {
				$toggle_btn_params['btn_class'].=' remove alg-wc-wl-toggle-btn';
			} else {
				$toggle_btn_params['btn_class'].=' add alg-wc-wl-toggle-btn';
			}
			$toggle_btn_params['add_label']    = __('Add to Wishlist','wish-list-for-woocommerce');
			$toggle_btn_params['remove_label'] = __('Remove from Wishlist','wish-list-for-woocommerce');
			// Handle loading icon
			$toggle_btn_params['show_loading'] = false;
			if ( filter_var( get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_LOADING_ICON ), FILTER_VALIDATE_BOOLEAN ) ) {
				$toggle_btn_params['show_loading'] = true;
			}
			echo alg_wc_wl_locate_template( 'default-button.php', $toggle_btn_params );
			if ( current_filter() == 'woocommerce_product_thumbnails' ) {
				self::position_button_inside_product_gallery();
			}
		}

		/**
		 * Move default button on single page to product gallery, after thumbnails
		 *
		 * @version 1.4.3
		 * @since   1.4.3
		 */
		private static function position_button_inside_product_gallery(){
			?>
			<script>
				jQuery(window).load(function(){
                    jQuery('.alg-wc-wl-btn-wrapper').detach().appendTo('.woocommerce-product-gallery');
				});
			</script>
			<?php
		}

		/**
		 * Show the thumb button for adding or removing an Item from Wishlist.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param null $args
		 */
		public static function show_thumb_btn( $args = null ) {
			$args = wp_parse_args( $args, array(
				'product_id' => ''
			) );
			if ( false === apply_filters( 'alg_wc_wl_btn_enabled', true ) ) {
				return;
			}
			if ( empty( $args['product_id'] ) ) {
				global $product;
				$args['product_id'] = is_a( $product, 'WC_Product' ) ? $product->get_id() : '';
			}
			if ( empty( $args['product_id'] ) ) {
				return '';
			}
			$toggle_btn_params = self::$toggle_btn_params;
			$product           = wc_get_product( $args['product_id'] );
			if ( is_a( $product, 'WC_Product_Variation' ) ) {
				$item_id = $product->get_parent_id();
			} elseif ( is_a( $product, 'WC_Product' ) ) {
				$item_id = $product->get_id();
			} else {
				return '';
			}
			$toggle_btn_params['product_id'] = $item_id;
			if ( filter_var( apply_filters( 'alg_wc_wl_show_thumb_btn', true, $item_id ), FILTER_VALIDATE_BOOLEAN ) === false ) {
				return;
			}
			$is_item_in_wish_list = false;
			if ( is_user_logged_in() ) {
				$user                 = wp_get_current_user();
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user->ID );
			} else {
				$is_item_in_wish_list = Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, null );
			}
			if ( $is_item_in_wish_list ) {
				$toggle_btn_params['btn_class'] .= ' remove alg-wc-wl-thumb-btn';
			} else {
				$toggle_btn_params['btn_class'] .= ' add alg-wc-wl-thumb-btn';
			}
			$toggle_btn_params['btn_class'] .= ' alg-wc-wl-thumb-btn-abs';
			// Is Wishlist
			$is_wish_list                      = get_query_var( 'is_wish_list' );
			$toggle_btn_params['is_wish_list'] = $is_wish_list;
			if ( $is_wish_list ) {
				$toggle_btn_params['btn_class'] .= ' is_wish_list';
			}
			// Is Wishlist WooCommerce Template
			$wishlist_wc_template                       = get_query_var( 'wish_list_wc_template' );
			$toggle_btn_params['wish_list_wc_template'] = $wishlist_wc_template;
			if ( $wishlist_wc_template ) {
				$toggle_btn_params['btn_class'] .= ' wish_list_wc_template';
			}
			if ( current_filter() == 'woocommerce_before_shop_loop_item' ) {
				$toggle_btn_params['btn_class'] .= ' alg-wc-wl-thumb-btn-loop';
			} else if ( current_filter() == 'woocommerce_product_thumbnails' ) {
				$toggle_btn_params['btn_class'] .= ' alg-wc-wl-thumb-btn-single';
			}
			// Handle loading icon
			$toggle_btn_params['show_loading'] = false;
			if ( filter_var( get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_LOADING_ICON ), FILTER_VALIDATE_BOOLEAN ) ) {
				$toggle_btn_params['show_loading'] = true;
			}
			echo alg_wc_wl_locate_template( 'thumb-button.php', $toggle_btn_params );
		}

		/**
		 * Load buttons vars on javascript
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param type $script
		 */
		public static function localize_script( $script ) {
			$toggle_btn_params = self::$toggle_btn_params;
			$btn_class_arr = $toggle_btn_params['btn_class'];
			$toggle_btn_params['btn_class'] = '.' . implode( '.', explode( ' ', $btn_class_arr ) );
			wp_localize_script( $script, 'alg_wc_wl_toggle_btn', $toggle_btn_params );
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}

		/**
		 * get_toggle_btn_params.
		 */
		static function get_toggle_btn_params() {
			return self::$toggle_btn_params;
		}

	}

}