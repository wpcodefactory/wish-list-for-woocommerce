<?php
/**
 * Wish List for WooCommerce Pro - Taxonomies.
 *
 * @version 2.0.3
 * @since   2.0.3
 * @author  WPFactory.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Taxonomies' ) ) {

	class Alg_WC_Wish_List_Taxonomies {
		/**
		 * init.
		 *
		 * @version 2.0.3
		 * @since   2.0.3
		 */
		function init() {
			add_filter( 'alg_wc_wl_locate_template_params', array( $this, 'override_wishlist_params' ), 11, 3 );
		}

		/**
		 * override_wishlist_params.
		 *
		 * @version 2.0.3
		 * @since   2.0.3
		 *
		 * @param $params
		 * @param $final_file
		 * @param $path
		 *
		 * @return array
		 */
		function override_wishlist_params( $params, $final_file, $path ) {
			if ( ! empty( $taxonomies = get_option( 'alg_wc_wl_taxonomies', array() ) ) ) {
				switch ( $path ) {
					case 'wish-list.php':
						$taxes = array();
						foreach ( $taxonomies as $tax_name ) {
							$taxes[ $tax_name ] = get_taxonomy( $tax_name );
						}
						$params['taxonomies'] = $taxes;
						break;
				}
			}
			return $params;
		}
	}
}