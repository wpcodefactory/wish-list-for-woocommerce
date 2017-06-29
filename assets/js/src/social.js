/**
 * Manages social options
 */
jQuery(function ($) {
	var alg_wc_wl_social = {

		/**
		 * Initiate
		 */
		init: function () {
			this.email_options_toggler('.alg-wc-wl-social-li .email', jQuery('.alg-wc-wl-email-options'));
		},

		email_options_toggler: function (selector, options_elem) {
			var is_active = -1;
			$('body').on('click',selector,function(){
				is_active*=-1;
				if(is_active==1){
					trigger.addClass('active');
				}else{
					trigger.removeClass('active');
				}
				e.preventDefault();
				options_elem.slideToggle();
			})
		}

	}
	alg_wc_wl_social.init();
})