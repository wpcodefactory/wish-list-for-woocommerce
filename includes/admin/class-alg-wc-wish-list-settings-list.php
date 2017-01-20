<?php
/**
 * Wish List for WooCommerce - Wish list Section Settings
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_List' ) ) :

	class Alg_WC_Wish_List_Settings_List extends Alg_WC_Wish_List_Settings_Section {

		const OPTION_STOCK = 'alg_wc_wl_lstock';
		const OPTION_PRICE = 'alg_wc_wl_lprice';

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct() {
			$this->id   = 'wish_list';
			$this->desc = __( 'Wish list', 'alg-wish-list-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * get_settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function get_settings() {
			$settings = array(
				array(
					'title'     => __( 'General options', 'alg-wish-list-for-woocommerce' ),
					'type'      => 'title',
					'id'        => 'alg_wc_wl_loptions',
				),
				array(
					'title'     => __( 'Show stock', 'alg-wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product stock on wish list', 'alg-wish-list-for-woocommerce' ),
					'id'        => self::OPTION_STOCK,
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'title'     => __( 'Show price', 'alg-wish-list-for-woocommerce' ),
					'desc'      => __( 'Show product price on wish list', 'alg-wish-list-for-woocommerce' ),
					'id'        => self::OPTION_PRICE,
					'default'   => 'yes',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_loptions',
				)
			);
			$this->settings = $settings;
			return $settings;
		}

	}

endif;