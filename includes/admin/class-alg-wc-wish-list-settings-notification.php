<?php
/**
 * Wish List for WooCommerce - Notification settings
 *
 * @version 1.1.5
 * @since   1.1.1
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Notification' ) ) {

	class Alg_WC_Wish_List_Settings_Notification extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_ENABLE_DESKTOP      = 'alg_wc_wl_notification_desktop';
		const OPTION_ENABLE_MOBILE       = 'alg_wc_wl_notification_mobile';
		const OPTION_SHOW_WISH_LIST_LINK = 'alg_wc_wl_notification_wish_list_link';
		const OPTION_SHOW_OK_BUTTON      = 'alg_wc_wl_notification_show_ok_btn';

		/**
		 * Constructor.
		 *
		 * @version 1.1.1
		 * @since   1.1.1
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'notification';
			$this->desc = __( 'Notification', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.1.5
		 * @since   1.1.1
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(
				array(
					'title'     => __( 'Notification options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => 'Notification options',
					'id'        => 'alg_wc_wl_notification_opt',
				),
				array(
					'title'     => __( 'Desktop', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Enables notifications on desktop', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_ENABLE_DESKTOP,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Mobile', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Enables notifications on mobile', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_ENABLE_MOBILE,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Wish list link', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows the wish list link after adding a product to it', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_WISH_LIST_LINK,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Ok Button', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows the Ok button so you have one more option to close the notification', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_OK_BUTTON,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_notification_opt',
				)
			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

	}
}