<?php
/**
 * Wish List for WooCommerce Pro - Compatibility.
 *
 * @version 2.0.9
 * @since   2.0.9
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Compatibility' ) ) {

	class Alg_WC_Wish_List_Compatibility {
		/**
		 * init.
		 *
		 * @version 2.0.9
		 * @since   2.0.9
		 */
		function init() {
			$this->add_compatibility_with_the7_theme();
		}

		/**
		 * add_compatibility_with_the7_theme.
		 *
		 * @version 2.0.9
		 * @since   2.0.9
		 */
		function add_compatibility_with_the7_theme() {
			add_action( 'init', array( $this, 'add_ti_fake_constant' ) );
			add_shortcode( 'ti_wishlists_addtowishlist', array( $this, 'replace_ti_wishlist_by_ours' ) );
		}

		/**
		 * add_ti_fake_constant.
		 *
		 * @version 2.0.9
		 * @since   2.0.9
		 */
		function add_ti_fake_constant() {
			if ( ! is_admin() && 'yes' === get_option( 'alg_wc_wl_the7_ti_wishlist_replace_shortcode', 'no' ) ) {
				define( 'TINVWL_FVERSION', 'fake-version' );
			}
		}

		/**
		 * replace_ti_wishlist_by_ours.
		 *
		 * @version 2.0.9
		 * @since   2.0.9
		 *
		 * @param $atts
		 *
		 * @return string
		 */
		function replace_ti_wishlist_by_ours( $atts ) {
			if ( 'yes' === get_option( 'alg_wc_wl_the7_ti_wishlist_replace_shortcode', 'no' ) ) {
				return do_shortcode( '[alg_wc_wl_toggle_item]' );
			}
		}
	}
}