<?php
/**
 * Wishlist for WooCommerce - Notification settings
 *
 * @version 2.3.7
 * @since   1.1.1
 * @author  WPFactory
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
		 * @version 2.3.7
		 * @since   1.1.1
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'notification';
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array(
				$this,
				'get_settings'
			), PHP_INT_MAX );
			
			$this->desc = __( 'Popup notifications', 'wish-list-for-woocommerce' );
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
		 * @version 1.1.5
		 * @since   1.1.1
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(
				array(
					'title'     => __( 'Popup notifications options', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => 'Popup notifications options',
					'id'        => 'alg_wc_wl_notification_opt',
				),
				array(
					'title'     => __( 'Desktop', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Enables popup notifications on desktop', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_ENABLE_DESKTOP,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Mobile', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Enables popup notifications on mobile', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_ENABLE_MOBILE,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Wishlist link', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows the wishlist link after adding a product to it', 'wish-list-for-woocommerce' ),
					'id'        => self::OPTION_SHOW_WISH_LIST_LINK,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Ok Button', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Shows the Ok button so you have one more option to close the popup notification', 'wish-list-for-woocommerce' ),
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