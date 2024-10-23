<?php
/**
 * Wish List for WooCommerce - Core Class.
 *
 * @version 3.0.9
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Core' ) ) {

	final class Alg_WC_Wish_List_Core {

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '3.1.2';

		/**
		 * @var   Alg_WC_Wish_List_Core The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;
		
		/**
		 * @var   Alg_WC_Wish_List_Report The single instance of the class
		 * @since 1.0.0
		 */
		public $report = null;

		/**
		 * Main Alg_WC_Wish_List_Core Instance
		 * Ensures only one instance of Alg_WC_Wish_List is loaded or can be loaded.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 * @return  Alg_WC_Wish_List_Core - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * @var Alg_WC_Wish_List_Pro_Stock_Bkg_Process
		 */
		public static $bkg_process;

		/**
		 * Alg_WC_Wish_List_Admin_Multiple.
		 *
		 * @version 3.0.9
		 * @since   3.0.9
		 *
		 * @var Alg_WC_Wish_List_Admin_Multiple
		 */
		public $admin_multiple_wishlist;

		/**
		 * $free_version_file_system_path.
		 *
		 * @since 3.1.0
		 */
		protected $free_version_file_system_path;
		
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
			// Remove wishlist page
			//Alg_WC_Wish_List_Page::delete_page();

			// Delete meta data
			//self::delete_meta_data();
		}

		/**
		 * Delete all plugin meta data.
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
		 * @version 3.1.0
		 * @since   1.0.0
		 */
		function __construct() {
			// Adds cross-selling library.
			$this->add_cross_selling_library();

			// Move WC Settings tab to WPFactory menu.
			$this->move_wc_settings_tab_to_wpfactory_menu();

			// Adds compatibility with HPOS.
			add_action( 'before_woocommerce_init', function () {
				$this->declare_compatibility_with_hpos( ALG_WC_WL_FILEPATH );
				if ( ! empty( $this->get_free_version_filesystem_path() ) ) {
					$this->declare_compatibility_with_hpos( $this->get_free_version_filesystem_path() );
				}
			} );

			// Set up localisation.
			add_action( 'init', array( $this, 'handle_localization' ) );
			
			// Include required files.
			if ( is_admin() ) {
				$this->init_admin_fields();
			}

			// Check if plugin is enabled on admin.
			if ( true === filter_var( get_option( 'alg_wc_wl_enabled', false ), FILTER_VALIDATE_BOOLEAN ) && true === apply_filters( 'alg_wc_wl_enabled' , true ) ) {
				
				// Scripts
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

				// Handle custom style.
				if ( true === filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_ENABLE, false ), FILTER_VALIDATE_BOOLEAN ) ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_custom_style' ), 20 );
					add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'handle_button_style_params' ), 10, 3 );
					add_filter( 'alg_wc_wl_fa_icon_class', array( $this, 'change_font_awesome_icon_class' ), 20, 2 );
				}

				add_filter( 'alg_wc_wl_toggle_item_texts', array( $this, 'override_toggle_item_texts' ) );
				add_filter( 'alg_wc_wl_locate_template', array( $this, 'locate_template' ), 10, 3 );
				add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'override_button_params' ), 10, 3 );
				add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'override_wishlist_params' ), 11, 3 );
				add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'add_sku_on_wish_list' ), 10, 3 );
				add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'add_description_wish_list' ), 10, 3 );
				
				// Frontned Scripts
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ), 20 );
				
				// Manages wishlist buttons
				$this->handle_buttons();
				
				// Saves wishlist on register
				add_action( 'user_register', array( Alg_WC_Wish_List::get_class_name(), 'save_wish_list_on_register' ) );

				// Saves wishlist on login
				add_action( 'wp_login', array( Alg_WC_Wish_List::get_class_name(), 'save_wish_list_on_login' ), 10, 2 );
				
				add_filter( 'alg_wc_wl_toggle_item_ajax_response', array( $this, 'alg_wc_wl_toggle_item_ajax_response' ) );
				
				// Script Localization
				$this->handle_scripts_localization();
				
				// Ajax
				$this->handle_ajax();

				// Change template path.
				add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );
				add_filter( 'woocommerce_locate_core_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );

				// Initializes background process class.
				$this->initialize_bkg_process_class();
				
				// Manages Shortcodes
				$this->handle_shortcodes();
				
				// Manages widgets
				add_action( 'widgets_init', array( $this, 'create_widgets' ) );
				
				// Email sharing
				if ( true === filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_EMAIL, true ), FILTER_VALIDATE_BOOLEAN ) ) {
					new Alg_WC_Wish_List_Email_Sharing();
				}

				// Setups wishlist tab on my account page
				new Alg_WC_Wish_List_Tab();
				
				// Toggle wishlist item by URL
				add_action( 'init', array( Alg_WC_Wish_List::get_class_name(), 'toggle_wishlist_item_by_url' ) );
				add_filter( 'alg_wc_wl_localize', array( Alg_WC_Wish_List::get_class_name(), 'show_wishlist_notification' ) );
				
				// Setup font awesome icons
				add_filter( 'alg_wc_wl_fa_icon_class', array( $this, 'get_font_awesome_icon_class' ), 9, 2 );
				
				// Responsive script.
				add_filter( 'wp_footer', array( $this, 'handle_responsive_script' ), 9, 2 );

				// Adds variable product data to response text.
				add_filter( 'alg_wc_wl_toggle_item_texts', array( 'Alg_WC_Wish_List_Ajax', 'add_variable_product_data_to_response_text' ) );

				// Filters button position on single page.
				$option_name = Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_SINGLE_POSITION;
				add_filter( "option_{$option_name}", array( $this, 'override_button_position_single' ) );

				// Nav menu item.
				add_filter( 'wp_get_nav_menu_items', array( $this, 'handle_nav_menu_item' ), 10, 3 );

				// Hide default btn.
				add_filter( 'alg_wc_wl_show_default_btn', array( $this, 'hide_default_btn' ), 10, 2 );

				// Hide thumb btn.
				add_filter( 'alg_wc_wl_show_thumb_btn', array( $this, 'hide_thumb_btn' ), 10, 2 );

				// Disallow wish listing while unlogged.
				add_filter( 'alg_wc_wl_toggle_item_response', array( 'Alg_WC_Wish_List_Ajax', 'handle_unlogged_users_response' ) );
				add_filter( 'alg_wc_wl_can_toggle_unlogged', array( 'Alg_WC_Wish_List_Ajax', 'disallow_unlogged_users' ) );
				add_filter( 'alg_wc_wl_btn_enabled', array( $this, 'disable_buttons_to_unlogged_users' ) );
				
				// Report.
				$this->report = new Alg_WC_Wish_List_Report();
				$this->report->init();
				
				// Shortcodes.
				$shortcodes = new Alg_WC_Wish_List_Shortcodes();
				$shortcodes->set_report_class( $this->report );
				$shortcodes->init();

				// JS Updater Events.
				add_action( 'wp_footer', array( $this, 'enable_js_updater_events' ) );

				// Block products grid.
				add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'change_render_product' ), 10, 3 );

				// Remove all from wish list.
				add_filter( 'alg_wc_wl_remove_all_btn_label', array( $this, 'set_remove_all_btn_label' ) );
				add_filter( 'alg_wc_wl_all_removed_text', array( $this, 'set_all_removed_text' ) );
				
				// Manages custom actions
				$this->handle_custom_actions();
				
				// Handle stock manager.
				$this->handle_stock_alert();
				
				// Custom Note Field.
				$note_field = new Alg_WC_Wish_List_Note_Field();
				$note_field->init();

				// Taxonomies.
				$taxonomies = new Alg_WC_Wish_List_Taxonomies();
				$taxonomies->init();
					
				// Subtotal.
				$subtotal = new Alg_WC_Wish_List_Subtotal();
				$subtotal->init();
				
				// Variable products.
				$variable_products = new Alg_WC_Wish_List_Variable_Products();
				$variable_products->init();
				
				// Auto Remove.
				$auto_remove = new Alg_WC_Wish_List_Auto_Remove();
				$auto_remove->init();
				
				// Wish list sorting.
				$wish_list_sorting = new Alg_WC_Wish_List_Sorting();
				$wish_list_sorting->init();
				
				// Compatibility.
				$compatibility = new Alg_WC_Wish_List_Compatibility();
				$compatibility->init();
				
				add_action( 'admin_notices', array( $this, 'clear_wishlist_admin_notice' ) );
				
				// Admin Multiple Wishlist.
				$this->admin_multiple_wishlist = new Alg_WC_Wish_List_Admin_Multiple();
				$this->admin_multiple_wishlist->init();
				
			}
		}

		/**
		 * Declare compatibility with custom order tables for WooCommerce.
		 *
		 * @version 3.1.0
		 * @since   3.1.0
		 *
		 * @param $filesystem_path
		 *
		 * @return void
		 * @link    https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
		 *
		 */
		function declare_compatibility_with_hpos( $filesystem_path ) {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $filesystem_path, true );
			}
		}

		/**
		 * add_cross_selling_library.
		 *
		 * @version 3.1.0
		 * @since   3.1.0
		 *
		 * @return void
		 */
		function add_cross_selling_library(){
			if ( ! is_admin() ) {
				return;
			}
			// Cross-selling library.
			$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
			$cross_selling->setup( array( 'plugin_file_path'   => ALG_WC_WL_FILEPATH ) );
			$cross_selling->init();
		}

		/**
		 * move_wc_settings_tab_to_wpfactory_submenu.
		 *
		 * @version 3.1.0
		 * @since   3.1.0
		 *
		 * @return void
		 */
		function move_wc_settings_tab_to_wpfactory_menu() {
			if ( ! is_admin() ) {
				return;
			}
			// WC Settings tab as WPFactory submenu item.
			$wpf_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();
			$wpf_admin_menu->move_wc_settings_tab_to_wpfactory_menu( array(
				'wc_settings_tab_id' => 'alg_wc_wish_list',
				'menu_title'         => __( 'Wishlist', 'cost-of-goods-for-woocommerce' ),
				'page_title'         => __( 'Wishlist', 'cost-of-goods-for-woocommerce' ),
			) );
		}

		/**
		 * Shows a error message after cleared message
		 *
		 * @version 3.0.8
		 * @since   3.0.8
		 */
		function clear_wishlist_admin_notice() {
			if ( isset( $_GET['cleared'] ) && $_GET['cleared'] == '1' ) {
			?>
			<div class="notice is-dismissible updated">
				<p><?php _e( 'Wishlist cleared.' ); ?></p>
			</div>
			<?php
			}
		}


		/**
		 * set_all_removed_text.
		 *
		 * @version 1.8.2
		 * @since   1.8.2
		 *
		 * @param $text
		 *
		 * @return mixed|void
		 */
		function set_all_removed_text( $text ) {
			$text = get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_REMOVE_ALL_SUCCESS_TEXT, __( 'All the items have been removed from your wish list.', 'wish-list-for-woocommerce' ) );
			return $text;
		}

		/**
		 * @version 1.8.2
		 * @since   1.8.2
		 *
		 * @param $label
		 *
		 * @return string;
		 */
		function set_remove_all_btn_label( $label ) {
			$label = get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_REMOVE_ALL_BTN_LABEL, __( 'Remove all', 'wish-list-for-woocommerce' ) );
			return $label;
		}

		/**
		 * change_font_awesome_icon_class.
		 *
		 * @version 1.8.1
		 * @since   1.8.0
		 *
		 * @param $class
		 * @param $icon
		 *
		 * @return string
		 */
		function change_font_awesome_icon_class( $class, $icon ) {
			switch ( $icon ) {
				case 'remove_btn':
					$remove_icon      = get_option( Alg_WC_Wish_List_Settings_Style::OPTION_REMOVE_BTN_ICON_CLASS, 'fas fa-times-circle' );
					//$additional_class = get_option( Alg_WC_Wish_List_Settings_Style::OPTION_REMOVE_BTN_ADDITIONAL_ICON_CLASS, 'fa-2x' );
					//$class            = $remove_icon . ' ' . $additional_class;
					$class            = $remove_icon;
					break;
			}
			return $class;
		}

		/**
		 * change_render_product.
		 *
		 * @version 1.7.8
		 * @since   1.7.8
		 *
		 * @param $html
		 * @param $data
		 * @param $product
		 *
		 * @return mixed
		 */
		function change_render_product( $html, $data, $product ) {
			if (
				'yes' === get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_LOOP_ENABLE, 'yes' )
				&& 'yes' === get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_LOOP_GUTENBERG, 'no' )
			) {
				$search = '<li class="wc-block-grid__product">';
				ob_start();
				Alg_WC_Wish_List_Toggle_Btn::show_thumb_btn( array( 'product_id' => $product->get_id() ) );
				$add = ob_get_contents();
				ob_end_clean();
				$html = str_replace( $search, $search . $add, $html );
			}
			return $html;
		}

		/**
		 * Forces the Wish List to display based on Javascript events.
		 *
		 * @version 1.7.2
		 * @since   1.7.2
		 */
		function enable_js_updater_events() {
			if ( 'yes' !== get_option( Alg_WC_Wish_List_Settings_Advanced::OPTION_WISH_LIST_UPDATER_EVENTS_ENABLE, 'no' ) ) {
				return;
			}
			$events = explode( "\n", str_replace( "\r", "", get_option( Alg_WC_Wish_List_Settings_Advanced::OPTION_WISH_LIST_UPDATER_EVENTS, Alg_WC_Wish_List_Settings_Advanced::get_updater_events_default() ) ) );
			$events = array_map( 'sanitize_text_field', $events );
			?>
			<script>
				jQuery(document).on('<?php echo implode( " ", $events ); ?>', function () {
					jQuery('.alg-wc-wl-btn').addClass('ajax-loading');
					var alg_wc_wl_show = function () {
						jQuery('.alg-wc-wl-btn.ajax-loading').removeClass('ajax-loading');
					}
					var alg_wc_wl_position = function () {
						if (typeof alg_wc_wl_thumb_btn_positioner === 'undefined' || jQuery.isEmptyObject(alg_wc_wl_thumb_btn_positioner)) {
							jQuery('body').on('alg_wc_wl_thumb_btn_positioner', function (e) {
								alg_wc_wl_thumb_btn_positioner = e.obj;
								alg_wc_wl_thumb_btn_positioner.init();
							});
						} else {
							alg_wc_wl_thumb_btn_positioner.init();
						}
						setTimeout(alg_wc_wl_show, 200);
					};
					setTimeout(alg_wc_wl_position, 500);
				})
			</script>
			<?php
		}

		/**
		 * disable_buttons_to_unlogged_users.
		 *
		 * @version 1.7.1
		 * @since   1.7.1
		 *
		 * @param $enabled
		 *
		 * @return bool
		 */
		function disable_buttons_to_unlogged_users( $enabled ) {
			if (
				! is_user_logged_in() &&
				'no' === get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_UNLOGGED_CAN_SEE_BUTTONS, 'yes' )
			) {
				return false;
			}
			return $enabled;
		}

		/**
		 * hide_thumb_btn.
		 *
		 * @version 1.6.2
		 * @since   1.6.2
		 *
		 * @param $show
		 * @param $product_id
		 *
		 * @return bool
		 */
		function hide_thumb_btn( $show, $product_id ){
			if ( ! empty( $hidden_tags = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_THUMB_BTN_HIDE_BY_TAG, array() ) ) ) {
				if ( has_term( $hidden_tags, 'product_tag', $product_id ) ) {
					$show = false;
				}
			}
			return $show;
		}

		/**
		 * hide_default_btn.
		 *
		 * @version 1.6.2
		 * @since   1.6.2
		 *
		 * @param $show
		 * @param $product_id
		 *
		 * @return bool
		 */
		function hide_default_btn( $show, $product_id ) {
			if ( ! empty( $hidden_tags = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_HIDE_BY_TAG, array() ) ) ) {
				if ( has_term( $hidden_tags, 'product_tag', $product_id ) ) {
					$show = false;
				}
			}
			return $show;
		}

		/**
         * Handle Nav menu icon for Wish List Icon
         *
		 * @version 2.0.3
		 * @since   1.6.0
         *
		 * @param $items
		 * @param $menu
		 * @param $args
		 *
		 * @return mixed
		 */
		public function handle_nav_menu_item( $items, $menu, $args ) {
			if ( 'yes' !== get_option( Alg_WC_Wish_List_Settings_General::OPTION_WISH_LIST_NAV_MENU_ICON, 'no' ) ) {
				return $items;
			}
			foreach ( $items as $item ) {
				if ( in_array( 'wish-list-icon', $item->classes ) ) {
					$alg_wc_wl_icon_ignore_excluded_items = apply_filters( 'alg_wc_wl_icon_ignore_excluded_items', true );
					$alg_wc_wl_icon_ignore_excluded_items = $alg_wc_wl_icon_ignore_excluded_items ? 'true' : 'false';
					$item->title = do_shortcode( '[alg_wc_wl_icon link="false" ignore_excluded_items="'.$alg_wc_wl_icon_ignore_excluded_items.'"]' );
				}
			}
			return $items;
		}

		/**
		 * Overrides button position on single product page
		 *
		 * @version 1.5.0
		 * @since 1.5.0
		 *
		 * @param $position
		 *
		 * @return mixed
		 */
		public function override_button_position_single( $position ) {
			$option_name = Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_SINGLE_POSITION_OVERRIDE;
			$option      = get_option( $option_name );
			if ( ! empty( $option ) ) {
				$position = $option;
			}

			return $position;
		}

		/**
		 * Adds SKU to wish list
		 *
		 * @version 1.4.9
		 * @since   1.4.9
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return mixed
		 */
		public function add_sku_on_wish_list( $params, $final_file, $path ) {
			if ( false === filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_SKU, false ), FILTER_VALIDATE_BOOLEAN ) ) {
				return $params;
			}
			switch ( $path ) {
				case 'wish-list.php':
					$params['sku'] = true;
				break;
			}

			return $params;
		}

		/**
		 * Adds product description to wish list
         *
		 * @version 1.4.9
		 * @since   1.4.9
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return mixed
		 */
		public function add_description_wish_list( $params, $final_file, $path ) {
			if ( false === filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_PRODUCT_DESCRIPTION, false ), FILTER_VALIDATE_BOOLEAN ) ) {
				return $params;
			}
			switch ( $path ) {
				case 'wish-list.php':
					$params['product_description'] = true;
				break;
			}

			return $params;
		}

		/**
		 * Handles Localization.
		 *
		 * Tries to load from 'wp-content/languages/plugins/wish-list-for-woocommerce-pt_BR.mo' first.
		 * If it's not possible, tries to load from "wp-content/plugins/wish-list-for-woocommerce-pro/languages/wish-list-for-woocommerce-pt_BR.mo'
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		public function handle_localization() {
			$domain = 'wish-list-for-woocommerce';
			load_plugin_textdomain( $domain, false, dirname( ALG_WC_WL_BASENAME ) . '/langs/' );
		}

		/**
		 * Initializes background process class
		 *
		 * @version 1.9.3
		 * @since   1.3.2
		 */
		public function initialize_bkg_process_class() {
			if ( empty( self::$bkg_process ) ) {
				self::$bkg_process = new Alg_WC_Wish_List_Stock_Bkg_Process();
			}
		}

		/**
		 * Override woocommerce locate template
		 *
		 * @version 1.3.2
		 * @since   1.3.2
		 *
		 * @param $template
		 * @param $template_name
		 * @param $template_path
		 *
		 * @return string
		 */
		public function woocommerce_locate_template( $template, $template_name, $template_path ) {
			if ( strpos( $template_name, 'alg_wcwl' ) !== false ) {

				$template_path = 'woocommerce';
				$default_path  = ALG_WC_WL_DIR . 'templates' . DIRECTORY_SEPARATOR;
				$template      = locate_template(
					array(
						trailingslashit( $template_path ) . $template_name,
						$template_name,
					)
				);

				// Get default template/
				if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
					$template = $default_path . $template_name;
				}
			}
			return $template;
		}

		/**
		 * Handle Ajax
		 *
		 * @version 1.3.0
		 * @since   1.2.8
		 */
		private function handle_ajax() {
			
		    // Get wish list shortcode via ajax
			$action = Alg_WC_Wish_List_Ajax::ACTION_GET_WISH_LIST_SHORTCODE;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_wish_list_shortcode' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_wish_list_shortcode' ) );
			
			$toggle_wish_list_item_action = Alg_WC_Wish_List_Ajax::ACTION_TOGGLE_WISH_LIST_ITEM;
			add_action( "wp_ajax_nopriv_{$toggle_wish_list_item_action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item' ) );
			add_action( "wp_ajax_{$toggle_wish_list_item_action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'toggle_wish_list_item' ) );

			// Get wishlist via ajax
			$action = Alg_WC_Wish_List_Ajax::ACTION_GET_WISH_LIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_wish_list' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_wish_list' ) );

			// Remove all button
			$action = Alg_WC_Wish_List_Ajax::ACTION_REMOVE_ALL_FROM_WISH_LIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'remove_all_from_wish_list' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'remove_all_from_wish_list' ) );
			
			// Save new wishlist
			$action = Alg_WC_Wish_List_Ajax::ACTION_SAVE_WISHLIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'save_to_multiple_wishlist' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'save_to_multiple_wishlist' ) );
			
			// Save multiple wishlist
			$action = Alg_WC_Wish_List_Ajax::ACTION_SAVE_MULTIPLE_WISHLIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'save_multiple_wishlist' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'save_multiple_wishlist' ) );
			
			// delete multiple wishlist
			$action = Alg_WC_Wish_List_Ajax::ACTION_DELETE_MULTIPLE_WISHLIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'delete_multiple_wishlist' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'delete_multiple_wishlist' ) );
			
			// Get multiple wishlist
			$action = Alg_WC_Wish_List_Ajax::ACTION_GET_MULTIPLE_WISHLIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_multiple_wishlist' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'get_multiple_wishlist' ) );
			
			// clear wishlist
			$action = Alg_WC_Wish_List_Ajax::ACTION_GET_CLEAR_WISHLIST_ADMIN;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'admin_clear_wishlist' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'admin_clear_wishlist' ) );
			
			// Save Duplicate wishlist
			$action = Alg_WC_Wish_List_Ajax::ACTION_DUPLICATE_WISHLIST;
			add_action( "wp_ajax_nopriv_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'save_duplicate_wishlist' ) );
			add_action( "wp_ajax_{$action}", array( Alg_WC_Wish_List_Ajax::get_class_name(), 'save_duplicate_wishlist' ) );
		}

		/**
         * Overrides template
         *
		 * @version 1.3.4
		 * @since   1.2.6
         *
		 * @param $final_file
		 * @param $params
		 * @param $path
		 *
		 * @return string
		 */
		public function locate_template( $final_file, $params, $path ) {
			$located     = locate_template( array(
				ALG_WC_WL_FOLDER_NAME . DIRECTORY_SEPARATOR . $path,
			) );
			$plugin_path = ALG_WC_WL_DIR . 'templates' . DIRECTORY_SEPARATOR . $path;
			if ( ! $located && file_exists( $plugin_path ) ) {
				$final_file = $plugin_path;
			} elseif ( $located ) {
				$final_file = $located;
			}

			return $final_file;
		}



		/**
		 * Overrides the ajax response when an item is toggled on wish list
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $response
		 *
		 * @return mixed
		 */
		public function alg_wc_wl_toggle_item_ajax_response( $response ) {
			$response = Alg_WC_Wish_List_Customization_Notification::get_toggle_item_ajax_response( $response );
			return $response;
		}

		/**
		 * Load scripts and styles
		 *
		 * @version 1.3.6
		 * @since   1.0.0
		 */
		function enqueue_frontend_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Balloon-css
			if ( filter_var( get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_TOOLTIP_ENABLE, false ), FILTER_VALIDATE_BOOLEAN ) ) {
				Alg_WC_Wish_List_Tooltip::enqueue_scripts( $suffix );
				Alg_WC_Wish_List_Tooltip::add_inline_script( 'alg-wc-wish-list' );
			}

			// Updates wish list counter
			Alg_WC_Wish_List_Ajax::update_wish_list_counter('alg-wc-wish-list');

			// Call Wishlist shortcode via AJAX
            Alg_WC_Wish_List_Ajax::get_wishlist_sc_via_ajax('alg-wc-wish-list');
			Alg_WC_Wish_List_Ajax::get_wishlist_via_ajax('alg-wc-wish-list');

			// Style items that are loading through ajax
            Alg_WC_Wish_List_Customization_Wish_List::style_ajax_items('alg-wc-wish-list');
		}

		/**
		 * Replaces some strings based on admin settings when an item is removed or added to wish list
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function override_toggle_item_texts($params){
			$params['added']         = __( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_ADDED_TO_WISH_LIST ) ), 'wish-list-for-woocommerce' );
			$params['saved']         = __( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_ADDED_TO_WISH_LIST_MULTIPLE ) ), 'wish-list-for-woocommerce' );
			$params['removed']       = __( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_REMOVED_FROM_WISH_LIST ) ), 'wish-list-for-woocommerce' );
			$params['error']         = __( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_ERROR ) ), 'wish-list-for-woocommerce' );
			$params['see_wish_list'] = __(sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_SEE_YOUR_WISH_LIST ) ), 'wish-list-for-woocommerce');
			return $params;
		}

		/**
		 * Enqueue admin scripts
		 *
		 * @version 1.7.5
		 * @since   1.0.0
		 */
		function enqueue_admin_scripts($hook){

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
		
			if ( $hook == 'woocommerce_page_wc-settings' && isset( $_GET['tab'] ) && $_GET['tab'] == 'alg_wc_wish_list' ) {
			    // Font awesome
				if ( ! wp_script_is( 'alg-font-awesome' ) ) {
					$css_file = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https//use.fontawesome.com/releases/v5.5.0/css/all.css' );
					wp_register_style( 'alg-font-awesome', $css_file, array() );
					wp_enqueue_style( 'alg-font-awesome' );
				}

				// Bootstrap
				wp_enqueue_script( 'alg-wc-wl-bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );

				// Fontawesome icon picker
				$css_file = 'assets/vendor/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css';
				$css_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR. $css_file ) );
				wp_register_style( 'alg-wc-wl-fa-iconpicker', ALG_WC_WL_URL . $css_file, array(), $css_ver );
				wp_enqueue_style( 'alg-wc-wl-fa-iconpicker' );
				$js_file = 'assets/vendor/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js';
				$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
				wp_register_script( 'alg-wc-wl-fa-iconpicker', ALG_WC_WL_URL . $js_file, array( 'jquery' ), $js_ver, true );
				wp_enqueue_script( 'alg-wc-wl-fa-iconpicker' );

				// Color picker Alpha
				$js_file = 'assets/vendor/color-picker-alpha/wp-color-picker-alpha.min.js';
				$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker-alpha', ALG_WC_WL_URL . $js_file, array( 'wp-color-picker' ), $js_ver, true );
				wp_add_inline_script(
					'wp-color-picker-alpha',
					'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
				);

				// Main js file for admin
				$js_file = 'assets/js/admin/alg-wc-wl-pro-admin.js';
				$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
				wp_register_script( 'alg-wc-wl-pro-admin', ALG_WC_WL_URL . $js_file, array( 'jquery','alg-wc-wl-fa-iconpicker' ), $js_ver, true );
				wp_enqueue_script( 'alg-wc-wl-pro-admin' );

				?>
                    <?php // Style for Iconpicker ?>
					<style>
					.alg-wc-wl-iconpicker-selected{
						box-shadow:0 0 0 1px #ddd !important;
						color:#000 !important;
						background:#ddd;
					}
					.iconpicker-popover{
						width:229px !important;
					}

                    <?php // Style for Color picker alpha ?>
                    .wp-picker-container{vertical-align:middle;}
                    .color-picker{display:inline-block !important}
                    .wp-picker-input-wrap{vertical-align:top;display:inline-block;}
                    </style>
				<?php
			}
		}

		/**
		 * Changes buttons style params based on admin settings
         *
		 * @version 1.2.8
		 * @since   1.0.0
		 * @param $params
		 * @param $final_file
		 * @param $path
		 * @return mixed
		 */
		public function handle_button_style_params( $params, $final_file, $path ) {
			switch ( $path ) {
				case 'default-button.php':
					$params = Alg_WC_Wish_List_Customization_Default_Button::handle_button_params( $params, $final_file, $path );
				break;
				case 'thumb-button.php':
					$params = Alg_WC_Wish_List_Customization_Thumb_Button::handle_button_params( $params, $final_file, $path );
				break;
			}
			return $params;
		}

		/**
		 * Overrides wishlist params based on admin settings
		 *
		 * @version 1.7.5
		 * @since   1.2.8
		 * @param $params
		 * @param $final_file
		 * @param $path
		 * @return mixed
		 */
		public function override_wishlist_params( $params, $final_file, $path ) {
			switch ( $path ) {
				case 'wish-list.php':
					$work_with_cache = filter_var( get_option( Alg_WC_Wish_List_Settings_General::OPTION_WORK_WITH_CACHE ), FILTER_VALIDATE_BOOLEAN );
					$params['work_with_cache'] = $work_with_cache;
					if ( true === filter_var( get_option( Alg_WC_Wish_List_Settings_List::OPTION_SHOW_PRODUCT_CATEGORY, 'no' ), FILTER_VALIDATE_BOOLEAN ) ) {
						$params['show_prod_category'] = true;
					}
					$params['empty_wishlist_text'] = get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_EMPTY_WISHLIST, __( 'The Wish list is empty.', 'wish-list-for-woocommerce' ) );
				break;
                case 'share.php':
                    $params = Alg_WC_Wish_List_Sharing::handle_share_params( $params, $final_file, $path );
                break;
			}
			return $params;
		}

		/**
         * Override some button texts based on admin settings
         *
		 * @version 1.2.8
		 * @since   1.0.0
		 * @param $params
		 * @param $final_file
		 * @param $path
		 * @return mixed
		 */
		public function override_button_params( $params, $final_file, $path ){
			switch ( $path ) {
				case 'default-button.php':
					$params = Alg_WC_Wish_List_Customization_Default_Button::override_button_texts( $params, $final_file, $path );
					$params = Alg_WC_Wish_List_Customization_Default_Button::add_ajax_loading_params( $params, $final_file, $path );
				break;
				case 'thumb-button.php':
					$params = Alg_WC_Wish_List_Customization_Thumb_Button::add_ajax_loading_params( $params, $final_file, $path );
                break;
			}
			return $params;
		}

		/**
		 * Generate custom style
		 *
		 * @version 1.3.3
		 * @since   1.0.0
		 */
		public function enqueue_frontend_custom_style(){
			$custom_css  = Alg_WC_Wish_List_Customization_Default_Button::is_default_button_custom_style_necessary() ? Alg_WC_Wish_List_Customization_Default_Button::get_default_button_custom_style() : '';
			$custom_css .= Alg_WC_Wish_List_Customization_Thumb_Button::get_thumb_button_custom_style();
			$custom_css .= Alg_WC_Wish_List_Customization_Wish_List::get_wish_list_custom_style();
			$custom_css.= Alg_WC_Wish_List_Customization_Wish_List::get_tab_icon_custom_style();
			wp_add_inline_style( 'alg-wc-wish-list', $custom_css );

			$custom_css = Alg_WC_Wish_List_Customization_Notification::get_notification_custom_style();
			wp_add_inline_style( 'alg-wc-wish-list-izitoast', $custom_css );
		}

		/**
		 * Localize scripts for loading dynamic vars in JS
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function handle_scripts_localization() {
			add_filter( 'alg_wc_wl_localize', array( $this, 'override_script_localization' ), 10, 2 );
			add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ), 12 );
		}

		/**
         * Overrides any script that has been already localized.
         *
		 * @version 1.2.6
		 * @since   1.0.0
		 * @param $object
		 * @param $object_name
		 *
		 * @return array
		 */
		public function override_script_localization( $object, $object_name ) {
			if ( true === filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_ENABLE, false ), FILTER_VALIDATE_BOOLEAN ) ) {
				if ( $object_name == 'alg_wc_wl_notification' ) {
					$new_object = Alg_WC_Wish_List_Customization_Notification::localize_script();
					$object     = array_merge( $new_object, $object );
				}
			}
			return $object;
		}

		/**
         * Localize scripts
         *
		 * @version 2.0.3
		 * @since   1.0.0
		 */
		public function localize_scripts(){
			if ( true === filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_ENABLE, false ), FILTER_VALIDATE_BOOLEAN ) ) {
				Alg_WC_Wish_List_Customization_Thumb_Button::localize_script( 'alg-wc-wish-list' );
			}
			
			$ajax_url = get_option( Alg_WC_Wish_List_Settings_Advanced::OPTION_ADMIN_AJAX_URL, admin_url( 'admin-ajax.php', 'relative' ) );
			if ( empty( $ajax_url ) ) {
				$ajax_url = admin_url( 'admin-ajax.php', 'relative' );
			}
			wp_localize_script( 'alg-wc-wish-list', 'alg_wc_wl',
				array(
					'ajaxurl'          => $ajax_url,
					'fa_icons'         => array( 'copy' => apply_filters( 'alg_wc_wl_fa_icon_class', '', 'copy' ) ),
					'error_text'       => apply_filters( 'alg_wc_wl_error_text', __( 'Sorry, Some error occurred. Please, try again later.', 'wish-list-for-woocommerce' ) ),
					'all_removed_text' => apply_filters( 'alg_wc_wl_all_removed_text', __( 'All the items have been removed from your wishlist.', 'wish-list-for-woocommerce' ) )
				)
			);
			Alg_WC_Wish_List_Toggle_Btn::localize_script( 'alg-wc-wish-list' );
			Alg_WC_Wish_List_Ajax::localize_script( 'alg-wc-wish-list' );
			Alg_WC_Wish_List_Notification::localize_script( 'alg-wc-wish-list' );
		
			Alg_WC_Wish_List_Ajax::localize_script('alg-wc-wish-list');
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
		 * @version 2.2.1
		 * @since   1.0.0
		 */
		function init_admin_fields() {
			
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . ALG_WC_WL_BASENAME, array( $this, 'action_links' ) );
			
			new Alg_WC_Wish_List_Settings_General();
			new Alg_WC_Wish_List_Settings_Social();
			new Alg_WC_Wish_List_Settings_Buttons();
			
			new Alg_WC_Wish_List_Settings_List();
			new Alg_WC_Wish_List_Settings_Shortcodes();
			new Alg_WC_Wish_List_Settings_Notification();
			
			new Alg_WC_Wish_List_Settings_Style();
            new Alg_WC_Wish_List_Settings_Texts();
			new Alg_WC_Wish_List_Settings_Admin();
			
			new Alg_WC_Wish_List_Settings_Compatibility();
			new Alg_WC_Wish_List_Settings_Advanced();
			
			$this->create_custom_settings_fields();

			if ( is_admin() && get_option( 'alg_wish_list_version', '' ) !== $this->version ) {
				update_option( 'alg_wish_list_version', $this->version );
			}
			

			// Admin scripts
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts' ) );
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
		 * Add Wishlist settings tab to WooCommerce settings.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function add_woocommerce_settings_tab( $settings ) {
			$settings[] = new Alg_WC_Wish_List_Settings();
			return $settings;
		}

		/**
		 * reposition_option.
		 *
		 * @version 2.0.1
		 * @since   2.0.1
		 *
		 * @param $target
		 * @param $full_settings
		 * @param $option
		 *
		 * @return mixed
		 */
		function reposition_option( $target, $full_settings, $option, $reposition_method = 'prepend' ) {
			$end_section = wp_list_filter( $full_settings, $target );
			if ( ! empty( $end_section ) ) {
				reset( $end_section );
				$end_section_key = key( $end_section );
				array_splice( $full_settings, $end_section_key, 0, $option );
			}
			return $full_settings;
		}
		
		/**
		 * Manages custom actions ( Social )
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		private function handle_custom_actions() {
			// Wishlist table actions
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, array( $this, 'handle_social' ) );
			add_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, array( $this, 'handle_social' ) );
		}

		/**
		 * Load social networks template ( Social )
		 *
		 * @version 1.8.7
		 * @since   1.0.0
		 */
		public function handle_social() {
			$user_id_from_query_string = isset( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) ? sanitize_text_field( $_REQUEST[ Alg_WC_Wish_List_Query_Vars::USER ] ) : '';
			$queried_user_id           = ! empty( $user_id_from_query_string ) ? Alg_WC_Wish_List_Query_Vars::crypt_user( $user_id_from_query_string, 'd' ) : null;
			$queried_user_id           = empty( $queried_user_id ) ? $user_id_from_query_string : $queried_user_id;

			// Doesn't show if queried user id is the user itself
			if ( $queried_user_id && Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id() != $queried_user_id ) {
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
					Alg_WC_Wish_List_Query_Vars::USER          => is_user_logged_in() ? Alg_WC_Wish_List_Query_Vars::crypt_user( get_current_user_id() ) : Alg_WC_Wish_List_Unlogged_User::get_unlogged_user_id(),
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
					'copy'   => array(
						'active' => filter_var( get_option( Alg_WC_Wish_List_Settings_Social::OPTION_COPY, 'no' ), FILTER_VALIDATE_BOOLEAN ),
						'url'    => Alg_WC_Wish_List::get_url()
					),
				);
				echo alg_wc_wl_locate_template( 'share.php', $params );
			}
		}
		
		/**
		 * Load scripts and styles
		 *
		 * @version 2.0.6
		 * @since   1.0.0
		 */
		function enqueue_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Main css file
			$css_file = 'assets/css/alg-wc-wish-list'.$suffix.'.css';
			$css_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $css_file ) );
			wp_register_style( 'alg-wc-wish-list', ALG_WC_WL_URL . $css_file, array(), $css_ver );
			wp_enqueue_style( 'alg-wc-wish-list' );
			
			// multiple Wishlist popup css file
			if( 'yes' === get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ) ){
				$css_file = 'assets/css/algwcwishlistmodal'.$suffix.'.css';
				$css_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $css_file ) );
				wp_register_style( 'alg-wc-wish-list-popup', ALG_WC_WL_URL . $css_file, array(), $css_ver );
				wp_enqueue_style( 'alg-wc-wish-list-popup' );
			}

			// Font awesome
			$this->fix_fontawesome_url_option();
			$css_file = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https://use.fontawesome.com/releases/v6.4.2/css/all.css' );
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
			
			if( 'yes' === get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ) ){
				// Multiple Wishlist POPUP js file
				$js_file = 'assets/js/algwcwishlistmodal'.$suffix.'.js';
				$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
				wp_register_script( 'alg-wc-wish-list-popup', ALG_WC_WL_URL . $js_file, array( 'jquery' ), $js_ver, true );
				wp_enqueue_script( 'alg-wc-wish-list-popup' );
			}
			
			// Main js file
			$js_file = 'assets/js/alg-wc-wish-list'.$suffix.'.js';
			$js_ver = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $js_file ) );
			wp_register_script( 'alg-wc-wish-list', ALG_WC_WL_URL . $js_file, array( 'jquery' ), $js_ver, true );
			wp_enqueue_script( 'alg-wc-wish-list' );
			
			
		}
		
		/**
		 * fix_fontawesome_url.
		 *
		 * @version 1.9.9
		 * @since   1.6.7
		 *
		 */
		function fix_fontawesome_url_option() {
			$css_file = get_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https://use.fontawesome.com/releases/v6.4.2/css/all.css' );
			if ( 'https//use.fontawesome.com/releases/v5.5.0/css/all.css' === $css_file ) {
				update_option( Alg_WC_Wish_List_Settings_General::OPTION_FONT_AWESOME_URL, 'https://use.fontawesome.com/releases/v6.4.2/css/all.css' );
			}
		}
		
		/**
		 * Manages wishlist buttons
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
		 * @version 1.7.3
		 * @since   1.0.0
		 */
		private function handle_shortcodes() {
			add_shortcode( Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST, array( Alg_WC_Wish_List_Shortcodes::get_class_name(), 'sc_alg_wc_wl' ) );
			add_shortcode( Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST_COUNT, array( Alg_WC_Wish_List_Shortcodes::get_class_name(), 'sc_alg_wc_wl_counter' ) );
			add_shortcode( Alg_WC_Wish_List_Shortcodes::SHORTCODE_WISH_LIST_REMOVE_ALL_BTN, array( Alg_WC_Wish_List_Shortcodes::get_class_name(), 'sc_alg_wc_wl_remove_all_btn' ) );
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
		 * get_font_awesome_icon_class.
		 *
		 * @version 1.9.9
		 * @since   1.5.9
		 *
		 * @param $class
		 * @param $icon
		 *
		 * @return mixed|string
		 */
		public function get_font_awesome_icon_class( $class, $icon ) {
			switch ( $icon ) {
				case 'facebook':
					$class = 'fab fa-facebook-square';
					break;
				case 'twitter':
					$class            = 'fab fa-twitter-square';
					$font_awesome_url = get_option( 'alg_wc_wl_fontawesome_url', 'https://use.fontawesome.com/releases/v6.4.2/css/all.css' );
					if ( false !== strpos( $font_awesome_url, '/v6' ) ) {
						$class = 'fa-brands fa-square-x-twitter';
					}
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
				case 'remove_btn':
					$class = 'fas fa-2x fa-times-circle';
					break;
			}

			return $class;
		}
		
		/**
		 * handle_responsive_script.
		 *
		 * @version 2.2.9
		 * @since   1.9.0
		 */
		function handle_responsive_script() {
			$php_to_js_data = array(
				'max_width'         => get_option( 'alg_wc_wl_responsiveness_max_width', 768 ),
				'max_height'        => get_option( 'alg_wc_wl_responsiveness_max_height', 400 ),
				'evaluation_method' => get_option( 'alg_wc_wl_responsiveness_evaluation_method', 'max_width_or_max_height' ),
			);
			
			?>
			<script>
				jQuery(document).ready(function ($) {
					let data = <?php echo json_encode( $php_to_js_data );?>;
					let isMobile = false;
					$(window).on("load resize scroll", function () {
						if (data.evaluation_method == 'max_width_or_max_height') {
							isMobile = $(window).width() < data.max_width || $(window).height() < data.max_height ? true : false;
						} else if (data.evaluation_method == 'max_width_and_max_height') {
							isMobile = $(window).width() < data.max_width && $(window).height() < data.max_height ? true : false;
						}
						isMobile ? $('body').addClass('alg-wc-wl-responsive') : $('body').removeClass('alg-wc-wl-responsive');
					});
				});
			</script>
			<?php if( 'yes' === get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ) ){ ?>
			<div class="algwcwishlistmodal-container js-algwcwishlistmodal-container">
				<div class="algwcwishlistmodal js-algwcwishlistmodal" data-modal="a">
					<button type="button" class="iziToast-close page__btn--cancel js-algwcwishlistmodal-btn-close">x</button>
					
					
					<div class="select-wishlist">
						<h2><?php _e( 'Select Wishlist', 'wish-list-for-woocommerce' ); ?></h2>
						<ul class="algwc-wishlist-collections-wrapper">
						
						</ul>
						
						<div class="button-split">
						<button class="page__btn page__btn--create js-algwcwishlistmodal-btn-create"><?php _e( 'Create Wishlist', 'wish-list-for-woocommerce' ); ?></button>
						<button class="page__btn page__btn--save js-algwcwishlistmodal-btn-save-wishlist"><?php _e( 'Done', 'wish-list-for-woocommerce' ); ?></button>
						<div class="float-clear"></div>
						<input type="hidden" name="wishlist_form_product_id" id="wishlist_form_product_id" value="0">
						</div>
					</div>
					
					<div class="create-wishlist-form is-hidden">
						<h2><?php _e( 'Create Wishlist', 'wish-list-for-woocommerce' ); ?></h2>
						<div class="form-field-wrap">
							<label for="wishlist_name"><?php _e( 'Wishlist Name', 'wish-list-for-woocommerce' ); ?></label>
							<input type="text" name="wishlist_name" id="wishlist_name" class="form-field">
						</div>
						<div class="button-split">
							<button class="page__btn page__btn--create js-algwcwishlistmodal-btn-save"><?php _e( 'Save Wishlist', 'wish-list-for-woocommerce' ); ?></button>
							<button class="page__btn page__btn--save js-algwcwishlistmodal-btn-cancel"><?php _e( 'Cancel', 'wish-list-for-woocommerce' ); ?></button>
							<div class="float-clear"></div>
						</div>
					</div>
					
					<div class="copy-wishlist-form is-hidden">
						<h2><?php _e( 'Copy Wishlist', 'wish-list-for-woocommerce' ); ?></h2>
						<div class="form-field-wrap">
							<label for="duplicate_wishlist_name"><?php _e( 'Duplicate Wishlist Name', 'wish-list-for-woocommerce' ); ?></label>
							<input type="text" name="duplicate_wishlist_name" id="duplicate_wishlist_name" class="form-field">
						</div>
						<div class="button-split">
							<button class="page__btn page__btn--create js-algwcwishlistmodal-btn-save-copy"><?php _e( 'Save Wishlist', 'wish-list-for-woocommerce' ); ?></button>
							<button class="page__btn page__btn--save js-algwcwishlistmodal-btn-cancel-copy"><?php _e( 'Cancel', 'wish-list-for-woocommerce' ); ?></button>
							<div class="float-clear"></div>
							<input type="hidden" name="wishlist_tab_id" id="wishlist_tab_id" value="d">
						</div>
					</div>
					
				</div>
			</div>
			<div class="algwcwishlistmodal-overlay js-algwcwishlistmodal-overlay"></div>
			
			<?php
			}
		}
		
		/**
		 * Handles stock alert.
		 *
		 * @version 2.0.1
		 * @since   1.3.2
		 */
		private function handle_stock_alert() {
			$stock_manager = new Alg_WC_Wish_List_Stock_Manager();
			$stock_manager->init();
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
		 * get_free_version_filesystem_path.
		 *
		 * @version 3.1.0
		 * @since   3.1.0
		 *
		 * @return mixed
		 */
		public function get_free_version_filesystem_path() {
			return $this->free_version_file_system_path;
		}

		/**
		 * set_free_version_filesystem_path.
		 *
		 * @version 3.1.0
		 * @since   3.1.0
		 *
		 * @param   mixed  $free_version_file_system_path
		 */
		public function set_free_version_filesystem_path( $free_version_file_system_path ) {
			$this->free_version_file_system_path = $free_version_file_system_path;
		}
		

	}
}