<?php
/**
 * Wishlist for WooCommerce - Ajax.
 *
 * @version 1.8.9
 * @since   1.0.0
 * @author  WPFactory
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Ajax' ) ) {

	class Alg_WC_Wish_List_Ajax {

		const ACTION_TOGGLE_WISH_LIST_ITEM = 'alg_wc_wl_toggle_item';
		const ACTION_GET_WISH_LIST = 'alg_wc_wl_get_wish_list';
		const ACTION_REMOVE_ALL_FROM_WISH_LIST = 'alg_wc_wl_remove_all_from_wish_list';
		const ACTION_GET_WISH_LIST_SHORTCODE = 'alg_wc_wl_pro_get_wish_list_sc';
		
		
		/**
		 * handle_unlogged_users_response.
		 *
		 * @version 1.6.5
		 * @since   1.6.5
		 *
		 * @param $final_response
		 *
		 * @return mixed
		 */
		public static function handle_unlogged_users_response( $final_response ) {
			if (
				! is_user_logged_in() &&
				false === apply_filters( 'alg_wc_wl_can_toggle_unlogged', true )
			) {
				$admin_message             = get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_DISALLOW_UNLOGGED, __( 'Please {login} if you want to use the Wishlist', 'wish-list-for-woocommerce' ) );
				$final_response['message'] = str_replace( '{login}', sprintf( '<a class=\'alg-wc-wl-link\' href="%s">login</a>', wc_get_page_permalink( 'myaccount' ) ), $admin_message );
			}
			return $final_response;
		}
		
		/**
		 * disallow_unlogged_users.
		 *
		 * @version 1.6.5
		 * @since   1.6.5
		 *
		 * @param $can_toggle_unlogged
		 *
		 * @return bool
		 */
		public static function disallow_unlogged_users( $can_toggle_unlogged ) {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_ALLOW_UNLOGGED_USERS, 'yes' ) ) {
				$can_toggle_unlogged = false;
			}
			return $can_toggle_unlogged;
		}
		
		/**
		 * remove_all_from_wish_list.
		 *
		 * @version 1.7.3
		 * @since   1.7.3
		 */
		static function remove_all_from_wish_list(){
			check_ajax_referer('alg_wc_wl', 'security');
			Alg_WC_Wish_List::remove_all_from_wish_list();
			wp_send_json_success();
		}

		/**
		 * Ajax method for toggling items to user wishlist
		 *
		 * @version 1.8.8
		 * @since   1.0.0
		 */
		public static function toggle_wish_list_item() {
			if ( ! isset( $_POST['alg_wc_wl_item_id'] ) ) {
				die();
			}
			check_ajax_referer( 'alg_wc_wl_toggle_item', 'nonce' );
			$response = Alg_WC_Wish_List::toggle_wish_list_item( array(
				'item_id'          => intval( sanitize_text_field( $_POST['alg_wc_wl_item_id'] ) ),
				'unlogged_user_id' => sanitize_text_field( $_POST['unlogged_user_id'] )
			) );
			$response = apply_filters( 'alg_wc_wl_toggle_item_ajax_response', $response );
			if ( $response['ok'] ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error( $response );
			}
		}

		/**
		 * Ajax method for get wishlist.
		 *
		 * @version 1.8.9
		 * @since   1.3.0
		 */
		public static function get_wish_list() {
			$args = wp_parse_args( $_POST, array(
				'ignore_excluded_items' => false,
			) );
			$use_id_from_unlogged_user = false;
			if ( is_user_logged_in() ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			} else {
				$use_id_from_unlogged_user = true;
				$user_id                   = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}
			$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user, $args['ignore_excluded_items'] );
			$response = array( 'wishlist' => ! is_array( $wishlisted_items ) ? array() : $wishlisted_items );
			wp_send_json_success( $response );
		}

		/**
		 * Load ajax actions on javascript
		 *
		 * @version 1.8.3
		 * @since   1.0.0
		 *
		 * @param $script
		 */
		public static function localize_script( $script ) {
			wp_localize_script( $script, 'alg_wc_wl_ajax', array(
				'action_remove_all'  => self::ACTION_REMOVE_ALL_FROM_WISH_LIST,
				'action_toggle_item' => self::ACTION_TOGGLE_WISH_LIST_ITEM,
				'ajax_action'        => self::ACTION_GET_WISH_LIST,
				'nonce'              => wp_create_nonce( 'alg_wc_wl' ),
				'toggle_nonce'       => wp_create_nonce( 'alg_wc_wl_toggle_item' ),
				'toggle_item_events' => apply_filters( 'alg_wc_wl_toggle_item_events', array(
					'default' => array(
						'mouseup',
						'touchend'
					),
					'touchscreen' => array(
						'mouseup',
						'touchend'
					)
				) )
			) );
			
			wp_localize_script( $script, 'alg_wc_wl_pro_get_wl_shortcode', array( 'ajax_action' => self::ACTION_GET_WISH_LIST_SHORTCODE ) );
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
		 * Updates wish list counter if ajax option is enabled
		 *
		 * @version 1.3.6
		 * @since   1.3.6
		 *
		 * @param string $handle What script should be handled.
		 */
		public static function update_wish_list_counter( $handle ) {
			$work_with_cache = filter_var( get_option( Alg_WC_Wish_List_Settings_General::OPTION_WORK_WITH_CACHE ), FILTER_VALIDATE_BOOLEAN );
			if ( ! $work_with_cache ) {
				return;
			}
			$script = "
			jQuery(document).ready(function($){
				if (typeof alg_wc_wl_counter === 'undefined' || jQuery.isEmptyObject(alg_wc_wl_counter)) {
					jQuery('body').on('alg_wc_wl_counter', function (e) {
						alg_wc_wl_counter = e.obj;
						alg_wc_wl_counter.update_counter();
					});
				} else {
					alg_wc_wl_counter.update_counter();
				}
			});
			";
			wp_add_inline_script( $handle, $script );
		}
		
		/**
		 * Get wishlist shortcode via ajax
		 *
		 * @version 1.8.0
		 * @since   1.2.8
		 * @param string $handle What script should be handled.
		 */
		public static function get_wishlist_sc_via_ajax( $handle ) {
			$work_with_cache = filter_var( get_option( Alg_WC_Wish_List_Settings_General::OPTION_WORK_WITH_CACHE ), FILTER_VALIDATE_BOOLEAN );
			if ( ! $work_with_cache ) {
				return;
			}
			$script = "
			jQuery(document).ready(function($){
				var unlogged_param = new URLSearchParams(window.location.search).get('alg_wc_wl_uunlogged');								
				var alg_wc_wl_user = new URLSearchParams(window.location.search).get('alg_wc_wl_user');				
				var wl_table_selector = '.alg-wc-wl-view-table-container';
				var wl_table_container = $(wl_table_selector);
				if(wl_table_container.length){					
					$.post(alg_wc_wl.ajaxurl, {action:alg_wc_wl_pro_get_wl_shortcode.ajax_action,alg_wc_wl_uunlogged:unlogged_param,alg_wc_wl_user:alg_wc_wl_user}, function (response) {
						if (response.success) {
							$(wl_table_selector).replaceWith($(response.data.shortcode));
							$(wl_table_selector).removeClass('ajax-loading');
							$('body').trigger({
								type: 'alg_wc_wl_ajax_complete',
								obj: response
							});
						}
					});
				}
			});
			";
			wp_add_inline_script( $handle, $script );
		}
		
		/**
		 * Get wishlist via ajax
		 *
		 * @version 1.8.0
		 * @since   1.2.8
		 * @param string $handle What script should be handled.
		 */
		public static function get_wishlist_via_ajax( $handle ) {
			$work_with_cache = filter_var( get_option( Alg_WC_Wish_List_Settings_General::OPTION_WORK_WITH_CACHE ), FILTER_VALIDATE_BOOLEAN );
			if (
				! $work_with_cache
			) {
				return;
			}
			$script = "
			var alg_wc_wl_get_wl_ajax = {
				init:function(){
					if(jQuery('.alg-wc-wl-thumb-btn').length || jQuery('.alg-wc-wl-toggle-btn').length){
						this.call_ajax();
					}
				},
				call_ajax:function(){
					jQuery.post(alg_wc_wl.ajaxurl, {action:alg_wc_wl_ajax.ajax_action}, function (response) {
						if (response.success) {							
							var wishlist = response.data.wishlist;							
							wishlist = Object.values(wishlist).map(function(item){
								return parseFloat(item);
							})							
							jQuery( '.alg-wc-wl-btn' ).removeClass('ajax-loading');
							jQuery( '.alg-wc-wl-btn' ).each(function( index ) {
								var prod_id = parseFloat(jQuery(this).attr('data-item_id'));
								if(jQuery.inArray(prod_id,wishlist)!=-1){									
									jQuery(this).addClass('remove');
									jQuery(this).removeClass('add');
								}else{
									jQuery(this).removeClass('remove');
									jQuery(this).addClass('add');
								}
							});							
						}
					});
				}
			};			
			jQuery(document).ready(function($){					
				alg_wc_wl_get_wl_ajax.init();
			});
			";
			wp_add_inline_script( $handle, $script );
		}

	}

}