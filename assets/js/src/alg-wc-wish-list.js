/**
 * Main js file
 */
jQuery(function ($) {
	var alg_wc_wish_list = {

		/**
		 * Initiate
		 */
		init: function () {
			$(document.body).on('click', alg_wc_wl_toggle_btn.btn_class, this.toggle_wishlist_item);
			this.handle_item_removal_from_wishlist_page();
		},

		/**
		 * Handle Item removal from wish list page.
		 *
		 * Here, the item is removed from DOM only.
		 * The real thing happens through ajax on function toggle_wishlist_item()
		 */
		handle_item_removal_from_wishlist_page: function () {
			$("body").on('alg_wc_wl_toggle_wl_item', function (e) {
				if (e.response.success) {
					if (jQuery('.alg-wc-wl-view-table').length) {
						e.target.parents('tr').remove();
					}
					if (jQuery('.alg-wc-wl-view-table tbody tr').length == 0) {
						jQuery('.alg-wc-wl-view-table').remove();
						jQuery('.alg-wc-wl-empty-wishlist').show();
					}
				}
			});
		},

		/**
		 * Convert a string to Boolean.
		 *
		 * It handles 'True' and 'False' Strings written as Lowercase or Uppercase.
		 * It also detects '0' and '1' Strings
		 */
		convertToBoolean: function (variable) {
			variable = variable.toLowerCase();
			return Boolean(variable == true | variable === 'true');
		},

		/**
		 * Toggle wish list item.
		 * If it is already in wish list, it is removed or else it is added
		 */
		toggle_wishlist_item: function () {
			var btns_with_same_item_id = jQuery(alg_wc_wl_toggle_btn.btn_class + '[data-item_id="' + jQuery(this).attr('data-item_id') + '"]');
			var this_btn = jQuery(this);
			var data = {
				action           : alg_wc_wl_ajax.action_toggle_item,
				alg_wc_wl_item_id: this_btn.attr('data-item_id')
			};
			if (!this_btn.hasClass('loading')) {
				this_btn.addClass('loading');
				jQuery.post(alg_wc_wl.ajaxurl, data, function (response) {
					if (response.success) {
						if (btns_with_same_item_id.hasClass('remove')) {
							btns_with_same_item_id.removeClass('remove');
							btns_with_same_item_id.addClass('add');
						} else {
							btns_with_same_item_id.removeClass('add');
							btns_with_same_item_id.addClass('remove');
						}
					}
					$("body").trigger({
						type    : "alg_wc_wl_toggle_wl_item",
						item_id : this_btn.attr('data-item_id'),
						target  : this_btn,
						response: response
					});
					alg_wc_wish_list.show_notification(response);
					this_btn.removeClass('loading');
				});
			}
		},

		/**
		 * Get notification options dynamically through the object called 'alg_wc_wl_notification'
		 *
		 * @param option
		 * @param default_opt
		 * @returns {*}
		 */
		get_notification_option: function (option, default_opt) {
			var result = null;
			if (typeof default_opt !== "undefined") {
				result = default_opt;
			}
			if (typeof alg_wc_wl_notification !== 'undefined') {
				if (alg_wc_wl_notification.hasOwnProperty(option) && !$.isEmptyObject(alg_wc_wl_notification[option])) {
					result = alg_wc_wl_notification[option];
				}
			}
			return result;
		},

		/**
		 * Get notification icon
		 *
		 * @param response
		 * @returns {string}
		 */
		get_notification_icon: function (response) {
			var icon = 'fa fa-heart';
			switch (response.data.action) {
				case 'added':
					icon = alg_wc_wish_list.get_notification_option('icon_add', 'fa fa-heart');
					break;
				case 'removed':
					icon = alg_wc_wish_list.get_notification_option('icon_remove', 'fa fa-heart-o');
					break;
				case 'error':
					icon = 'fa-frown-o'
					break;
			}
			return icon;
		},

		/**
		 * Show notification
		 *
		 * @param response
		 */
		show_notification: function (response) {
			iziToast.destroy();
			iziToast.show({
				icon            : alg_wc_wish_list.get_notification_icon(response),
				color           : 'dark',
				timeout         : alg_wc_wish_list.get_notification_option('timeout', 7000),
				backgroundColor : '#000000',
				progressBar     : alg_wc_wish_list.convertToBoolean(alg_wc_wish_list.get_notification_option('progressBar', true)),
				message         : response.data.message,
				position        : alg_wc_wish_list.get_notification_option('position', 'center'), // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
				progressBarColor: 'rgb(255, 255, 255)',
			});
		}
	}
	alg_wc_wish_list.init();
});