/**
 * Manages social options
 */
jQuery(function ($) {
	var alg_wc_wl_social = {

		/**
		 * Initiate
		 */
		init: function () {
			this.email_options_toggler(jQuery('.alg-wc-wl-social-li .email'), jQuery('.alg-wc-wl-email-options'));
		},

		email_options_toggler: function (trigger, options_elem) {
			var is_active = -1;
			trigger.on('click', function (e) {
				is_active*=-1;
				if(is_active==1){
					trigger.addClass('active');
				}else{
					trigger.removeClass('active');
				}
				e.preventDefault();
				options_elem.slideToggle();
			});
		}

	}
	alg_wc_wl_social.init();
})