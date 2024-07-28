<?php
/**
 * Wish List for WooCommerce Pro - General Section Settings
 *
 * @version 3.0.5
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Style' ) ) {

	class Alg_WC_Wish_List_Settings_Style extends Alg_WC_Wish_List_Settings_Section {

		// General style options
		const OPTION_STYLE_ENABLE = 'alg_wc_wl_style_enable';

		// Default button options
		const OPTION_STYLE_DEFAULT_BTN_BACKGROUND       = 'alg_wc_wl_style_default_btn_bkg';
		const OPTION_STYLE_DEFAULT_BTN_BACKGROUND_HOVER = 'alg_wc_wl_style_default_btn_bkg_hover';
		const OPTION_STYLE_DEFAULT_BTN_BORDER_RADIUS    = 'alg_wc_wl_style_default_btn_border_radius';
		const OPTION_STYLE_DEFAULT_BTN_ALIGNMENT_SINGLE = 'alg_wc_wl_style_default_btn_align_single';
		const OPTION_STYLE_DEFAULT_BTN_ALIGNMENT_LOOP   = 'alg_wc_wl_style_default_btn_align_loop';
		const OPTION_STYLE_DEFAULT_BTN_TEXT_COLOR       = 'alg_wc_wl_style_default_btn_txt_color';
		const OPTION_STYLE_DEFAULT_BTN_TEXT_WEIGHT      = 'alg_wc_wl_style_default_btn_txt_weight';
		const OPTION_STYLE_DEFAULT_BTN_FONT_SIZE        = 'alg_wc_wl_style_default_btn_font_size';
		const OPTION_STYLE_DEFAULT_BTN_ICON             = 'alg_wc_wl_style_default_btn_icon';
		const OPTION_STYLE_DEFAULT_BTN_ICON_ADDED       = 'alg_wc_wl_style_default_btn_icon_added';
		const OPTION_STYLE_DEFAULT_BTN_MARGIN_SINGLE    = 'alg_wc_wl_style_default_btn_margin_single';
		const OPTION_STYLE_DEFAULT_BTN_MARGIN_LOOP      = 'alg_wc_wl_style_default_btn_margin_loop';

		// Thumb button options
		const OPTION_STYLE_THUMB_BTN_PULSATE            = 'alg_wc_wl_style_thumb_btn_pulsate';
		const OPTION_STYLE_THUMB_BTN_ICON               = 'alg_wc_wl_style_thumb_btn_icon';
		const OPTION_STYLE_THUMB_BTN_ICON_ADDED         = 'alg_wc_wl_style_thumb_btn_icon_added';
		const OPTION_STYLE_THUMB_BTN_COLOR              = 'alg_wc_wl_style_thumb_btn_color';
		const OPTION_STYLE_THUMB_BTN_COLOR_ENABLED      = 'alg_wc_wl_style_thumb_btn_color_enabled';
		const OPTION_STYLE_THUMB_BTN_COLOR_HOVER        = 'alg_wc_wl_style_thumb_btn_color_hover';
		const OPTION_STYLE_THUMB_BTN_FONT_SIZE_SINGLE   = 'alg_wc_wl_style_thumb_btn_font_size_single';
		const OPTION_STYLE_THUMB_BTN_FONT_SIZE_LOOP     = 'alg_wc_wl_style_thumb_btn_font_size_loop';
		const OPTION_STYLE_THUMB_BTN_HOVER_SIZE         = 'alg_wc_wl_style_thumb_btn_hover_size';
		const OPTION_STYLE_THUMB_BTN_POSITION           = 'alg_wc_wl_style_thumb_btn_position';
		const OPTION_STYLE_THUMB_BTN_OFFSET_SINGLE      = 'alg_wc_wl_style_thumb_btn_offset_single';
		const OPTION_STYLE_THUMB_BTN_OFFSET_LOOP        = 'alg_wc_wl_style_thumb_btn_offset';
		const OPTION_STYLE_THUMB_BTN_PADDING_SINGLE     = 'alg_wc_wl_style_thumb_btn_padding_single';
		const OPTION_STYLE_THUMB_BTN_PADDING_LOOP       = 'alg_wc_wl_style_thumb_btn_padding_loop';
		const OPTION_STYLE_THUMB_BTN_BACK_LAYER_ENABLE  = 'alg_wc_wl_style_thumb_btn_back_l_enable';
		const OPTION_STYLE_THUMB_BTN_BACK_LAYER_BKG     = 'alg_wc_wl_style_thumb_btn_back_l_bkg';
		const OPTION_STYLE_THUMB_BTN_BACK_LAYER_SIZE    = 'alg_wc_wl_style_thumb_btn_back_l_size';

		// Notification options
		const OPTION_STYLE_NOTIFICATION_BACKGROUND_COLOR    = 'alg_wc_wl_style_notification_bkg_color';
		const OPTION_STYLE_NOTIFICATION_TEXT_COLOR          = 'alg_wc_wl_style_notification_txt_color';
		const OPTION_STYLE_NOTIFICATION_TEXT_SIZE           = 'alg_wc_wl_style_notification_txt_size';
		const OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_ENABLE = 'alg_wc_wl_style_notification_pbar_enable';
		const OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_COLOR  = 'alg_wc_wl_style_notification_pbar_color';
		const OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_TIME   = 'alg_wc_wl_style_notification_pbar_time';
		const OPTION_STYLE_NOTIFICATION_ICON_ADD            = 'alg_wc_wl_style_notification_icon_add';
		const OPTION_STYLE_NOTIFICATION_ICON_REMOVE         = 'alg_wc_wl_style_notification_icon_remove';
		const OPTION_STYLE_NOTIFICATION_POSITION            = 'alg_wc_wl_style_notification_position';

		// Wishlist options
		const OPTION_STYLE_WISH_LIST_SHARE_ICON_COLOR = 'alg_wc_wl_style_wish_list_share_icon_color';
		const OPTION_STYLE_WISH_LIST_SHARE_ICON_COLOR_HOVER = 'alg_wc_wl_style_wish_list_share_icon_color_hover';
		const OPTION_STYLE_WISH_LIST_SHOW_TABLE_TITLES_DESKTOP = 'alg_wc_wl_style_wish_list_t_titles_desktop';
		const OPTION_STYLE_WISH_LIST_SHOW_TABLE_TITLES_MOBILE = 'alg_wc_wl_style_wish_list_t_titles_mobile';
		const OPTION_REMOVE_BTN_ICON_CLASS = 'alg_wc_wl_style_wish_list_remove_btn_icon_class';
		//const OPTION_REMOVE_BTN_ADDITIONAL_ICON_CLASS = 'alg_wc_wl_style_wish_list_remove_btn_additional_icon_class';
		const OPTION_REMOVE_BTN_ICON_COLOR = 'alg_wc_wl_style_wish_list_remove_btn_icon_color';
		const OPTION_REMOVE_BTN_ICON_COLOR_HOVER = 'alg_wc_wl_style_wish_list_remove_btn_icon_color_hover';
		const OPTION_REMOVE_BTN_HOVER_SIZE = 'alg_wc_wl_style_wish_list_remove_btn_icon_hover_size';
		const OPTION_REMOVE_BTN_ICON_FONT_SIZE = 'alg_wc_wl_style_wish_list_remove_btn_icon_font_size';
		
		//Wishlist Multiple Tab
		const OPTION_MULTIPLE_TAB_FONT_COLOR = 'alg_wc_wl_style_wish_list_multiple_tab_font_color';
		const OPTION_MULTIPLE_TAB_BG_COLOR = 'alg_wc_wl_style_wish_list_multiple_tab_bg_color';
		const OPTION_MULTIPLE_TAB_ACTIVE_FONT_COLOR = 'alg_wc_wl_style_wish_list_multiple_tab_active_font_color';
		const OPTION_MULTIPLE_TAB_ACTIVE_BG_COLOR = 'alg_wc_wl_style_wish_list_multiple_tab_active_bg_color';
		const OPTION_MULTIPLE_TAB_DELETE_BUTTON_COLOR = 'alg_wc_wl_style_wish_list_multiple_tab_delete_button_color';
		const OPTION_MULTIPLE_TAB_DELETE_BUTTON_HOVER_COLOR = 'alg_wc_wl_style_wish_list_multiple_tab_delete_button_hover_color';

		// My account tab
		const OPTION_STYLE_MY_ACCOUNT_TAB_ICON_ENABLE = 'alg_wc_wl_style_my_account_tab_icon_enable';
		const OPTION_STYLE_MY_ACCOUNT_TAB_ICON = 'alg_wc_wl_style_my_account_tab_icon';
		const OPTION_STYLE_MY_ACCOUNT_TAB_ICON_ELEMENT = 'alg_wc_wl_style_my_account_tab_icon_element';
		
		// Style Customization
		const OPTION_STYLE_CUSTOMIZED_CSS = 'alg_wc_wl_style_customized_css';
		
		// Multiple Wishlist Modal Color Option
		const OPTION_MULTIPLE_POPUP_BG_COLOR = 'alg_wc_wl_style_popup_bg_color';
		const OPTION_MULTIPLE_POPUP_FONT_COLOR = 'alg_wc_wl_style_popup_font_color';
		const OPTION_MULTIPLE_POPUP_LIST_ITEM_COLOR = 'alg_wc_wl_style_popup_list_item_color';
		const OPTION_MULTIPLE_POPUP_CHECKBOX_CHECKED_COLOR = 'alg_wc_wl_style_popup_checkbox_checked_color';
		const OPTION_MULTIPLE_POPUP_CHECKBOX_UNCHECKED_COLOR = 'alg_wc_wl_style_popup_checkbox_unchecked_color';
		const OPTION_MULTIPLE_POPUP_CHECKBOX_TICK_COLOR = 'alg_wc_wl_style_popup_checkbox_tick_color';
		const OPTION_MULTIPLE_POPUP_BUTTON_COLOR = 'alg_wc_wl_style_popup_button_color';

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'style';
			
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array(
				$this,
				'get_settings'
			), PHP_INT_MAX );
			
			$this->desc = __( 'Style', 'wish-list-for-woocommerce' );
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
		 * @version 3.0.5
		 * @since   1.0.0
		 */
		function get_settings( $settings = null ) {
			$style_section_opts = array(
				array(
					'title' => __( 'Style section', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Section that can be used to override the default wishlist style.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_section_opts',
				),
				array(
					'title'   => __( 'Custom style', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable custom style section', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_ENABLE,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_section_opts',
				),
			);

			$general_opts = array(
				array(
					'title' => __( 'General style options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'General style options.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_general_style_opts',
				),
				array(
					'title'             => __( 'Social icons colors', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Color for social icons' ),
					'id'                => self::OPTION_STYLE_WISH_LIST_SHARE_ICON_COLOR,
					'default'           => '#a0a0a0',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'             => __( 'Social icons hover colors', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Color for social icons' ),
					'id'                => self::OPTION_STYLE_WISH_LIST_SHARE_ICON_COLOR_HOVER,
					'default'           => '#a0a0a0',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_general_style_opts',
				),
			);

			$default_button_opts = array(
				// Default button options
				array(
					'title' => __( 'Default button', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Style for default button', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_default_btn_opt',
				),
				array(
					'title'    => __( 'Icon', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Icon - Normal.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Icon when an item in not on wishlist.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_DEFAULT_BTN_ICON,
					'default'  => 'fas fa-star',
					'class'    => 'alg-wc-wl-icon-picker',
					'type'     => 'text',
				),
				array(
					'desc'     => __( 'Icon - Added.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Icon when an item has been added to wishlist.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_DEFAULT_BTN_ICON_ADDED,
					'default'  => 'fas fa-star',
					'class'    => 'alg-wc-wl-icon-picker',
					'type'     => 'text',
				),
				array(
					'title'             => __( 'Background color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Background color.', 'wish-list-for-woocommerce' ),
					'id'                => self::OPTION_STYLE_DEFAULT_BTN_BACKGROUND,
					'default'           => '#919191',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'desc'              => __( 'Background color - Hover.', 'wish-list-for-woocommerce' ),
					'id'                => self::OPTION_STYLE_DEFAULT_BTN_BACKGROUND_HOVER,
					'default'           => '#bfbfbf',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'             => __( 'Text color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Color for the text and icon. ' ),
					'id'                => self::OPTION_STYLE_DEFAULT_BTN_TEXT_COLOR,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'   => __( 'Font weight', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Font weight for default button text. ' ),
					'id'      => self::OPTION_STYLE_DEFAULT_BTN_TEXT_WEIGHT,
					'default' => 600,
					'type'    => 'number',
				),
				array(
					'title'   => __( 'Border radius', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Use it to make a rounded button.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_DEFAULT_BTN_BORDER_RADIUS,
					'default' => '0',
					'type'    => 'number',
				),
				array(
					'title'   => __( 'Font size', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Default button font size (in pixels)', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_DEFAULT_BTN_FONT_SIZE,
					'default' => '15',
					'type'    => 'number',
				),
				array(
					'title'    => __( 'Margin', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Margin - Single product page. ', 'wish-list-for-woocommerce' ) . '<br />' .
					              __( 'Distance between button and other adjacent elements on single product page. Look for <a target="_blank" href="http://www.w3schools.com/css/css_margin.asp">Margin-shorthand</a> if you want more help', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'E.g: 5px 15px 15px 20px. <br /><br />There are 4 space separated numbers, each one poiting to a direction:<br /><br /> up right down left <br /><br /> Do not forget to write px after each number', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_DEFAULT_BTN_MARGIN_SINGLE,
					'default'  => '0 0 15px 0',
					'type'     => 'text',
				),
				array(
					'desc'     => __( 'Margin - loop.', 'wish-list-for-woocommerce' ) . '<br />' .
					              __( 'Distance between button and other adjacent elements on product loop. Look for <a target="_blank" href="http://www.w3schools.com/css/css_margin.asp">Margin-shorthand</a> if you want more help', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'E.g: 5px 15px 15px 20px. <br /><br />There are 4 space separated numbers, each one poiting to a direction:<br /><br /> up right down left <br /><br /> Do not forget to write px after each number. ', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_DEFAULT_BTN_MARGIN_LOOP,
					'default'  => '10px 0 0 0',
					'type'     => 'text',
				),
				array(
					'title'   => __( 'Alignment', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Alignment - Single product page.' ),
					'id'      => self::OPTION_STYLE_DEFAULT_BTN_ALIGNMENT_SINGLE,
					'default' => 'left',
					'type'    => 'select',
					'options' => array(
						'left'   => __( 'Left', 'wish-list-for-woocommerce' ),
						'right'  => __( 'Right', 'wish-list-for-woocommerce' ),
						'center' => __( 'Center', 'wish-list-for-woocommerce' ),
					)
				),
				array(
					'desc'    => __( 'Alignment - loop', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_DEFAULT_BTN_ALIGNMENT_LOOP,
					'default' => 'center',
					'type'    => 'select',
					'options' => array(
						'left'   => __( 'Left', 'wish-list-for-woocommerce' ),
						'right'  => __( 'Right', 'wish-list-for-woocommerce' ),
						'center' => __( 'Center', 'wish-list-for-woocommerce' ),
					)
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_default_btn_opt',
				),
			);

			$thumb_button_opts = array(
				// Thumb button options
				array(
					'title' => __( 'Thumb button', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Style for thumb button', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_thumb_btn_opt',
				),
				array(
					'title'    => __( 'Icon', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Icon - Normal.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Thumb icon when an item is not on wishlist yet.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_ICON,
					'default'  => 'fas fa-star',
					'type'     => 'text',
					'class'    => 'alg-wc-wl-icon-picker',
				),
				array(
					'desc'     => __( 'Icon - Added.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Thumb icon when an item has been added to wishlist.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_ICON_ADDED,
					'default'  => 'fas fa-star',
					'type'     => 'text',
					'class'    => 'alg-wc-wl-icon-picker',
				),
				array(
					'title'   => __( 'Pulsate', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Make the icon pulsate on mouse over', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_THUMB_BTN_PULSATE,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'             => __( 'Icon color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Icon color - Normal.', 'wish-list-for-woocommerce' ),
					'desc_tip'          => __( 'Thumb button color when a item is not on wishlist. ' ),
					'id'                => self::OPTION_STYLE_THUMB_BTN_COLOR,
					'default'           => '#afafaf',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'desc'              => __( 'Icon color - Added.', 'wish-list-for-woocommerce' ),
					'desc_tip'          => __( 'Thumb button color when an item has been added to wishlist.' ),
					'id'                => self::OPTION_STYLE_THUMB_BTN_COLOR_ENABLED,
					'default'           => '#333333',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'desc'              => __( 'Icon color - Hover.', 'wish-list-for-woocommerce' ),
					'desc_tip'          => __( 'Thumb button color when mouse is over it. ' ),
					'id'                => self::OPTION_STYLE_THUMB_BTN_COLOR_HOVER,
					'default'           => '#7d3f71',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'   => __( 'Position', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Position where thumb button will be displayed (Relative to product thumbnail).', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_THUMB_BTN_POSITION,
					'default' => 'topLeft',
					'type'    => 'select',
					'options' => array(
						'bottomRight' => __( 'Bottom right', 'wish-list-for-woocommerce' ),
						'bottomLeft'  => __( 'Bottom left', 'wish-list-for-woocommerce' ),
						'topRight'    => __( 'Top right', 'wish-list-for-woocommerce' ),
						'topLeft'     => __( 'Top left', 'wish-list-for-woocommerce' ),
					),
				),
				array(
					'title'    => __( 'Hover size', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Icon size on mouse over (in percentage %).', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( '100 is the original size (100%). You have to put any number bigger than that to note any difference. The default is 145', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_HOVER_SIZE,
					'default'  => '145',
					'type'     => 'number',
				),
				array(
					'title'    => __( 'Offset', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Offset - Single product page.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Distance from product thumbnail margin on single product (in pixels)', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_OFFSET_SINGLE,
					'default'  => '17',
					'type'     => 'number',
				),
				array(
					'desc'     => __( 'Offset - Loop.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Distance from product thumbnail margin on loop (in pixels)', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_OFFSET_LOOP,
					'default'  => '17',
					'type'     => 'number',
				),
				array(
					'title'    => __( 'Padding', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Padding - Single product page.', 'wish-list-for-woocommerce' ) . '<br />' .
					              __( 'Fine tune distance from product thumbnail margin on single product.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'E.g: 5px 15px 15px 20px. <br /><br />There are 4 space separated numbers, each one poiting to a direction:<br /><br /> up right down left <br /><br /> Do not forget to write px after each number', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_PADDING_SINGLE,
					'default'  => '0 0 0 0',
					'type'     => 'text',
				),
				array(
					'desc'     => __( 'Padding - Loop.', 'wish-list-for-woocommerce' ) . '<br />' .
					              __( 'Fine tune distance from product thumbnail margin on loop.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'E.g: 5px 15px 15px 20px. <br /><br />There are 4 space separated numbers, each one poiting to a direction:<br /><br /> up right down left <br /><br /> Do not forget to write px after each number', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_PADDING_LOOP,
					'default'  => '0 0 0 0',
					'type'     => 'text',
				),
				array(
					'title'    => __( 'Font size', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Font size - Single product page.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Font size in pixels.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_FONT_SIZE_SINGLE,
					'default'  => '25',
					'type'     => 'number',
				),
				array(
					'desc'     => __( 'Font size - Loop.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Font size in pixels.', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_THUMB_BTN_FONT_SIZE_LOOP,
					'default'  => '20',
					'type'     => 'number',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_thumb_btn_opt',
				),
			);

			$thumb_button_back_layer_opts = array(
				// Thumb button - back layer
				array(
					'title' => __( 'Thumb button - Back layer', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Back circle protecting the thumb icon.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_thumb_backlayer_opt',
				),
				array(
					'title'   => __( 'Enable', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable back layer' ),
					'id'      => self::OPTION_STYLE_THUMB_BTN_BACK_LAYER_ENABLE,
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'             => __( 'Background Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_STYLE_THUMB_BTN_BACK_LAYER_BKG,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'   => __( 'Size', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Width and height in pixels.' ),
					'id'      => self::OPTION_STYLE_THUMB_BTN_BACK_LAYER_SIZE,
					'default' => '28',
					'type'    => 'number'
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_thumb_backlayer_opt',
				),
			);

			$notification_opts = array(
				// Notifications options
				array(
					'title' => __( 'Popup notifications', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Style for notification', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_notification_opt',
				),
				array(
					'title'    => __( 'Add Icon', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Icon when item is successfully added to wishlist.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Click on input field to choose icon', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_NOTIFICATION_ICON_ADD,
					'default'  => 'fas fa-check',
					'type'     => 'text',
					'class'    => 'alg-wc-wl-icon-picker',
				),
				array(
					'title'    => __( 'Remove Icon', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Icon when item is successfully removed from wishlist.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Click on input field to choose icon', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_NOTIFICATION_ICON_REMOVE,
					'default'  => 'fas fa-trash',
					'type'     => 'text',
					'class'    => 'alg-wc-wl-icon-picker',
				),
				array(
					'title'   => __( 'Progress bar', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Enable notification progress bar' ),
					'id'      => self::OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_ENABLE,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'             => __( 'Progress bar color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Progress bar color' ),
					'id'                => self::OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_COLOR,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'             => __( 'Background color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Notification background color', 'wish-list-for-woocommerce' ),
					'id'                => self::OPTION_STYLE_NOTIFICATION_BACKGROUND_COLOR,
					'default'           => '#000000',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'             => __( 'Text color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Notification text color ' ),
					'id'                => self::OPTION_STYLE_NOTIFICATION_TEXT_COLOR,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'    => __( 'Timeout', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Time in milliseconds for closing modal automatically.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Use 0 if you do not want to close it automatically', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_TIME,
					'default'  => '7000',
					'type'     => 'number',
				),
				array(
					'title'   => __( 'Notification position', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Position where notification window will appear (Relative to browser window).', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_NOTIFICATION_POSITION,
					'default' => 'center',
					'type'    => 'select',
					'options' => array(
						'center'       => __( 'Center', 'wish-list-for-woocommerce' ),
						'bottomRight'  => __( 'Bottom right', 'wish-list-for-woocommerce' ),
						'bottomLeft'   => __( 'Bottom left', 'wish-list-for-woocommerce' ),
						'bottomCenter' => __( 'Bottom center', 'wish-list-for-woocommerce' ),
						'topRight'     => __( 'Top right', 'wish-list-for-woocommerce' ),
						'topLeft'      => __( 'Top left', 'wish-list-for-woocommerce' ),
						'topCenter'    => __( 'Top center', 'wish-list-for-woocommerce' ),
					),
					'class'   => 'alg-wc-wl-icon-picker',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_notification_opt',
				),
			);

			$wish_list_table_opts = array(
				// Wishlist options
				array(
					'title' => __( 'Wishlist page table', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Style for the wishlist page table.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_wish_list_opt',
				),
				array(
					'title'         => __( 'Column title', 'wish-list-for-woocommerce' ),
					'desc'          => __( 'Show column title on desktop version', 'wish-list-for-woocommerce' ),
					'id'            => self::OPTION_STYLE_WISH_LIST_SHOW_TABLE_TITLES_DESKTOP,
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					//'title'   => __( 'Show Table Titles - Mobile', 'wish-list-for-woocommerce' ),
					'desc'          => __( 'Show column titles on mobile version', 'wish-list-for-woocommerce' ),
					'id'            => self::OPTION_STYLE_WISH_LIST_SHOW_TABLE_TITLES_MOBILE,
					'default'       => 'yes',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
				),
				array(
					'title'   => __( 'Alignment', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Alignment on mobile.', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_style_wish_list_t_alignment_mobile',
					'type'    => 'select',
					'default' => 'default',
					'options' => array(
						'default' => __( 'Default', 'wish-list-for-woocommerce' ),
						'left'    => __( 'Left', 'wish-list-for-woocommerce' ),
						'right'   => __( 'Right', 'wish-list-for-woocommerce' ),
						'center'  => __( 'Center', 'wish-list-for-woocommerce' ),
					)
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_wish_list_opt',
				),
			);

			$remove_button_opts = array(


				// Wishlist Remove icon options
				array(
					'title' => __( 'Wishlist page remove item button', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Style for the wishlist remove button.', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_wish_list_remove_icon_opt',
				),
				array(
					'title'   => __( 'Icon', 'wish-list-for-woocommerce' ),
					//'desc'     => __( 'Icon - Normal.', 'wish-list-for-woocommerce' ),
					//'desc_tip' => __( 'Icon when an item in not on wishlist.', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_REMOVE_BTN_ICON_CLASS,
					'default' => 'fas fa-times-circle',
					'class'   => 'alg-wc-wl-icon-picker',
					'type'    => 'text',
				),
				/*array(
					'title'   => __( 'Additional class', 'wish-list-for-woocommerce' ),
					'desc'    => sprintf( __( 'You can find more examples of possible font awesome icon classes <a href="%s" target="_blank">here</a>.' ), 'https://fontawesome.com/v4.7.0/examples/' ),
					'id'      => self::OPTION_REMOVE_BTN_ADDITIONAL_ICON_CLASS,
					'default' => 'fa-2x',
					'type'    => 'text',
				),*/
				array(
					'title'             => __( 'Icon color', 'wish-list-for-woocommerce' ),
					'desc'              => __( 'Icon color - Normal.', 'wish-list-for-woocommerce' ),
					'id'                => self::OPTION_REMOVE_BTN_ICON_COLOR,
					'default'           => '#DC3232',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'desc'              => __( 'Icon color - Hover.', 'wish-list-for-woocommerce' ),
					'id'                => self::OPTION_REMOVE_BTN_ICON_COLOR_HOVER,
					'default'           => '#DC3232',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'    => __( 'Size', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Default icon size.', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Default button icon size (in pixels)', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_REMOVE_BTN_ICON_FONT_SIZE,
					'default'  => '30',
					'type'     => 'number',
				),
				array(
					'desc'     => __( 'Hover size - Icon size on mouse over (in percentage %).', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( '100 is the original size (100%). You have to put any number bigger than that to note any difference. The default is 150', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_REMOVE_BTN_HOVER_SIZE,
					'default'  => '150',
					'type'     => 'number',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_wish_list_remove_icon_opt',
				),
			);

			$my_account_tab_options = array(

				// My account tab
				array(
					'title' => __( 'My account tab', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'My account tab', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_my_account_tab',
				),
				array(
					'title'    => __( 'Show icon', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'Shows an icon on my account tab', 'wish-list-for-woocommerce' ),
					'desc_tip' => __( 'Requires Font Awesome', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_STYLE_MY_ACCOUNT_TAB_ICON_ENABLE,
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'   => __( 'Icon', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'Tab icon', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_MY_ACCOUNT_TAB_ICON,
					'default' => "f004",
					'type'    => 'text',
					//'class'    => 'alg-wc-wl-icon-picker',
				),
				array(
					'title'   => __( 'Icon element', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'The HTML element where the icon will be placed in', 'wish-list-for-woocommerce' ),
					'id'      => self::OPTION_STYLE_MY_ACCOUNT_TAB_ICON_ELEMENT,
					'default' => 'a',
					'options' => array(
						'li' => 'LI',
						'a'  => 'A'
					),
					'type'    => 'select',
					'class'   => 'chosen-select',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_my_account_tab',
				),
			);
			
			$multiple_wishlist_modal_options = array(

				// My account tab
				array(
					'title' => __( 'Multiple Wishlist Modal Color Option', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'Multiple wishlist popup', 'wish-list-for-woocommerce' ),
					'id'    => 'alg_wc_wl_style_multiple_wishlist_popup',
				),
				array(
					'title'             => __( 'Popup Background Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_BG_COLOR,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Popup Font Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_FONT_COLOR,
					'default'           => '#000',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Popup List Item Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_LIST_ITEM_COLOR,
					'default'           => '#eee',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Checkbox checked Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_CHECKBOX_CHECKED_COLOR,
					'default'           => '#255cd2',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				array(
					'title'             => __( 'Checkbox unchecked Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_CHECKBOX_UNCHECKED_COLOR,
					'default'           => '#47474936',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
					
				array(
					'title'             => __( 'Checkbox tick Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_CHECKBOX_TICK_COLOR,
					'default'           => '#fff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),	
				array(
					'title'             => __( 'Checkbox button Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_POPUP_BUTTON_COLOR,
					'default'           => '#eeeeee',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
					
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_multiple_wishlist_popup',
				),
			);
			
			
			$multiple_wishlist_color_opts = array(
				// Thumb button - back layer
				array(
					'title' => __( 'Multiple Wishlsit TAB color', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					/*'desc'  => __( '', 'wish-list-for-woocommerce' ),*/
					'id'    => 'alg_wc_wl_style_multi_wishlist_opt',
				),
				
				array(
					'title'             => __( 'Tab Background Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_TAB_BG_COLOR,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Tab Font Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_TAB_FONT_COLOR,
					'default'           => '#000',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Tab Active Background Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_TAB_ACTIVE_BG_COLOR,
					'default'           => '#ffffff',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Tab Active Font Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_TAB_ACTIVE_FONT_COLOR,
					'default'           => '#000',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Tab Delete Button Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_TAB_DELETE_BUTTON_COLOR,
					'default'           => '#DC3232',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'title'             => __( 'Tab Delete Button Hover Color', 'wish-list-for-woocommerce' ),
					//'desc'              => __( 'Thumb button color when an item is on wishlist. ' ),
					'id'                => self::OPTION_MULTIPLE_TAB_DELETE_BUTTON_HOVER_COLOR,
					'default'           => '#DC3232',
					'class'             => 'color-picker',
					'type'              => 'text',
					'custom_attributes' => array(
						'data-alpha-enabled' => "true",
					)
				),
				
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_style_multi_wishlist_opt',
				),
			);
			
			$wishlist_customized_css = array(
					// Customized CSS
					array(
						'title' => __( 'Style Customization', 'wish-list-for-woocommerce' ),
						'type'  => 'title',
						/*'desc'  => __( '', 'wish-list-for-woocommerce' ),*/
						'id'    => 'alg_wc_wl_style_customized_css',
					),
					
					array(
						'title'   => __( 'Add customized css', 'wish-list-for-woocommerce' ),
						'desc'    => __( 'Apply customized css for wishlist', 'wish-list-for-woocommerce' ),
						'id'      => self::OPTION_STYLE_CUSTOMIZED_CSS,
						'type'    => 'textarea',
					),
					
					array(
						'type' => 'sectionend',
						'id'   => 'alg_wc_wl_style_customized_css',
					),
				
				);

			return parent::get_settings( array_merge( $settings, array_merge(
				$style_section_opts,
				$general_opts,
				$default_button_opts,
				$thumb_button_opts,
				$thumb_button_back_layer_opts,
				$notification_opts,
				$wish_list_table_opts,
				$remove_button_opts,
				$multiple_wishlist_color_opts,
				$multiple_wishlist_modal_options,
				$wishlist_customized_css,
				$my_account_tab_options,
			) ) );
		}

	}
}