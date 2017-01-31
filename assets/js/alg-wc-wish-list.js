/**
 * @summary Main JS of Wish list for WooCommerce plugin.
 *
 * This js is mainly responsible for adding / removing WooCommerce product items from Wish list through Ajax,
 * and to show a notification to user when Ajax response is complete.
 * 
 * @version   1.1.0
 * @since     1.0.0 
 * @requires  jQuery.js
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
			if(typeof variable === 'string' || variable instanceof String){
				variable = variable.toLowerCase();	
			}
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
/**
 * Controls thumb button position
 */
jQuery(function ($) {
	var alg_wc_wl_thumb_btn_positioner = {

		thumb_btn         : null,
		offset            : 0,
		thumb_btn_position: 'topRight',
		buttons_count     : 0,
		repeater          : null,

		/**
		 * Initiate
		 */
		init: function () {
			this.thumb_btn = jQuery('.' + this.get_thumb_option('thumb_css_class', 'alg-wc-wl-thumb-btn'));
			this.thumb_btn_position = this.get_thumb_option('position', 'topRight');
			this.offset = parseInt(this.get_thumb_option('offset', 17));
			this.thumb_btn.css('left', 'auto').css('top', 'auto').css('right', 'auto').css('bottom', 'auto');
			this.position_btns_looping();
		},

		/**
		 * Initiate a repeater to positioning buttons
		 *
		 * It has to be a set interval because we need to wait images loaded to calculate its offset and position.
		 * But don't need to worry because we are always checking when it is complete with stopRepeater()
		 */
		position_btns_looping: function () {
			this.repeater = setInterval(function () {
				alg_wc_wl_thumb_btn_positioner.position_btns()
			}, 200);
		},

		/**
		 * Position thumb buttons where they belong (bottomRight, bottomLeft, topRight, topLeft for now)
		 */
		position_btns: function () {
			alg_wc_wl_thumb_btn_positioner.thumb_btn.each(function () {
				var offset = alg_wc_wl_thumb_btn_positioner.offset;
				if (!jQuery(this).hasClass('positioned')) {
					var img = jQuery(this).parent().find('img');
					if (img.offset() && img.parent().offset) {
						var positionBottom = img.height() - jQuery(this).height() - offset;
						var positionTop = offset;
						var positionLeft = offset + img.offset().left - img.parent().offset().left;
						var positionRight = offset + img.offset().left - img.parent().offset().left;
						if (alg_wc_wl_thumb_btn_positioner.thumb_btn_position.match(/bottom/i)) {
							jQuery(this).css('top', positionBottom);
						}
						if (alg_wc_wl_thumb_btn_positioner.thumb_btn_position.match(/top/i)) {
							jQuery(this).css('top', positionTop);
						}
						if (alg_wc_wl_thumb_btn_positioner.thumb_btn_position.match(/right/i)) {
							jQuery(this).css('right', positionRight);
						}
						if (alg_wc_wl_thumb_btn_positioner.thumb_btn_position.match(/left/i)) {
							jQuery(this).css('left', positionLeft);
						}
						jQuery(this).addClass('alg-wc-wl-positioned');
						jQuery(this).show();
						alg_wc_wl_thumb_btn_positioner.buttons_count++;
						alg_wc_wl_thumb_btn_positioner.stopRepeater();
					}
				}
			});
		},

		/**
		 * Knows when function "position_btns()" has to stop
		 */
		stopRepeater: function () {
			if (alg_wc_wl_thumb_btn_positioner.buttons_count == alg_wc_wl_thumb_btn_positioner.thumb_btn.length) {
				clearInterval(alg_wc_wl_thumb_btn_positioner.repeater);
			}
		},

		/**
		 * Get thumb options dynamically through the object called 'alg_wc_wl_thumb'
		 *
		 * @param option
		 * @param default_opt
		 * @returns {*}
		 */
		get_thumb_option: function (option, default_opt) {
			var result = null;
			if (typeof default_opt !== "undefined") {
				result = default_opt;
			}
			if (typeof alg_wc_wl_thumb !== 'undefined') {
				if (alg_wc_wl_thumb.hasOwnProperty(option) && !$.isEmptyObject(alg_wc_wl_thumb[option])) {
					result = alg_wc_wl_thumb[option];
				}
			}
			return result;
		}
	}

	alg_wc_wl_thumb_btn_positioner.init();
});


