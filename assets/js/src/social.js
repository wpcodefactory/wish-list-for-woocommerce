/**
 * Manages social options
 */
jQuery(function ($) {
    var alg_wc_wl_social = {

        /**
         * Initializes
         */
        init: function () {
            this.email_options_toggler('.alg-wc-wl-social-li .email', '.alg-wc-wl-email-options');
            this.handle_send_to_option();
            this.handle_clipboard_button();
        },

        copyToClipboard: function (text) {
            if (window.clipboardData && window.clipboardData.setData) {
                // IE specific code path to prevent textarea being shown while dialog is visible.
                return clipboardData.setData("Text", text);

            } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    return document.execCommand("copy");  // Security exception may be thrown by some browsers.
                } catch (ex) {
                    console.warn("Copy to clipboard failed.", ex);
                    return false;
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        },

        handle_clipboard_button: function () {
            $( "body" ).on( "click", '.alg-wc-wl-social-li .copy', function(e) {                
                e.preventDefault();
                var link = this.getAttribute('href');                
                alg_wc_wl_social.copyToClipboard(link);
                $("body").trigger({
                    type: "alg_wc_wl_copied_to_clipboard",
                    link: link
                });                
            })
        },

        email_options_toggler: function (selector, options_elem_selector) {
            var is_active = -1;
            $('body').on('click', selector, function (e) {
                var trigger = $(this);
                e.preventDefault();
                is_active *= -1;
                if (is_active == 1) {
                    trigger.addClass('active');
                } else {
                    trigger.removeClass('active');
                }

                $(options_elem_selector).slideToggle();
            })
        },

        handle_send_to_option: function () {
            $('body').on('change', 'input[name="alg_wc_wl_email_send_to"]', function (e) {
                var selected_val = $(this).val();
                if (selected_val == 'friends') {
                    $('.alg-wc-wl-emails-input').show();
                } else if (selected_val == 'admin') {
                    $('.alg-wc-wl-emails-input').hide();
                }
            });
        }

    }
    alg_wc_wl_social.init();
})