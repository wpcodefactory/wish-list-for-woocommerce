<?php
/**
 * Wish List for WooCommerce - Wish list page
 *
 * @version 1.3.3
 * @since   1.0.0
 * @author  Thanks to IT
 */

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Page' ) ) {

	class Alg_WC_Wish_List_Page {

		const PAGE_OPTION='alg_wc_wl_page_id';

		/**
		 * Create a wish list page
		 *
		 * Create a wish list page with a shortcode used for displaying wishlisted items.
		 * This page is only created if it doesn't exist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function create_page() {
			$previous_page_id = self::get_wish_list_page_id();
			$previous_page    = null;
			if ( $previous_page_id !== false ) {
				$previous_page = get_post( $previous_page_id );
			}

			if ( $previous_page == null ) {
				$post = array(
					'post_title'     => __( 'Wish List', 'wish-list-for-woocommerce' ),
					'post_type'      => 'page',
					'post_content'   => '[alg_wc_wl]',
					'post_status'    => 'publish',
					'post_author'    => 1,
					'comment_status' => 'closed'
				);
				// Insert the post into the database.
				$page_id = wp_insert_post( $post );
				self::set_wish_list_page_id( $page_id );
			}
		}

		/**
		 * Delete the wish list page
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function delete_page() {
			$previous_page_id = self::get_wish_list_page_id();
			$previous_page    = null;
			if ( $previous_page_id !== false ) {
				$previous_page = get_post( $previous_page_id );
			}

			if ( $previous_page != null ) {
				wp_delete_post( $previous_page_id, true );
			}
		}

		/**
		 * Set wish list page id
		 *
		 * @version 1.1.4
		 * @since   1.0.0
		 * @param $page_id
		 * @return bool
		 */
		public static function set_wish_list_page_id( $page_id ) {
			return update_option( self::PAGE_OPTION, $page_id );
		}

		/**
		 * Get wish list page id
		 *
		 * @version 1.3.3
		 * @since   1.0.0
		 * @return mixed|void
		 */
		public static function get_wish_list_page_id() {
			if ( function_exists( 'pll_get_post' ) ) {
				return pll_get_post( get_option( self::PAGE_OPTION ) );
			} else {
				return get_option( self::PAGE_OPTION );
			}
		}

	}

}

