<?php
/**
 * Wish List for WooCommerce - Buttons Section Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Buttons' ) ) :

class Alg_WC_Wish_List_Settings_Buttons extends Alg_WC_Wish_List_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = 'buttons';
		$this->desc = __( 'Buttons', ALG_WC_WL_DOMAIN );
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
				'title'     => __( 'Buttons options', ALG_WC_WL_DOMAIN ),
				'type'      => 'title',
				'id'        => 'alg_wc_wish_list_social_options',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_wish_list_social_options',
			),
		);
		$this->settings = $settings;
		return $settings;
	}

}

endif;