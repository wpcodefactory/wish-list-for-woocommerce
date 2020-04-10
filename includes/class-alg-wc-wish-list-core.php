<?php
/**
 * Wish List for WooCommerce - Core Class
 *
 * @version 1.6.7
 * @since   1.0.0
 * @author  Thanks to IT
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
	public $version = '1.6.2';

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
	 * @version 1.2.8
	 * @since   1.0.0
	 */
	public static function on_install() {
		Alg_WC_Wish_List_Page::create_page();
	}

	/**
	 * Method called when the plugin is uninstalled
	 *
	 * @version 1.6.1
	 * @since   1.0.0
	 */
	public static function on_uninstall() {
		// Remove wish list page
		//Alg_WC_Wish_List_Page::delete_page();

		// Delete meta data
		//self::delete_meta_data();
	}

	/**
	 * Delete all plugin meta data
	 *
	 * Probably called when the plugin is uninstalled
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	public static function delete_meta_data(){
		global $wpdb;
		$meta_prefix = 'alg_wc_wl';

		// Remove user meta
		$wpdb->query(
			$wpdb->prepare(
				"
                DELETE FROM $wpdb->usermeta
		 		WHERE meta_key like '%%%s%%'
				",
				$meta_prefix
			)
		);

		// Remove options
		$wpdb->query(
			$wpdb->prepare(
				"
                DELETE FROM $wpdb->options
		 		WHERE option_name like '%%%s%%'
				",
				$meta_prefix
			)
		);
	}

	/**
	 * Constructor.
	 *
	 * @version 1.5.9
	 * @since   1.0.0
	 */
	function __construct() {

		// Set up localisation
		add_action( 'init', array( $this, 'handle_localization' ) );

		// Include required files
		if(is_admin()){
			$this->init_admin_fields();
		}

		if ( true === filter_var( get_option( 'alg_wc_wl_enabled', false ), FILTER_VALIDATE_BOOLEAN ) ) {
			// Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ), 11 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Manages wish list buttons
			$this->handle_buttons();

			// Handle cookies
			add_action( 'init', array( $this, "handle_cookies" ), 1 );

			// Saves wish list on register
			add_action( 'user_register', array( Alg_WC_Wish_List::get_class_name(), 'save_wish_list_on_register' ) );

			// Saves wish list on login
			add_action( 'wp_login', array( Alg_WC_Wish_List::get_class_name(), 'save_wish_list_on_login' ), 10, 2 );

			// Ajax
			$this->handle_ajax();

			// Manages Shortcodes
			$this->handle_shortcodes();

			// Manages custom actions
			$this->handle_custom_actions();

			// Manages query vars
			add_filter( 'query_vars', array( $this, 'handle_query_vars' ) );

			// Manages widgets
			add_action( 'widgets_init', array( $this, 'create_widgets' ) );

			// Email sharing
			if ( true === filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_EMAIL, true ), FILTER_VALIDATE_BOOLEAN ) ) {
				new Alg_WC_Wish_List_Email_Sharing();
			}

			// Setups wish list tab on my account page
			new Alg_WC_Wish_List_Tab();

			// Toggle wish list item by URL
			add_action( 'init', array( Alg_WC_Wish_List::get_class_name(), 'toggle_wishlist_item_by_url' ) );
			add_filter( 'alg_wc_wl_localize', array( Alg_WC_Wish_List::get_class_name(), 'show_wishlist_notification' ) );

			// Setup font awesome icons
			add_filter( 'alg_wc_wl_fa_icon_class', array( $this, 'get_font_awesome_icon_class' ), 9, 2 );
		}				
	}

	/**
	 * Initialize cookies.
	 *
	 * @version 1.1.5
	 * @since   1.1.5
	 */
	public function handle_cookies(){
		Alg_WC_Wish_List_Cookies::get_unlogged_user_id();
	}

	/**
	 * Create widgets.
	 *
	 * @version 1.1.4
	 * @since   1.1.4
	 */
	public function create_widgets() {
		register_widget( 'Alg_WC_Wish_List_Widget_Link' );
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
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	public function handle_social() {
		$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
		$queried_user_id           = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
		$queried_user_id           = empty( $queried_user_id ) ? $user_id_from_query_string : $queried_user_id;

		// Doesn't show if queried user id is the user itself
		if ( $queried_user_id && Alg_WC_Wish_List_Cookies::get_unlogged_user_id() != $queried_user_id ) {
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

			$link = wp_get_shortlink();
			if ( empty( $link ) ) {
				$link = trailingslashit( get_home_url() );
			}

			// Get current url with user id
			$url = add_query_arg( array_filter( array(
				'p'                                        => Alg_WC_Wish_List_Page::get_wish_list_page_id(),
				Alg_WC_Wish_List_Query_Vars::USER          => is_user_logged_in() ? Alg_WC_Wish_List_Query_Vars::crypt_user( get_current_user_id() ) : Alg_WC_Wish_List_Cookies::get_unlogged_user_id(),
				Alg_WC_Wish_List_Query_Vars::USER_UNLOGGED => is_user_logged_in() ? 0 : 1,
			) ), $link );

			// Title that will be passed on share links
			$title = get_the_title();

			$params = array(
				'share_txt' => __( 'Share', 'wish-list-for-woocommerce' ),				
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
						'title' => $title,
					), 'https://www.facebook.com/sharer/sharer.php' )
				),
				'google'   => array(
					'active' => filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_GOOGLE ), FILTER_VALIDATE_BOOLEAN ),
					'url'    => add_query_arg( array(
						'url' => urlencode( $url ),
					), 'https://plus.google.com/share' )
				),
				'copy'   => array(
					'active' => filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_COPY ), FILTER_VALIDATE_BOOLEAN,'no' ),
					'url'    => Alg_WC_Wish_List::get_url()
				),
			);
			echo alg_wc_wl_locate_template( 'share.php', $params );
		}
	}

	/**
	 * Handle Localization.
     *
	 * Tries to load from 'wp-content/languages/plugins/wish-list-for-woocommerce-pt_BR.mo' first.
	 * If it's not possible, tries to load from "wp-content/plugins/wish-list-for-woocommerce/languages/wish-list-for-woocommerce-pt_BR.mo'
	 *
	 * @version 1.6.1
	 * @since   1.0.0
	 */
	public function handle_localization() {
		$domain = 'wish-list-for-woocommerce';
		load_plugin_textdomain( $domain, false, dirname( ALG_WC_WL_BASENAME ) . '/languages/' );
	}

	/**
	 * Manages wish list buttons
	 *
	 * @version 1.5.5
	 * @since   1.0.0
	 */
	private function handle_buttons() {
		$show_default_btn_single_product = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_SINGLE_ENABLE, false );
		if ( filter_var( $show_default_btn_single_product, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			$default_btn_single_prod_position = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_SINGLE_POSITION, 'woocommerce_single_product_summary' );
			$default_btn_single_prod_priority = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_SINGLE_PRIORITY, 31 );
			add_action( sanitize_text_field( $default_btn_single_prod_position ), array(
				Alg_WC_Wish_List_Toggle_Btn::get_class_name(),
				'show_default_btn',
			), filter_var( $default_btn_single_prod_priority, FILTER_VALIDATE_INT ) );
		}

		$show_default_btn_loop_product = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_LOOP_ENABLE, false );
		if ( filter_var( $show_default_btn_loop_product, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			$default_btn_loop_prod_position = 'woocommerce_after_shop_loop_item';
			$default_btn_loop_prod_priority = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_LOOP_PRIORITY, 11 );
			add_action( $default_btn_loop_prod_position, array(
				Alg_WC_Wish_List_Toggle_Btn::get_class_name(),
				'show_default_btn',
			), filter_var( $default_btn_loop_prod_priority, FILTER_VALIDATE_INT ) );
		}

		$show_product_page_thumb_btn = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_SINGLE_ENABLE, true );
		if ( filter_var( $show_product_page_thumb_btn, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			add_action( 'woocommerce_product_thumbnails', array(
				Alg_WC_Wish_List_Toggle_Btn::get_class_name(),
				'show_thumb_btn'
			), 21 );
		}

		$show_loop_page_thumb_btn = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_LOOP_ENABLE, true );
		if ( filter_var( $show_loop_page_thumb_btn, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			$hook     = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_LOOP_POSITION, 'woocommerce_before_shop_loop_item' );
			$priority = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_LOOP_PRIORITY, 9 );
			add_action( esc_attr( $hook ), array(
				Alg_WC_Wish_List_Toggle_Btn::get_class_name(),
				'show_thumb_btn'
			), esc_attr( $priority ) );
		}
	}

	/**
	 * Manages Shortcodes
	 *
	 * @version 1.2.10
	 * @since   1.0.0
	 */
	private function handle_shortcodes() {
		add_shortcode( Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST, array( Alg_WC_Wish_List_Shortcodes::get_class_name(), 'sc_alg_wc_wl' ) );
		add_shortcode( Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST_COUNT, array( Alg_WC_Wish_List_Shortcodes::get_class_name(), 'sc_alg_wc_wl_counter' ) );
	}

	/**
	 * Handle Ajax requisitions
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function handle_ajax() {
		$toggle_wish_list_item_action = Alg_WC_Wish_List_Ajax::ACTION_TOGGLE_WISH_LIST_ITEM;
		add_action( "wp_ajax_nopriv_{$toggle_wish_list_item_action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item' ) );
		add_action( "wp_ajax_{$toggle_wish_list_item_action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item' ) );

		// Get wish list via ajax
		$action = Alg_WC_Wish_List_Ajax::ACTION_GET_WISH_LIST;
		add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_wish_list' ) );
		add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_wish_list' ) );
	}

	/**
	 * Localize scripts to load dynamic vars in JS
	 *
	 * @version 1.5.9
	 * @since   1.0.0
	 */
	function localize_scripts() {
		$ajax_url = get_option( Alg_WC_Wish_List_Settings_General::OPTION_ADMIN_AJAX_URL, admin_url( 'admin-ajax.php', 'relative' ) );
		if ( empty( $ajax_url ) ) {
			$ajax_url = admin_url( 'admin-ajax.php', 'relative' );
		}
		wp_localize_script( 'alg-wc-wish-list', 'alg_wc_wl',
			array(
				'ajaxurl'  => $ajax_url,
				'fa_icons' => array( 'copy' => apply_filters( 'alg_wc_wl_fa_icon_class', '', 'copy' ) )
			)
		);
		Alg_WC_Wish_List_Toggle_Btn::localize_script( 'alg-wc-wish-list' );
		Alg_WC_Wish_List_Ajax::localize_script( 'alg-wc-wish-list' );
		Alg_WC_Wish_List_Notification::localize_script( 'alg-wc-wish-list' );
	}

	/**
     * fix_fontawesome_url.
     *
	 * @version 1.6.7
	 * @since   1.6.7
     *
	 */
	function fix_fontawesome_url_option() {
		$css_file = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
		if ( 'https//use.fontawesome.com/releases/v5.5.0/css/all.css' === $css_file ) {
			update_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
		}
	}

	/**
	 * Load scripts and styles
	 *
	 * @version 1.6.7
	 * @since   1.0.0
	 */
	function enqueue_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Main css file
		$css_file = 'assets/css/alg-wc-wish-list'.$suffix.'.css';
		$css_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $css_file ) );
		wp_register_style( 'alg-wc-wish-list', ALG_WC_WL_URL . $css_file, array(), $css_ver );
		wp_enqueue_style( 'alg-wc-wish-list' );

		// Font awesome
		$this->fix_fontawesome_url_option();
		$css_file = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
		$font_awesome_opt = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME, 'yes' );
		if ( filter_var( $font_awesome_opt, FILTER_VALIDATE_BOOLEAN ) !== false ) {
			if ( ! wp_script_is( 'alg-font-awesome' ) ) {
				wp_register_style( 'alg-font-awesome', $css_file, array() );
				wp_enqueue_style( 'alg-font-awesome' );
			}
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

		// Main js file
		$js_file = 'assets/js/alg-wc-wish-list'.$suffix.'.js';
		$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
		wp_register_script( 'alg-wc-wish-list', ALG_WC_WL_URL . $js_file, array( 'jquery' ), $js_ver, true );
		wp_enqueue_script( 'alg-wc-wish-list' );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @version 1.2.6
	 * @since   1.0.0
	 */
	function enqueue_admin_scripts( $hook ) {		
		if ( $hook != 'woocommerce_page_wc-settings' || ! isset( $_GET['tab'] ) || $_GET['tab'] != 'alg_wc_wish_list' ) {
			return;
		}
		?>
           <style>           	
               /* Fixes select2 inputs*/
               .woocommerce table.form-table .select2-container {
                   vertical-align: middle !important;
               }
           </style>
		<?php
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
	 * @version 1.1.6
	 * @since   1.0.0
	 */
	function init_admin_fields() {
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		add_filter( 'plugin_action_links_' . ALG_WC_WL_BASENAME, array( $this, 'action_links' ) );

		new Alg_WC_Wish_List_Settings_General();
		new Alg_WC_Wish_List_Settings_Social();
		new Alg_WC_Wish_List_Settings_Buttons();
		new Alg_WC_Wish_List_Settings_List();
		new Alg_WC_Wish_List_Settings_Notification();		
		$this->create_custom_settings_fields();

		if ( is_admin() && get_option( 'alg_wish_list_version', '' ) !== $this->version ) {
			update_option( 'alg_wish_list_version', $this->version );
		}
	}

	/**
	 * get_font_awesome_icon_class.
	 *
	 * @version 1.6.2
	 * @since   1.5.9
	 *
	 * @param $icon
	 *
	 * @return string
	 */
	public function get_font_awesome_icon_class( $class, $icon ) {
		switch ( $icon ) {
			case 'facebook':
				$class = 'fab fa-facebook-square';
				break;
			case 'twitter':
				$class = 'fab fa-twitter-square';
				break;
			case 'google_plus':
				$class = 'fab fa-google-plus-square';
				break;
			case 'email':
				$class = 'fas fa-envelope-square';
				break;
			case 'copy':
				$class = 'fas fa-copy';
				break;
		}
        return $class;
	}

	/**
	 * Create custom settings fields
	 *
	 * @version 1.1.2
	 * @since   1.1.2
	 */
	public function create_custom_settings_fields(){
	    WCCSO_Metabox::get_instance();
	}

	/**
	 * Add Wish List settings tab to WooCommerce settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = new Alg_WC_Wish_List_Settings();
		return $settings;
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

}

endif;