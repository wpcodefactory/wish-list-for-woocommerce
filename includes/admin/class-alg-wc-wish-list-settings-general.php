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

	const OPTION_FONT_AWESOME='alg_wc_wl_fontawesome';
	const OPTION_ENABLED='alg_wc_wl_enabled';

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', ALG_WC_WL_DOMAIN);
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title' => __( 'General options', ALG_WC_WL_DOMAIN ),
				'type'      => 'title',
				'id'        => 'alg_wc_wl_options',
			),
			array(
				'title'     => __( 'Wish List for WooCommerce.', ALG_WC_WL_DOMAIN),
				'desc'      => '<strong>' . __( 'Enable', ALG_WC_WL_DOMAIN ) . '</strong>',
				'desc_tip'  => __( 'Enable the plugin "Wish List for WooCommerce".', ALG_WC_WL_DOMAIN ),
				'id'        => self::OPTION_ENABLED,
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Load FontAwesome', ALG_WC_WL_DOMAIN ),
				'desc'      => __( 'Load most recent version of Font Awesome', ALG_WC_WL_DOMAIN),
				'desc_tip'  => __( 'Only mark this if you are not loading Font Awesome nowhere else. Font Awesome is responsible for creating icons', ALG_WC_WL_DOMAIN),
				'id'        => self::OPTION_FONT_AWESOME,
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_wl_options',
			)
		);
		$this->settings = $settings;
		return $settings;
	}

}

endif;