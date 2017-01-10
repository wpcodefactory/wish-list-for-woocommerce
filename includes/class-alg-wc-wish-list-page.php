<?php

/**
 * Wish List for WooCommerce - Wish list page
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

if (!class_exists('Alg_WC_Wish_List_Page')) {

	class Alg_WC_Wish_List_Page {

		/**
		 * Create a wishlist page
		 *
		 * Create a wishlist page with a shortcode used for displaying wishlisted items.
		 * This page is only created if it doesn't exist
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function create_page() {
			$previous_page_id	 = get_option('alg_wc_wl_page_id');
			$previous_page		 = null;
			if ($previous_page_id !== false) {
				$previous_page = get_post($previous_page_id);
			}

			if ($previous_page == null) {
				$post	 = array(
					'post_title'	 => __('Wish List', ALG_WC_WL_DOMAIN),
					'post_type'		 => 'page',
					'post_content'	 => '[alg_wc_wl]',
					'post_status'	 => 'publish',
					'post_author'	 => 1,
					'comment_status' => 'closed'
				);
				// Insert the post into the database.
				$page_id = wp_insert_post($post);
				update_option('alg_wc_wl_page_id', $page_id);
			}
		}

	}

}

