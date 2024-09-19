<?php
/*
Plugin Name: Wishlist for WooCommerce
Plugin URI: https://wpfactory.com/item/wish-list-woocommerce/
Description: Let your visitors show what products they like on your WooCommerce store with a <strong>Wishlist</strong>.
Version: 3.0.8
Author: WPFactory
Author URI: https://wpfactory.com/
Copyright: © 2023 WPFactory.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wish-list-for-woocommerce
Domain Path: /langs
WC requires at least: 3.0.0
WC tested up to: 9.1
Requires Plugins: woocommerce
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Auto deactivate the plugin
 *
 * @version 1.0.1
 * @since   1.0.1
 */
function alg_wc_wl_pro_auto_deactivate(){
	deactivate_plugins( plugin_basename( __FILE__ ) );

	// Hide the default "Plugin activated" notice
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

if ( ! function_exists( 'alg_wc_wl_pro_missing_woocommerce_admin_notice' ) ) {

	/**
	 * Shows a error message about plugin auto deactivation because WooCommerce is not enabled
	 *
	 * @version 1.0.1
	 * @since   1.0.1
	 */
	function alg_wc_wl_pro_missing_woocommerce_admin_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php printf( __( '<strong>Wish list for WooCommerce Pro</strong> was auto deactivated. It requires <a href="%s">WooCommerce</a> in order to work properly.', 'alg-wish-list-for-woocommerce' ), 'https://wordpress.org/plugins/woocommerce/' ); ?></p>
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
	add_action('admin_notices', 'alg_wc_wl_pro_missing_woocommerce_admin_notice', 99 );
	add_action('admin_init','alg_wc_wl_pro_auto_deactivate');
	return;
}

if ( ! function_exists( 'alg_wc_wl_pro_autoloader' ) ) {

	/**
	 * Autoloads all classes
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   type $class
	 */
	function alg_wc_wl_pro_autoloader( $class ) {
		// if ( false !== strpos( $class, 'Alg_WC_Wish_List_Pro' ) ) {
			$classes_dir = array();
			$plugin_dir_path = realpath( plugin_dir_path( __FILE__ ) );
			$classes_dir[0] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
			$classes_dir[1] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
			$classes_dir[2] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR;
			$classes_dir[3] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'free' . DIRECTORY_SEPARATOR;
			$classes_dir[4] = $plugin_dir_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'pro' . DIRECTORY_SEPARATOR;
			$class_file = 'class-' . strtolower( str_replace( array( '_', "\0" ), array(
						'-',
						''
					), $class ) . '.php' );
			foreach ( $classes_dir as $key => $dir ) {
				$file = $dir . $class_file;
				if ( is_file( $file ) ) {
					require_once $file;
					break;
				}
			}
		// }
	}

	spl_autoload_register( 'alg_wc_wl_pro_autoloader' );
}

if ( ! class_exists( 'Alg_WC_Wishlist_For_Woocommerce' ) ) :

/**
 * Main Alg_WC_Wishlist_For_Woocommerce Class
 *
 * @class   Alg_WC_Wishlist_For_Woocommerce
 * @version 2.3.7
 * @since   2.3.7
 */
final class Alg_WC_Wishlist_For_Woocommerce {
	/**
	 * @var   Alg_WC_Wishlist_For_Woocommerce The single instance of the class
	 * @since 2.3.7
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Wishlist_For_Woocommerce Instance
	 *
	 * Ensures only one instance of Alg_WC_Wishlist_For_Woocommerce is loaded or can be loaded.
	 *
	 * @version 2.3.7
	 * @since   2.3.7
	 * @static
	 * @return  Alg_WC_Wishlist_For_Woocommerce - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Wishlist_For_Woocommerce Constructor.
	 *
	 * @version 2.3.7
	 * @since   2.3.7
	 * @access  public
	 */
	function __construct() {

		// Pro
		if ( 'wish-list-for-woocommerce-pro.php' === basename( __FILE__ ) ) {
			require_once( 'includes/pro/class-wish-list-for-woocommerce-pro.php' );
		}
		
	}
}

endif;

if ( ! function_exists( 'alg_wc_wishlist_for_woocommerce' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Wishlist_For_Woocommerce to prevent the need to use globals.
	 *
	 * @version 2.3.7
	 * @since   2.3.7
	 * @return  Alg_WC_Wishlist_For_Woocommerce
	 */
	function alg_wc_wishlist_for_woocommerce() {
		return Alg_WC_Wishlist_For_Woocommerce::instance();
	}
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

if ( ! defined( 'ALG_WC_WL_FILEPATH' ) ) {
	define( 'ALG_WC_WL_FILEPATH', __FILE__ );
}

// Loads the template
if ( ! function_exists( 'alg_wc_wl_pro_locate_template' ) ) {
	/**
	 * Returns a template.
	 *
	 * Searches For a template on stylesheet directory and if it's not found get this same template on plugin's template folder
	 *
	 * @version 1.3.1
	 * @since   1.3.1
	 * @param   $path
	 * @param   $params
	 * @return  string
	 */
	function alg_wc_wl_pro_locate_template( $path, $params = null ) {
		$located     = locate_template( array(
			ALG_WC_WL_FOLDER_NAME . '/' . $path,
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
		extract( $params );
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

add_action( 'plugins_loaded', 'alg_wc_wl_pro_start_plugin' );
if ( ! function_exists( 'alg_wc_wl_pro_start_plugin' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Wish_List_Core to prevent the need to use globals.
	 *
	 * @version 2.3.7
	 * @since   1.3.2
	 * @return  Alg_WC_Wish_List_Core
	 */
	function alg_wc_wl_pro_start_plugin() {
		
		// Loads free version of plugin
		$alg_wc_wl = alg_wc_wish_list();
		
		// Load Pro version 
		alg_wc_wishlist_for_woocommerce();
		
		remove_action( 'plugins_loaded', 'alg_wc_wl_pro_start_plugin' );
	}
}

if ( ! function_exists( 'alg_wc_wl_pro_on_install' ) ) {
	/**
	 * alg_wc_wl_pro_on_install.
	 *
	 * @version   1.9.1
	 * @since     1.9.1
	 */
	function alg_wc_wl_pro_on_install() {
		alg_wc_wl_pro_start_plugin();
		Alg_WC_Wish_List_Core::on_install();
	}
}
register_activation_hook( __FILE__, 'alg_wc_wl_pro_on_install' );

if ( ! function_exists( 'alg_wc_wl_pro_on_uninstall' ) ) {
	/**
	 * alg_wc_wl_pro_on_uninstall.
	 *
	 * @version   1.9.1
	 * @since     1.9.1
	 */
	function alg_wc_wl_pro_on_uninstall() {
		alg_wc_wl_pro_start_plugin();
		Alg_WC_Wish_List_Core::on_uninstall();
	}
}
register_uninstall_hook( __FILE__, 'alg_wc_wl_pro_on_uninstall' );


add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', dirname(__FILE__), true );
	}
} );