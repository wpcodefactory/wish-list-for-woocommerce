<?php
/**
 * Wish List for WooCommerce Pro - Variable Products.
 *
 * @version 2.0.6
 * @since   2.0.3
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Variable_Products' ) ) {

	class Alg_WC_Wish_List_Variable_Products {
		/**
		 * init.
		 *
		 * @version 2.0.6
		 * @since   2.0.3
		 */
		function init() {
			add_action( 'wp_footer', array( $this, 'sync_variation_with_toggle_btn' ), PHP_INT_MAX );
			add_action( 'wp_footer', array( $this, 'add_variation_value_on_toggle_item' ), PHP_INT_MAX );
			add_action( 'alg_wc_wl_toggle_wish_list_item', array( $this, 'save_product_attributes' ) );
			add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'add_products_attributes_on_wish_list_template' ), 10, 3 );
		}

		/**
		 * Adds products attributes on wish list template
		 *
		 * @version 2.0.6
		 * @since   2.0.6
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function add_products_attributes_on_wish_list_template( $params, $final_file, $path ) {
			if (
				'yes' === get_option( Alg_WC_Wish_List_Settings_List::OPTION_SAVE_ATTRIBUTES, 'yes' ) &&
				'wish-list.php' === $path
			) {
				$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
				$query_var_user_id         = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
				$query_var_user_id         = empty( $query_var_user_id ) ? $user_id_from_query_string : $query_var_user_id;
				// Tries to get user if from query string
				$query_var_user = $query_var_user_id ? get_user_by( 'ID', $query_var_user_id ) : - 1;
				$user_id        = false;
				// If finds user id from query string and if it's a user, gets metas from user meta table, if not, gets from transient
				$get_user_from = 'transient';
				if ( is_a( $query_var_user, 'WP_User' ) ) {
					$user_id       = $query_var_user->ID;
					$get_user_from = 'user_meta';
				} else {
					$user_id = $query_var_user_id;
				}
				// If not finds user id from query string, so get id from current user
				if ( ! $user_id ) {
					if ( is_user_logged_in() ) {
						$get_user_from = 'user_meta';
						$user          = wp_get_current_user();
						$user_id       = $query_var_user_id ? $query_var_user_id : $user->ID;
					} else {
						$user_id = $query_var_user_id ? $query_var_user_id : Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
					}
				}
				// Gets metas from transient or user_meta
				if ( $get_user_from == 'transient' ) {
					$transient     = Alg_WC_Wish_List_Transients::WISH_LIST_METAS;
					$old_user_meta = get_transient( "{$transient}{$user_id}" );
				} else if ( $get_user_from == 'user_meta' ) {
					$user_id       = $query_var_user_id ? $query_var_user_id : $user->ID;
					$old_user_meta = get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS, true );
				}
				
				$current_tab_id = '';

				if ( isset($_GET) && isset($_GET['wtab']) && $_GET['wtab'] > 0) {
					$current_tab_id = $_GET['wtab'];
				}
				
				if ( $current_tab_id > 0 ) {
					
				}
			
				$params['product_attributes'] = $old_user_meta;
			}
			return $params;
		}

		/**
		 * save_product_attributes.
		 *
		 * @version 2.0.6
		 * @since   2.0.6
		 *
		 * @param $ajax_response
		 *
		 * @throws Exception
		 */
		public function save_product_attributes( $ajax_response ) {
			if (
				'yes' === get_option( 'alg_wc_wl_allow_variations', 'yes' ) &&
				isset( $_POST['alg_wc_wl_item_id'] ) &&
				! empty( $item_id = filter_var( $_POST['alg_wc_wl_item_id'], FILTER_VALIDATE_INT ) ) &&
				isset( $_POST['variation_id'] ) &&
				! empty( $variation_id = filter_var( $_POST['variation_id'], FILTER_VALIDATE_INT ) ) &&
				isset( $_POST['attributes'] ) &&
				! empty( $attributes = array_map( 'sanitize_text_field', $_POST['attributes'] ) )
			) {
				$unlogged_user_id          = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
				$user_id                   = ! is_user_logged_in() ? $unlogged_user_id : wp_get_current_user()->ID;
				$use_id_from_unlogged_user = ! is_user_logged_in() ? true : false;
				Alg_WC_Wish_List_Item::update_wish_list_item_metas( $item_id, 'attributes', $attributes, $user_id, $use_id_from_unlogged_user );
				Alg_WC_Wish_List_Item::update_wish_list_item_metas( $item_id, 'is_variation', true, $user_id, $use_id_from_unlogged_user );
			}
		}

		/**
		 * add_variation_value_on_toggle_item.
		 *
		 * @version 2.0.6
		 * @since   2.0.6
		 */
		function add_variation_value_on_toggle_item(){
			?>
			<script>
				jQuery(document).ready(function ($) {
					let alg_wc_wl_orig_wishlist_item_data = alg_wc_wl_get_toggle_wishlist_item_data;
					let attributes = {};
					let variationId = 0;
					alg_wc_wl_get_toggle_wishlist_item_data = function (clicked_btn) {
						let data = alg_wc_wl_orig_wishlist_item_data(clicked_btn);
						if (!jQuery.isEmptyObject(attributes)) {
							data['attributes'] = attributes;
							data.variation_id = variationId;
						}
						return data;
					}
					$(document).on('found_variation', 'form.cart', function (event, variation) {
						variationId = variation.variation_id;
						for (let attr_id in variation.attributes) {
							let variationInput = jQuery('.variations *[name=' + attr_id + ']');
							let variationInputText = variationInput.find(":selected").val();
							attributes[attr_id] = variationInputText;
						}
					});
					$(document).on('update_variation_values', 'form.cart', function (event, variation) {
						variationId = 0;
						attributes = {};
					});
				})
			</script>
			<?php
		}

		/**
		 * sync_variation_with_toggle_btn.
		 *
		 * @version 2.0.3
		 * @since   2.0.3
		 *
		 * @throws Exception
		 */
		function sync_variation_with_toggle_btn() {
			global $product;
			if (
				is_product() &&
				'yes' === get_option( 'alg_wc_wl_allow_variations', 'yes' ) &&
				is_a( $product, 'WC_Product_Variable' )
			) {
				$wishlisted_items = Alg_WC_Wish_List::get_wish_list( is_user_logged_in() ? get_current_user_id() : null, is_user_logged_in() ? false : true, true );
				$php_to_js = array(
					'variable_product_id' => $product->get_id(),
					'wishlist'            => is_array( $wishlisted_items ) ? array_map( 'intval', array_values( $wishlisted_items ) ) : array(),
				);
				?>
				<script>
					jQuery(document).ready(function ($) {
						let data = <?php echo json_encode( $php_to_js );?>;
						let btnsWithSameItemID = jQuery(alg_wc_wl_toggle_btn.btn_class + '[data-item_id="' + data.variable_product_id + '"]');
						btnsWithSameItemID.addClass('alg-wc-wl-variable-product');
						function sync_toggle_btn(itemId) {
							if ($('.alg-wc-wl-variable-product').attr('data-item_id') != itemId) {
								itemId = parseFloat(itemId);
								$('.alg-wc-wl-variable-product').attr('data-item_id', itemId);
								$('.alg-wc-wl-variable-product').removeClass('remove add');
								let btnClass = data.wishlist.includes(itemId) ? 'remove' : 'add';
								$('.alg-wc-wl-variable-product').addClass(btnClass)
							}
						}
						$(".single_variation_wrap").on("change", function (event) {
							let variationId = jQuery('.variation_id').val() == '' ? data.variable_product_id : jQuery('.variation_id').val();
							sync_toggle_btn(variationId);
						});
						$(".single_variation_wrap").on("show_variation", function (event, variation) {
							sync_toggle_btn(variation.variation_id);
						});
						$("body").on('alg_wc_wl_toggle_wl_item', function (e) {
							let changedItemID = parseInt(e.item_id);
							data.wishlist.includes(changedItemID) ? data.wishlist.splice(data.wishlist.indexOf(changedItemID), 1) : data.wishlist.push(changedItemID);
						});
					})
				</script>
				<?php
			}
		}
	}
}