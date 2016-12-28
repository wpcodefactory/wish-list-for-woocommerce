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

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'wish-list-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * add_settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_settings( $settings ) {
		$settings = array_merge(
			array(
				array(
					'title'     => __( 'Wish List Options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wish_list_options',
				),
				array(
					'title'     => __( 'WooCommerce Wish List', 'wish-list-for-woocommerce' ),
					'desc'      => '<strong>' . __( 'Enable', 'wish-list-for-woocommerce' ) . '</strong>',
					'desc_tip'  => __( 'Wish List for WooCommerce.', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wish_list_enabled',
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wish_list_options',
				),
			),
			$settings
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Wish_List_Settings_General();
