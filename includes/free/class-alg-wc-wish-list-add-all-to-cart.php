<?php
/**
 * Wishlist for WooCommerce - Add all to cart.
 *
 * Handles the "Add all to cart" button rendering, display and form submission.
 *
 * @version 3.5.8
 * @since   3.5.8
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Add_All_To_Cart' ) ) {

	class Alg_WC_Wish_List_Add_All_To_Cart {

		const NONCE_ACTION = 'alg_wc_wl_add_all_to_cart';

		const SHORTCODE_ADD_ALL_TO_CART_BTN = 'alg_wc_wl_add_all_to_cart_btn';

		/**
		 * init.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 */
		public function init() {
			add_shortcode( self::SHORTCODE_ADD_ALL_TO_CART_BTN, array( $this, 'sc_alg_wc_wl_add_all_to_cart_btn' ) );
			add_action( 'wp_loaded', array( $this, 'handle_form_submission' ), 20 );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, array( $this, 'display_add_all_to_cart_btn_before_wishlist' ), 10, 3 );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array( $this, 'display_add_all_to_cart_btn_after_wishlist' ), 10, 3 );
		}

		/**
		 * handle_form_submission.
		 *
		 * Adds all directly-purchasable products sent by the form to the cart,
		 * skipping products that require choosing options, and redirects.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 */
		public function handle_form_submission() {
			if ( ! isset( $_POST['alg_wc_wl_add_all'] ) ) {
				return;
			}
			check_admin_referer( self::NONCE_ACTION, 'alg_wc_wl_add_all_nonce' );
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce already checked via check_admin_referer.
			$items = isset( $_POST['alg_wc_wl_add_all_items'] ) ? wp_unslash( $_POST['alg_wc_wl_add_all_items'] ) : array();
			if ( ! is_array( $items ) ) {
				$items = array();
			}
			$added           = 0;
			$skipped_options = 0;
			foreach ( $items as $item_id => $quantity ) {
				$item_id = absint( $item_id );
				if ( ! $item_id ) {
					continue;
				}
				$product = wc_get_product( $item_id );
				if ( ! $product instanceof WC_Product ) {
					continue;
				}
				$quantity = absint( $quantity );
				if ( $quantity < 1 ) {
					$quantity = 1;
				}
				$variation_id = 0;
				if ( $product->is_type( 'variation' ) ) {
					$variation_id = $product->get_id();
					$item_id      = $product->get_parent_id();
				}
				if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
					continue;
				}
				if ( ! $variation_id && ( $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ) ) {
					++$skipped_options;
					continue;
				}
				if ( false !== WC()->cart->add_to_cart( $item_id, $quantity, $variation_id ) ) {
					++$added;
				}
			}
			if ( $added > 0 ) {
				/* translators: %d: number of products added to the cart. */
				wc_add_notice( sprintf( _n( '%d product has been added to the cart.', '%d products have been added to the cart.', $added, 'wish-list-for-woocommerce' ), $added ), 'success' );
			} else {
				wc_add_notice( __( 'No products were added to the cart.', 'wish-list-for-woocommerce' ), 'error' );
			}
			if ( $skipped_options > 0 ) {
				/* translators: %d: number of products not added to the cart. */
				wc_add_notice( sprintf( _n( '%d product was not added because it requires choosing product options.', '%d products were not added because they require choosing product options.', $skipped_options, 'wish-list-for-woocommerce' ), $skipped_options ), 'notice' );
			}
			$redirect_url = apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null );
			$redirect_url = apply_filters( 'alg_wc_wl_add_all_to_cart_redirect', $redirect_url, $added );
			wp_safe_redirect( $redirect_url );
			exit;
		}

		/**
		 * sc_alg_wc_wl_add_all_to_cart_btn.
		 *
		 * Shortcode for showing the add all to cart button.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 *
		 * @param   null  $atts
		 *
		 * @return string
		 */
		public function sc_alg_wc_wl_add_all_to_cart_btn( $atts = null ) {
			if ( 'no' === get_option( 'alg_wc_wl_sc_add_all_to_cart_btn', 'yes' ) ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- class constant, not user input; returned (not echoed) as shortcode fallback placeholder.
				return '[' . self::SHORTCODE_ADD_ALL_TO_CART_BTN . ']';
			}
			$atts = shortcode_atts( array(
				'label'     => apply_filters( 'alg_wc_wl_add_all_to_cart_btn_label', sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_ALL_TO_CART_BTN_LABEL, __( 'Add all to cart', 'wish-list-for-woocommerce' ) ) ) ),
				'btn_class' => 'alg-wc-wl-btn2 alg-wc-wl-add-all-to-cart',
			), $atts, self::SHORTCODE_ADD_ALL_TO_CART_BTN );

			return $this->get_add_all_to_cart_btn_html( null, array(), $atts );
		}

		/**
		 * get_add_all_to_cart_btn_html.
		 *
		 * Returns the add all to cart form markup.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 *
		 * @param   WP_Query|null  $the_query            Wishlist items query (auto display).
		 * @param   array          $products_attributes  Wishlist items attributes.
		 * @param   array          $atts                 Shortcode attributes.
		 *
		 * @return string
		 */
		public function get_add_all_to_cart_btn_html( $the_query = null, $products_attributes = array(), $atts = array() ) {
			$items = $this->get_add_all_to_cart_items( $the_query, $products_attributes );
			if ( empty( $items ) ) {
				return '';
			}

			$atts = wp_parse_args( $atts, array(
				'label'     => apply_filters( 'alg_wc_wl_add_all_to_cart_btn_label', sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_ALL_TO_CART_BTN_LABEL, __( 'Add all to cart', 'wish-list-for-woocommerce' ) ) ) ),
				'btn_class' => 'alg-wc-wl-btn2 alg-wc-wl-add-all-to-cart',
			) );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin template markup, escaped at source.
			return alg_wc_wl_locate_template( 'add-all-to-cart-button.php', array(
				'items'        => $items,
				'label'        => $atts['label'],
				'btn_class'    => $atts['btn_class'],
				'nonce_action' => self::NONCE_ACTION,
			) );
		}

		/**
		 * get_add_all_to_cart_items.
		 *
		 * Gets the item id => quantity pairs to be sent by the form.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 *
		 * @param   WP_Query|null  $the_query            Wishlist items query (auto display).
		 * @param   array          $products_attributes  Wishlist items attributes.
		 *
		 * @return array
		 */
		public function get_add_all_to_cart_items( $the_query = null, $products_attributes = array() ) {
			$items = array();
			if ( $the_query instanceof WP_Query && ! empty( $the_query->posts ) ) {
				foreach ( $the_query->posts as $post ) {
					$item_id = $post->ID;
					if ( isset( $products_attributes[ $item_id ]['variation_id'] ) && ! empty( $products_attributes[ $item_id ]['variation_id'] ) ) {
						$item_id = absint( $products_attributes[ $item_id ]['variation_id'] );
					}
					$quantity          = isset( $products_attributes[ $post->ID ]['quantity'] ) ? absint( $products_attributes[ $post->ID ]['quantity'] ) : 1;
					$items[ $item_id ] = max( 1, $quantity );
				}

				return $items;
			}
			if ( is_user_logged_in() ) {
				$user_id                   = get_current_user_id();
				$use_id_from_unlogged_user = false;
			} else {
				$user_id                   = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id( true );
				$use_id_from_unlogged_user = true;
			}
			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user, true );
			if ( ! is_array( $wishlisted_items ) ) {
				return array();
			}
			foreach ( $wishlisted_items as $item_id ) {
				$items[ absint( $item_id ) ] = 1;
			}

			return $items;
		}

		/**
		 * display_add_all_to_cart_btn_before_wishlist.
		 *
		 * Displays the add all to cart button before the wishlist table.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 *
		 * @param   WP_Query  $the_query            Wishlist items query.
		 * @param   array     $products_attributes  Wishlist items attributes.
		 * @param   array     $params               Wishlist template params.
		 */
		public function display_add_all_to_cart_btn_before_wishlist( $the_query, $products_attributes, $params ) {
			$this->display_add_all_to_cart_btn( 'before', $params, $the_query, $products_attributes );
		}

		/**
		 * display_add_all_to_cart_btn_after_wishlist.
		 *
		 * Displays the add all to cart button after the wishlist table.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 *
		 * @param   WP_Query  $the_query            Wishlist items query.
		 * @param   array     $products_attributes  Wishlist items attributes.
		 * @param   array     $params               Wishlist template params.
		 */
		public function display_add_all_to_cart_btn_after_wishlist( $the_query, $products_attributes, $params ) {
			$this->display_add_all_to_cart_btn( 'after', $params, $the_query, $products_attributes );
		}

		/**
		 * display_add_all_to_cart_btn.
		 *
		 * Outputs the add all to cart button if the display, context and
		 * current view conditions are met.
		 *
		 * @version 3.5.8
		 * @since   3.5.8
		 *
		 * @param   string          $position             'before' or 'after'.
		 * @param   array           $params               Wishlist template params.
		 * @param   WP_Query|null   $the_query            Wishlist items query.
		 * @param   array           $products_attributes  Wishlist items attributes.
		 */
		public function display_add_all_to_cart_btn( $position, $params = array(), $the_query = null, $products_attributes = array() ) {
			$display = get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_ALL_TO_CART_BTN_DISPLAY, 'disabled' );
			if ( $position !== $display ) {
				return;
			}
			if ( ! empty( $params['is_email'] ) || ! empty( $params['user_id_from_query_string'] ) ) {
				return;
			}
			$contexts = get_option( Alg_WC_Wish_List_Settings_List::OPTION_ADD_ALL_TO_CART_BTN_CONTEXT, array( 'wishlist_page', 'my_account_page' ) );
			if ( ! is_array( $contexts ) ) {
				$contexts = array();
			}
			global $wp_query;
			$theid           = isset( $wp_query->queried_object ) && isset( $wp_query->queried_object->ID ) ? intval( $wp_query->queried_object->ID ) : 0;
			$current_page_id = $theid ? $theid : ( ! empty( $params['current_page_id'] ) ? intval( $params['current_page_id'] ) : 0 );

			$in_wishlist_page = 0 < $current_page_id &&
				in_array( 'wishlist_page', $contexts, true ) &&
				intval( Alg_WC_Wish_List_Page::get_wish_list_page_id() ) === $current_page_id;
			$in_account_page  = in_array( 'my_account_page', $contexts, true ) &&
				( is_account_page() || ( 0 < $current_page_id && function_exists( 'wc_get_page_id' ) && intval( wc_get_page_id( 'myaccount' ) ) === $current_page_id ) );

			if ( ! $in_wishlist_page && ! $in_account_page ) {
				return;
			}
			$btn_html = $this->get_add_all_to_cart_btn_html( $the_query, $products_attributes );
			if ( ! empty( $btn_html ) ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin-generated template markup, escaped at source.
				echo $btn_html;
			}
		}

	}

}
