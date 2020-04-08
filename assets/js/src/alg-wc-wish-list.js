/**
 * @summary Main JS of Wish list for WooCommerce plugin.
 *
 * This js is mainly responsible for adding / removing WooCommerce product items from Wish list through Ajax,
 * and to show a notification to user when Ajax response is complete.
 *
 * @version   1.6.3
 * @since     1.0.0
 * @requires  jQuery.js
 */

alg_wc_wl_get_toggle_wishlist_item_data = function (clicked_btn) {
    data = {
        action: alg_wc_wl_ajax.action_toggle_item,
        unlogged_user_id: alg_wc_wish_list.get_cookie('alg-wc-wl-user-id'),
        alg_wc_wl_item_id: clicked_btn.attr('data-item_id')
    };
    return data;
}

var alg_wc_wish_list = {};
jQuery(function ($) {
    alg_wc_wish_list = {

        /**
         * Initiate
         */
        init: function () {
            $(document.body).on('mouseup touchend', alg_wc_wl_toggle_btn.btn_class, this.toggle_wishlist_item);
            this.handle_item_removal_from_wishlist_page();
            this.setup_izitoast();
            var toggle_item_return = this.get_notification_option('toggle_item_return');
            if (toggle_item_return) {
                alg_wc_wish_list.show_notification(toggle_item_return);
            }
            $("body").on('alg_wc_wl_toggle_wl_item', this.removeItemFromDomOnThumbBtnClick);
            $("body").on('alg_wc_wl_copied_to_clipboard', this.notify_on_copy_to_clipboard);
        },

        notify_on_copy_to_clipboard: function (link) {
            alg_wc_wish_list.show_notification({
                data: {
                    message: alg_wc_wish_list.get_notification_option('copied_message'),
                    icon: alg_wc_wl.fa_icons.copy
                }
            });
        },

        removeItemFromDomOnThumbBtnClick: function (e) {
            if (jQuery(e.target).hasClass('is_wish_list') && jQuery(e.target).hasClass('wish_list_wc_template')) {
                var item_removed = jQuery(e.target).closest('.product');
                item_removed.remove();
                $("body").trigger({
                    type: "alg_wc_wl_remove_item",
                    item_id: e.target.attr('data-item_id'),
                    target: e.target,
                    removed_item: item_removed
                });
            }
        },
        /**
         * Get cookie
         * @param cname
         * @returns {*}
         */
        get_cookie: function (cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },

        /**
         * Handle Item removal from wish list page.
         *
         * Here the item is removed from DOM only.
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
                        $('.alg-wc-wl-social').remove();
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
            if (typeof variable === 'string' || variable instanceof String) {
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
            var data = alg_wc_wl_get_toggle_wishlist_item_data(this_btn);

            if (!this_btn.hasClass('loading')) {
                this_btn.addClass('loading');
                jQuery.post(alg_wc_wl.ajaxurl, data, function (response) {
                    if (
                        response.success &&
                        (
                            response.data.action === 'removed' ||
                            response.data.action === 'added'
                        )
                    ) {
                        if (btns_with_same_item_id.hasClass('remove')) {
                            btns_with_same_item_id.removeClass('remove');
                            btns_with_same_item_id.addClass('add');
                        } else {
                            btns_with_same_item_id.removeClass('add');
                            btns_with_same_item_id.addClass('remove');
                        }
                    }
                    $("body").trigger({
                        type: "alg_wc_wl_toggle_wl_item",
                        item_id: this_btn.attr('data-item_id'),
                        target: this_btn,
                        response: response
                    });

                    alg_wc_wish_list.show_notification(response);

                    this_btn.removeClass('loading');
                });
            }
        },

        /**
         * Detect if user is browsing with a mobile
         * @returns {boolean}
         */
        is_mobile: function () {
            if (window.innerWidth <= 800 || window.innerHeight <= 600) {
                return true;
            } else {
                return false;
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
            var icon = 'fas fa-heart';
            switch (response.data.action) {
                case 'added':
                    icon = alg_wc_wish_list.get_notification_option('icon_add', 'fas fa-heart');
                    break;
                case 'removed':
                    icon = alg_wc_wish_list.get_notification_option('icon_remove', 'far fa-heart');
                    break;
                case 'error':
                    icon = alg_wc_wish_list.get_notification_option('icon_error', 'fas fa-exclamation-circle');
                    break;
                default:
                    if (response.data.icon !== 'undefined') {
                        icon = response.data.icon;
                    }
            }
            return icon;
        },

        /**
         * Show notification
         *
         * @param response
         */
        show_notification: function (response) {
            var can_show_notification = false;

            if (alg_wc_wish_list.convertToBoolean(alg_wc_wish_list.get_notification_option('mobile')) && alg_wc_wish_list.is_mobile()) {
                can_show_notification = true;
            } else if (alg_wc_wish_list.convertToBoolean(alg_wc_wish_list.get_notification_option('desktop')) && !alg_wc_wish_list.is_mobile()) {
                can_show_notification = true;
            }

            if (can_show_notification) {
                iziToast.destroy();
                iziToast.show({
                    message: response.data.message,
                    icon: alg_wc_wish_list.get_notification_icon(response)
                });
            }
        },

        /**
         * Setups Izitoast with default options
         */
        setup_izitoast: function () {
            this.setup_notification_to_close_on_esc();
            var settings = {
                resetOnHover: true,
                drag: false,
                layout: 2,
                theme: 'dark',
                timeout: alg_wc_wish_list.get_notification_option('timeout', 0),
                backgroundColor: '#000000',
                progressBar: alg_wc_wish_list.convertToBoolean(alg_wc_wish_list.get_notification_option('progressBar', true)),
                position: alg_wc_wish_list.get_notification_option('position', 'center'), // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                progressBarColor: 'rgb(255, 255, 255)',
                class: 'alg-wc-wl-izitoast',
                onClose: function (instance, toast, closedBy) {
                    $("body").trigger({
                        type: "alg_wc_wl_notification_close",
                        message: jQuery(toast).find('p.slideIn'),
                    });
                }
            };

            // Handle ok button to close notification
            if (alg_wc_wish_list.convertToBoolean(alg_wc_wish_list.get_notification_option('ok_button', false))) {
                settings.buttons = [
                    ['<button>OK</button>', function (instance, toast) {
                        instance.hide({}, toast);
                    }]
                ];
            }

            iziToast.settings(settings);
        },

        /**
         * Setups izitoast to close on esc
         */
        setup_notification_to_close_on_esc: function () {
            $(document).keyup(function (e) {
                if (e.keyCode == 27) {
                    if (jQuery('.iziToast').length > 0) {
                        iziToast.hide({}, '.iziToast');
                    }
                }
            });
        }
    }
    alg_wc_wish_list.init();
});