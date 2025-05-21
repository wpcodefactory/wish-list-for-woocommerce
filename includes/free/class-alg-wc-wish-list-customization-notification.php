<?php
/**
 * Wish List for WooCommerce - Notification customization
 *
 * @version 3.2.5
 * @since   2.0.4
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Customization_Notification' ) ) {

	class Alg_WC_Wish_List_Customization_Notification {

		/**
		 * Get custom style for notification
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return string
		 */
		public static function get_notification_custom_style() {
			// Thumb button options
			$bkg_color              = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_BACKGROUND_COLOR ) );
			$txt_color              = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_TEXT_COLOR ) );
			$progress_bar_bkg_color = sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_COLOR ) );

			// Thumb button style
			$custom_css = "
                .iziToast{
                    transition:all 0.2s ease-in-out;
                    background:{$bkg_color} !important;
                }     
                .iziToast *{
                    color:{$txt_color} !important;
                }
                .iziToast>.iziToast-progressbar>div{
                    background:{$progress_bar_bkg_color} !important;
                }
            ";

			return $custom_css;
		}

		/**
		 * Overrides the ajax response when an item is toggled on wish list
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $response
		 *
		 * @return mixed
		 */
		public static function get_toggle_item_ajax_response( $response ) {
			$add_question_on_notification = true;
			if ( ! $add_question_on_notification ) {
				return $response;
			}

			return $response;
		}

		/**
		 * Load notification options in js
		 *
		 * @version 3.2.5
		 * @since   1.0.0
		 */
		public static function localize_script() {
			$options = array(
				'icon_add'    => sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_ICON_ADD, 'fas fa-check' ) ),
				'icon_remove' => sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_ICON_REMOVE, 'fas fa-trash' ) ),
				'progressBar' => filter_var( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_ENABLE, 'yes' ), FILTER_VALIDATE_BOOLEAN ),
				'timeout'     => sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_PROGRESS_BAR_TIME, '7000' ) ),
				'position'    => sanitize_text_field( get_option( Alg_WC_Wish_List_Settings_Style::OPTION_STYLE_NOTIFICATION_POSITION, 'center' ) ),
			);
			if ( ! empty( $options['icon_add'] ) ) {
				$options['icon_add'] = '' . $options['icon_add'];
			}
			if ( ! empty( $options['icon_remove'] ) ) {
				$options['icon_remove'] = '' . $options['icon_remove'];
			}
			if ( ! ( $options['progressBar'] ) ) {
				$options['progressBar'] = 0;
			}

			return $options;
		}

	}

}