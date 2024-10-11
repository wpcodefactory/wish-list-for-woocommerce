<?php
/**
 * Wish List for WooCommerce - Advanced settings.
 *
 * @version 3.1.0
 * @since   2.0.1
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Advanced' ) ) :

	class Alg_WC_Wish_List_Settings_Advanced extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_ADMIN_AJAX_URL   = 'alg_wc_wl_admin_ajax_url';
		const OPTION_WISH_LIST_UPDATER_EVENTS_ENABLE = 'alg_wc_wl_updater_events_enable';
		const OPTION_WISH_LIST_UPDATER_EVENTS = 'alg_wc_wl_updater_events';

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   2.0.1
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'advanced';
			$this->desc = __( 'Advanced', 'wish-list-for-woocommerce' );
			add_filter( 'woocommerce_get_settings_alg_wc_wish_list_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
			
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_section_priority.
		 *
		 * @version 2.0.1
		 * @since   2.0.1
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 99;
		}

		/**
		 * get_settings.
		 *
		 * @version 3.1.0
		 * @since   2.0.1
		 */
		function get_settings( $settings = array() ) {
			$new_settings = array(
				array(
					'title' => __( 'Advanced options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_advanced',
				),
				array(
					'title'    => __( 'Frontend Ajax URL', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'The admin-ajax.php URL used on frontend.', 'wish-list-for-woocommerce' ) . '<br />' . '<br />' . __( 'Some suggestions:', 'wish-list-for-woocommerce' ) . '<br />- ' . implode( "<br />- ", array_unique( $this->get_possible_ajax_urls() ) ),
					'desc_tip' => __( 'No need o worry about this option, unless you notice something is not working like if the wishlist is always empty or if you cannot add items to it', 'wish-list-for-woocommerce' ),
					'id'       => self::OPTION_ADMIN_AJAX_URL,
					'class'    => 'regular-input',
					'default'  => '',
					'type'     => 'text',
				),
				array(
					'title'   => __( 'Guest user data type', 'wish-list-for-woocommerce' ),
					'desc'    => __( 'The way the plugin will get/set the unlogged user id.', 'wish-list-for-woocommerce' ),
					'desc_tip'=> __( 'Try WooCommerce session if you have issues with caching.', 'wish-list-for-woocommerce' ),
					'id'      => 'alg_wc_wl_guest_user_data_type',
					'default' => 'cookie',
					'type'    => 'select',
					'class'   => 'chosen_select',
					'options' => array(
						'wc_session' => __( 'WooCommerce Session', 'url-coupons-for-woocommerce-by-algoritmika' ),
						'cookie'     => __( 'Cookie', 'url-coupons-for-woocommerce-by-algoritmika' ),
					),
				),
				
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_wl_advanced',
				),
				// JS Updater Events.
				array(
					'title'     => __( 'Javascript update events', 'wish-list-for-woocommerce' ),
					'type'      => 'title',
					'desc'      => __( 'Javascript events that will force the Wish List to update.', 'wish-list-for-woocommerce' ),
					'id'        => 'alg_wc_wl_js_updater_events_opt',
				),
				array(
					'title'     => __( 'Javascript update events', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Enable', 'wish-list-for-woocommerce' ),
					'type'      => 'checkbox',
					'default'   => '',
					'id'        => self::OPTION_WISH_LIST_UPDATER_EVENTS_ENABLE,
					'custom_attributes' => apply_filters( 'alg_wc_wishlist_settings', array( 'disabled' => 'disabled' ) )
				),
				array(
					'title'     => __( 'Events', 'wish-list-for-woocommerce' ),
					'desc_tip'  => __( 'Use one line for event.', 'wish-list-for-woocommerce' ),
					'type'      => 'textarea',
					'default'   => self::get_updater_events_default(),
					'id'        => self::OPTION_WISH_LIST_UPDATER_EVENTS,
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_js_updater_events_opt',
				),

				// JS Toggle events.
				array(
					'title' => __( 'Javascript toggle events', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'JavaScript events that toggle items to/from wishlist.', 'wish-list-for-woocommerce' ) . ' ' .
					           sprintf( __( 'Common events related to click: %s.', 'wish-list-for-woocommerce' ), implode( ', ', array_map( function ( $word ) {
						           return '<code>' . $word . '</code>';
					           }, array( 'click', 'dblclick', 'mouseup', 'touchend' ) ) ) ),
					'id'    => 'alg_wc_wl_js_toggle_events_opt',
				),
				array(
					'title'   => __( 'Default toggle events', 'wish-list-for-woocommerce' ),
					'type'    => 'text',
					'default' => 'mouseup,touchend',
					'id'      => 'alg_wc_wl_default_js_toggle_events',
				),
				array(
					'title'   => __( 'Mobile events', 'wish-list-for-woocommerce' ),
					'type'    => 'text',
					'default' => 'mouseup,touchend',
					'id'      => 'alg_wc_wl_mobile_js_toggle_events',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_js_toggle_events_opt',
				),
			);
			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}

		/**
		 * Get possible ajax urls
		 *
		 * @version 1.5.0
		 * @since   1.5.0
		 * @return array
		 */
		function get_possible_ajax_urls(){
			return array(
				admin_url( 'admin-ajax.php', 'relative' ),
				home_url( 'wp-admin/admin-ajax.php' ),
				admin_url( 'admin-ajax.php' ),
				home_url( 'admin-ajax.php' ),
			);
		}

		/**
		 * get_updater_events_default.
		 *
		 * @version 1.7.2
		 * @since   1.7.2
		 */
		static function get_updater_events_default( $implode = true ) {
			$events = array(
				'prdctfltr-reload',
				'yith_infs_added_elem',
				'wpfAjaxSuccess'
			);
			return $implode ? implode( "\n", $events ) : $events;
		}

	}

endif;