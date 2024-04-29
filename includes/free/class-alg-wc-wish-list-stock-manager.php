<?php
/**
 * Wish List for WooCommerce - Stock Manager.
 *
 * @version 2.0.5
 * @since   1.3.2
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Stock_Manager' ) ) {

	class Alg_WC_Wish_List_Stock_Manager {

		public $option_stock_alert = 'alg_wc_wl_stock_alert';

		/**
		 * init.
		 *
		 * @version 2.1.7
		 * @since   2.0.1
		 */
		function init() {
			add_filter( 'woocommerce_email_classes', array( $this, 'add_stock_email' ) );
			add_action( 'update_postmeta', array( $this, 'trigger_wc_product_stock_change' ), 10, 4 );
			add_action( 'init', array( $this, 'save_stock_alert_infs' ) );
			add_action( 'alg_wcwl_wc_product_stock_change_instock', array( $this, 'notify_users' ) );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array(
				$this,
				'add_stock_alert_on_template'
			) );
		}

		/**
		 * Trigger an action when a product changes its stock
		 *
		 * "alg_wcwl_wc_product_stock_change" when product stock changes
		 * "alg_wcwl_wc_product_stock_change_instock" when product is in stock
		 * "alg_wcwl_wc_product_stock_change_outofstock" when product gets out of stock
		 *
		 * @version 2.1.7
		 * @since   1.3.2
		 *
		 * @param $meta_id
		 * @param $object_id
		 * @param $meta_key
		 * @param $new_meta_value
		 */
		public function trigger_wc_product_stock_change( $meta_id, $object_id, $meta_key, $new_meta_value ) {
			if (
				$meta_key != '_stock_status' ||
				! in_array( get_post_type( $object_id ), array(
					'product_variation',
					'product'
				) ) ||
				! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK_ALERT, true ), FILTER_VALIDATE_BOOLEAN ) ||
				( ! empty( $stock_settings = get_option( 'woocommerce_alg_wc_wl_stock_settings', '' ) ) && 'yes' !== $stock_settings['enabled'] )
			) {
				return;
			}
			$old_meta_value = get_post_meta( $object_id, $meta_key, true );
			if ( $new_meta_value != $old_meta_value ) {
				do_action( "alg_wcwl_wc_product_stock_change", $new_meta_value, $object_id, $meta_key );
				do_action( "alg_wcwl_wc_product_stock_change_{$new_meta_value}", $object_id, $meta_key );
			}
		}

		/**
		 * Saves stock alert info in database.
		 *
		 * @version 1.3.2
		 * @since   1.3.2
		 *
		 * @param   null  $args
		 *
		 * @return bool
		 * @throws Exception
		 */
		public function save_stock_alert_infs( $args = null ) {
			$args = wp_parse_args( $args = array(
				'email'  => isset( $args['email'] ) ? $args['email'] : null,
				'enable' => isset( $args['enable'] ) ? $args['enable'] : null,
			) );

			if ( $args['email'] == null ) {
				$args['email'] = isset( $_REQUEST['alg_wcwl_user_stock_alert_email'] ) ? $_REQUEST['alg_wcwl_user_stock_alert_email'] : null;
			}

			if ( $args['enable'] == null ) {
				if ( ! isset( $_REQUEST['alg_wcwl_user_stock_alert_form'] ) ) {
					return false;
				}
				$args['enable'] = isset( $_REQUEST['alg_wcwl_user_stock_alert'] ) ? $_REQUEST['alg_wcwl_user_stock_alert'] : false;
			}

			$args['enable'] = filter_var( $args['enable'], FILTER_VALIDATE_BOOLEAN );

			if ( ! filter_var( filter_var( $args['email'], FILTER_SANITIZE_EMAIL ), FILTER_VALIDATE_EMAIL ) ) {
				return false;
			}

			$stock_alert_opt = get_option( $this->option_stock_alert, array() );

			if ( is_user_logged_in() ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			} else {
				$user_id = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}

			$stock_alert_opt[ $user_id ] = array(
				'is_registered' => is_user_logged_in(),
				'email'         => $args['email'],
				'enable'        => $args['enable']
			);

			update_option( $this->option_stock_alert, $stock_alert_opt );
		}

		/**
		 * Add a custom stock email to the list of emails WooCommerce should load
		 *
		 * @version 1.6.3
		 * @since   1.3.2
		 *
		 * @param $email_classes
		 *
		 * @return mixed
		 */
		public function add_stock_email( $email_classes ) {
			if ( 'yes' !== get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK_ALERT, 'yes' ) ) {
				return $email_classes;
			}
			$email_classes['Alg_WC_Wish_List_Stock_Email'] = new Alg_WC_Wish_List_Stock_Email();

			return $email_classes;
		}

		/**
		 * get_guest_users_to_notify.
		 *
		 * @version 2.0.7
		 * @since   1.3.2
		 *
		 * @param $product_id
		 *
		 * @return array
		 * @throws Exception
		 */
		public function get_guest_users_to_notify( $product_id ) {
			$stock_alert_opt = get_option( $this->option_stock_alert, array() );
			$users           = array();
			foreach ( $stock_alert_opt as $user_id => $user ) {
				if (
					$user['is_registered'] ||
					! filter_var( $user['enable'], FILTER_VALIDATE_BOOLEAN )
				) {
					continue;
				}

				$wish_list = Alg_WC_Wish_List::get_wish_list( $user_id, true );
				if (
					empty( $wish_list ) ||
					array_search( $product_id, $wish_list ) === false
				) {
					continue;
				}

				$email   = $user['email'];
				$users[] = array( 'email' => $email, 'user_id' => $user_id, 'is_registered' => false );
			}

			return $users;
		}

		/**
		 * get_registered_users_to_notify.
		 *
		 * @version 2.0.1
		 * @since   1.3.2
		 *
		 * @param $product_id
		 *
		 * @return array
		 */
		public function get_registered_users_to_notify( $product_id ) {
			$user_query = new WP_User_Query( array(
				'meta_key'   => Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM,
				'meta_value' => $product_id,
			) );

			$stock_alert_opt = get_option( $this->option_stock_alert, array() );

			$users = array();
			if ( ! empty( $user_query->results ) ) {
				foreach ( $user_query->results as $user ) {
					$email  = '';
					$enable = true;
					if ( array_key_exists( $user->ID, $stock_alert_opt ) ) {
						$email  = $stock_alert_opt[ $user->ID ]['email'];
						$enable = $stock_alert_opt[ $user->ID ]['enable'];
					} else {
						$email = $user->user_email;
					}
					if ( $enable ) {
						$users[] = array( 'email' => $email, 'user_id' => $user->ID, 'is_registered' => true );
					}
				}
			}

			return $users;
		}

		/**
		 * Join in stock products to subscribed customers
		 * @version 2.1.7
		 *
		 * @param $product_id
		 *
		 * @return bool
		 * @throws Exception
		 */
		public function notify_users( $product_id ) {
			$stock_alert_admin_opt = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK_ALERT, 'yes' ), FILTER_VALIDATE_BOOLEAN );
			if ( ! $stock_alert_admin_opt ) {
				return false;
			}
			$registered_users   = $this->get_registered_users_to_notify( $product_id );
			$unregistered_users = $this->get_guest_users_to_notify( $product_id );
			$unique_items       = array_unique( wp_list_pluck( array_merge( $registered_users, $unregistered_users ), 'email' ) );
			$this->push_email_info_to_bkg_process_queue( $product_id, array_intersect_key( array_merge( $registered_users, $unregistered_users ), $unique_items ) );
		}

		/**
		 * Pushes email info to background process queue.
		 *
		 * @version 1.9.4
		 * @since   1.3.2
		 *
		 * @param $product_id
		 * @param $users
		 *
		 */
		public function push_email_info_to_bkg_process_queue( $product_id, $users ) {
			$bkg_process = Alg_WC_Wish_List_Core::$bkg_process;
			foreach ( $users as $user ) {
				$bkg_process->push_to_queue( array(
					'product_id'      => $product_id,
					'email'           => $user['email'],
					'user_id'         => $user['user_id'],
					'user_registered' => $user['is_registered']
				) );
			}
			$bkg_process->save()->dispatch();
		}

		/**
		 * Adds stock alert on wish list template.
		 *
		 * @version 2.0.5
		 * @since   1.3.2
		 */
		public function add_stock_alert_on_template() {
			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
			$queried_user_id           = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
			$queried_user_id           = empty( $queried_user_id ) ? $user_id_from_query_string : $queried_user_id;

			// Doesn't show if queried user id is the user itself
			if ( $queried_user_id && Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id() != $queried_user_id ) {
				return;
			}

			$args                        = array();
			$stock_alert                 = filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_STOCK_ALERT, true ), FILTER_VALIDATE_BOOLEAN );
			$args['stock_alert']         = $stock_alert;
			$args['stock_alert_enabled'] = $stock_alert;
			$args['stock_alert_email']   = '';
			$params                      = get_query_var( 'params' );
			$is_email                    = isset( $params['is_email'] ) ? $params['is_email'] : false;

			if (
				! $stock_alert ||
				$is_email
			) {
				return;
			}

			$stock_alert_opt = get_option( $this->option_stock_alert, array() );
			if ( is_user_logged_in() ) {
				$user    = wp_get_current_user();
				$user_id = $user->ID;
			} else {
				$user_id = Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id();
			}

			if ( array_key_exists( $user_id, $stock_alert_opt ) ) {
				$args['stock_alert_enabled'] = filter_var( $stock_alert_opt[ $user_id ]['enable'], FILTER_VALIDATE_BOOLEAN );
				$args['stock_alert_email']   = filter_var( $stock_alert_opt[ $user_id ]['email'], FILTER_SANITIZE_EMAIL );
			} else {
				if ( is_user_logged_in() ) {
					$args['stock_alert_email'] = filter_var( $user->user_email, FILTER_SANITIZE_EMAIL );
				}
			}

			/*wc_get_template( 'alg_wcwl_stock_alert.php', $args );*/
			alg_wc_wl_locate_template( 'alg_wcwl_stock_alert.php', $args );
			
		}


	}
}