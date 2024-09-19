<?php
/**
 * Wish List for WooCommerce Pro - Report
 *
 * @version 1.9.0
 * @since   1.6.7
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Report' ) ) {

	class Alg_WC_Wish_List_Report {

		/**
		 * added_item_amount_method_from_registered_users.
		 *
		 * @version 1.9.0
		 *
		 * @var null
		 */
		protected $wish_list_item_users_amount_method_from_registered = null;

		/**
		 * wish_list_item_users_amount_consider_guests.
		 *
		 * @since 2.0.2
		 *
		 * @var null
		 */
		protected $wish_list_item_users_amount_consider_guests = null;

		protected $trash_items = array();

		/**
		 * init.
		 *
		 * @version 1.9.0
		 * @since   1.9.0
		 *
		 */
		function init() {
			// Users Page.
			add_filter( 'manage_users_columns', array( $this, 'admin_users_columns_add' ) );
			add_filter( 'manage_users_custom_column', array( $this, 'admin_users_columns_setup' ), 10, 3 );
			add_action( 'pre_user_query', array( $this, 'admin_users_pre_user_query' ), 10);
			add_filter( 'manage_users_sortable_columns', array( $this, 'admin_users_sortable_columns' ) );
			// Products Page.
			add_filter( 'manage_edit-' . 'product' . '_columns', array( $this, 'admin_products_columns_add' ) );
			add_action( 'manage_' . 'product' . '_posts_custom_column', array( $this, 'admin_products_columns_setup' ), 30, 2 );
			add_action( 'posts_clauses', array( $this, 'admin_products_posts_clauses' ), 10, 2 );
			add_filter( 'manage_edit-product_sortable_columns', array( $this, 'admin_products_sortable_columns' ) );
			// CSS
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_style' ) );
		}

		/**
		 * get_added_item_amount_from_registered_users.
		 *
		 * @version 1.9.7
		 * @since   1.9.0
		 *
		 * @param null $args
		 *
		 * @return int
		 */
		function get_wish_list_item_users_amount_from_registered( $args = null ) {
			$args       = wp_parse_args( $args, array(
				'product_id' => '',
				'method'     => 'post_meta' // user_meta || post_meta
			) );
			$product_id = $args['product_id'];
			if ( 'user_meta' === $args['method'] ) {
				$results = $this->get_wish_list_item_users( array(
					'product_id' => $product_id
				) );
				return count( $results );
			} elseif ( 'post_meta' === $args['method'] ) {
				return (int) get_post_meta( $product_id, '_alg_wc_wl_added_by_registered_users_count', true );
			}
		}

		/**
		 * get_users_by_added_product_id.
		 *
		 * @version 1.9.7
		 * @since   1.9.7
		 *
		 * @param null $args
		 *
		 * @return array
		 */
		function get_wish_list_item_users( $args = null ) {
			$args       = wp_parse_args( $args, array(
				'meta_key'   => '_alg_wc_wl_item',
				'product_id' => '',
				'fields'     => array( 'ID' )
			) );
			$meta_key   = $args['meta_key'];
			$product_id = $args['product_id'];
			$fields     = $args['fields'];
			$user_query = new WP_User_Query( array(
				'meta_key'   => $meta_key,
				'meta_value' => $product_id,
				'fields'     => $fields
			) );
			return $user_query->get_results();
		}

		/**
		 * get_wish_list_item_users_amount.
		 *
		 * @version 2.0.2
		 * @since   1.9.0
		 *
		 * @param null $args
		 *
		 * @return int
		 */
		function get_wish_list_item_users_amount( $args = null ) {
			$args                    = wp_parse_args( $args, array(
				'product_id'              => '',
				'registered_users_method' => $this->get_wish_list_item_users_amount_method_from_registered(), // user_meta || post_meta
				'consider_guest_users'    => $this->wish_list_item_users_amount_consider_guests()
			) );
			$product_id              = $args['product_id'];
			$registered_users_method = $args['registered_users_method'];
			$consider_guests         = $args['consider_guest_users'];
			$registered_amount       = $this->get_wish_list_item_users_amount_from_registered( array(
				'product_id' => $product_id,
				'method'     => $registered_users_method
			) );
			$final_amount            = $registered_amount;
			if ( $consider_guests ) {
				$final_amount += $this->get_wish_list_item_users_amount_from_unregistered( array(
					'product_id' => $product_id,
				) );
			}
			return $final_amount;
		}

		/**
		 * wish_list_item_users_amount_consider_guests.
		 *
		 * @version 2.0.2
		 * @since   2.0.2
		 *
		 * @return bool|null
		 */
		function wish_list_item_users_amount_consider_guests() {
			if ( is_null( $this->wish_list_item_users_amount_consider_guests ) ) {
				$this->wish_list_item_users_amount_consider_guests = 'yes' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_PRODUCTS_UNLOGGED, 'no' );
			}
			return $this->wish_list_item_users_amount_consider_guests;
		}

		/**
		 * get_wish_list_item_users_amount_method_from_registered.
		 *
		 * @version 1.9.7
		 * @since   1.9.0
		 *
		 * @return null
		 */
		function get_wish_list_item_users_amount_method_from_registered() {
			if ( null === $this->wish_list_item_users_amount_method_from_registered ) {
				$this->wish_list_item_users_amount_method_from_registered = get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_PROD_EXPORT_COL_LOGGED_USERS_AMOUNT_METHOD, 'post_meta' );
			}
			return $this->wish_list_item_users_amount_method_from_registered;
		}

		/**
		 * get_added_item_amount_from_unregistered_users.
		 *
		 * @version 1.9.0
		 * @since   1.9.0
		 *
		 * @param null $args
		 *
		 * @return int
		 */
		function get_wish_list_item_users_amount_from_unregistered( $args = null ) {
			$args       = wp_parse_args( $args, array(
				'product_id' => '',
			) );
			$product_id = $args['product_id'];
			return (int) get_post_meta( $product_id, '_alg_wc_wl_added_by_unregistered_users_count', true );
		}

		/**
		 * admin_users_pre_user_query.
		 *
		 * @see https://usersinsights.com/wordpress-user-sql-query/
		 *
		 * @version 1.7.0
		 * @since   1.7.0
		 *
		 * @param $query
		 *
		 * @return mixed
		 */
		function admin_users_pre_user_query( $query ) {
			if (
				! is_admin() ||
				'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_USERS_PAGE, 'no' ) ||
				! isset( $_GET['orderby'] ) ||
				'wish_list_total' !== $_GET['orderby']
			) {
				return $query;
			}
			global $wpdb;
			$query->query_fields .= ', count(um.meta_value) AS wl_total';
			$query->query_from   .= " LEFT JOIN $wpdb->usermeta um ON $wpdb->users.ID = um.user_id and um.meta_key = '_alg_wc_wl_item'";
			$query->query_where  .= " GROUP BY $wpdb->users.ID";
			$order = isset( $_GET['order'] ) ? esc_sql($_GET['order']) : 'asc';
			$query->query_orderby = " ORDER BY wl_total $order";
		}

		/**
		 * admin_products_posts_clauses.
		 *
		 * @version 1.7.6
		 * @since   1.6.7
		 *
		 * @param $clauses
		 * @param $wp_query
		 *
		 * @return mixed
		 */
		function admin_products_posts_clauses( $clauses, $wp_query ) {
			if (
				! is_admin() ||
				'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_PRODUCTS_PAGE, 'no' ) ||
				! function_exists( 'get_current_screen' ) ||
				empty( $screen = get_current_screen() ) ||
				'edit-product' != $screen->id ||
				'product' != $screen->post_type ||
				! $wp_query->is_main_query() ||
				'product' !== $wp_query->get( 'post_type' )
			) {
				return $clauses;
			}
			global $wpdb;

			// Count unlogged users
			if ( 'yes' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_PRODUCTS_UNLOGGED, 'no' ) ) {
				$clauses['join'] .= " LEFT JOIN $wpdb->postmeta as pm ON pm.post_id = $wpdb->posts.ID AND pm.meta_key = '_alg_wc_wl_added_by_unregistered_users_count'";
				$new_column      = 'COUNT(um.meta_value) + IFNULL(pm.meta_value, 0)';
			} else {
				$new_column = 'COUNT(um.meta_value)';
			}

			$clauses['join']    .= " LEFT JOIN $wpdb->usermeta as um ON um.meta_key = '_alg_wc_wl_item' AND um.meta_value = $wpdb->posts.ID";
			$clauses['groupby'] = " $wpdb->posts.ID";
			$clauses['fields'] .= ", {$new_column} AS wl_total";

			if ( 'wish_list_total' == $wp_query->get( 'orderby' ) ) {
				$clauses['orderby'] = "{$new_column} + 0 " . $wp_query->get( 'order' ) . ', ' . $clauses['orderby'];
			}
			return $clauses;
		}

		/**
		 * product_sortable_columns.
		 *
		 * @version 1.6.7
		 * @since   1.6.7
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		function admin_products_sortable_columns( $columns ) {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_PRODUCTS_PAGE, 'no' ) ) {
				return $columns;
			}
			$columns['alg_wish_list'] = 'wish_list_total';
			return $columns;
		}

		/**
		 * product_sortable_columns.
		 *
		 * @version 1.7.0
		 * @since   1.7.0
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		function admin_users_sortable_columns( $columns ) {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_USERS_PAGE, 'no' ) ) {
				return $columns;
			}
			$columns['alg_wish_list'] = 'wish_list_total';
			return $columns;
		}

		/**
		 * enqueue_css_for_users_page_report.
		 *
		 * @version 1.6.7
		 * @since   1.6.7
		 */
		function enqueue_admin_style() {
			if (
				(
					'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_USERS_PAGE, 'no' ) &&
					'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_PRODUCTS_PAGE, 'no' )
				)
				||
				! function_exists( 'get_current_screen' ) ||
				empty( $screen = get_current_screen() ) ||
				( 'users' !== $screen->base && 'edit-product' != $screen->id )
			) {
				return;
			}

			?>
			<style>
				.column-alg_wish_list {
					width:88px;
					/*vertical-align: middle !important;*/
					/*text-align:center !important;*/
				}
			</style>
			<?php
		}

		/**
		 * setup_users_columns_for_users_page_report.
		 *
		 * @version 2.0.3
		 * @since   1.6.7
		 *
		 * @param $val
		 * @param $column_name
		 * @param $user_id
		 *
		 * @return string
		 */
		function admin_users_columns_setup( $val, $column_name, $user_id ) {
			switch ( $column_name ) {
				case 'alg_wish_list' :
					$items = get_user_meta( $user_id, '_alg_wc_wl_item', false );
					$excluded_items = get_posts( array(
						'post_type'      => 'product',
						'post_status'    => 'trash',
						'posts_per_page' => - 1,
						'post__in'       => $items,
						'fields'         => 'ids'
					) );
					if ( is_array( $excluded_items ) && ! empty( $excluded_items ) ) {
						$items = array_diff( $items, $excluded_items );
					}
					if ( ! empty( $items ) ) {
						return count( $items );
						//return do_shortcode( '[alg_wc_wl_icon link="false" counter="true" amount="' . count( $items ) . '"]' );
					} else {
						return '';
					}
				case 'alg_wish_list_clear_wishlist' :
					$request = 'admin-ajax.php?action=alg_wc_wl_clear_wish_list_admin&user_id=' . $user_id;
					$action_url = admin_url( $request );
					return '<a href="' . $action_url . '" class="button" value="Clear Wishlist">Clear Wishlist</a>';
				default:
			}
			return $val;
		}

		/**
		 * setup_products_columns.
		 *
		 * @version 2.0.7
		 * @since   1.6.8
		 *
		 * @param $val
		 * @param $column_name
		 *
		 * @return string
		 */
		function admin_products_columns_setup( $val, $product_id ) {
			switch ( $val ) {
				case 'alg_wish_list' :
					global $post;
					$prod = $post;
					if ( ! empty( $prod->wl_total ) ) {
						echo $prod->wl_total;
						//echo do_shortcode( '[alg_wc_wl_icon link="false" counter="true" amount="' . $prod->wl_total . '"]' );
					} else {
						return '';
					}
				default:
			}
			return $val;
		}

		/**
		 * add_users_columns_for_users_page_report.
		 *
		 * @see https://stackoverflow.com/a/3354804/1193038
		 *
		 * @version 1.6.7
		 * @since   1.6.7
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
		function admin_users_columns_add( $columns ) {
			
			if ( 'yes' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_USERS_PAGE, 'no' ) ) {
				$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) +
			           array( "alg_wish_list" => __( 'Wish List', 'wish-list-for-woocommerce' ) ) +
			           array_slice( $columns, count( $columns ) - 1, count( $columns ) - 1, true );
			}
			
			if ( 'yes' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_USERS_PAGE_CLEAR_WISHLIST, 'no' ) ) {
				$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) +
			           array( "alg_wish_list_clear_wishlist" => __( 'Clear Wish List', 'wish-list-for-woocommerce' ) ) +
			           array_slice( $columns, count( $columns ) - 1, count( $columns ) - 1, true );
			}
			
			return $columns;
		}

		/**
		 * add_products_columns.
		 *
		 * @version 1.6.8
		 * @since   1.6.8
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		function admin_products_columns_add( $columns ) {
			if ( 'no' === get_option( Alg_WC_Wish_List_Settings_Admin::OPTION_REPORT_WISHLIST_COL_PRODUCTS_PAGE, 'no' ) ) {
				return $columns;
			}
			$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) +
			           array( "alg_wish_list" => __( 'Wish List', 'wish-list-for-woocommerce' ) ) +
			           array_slice( $columns, count( $columns ) - 1, count( $columns ) - 1, true );
			return $columns;
		}
	}
}