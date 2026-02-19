/**
 * Wishlist for WooCommerce.
 *
 * @version 3.1.6
 * @since   3.1.6
 * @author  WPFactory
 */

__webpack_public_path__ = ALG_WC_WISHLIST_JS_OBJ.plugin_url + "/assets/";

// Dynamic modules.
let modules = ALG_WC_WISHLIST_JS_OBJ.modules_to_load;
if (modules && modules.length) {
	modules.forEach(function (module) {
		import(
			/* webpackMode: "lazy"*/
			`./modules/${module}`)
			.then(function (component) {
				component.init();
			});
	});
}

// Static modules.
const staticModules = [
	'alg-wc-wish-list',
	'social',
	'thumb-btn-positioner',
	'wish-list-counter',
	//'multi-wishlist',
];
staticModules.forEach( function ( module_name ) {
	import(
		/* webpackMode: "lazy"*/
		`./modules/${ module_name }`)
		.then( function ( component ) {
			component.init();
		} );
} );