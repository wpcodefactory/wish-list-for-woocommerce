<?php

if ( ! class_exists( 'Alg_WC_Wish_List' ) ) {

	/**
	 * Main Alg_WC_Wish_List Class
	 *
	 * @class   Alg_WC_Wish_List
	 * @since   1.0.0
	 * @version 1.0.0
	 */

	final class Alg_WC_Wish_List {

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '1.0.0-dev-201701052220';

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
		 * Alg_WC_Wish_List Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @access  public
		 */
		function __construct() {

			// Set up localisation
			load_plugin_textdomain(ALG_WC_WL_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/langs/');

			// Include required files
			$this->includes();

			// Settings & Scripts
			if (is_admin()) {
				add_filter('woocommerce_get_settings_pages', array($this, 'add_woocommerce_settings_tab'));
				add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'action_links'));
			} else {
				Alg_WC_Wish_List_Toggle_Btn::init();
				add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
				add_action('wp_enqueue_scripts', array($this, 'localize_scripts'), 11);
				add_action('woocommerce_single_product_summary', array(Alg_WC_Wish_List_Toggle_Btn::get_class_name(), 'show_toggle_btn'), 31);
			}

			//Start session if necessary
			add_action('init', array($this, "handle_session"));

			//Save wishlist from unregistered user to database when this user registers
			add_action('user_register', array($this, 'save_wish_list_from_unregistered_user'));

			//Ajax
			$this->handle_ajax();

			//Manages Shortcodes
			$this->handle_shortcodes();
		}

		/**
		 * Manages Shortcodes
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_shortcodes(){
			add_shortcode( 'bartag', array(Alg_WC_Wish_List_Shortcodes::get_class_name(),'sc_alg_wc_wl') );
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
		 * Save wishlist from unregistered user to database when this user registers
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param type $user
		 * @return type
		 */
		public function save_wish_list_from_unregistered_user($user_id) {
			$wishlisted_items = self::get_wish_list(null);
			if (is_array($wishlisted_items) && count($wishlisted_items) > 0) {
				foreach ($wishlisted_items as $key => $item_id) {
					Alg_WC_Wish_List_Item::add_item_to_wish_list($item_id, $user_id);
				}
			}

			return $user_id;
		}

		/**
		 * Start session if necessary
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function handle_session() {
			if (!is_user_logged_in()) {
				if (!session_id())
					session_start();
			}
		}

		/**
		 * Get user wishlist
		 * @param type $user_id
		 * @return type
		 */
		public static function get_wish_list($user_id = null) {
			if ($user_id) {
				$wishlisted_items = get_user_meta($user_id, Alg_WC_Wish_List_User_Metas::WISH_LIST_ITEM, false);
			} else {
				if (!isset($_SESSION[Alg_WC_Wish_List_Session_Vars::WISH_LIST])) {
					$wishlisted_items = null;
				} else {
					$wishlisted_items = $_SESSION[Alg_WC_Wish_List_Session_Vars::WISH_LIST];
				}
			}
			return $wishlisted_items;
		}

		/**
		 * Handle Ajax requisitions
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function handle_ajax() {
			$toggle_wish_list_item_action = Alg_WC_Wish_List_Ajax::ACTION_TOGGLE_WISH_LIST_ITEM;
			add_action("wp_ajax_nopriv_{$toggle_wish_list_item_action}", array(Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item'));
			add_action("wp_ajax_{$toggle_wish_list_item_action}", array(Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item'));
		}

		/**
		 * Localize scripts for loading dynamic vars in JS
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function localize_scripts() {
			wp_localize_script('alg-wc-wish-list', 'alg_wc_wl', array('ajaxurl' => admin_url('admin-ajax.php')));
			Alg_WC_Wish_List_Toggle_Btn::localize_script('alg-wc-wish-list');
			Alg_WC_Wish_List_Ajax::localize_script('alg-wc-wish-list');
		}

		/**
		 * Load scripts and styles
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function enqueue_scripts() {
			//Main js file
			$js_file = 'assets/js/alg-wc-wish-list.js';
			$js_ver	 = date("ymd-Gis", filemtime(ALG_WC_WL_DIR . $js_file));
			wp_register_script('alg-wc-wish-list', ALG_WC_WL_URL . $js_file, array('jquery'), $js_ver, true);
			wp_enqueue_script('alg-wc-wish-list');

			//Main css file
			$css_file	 = 'assets/css/alg-wc-wish-list.css';
			$css_ver	 = date("ymd-Gis", filemtime(ALG_WC_WL_DIR . $css_file));
			wp_register_style('alg-wc-wish-list', ALG_WC_WL_URL . $css_file, array(), $css_ver);
			wp_enqueue_style('alg-wc-wish-list');

			//Font awesome
			$css_file			 = 'http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css';
			$font_awesome_opt	 = get_option(Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME);
			if (filter_var($font_awesome_opt, FILTER_VALIDATE_BOOLEAN) !== false) {
				wp_register_style('alg-wc-wish-list-font-awesome', $css_file, array());
				wp_enqueue_style('alg-wc-wish-list-font-awesome');
			}

			//Izitoast - A Notification plugin (http://izitoast.marcelodolce.com/)
			$js_file = 'assets/vendor/izitoast/js/iziToast.min.js';
			$js_ver	 = date("ymd-Gis", filemtime(ALG_WC_WL_DIR . $js_file));
			wp_register_script('alg-wc-wish-list-izitoast', ALG_WC_WL_URL . $js_file, array('jquery'), $js_ver, true);
			wp_enqueue_script('alg-wc-wish-list-izitoast');
			$css_file	 = 'assets/vendor/izitoast/css/iziToast.min.css';
			$css_ver	 = date("ymd-Gis", filemtime(ALG_WC_WL_DIR . $css_file));
			wp_register_style('alg-wc-wish-list-izitoast', ALG_WC_WL_URL . $css_file, array(), $css_ver);
			wp_enqueue_style('alg-wc-wish-list-izitoast');
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
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function includes() {
			$settings = new Alg_WC_Wish_List_Settings_General();
			$settings->get_settings();
			$settings->handle_autoload();

			$settings = new Alg_WC_Wish_List_Settings_Social();
			$settings->get_settings();
			$settings->handle_autoload();

			if ( is_admin() && get_option( 'alg_wish_list_version', '' ) !== $this->version ) {
				update_option( 'alg_wish_list_version', $this->version );
			}
			// Core
			new Alg_WC_Wish_List_Core();
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

		/**
		 * Get the plugin url.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  string
		 */
		function plugin_url() {
			return untrailingslashit( plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  string
		 */
		function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

	}

}