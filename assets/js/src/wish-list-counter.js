/**
 * Updates wish list counter
 */

var alg_wc_wl_counter = {};
jQuery(function ($) {
	alg_wc_wl_counter = {
		counter_selector: '.alg-wc-wl-counter',

		init: function () {
			if ($(this.counter_selector).length) {
				$("body").on('alg_wc_wl_toggle_wl_item', function (e) {
					alg_wc_wl_counter.update_counter();
				});
			}
		},

		update_counter: function () {
			$.post(alg_wc_wl.ajaxurl, {
				action: alg_wc_wl_get_wl_ajax_action,
				ignore_excluded_items: true
			}, function (response) {
				if (response.success) {
					var wishlist = response.data.wishlist;
					console.log(wishlist.length);
					$(alg_wc_wl_counter.counter_selector).html(wishlist.length);
				}
			});
		}
	}
	alg_wc_wl_counter.init();
});