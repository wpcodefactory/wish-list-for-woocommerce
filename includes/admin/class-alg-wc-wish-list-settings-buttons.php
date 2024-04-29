<?php
/**
 * Wish List for WooCommerce Pro - General Section Settings
 *
 * @version 2.3.7
 * @since   1.5.0
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Buttons' ) ) {
	class Alg_WC_Wish_List_Settings_Buttons extends Alg_WC_Wish_List_Settings_Section{
		
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
		
		const OPTION_DEFAULT_BTN_SINGLE_POSITION_OVERRIDE = 'alg_wc_wl_dbtn_single_pos_ovr';
		const OPTION_DEFAULT_BTN_HIDE_BY_TAG = 'alg_wc_wl_dbtn_hide_by_tag';
		const OPTION_THUMB_BTN_HIDE_BY_TAG = 'alg_wc_wl_tbtn_hide_by_tag';
		const IMAGE_WRAPPER_GUESSING_LEVELS_SINGLE = 'alg_wc_wl_img_wrapper_guess_levels_single';
		const OPTION_THUMB_BTN_LOOP_GUTENBERG = 'alg_wc_wl_tbtn_loop_gutenberg';
		const OPTION_ALLOW_UNLOGGED_USERS = 'alg_wc_wl_allow_unlogged';
		const OPTION_UNLOGGED_CAN_SEE_BUTTONS = 'alg_wc_wl_unlogged_can_see_buttons';
		const OPTION_TOOLTIP_ENABLE = 'alg_wc_wl_tooltip_enable';
		
		protected $pro_version_url = 'https://wpcodefactory.com/item/wish-list-woocommerce/';


		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'buttons';
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
			
			$this->desc = __( 'Buttons', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}
		
		/**
		 * get_section_priority.
		 *
		 * @version 2.3.7
		 * @since   2.3.7
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 9;
		}

		/**
		 * get_settings.
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 * @todo    translation via admin (is it recommended?)
		 */
		function get_settings( $settings = array() ) {
			$default_btn = array(
				array(
					'title'      => __( 'Default button', 'wish-list-for-woocommerce' ),
					'type'       => 'title',
					'desc'       => __( 'A button with a text and an icon with the purpose of removing or adding items to the wishlist.', 'wish-list-for-woocommerce' ),
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
					'title'    => __( 'Hide by tag', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Hides the button on products containing these product tags.', 'wish-list-for-woocommerce' ),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'default'  => '',
					'options'  => wp_list_pluck( get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => false ) ), 'name', 'term_id' ),
					'id'       => self::OPTION_DEFAULT_BTN_HIDE_BY_TAG,
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) ),
					'desc'	   => apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) )
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
					'title'    => __( 'Custom Hook', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'This option will give you the freedom to use the hook you want to display the button. If set, will override the "hook" option.', 'wish-list-for-woocommerce' ),
					'type'     => 'text',
					'class'    => 'regular-input',
					'default'  => '',
					'id'       => self::OPTION_DEFAULT_BTN_SINGLE_POSITION_OVERRIDE,
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) ),
					'desc'	   => apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) )
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
					'desc'       => __( 'A button with an icon positioned over the product image, with the purpose of removing or adding items to the wishlist.', 'wish-list-for-woocommerce' ),
					'id'         => 'alg_wc_wl_thumb_btn_opt',
				),
				array(
					'title'      => __( 'Loading icon', 'wish-list-for-woocommerce' ),
					'desc'       => __( 'Show loading icon after clicked, while waiting response', 'wish-list-for-woocommerce' ),
					'id'         => self::OPTION_THUMB_LOADING_ICON,
					'default'    => 'yes',
					'type'       => 'checkbox',
				),
				array(
					'title'    => __( 'Hide by tag', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Hides the button on products containing these product tags.', 'wish-list-for-woocommerce' ),
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'default'  => '',
					'options'  => wp_list_pluck( get_terms( array( 'taxonomy' => 'product_tag', 'hide_empty' => false ) ), 'name', 'term_id' ),
					'id'       => self::OPTION_THUMB_BTN_HIDE_BY_TAG,
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) ),
					'desc'	   => apply_filters( 'alg_wc_wishlist_settings', sprintf( __( 'This is a Pro feature, you will need <a target="_blank" href="%1$s">Wishlist for WooCommerce Pro</a> to enable it.', 'wish-list-for-woocommerce' ), esc_url( $this->pro_version_url ) ) )
				),
				array(
					'title'     => __( 'Tooltip', 'wish-list-for-woocommerce' ),
					'type'      => 'checkbox',
					'default'   => 'no',
					'desc'      => __( 'Show a hint on mouse over to inform what happens if clicked.', 'wish-list-for-woocommerce' ),
					'desc_tip'  => sprintf( __( 'Tooltip texts can be <a href="%s">edited</a>.', 'wish-list-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=alg_wc_wish_list&section=texts' )),
					'id'        => self::OPTION_TOOLTIP_ENABLE,
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
					'title'             => __( 'Image wrapper guessing level', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Number of levels necessary to find the image wrapper.', 'wish-list-for-woocommerce' ),
					'desc_tip'          => __( 'Try to change it if you have issues trying to display the thumb button on the product page.', 'wish-list-for-woocommerce' ),
					'type'              => 'number',
					'custom_attributes' => array( 'min' => 1, 'max' => 3 ),
					'default'           => 2,
					'id'                => self::IMAGE_WRAPPER_GUESSING_LEVELS_SINGLE
					
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
					'title'    => __( 'Gutenberg compatibility', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Add compatibility with Gutenberg editor', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'For now, only supports the "Products by Category" block. As a workaround to display all products, simply select all categories.', 'wish-list-for-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
					'id'       => self::OPTION_THUMB_BTN_LOOP_GUTENBERG,
				),
				array(
					'type'       => 'sectionend',
					'id'         => 'alg_wc_wl_thumb_btn_loop_opt',
				),
				array(
					'title' => __( 'Interaction with unlogged Users', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'How users that are not logged in should interact with the wish list buttons.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_unlogged',
				),
				array(
					'title'    => __( 'Add/Remove', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Allow unlogged users to remove/add items to the wish list once the button is clicked', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'If disabled, unlogged users will see the buttons but will receive a message asking to login if they click on it.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_ALLOW_UNLOGGED_USERS,
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Visibility', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Allow unlogged users to see the buttons on the frontend', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_UNLOGGED_CAN_SEE_BUTTONS,
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_unlogged',
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
}