<?php

if ( ! class_exists( 'Alg_WC_Wish_List' ) ) :

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
		load_plugin_textdomain( ALG_WC_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Settings & Scripts
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}else{			
			//new Gamajo_Template_Loader();
			//new Alg_WC_Template_Loader();
			add_action('woocommerce_single_product_summary',array($this,'show_wishlist_btn'),31);
		}
	}	

	/**
	 * Show the toggle button of adding or removing Item from Wishlist 
	 */	
	function show_wishlist_btn(){	
		$params = array(
			'btn_label'	=>	__('Add to Wishlist',ALG_WC_DOMAIN),
			'btn_class'	=>	'alg-wc-wishlist-toggle-btn'
		);
		echo alg_wc_locate_template('add-to-wishlist-btn.php',$params);
		//include('templates/add-to-wishlist-btn.php');
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

endif;