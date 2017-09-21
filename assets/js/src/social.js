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
		},

		email_options_toggler: function (selector, options_elem_selector) {
			var is_active = -1;
			$('body').on('click',selector,function(e){
				var trigger = $(this);
				e.preventDefault();
				is_active*=-1;
				if(is_active==1){
					trigger.addClass('active');
				}else{
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