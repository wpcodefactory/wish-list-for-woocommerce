<?php
/**
 * Wish List for WooCommerce - Quantity
 *
 * @version 3.5.1
 * @since   1.7.2
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Quantity' ) ) {

	class Alg_WC_Wish_List_Quantity {

		/**
		 * Alg_WC_Wish_List_Quantity constructor.
		 *
		 * @version 3.5.1
		 * @since   1.7.2
		 */
		public function __construct() {
			add_action( 'alg_wc_wl_table_head', array( $this, 'render_table_head_product_qty' ), 30, 2 );
			add_action( 'alg_wc_wl_table_body', array( $this, 'render_table_body_product_qty' ), 30, 4 );
			add_filter( 'alg_wc_wl_add_to_cart_qty', array( $this, 'get_item_qty' ), 10, 3 );
			add_filter( 'alg_wc_wl_subtotal_qty', array( $this, 'get_item_qty' ), 10, 3 );
			add_filter( 'wp_footer', array( $this, 'handle_add_to_cart_btn' ), 99 );
			add_filter( 'wp_footer', array( $this, 'save_quantity_js' ), 99 );
			add_action( 'wp_ajax_' . 'alg_wc_wl_save_qty', array( $this, 'save_quantity_ajax' ) );
			add_action( 'wp_ajax_nopriv_' . 'alg_wc_wl_save_qty', array( $this, 'save_quantity_ajax' ) );
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'change_add_to_cart_link' ), 10 );
			add_action( 'wp_footer', array( $this, 'add_quantity_on_js_toggle_item' ), 100 );
			add_action( 'alg_wc_wl_toggle_wish_list_item', array( $this, 'save_quantity_on_toggle' ) );
		}

		/**
		 * save_quantity_on_toggle.
		 *
		 * @version 3.4.5
		 * @since   2.0.3
		 *
		 * @param $final_response
		 */
		function save_quantity_on_toggle( $final_response ) {
			$posted_nonce = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
			if ( empty( $posted_nonce ) || ! wp_verify_nonce( $posted_nonce, 'alg_wc_wl' ) ) {
				return;
			}

			$item_id = isset( $_POST['alg_wc_wl_item_id'] ) ? absint( wp_unslash( $_POST['alg_wc_wl_item_id'] ) ) : 0;
			$qty     = isset( $_POST['qty'] ) ? wc_stock_amount( sanitize_text_field( wp_unslash( $_POST['qty'] ) ) ) : 0;
			if (
				$item_id > 0 &&
				$qty > 0 &&
				isset( $final_response['action'] ) &&
				'added' === $final_response['action']
			) {
				if ( is_user_logged_in() ) {
					Alg_WC_Wish_List_Item::update_wish_list_item_metas( $item_id, 'quantity', $qty, get_current_user_id() );
				} else {
					Alg_WC_Wish_List_Item::update_wish_list_item_metas( $item_id, 'quantity', $qty, null, true );
				}
			}
		}

		/**
		 * add_quantity_on_js_toggle_item.
		 *
		 * @version 3.2.2
		 * @since   2.0.3
		 */
		public function add_quantity_on_js_toggle_item() {
			?>
			<script>
				jQuery( 'body' ).on( 'alg_wc_wish_list_init', function ( event ) {
					var $ = jQuery;
					var alg_wc_wl_orig_wishlist_item_data = alg_wc_wl_get_toggle_wishlist_item_data;
					alg_wc_wl_get_toggle_wishlist_item_data = function ( clicked_btn ) {
						var data = alg_wc_wl_orig_wishlist_item_data( clicked_btn );
						if ( $( '.input-text.qty' ).length && $( '.variation_id' ).length && parseFloat( $( '.variation_id' ).val() ) === parseFloat( data.alg_wc_wl_item_id ) ) {
							data.qty = parseFloat( $( '.input-text.qty' ).val() );
						}
						return data;
					}
				} );
			</script>
			<?php
		}

		/**
		 * add_to_cart_link
		 *
		 * @version 3.4.2
		 * @since   1.7.2
		 *
		 * @param $link
		 *
		 * @return string
		 */
		function change_add_to_cart_link( $link ) {
			global $wp_query;
			if (
				'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_QUANTITY, 'no' )
				|| is_shop()
				|| is_archive()
				|| is_product()
			) {
				return $link;
			}

			$attrs = wp_kses_hair( $link, array( 'http', 'https' ) );

			if ( empty( $attrs['data-product_id']['value'] ) ) {
				return $link;
			}
			$qty        = (int) ( $attrs['data-quantity']['value'] ?? 1 );

			$href       = $attrs['href']['value'] ?? null;
			if ( ! $href ) {
				return $link;
			}

			$url = add_query_arg( array( 'quantity' => $qty ), $href );
			return str_replace(
				'href="' . $href . '"',
				'href="' . esc_url( $url ) . '"',
				$link
			);
		}

		/**
		 * save_quantity_ajax.
		 *
		 * @version 3.4.4
		 * @since   1.7.2
		 */
		function save_quantity_ajax() {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_QUANTITY, 'no' ) ) {
				return;
			}
			check_ajax_referer( 'alg-wc-wl-security-save-qty', 'security' );
			if (
				! isset( $_POST['qty'] ) ||
				empty( $qty = intval( $_POST['qty'] ) ) ||
				! isset( $_POST['prod_id'] ) ||
				empty( $prod_id = intval( $_POST['prod_id'] ) )
			) {
				die();
			}
			if ( ! isset( $_POST['wltab_id'] ) && isset( $_POST['wltab_id_param'] ) ) {
				$_POST['wltab_id'] = intval( $_POST['wltab_id_param'] );
			}
			if ( is_user_logged_in() ) {
				Alg_WC_Wish_List_Item::update_wish_list_item_metas( $prod_id, 'quantity', $qty, get_current_user_id() );
			} else {
				Alg_WC_Wish_List_Item::update_wish_list_item_metas( $prod_id, 'quantity', $qty, null, true );
			}
			wp_send_json_success( array( 'prod_id' => $prod_id, 'qty' => $qty ) );
		}

		/**
		 * save_quantity_js.
		 *
		 * @version 3.4.4
		 * @since   1.7.2
		 *
		 * @see     https://blog.garstasio.com/you-dont-need-jquery/ajax/
		 * @see     https://getbutterfly.com/how-to-replace-jquery-ajax-with-vanilla-javascript-in-wordpress/
		 */
		function save_quantity_js() {
			if (
				'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_QUANTITY, 'no' ) ||
				(
					( ! empty( $object_id = get_queried_object_id() ) && (int) $object_id !== (int) Alg_WC_Wish_List_Page::get_wish_list_page_id() && ! is_wc_endpoint_url( get_option( 'alg_wc_wl_tab_slug', 'my-wish-list' ) ) ) ||
					( empty( $object_id ) )
				)
			) {
				return;
			}
			?>
			<script>
				document.addEventListener( 'DOMContentLoaded', function () {
					function getWtab() {
						let wtabEl = document.getElementById( 'wtab' );
						if ( wtabEl && wtabEl.value ) return wtabEl.value;
						let urlParams = new URLSearchParams( window.location.search );
						return urlParams.get( 'wtab' ) || 0;
					}
					function saveQty( qty, prodId ) {
						let request = new XMLHttpRequest();
						request.open( 'POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', true );
						request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
						request.send(
							'action=alg_wc_wl_save_qty' +
							'&qty=' + qty +
							'&security=' + '<?php echo esc_js( wp_create_nonce( 'alg-wc-wl-security-save-qty' ) ); ?>' +
							'&prod_id=' + prodId +
							'&wltab_id=' + getWtab()
						);
					}
					document.body.addEventListener( "focusout", function ( event ) {
						if (
							!event.target.classList.contains( "qty" ) ||
							!event.target.closest( '.alg-wc-wl-view-table' )
						) {
							return;
						}
						let td     = event.target.closest( 'td' );
						let prodId = td.querySelectorAll( '.prod-id' );
						if ( prodId.length ) {
							saveQty( event.target.value, prodId[ 0 ].value );
						}
					} );
					document.body.addEventListener( "change", function ( event ) {
						if (
							!event.target.classList.contains( "qty" ) ||
							!event.target.closest( '.alg-wc-wl-view-table' )
						) {
							return;
						}
						let qty       = event.target.value;
						let td        = event.target.closest( 'td' );
						let prodInput = td.querySelectorAll( '.prod-id' );
						if ( prodInput.length ) {
							// Save to DB
							saveQty( qty, prodInput[ 0 ].value );
							// Update subtotal UI
							document.dispatchEvent( new CustomEvent( 'alg-wc-wl-qty-change', {
								detail: {
									qty: qty,
									prodId: prodInput[ 0 ].value
								}
							} ) );
						}
					} );
				} )
			</script>
			<?php
		}

		/**
		 * handle_add_to_cart_btn.
		 *
		 * @version 1.7.2
		 * @since   1.7.2
		 */
		function handle_add_to_cart_btn() {
			global $post;
			if (
				'no' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_QUANTITY, 'no' ) ||
				! (
					is_singular() &&
					is_a( $post, 'WP_Post' ) &&
					has_shortcode( $post->post_content, Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST )
				) &&
				! is_account_page()
			) {
				return;
			}
			?>
			<script>
				document.addEventListener( 'DOMContentLoaded', function () {
					document.body.addEventListener( "change", function ( event ) {
						if (
							!event.target.classList.contains( "qty" ) ||
							!event.target.closest( '.alg-wc-wl-view-table' )
						) {
							return;
						}
						var tr = event.target.closest( 'tr' );
						var addToCartBtn = tr.querySelectorAll( '.add_to_cart_button' );
						if ( addToCartBtn.length ) {
							addToCartBtn[ 0 ].setAttribute( 'data-quantity', event.target.value );
							let href = new URLSearchParams( addToCartBtn[ 0 ].getAttribute( 'href' ) );
							href.set( 'quantity', event.target.value );
							addToCartBtn[ 0 ].setAttribute( 'href', '?' + href );
						}
					} );
				} )
			</script>
			<?php
		}

		/**
		 * Renders the quantity table head cell.
		 *
		 * @version 3.5.1
		 * @since   3.4.7
		 *
		 * @param array $params Wishlist template params.
		 * @param bool  $is_email Whether rendering for email.
		 */
		public function render_table_head_product_qty( $params, $is_email ) {
			$quantity_option = $is_email ? Alg_WC_Wish_List_Settings_List::OPTION_QUANTITIES_ON_EMAILS : Alg_WC_Wish_List_Settings_List::OPTION_SHOW_QUANTITY;
			if (
				'no' === get_option( $quantity_option, 'no' ) ||
				$this->is_shared_wishlist_view()
			) {
				return;
			}
			?>
			<th class="product-qty"><?php esc_html_e( 'Quantity', 'wish-list-for-woocommerce' ); ?></th>
			<?php
		}

		/**
		 * Renders the quantity table body cell.
		 *
		 * @version 3.4.7
		 * @since   3.4.7
		 *
		 * @param array      $params Wishlist template params.
		 * @param WC_Product $product Wishlist product.
		 * @param array      $products_attributes Product attributes from item metas.
		 * @param bool       $is_email Whether rendering for email.
		 */
		public function render_table_body_product_qty( $params, $product, $products_attributes, $is_email ) {
			$quantity_option = $is_email ? Alg_WC_Wish_List_Settings_List::OPTION_QUANTITIES_ON_EMAILS : Alg_WC_Wish_List_Settings_List::OPTION_SHOW_QUANTITY;
			if (
				'no' === get_option( $quantity_option, 'no' ) ||
				$this->is_shared_wishlist_view()
			) {
				return;
			}
			$qty      = isset( $products_attributes[ $product->get_id() ] ) && isset( $products_attributes[ $product->get_id() ]['quantity'] ) ? esc_attr( $products_attributes[ $product->get_id() ]['quantity'] ) : null;
			$qty_args = array( 'min_value' => 1 );
			if ( null !== $qty ) {
				$qty_args['input_value'] = $qty;
			}
			?>
			<td data-title="<?php esc_attr_e( 'Quantity', 'wish-list-for-woocommerce' ); ?>" class="product-qty">
				<?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce helper returns safe form markup. ?>
				<?php echo woocommerce_quantity_input( $qty_args, $product, false ); ?>
				<input type="hidden" class="prod-id" name="prod_id" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
			</td>
			<?php
		}

		/**
		 * Returns the item quantity stored on the wishlist item metas.
		 *
		 * @version 3.4.7
		 * @since   3.4.7
		 *
		 * @param int|string $qty Current quantity.
		 * @param WC_Product $product Wishlist product.
		 * @param array      $products_attributes Product attributes from item metas.
		 *
		 * @return int
		 */
		public function get_item_qty( $qty, $product, $products_attributes ) {
			if ( isset( $products_attributes[ $product->get_id() ] ) && isset( $products_attributes[ $product->get_id() ]['quantity'] ) ) {
				return intval( $products_attributes[ $product->get_id() ]['quantity'] );
			}

			return $qty;
		}

		/**
		 * Checks if the current wishlist view belongs to another user.
		 *
		 * @version 3.4.7
		 * @since   3.4.7
		 *
		 * @return bool
		 */
		protected function is_shared_wishlist_view() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only query var used to identify a shared wishlist view, no data mutation.
			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ) : '';
			$shared_user               = Alg_WC_Wish_List_Query_Vars::parse_shared_user_id( $user_id_from_query_string );
			$queried_user_id           = $shared_user['user_id'] ? $shared_user['user_id'] : $shared_user['guest_id'];

			// Doesn't show if queried user id is the user itself.
			return $queried_user_id && (string) Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id() !== (string) $queried_user_id;
		}
	}
}