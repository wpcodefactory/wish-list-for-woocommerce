/**
 * Wishlist for WooCommerce.
 *
 * @version 3.3.7
 * @since   3.3.7
 * @author  WPFactory
 */

__webpack_public_path__ = ALG_WC_WISHLIST_JS_OBJ.plugin_url + "/assets/";

// Static modules
const staticModules = [
	'iconpicker-manager',
];
staticModules.forEach( function ( module_name ) {
	import(
		/* webpackMode: "lazy"*/
		`./modules/${ module_name }`)
		.then( function ( component ) {
			component.init();
		} );
} );