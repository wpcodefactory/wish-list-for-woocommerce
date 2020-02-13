<?php
/**
 * Wish List for WooCommerce - Alg_WC_Wish_List Class
 *
 * @class   Alg_WC_Wish_List
 * @version 1.6.3
 * @since   1.0.0
 */

if ( ! class_exists( 'Alg_WC_Wish_List' ) ) {

	class Alg_WC_Wish_List {

		public static $toggle_item_return = array();

		/**
		 * Saves wish list on register
		 *
		 * @version 1.4.1
		 * @since   1.0.0
		 *
		 * @param $user_id
		 *
		 * @return mixed
		 */
		public static function save_wish_list_on_register( $user_id ) {
			$wishlisted_items = self::get_wish_list( null );
			if ( is_array( $wishlisted_items ) && count( $wishlisted_items ) > 0 ) {
				foreach ( $wishlisted_items as $key => $item_id ) {
					Alg_WC_Wish_List_Item::add_item_to_wish_list( $item_id, $user_id );
					Alg_WC_Wish_List_Item::remove_item_from_wish_list( $item_id, null, true );
				}

			}
			$transient        = Alg_WC_Wish_List_Transients::WISH_LIST_METAS;
			$unlogged_user_id = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
			$metas            = get_transient( "{$transient}{$unlogged_user_id}" );
			if ( $metas ) {
				$response = update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS, $metas );
			}

			return $user_id;
		}

		/**
		 * Saves wish list on login
		 *
		 * @version 1.4.1
		 * @since   1.3.9
		 *
		 * @param $login
		 * @param $user
		 */
		public static function save_wish_list_on_login( $login, $user ) {
			if ( ! $user ) {
				return;
			}
			$user_id = $user->ID;

			$wishlist_unlogged = Alg_WC_Wish_List::get_wish_list( null );

			if ( is_array( $wishlist_unlogged ) && count( $wishlist_unlogged ) > 0 ) {
				foreach ( $wishlist_unlogged as $key => $item_id ) {
					if ( ! Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user_id ) ) {
						Alg_WC_Wish_List_Item::add_item_to_wish_list( $item_id, $user_id );
					}
					Alg_WC_Wish_List_Item::remove_item_from_wish_list( $item_id, null, true );
				}
			}
		}

		/**
		 * Shows wish list notification in case an item has been toggled
		 *
		 * @version 1.5.2
		 * @since   1.5.2
		 * @param $options
		 *
		 * @return mixed
		 */
		public static function show_wishlist_notification( $options ) {
			if ( empty( self::$toggle_item_return ) || ! self::$toggle_item_return['ok'] ) {
				return $options;
			}
			$options['toggle_item_return']['data'] = self::$toggle_item_return;
			return $options;
		}

		/**
		 * Toggles Wish Wist item by url
		 *
		 * @version 1.5.2
		 * @since   1.5.2
		 */
		public static function toggle_wishlist_item_by_url() {
			if (
				! isset( $_GET['wishlist_toggle'] ) ||
				! filter_var( $_GET['wishlist_toggle'], FILTER_VALIDATE_INT ) ||
				! wc_get_product( $_GET['wishlist_toggle'] )
			) {
				return;
			}
			$item_id = $_GET['wishlist_toggle'];

			$response                 = Alg_WC_Wish_List::toggle_wish_list_item( array(
				'item_id' => $item_id
			) );
			self::$toggle_item_return = $response;
		}

		/**
		 * get_url().
		 *
		 * @version 1.5.7
		 * @since   1.5.7
		 * @return string
		 */
		public static function get_url() {
			$url = add_query_arg( array_filter( array(
				Alg_WC_Wish_List_Query_Vars::USER          => is_user_logged_in() ? Alg_WC_Wish_List_Query_Vars::crypt_user( get_current_user_id() ) : Alg_WC_Wish_List_Cookies::get_unlogged_user_id(),
				Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED => is_user_logged_in() ? 0 : 1,
			) ), wp_get_shortlink( Alg_WC_Wish_List_Page::get_wish_list_page_id() ) );

			return $url;
		}

		/**
		 * Toggles Wish List Item
		 *
		 * @version 1.6.3
		 * @since   1.5.2
		 * @param array $args
		 *
		 * @return array|bool|mixed|void
		 */
		public static function toggle_wish_list_item( $args = array() ) {
			$args = wp_parse_args( $args, array(
				'item_id'          => null,
				'unlogged_user_id' => null,
			) );
			$item_id = filter_var( $args['item_id'], FILTER_SANITIZE_NUMBER_INT );
			if ( empty( $item_id ) ) {
				return false;
			}

			$message = '';
			$product = wc_get_product( $item_id );
			$all_ok  = true;
			$action  = 'added'; // 'added' | 'removed' | error | cant_toggle_unlogged
			$icon = false;

			$params = apply_filters( 'alg_wc_wl_toggle_item_texts', array(
				'added'                => __( '%s was successfully added to wish list.', 'wish-list-for-woocommerce' ),
				'removed'              => __( '%s was successfully removed from wish list', 'wish-list-for-woocommerce' ),
				'see_wish_list'        => __( 'See your wish list', 'wish-list-for-woocommerce' ),
				'error'                => __( 'Sorry, Some error occurred. Please, try again later.', 'wish-list-for-woocommerce' ),
				'cant_toggle_unlogged' => sprintf( __( 'Please <a class=\'alg-wc-wl-link\' href="%s">login</a> if you want to use the Wishlist', 'wish-list-for-woocommerce' ), wc_get_page_permalink( 'myaccount' ) ),
			) );

			if ( ! is_user_logged_in() ) {
				if ( true === apply_filters( 'alg_wc_wl_can_toggle_unlogged', true ) ) {
					$unlogged_user_id = ! empty( $args['unlogged_user_id'] ) ? sanitize_text_field( $args['unlogged_user_id'] ) : Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
					$response         = Alg_WC_Wish_List_Item::toggle_item_from_wish_list( $item_id, $unlogged_user_id, true );
				} else {
					$icon     = 'fa fa-exclamation-circle';
					$response = 'cant_toggle_unlogged';
					$action   = 'cant_toggle_unlogged';
				}
			} else {
				$user     = wp_get_current_user();
				$response = Alg_WC_Wish_List_Item::toggle_item_from_wish_list( $item_id, $user->ID );
			}

			if ( $response === false ) {
				$message = $params['error'];
				$all_ok  = false;
				$action  = 'error';
			} elseif ( $response === true ) {
				$message = sprintf(
					$params['removed'],
					'<b>' . $product->get_title() . '</b>'
				);
				$action  = 'removed';
			} elseif ( is_numeric( $response ) ) {
				$wish_list_page_id         = Alg_WC_Wish_List_Page::get_wish_list_page_id();
				$wish_list_permalink       = get_permalink( $wish_list_page_id );
				$see_your_wishlist_message = $params['see_wish_list'];
				$added_message             = sprintf(
					$params['added'],
					'<b>' . $product->get_title() . '</b>'
				);

				$message = "{$added_message}<br /> <a class='alg-wc-wl-notification-link' href='{$wish_list_permalink}'>{$see_your_wishlist_message}</a>";

				$show_wish_list_link = filter_var( get_option( Alg_WC_Wish_List_Settings_Notification::OPTION_SHOW_WISH_LIST_LINK, true ), FILTER_VALIDATE_BOOLEAN );
				if ( $show_wish_list_link && ! empty( $wish_list_page_id ) ) {
					$message = "{$added_message}<br /> <a class='alg-wc-wl-notification-link' href='{$wish_list_permalink}'>{$see_your_wishlist_message}</a>";
				} else {
					$message = "{$added_message}";
				}

				$action = 'added';
			} elseif ( 'cant_toggle_unlogged' == $response ) {
				$message = $params['cant_toggle_unlogged'];
			}

			$final_response = array(
				'ok'                   => $all_ok,
				'message'              => $message,
				'action'               => $action,
				'toggle_item_response' => $response,
				'icon'                 => $icon
			);
			$final_response = apply_filters( 'alg_wc_wl_toggle_item_response', $final_response );
			do_action( 'alg_wc_wl_toggle_wish_list_item', $final_response );
			return $final_response;
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
		 * Get user wishlist.
		 *
		 * If user is logged get wishlist from user meta.
		 * If user is unlogged get wishlist from transient.
		 * If user_id is passed along with the $use_id_from_unlogged_user boolean as true then get wishlist from transient.
		 *
		 * @version 1.3.0
		 * @since   1.0.0
		 * @param null $user_id
		 * @param bool $use_id_from_unlogged_user
		 * @return array|null
		 */
		public static function get_wish_list( $user_id = null, $use_id_from_unlogged_user = false ) {
			if ( $user_id ) {
				if ( ! $use_id_from_unlogged_user ) {
					$wishlisted_items = get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, false );
				} else {
					$transient        = Alg_WC_Wish_List_Transients::WISH_LIST;
					$wishlisted_items = get_transient( "{$transient}{$user_id}" );
				}
			} else {
				$transient        = Alg_WC_Wish_List_Transients::WISH_LIST;
				$user_id          = Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
				$wishlisted_items = get_transient( "{$transient}{$user_id}" );
			}
			return $wishlisted_items;
		}

	}

}