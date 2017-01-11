<?php
/**
 * Wish List for WooCommerce - Buttons Section Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Buttons' ) ) :

	class Alg_WC_Wish_List_Settings_Buttons extends Alg_WC_Wish_List_Settings_Section {

		//Product page button
		const OPTION_ENABLE_PRODUCT_PAGE_BTN = 'alg_wc_wl_ppage_btn';
		const OPTION_ENABLE_PRODUCT_PAGE_POSITION = 'alg_wc_wl_ppage_pos';
		const OPTION_ENABLE_PRODUCT_PAGE_PRIORITY = 'alg_wc_wl_ppage_priority';

		//Product page thumb button
		const OPTION_ENABLE_PRODUCT_PAGE_THUMB_BUTTON = 'alg_wc_wl_ppage_tbtn';

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
			$settings       = array(
				/*array(
					'title' => __( 'Buttons options', ALG_WC_WL_DOMAIN ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_buttons',
				),*/
				array(
					'title' => __( 'Product page button', ALG_WC_WL_DOMAIN ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_ppage_btn_opt',
				),
				array(
					'title'   => __( 'Enable product page button', ALG_WC_WL_DOMAIN ),
					'desc'    => '<strong>' . __( 'Show a button to toggle wish list items on product page', ALG_WC_WL_DOMAIN ) . '</strong>',
					//'desc_tip'  => __( 'Only mark this if you are not loading Font Awesome nowhere else. Font Awesome is responsible for creating icons', ALG_WC_WL_DOMAIN),
					'id'      => self::OPTION_ENABLE_PRODUCT_PAGE_BTN,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Product page button position', ALG_WC_WL_DOMAIN ),
					'desc'    => '<strong>' . __( 'Where the button will appear?', ALG_WC_WL_DOMAIN ) . '</strong>',
					'desc_tip'  => __( 'Default is On single product summary', ALG_WC_WL_DOMAIN),
					'id'      => self::OPTION_ENABLE_PRODUCT_PAGE_POSITION,
					'default' => 'woocommerce_single_product_summary',
					'type'    => 'select',
					'options' => array(
						'woocommerce_single_product_summary'        => __( 'On single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_before_single_product_summary' => __( 'Before single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_after_single_product_summary'  => __( 'After single product summary', ALG_WC_WL_DOMAIN ),
					),
				),
				array(
					'title'   => __( 'Product page button priority', ALG_WC_WL_DOMAIN ),
					'desc'    => '<strong>' . __( 'More precise control of where the button will appear', ALG_WC_WL_DOMAIN ) . '</strong>',
					'desc_tip'  => __( 'Default is 31, right after "add to cart" button ', ALG_WC_WL_DOMAIN),
					'id'      => self::OPTION_ENABLE_PRODUCT_PAGE_PRIORITY,
					'default' => 31,
					'type'    => 'number',
					'attributes'=>array('type'=>'number'),
					'options' => array(
						'woocommerce_single_product_summary'        => __( 'On single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_before_single_product_summary' => __( 'Before single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_after_single_product_summary'  => __( 'After single product summary', ALG_WC_WL_DOMAIN ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_ppage_btn_opt',
				),
				array(
					'title' => __( 'Product page Thumb button', ALG_WC_WL_DOMAIN ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_ppage_tbtn_opt',
				),
				array(
					'title'   => __( 'Enable product page thumb button', ALG_WC_WL_DOMAIN ),
					'desc'    => '<strong>' . __( 'Show a button to toggle wish list items on the thumbnail of a product page', ALG_WC_WL_DOMAIN ) . '</strong>',
					'id'      => self::OPTION_ENABLE_PRODUCT_PAGE_THUMB_BUTTON,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_ppage_tbtn_opt',
				),
			);
			$this->settings = $settings;

			return $settings;
		}

	}

endif;