<?php
/*
Plugin Name: Wish List for WooCommerce
Description: Wish List for WooCommerce.
Version: 1.0.0-dev
Author: Algoritmika Ltd
Copyright: © 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

// Autoloader without namespace
spl_autoload_register( 'alg_autoloader' );
function alg_autoloader( $class ) {
	if ( false !== strpos( $class, 'Alg_' ) ) {
		$classes_dir = array();
		$plugin_dir_path = realpath( plugin_dir_path( __FILE__ ) ); 
		$classes_dir[0] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
		$classes_dir[1] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR;				
		$class_file = 'class-'.strtolower(str_replace(array('_', "\0"), array('-', ''), $class).'.php');
		foreach ($classes_dir as $key => $dir) {
			if (is_file($file = $dir.'class-'.strtolower(str_replace(array('_', "\0"), array('-', ''), $class).'.php'))) {
	            require_once $file;
	            break;
	        }			
		}
	}
}

if ( ! function_exists( 'alg_wc_wish_list' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Wish_List to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Wish_List
	 */
	function alg_wc_wish_list() {
		return Alg_WC_Wish_List::instance();
	}
}

alg_wc_wish_list();