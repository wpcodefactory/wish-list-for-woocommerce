<?php
/**
 * Wish List for WooCommerce - Buttons Section Settings
 *
 * @version 1.8.0
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
		 * @version 1.8.0
		 * @since   1.0.0
		 * @todo    translation via admin (is it recommended?)
		 */
		function get_settings( $settings = array() ) {
			$default_btn = array(
				array(
					'title'      => __( 'Default button', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'A button with a text and an icon with the purpose of removing or adding items to wish list.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_default_btn_opt',
				),
				array(
					'title'      => __( 'Loading icon', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Show loading icon after clicked, while waiting response', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_LOADING_ICON,
					'default'    => 'no',
					'type'       => 'checkbox',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_default_btn_opt',
				),
			);

			$default_btn_single = array(
				array(
					'title'      => __( 'Default button - Single product', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'Options related to the default button on single product page.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_default_btn_single_opt',
				),
				array(
					'title'      => __( 'Single product page', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable button on single product page', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_ENABLE,
					'default'    => 'no',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Hook', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is On single product summary', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_POSITION,
					'default'    => 'woocommerce_single_product_summary',
					'type'       => 'select',
					'options'    => array(
						'woocommerce_single_product_summary'        => __( 'On single product summary', 'wish-list-for-woocommerce' ),
						'woocommerce_before_single_product_summary' => __( 'Before single product summary', 'wish-list-for-woocommerce' ),
						'woocommerce_after_single_product_summary'  => __( 'After single product summary', 'wish-list-for-woocommerce' ),
						'woocommerce_product_thumbnails'            => __( 'After product thumbnail', 'wish-list-for-woocommerce' ),
						'woocommerce_before_add_to_cart_form'       => __( 'Before add to cart form', 'wish-list-for-woocommerce' ),
						'woocommerce_after_add_to_cart_form'        => __( 'After add to cart form', 'wish-list-for-woocommerce' ),
						'woocommerce_before_add_to_cart_quantity'   => __( 'Before add to cart quantity', 'wish-list-for-woocommerce' ),
						'woocommerce_after_add_to_cart_quantity'    => __( 'After add to cart quantity', 'wish-list-for-woocommerce' ),
					),
				),
				array(
					'title'      => __( 'Hook priority', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Priority, giving a more precise control of where it will be displayed', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Button priority, giving a more precise control of where it will be displayed', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is 31, right after "add to cart" button ', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_SINGLE_PRIORITY,
					'default'    => 31,
					'type'       => 'number',
					'attributes' => array( 'type' => 'number' ),
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_default_btn_single_opt',
				),
			);

			$default_btn_loop = array(
				array(
					'title'      => __( 'Default button - Loop', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'Options related to the default button on loop pages, like shop and archive.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_default_btn_loop_opt',
				),
				array(
					'title'      => __( 'Loop', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable button on product loop', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_LOOP_ENABLE,
					'default'    => 'no',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Hook priority' ),
					'desc'       => __( 'Hook priority, giving more precise control of where it will be displayed.', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_DEFAULT_BTN_LOOP_PRIORITY,
					'options'    => array(
						'9'  => __( '9 - Before add to cart button', 'wish-list-for-woocommerce' ),
						'11' => __( '11 - After add to cart button', 'wish-list-for-woocommerce' ),
					),
					'default'    => '11',
					'type'       => 'select',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_default_btn_loop_opt',
				),
			);

			$thumb_btn = array(
				array(
					'title'      => __( 'Thumb button', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'A button with an icon positioned over the product image, with the purpose of removing or adding items to wish list.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_thumb_btn_opt',
				),
				array(
					'title'      => __( 'Loading icon', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Show loading icon on loop and product page', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_LOADING_ICON,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_thumb_btn_opt',
				),
			);

			$thumb_btn_single = array(
				array(
					'title'      => __( 'Thumb button - Single product', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'Options related to the thumb button on single product page.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_thumb_btn_single_opt',
				),
				array(
					'title'      => __( 'Product page', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable the button on single product page', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_SINGLE_ENABLE,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_thumb_btn_single_opt',
				),
			);

			$thumb_btn_loop = array(
				array(
					'title'      => __( 'Thumb button - Loop', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'Options related to the thumb button on loop pages, like shop and archive.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_thumb_btn_loop_opt',
				),
				array(
					'title'      => __( 'Product loop', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Enable the button on product loop', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_LOOP_ENABLE,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'title'      => __( 'Hook', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Hook responsible for displaying the thumb button.', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is woocommerce_before_shop_loop_item', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_LOOP_POSITION,
					'default'    => 'woocommerce_before_shop_loop_item',
					'type'       => 'text',
				),
				array(
					'title'      => __( 'Hook priority', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Position priority, giving more precise control of where it will be displayed', 'wish-list-for-woocommerce' ),
					'desc_tip'   => __( 'Default is 9', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_BTN_LOOP_PRIORITY,
					'default'    => 9,
					'type'       => 'number',
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_thumb_btn_loop_opt',
				),
			);
			return parent::get_settings( array_merge(
				$settings,
				$default_btn,
				$default_btn_single,
				$default_btn_loop,
				$thumb_btn,
				$thumb_btn_single,
				$thumb_btn_loop
			) );
		}

	}

endif;