/**
 * Updates wish list counter
 */

var alg_wc_wl_counter = {};
jQuery(function ($) {
	alg_wc_wl_counter = {
		counter_selector: '.alg-wc-wl-counter',
		init: function () {
			$("body").on('alg_wc_wl_toggle_wl_item alg_wc_wl_remove_all', function (e) {
				if ($(alg_wc_wl_counter.counter_selector).length) {					
					alg_wc_wl_counter.update_counter();
				}
			});			
		},
		update_counter: function () {
			if ($(alg_wc_wl_counter.counter_selector).length) {
				$.post(alg_wc_wl.ajaxurl, {
					action: alg_wc_wl_ajax.ajax_action,
					ignore_excluded_items: true
				}, function (response) {
					if (response.success) {
						var wishlist = response.data.wishlist;
                        $(alg_wc_wl_counter.counter_selector).html(Object.keys(wishlist).length);
					}
				});
			}
		}
	}
	alg_wc_wl_counter.init();	
	$("body").trigger({
		type: "alg_wc_wl_counter",
		obj: alg_wc_wl_counter
	});
});