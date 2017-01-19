<?php
/**
 * Wish List for WooCommerce - Core Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Core' ) ) :

final class Alg_WC_Wish_List_Core {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.0.0-dev-201701162033';

	/**
	 * @var   Alg_WC_Wish_List The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Wish_List Instance
	 *
	 * Ensures only one instance of Alg_WC_Wish_List is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Wish_List - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Method called when the plugin is activated
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function on_install() {
		Alg_WC_Wish_List_Page::create_page();
	}

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {


		// Set up localisation
		$this->handle_localization();

		// Include required files
		$this->init_admin_fields();

		if ( true === filter_var( get_option( 'alg_wc_wl_enabled', false ), FILTER_VALIDATE_BOOLEAN ) ) {
			// Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ), 11 );

			// Manages wish list buttons
			$this->handle_buttons();

			// Start session if necessary
			add_action( 'init', array( $this, "handle_session" ) );

			// Save wishlist from unregistered user to database when this user registers
			add_action( 'user_register', array( Alg_WC_Wish_List::get_class_name(), 'save_wish_list_from_unregistered_user' ) );

			// Ajax
			$this->handle_ajax();

			// Manages Shortcodes
			$this->handle_shortcodes();

			// Manages custom actions
			$this->handle_custom_actions();

			// Manages query vars
			add_filter( 'query_vars', array( $this, 'handle_query_vars' ) );
		}
	}

	/**
	 * Manages query vars
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function handle_query_vars($vars){
		$vars[] = Alg_WC_Wish_List_Query_Vars::USER;
		$vars[] = Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED;
		return $vars;
	}

	/**
	 * Manages custom actions
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private function handle_custom_actions() {

		// Wish list table actions
		add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, array( $this, 'handle_social' ) );
		add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array( $this, 'handle_social' ) );
	}

	/**
	 * Load social networks template
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function handle_social() {
		// Doesn't show if queried user id is the user itself
		$queried_user_id = get_query_var( Alg_WC_Wish_List_Query_Vars::USER, null );
		if ( $queried_user_id && Alg_WC_Wish_List_Session::get_current_unlogged_user_id() != $queried_user_id ) {
			return;
		}

		// Check if user enabled social networks on admin
		$social_is_active = filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_ENABLE, true ), FILTER_VALIDATE_BOOLEAN );
		if ( ! $social_is_active ) {
			return;
		}

		// Possible positions to show social buttons
		$before = Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE;
		$after  = Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER;

		// Positions where the user selected to show network buttons
		$positions = get_option( Alg_WC_Wish_List_Settings_Social::OPTION_SHARE_POSITION );
		if ( ! is_array( $positions ) ) {
			return;
		}

		if (
			current_filter() == $before && array_search( $before, $positions ) !== false ||
			current_filter() == $after && array_search( $after, $positions ) !== false
		) {

			// Get current url with user id
			$url = add_query_arg( array(
				Alg_WC_Wish_List_Query_Vars::USER          => is_user_logged_in() ? get_current_user_id() : Alg_WC_Wish_List_Session::get_current_unlogged_user_id(),
				Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED => is_user_logged_in() ? 0 : 1,
			), wp_get_shortlink() );

			// Title that will be passed on share links
			$title = get_the_title();

			$params = array(
				'twitter'  => array(
					'active' => filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_TWITTER ), FILTER_VALIDATE_BOOLEAN ),
					'url'    => add_query_arg( array(
						'url'  => urlencode( $url ),
						'text' => $title,
					), 'https://twitter.com/intent/tweet' )
				),
				'facebook' => array(
					'active' => filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_FACEBOOK ), FILTER_VALIDATE_BOOLEAN ),
					'url'    => add_query_arg( array(
						'u' => urlencode( $url ),
						't' => $title,
					), 'https://www.facebook.com/sharer/sharer.php' )
				),
				'google'   => array(
					'active' => filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_GOOGLE ), FILTER_VALIDATE_BOOLEAN ),
					'url'    => add_query_arg( array(
						'url' => urlencode( $url ),
					), 'https://plus.google.com/share' )
				)
			);
			echo alg_wc_wl_locate_template( 'social-networks.php', $params );
		}
	}

	/**
	 * Handle Localization
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public function handle_localization(){
		$locale = apply_filters( 'plugin_locale', get_locale(), ALG_WC_WL_DOMAIN);
		load_textdomain(ALG_WC_WL_DOMAIN, WP_LANG_DIR.dirname( ALG_WC_WL_BASENAME ).ALG_WC_WL_DOMAIN.'-'.$locale.'.mo');
		load_plugin_textdomain( ALG_WC_WL_DOMAIN, false, dirname( ALG_WC_WL_BASENAME ) . '/languages/' );
	}

	/**
	 * Manages wish list buttons
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private function handle_buttons(){
		$show_product_page_btn = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_ENABLE_PRODUCT_PAGE_BTN,false );
		if ( filter_var( $show_product_page_btn, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			$product_page_position = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_ENABLE_PRODUCT_PAGE_POSITION,'woocommerce_single_product_summary' );
			$product_page_priority = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_ENABLE_PRODUCT_PAGE_PRIORITY,31 );
			add_action( sanitize_text_field($product_page_position), array( Alg_WC_Wish_List_Toggle_Btn::get_class_name(), 'show_toggle_btn' ), filter_var( $product_page_priority, FILTER_VALIDATE_INT) );
		}

		$show_product_page_thumb_btn = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_ENABLE_PRODUCT_PAGE_THUMB_BUTTON,true );
		if ( filter_var( $show_product_page_thumb_btn, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			add_action('woocommerce_product_thumbnails',array(Alg_WC_Wish_List_Toggle_Btn::get_class_name(), 'show_toggle_simple_btn'),21);
		}

		$show_loop_page_thumb_btn = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_ENABLE_LOOP_PAGE_THUMB_BUTTON,true );
		if ( filter_var( $show_loop_page_thumb_btn, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			add_action('woocommerce_before_shop_loop_item',array(Alg_WC_Wish_List_Toggle_Btn::get_class_name(), 'show_toggle_simple_btn'),9);
		}
	}

	/**
	 * Manages Shortcodes
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	private function handle_shortcodes() {
		add_shortcode( 'alg_wc_wl', array( Alg_WC_Wish_List_Shortcodes::get_class_name(), 'sc_alg_wc_wl' ) );
	}

	/**
	 * Start session if necessary
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function handle_session() {
		if ( ! is_user_logged_in() ) {
			if ( ! session_id() )
				session_start();
		}
	}

	/**
	 * Handle Ajax requisitions
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function handle_ajax() {
		$toggle_wish_list_item_action = Alg_WC_Wish_List_Ajax::ACTION_TOGGLE_WISH_LIST_ITEM;
		add_action( "wp_ajax_nopriv_{$toggle_wish_list_item_action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item' ) );
		add_action( "wp_ajax_{$toggle_wish_list_item_action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item' ) );
	}

	/**
	 * Localize scripts for loading dynamic vars in JS
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function localize_scripts() {
		wp_localize_script( 'alg-wc-wish-list', 'alg_wc_wl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		Alg_WC_Wish_List_Toggle_Btn::localize_script( 'alg-wc-wish-list' );
		Alg_WC_Wish_List_Ajax::localize_script( 'alg-wc-wish-list' );
	}

	/**
	 * Load scripts and styles
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Main js file
		$js_file = 'assets/js/alg-wc-wish-list'.$suffix.'.js';
		$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
		wp_register_script( 'alg-wc-wish-list', ALG_WC_WL_URL . $js_file, array( 'jquery' ), $js_ver, true );
		wp_enqueue_script( 'alg-wc-wish-list' );

		// Main css file
		$css_file = 'assets/css/alg-wc-wish-list'.$suffix.'.css';
		$css_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $css_file ) );
		wp_register_style( 'alg-wc-wish-list', ALG_WC_WL_URL . $css_file, array(), $css_ver );
		wp_enqueue_style( 'alg-wc-wish-list' );

		// Font awesome
		$css_file = 'http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css';
		$font_awesome_opt = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME,true );
		if ( filter_var( $font_awesome_opt, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			wp_register_style( 'alg-wc-wish-list-font-awesome', $css_file, array() );
			wp_enqueue_style( 'alg-wc-wish-list-font-awesome' );
		}

		// Izitoast - A Notification plugin (http://izitoast.marcelodolce.com/)
		$js_file = 'assets/vendor/izitoast/js/iziToast.min.js';
		$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
		wp_register_script( 'alg-wc-wish-list-izitoast', ALG_WC_WL_URL . $js_file, array( 'jquery' ), $js_ver, true );
		wp_enqueue_script( 'alg-wc-wish-list-izitoast' );
		$css_file = 'assets/vendor/izitoast/css/iziToast.min.css';
		$css_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $css_file ) );
		wp_register_style( 'alg-wc-wish-list-izitoast', ALG_WC_WL_URL . $css_file, array(), $css_ver );
		wp_enqueue_style( 'alg-wc-wish-list-izitoast' );
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_wish_list' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>' );
		return array_merge( $custom_links, $links );
	}

	/**
	 * Init admin fields
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function init_admin_fields() {
		if(is_admin()){
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . ALG_WC_WL_BASENAME, array( $this, 'action_links' ) );
		}

		$settings = new Alg_WC_Wish_List_Settings_General();
		$settings->get_settings();
		$settings->handle_autoload();

		$settings = new Alg_WC_Wish_List_Settings_Social();
		$settings->get_settings();
		$settings->handle_autoload();

		$settings = new Alg_WC_Wish_List_Settings_Buttons();
		$settings->get_settings();
		$settings->handle_autoload();

		if ( is_admin() && get_option( 'alg_wish_list_version', '' ) !== $this->version ) {
			update_option( 'alg_wish_list_version', $this->version );
		}
	}

	/**
	 * Add Wish List settings tab to WooCommerce settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = new Alg_WC_Settings_Wish_List();
		return $settings;
	}

}

endif;