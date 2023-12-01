<?php
/**
 * Wishlist for WooCommerce - Array
 *
 * @version 2.0.0
 * @since   2.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Array' ) ) {

	class Alg_WC_Wish_List_Array {

		/**
		 * converts array to string.
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 *
		 * @param $arr
		 * @param array $args
		 *
		 * @return string
		 */
		public static function to_string( $arr, $args = array() ) {
			$args            = wp_parse_args( $args, array(
				'glue'          => '', // Most probably value would be: ', '
				'item_template' => '{value}' //  {key} and {value} allowed
			) );
			$transformed_arr = array_map( function ( $key, $value ) use ( $args ) {
				$item = str_replace( array( '{key}', '{value}' ), array( $key, $value ), $args['item_template'] );

				return $item;
			}, array_keys( $arr ), $arr );

			return implode( $args['glue'], $transformed_arr );
		}

	}
}