<?php
/**
 * Wish List for WooCommerce - General Section Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_General' ) ) :

class Alg_WC_Wish_List_Settings_General extends Alg_WC_Wish_List_Settings_Section {

	const OPTION_FONT_AWESOME = 'alg_wc_wl_fontawesome';
	const OPTION_ENABLED      = 'alg_wc_wl_enabled';

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct( $handle_autoload = true ) {
		$this->id   = '';
		$this->desc = __( 'General', 'alg-wish-list-for-woocommerce' );
		parent::__construct( $handle_autoload );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_settings( $settings = null ) {
		$new_settings = array(
			array(
				'title'     => __( 'General options', 'alg-wish-list-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_wc_wl_options',
			),
			array(
				'title'     => __( 'Wish List for WooCommerce.', 'alg-wish-list-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable', 'alg-wish-list-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Enable the plugin "Wish List for WooCommerce".', 'alg-wish-list-for-woocommerce' ),
				'id'        => self::OPTION_ENABLED,
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Load FontAwesome', 'alg-wish-list-for-woocommerce' ),
				'desc'      => __( 'Load most recent version of Font Awesome', 'alg-wish-list-for-woocommerce' ),
				'desc_tip'  => __( 'Only mark this if you are not loading Font Awesome nowhere else. Font Awesome is responsible for creating icons', 'alg-wish-list-for-woocommerce' ),
				'id'        => self::OPTION_FONT_AWESOME,
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_wl_options',
			)
		);

		return parent::get_settings( array_merge( $settings, $new_settings ) );
	}

}

endif;