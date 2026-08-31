<?php
/**
 * Wish List for WooCommerce Pro - General Section Settings.
 *
 * @version 3.5.1
 * @since   1.0.0
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'Alg_WC_Wish_List_Settings_General' ) ) {
	class Alg_WC_Wish_List_Settings_General extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_FONT_AWESOME     = 'alg_wc_wl_fontawesome';
		const OPTION_FONT_AWESOME_SOURCE = 'alg_wc_wl_fontawesome_source';
		const OPTION_ENABLED          = 'alg_wc_wl_enabled';
		const OPTION_METABOX_PRO      = 'alg_wc_wl_cmb_pro';

		const OPTION_MULTIPLE_WISHLIST = 'alg_wc_wl_multiple_wishlist_enabled';

		// Move to free
		const OPTION_WORK_WITH_CACHE         = 'alg_wc_wl_work_with_cache';
		const OPTION_WISH_LIST_NAV_MENU_ICON = 'alg_wc_wl_nav_menu_item';

		const OPTION_FRONTEND_ASSETS_LOADING_MODE = 'alg_wc_wl_frontend_assets_loading_mode';
		const OPTION_FRONTEND_ASSETS_PAGES        = 'alg_wc_wl_frontend_assets_pages';
		const OPTION_FRONTEND_ASSETS_CONDITIONALS = 'alg_wc_wl_frontend_assets_conditionals';

		protected $pro_version_url = 'https://wpcodefactory.com/item/wish-list-woocommerce/';


		/**
		 * Constructor.
		 *
		 * @version 3.2.2
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = false ) {
			$this->id = '';
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );

			//$this->desc = __( 'General', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * set_section_variables.
		 *
		 * @version 3.2.2
		 * @since   3.2.2
		 *
		 * @return void
		 */
		public function set_section_variables() {
			parent::set_section_variables();
			$this->desc = __( 'General', 'wish-list-for-woocommerce' );
		}

		/**
		 * get_section_priority.
		 *
		 * @version 2.3.7
		 * @since   2.3.7
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 7;
		}

		/**
		 * get_settings.
		 *
		 * @version 3.5.1
		 * @since   1.0.0
		 */
		function get_settings( $settings = null ) {

			$new_settings = array(
				array(
					'title' => __( 'General options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_options',
				),
				array(
					'title'   => __( 'Wishlist for WooCommerce', 'wish-list-for-woocommerce' ),
					/* translators: %s: plugin name */
					'desc'    => sprintf( __( 'Enable the plugin %s.', 'wish-list-for-woocommerce' ), '<strong>' . __( 'Wishlist for WooCommerce', 'wish-list-for-woocommerce' ) . '</strong>' ),
					'id'      => self::OPTION_ENABLED,
					'default' => 'yes',
					'type'    => 'checkbox',
				),

				array(
					'title'    => __( 'Multiple Wishlist', 'wish-list-for-woocommerce' ),
					/* translators: %s: plugin name */
					'desc'     => sprintf( __( 'Enable multiple wishlist for %s.', 'wish-list-for-woocommerce' ), '<strong>' . __( 'Wishlist for WooCommerce', 'wish-list-for-woocommerce' ) . '</strong>' ),
					'desc_tip' => __( 'Enable multi wishlists for each customer.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_MULTIPLE_WISHLIST,
					'default'  => 'no',
					'type'     => 'checkbox',
				),

				array(
					'title'    => __( 'Cache', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Ignore cache by loading the wish list elements via javascript', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Mark this option only if you use some caching plugin, like WP Super Cache, W3 Total Cache or some other.', 'wish-list-for-woocommerce' ) . '<br />'
					              . __( 'Please, clear the cache after you enable this option.', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
					'id'       => self::OPTION_WORK_WITH_CACHE,
				),
				array(
					'title'    => __( 'Nav menu item', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Enable a wish list icon with a counter on the menu', 'wish-list-for-woocommerce' ),
					/* translators: %s: documentation URL */
					'desc_tip' => sprintf( __( "It's necessary to <a target='_blank' href='%s'>add a CSS class</a> 'wish-list-icon' on the menu item", 'wish-list-for-woocommerce' ), 'https://presscustomizr.com/snippet/adding-css-classes-wordpress-menu/' ),
					'type'     => 'checkbox',
					'default'  => 'no',
					'id'       => self::OPTION_WISH_LIST_NAV_MENU_ICON,
				),
				array(
					'title'    => __( 'Variable products', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Allow variations to be added to the wish list', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'It\'s only possible to add one combination of attributes per variation to wish list.', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'yes',
					'id'       => 'alg_wc_wl_allow_variations',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_options',
				),
			);

			$frontend_assets_opts = array(
				array(
					'title' => __( 'Frontend assets', 'wish-list-for-woocommerce' ),
					'desc'  => sprintf( __( 'Choose when the frontend scripts and styles should be loaded. By default they are loaded only when wishlist content is detected with the %s option, which improves performance.', 'wish-list-for-woocommerce' ), __( 'Smart', 'wish-list-for-woocommerce' ) ) . '<br  /><br  />' .
					           sprintf( __( 'In case it doesn\'t work, try the %s or %s options.', 'wish-list-for-woocommerce' ), __( 'All pages', 'wish-list-for-woocommerce' ), __( 'Manual', 'wish-list-for-woocommerce' ) ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_frontend_assets_opts',
				),
				array(
					'title'   => __( 'Loading mode', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_FRONTEND_ASSETS_LOADING_MODE,
					'default' => 'smart',
					'type'    => 'select',
					'class'   => 'chosen_select',
					'options' => array(
						'smart'  => __( 'Smart', 'wish-list-for-woocommerce' ) . ' - ' . __( 'Load automatically (recommended)', 'wish-list-for-woocommerce' ),
						'all'    => __( 'All pages', 'wish-list-for-woocommerce' ) . ' - ' . __( 'Load on every page', 'wish-list-for-woocommerce' ),
						'manual' => __( 'Manual', 'wish-list-for-woocommerce' ) . ' - ' . __( 'Load on specific pages or conditionals', 'wish-list-for-woocommerce' ),
					),
				),
				array(
					'title'   => __( 'Pages', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Load the frontend assets on the selected pages. Only used on Manual mode.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_FRONTEND_ASSETS_PAGES,
					'default' => self::get_default_frontend_assets_pages(),
					'class'   => 'chosen_select',
					'options' => $this->get_pages_options(),
					'type'    => 'multiselect',
				),
				array(
					'title'   => __( 'Conditionals', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Load the frontend assets when any of the selected conditionals is true. Only used on Manual mode.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_FRONTEND_ASSETS_CONDITIONALS,
					'default' => self::get_default_frontend_assets_conditionals(),
					'class'   => 'chosen_select',
					'options' => array(
						'is_front_page'       => __( 'Front page - is_front_page', 'wish-list-for-woocommerce' ),
						'is_home'             => __( 'Blog index - is_home', 'wish-list-for-woocommerce' ),
						'is_woocommerce'      => __( 'WooCommerce page - is_woocommerce', 'wish-list-for-woocommerce' ),
						'is_shop'             => __( 'Shop page - is_shop', 'wish-list-for-woocommerce' ),
						'is_product'          => __( 'Product page - is_product', 'wish-list-for-woocommerce' ),
						'is_product_category' => __( 'Product category archive - is_product_category', 'wish-list-for-woocommerce' ),
						'is_product_tag'      => __( 'Product tag archive - is_product_tag', 'wish-list-for-woocommerce' ),
						'is_cart'             => __( 'Cart page - is_cart', 'wish-list-for-woocommerce' ),
						'is_checkout'         => __( 'Checkout page - is_checkout', 'wish-list-for-woocommerce' ),
						'is_account_page'     => __( 'My account page - is_account_page', 'wish-list-for-woocommerce' ),
						'is_page'             => __( 'Any page - is_page', 'wish-list-for-woocommerce' ),
						'is_singular'         => __( 'Any single post or page - is_singular', 'wish-list-for-woocommerce' ),
						'is_archive'          => __( 'Any archive - is_archive', 'wish-list-for-woocommerce' ),
						'is_search'           => __( 'Search results - is_search', 'wish-list-for-woocommerce' ),
						'is_404'              => __( '404 page - is_404', 'wish-list-for-woocommerce' ),
					),
					'type'    => 'multiselect',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_frontend_assets_opts',
				),
			);

			$font_awesome_opts = array(
				array(
					'title' => __( 'Font Awesome', 'wish-list-for-woocommerce' ),
					'desc'  => __( 'Font Awesome is a library responsible for presenting icons. You only need to enable it here if it\'s not being loaded already from some other plugin or theme.', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_fa',
				),
				array(
					'title'   => __( 'Font Awesome', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Load FontAwesome', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_FONT_AWESOME,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Font Awesome source', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Choose whether Font Awesome is loaded from the bundled copy or from a CDN.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_FONT_AWESOME_SOURCE,
					'default' => 'local',
					'type'    => 'select',
					'options' => array(
						'local'      => __( 'Local (bundled)', 'wish-list-for-woocommerce' ),
						'cdn'        => __( 'CDN (Font Awesome 6.4.2)', 'wish-list-for-woocommerce' ),
						'cdn_latest' => __( 'CDN (latest 6.x version)', 'wish-list-for-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_fa',
				)
			);

			$responsiveness_opts = array(
				array(
					'title' => __( 'Responsiveness', 'wish-list-for-woocommerce' ),
					'desc'  => __( 'Setup breakpoints that will be used to adapt the layout to mobile', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_responsiveness_opts',
				),
				array(
					'title'   => __( 'Max width', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_responsiveness_max_width',
					'default' => 768,
					'type'    => 'number',
				),
				array(
					'title'   => __( 'Max height', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_responsiveness_max_height',
					'default' => 400,
					'type'    => 'number',
				),
				array(
					'title'   => __( 'Evaluation method', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_responsiveness_evaluation_method',
					'default' => 'max_width_or_max_height',
					'options' => array(
						'max_width_or_max_height'  => __( 'Max width or max height', 'wish-list-for-woocommerce' ),
						'max_width_and_max_height' => __( 'Max width and max height', 'wish-list-for-woocommerce' )
					),
					'class'   => 'chosen_select',
					'type'    => 'select',
				),

				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_pro_version_opts',
				)
			);

			return parent::get_settings( array_merge( $settings, $new_settings, $frontend_assets_opts, $font_awesome_opts, $responsiveness_opts ) );

		}

		/**
		 * get_pages_options.
		 *
		 * Returns the site pages for the "Frontend assets" > "Pages" option.
		 *
		 * @version 3.5.1
		 * @since   3.5.1
		 *
		 * @return array
		 */
		public function get_pages_options() {
			$pages_options = array();
			$pages         = get_pages( array( 'numberposts' => -1 ) );
			foreach ( $pages as $page ) {
				/* translators: %1$s: page title, %2$d: page ID */
				$pages_options[ $page->ID ] = sprintf( __( '%1$s (ID %2$d)', 'wish-list-for-woocommerce' ), $page->post_title, $page->ID );
			}

			return $pages_options;
		}

		/**
		 * get_default_frontend_assets_pages.
		 *
		 * Returns the default value for the "Frontend assets" > "Pages" option.
		 *
		 * @version 3.5.1
		 * @since   3.5.1
		 *
		 * @return array
		 */
		public static function get_default_frontend_assets_pages() {
			$pages = array();

			// Values must be strings: WooCommerce compares stored multiselect values strictly (as strings) when rendering the field.
			// Front page.
			$front_page_id = get_option( 'page_on_front', 0 );
			if ( $front_page_id ) {
				$pages[] = (string) $front_page_id;
			}

			// Wishlist page.
			$wishlist_page_id = Alg_WC_Wish_List_Page::get_wish_list_page_id();
			if ( $wishlist_page_id ) {
				$pages[] = (string) $wishlist_page_id;
			}

			return array_values( array_unique( $pages ) );
		}

		/**
		 * get_default_frontend_assets_conditionals.
		 *
		 * Returns the default value for the "Frontend assets" > "Conditionals" option.
		 *
		 * @version 3.5.1
		 * @since   3.5.1
		 *
		 * @return array
		 */
		public static function get_default_frontend_assets_conditionals() {
			return array( 'is_woocommerce', 'is_cart', 'is_checkout', 'is_account_page' );
		}
	}
}