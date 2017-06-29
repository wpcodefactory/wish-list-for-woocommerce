/**
 * Manages social options
 */
jQuery(function ($) {
	var alg_wc_wl_social = {

		/**
		 * Initiate
		 */
		init: function () {
			this.email_options_toggler('.alg-wc-wl-social-li .email', '.alg-wc-wl-email-options');
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
		}

	}
	alg_wc_wl_social.init();
})