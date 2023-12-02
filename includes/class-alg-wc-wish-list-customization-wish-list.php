<?php
/**
 * Wish List for WooCommerce Pro - Wish list customization
 *
 * @version 2.2.1
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Customization_Wish_List' ) ) {

	class Alg_WC_Wish_List_Customization_Wish_List {

		/**
		 * Get custom style for wish list
		 *
		 * @version 2.2.1
		 * @since   1.0.0
		 * @return string
		 */
		public static function get_wish_list_custom_style() {
			// Options
			$icon_color                      = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_WISH_LIST_SHARE_ICON_COLOR ) );
			$titles_desktop_enabled          = get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_WISH_LIST_SHOW_TABLE_TITLES_DESKTOP, 'yes' );
			$titles_mobile_enabled           = get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_WISH_LIST_SHOW_TABLE_TITLES_MOBILE, 'yes' );
			$titles_desktop_style            = 'yes' === $titles_desktop_enabled ? '' : 'display:none !important;';
			$titles_mobile_style             = 'yes' === $titles_mobile_enabled ? '' : 'content:none !important;';
			$remove_btn_icon_color           = get_option( Alg_WC_Wish_List_Settings_Style::OPTION_REMOVE_BTN_ICON_COLOR, '#DC3232' );
			$remove_btn_hover_color          = get_option( Alg_WC_Wish_List_Settings_Style::OPTION_REMOVE_BTN_ICON_COLOR_HOVER, '#DC3232' );
			$remove_btn_hover_size           = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_REMOVE_BTN_HOVER_SIZE, 145 ), FILTER_VALIDATE_INT );
			$remove_btn_hover_size_converted = $remove_btn_hover_size / 100;
			$remove_btn_font_size            = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_REMOVE_BTN_ICON_FONT_SIZE, 30 ), FILTER_VALIDATE_INT );

			// style
			$custom_css = "				
				.alg-wc-wl-remove-item-from-wl i{
					font-size:{$remove_btn_font_size}px;
				}
				.alg-wc-wl-remove-item-from-wl i:first-child{
					color:{$remove_btn_icon_color} !important; 
				}
				.alg-wc-wl-remove-item-from-wl:hover i{
					transform: translateZ(0) scale({$remove_btn_hover_size_converted}, {$remove_btn_hover_size_converted});
					color:{$remove_btn_hover_color} !important;					
				}
                .alg-wc-wl-social-li i{
                    color:{$icon_color} !important;
                }
                .alg-wc-wl-view-table th{{$titles_desktop_style}}
                .alg-wc-wl-responsive .alg-wc-wl-view-table tr td:before{{$titles_mobile_style}}
            ";

			if ( 'default' !== ( $wl_table_align_mobile = get_option( 'alg_wc_wl_style_wish_list_t_alignment_mobile', 'default' ) ) ) {
				$custom_css .= ".alg-wc-wl-responsive .alg-wc-wl-view-table tr td{text-align:{$wl_table_align_mobile} !important}";
			}

			return $custom_css;
		}

		/**
		 * Get custom style for tab icon
		 *
		 * @version 1.5.9
		 * @since   1.3.3
		 * @return string
		 */
		public static function get_tab_icon_custom_style() {
			// Options
			$icon_enabled = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_MY_ACCOUNT_TAB_ICON_ENABLE, 'no' ), FILTER_VALIDATE_BOOLEAN );
			if ( ! $icon_enabled ) {
				return '';
			}

			$element     = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_MY_ACCOUNT_TAB_ICON_ELEMENT ) );
			$element_str = ( $element == 'a' ) ? ' a' : '';

			$icon = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_MY_ACCOUNT_TAB_ICON, 'f004' ) );
			$icon_str = $icon;
			if ( ! preg_match( "/\\\\/", $icon_str ) ) {
				$icon_str = "\\" . $icon_str;
			}

			// style
			$custom_css = "
                .woocommerce-MyAccount-navigation ul li.woocommerce-MyAccount-navigation-link--my-wish-list{$element_str}:before{                    
                    content:\"{$icon_str}\";
                }               
            ";

			return $custom_css;
		}

		/**
		 * Style items that are loading through ajax
		 *
		 * @version 1.2.8
		 * @since   1.2.8
		 */
		public static function style_ajax_items() {
			// Thumb button options
			$work_with_cache = filter_var( get_option( Alg_WC_Wish_List_Settings_General::OPTION_WORK_WITH_CACHE ), FILTER_VALIDATE_BOOLEAN );
			if ( ! $work_with_cache ) {
				return;
			}

			// Thumb button style
			$custom_css = "
				.alg-wc-wl-btn{
					transition: all 0.5s ease-in-out;
					opacity:1;					
				}
				.alg-wc-wl-btn.ajax-loading{
					cursor:default;
					opacity:0;
					pointer-events: none;
				}
			";
			wp_add_inline_style( 'alg-wc-wish-list', $custom_css );
		}

	}
}