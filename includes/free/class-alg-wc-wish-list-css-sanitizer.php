<?php
/**
 * Wish List for WooCommerce Pro - CSS value sanitizer.
 *
 * @version 3.5.0
 * @since   3.5.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_CSS_Sanitizer' ) ) {

	class Alg_WC_Wish_List_CSS_Sanitizer {

		/**
		 * Sanitizes a CSS color value (hex, rgb()/rgba(), hsl()/hsla() or a named color).
		 *
		 * @version 3.5.0
		 * @since   3.5.0
		 *
		 * @param   string  $value    Value to sanitize.
		 * @param   string  $default  Fallback returned for empty or invalid values.
		 *
		 * @return string
		 */
		public static function color( $value, $default ) {
			$value = strtolower( trim( wp_strip_all_tags( $value ) ) );

			if ( '' === $value || strlen( $value ) > 100 ) {
				return $default;
			}
			// Block anything that could break out of the CSS declaration.
			if ( 1 === preg_match( '/[;{}\\\\\"\']|url\s*\(|expression\s*\(|javascript\s*:/i', $value ) ) {
				return $default;
			}
			if ( 1 === preg_match( '/^(#[0-9a-f]{3,8}|rgba?\([^)]*\)|hsla?\([^)]*\)|[a-z]+)$/', $value ) ) {
				return $value;
			}

			return $default;
		}

		/**
		 * Sanitizes a simple CSS value used for sizes, margins and paddings.
		 *
		 * @version 3.5.0
		 * @since   3.5.0
		 *
		 * @param   string  $value    Value to sanitize.
		 * @param   string  $default  Fallback returned for empty or invalid values.
		 *
		 * @return string
		 */
		public static function size( $value, $default ) {
			$value = trim( wp_strip_all_tags( $value ) );

			if ( '' === $value || strlen( $value ) > 50 ) {
				return $default;
			}
			// Digits, letters (px, em, rem, vh, vw, auto), %, dot, whitespace and hyphen only.
			if ( 1 === preg_match( '/[^0-9a-z%.\s-]/i', $value ) ) {
				return $default;
			}

			return $value;
		}

		/**
		 * Sanitizes a CSS keyword against an allowed list.
		 *
		 * @version 3.5.0
		 * @since   3.5.0
		 *
		 * @param   string  $value    Value to sanitize.
		 * @param   array   $allowed  Allowed keywords.
		 * @param   string  $default  Fallback returned for invalid values.
		 *
		 * @return string
		 */
		public static function keyword( $value, array $allowed, $default ) {
			$value = strtolower( trim( (string) $value ) );

			return in_array( $value, $allowed, true ) ? $value : $default;
		}

	}

}
