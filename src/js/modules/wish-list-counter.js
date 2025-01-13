/**
 * Updates wish list counter.
 */

var alg_wc_wl_counter = {};
alg_wc_wl_counter = {
	counter_selector: '.alg-wc-wl-counter',
	init: function () {
		jQuery( "body" ).on( 'alg_wc_wl_toggle_wl_item alg_wc_wl_remove_all', function ( e ) {
			if ( jQuery( alg_wc_wl_counter.counter_selector ).length ) {
				alg_wc_wl_counter.update_counter();
			}
		} );
	},
	update_counter: function () {
		if ( jQuery( alg_wc_wl_counter.counter_selector ).length ) {
			jQuery.post( alg_wc_wl.ajaxurl, {
				action: alg_wc_wl_ajax.ajax_action,
				ignore_excluded_items: true
			}, function ( response ) {
				if ( response.success ) {
					var wishlist = response.data.wishlist;
					jQuery( alg_wc_wl_counter.counter_selector ).html( Object.keys( wishlist ).length );
				}
			} );
		}
	}
}

const wishListCounter = {
	init: function () {
		jQuery( function ( $ ) {
			alg_wc_wl_counter.init();
			$( "body" ).trigger( {
				type: "alg_wc_wl_counter",
				obj: alg_wc_wl_counter
			} );
		} );
	}
}
module.exports = wishListCounter;