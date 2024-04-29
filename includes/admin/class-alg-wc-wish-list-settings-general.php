<?php
/**
 * Wish List for WooCommerce Pro - General Section Settings.
 *
 * @version 2.3.7
 * @since   1.0.0
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'Alg_WC_Wish_List_Settings_General' ) ) {
	class Alg_WC_Wish_List_Settings_General extends Alg_WC_Wish_List_Settings_Section {
		
		const OPTION_FONT_AWESOME     = 'alg_wc_wl_fontawesome';
		const OPTION_FONT_AWESOME_URL = 'alg_wc_wl_fontawesome_url';
		const OPTION_ENABLED          = 'alg_wc_wl_enabled';
		const OPTION_METABOX_PRO      = 'alg_wc_wl_cmb_pro';
		
		const OPTION_MULTIPLE_WISHLIST   	= 'alg_wc_wl_multiple_wishlist_enabled';
		
		// Move to free
		const OPTION_WORK_WITH_CACHE = 'alg_wc_wl_work_with_cache';
		const OPTION_WISH_LIST_NAV_MENU_ICON = 'alg_wc_wl_nav_menu_item';

		protected $pro_version_url = 'https://wpcodefactory.com/item/wish-list-woocommerce/';


		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = '';
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
			
			$this->desc = __( 'General', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
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
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		function get_settings( $settings = null ) {
			
			$new_settings = array(
			array(
				'title'       => __( 'General options', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_options',
			),
			array(
				'title'       => __( 'Wishlist for WooCommerce', 'wish-list-for-woocommerce' ),
				'desc'        => sprintf( __( 'Enable the plugin %s.', 'wish-list-for-woocommerce' ), '<strong>' . __( 'Wishlist for WooCommerce', 'wish-list-for-woocommerce' ) . '</strong>' ),
				'id'          => self::OPTION_ENABLED,
				'default'     => 'yes',
				'type'        => 'checkbox',
			),
			
			array(
				'title'       => __( 'Multiple Wishlist', 'wish-list-for-woocommerce' ),
				'desc'        => sprintf( __( 'Enable multiple wishlist for %s.', 'wish-list-for-woocommerce' ), '<strong>' . __( 'Wishlist for WooCommerce', 'wish-list-for-woocommerce' ) . '</strong>' ),
				'desc_tip'  => __( 'Enable multi wishlists for each customer.', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_MULTIPLE_WISHLIST,
				'default'     => 'no',
				'type'        => 'checkbox',
			),
			
			array(
				'title'     => __( 'Cache', 'wish-list-for-woocommerce' ),
				'desc'      => __( 'Ignore cache by loading the wish list elements via javascript', 'wish-list-for-woocommerce' ),
				'desc_tip'  => __( 'Mark this option only if you use some caching plugin, like WP Super Cache, W3 Total Cache or some other.', 'wish-list-for-woocommerce' ).'<br />'
							   .__( 'Please, clear the cache after you enable this option.', 'wish-list-for-woocommerce' ),
				'type'      => 'checkbox',
				'default'   => 'no',
				'id'        => self::OPTION_WORK_WITH_CACHE,
			),
			array(
				'title'     => __( 'Nav menu item', 'wish-list-for-woocommerce' ),
				'desc'      => __( 'Enable a wish list icon with a counter on the menu', 'wish-list-for-woocommerce' ),
				'desc_tip'  => sprintf(__( "It's necessary to <a target='_blank' href='%s'>add a CSS class</a> 'wish-list-icon' on the menu item", 'wish-list-for-woocommerce' ),'https://presscustomizr.com/snippet/adding-css-classes-wordpress-menu/'),
				'type'      => 'checkbox',
				'default'   => 'no',
				'id'        => self::OPTION_WISH_LIST_NAV_MENU_ICON,
			),
			array(
				'title'     => __( 'Variable products', 'wish-list-for-woocommerce' ),
				'desc'      => __( 'Allow variations to be added to the wish list', 'wish-list-for-woocommerce' ),
				'desc_tip'  => __( 'It\'s only possible to add one combination of attributes per variation to wish list.', 'wish-list-for-woocommerce' ),
				'type'      => 'checkbox',
				'default'   => 'yes',
				'id'        => 'alg_wc_wl_allow_variations',
			),		
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_options',
			),
		);

		$font_awesome_opts = array(
			array(
				'title'       => __( 'Font Awesome', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'Font Awesome is a library responsible for presenting icons. You only need to enable it here if it\'s not being loaded already from some other plugin or theme.', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_fa',
			),
			array(
				'title'       => __( 'Font Awesome', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'Load FontAwesome', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_FONT_AWESOME,
				'default'     => 'yes',
				'type'        => 'checkbox',
			),
			array(
				'title'       => __( 'FontAwesome URL', 'wish-list-for-woocommerce' ),
				//'desc'        => __( 'Enable', 'wish-list-for-woocommerce' ),
				'desc_tip'    => __( 'The URL address used to load FontAwesome.' ),
				'id'          => self::OPTION_FONT_AWESOME_URL,
				'default'     => 'https://use.fontawesome.com/releases/v6.4.2/css/all.css',
				'type'        => 'url',
			),
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_fa',
			)
		);

		$responsiveness_opts = array(
			array(
				'title'       => __( 'Responsiveness', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'Setup breakpoints that will be used to adapt the layout to mobile', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_responsiveness_opts',
			),
			array(
				'title'       => __( 'Max width', 'wish-list-for-woocommerce' ),
				'id'          => 'alg_wc_wl_responsiveness_max_width',
				'default'     => 768,
				'type'        => 'number',
			),
			array(
				'title'       => __( 'Max height', 'wish-list-for-woocommerce' ),
				'id'          => 'alg_wc_wl_responsiveness_max_height',
				'default'     => 400,
				'type'        => 'number',
			),
			array(
				'title'   => __( 'Evaluation method', 'wish-list-for-woocommerce' ),
				'id'      => 'alg_wc_wl_responsiveness_evaluation_method',
				'default' => 'max_width_or_max_height',
				'options' => array(
					'max_width_or_max_height' => __( 'Max width or max height', 'wish-list-for-woocommerce' ),
					'max_width_and_max_height' => __( 'Max width and max height', 'wish-list-for-woocommerce' )
				),
				'class'   => 'chosen_select',
				'type'    => 'select',
			),
			
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_pro_version_opts',
			)
		);

		return parent::get_settings( array_merge( $settings, $new_settings, $font_awesome_opts, $responsiveness_opts ) );
		
		}
	}
}