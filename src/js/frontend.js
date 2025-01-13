/**
 * Wishlist for WooCommerce.
 *
 * @version 3.1.6
 * @since   3.1.6
 * @author  WPFactory
 */

__webpack_public_path__ = ALG_WC_WISHLIST_JS_OBJ.plugin_url + "/assets/";

// Static modules
const staticModules = [
	'alg-wc-wish-list',
	'social',
	'thumb-btn-positioner',
	'wish-list-counter',
];
staticModules.forEach( function ( module_name ) {
	import(
		/* webpackMode: "lazy"*/
		`./modules/${ module_name }`)
		.then( function ( component ) {
			component.init();
		} );
} );