<?php
/**
 * Wish List for WooCommerce Pro - Subtotal.
 *
 * @version 3.4.7
 * @since   2.0.3
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Subtotal' ) ) {

	class Alg_WC_Wish_List_Subtotal {

		protected $subtotal_template_info = array();

		/**
		 * init.
		 *
		 * @version 3.4.7
		 * @since   2.0.3
		 */
		function init() {
			add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'override_wishlist_params' ), 11, 3 );
			add_action( 'wp_footer', array( $this, 'subtotal_js' ), 11, 3 );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array( $this, 'display_subtotal' ), 9, 3 );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, array( $this, 'display_subtotal' ), 11, 3 );
		}

		/**
		 * display_subtotal.
		 *
		 * @version 3.4.7
		 * @since   2.0.4
		 *
		 * @param $wish_list_query
		 * @param $attributes
		 */
		function display_subtotal( $wish_list_query, $attributes, $params ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only query var used to identify a shared wishlist view, no data mutation.
			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ) : '';
			$shared_user               = Alg_WC_Wish_List_Query_Vars::parse_shared_user_id( $user_id_from_query_string );
			$queried_user_id           = $shared_user['user_id'] ? $shared_user['user_id'] : $shared_user['guest_id'];
			// Doesn't show if queried user id is the user itself
			if ( $queried_user_id && Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id() != $queried_user_id ) {
				return $wish_list_query;
			}
			if (
				'yes' === get_option( 'alg_wc_wl_subtotal', 'no' ) &&
				in_array( current_filter(), get_option( 'alg_wc_wl_subtotal_position', array( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER ) ) ) &&
				( ! isset( $params['is_email'] ) || ! $params['is_email'] )
			) {
				$subtotal_value = 0;
				$wishlist       = array();
				while ( $wish_list_query->have_posts() ) {
					$wish_list_query->the_post();
					$product                  = wc_get_product( get_the_ID() );
					$qty                      = apply_filters( 'alg_wc_wl_subtotal_qty', 1, $product, $attributes );
					$subtotal_value           += (float) $qty * (float) $product->get_price();
					$wishlist[ get_the_ID() ] = array( 'subtotal' => (float) $qty * (float) $product->get_price(), 'price' => (float) $product->get_price() );
				}
				wp_reset_postdata();
				$args['subtotal_value']       = (float) $subtotal_value;
				$args['subtotal_value_html']  = wc_price( $subtotal_value );
				$args['wishlist']             = $wishlist;
				$this->subtotal_template_info = $args;
				/*wc_get_template( 'alg_wcwl-subtotal.php', $args );*/
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted template HTML rendered by plugin/theme template loader.
				echo alg_wc_wl_locate_template( 'alg_wcwl-subtotal.php', $args );
			}
		}

		/**
		 * override_wishlist_params.
		 *
		 * @version 2.0.4
		 * @since   2.0.3
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return array
		 */
		function override_wishlist_params( $params, $final_file, $path ) {
			if ( 'wish-list.php' !== $path ) {
				return $params;
			}
			if ( 'yes' === get_option( 'alg_wc_wl_subtotal_column', 'no' ) ) {
				$params['subtotal_column'] = true;
			}
			if ( 'yes' === get_option( 'alg_wc_wl_subtotal', 'no' ) ) {
				$params['subtotal'] = true;
			}

			return $params;
		}

		/**
		 * subtotal_js.
		 *
		 * @version 2.0.4
		 * @since   2.0.3
		 */
		function subtotal_js() {
			if (
				(
					( ! empty( $object_id = get_queried_object_id() ) && (int) $object_id !== (int) Alg_WC_Wish_List_Page::get_wish_list_page_id() && ! is_wc_endpoint_url( get_option( 'alg_wc_wl_tab_slug', 'my-wish-list' ) ) ) ||
					( empty( $object_id ) )
				) ||
				'yes' !== get_option( 'alg_wc_wl_subtotal_column', 'no' ) &&
				'yes' !== get_option( 'alg_wc_wl_subtotal', 'no' )
			) {
				return;
			}
			$php_to_js = array(
				'locale'            => get_locale(),
				'currency'          => get_woocommerce_currency(),
				'decimals'          => wc_get_price_decimals(),
				'wishlist'          => isset( $this->subtotal_template_info['wishlist'] ) ? $this->subtotal_template_info['wishlist'] : new stdClass(),
				'wishlist_subtotal' => isset( $this->subtotal_template_info['subtotal_value'] ) ? $this->subtotal_template_info['subtotal_value'] : 0
			);
			?>
			<script>
				document.addEventListener( 'DOMContentLoaded', function () {
					let data = <?php echo wp_json_encode( $php_to_js );?>;
					let formatter = new Intl.NumberFormat( data.locale.replace( "_", "-" ), {
						style: 'currency',
						currency: data.currency,
						maximumFractionDigits: data.decimals
					} );
					document.addEventListener( "alg-wc-wl-qty-change", function ( e ) {
						let prodId = e.detail.prodId;
						let originalSubtotalObj = jQuery( '.alg-wc-wl-subtotal[data-product-id=' + prodId + ']' );
						if ( originalSubtotalObj.length ) {
							let price = originalSubtotalObj.attr( 'data-price' );
							let productTotal = parseFloat( price * e.detail.qty );
							originalSubtotalObj.html( formatter.format( productTotal ) );
						}
						if ( data.wishlist[ prodId ] ) {
							data.wishlist[ prodId ].subtotal = data.wishlist[ prodId ].price * e.detail.qty;
						}
						updateSubtotal();
					} );
					jQuery( "body" ).on( 'alg_wc_wl_toggle_wl_item', function ( e ) {
						let removedItemId = parseInt( e.item_id );
						if ( e.response.data.action == 'removed' ) {
							if ( data.wishlist[ removedItemId ] ) {
								delete data.wishlist[ removedItemId ];
							}
							updateSubtotal();
						}
					} );

					function updateSubtotal() {
						if ( data.wishlist_subtotal > 0 ) {
							let total = 0
							for ( const key in data.wishlist ) {
								total += data.wishlist[ key ].subtotal;
							}
							jQuery( '.alg-wc-wl-subtotal-value' ).html( formatter.format( total ) );
						}
					}
				} );
			</script>
			<?php
		}
	}
}