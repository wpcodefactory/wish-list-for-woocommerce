<?php
/**
 * Wish List for WooCommerce - Sorting.
 *
 * @version 2.3.0
 * @since   2.0.6
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Sorting' ) ) {

	class Alg_WC_Wish_List_Sorting {

		/**
		 * init.
		 *
		 * @version 2.0.6
		 * @since   2.0.6
		 */
		function init() {
			add_filter( 'alg_wc_wl_wish_list', array( $this, 'sort_wish_list_alphabetically' ), 9, 2 );
		}

		/**
		 * sort_wish_list_alphabetically.
		 *
		 * @version 2.3.0
		 * @since   2.0.6
		 *
		 * @param $wish_list
		 * @param $args
		 *
		 * @return mixed
		 */
		function sort_wish_list_alphabetically( $wish_list, $args ) {
			if (
				! empty( $wish_list ) &&
				in_array(
					$sorting_method = get_option( 'alg_wc_wl_sorting_method', 'latest_to_bottom' ),
					array( 'alpha_asc', 'alpha_desc' )
				)
			) {
				$order = 'alpha_asc' === $sorting_method ? 'asc' : 'desc';


				return wc_get_products( array(
					'type'    => array_merge( array_keys( wc_get_product_types() ), array( 'variation' ) ),
					'limit'   => - 1,
					'include' => $wish_list,
					'orderby' => 'title',
					'order'   => $order,
					'return'  => 'ids'
				) );
			}

			return $wish_list;
		}

	}
}