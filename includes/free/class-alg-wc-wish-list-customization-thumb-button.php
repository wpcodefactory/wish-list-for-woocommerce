<?php
/**
 * Wish List for WooCommerce Pro - Thumb button customization
 *
 * @version 3.3.9
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Customization_Thumb_Button' ) ) {

	class Alg_WC_Wish_List_Customization_Thumb_Button {

		/**
		 * Get custom style for default button
		 *
		 * @version 3.3.7
		 * @since   1.0.0
		 * @return string
		 */
		public static function get_thumb_button_custom_style() {
			// Thumb button options.
			$thumb_btn_color            = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_COLOR, '#afafaf' ) );
			$thumb_btn_pulsate          = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_PULSATE, 'no' ), FILTER_VALIDATE_BOOLEAN );
			$thumb_btn_color_hover      = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_COLOR_HOVER, '#7d3f71' ) );
			$thumb_btn_color_enabled    = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_COLOR_ENABLED, '#333333' ) );
			$thumb_btn_font_size_single = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_FONT_SIZE_SINGLE, '25' ), FILTER_VALIDATE_INT );
			$thumb_btn_hover_size       = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_HOVER_SIZE, '145' ), FILTER_VALIDATE_INT );
			$thumb_btn_font_size_loop   = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_FONT_SIZE_LOOP, '20' ), FILTER_VALIDATE_INT );
			$thumb_btn_padding_single   = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_PADDING_SINGLE, '0 0 0 0' ) );
			$thumb_btn_padding_loop     = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_PADDING_LOOP, '0 0 0 0' ) );

			// Size.
			$thumb_btn_hover_size_converted = $thumb_btn_hover_size / 100;

			// Thumb button - Back layer.
			$thumb_btn_back_layer           = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_BACK_LAYER_ENABLE, 'no' ), FILTER_VALIDATE_BOOLEAN );
			$thumb_btn_back_layer_bkg_color = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_BACK_LAYER_BKG, '#ffffff' ) );
			$thumb_btn_back_layer_size      = filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_BACK_LAYER_SIZE, 28 ), FILTER_VALIDATE_INT );

			// Thumb button style.
			$thumb_btn_css = "
				.alg-wc-wl-thumb-btn:before, .alg-wc-wl-thumb-btn-shortcode-wrapper:before{
					opacity:{$thumb_btn_back_layer};
				    background:{$thumb_btn_back_layer_bkg_color};
				    width:{$thumb_btn_back_layer_size}px;
				    height:{$thumb_btn_back_layer_size}px;
				}				
				.alg-wc-wl-thumb-btn.add i.fa, .alg-wc-wl-thumb-btn.add i.fas{
			        opacity:1;
			    }
			    .alg-wc-wl-thumb-btn.remove .alg-wc-wl-view-state i:first-child, .alg-wc-wl-icon-wrapper.thumb-btn-style i:first-child, .alg-wc-wl-thumb-btn-shortcode-wrapper .remove .alg-wc-wl-view-state i:first-child{
			        color:{$thumb_btn_color_enabled};
			    }
			    .alg-wc-wl-thumb-btn .alg-wc-wl-view-state i, .alg-wc-wl-thumb-btn-shortcode-wrapper .alg-wc-wl-view-state i{
			        color:{$thumb_btn_color};
			    }
			    .alg-wc-wl-thumb-btn .alg-wc-wl-view-state i:hover, .alg-wc-wl-icon-wrapper.thumb-btn-style i:hover, .alg-wc-wl-thumb-btn-shortcode-wrapper .alg-wc-wl-view-state i:hover{
			        color:{$thumb_btn_color_hover} !important;
			    }
			    .alg-wc-wl-thumb-btn-single div i{
			        font-size:{$thumb_btn_font_size_single}px;			        
			    }
			    .alg-wc-wl-thumb-btn-single{
			        padding:{$thumb_btn_padding_single};
			    }
			    .alg-wc-wl-thumb-btn-loop div i, .alg-wc-wl-thumb-btn-shortcode-wrapper div i{
			        padding:{$thumb_btn_padding_loop};
			        font-size:{$thumb_btn_font_size_loop}px;
			    }			    
			    .alg-wc-wl-btn:hover .alg-wc-wl-view-state i, .alg-wc-wl-btn:hover .alg-wc-wl-view-state i{
			        transform: translateZ(0) scale({$thumb_btn_hover_size_converted}, {$thumb_btn_hover_size_converted}) !important;
			    }
			";

			if ( $thumb_btn_pulsate ) {
				$thumb_btn_css .=
					"					
				    @keyframes pulsate {
					  0,75% {
					    transform: translateZ(0) scale(1, 1);
					  }
					
					  75%,100% {
					    transform: translateZ(0) scale({$thumb_btn_hover_size_converted}, {$thumb_btn_hover_size_converted});
					  }
					}					
					.alg-wc-wl-btn:hover i.fa:first-child, .alg-wc-wl-btn:hover i.fas:first-child{	
						transform: none;
					    animation: pulsate 0.34s infinite alternate 
					}
				";
			}

			return $thumb_btn_css;
		}

		/**
		 * Localize custom style params.
		 *
		 * @version 3.3.9
		 * @since   1.0.0
		 *
		 */
		public static function get_custom_style_dynamic_params() {
			$params = array(
				'position'                        => sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_POSITION, 'topLeft' ) ),
				'offset_loop'                     => filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_OFFSET_LOOP, '17' ), FILTER_VALIDATE_INT ),
				'offset_single'                   => filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_OFFSET_SINGLE, '17' ), FILTER_VALIDATE_INT ),
			);
			return $params;
		}

		/**
		 * Localize general params.
		 *
		 * @version 3.3.9
		 * @since   3.3.8
		 *
		 * @return array
		 */
		public static function get_general_dynamic_params() {
			$params = array(
				'img_wrapper_guess_levels_single' => filter_var( get_option( Alg_WC_Wish_List_Settings_Buttons::IMAGE_WRAPPER_GUESSING_LEVELS_SINGLE, 2 ), FILTER_VALIDATE_INT ),
				'guide_img_selector'              => sanitize_text_field( get_option( 'alg_wc_wl_thumb_btn_guide_img_selector', 'img.wp-post-image, img.attachment-woocommerce_thumbnail' ) ),
			);
			return $params;
		}

		/**
		 * Changes buttons params based on admin settings
		 *
		 * @version 3.3.7
		 * @since   1.0.0
		 *
		 * @param $path
		 *
		 * @param $params
		 * @param $final_file
		 *
		 * @return mixed
		 */
		public static function handle_button_params( $params, $final_file, $path ) {
			$thumb_btn_icon                 = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_ICON,'fas fa-star' ) );
			$thumb_btn_icon_added           = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_ICON_ADDED,'fas fa-star' ) );
			$params['btn_icon_class']       = "{$thumb_btn_icon}";
			$params['btn_icon_class_added'] = "{$thumb_btn_icon_added}";

			return $params;
		}

		/**
		 * Returns class name
		 *
		 * @version 1.1.2
		 * @since   1.1.2
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
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
		 *
		 * @return mixed
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