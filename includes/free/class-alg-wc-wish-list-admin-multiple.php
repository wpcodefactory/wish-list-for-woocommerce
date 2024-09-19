<?php
/**
 * Wishlist for WooCommerce - Admin Multiple Wishlist
 *
 * @version 3.0.8
 * @since   3.0.8
 * @author  WPFactory
 */
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Wish_List_Admin_Multiple' ) ) {

	class Alg_WC_Wish_List_Admin_Multiple {
		
		/**
		 * init.
		 *
		 * @version 3.0.8
		 * @since   3.0.8
		 *
		 */
		function init() {
			if( 'yes' === get_option( 'alg_wc_wl_multiple_wishlist_enabled', 'no' ) ) {
				add_filter( 'alg_wc_wl_locate_template', array( $this, 'admin_profile_template_upgrade_to_multiple_wishlist' ), 99, 3 );
			}
		}
		
		/**
		 * admin_profile_template_upgrade_to_multiple_wishlist.
		 *
		 * @version 3.0.8
		 * @since   3.0.8
		 *
		 * @param 	$final_file, $params, $path
		 *
		 * @return 	$final_file
		 */
		function admin_profile_template_upgrade_to_multiple_wishlist( $final_file, $params, $path ) {
			
			
			if( $path == 'admin-wish-list.php' ) {
				
				$path = 'admin-wish-list-multiple.php';
				
				$located     = locate_template( array(
					ALG_WC_WL_FOLDER_NAME . '/' . $path,
				) );
				
				$plugin_path = ALG_WC_WL_DIR . 'templates' . DIRECTORY_SEPARATOR . $path;
				
				if ( ! $located && file_exists( $plugin_path ) ) {
					$final_file = $plugin_path;
				} elseif ( $located ) {
					$final_file = $located;
				}
		
				return $final_file;
			}
			
			
			return $final_file;
		}
		
		
	}
}