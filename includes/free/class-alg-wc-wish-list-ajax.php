<?php
/**
 * Wishlist for WooCommerce - Ajax.
 *
 * @version 3.1.2
 * @since   1.0.0
 * @author  WPFactory
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Ajax' ) ) {

	class Alg_WC_Wish_List_Ajax {

		const ACTION_TOGGLE_WISH_LIST_ITEM 		= 'alg_wc_wl_toggle_item';
		const ACTION_GET_WISH_LIST 				= 'alg_wc_wl_get_wish_list';
		const ACTION_REMOVE_ALL_FROM_WISH_LIST 	= 'alg_wc_wl_remove_all_from_wish_list';
		const ACTION_GET_MULTIPLE_WISHLIST 		= 'alg_wc_wl_get_multiple_wish_list';
		const ACTION_GET_CLEAR_WISHLIST_ADMIN 	= 'alg_wc_wl_clear_wish_list_admin';
		const ACTION_SAVE_MULTIPLE_WISHLIST 	= 'alg_wc_wl_save_multiple_wish_list';
		const ACTION_DELETE_MULTIPLE_WISHLIST 	= 'alg_wc_wl_delete_multiple_wish_list';
		const ACTION_SAVE_WISHLIST 				= 'alg_wc_wl_save_to_multiple_wish_list';
		const ACTION_DUPLICATE_WISHLIST 		= 'alg_wc_wl_save_duplicate_wish_list';
		const ACTION_GET_WISH_LIST_SHORTCODE 	= 'alg_wc_wl_pro_get_wish_list_sc';
		
		
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
		 * Ajax method for toggling items to user wishlist.
		 *
		 * @version 1.8.8
		 * @since   1.0.0
		 */
		public static function toggle_wish_list_item() {
			if ( ! isset( $_POST['alg_wc_wl_item_id'] ) ) {
				die();
			}
			check_ajax_referer( 'alg_wc_wl_toggle_item', 'nonce' );
			if ( isset( $_POST['wtab_id'] ) && $_POST['wtab_id'] > 0 ) {
				$tab_id = $_POST['wtab_id'];
				$item_id = $_POST['alg_wc_wl_item_id'];
				$response = self::delete_multiple_wishlist_item( $item_id, $tab_id );
				wp_send_json_success( $response );
			} else {
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
		 * Load ajax actions on javascript.
		 *
		 * @version 3.1.0
		 * @since   1.0.0
		 *
		 * @param $script
		 */
		public static function localize_script( $script ) {
			$default_toggle_events = array_map( 'sanitize_text_field', array_map( 'trim', explode( ',', get_option( 'alg_wc_wl_default_js_toggle_events', 'mouseup,touchend' ) ) ) );
			$mobile_toggle_events = array_map( 'sanitize_text_field', array_map( 'trim', explode( ',', get_option( 'alg_wc_wl_mobile_js_toggle_events', 'mouseup,touchend' ) ) ) );
			wp_localize_script( $script, 'alg_wc_wl_ajax', array(
				'action_remove_all'  				=> self::ACTION_REMOVE_ALL_FROM_WISH_LIST,
				'action_toggle_item' 				=> self::ACTION_TOGGLE_WISH_LIST_ITEM,
				'action_get_multiple_wishlist' 		=> self::ACTION_GET_MULTIPLE_WISHLIST,
				'action_save_multiple_wishlist' 	=> self::ACTION_SAVE_MULTIPLE_WISHLIST,
				'action_delete_multiple_wishlist' 	=> self::ACTION_DELETE_MULTIPLE_WISHLIST,
				'is_multiple_wishlist_enabled' 		=> get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ),
				'is_current_page_wishlist' 			=> self::is_current_page_wishlist(),
				'action_save_wishlist' 				=> self::ACTION_SAVE_WISHLIST,
				'action_duplicate_wishlist' 		=> self::ACTION_DUPLICATE_WISHLIST,
				'ajax_action'        				=> self::ACTION_GET_WISH_LIST,
				'nonce'              				=> wp_create_nonce( 'alg_wc_wl' ),
				'toggle_nonce'       				=> wp_create_nonce( 'alg_wc_wl_toggle_item' ),
				'toggle_item_events'                => apply_filters( 'alg_wc_wl_toggle_item_events', array(
					'default'     => $default_toggle_events,
					'touchscreen' => $mobile_toggle_events
				) )
			) );
			
			wp_localize_script( $script, 'alg_wc_wl_pro_get_wl_shortcode', array( 'ajax_action' => self::ACTION_GET_WISH_LIST_SHORTCODE ) );
		}

		/**
		 * Returns class name.
		 *
		 * @version 2.0.5
		 * @since   2.0.5
		 * @return type
		 */
		public static function is_current_page_wishlist() {
			$current_page_id  =   get_the_ID();
			$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
			
			if( $wish_list_page_id == $current_page_id ){
				return 'yes';
			}
			
			return 'no';
		}
		
		/**
		 * Returns class name.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}
		
		/**
		 * Updates wish list counter if ajax option is enabled.
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
		 * Get wishlist shortcode via ajax.
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
		 * Get wishlist via ajax.
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
		
		/**
		 * Ajax method for save new wishlist.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		public static function save_to_multiple_wishlist() {
			$args = wp_parse_args( $_POST, array(
				'ignore_excluded_items' => false,
			) );
			
			if ( is_user_logged_in() ) {
				$user    	= wp_get_current_user();
				$user_id 	= $user->ID;
			} else {
				$user_id   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}
			
			
			$transient = Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE;
			
			$value = $args['value'];
			
			$wishlist_list = Alg_WC_Wish_List::get_multiple_wishlists( $user_id );
			if ( ! $wishlist_list ) {
				$wishlist_list = array();
			}
			array_push( $wishlist_list, $value );
			
			if( is_int( $user_id ) && $user_id > 0 ) {
				update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE_NAME, $wishlist_list );
			} else {
				set_transient( "{$transient}{$user_id}", $wishlist_list, 1 * MONTH_IN_SECONDS );
			}
			
			$response = array( 'wishlist_list' => ! is_array( $wishlist_list ) ? array() : $wishlist_list );
			
			wp_send_json_success( $response );
			
		}
		
		/**
		 * Ajax method for delete multiple wishlist item.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		public static function delete_multiple_wishlist_item($item_id, $tab_id) {
				$index = $tab_id - 1;
				
				if ( is_user_logged_in() ) {
					$user    	= wp_get_current_user();
					$user_id 	= $user->ID;
				} else {
					$user_id   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
				}
				
				$wishlist_list_items = Alg_WC_Wish_List::get_multiple_wishlists_with_all_item( $user_id );
				
				if( isset( $wishlist_list_items[$index] ) ) {
					
					if (($key = array_search($item_id, $wishlist_list_items[$index])) !== false) {
						unset($wishlist_list_items[$index][$key]);
					}
				}
				
				if( is_int( $user_id ) && $user_id > 0 ) {
					
					// remove only multiple wishlist items
					update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, $wishlist_list_items );
				} else {
				
					$transient = Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE_STORE;
					set_transient( "{$transient}{$user_id}", $wishlist_list_items, 1 * MONTH_IN_SECONDS );
				
				}
				
				$product = wc_get_product( $item_id );
				
				$params = apply_filters( 'alg_wc_wl_toggle_item_texts', array(
					'added'                => __( '%s was successfully added to wishlist.', 'wish-list-for-woocommerce' ),
					'removed'              => __( '%s was successfully removed from wishlist', 'wish-list-for-woocommerce' ),
					'see_wish_list'        => __( 'See your wishlist', 'wish-list-for-woocommerce' ),
					'error'                => apply_filters( 'alg_wc_wl_error_text', __( 'Sorry, Some error occurred. Please, try again later.', 'wish-list-for-woocommerce' ) ),
					'cant_toggle_unlogged' => sprintf( __( 'Please <a class=\'alg-wc-wl-link\' href="%s">login</a> if you want to use the Wishlist', 'wish-list-for-woocommerce' ), wc_get_page_permalink( 'myaccount' ) ),
				) );
				
				$wish_list_page_id         = Alg_WC_Wish_List_Page::get_wish_list_page_id();
				$wish_list_permalink       = get_permalink( $wish_list_page_id );
				$see_your_wishlist_message = $params['see_wish_list'];
				$added_message             = sprintf(
					$params['added'],
					'<b>' . $product->get_name() . '</b>'
				);
				$removed_message             = sprintf(
					$params['removed'],
					'<b>' . $product->get_name() . '</b>'
				);

				$message = "{$removed_message}<br /> <a class='alg-wc-wl-notification-link' href='{$wish_list_permalink}'>{$see_your_wishlist_message}</a>";
				$response = array(
					'ok'                   => true,
					'message'              => $message,
					'action'               => $action,
					'icon'                 => $icon
				);	
				
				return $response;
		}
		
		/**
		 * Ajax method for delete multiple wishlist.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		public static function delete_multiple_wishlist() {
			$args = wp_parse_args( $_POST, array(
				'ignore_excluded_items' => false,
			) );
			
			$wishlist_tab_id = $args['wishlist_tab_id'];
			$wishlist_page_id = $args['wishlist_page_id'];
			
			if($wishlist_tab_id > 0) {
				$index = $wishlist_tab_id - 1;
				if ( is_user_logged_in() ) {
					$user    	= wp_get_current_user();
					$user_id 	= $user->ID;
				} else {
					$user_id   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
				}
				
				$wishlist_list = Alg_WC_Wish_List::get_multiple_wishlists( $user_id );
				$wishlist_list_items = Alg_WC_Wish_List::get_multiple_wishlists_with_all_item( $user_id );
				
				if( isset($wishlist_list[$index]) ) {
					unset($wishlist_list[$index]);
				}
				if( isset( $wishlist_list_items[$index] ) ) {
					unset( $wishlist_list_items[$index] );
				}
				
				if( is_int( $user_id ) && $user_id > 0 ) {
					
					// save only multiple wishlist name
					update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE_NAME, $wishlist_list );
					
					// save only multiple wishlist items
					update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, $wishlist_list_items );
					
				} else {
				
					$transient = Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE;
					set_transient( "{$transient}{$user_id}", $wishlist_list, 1 * MONTH_IN_SECONDS );
					
					$transient = Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE_STORE;
					set_transient( "{$transient}{$user_id}", $wishlist_list_items, 1 * MONTH_IN_SECONDS );
				}
				
			}
			
			$wish_list_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
			$wish_list_permalink   =   get_permalink( $wish_list_page_id );
			
			
	
			if( !empty( $wishlist_page_id ) ){
				$myaccount_permalink   =   get_permalink( get_option('woocommerce_myaccount_page_id') );
				
				$structure = get_option( 'permalink_structure', '' );
				if( $structure == '' ){
					$wish_list_permalink  = untrailingslashit( $myaccount_permalink ) .'&' . $wishlist_page_id;
					
				} else {
					$wish_list_permalink  = untrailingslashit( $myaccount_permalink ) .'/' . $wishlist_page_id;
					
				}
			}
			
			$response = array('ok' => true, 'redirect_url' => $wish_list_permalink);
			wp_send_json_success( $response );
		}
		 
		 
		 /**
		 * Ajax method for save to new multiple wishlist.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		public static function save_multiple_wishlist() {
			$args = wp_parse_args( $_POST, array(
				'ignore_excluded_items' => false,
				'value' => array(),
			) );
			
			if( isset($args['value']) ) {
				$value = $args['value'];
			} else {
				$value = array();
			}
			
			$item_id = $args['item_id'];
			
			if ( is_user_logged_in() ) {
				$user    	= wp_get_current_user();
				$user_id 	= $user->ID;
			} else {
				$user_id   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}
			
			$res = Alg_WC_Wish_List_Item::remove_item_from_wish_list( $item_id, $user_id );
			
			if( is_array( $value ) && !empty( $value ) ) {
				$del_val = -99;
				if (($key = array_search($del_val, $value)) !== false) {
					unset($value[$key]);
					$res = Alg_WC_Wish_List_Item::add_item_to_wish_list( $item_id, $user_id );
				}
			}
			
			if ( is_int( $user_id ) && $user_id > 0 ) {
					
				// get only multiple wishlist items
				$arrange_arr =  get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, true );
				
			} else {
			
				$transient = Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE_STORE;
				$arrange_arr = get_transient( "{$transient}{$user_id}" );
			
			}
			
			if ( ! $arrange_arr ) {
				$arrange_arr = array();
			}
			
			if ( is_array( $arrange_arr ) && ! empty( $arrange_arr ) ) {
				foreach( $arrange_arr as $k => $arr ) {
					if ( is_array( $arr ) ) {
						if ( ( $key = array_search( $item_id, $arr ) ) !== false ) {
								unset( $arrange_arr[$k][$key] );
						}
					}
				}
			}
			
			if ( is_array( $value ) && ! empty( $value ) ) {
				foreach( $value as $val ) {
					if ( is_array( $arrange_arr[$val] ) ) {
						
						if ( ( $key = array_search( $item_id, $arrange_arr[$val] ) ) !== false ) {
							unset( $arrange_arr[$val][$key] );
						}
						
						if ( ! in_array( $item_id, $arrange_arr[$val] ) ) {
							$arrange_arr[$val][] = $item_id;
						}
					} else {
						$arrange_arr[$val][] = $item_id;
					}
				}
			}
			
			if ( is_int( $user_id ) && $user_id > 0 ) {
					
				// save only multiple wishlist items
				update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, $arrange_arr );
				
			} else {
				
				set_transient( "{$transient}{$user_id}", $arrange_arr, 1 * MONTH_IN_SECONDS );
			}
			
			
			$product = wc_get_product( $item_id );
			
			$all_ok  = true;
			$action  = 'added'; // 'added' | 'removed' | error | cant_toggle_unlogged
			$icon = false;
					
			$params = apply_filters( 'alg_wc_wl_toggle_item_texts', array(
				'added'                => __( '%s was successfully added to wishlist.', 'wish-list-for-woocommerce' ),
				'saved'                => __( 'Wishlist successfully saved.', 'wish-list-for-woocommerce' ),
				'removed'              => __( '%s was successfully removed from wishlist', 'wish-list-for-woocommerce' ),
				'see_wish_list'        => __( 'See your wishlist', 'wish-list-for-woocommerce' ),
				'error'                => apply_filters( 'alg_wc_wl_error_text', __( 'Sorry, Some error occurred. Please, try again later.', 'wish-list-for-woocommerce' ) ),
				'cant_toggle_unlogged' => sprintf( __( 'Please <a class=\'alg-wc-wl-link\' href="%s">login</a> if you want to use the Wishlist', 'wish-list-for-woocommerce' ), wc_get_page_permalink( 'myaccount' ) ),
			) );
			
			$wish_list_page_id         = Alg_WC_Wish_List_Page::get_wish_list_page_id();
			$wish_list_permalink       = get_permalink( $wish_list_page_id );
			$see_your_wishlist_message = $params['see_wish_list'];
			$saved_message             = sprintf(
				$params['saved'],
				''
			);
			
			$added_or_removed = 'removed';
			
			if ( Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user_id ) ) {
				$added_or_removed = 'added';
			}
			
			$message = "{$saved_message}<br /> <a class='alg-wc-wl-notification-link' href='{$wish_list_permalink}'>{$see_your_wishlist_message}</a>";
			$response = array(
				'ok'                   => true,
				'message'              => $message,
				'action'               => $action,
				'icon'                 => $icon,
				'added_or_removed'     => $added_or_removed
			);	
			
			wp_send_json_success( $response );
		}
		
		/**
		 * wishlist_multi_array_search
		 *
		 * @version 3.0.8
		 * @since   3.0.8
		 */
		public static function wishlist_multi_array_search($search_for, $search_in) {
			foreach ($search_in as $element) {
				if ( ($element === $search_for) || (is_array($element) && self::wishlist_multi_array_search($search_for, $element)) ){
					return true;
				}
			}
			return false;
		}
		
		/**
		 * Ajax method for get wish list shortcode.
		 *
		 * @version 1.2.8
		 * @since   1.2.8
		 */
		public static function get_wish_list_shortcode() {
			$response = array( 'shortcode' => do_shortcode( '[alg_wc_wl]' ) );
			wp_send_json_success( $response );
		}
		
		/**
		 * Adds variable product data to response text when an item is toggled
		 *
		 * @version 1.6.2
		 * @since   1.4.6
		 *
		 * @param $texts
		 *
		 * @return mixed
		 */
		public static function add_variable_product_data_to_response_text( $texts ) {
			if ( ! empty ( $_POST['attributes']['variation_id'] ) ) {
				$attributes_arr = array();
				foreach ( $_POST['attributes']['terms'] as $key => $value ) {
					$tax_sanitized    = str_replace( "attribute_", "", $key );
					$term             = get_term_by( 'slug', $value, $tax_sanitized );
					$attributes_arr[] = $term ? $term->name : $value;
				}
				$attributes     = implode( ', ', $attributes_arr );
				$texts['added'] = __( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_ADDED_TO_WISH_LIST ) ), 'wish-list-for-woocommerce' );
				$texts['added'] = preg_replace( '/\%s/', '%s - (' . $attributes . ')', $texts['added'] );
				$texts['removed'] = __( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_REMOVED_FROM_WISH_LIST ) ), 'wish-list-for-woocommerce' );
				$texts['removed'] = preg_replace( '/\%s/', '%s - (' . $attributes . ')', $texts['removed'] );
			}

			return $texts;
		}
		
		/**
		 * Ajax method for get from new multiple wishlist.
		 *
		 * @version 3.0.8
		 * @since   2.0.5
		 */
		public static function get_multiple_wishlist() {
			$args = wp_parse_args( $_POST, array(
				'ignore_excluded_items' => false,
			) );
			
			$item_id = $args['item_id'];
			
			if ( is_user_logged_in() ) {
				$user    	= wp_get_current_user();
				$user_id 	= $user->ID;
			} else {
				$user_id   	= Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}
			
			$wishlist_list = Alg_WC_Wish_List::get_multiple_wishlists( $user_id );
			
			if ( is_int( $user_id ) && $user_id > 0 ) {
					
				// get only multiple wishlist items
				$arrange_arr =  get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, true );
			} else {
			
				$transient_store = Alg_WC_Wish_List_Transients::WISH_LIST_MULTIPLE_STORE;
				$arrange_arr = get_transient( "{$transient_store}{$user_id}" );
			}
			if ( ! $arrange_arr ) {
				$arrange_arr = array();
			}
			
			$return_html = '';
			
			$checked_default = '';
			if ( Alg_WC_Wish_List_Item::is_item_in_wish_list( $item_id, $user_id ) ) {
				$checked_default = 'checked="checked"';
			}
			ob_start();
			?>
				<li>
					<div class="algwcwishlistmodal-checkbox-wrapper">
					<span class="titlebox"><?php echo esc_html( get_option( 'alg_wc_wl_texts_default_wishlist', __( 'Default Wishlist', 'wish-list-for-woocommerce' ) ) ); ?></span>
					  <label for="algwcwishlistmodal-cbk">
						<input type="checkbox" id="algwcwishlistmodal-cbk" class="whichlist-check" value="-99" <?php echo $checked_default; ?>>
						<span class="cbx">
						  <svg width="12px" height="11px" viewBox="0 0 12 11">
							<polyline points="1 6.29411765 4.5 10 11 1"></polyline>
						  </svg>
						</span>
					  </label>
					  <div class="float-clear"></div>
					</div>
				</li>
			<?php 
			if( is_array( $wishlist_list ) ) {
				foreach( $wishlist_list as $k => $list ) {
					$checked = '';
					if ( ! is_array( $arrange_arr[$k] ) || ( is_array( $arrange_arr[$k] ) && empty( $value[$k] ) ) ) {
						$haystack = ( ( isset($arrange_arr[$k]) && !empty($arrange_arr[$k]) ) ? $arrange_arr[$k] : array() );
						if ( in_array( $item_id, $haystack ) ) {
							$checked = 'checked="checked"';
						}
					}
				?>
					<li>
						<div class="algwcwishlistmodal-checkbox-wrapper">
						<span class="titlebox"><?php echo $list; ?></span>
						  <label for="algwcwishlistmodal-cbk<?php echo $k+1; ?>">
							<input type="checkbox" id="algwcwishlistmodal-cbk<?php echo $k+1; ?>" class="whichlist-check" value="<?php echo $k; ?>" <?php echo $checked; ?>>
							<span class="cbx">
							  <svg width="12px" height="11px" viewBox="0 0 12 11">
								<polyline points="1 6.29411765 4.5 10 11 1"></polyline>
							  </svg>
							</span>
						  </label>
						  <div class="float-clear"></div>
						</div>
					</li>
				<?php 
				}
			}
			$return_html = ob_get_contents();
			ob_end_clean();
			
			$response = array( 'wishlist_list' => ! is_array( $wishlist_list ) ? array() : $wishlist_list, 'list_html' => $return_html );
			
			wp_send_json_success( $response );
		}
		
		/**
		 * Ajax method for get from new multiple wishlist.
		 *
		 * @version 3.0.8
		 * @since   3.0.8
		 */
		public static function admin_clear_wishlist() {
			$args = wp_parse_args( $_GET, array(
				'user_id' => 0,
			) );
			if( $args['user_id'] > 0 ) {
				$user_id = $args['user_id'];
				delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM );
				delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS );
				delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE );
				delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS_MULTIPLE );
				delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE_NAME );
				delete_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_METAS_MULTIPLE_NAME );
			}
			$ref_url = add_query_arg('cleared', '1',  wp_get_referer());
			wp_safe_redirect( $ref_url );
			exit;
		}
		
		/**
		 * Ajax method for copy wishlist.
		 *
		 * @version 3.0.8
		 * @since   3.0.8
		 */
		public static function save_duplicate_wishlist() {
			
			$args = wp_parse_args( $_POST, array(
				'value_tab_id' => '',
				'value' => '',
			) );
			
			$value = $args['value'];
			$value_tab_id = $args['value_tab_id'];
			
			if( $value_tab_id !== '' ) {
				$value_tab_id = (int) $value_tab_id;
			} else {
				$value_tab_id = 'd';
			}
			
			$wishlisted_items = array();
			
			$user_id                   = get_current_user_id(); 
			$use_id_from_unlogged_user =  false;
			$ignore_excluded_items 	   = false;
			
			
			
			// save wishlist name 
			$wishlist_list = Alg_WC_Wish_List::get_multiple_wishlists( $user_id );
			if ( ! $wishlist_list ) {
				$wishlist_list = array();
			}
			array_push( $wishlist_list, $value );
			
			if( is_int( $user_id ) && $user_id > 0 ) {
				update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE_NAME, $wishlist_list );
			} 
			
			if( is_int( $value_tab_id ) ) {
				$wishlisted_items = Alg_WC_Wish_List::get_multiple_wishlist_items( $user_id, $use_id_from_unlogged_user, $ignore_excluded_items, $value_tab_id );
			} else if ( $value_tab_id == 'd' ) {
				$wishlisted_items = Alg_WC_Wish_List::get_wish_list( $user_id, $use_id_from_unlogged_user, $ignore_excluded_items );
			}
			
			if ( is_int( $user_id ) && $user_id > 0 ) {
				
				// get only multiple wishlist items
				$arrange_arr =  get_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, true );
				
				if( empty($arrange_arr) ) {
					$arrange_arr = array();
				}
				
				$arrange_arr[] = $wishlisted_items;
				// save only multiple wishlist items
				update_user_meta( $user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM_MULTIPLE, $arrange_arr );
				
			}
				
				
			$response = array('ok' => true);
			wp_send_json_success( $response );
		}

	}

}