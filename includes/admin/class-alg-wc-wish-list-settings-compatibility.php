<?php
/**
 * Wish List for WooCommerce - Compatibility.
 *
 * @version 2.3.7
 * @since   2.0.9
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Settings_Compatibility' ) ) :

	class Alg_WC_Wish_List_Settings_Compatibility extends Alg_WC_Wish_List_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.3.7
		 * @since   2.0.9
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = 'compatibility';
			$this->desc = __( 'Compatibility', 'wish-list-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_section_priority.
		 *
		 * @version 2.3.7
		 * @since   2.0.9
		 *
		 * @return int
		 */
		function get_section_priority() {
			return 11;
		}

		/**
		 * get_settings.
		 *
		 * @version 2.0.9
		 * @since   2.0.9
		 */
		function get_settings( $settings = array() ) {
			$the7_compatibilty_opts = array(
				// JS Updater Events
				array(
					'title' => __( 'The7', 'cost-of-goods-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => sprintf( __( 'Compatibility with %s theme.', 'cost-of-goods-for-woocommerce' ), '<a href="https://the7.io/" target="_blank">' . __( 'The7', 'cost-of-goods-for-woocommerce' ) . '</a>' ),
					'id'    => 'alg_wc_wl_the7_compatibility_opts',
				),
				array(
					'title'     => __( 'TI WooCommerce Wishlist', 'wish-list-for-woocommerce' ),
					'desc'      => __( 'Replace TI Wishlist shortcode by ours', 'wish-list-for-woocommerce' ),
					'desc_tip'  => sprintf(__( 'The %s shortcode will trigger %s.', 'wish-list-for-woocommerce' ),'<code>[ti_wishlists_addtowishlist]</code>','<code>[alg_wc_wl_toggle_item]</code>'),
					'type'      => 'checkbox',
					'default'   => 'no',
					'id'        => 'alg_wc_wl_the7_ti_wishlist_replace_shortcode',
				),
				array(
					'type'      => 'sectionend',
					'id'        => 'alg_wc_wl_the7_compatibility_opts',
				),
			);
			return parent::get_settings( array_merge( $settings, $the7_compatibilty_opts ) );
		}

		

	}

endif;