<?php
/**
 * Wish List Tab
 *
 * @version 1.2.8
 * @since   1.2.8
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Tab' ) ) {

	class Alg_WC_Wish_List_Tab {

		/**
		 * Custom endpoint name.
		 *
		 * @var string
		 */
		public static $endpoint = 'my-wish-list';

		/**
		 * Plugin actions.
		 */
		public function __construct() {


			// Actions used to insert a new endpoint in the WordPress.
			add_action( 'init', array( $this, 'add_endpoints' ) );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

			// Change the My Accout page title.
			add_filter( 'the_title', array( $this, 'endpoint_title' ) );

			// Insering your new tab/page into the My Account page.
			add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
			add_action( 'woocommerce_account_' . self::$endpoint . '_endpoint', array( $this, 'endpoint_content' ) );
		}

		/**
		 * Register new endpoint to use inside My Account page.
		 *
		 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
		 */
		public function add_endpoints() {
			if ( ! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB ), FILTER_VALIDATE_BOOLEAN ) ) {
				return;
			}

			$this->setup_endpoint();

			add_rewrite_endpoint( self::$endpoint, EP_ROOT | EP_PAGES );
		}

		public function setup_endpoint(){
			self::$endpoint = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB_SLUG, self::$endpoint ) );
		}

		/**
		 * Add new query var.
		 *
		 * @param array $vars
		 *
		 * @return array
		 */
		public function add_query_vars( $vars ) {
			if ( ! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB ), FILTER_VALIDATE_BOOLEAN ) ) {
				return $vars;
			}

			$this->setup_endpoint();

			$vars[] = self::$endpoint;
			return $vars;
		}

		/**
		 * Set endpoint title.
		 *
		 * @param string $title
		 *
		 * @return string
		 */
		public function endpoint_title( $title ) {
			if ( ! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB ), FILTER_VALIDATE_BOOLEAN ) ) {
				return $title;
			}

			$this->setup_endpoint();
			$label = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB_LABEL ) );

			global $wp_query;
			$is_endpoint = isset( $wp_query->query_vars[ self::$endpoint ] );
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = $label;
				remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
			}
			return $title;
		}

		/**
		 * Insert the new endpoint into the My Account menu.
		 *
		 * @param array $items
		 *
		 * @return array
		 */
		public function new_menu_items( $items ) {
			if ( ! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB ), FILTER_VALIDATE_BOOLEAN ) ) {
				return $items;
			}

			$this->setup_endpoint();
			$label = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB_LABEL ) );

			// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );

			// Insert your custom endpoint.
			$items[ self::$endpoint ] = $label;

			// Insert back the logout item.
			$items['customer-logout'] = $logout;
			return $items;
		}

		/**
		 * Endpoint HTML content.
		 */
		public function endpoint_content() {
			if ( ! filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_TAB ), FILTER_VALIDATE_BOOLEAN ) ) {
				return;
			}
			echo do_shortcode( '[alg_wc_wl]' );
		}
	}
}