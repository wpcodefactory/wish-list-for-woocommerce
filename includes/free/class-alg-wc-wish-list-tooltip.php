<?php
/**
 * Wish List for WooCommerce - Tooltip
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Tooltip' ) ) {

	class Alg_WC_Wish_List_Tooltip {

		/**
		 * Load scripts and styles for tooltip
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function enqueue_scripts( $suffix = 'min' ) {
			$css_file = 'assets/vendor/balloon-css/css/balloon' . $suffix . '.css';
			$css_ver  = date( "ymd-Gis", filemtime( ALG_WC_WL_DIR . $css_file ) );
			wp_register_style( 'alg-wc-wish-list-pro-balloon-css', ALG_WC_WL_URL . $css_file, array(), $css_ver );
			wp_enqueue_style( 'alg-wc-wish-list-pro-balloon-css' );

			$bkg_color = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_BACKGROUND_COLOR ) );
		}

		/**
		 * Handle tooltip dynamic vars
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @param string $handle What script should be handled.
		 */
		public static function add_inline_script( $handle ) {
			$thumb_button_position = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_THUMB_BTN_POSITION ) );
			$add_label             = esc_attr( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_ADD_TO_WISH_LIST ) ) );
			$remove_label          = esc_attr( sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Texts::OPTION_TEXTS_REMOVE_FROM_WISH_LIST ) ) );
			$script="
				var add_label = '{$add_label}';
				var remove_label = '{$remove_label}';
				var thumb_btn_position = '{$thumb_button_position}';
				jQuery(document).ready(function($){					
					var elements = $('.alg-wc-wl-thumb-btn, .alg-wc-wl-remove-item-from-wl');
					function handleElementsAttr(){
						var label = $(this).hasClass('add') ? add_label : remove_label;
						$(this).attr('data-balloon',label);
					}
					elements.each(handleElementsAttr);
					elements.on('mouseenter',handleElementsAttr);
					var tooltip_position = 'left';
					if(thumb_btn_position.match(/left/i)){
						tooltip_position = 'right';
					}
					$('.alg-wc-wl-thumb-btn').attr('data-balloon-pos',tooltip_position);
				});
			";
			wp_add_inline_script( $handle, $script );
		}
	}

}