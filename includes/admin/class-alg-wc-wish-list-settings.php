<?php
/**
 * Wishlist for WooCommerce - Settings
 *
 * @version 3.1.0
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings' ) ) :

class Alg_WC_Wish_List_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 3.1.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_wish_list';
		$this->label = __( 'Wishlist', 'wish-list-for-woocommerce' );
		parent::__construct();

		// Create notice about pro.
		add_action( 'admin_init', array( $this, 'add_promoting_notice' ) );
	}

	/**
	 * add_promoting_notice.
	 *
	 * @version 3.1.0
	 * @since   3.1.0
	 */
	function add_promoting_notice() {
		$promoting_notice = wpfactory_promoting_notice();
		$promoting_notice->set_args( array(
			'url_requirements'              => array(
				'page_filename' => 'admin.php',
				'params'        => array( 'page' => 'wc-settings', 'tab' => $this->id ),
			),
			'enable'                        => true === apply_filters( 'alg_wc_wishlist_settings', true ),
			'optimize_plugin_icon_contrast' => true,
			'template_variables'            => array(
				'%pro_version_url%'    => 'https://wpfactory.com/item/wish-list-woocommerce/',
				'%plugin_icon_url%'    => 'https://ps.w.org/wish-list-for-woocommerce/assets/icon-128x128.png?rev=1884298',
				'%pro_version_title%'  => __( 'Wishlist for WooCommerce Pro', 'wish-list-for-woocommerce' ),
				'%main_text%'          => __( 'Disabled options can be unlocked using <a href="%pro_version_url%" target="_blank"><strong>%pro_version_title%</strong></a>', 'wish-list-for-woocommerce' ),
				'%btn_call_to_action%' => __( 'Upgrade to Pro version', 'wish-list-for-woocommerce' ),
			),
		) );
		$promoting_notice->init();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );
	}



}

endif;


