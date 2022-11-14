<?php
/**
 * Wish List for WooCommerce - Advanced settings.
 *
 * @version 1.8.9
 * @since   1.8.8
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Advanced' ) ) :

	class Alg_WC_Wish_List_Settings_Advanced extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_ADMIN_AJAX_URL   = 'alg_wc_wl_admin_ajax_url';
		
		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'advanced';
			$this->desc = __( 'Advanced', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.8.9
		 * @since   1.8.8
		 */
		function get_settings( $settings = array() ) {
			$advanced_settings = array(
				array(
					'title' => __( 'Advanced options', 'wish-list-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_wl_advanced',
				),
				array(
					'title'    => __( 'Frontend Ajax URL', 'wish-list-for-woocommerce' ),
					'desc'     => __( 'The admin-ajax.php URL used on frontend.', 'wish-list-for-woocommerce' ) . '<br />' . '<br />' . __( 'Some suggestions:', 'wish-list-for-woocommerce' ) . '<br />- ' . implode( "<br />- ", array_unique( $this->get_possible_ajax_urls() ) ),
					'desc_tip' => __( 'No need o worry about this option, unless you notice something is not working like if the wish list is always empty or if you cannot add items to it', 'wish-list-for-woocommerce' ),
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
				)
			);
			return parent::get_settings( array_merge( $settings, $advanced_settings ) );
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

	}

endif;