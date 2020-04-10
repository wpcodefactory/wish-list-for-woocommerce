<?php
/*
Plugin Name: Wish List for WooCommerce
Description: Let your visitors save and share the products they love on your WooCommerce store with a Wish List.
Version: 1.6.7
Author: Thanks to IT
Author URI: http://github.com/thanks-to-it
Copyright: © 2019 Thanks to IT.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wish-list-for-woocommerce
Domain Path: /languages
WC requires at least: 3.0.0
WC tested up to: 4.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'alg_wc_wl_auto_deactivate' ) ) {

	/**
	 * Auto deactivate the plugin
	 *
	 * @version 1.1.4
	 * @since   1.1.4
	 */
	function alg_wc_wl_auto_deactivate(){
        $wl_plugin = '';
        if(is_multisite()){
            $plugins = get_site_option( 'active_sitewide_plugins', array() );
	        $fl_array = preg_grep("/wish-list-for-woocommerce.php$/", array_keys($plugins));
	        $wl_plugin = reset($fl_array);
        }else{
	        $plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
	        $fl_array = preg_grep("/wish-list-for-woocommerce.php$/", $plugins);
	        if(count($fl_array)>0){
		        $wl_plugin = $plugins[key($fl_array)];
            }
        }
        if(!empty($wl_plugin)){
	        deactivate_plugins( $wl_plugin );

	        // Hide the default "Plugin activated" notice
	        if ( isset( $_GET['activate'] ) ) {
		        unset( $_GET['activate'] );
	        }
        }
	}
}

if ( ! function_exists( 'alg_wc_wl_missing_woocommerce_admin_notice' ) ) {

	/**
	 * Shows a error message about plugin auto deactivation because WooCommerce is not enabled
	 *
	 * @version 1.1.4
	 * @since   1.1.4
	 */
	function alg_wc_wl_missing_woocommerce_admin_notice() {
	    ?>
	    <div class="notice notice-error is-dismissible">
	        <p><?php printf( __( '<strong>Wish list for WooCommerce</strong> was auto deactivated. It requires <a href="%s">WooCommerce</a> in order to work properly.', 'wish-list-for-woocommerce' ), 'https://wordpress.org/plugins/woocommerce/' ); ?></p>
	    </div>
	<?php
	}
}

if ( ! function_exists( 'alg_wc_wl_pro_version_enabled_admin_notice' ) ) {

	/**
	 * Shows a error message about plugin auto deactivation because Pro version is enabled
	 *
	 * @version 1.1.4
	 * @since   1.1.4
	 */
	function alg_wc_wl_pro_version_enabled_admin_notice() {
		?>
	    <div class="notice notice-info is-dismissible">
	        <p><?php _e( '<strong>The free version of Wish list for WooCommerce</strong> was auto deactivated because the Pro version was enabled.', 'wish-list-for-woocommerce' ); ?></p>
	    </div>
		<?php
	}
}

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	add_action('admin_notices', 'alg_wc_wl_missing_woocommerce_admin_notice', 99 );
	add_action('admin_init', 'alg_wc_wl_auto_deactivate' );
	return;
}

// Autoloader without namespace
if ( ! function_exists( 'alg_wc_wl_autoloader' ) ) {

	/**
	 * Autoloads all classes
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   type $class
	 */
	function alg_wc_wl_autoloader( $class ) {
		if ( false !== strpos( $class, 'Alg_WC_Wish_List' ) ) {
			$classes_dir     = array();
			$plugin_dir_path = realpath( plugin_dir_path( __FILE__ ) );
			$classes_dir[0]  = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
			$classes_dir[1]  = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
			$classes_dir[2]  = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR;
			$class_file      = 'class-' . strtolower( str_replace( array( '_', "\0" ), array( '-', '' ), $class ) . '.php' );
			foreach ( $classes_dir as $key => $dir ) {
				$file = $dir . $class_file;
				if ( is_file( $file ) ) {
					require_once $file;
					break;
				}
			}
		}
	}

	spl_autoload_register( 'alg_wc_wl_autoloader' );
}

// Constants
if ( ! defined( 'ALG_WC_WL_DIR' ) ) {
	define( 'ALG_WC_WL_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
}

if ( ! defined( 'ALG_WC_WL_URL' ) ) {
	define( 'ALG_WC_WL_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'ALG_WC_WL_BASENAME' ) ) {
	define( 'ALG_WC_WL_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ALG_WC_WL_FOLDER_NAME' ) ) {
	define( 'ALG_WC_WL_FOLDER_NAME', untrailingslashit( plugin_dir_path( plugin_basename( __FILE__ ) ) ) );
}

// Loads the template
if ( ! function_exists( 'alg_wc_wl_locate_template' ) ) {
	/**
	 * Returns a template.
	 *
	 * Searches For a template on stylesheet directory and if it's not found get this same template on plugin's template folder
	 *
	 * @version 1.3.2
	 * @since   1.0.0
	 * @global  type $woocommerce
	 * @param   type $path
	 * @param   type $params
	 * @return  type
	 */
	function alg_wc_wl_locate_template( $path, $params = null ) {
		$located     = locate_template( array(
			'wish-list-for-woocommerce' . DIRECTORY_SEPARATOR . $path,
		) );
		$plugin_path = ALG_WC_WL_DIR . 'templates' . DIRECTORY_SEPARATOR . $path;
		if ( ! $located && file_exists( $plugin_path ) ) {
			$final_file = $plugin_path;
		} elseif ( $located ) {
			$final_file = $located;
		}
		if ( $params ) {
			$params = apply_filters( 'alg_wc_wl_locate_template_params', $params, $final_file, $path );
			set_query_var( 'params', $params );
		}
		ob_start();		
		$final_file = apply_filters( 'alg_wc_wl_locate_template', $final_file, $params, $path );
		include( $final_file );
		return ob_get_clean();
	}
}

if ( ! function_exists( 'alg_wc_wish_list' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Wish_List_Core to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Wish_List_Core
	 */
	function alg_wc_wish_list() {
		return Alg_WC_Wish_List_Core::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_wl_plugins_loaded' );
if ( ! function_exists( 'alg_wc_wl_plugins_loaded' ) ) {
	function alg_wc_wl_plugins_loaded(){
		// Includes composer dependencies
		require __DIR__ . '/vendor/autoload.php';

		// Check if Wish List for WooCommerce Pro is activated
		if ( function_exists( 'alg_wc_wish_list_pro' ) ) {
			add_action( 'admin_init', 'alg_wc_wl_auto_deactivate' );
			add_action( 'admin_notices', 'alg_wc_wl_pro_version_enabled_admin_notice', 99 );
		}

		$alg_wc_wl = alg_wc_wish_list();
	}
}

// Called when plugin is activated
register_activation_hook( __FILE__, array( Alg_WC_Wish_List_Core::get_class_name(), 'on_install' ) );

// Called when plugin is uninstalled
register_uninstall_hook( __FILE__, array( Alg_WC_Wish_List_Core::get_class_name(), 'on_uninstall' ) );