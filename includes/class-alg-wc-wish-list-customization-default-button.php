<?php
/**
 * Wish List for WooCommerce Pro - Default button customization
 *
 * @version 1.7.9
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Customization_Default_Button' ) ) {

	class Alg_WC_Wish_List_Customization_Default_Button {

		/**
		 * Get custom style for default button
		 *
		 * @version 1.8.2
		 * @since   1.0.0
		 * @return string
		 */
		public static function get_default_button_custom_style() {

			// Default button options
			$default_btn_bkg_color        = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_BACKGROUND ) );
			$default_btn_bkg_color_hover  = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_BACKGROUND_HOVER ) );
			$default_btn_txt_color        = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_TEXT_COLOR ) );
			$default_btn_border_radius    = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_BORDER_RADIUS ), FILTER_VALIDATE_INT );
			$default_btn_font_weight      = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_TEXT_WEIGHT ), FILTER_VALIDATE_INT );
			$default_btn_font_size        = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_FONT_SIZE ), FILTER_VALIDATE_INT );
			$default_btn_alignment_single = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_ALIGNMENT_SINGLE ) );
			$default_btn_alignment_loop   = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_ALIGNMENT_LOOP ) );
			$default_btn_icon_display     = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_ICON ) );
			$default_btn_icon_display_css = $default_btn_icon_display ? 'inline-block' : 'none';
			$default_btn_margin_single    = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_MARGIN_SINGLE ) );
			$default_btn_margin_loop      = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_MARGIN_LOOP ) );

			// Default button style
			$default_btn_css = "				
				.alg-wc-wl-btn-wrapper{
					font-size:0;
					text-align:{$default_btn_alignment_single}
				}
				*.woocommerce [class*='products'] .alg-wc-wl-btn-wrapper, *.woocommerce [class*='grid'] .alg-wc-wl-btn-wrapper{
					text-align:{$default_btn_alignment_loop}
				}
                .alg-wc-wl-toggle-btn{
                    transition:all 0.2s ease-in-out;
                    font-size:{$default_btn_font_size}px !important;
                    display:inline-block !important;
                    background:{$default_btn_bkg_color} !important;
                    border-radius: {$default_btn_border_radius}px !important;
					-moz-border-radius: {$default_btn_border_radius}px !important;
					-webkit-border-radius: {$default_btn_border_radius}px !important;
                }                
                .alg-wc-wl-toggle-btn:hover{
                    background:{$default_btn_bkg_color_hover} !important;
                }
                .alg-wc-wl-btn .alg-wc-wl-btn-text{
                    color:{$default_btn_txt_color} !important;
                    font-weight:$default_btn_font_weight !important;
                }
                .alg-wc-wl-toggle-btn .alg-wc-wl-view-state i{
                    display: {$default_btn_icon_display_css}
                }
            ";

			if ( ! empty( $default_btn_margin_loop ) ) {
				$default_btn_css .= "
				.woocommerce [class*='products'] .product .alg-wc-wl-toggle-btn, .woocommerce [class*='grid'] .product .alg-wc-wl-toggle-btn{
					margin:{$default_btn_margin_loop};
				}
				";
			}

			if ( ! empty( $default_btn_margin_single ) ) {
				$default_btn_css .= "
				.woocommerce .product .alg-wc-wl-toggle-btn{
					margin:{$default_btn_margin_single};
				}
				";
			}

			return $default_btn_css;
		}

		/**
		 * Changes buttons params based on admin settings
		 *
		 * @version 1.7.9
		 * @since   1.0.0
		 * @param $params
		 * @param $final_file
		 * @param $path
		 * @return mixed
		 */
		public static function handle_button_params( $params, $final_file, $path ) {
			$default_btn_icon               = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_ICON ) );
			$default_btn_icon_added         = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_DEFAULT_BTN_ICON_ADDED ) );
			$params['btn_icon_class']       = "{$default_btn_icon}";
			$params['btn_icon_class_added'] = "{$default_btn_icon_added}";
			return $params;
		}

		/**
		 * Override some button texts based on admin settings
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param $params
		 * @param $final_file
		 * @param $path
		 * @return mixed
		 */
		public static function override_button_texts($params, $final_file, $path){
			$params['add_label']    = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_ADD_TO_WISH_LIST ) );
			$params['remove_label'] = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_REMOVE_FROM_WISH_LIST ) );
			return $params;
		}

		/**
		 * Is it necessary to use custom style for default button?
		 *
		 * If default button was not enabled by user, there is no need to load custom style for it
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return bool
		 */
		public static function is_default_button_custom_style_necessary(){
			$is_necessary                    = true;
			$show_default_btn_single_product = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_SINGLE_ENABLE, false );
			$show_default_btn_loop_product   = get_option( Alg_WC_Wish_List_Settings_Buttons::OPTION_DEFAULT_BTN_LOOP_ENABLE, false );
			if (
				filter_var( $show_default_btn_single_product, FILTER_VALIDATE_BOOLEAN ) === false &&
				filter_var( $show_default_btn_loop_product, FILTER_VALIDATE_BOOLEAN ) === false
			) {
				$is_necessary = false;
			}

			return $is_necessary;
		}

		/**
		 * Add ajax loading params
		 *
		 * @version 1.2.8
		 * @since   1.2.8
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 */
		public static function add_ajax_loading_params( $params, $final_file, $path ) {
			$work_with_cache = filter_var( get_option( Alg_WC_Wish_List_Settings_General::OPTION_WORK_WITH_CACHE ), FILTER_VALIDATE_BOOLEAN );
			if ( ! $work_with_cache ) {
				return $params;
			}

			$params['btn_class'] .= ' ajax-loading';
			return $params;
		}
	}
}