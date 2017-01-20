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

		// Default button on single product
		const OPTION_DEFAULT_BTN_SINGLE_ENABLE = 'alg_wc_wl_dbtn_s_enable';
		const OPTION_DEFAULT_BTN_SINGLE_POSITION = 'alg_wc_wl_dbtn_s_pos';
		const OPTION_DEFAULT_BTN_SINGLE_PRIORITY = 'alg_wc_wl_dbtn_s_pri';

		// Default button on product loop
		const OPTION_DEFAULT_BTN_LOOP_ENABLE = 'alg_wc_wl_dbtn_l_enable';
		const OPTION_DEFAULT_BTN_LOOP_POSITION = 'alg_wc_wl_dbtn_l_post';
		const OPTION_DEFAULT_BTN_LOOP_PRIORITY = 'alg_wc_wl_dbtn_l_pri';

		// Default button strings
		const OPTION_DEFAULT_BTN_ADD_TEXT = 'alg_wc_wl_dbtn_add_text';
		const OPTION_DEFAULT_BTN_REMOVE_TEXT = 'alg_wc_wl_dbtn_del_text';

		// Thumb button on single product
		const OPTION_THUMB_BTN_SINGLE_ENABLE = 'alg_wc_wl_tbtn_s_enable';

		// Thumb button on product loop
		const OPTION_THUMB_BTN_LOOP_ENABLE = 'alg_wc_wl_tbtn_l_enable';

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

				// Default button
				array(
					'title' => __( 'Default button', ALG_WC_WL_DOMAIN ),
					'type'  => 'title',
					'desc'  => __( 'A default button to toggle wish list items', ALG_WC_WL_DOMAIN ),
					'id'    => 'alg_wc_wl_ppage_btn_opt',
				),

				//todo: Translation via admin (is it recommended?)
				/*array(
					'title'   => __( 'Add to wish list', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'Text for adding an item on wish list', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_DEFAULT_BTN_ADD_TEXT,
					'default' => __( 'Add to Wish list', ALG_WC_WL_DOMAIN ),
					'type'    => 'text',
				),
				array(
					'title'   => __( 'Remove from wish list', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'Text for removing an item from wish list', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_DEFAULT_BTN_REMOVE_TEXT,
					'default' => __( 'Remove from Wish list', ALG_WC_WL_DOMAIN ),
					'type'    => 'text',
				),
				*/

				array(
					'title'   => __( 'Single product page', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'Enable button on single product page', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_DEFAULT_BTN_SINGLE_ENABLE,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'    => __( 'Position on single', ALG_WC_WL_DOMAIN ),
					'desc'     => __( 'Where the button will appear on single product page?', ALG_WC_WL_DOMAIN ),
					'desc_tip' => __( 'Default is On single product summary', ALG_WC_WL_DOMAIN ),
					'id'       => self::OPTION_DEFAULT_BTN_SINGLE_POSITION,
					'default'  => 'woocommerce_single_product_summary',
					'type'     => 'select',
					'options'  => array(
						'woocommerce_single_product_summary'        => __( 'On single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_before_single_product_summary' => __( 'Before single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_after_single_product_summary'  => __( 'After single product summary', ALG_WC_WL_DOMAIN ),
						'woocommerce_product_thumbnails'            => __( 'After product thumbnail', ALG_WC_WL_DOMAIN ),
					),
				),
				array(
					'title'      => __( 'Priority on single', ALG_WC_WL_DOMAIN ),
					'desc'       => __( 'More precise control of where the button will appear on single product page', ALG_WC_WL_DOMAIN ),
					'desc_tip'   => __( 'Default is 31, right after "add to cart" button ', ALG_WC_WL_DOMAIN ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_PRIORITY,
					'default'    => 31,
					'type'       => 'number',
					'attributes' => array( 'type' => 'number' ),
				),
				array(
					'title'   => __( 'Product loop', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'Enable button on product loop', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_DEFAULT_BTN_LOOP_ENABLE,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Priority on loop', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'More precise control of where the button will appear on product loop', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_DEFAULT_BTN_LOOP_PRIORITY,
					'options' => array(
						'9'  => __( 'Before add to cart button', ALG_WC_WL_DOMAIN ),
						'11' => __( 'After add to cart button', ALG_WC_WL_DOMAIN ),
					),
					'default' => '11',
					'type'    => 'select',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_tbtn_btn_opt',
				),

				// Thumb button
				array(
					'title' => __( 'Thumb button', ALG_WC_WL_DOMAIN ),
					'type'  => 'title',
					'desc'  => __( 'A minimalist button to toggle wish list items on <strong>product thumbnail</strong>', ALG_WC_WL_DOMAIN ),
					'id'    => 'alg_wc_wl_ppage_tbtn_opt',
				),
				array(
					'title'   => __( 'Product page', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'Enable the button on product page', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_THUMB_BTN_SINGLE_ENABLE,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Product loop', ALG_WC_WL_DOMAIN ),
					'desc'    => __( 'Enable the button on product loop', ALG_WC_WL_DOMAIN ),
					'id'      => self::OPTION_THUMB_BTN_LOOP_ENABLE,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_tbtn_tbtn_opt',
				),
			);
			$this->settings = $settings;

			return $settings;
		}

	}

endif;