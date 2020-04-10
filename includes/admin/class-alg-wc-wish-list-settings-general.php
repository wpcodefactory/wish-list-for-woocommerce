<?php
/**
 * Wish List for WooCommerce - General Section Settings
 *
 * @version 1.6.5
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_General' ) ) :

class Alg_WC_Wish_List_Settings_General extends Alg_WC_Wish_List_Settings_Section {

	const OPTION_FONT_AWESOME     = 'alg_wc_wl_fontawesome';
	const OPTION_FONT_AWESOME_URL = 'alg_wc_wl_fontawesome_url';
	const OPTION_ENABLED          = 'alg_wc_wl_enabled';
	const OPTION_METABOX_PRO      = 'alg_wc_wl_cmb_pro';
	const OPTION_ADMIN_AJAX_URL   = 'alg_wc_wl_admin_ajax_url';	

	protected $pro_version_url = 'https://wpcodefactory.com/item/wish-list-woocommerce/';

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function __construct( $handle_autoload = true ) {
		$this->id   = '';
		$this->desc = __( 'General', 'wish-list-for-woocommerce' );
		parent::__construct( $handle_autoload );
	}

	/**
	 * Get possible ajax urls
	 *
	 * @version 1.5.0
	 * @since 1.5.0
	 * @return array
	 */
	function get_possible_ajax_urls(){
		return array(
			admin_url( 'admin-ajax.php', 'relative' ),
			home_url( 'wp-admin/admin-ajax.php' ),
			admin_url( 'admin-ajax.php' ),
			home_url( 'admin-ajax.php' ),
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
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
				'title'          => 'Pro version',
				'enabled'         => !defined( 'ALG_WC_WL_PRO_DIR' ),
				'type'           => 'wccso_metabox',
				'show_in_pro'    => false,
				'accordion' => array(
					'title' => __( 'Take a look on some of its features:', 'wish-list-for-woocommerce' ),
					'items' => array(

						array(
							'trigger'     => __( 'Ignore cache', 'wish-list-for-woocommerce' ),
							'description' => __( 'The Wish list plugin can work just fine even if you use some caching plugin, like WP Super Cache, W3 Total Cache or some other', 'wish-list-for-woocommerce' ),
						),
						array(
							'trigger'     => __( 'Stock alert - Notify users via email when products they added to wish list become available', 'wish-list-for-woocommerce' ),
							'description' => __( 'In other words, products that are out of stock and get restocked.', 'wish-list-for-woocommerce' ),
						),
						array(
							'trigger'     => __( 'As an admin, see what your customers have in their wish list', 'wish-list-for-woocommerce' ),
							'description' => __( 'As an admin, you can see what customers have in their wish list accessing their profile pages', 'wish-list-for-woocommerce' ),
							'img_src'     => plugins_url( '../../assets/images/admin-wish-list.png', __FILE__ ),							
						),
						array(
							'trigger'     => __( 'Choose custom icons from FontAwesome for any of your buttons and notifications', 'wish-list-for-woocommerce' ),
							'description' => sprintf( __( 'Use all <a target="_blank" href="%s">FontAwesome icons</a> at your disposal', 'wish-list-for-woocommerce' ), esc_url( 'http://fontawesome.io/icons/' ) ),
							'img_src'     => plugins_url( '../../assets/images/icons.gif', __FILE__ ),
						),
						array(
							'trigger'     => __( 'Customize the default button in all ways (background and hover color, font weight, size, margin and more)', 'wish-list-for-woocommerce' ),
							'description' => __( 'Customize the icon, size, alignment, color, background, font weight, margin and more. ', 'wish-list-for-woocommerce' ),
							'img_src'     => plugins_url( '../../assets/images/default-btn.gif', __FILE__ ),
						),
						array(
							'trigger'     => __( 'Choose precisely where the thumbnail button will be displayed inside product image and also style it the way you want', 'wish-list-for-woocommerce' ),
							'description' => __( 'Customize the icon, size, position, color and use a heart beat effect optionally and more. ', 'wish-list-for-woocommerce' ),
							'img_src'     => plugins_url( '../../assets/images/thumb-btn-position.gif', __FILE__ ),
						),
						array(
							'trigger'     => __( 'Style your notifications', 'wish-list-for-woocommerce' ),
							'img_src'     => plugins_url( '../../assets/images/notification.gif', __FILE__ ),
						),
						array(
							'trigger'  => __( 'Choose your social icon colors', 'wish-list-for-woocommerce' ),
							'img_src'     => plugins_url( '../../assets/images/social-icons-colors.gif', __FILE__ ),
						),
						array(
							'trigger'  => __( 'Customize all messages displayed to users easily', 'wish-list-for-woocommerce' ),
							'img_src'  => plugins_url( '../../assets/images/texts.png', __FILE__ ),
						),
						array(
							'trigger'  => __( 'Use tooltips to make this plugin even easier to users', 'wish-list-for-woocommerce' ),
							'img_src'  => plugins_url( '../../assets/images/tooltip.png', __FILE__ ),
						),
						array(
							'trigger'  => __( 'Save product attributes on wish list', 'wish-list-for-woocommerce' ),
							'description' => "If an user takes time to select a variable product with multiple terms, these attributes will be displayed on wish list",
							'img_src'  => plugins_url( '../../assets/images/attributes-on-wishlist.png', __FILE__ ),
						),
			            array(
				           'trigger'  => __( 'Display product images in emails', 'wish-list-for-woocommerce' ),
			            ),
						array(
							'trigger'  => __( 'Allow / Disallow Unlogged users from interacting with the Wish List', 'wish-list-for-woocommerce' ),
						),
						array(
							'trigger'  => __( 'Add a column on admin users list informing which customers have added items to the Wish List', 'wish-list-for-woocommerce' ),
						),
						array(
							'trigger'  => __( 'Add a column on the admin products list informing how many times a product has been added to the Wish List', 'wish-list-for-woocommerce' ),
						),
						array(
							'trigger'=>__( 'Support', 'wish-list-for-woocommerce' ),
						),
					),
				),
				'call_to_action' => array(
					'href'   => $this->pro_version_url,
					'label'  => 'Upgrade to Pro version now',
				),
				'description'    => __( 'Do you like the free version of this plugin? Imagine what the Pro version can do for you!', 'wish-list-for-woocommerce' ) . '<br />' . sprintf( __( 'Check it out <a target="_blank" href="%1$s">here</a> or on this link: <a target="_blank" href="%1$s">%1$s</a>', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ),
				'id'             => self::OPTION_METABOX_PRO,
			),
			array(
				'title'       => __( 'Wish List for WooCommerce', 'wish-list-for-woocommerce' ),
				'desc'        => '<strong>' . __( 'Enable', 'wish-list-for-woocommerce' ) . '</strong>',
				'desc_tip'    => __( 'Enable the plugin "Wish List for WooCommerce".', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_ENABLED,
				'default'     => 'yes',
				'type'        => 'checkbox',
			),			
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_options',
			),
			array(
				'title'       => __( 'Font Awesome', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_fa',
			),
			array(
				'title'       => __( 'Load FontAwesome', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'Enable', 'wish-list-for-woocommerce' ),
				'desc_tip'    => __( 'Only mark this if you are not loading Font Awesome nowhere else. Font Awesome is responsible for creating icons', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_FONT_AWESOME,
				'default'     => 'yes',
				'type'        => 'checkbox',
			),
			array(
				'title'       => __( 'FontAwesome URL', 'wish-list-for-woocommerce' ),
				//'desc'        => __( 'Enable', 'wish-list-for-woocommerce' ),
				'desc_tip'    => __( 'The URL address used to load FontAwesome.' ),
				'id'          => self::OPTION_FONT_AWESOME_URL,
				'default'     => 'https://use.fontawesome.com/releases/v5.5.0/css/all.css',
				'type'        => 'url',
			),
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_fa',
			),
			array(
				'title'       => __( 'Advanced', 'wish-list-for-woocommerce' ),
				'type'        => 'title',
				'id'          => 'alg_wc_wl_advanced',
			),
			array(
				'title'       => __( 'Frontend Ajax URL', 'wish-list-for-woocommerce' ),
				'desc'        => __( 'The url of admin-ajax.php file for frontend', 'wish-list-for-woocommerce' ).'<br />'.'<br />'.__( 'Some suggestions:', 'wish-list-for-woocommerce' ).'<br />- '. implode( "<br />- ", array_unique( $this->get_possible_ajax_urls() ) ),
				'desc_tip'    => __( 'No need o worry about this option, unless you notice something is not working like if the wish list is always empty or if you cannot add items to it', 'wish-list-for-woocommerce' ),
				'id'          => self::OPTION_ADMIN_AJAX_URL,
				'class'       => 'regular-input',
				'default'     => '',
				'type'        => 'text',
			),
			array(
				'type'        => 'sectionend',
				'id'          => 'alg_wc_wl_advanced',
			),
		);

		return parent::get_settings( array_merge( $settings, $new_settings ) );
	}

}

endif;