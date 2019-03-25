<?php
/**
 * Wish List for WooCommerce - Buttons Section Settings
 *
 * @version 1.5.5
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Buttons' ) ) :

	class Alg_WC_Wish_List_Settings_Buttons extends Alg_WC_Wish_List_Settings_Section {

		// Default button on single product
		const OPTION_DEFAULT_BTN_SINGLE_ENABLE   = 'alg_wc_wl_dbtn_single_enable';
		const OPTION_DEFAULT_BTN_SINGLE_POSITION = 'alg_wc_wl_dbtn_single_pos';
		const OPTION_DEFAULT_BTN_SINGLE_PRIORITY = 'alg_wc_wl_dbtn_single_pri';

		// Default button on product loop
		const OPTION_DEFAULT_BTN_LOOP_ENABLE     = 'alg_wc_wl_dbtn_loop_enable';
		const OPTION_DEFAULT_BTN_LOOP_POSITION   = 'alg_wc_wl_dbtn_loop_post';
		const OPTION_DEFAULT_BTN_LOOP_PRIORITY   = 'alg_wc_wl_dbtn_loop_pri';

		// Default button loading icon
		const OPTION_DEFAULT_BTN_LOADING_ICON    = 'alg_wc_wl_dbtn_loading';

		// Default button strings
		const OPTION_DEFAULT_BTN_ADD_TEXT        = 'alg_wc_wl_dbtn_add_text';
		const OPTION_DEFAULT_BTN_REMOVE_TEXT     = 'alg_wc_wl_dbtn_del_text';

		// Thumb button on single product
		const OPTION_THUMB_BTN_SINGLE_ENABLE     = 'alg_wc_wl_tbtn_single_enable';

		// Thumb button loading icon
		const OPTION_THUMB_LOADING_ICON          = 'alg_wc_wl_tbtn_loading';

		// Thumb button on product loop
		const OPTION_THUMB_BTN_LOOP_ENABLE       = 'alg_wc_wl_tbtn_loop_enable';
		const OPTION_THUMB_BTN_LOOP_POSITION     = 'alg_wc_wl_tbtn_loop_position';
		const OPTION_THUMB_BTN_LOOP_PRIORITY     = 'alg_wc_wl_tbtn_loop_priority';

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'buttons';
			$this->desc = __( 'Buttons', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.5.5
		 * @since   1.0.0
		 * @todo    translation via admin (is it recommended?)
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(

				// Default button
				array(
					'title'      => __( 'Default button', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'A default button to toggle wish list items', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_ppage_btn_opt',
				),
				array(
					'title'      => __( 'Single product page', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable button on single product page', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_ENABLE,
					'default'    => 'no',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Position on single', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Where the button will appear on single product page?', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is On single product summary', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_POSITION,
					'default'    => 'woocommerce_single_product_summary',
					'type'       => 'select',
					'options'    => array(
						'woocommerce_single_product_summary'        => __( 'On single product summary', 'wish-list-for-woocommerce' ),
						'woocommerce_before_single_product_summary' => __( 'Before single product summary', 'wish-list-for-woocommerce' ),
						'woocommerce_after_single_product_summary'  => __( 'After single product summary', 'wish-list-for-woocommerce' ),
						'woocommerce_product_thumbnails'            => __( 'After product thumbnail', 'wish-list-for-woocommerce' ),
					),
				),
				array(
					'title'      => __( 'Priority on single', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'More precise control of where the button will appear on single product page', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is 31, right after "add to cart" button ', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_PRIORITY,
					'default'    => 31,
					'type'       => 'number',
					'attributes' => array( 'type' => 'number' ),
				),
				array(
					'title'      => __( 'Product loop', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable button on product loop', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_LOOP_ENABLE,
					'default'    => 'no',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Priority on loop', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'More precise control of where the button will appear on product loop', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_LOOP_PRIORITY,
					'options'    => array(
						'9'  => __( 'Before add to cart button', 'wish-list-for-woocommerce' ),
						'11' => __( 'After add to cart button', 'wish-list-for-woocommerce' ),
					),
					'default'    => '11',
					'type'       => 'select',
				),
				array(
					'title'      => __( 'Show loading icon', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Show loading icon on default button', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_LOADING_ICON,
					'default'    => 'no',
					'type'       => 'checkbox',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_tbtn_btn_opt',
				),

				// Thumb button
				array(
					'title'      => __( 'Thumb button', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'A minimalist button to toggle wish list items on <strong>product thumbnail</strong>', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_ppage_tbtn_opt',
				),
				array(
					'title'      => __( 'Product page', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable the button on product page', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_SINGLE_ENABLE,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Product loop', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable the button on product loop', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_LOOP_ENABLE,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Loop Position', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Hook responsible for displaying the Thumb Button on Loop', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is woocommerce_before_shop_loop_item', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_LOOP_POSITION,
					'default'    => 'woocommerce_before_shop_loop_item',
					'type'       => 'text',
				),
				array(
					'title'      => __( 'Loop Position Priority', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Hook priority for Thumb Button on Loop', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is 9', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_LOOP_PRIORITY,
					'default'    => 9,
					'type'       => 'number',
				),
				array(
					'title'      => __( 'Show loading icon', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Show loading icon on thumb button', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_LOADING_ICON,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_tbtn_tbtn_opt',
				),
			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}

endif;