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
				array(
					'title'     => __( 'General Options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wish_list_general_options',
				),
				array(
					'title'     => __( 'Wish List Title', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wish_list_title',
					'default'   => __( 'My Wish List', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'css'       => 'min-width: 300px;',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wish_list_general_options',
				),
				array(
					'title'     => __( '"Add to Wish List" Button Options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wish_list_button_options',
				),
				array(
					'title'     => __( 'Button Type', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wish_list_button_type',
					'default'   => 'button',
					'type'      => 'select',
					'options'   => array(
						'button' => __( 'Button', 'wish-list-for-woocommerce' ),
						'link'   => __( 'Text Link', 'wish-list-for-woocommerce' ),
					),
				),
				array(
					'title'     => __( 'Button Text', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wish_list_button_text',
					'default'   => __( 'Add to Wish List', 'wish-list-for-woocommerce' ),
					'type'      => 'text',
					'css'       => 'min-width: 300px;',
				),
				array(
					'title'     => __( 'Button Position(s)', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wish_list_button_position',
					'default'   => 'single_after_add_to_cart',
					'type'      => 'multiselect',
					'options'   => array(
						'single_after_add_to_cart' => __( 'Single product page: after "Add to Cart" button', 'wish-list-for-woocommerce' ),
						'single_after_summary'     => __( 'Single product page: after product summary', 'wish-list-for-woocommerce' ),
						'loop_after_summary'       => __( 'Category (archive) page: after "Add to Cart" button', 'wish-list-for-woocommerce' ),
					),
					'class'     => 'chosen_select',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wish_list_button_options',
				),
			),
			$settings
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Wish_List_Settings_General();
