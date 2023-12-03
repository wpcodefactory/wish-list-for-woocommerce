<?php
/**
 * Wish List for WooCommerce Pro - Auto Remove option.
 *
 * Removes purchased products from wish list
 *
 * @version 2.1.1
 * @since   1.2.9
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Auto_Remove' ) ) {

	class Alg_WC_Wish_List_Auto_Remove {

		/**
		 * Initializes class.
		 *
		 * @version 2.0.8
		 * @since   2.0.8
		 */
		function init() {
			// On new order.
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'save_wishlist_user_id_on_order_processed' ), 999 );
			// On order status change.
			add_action( 'woocommerce_order_status_changed', array( $this, 'remove_wishlist_item_on_order_status_change' ), 11, 3 );
			// On added to cart
			add_action( 'woocommerce_add_to_cart', array( $this, 'remove_on_added_to_cart' ), 10, 4 );
		}

		/**
		 * remove_on_added_to_cart.
		 *
		 * @version 2.1.1
		 * @since   2.0.8
		 *
		 * @param $cart_id
		 * @param $product_id
		 * @param $qty
		 * @param $variation_id
		 *
		 * @throws Exception
		 */
		function remove_on_added_to_cart( $cart_id, $product_id, $qty, $variation_id ) {
			if ( 'yes' === get_option( 'alg_wc_wl_remove_if_added_to_cart', 'no' ) ) {
				$user_id                   = is_user_logged_in() ? get_current_user_id() : null;
				$use_id_from_unlogged_user = is_user_logged_in() ? false : true;
				$wishlisted_items          = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user, true );
				if ( ! empty( $wishlisted_items ) && is_array( $wishlisted_items ) ) {
					$items_to_check = array( $variation_id, $product_id );
					foreach ( $items_to_check as $item_id ) {
						if ( in_array( $item_id, $wishlisted_items ) ) {
							Alg_WC_Wish_List_Item::remove_item_from_wish_list( $item_id, $user_id, $use_id_from_unlogged_user );
							break;
						}
					}
				}
			}
		}

		/**
		 * Saves wish list user id in order.
		 *
		 * @version 1.2.9
		 * @since   1.2.9
		 *
		 * @param $order_id
		 *
		 * @throws Exception
		 */
		public function save_wishlist_user_id_on_order_processed( $order_id ) {
			$user_unlogged = true;
			if ( is_user_logged_in() ) {
				$user_unlogged = false;
				$user          = wp_get_current_user();
				$user_id       = $user->ID;
			} else {
				$user_id = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}

			update_post_meta( $order_id, Alg_WC_Wish_List_Order_Metas::WISH_LIST_USER_ID, $user_id );
			update_post_meta( $order_id, Alg_WC_Wish_List_Order_Metas::WISH_LIST_USER_UNLOGGED, $user_unlogged );
		}

		/**
		 * Removes item from wish list after it is purchased.
		 *
		 * @version 1.4.7
		 * @since   1.2.9
		 * @param $order_id
		 * @param $transition_to
		 */
		public function remove_wishlist_item_on_order_status_change( $order_id, $transition_to ) {
			$order_status = get_option( Alg_WC_Wish_List_Settings_List::OPTION_REMOVE_IF_BOUGHT_STATUS, array('wc-completed','wc-processing') );
			if (
				! in_array( 'wc-' . $transition_to, $order_status ) ||
				! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_REMOVE_IF_BOUGHT ), FILTER_VALIDATE_BOOLEAN )
			) {
				return;
			}

			$order   = wc_get_order( $order_id );
			$user_id = get_post_meta( $order_id, Alg_WC_Wish_List_Order_Metas::WISH_LIST_USER_ID, true );
			$unlogged_user_id = get_post_meta( $order_id, Alg_WC_Wish_List_Order_Metas::WISH_LIST_USER_UNLOGGED, true );

			/* @var WC_Order_Item_Product $order_item */
			foreach ( $order->get_items() as $item_id => $order_item ) {
				$prod_id = $order_item->get_product_id();
				$result = Alg_WC_Wish_List_Item::remove_item_from_wish_list( $prod_id, $user_id, $unlogged_user_id );
			}
		}
	}
}