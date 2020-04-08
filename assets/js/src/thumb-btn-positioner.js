/**
 * Controls thumb button position
 */

 var alg_wc_wl_thumb_btn_positioner = {

 	thumb_btn: null,
 	offset: 0,
 	offset_single: 0,
 	offset_loop: 0,
 	thumb_btn_position: 'topRight',
 	buttons_count: 0,
 	repeater: null,

 	/**
 	 * Initiate
 	 */
 	init: function () {
 		this.thumb_btn = jQuery('.' + this.get_thumb_option('thumb_css_class', 'alg-wc-wl-thumb-btn'));
 		this.thumb_btn_position = this.get_thumb_option('position', 'topLeft');
 		this.offset = parseInt(this.get_thumb_option('offset_loop', 17));
 		this.offset_single = parseInt(this.get_thumb_option('offset_single', 17));
 		this.offset_loop = parseInt(this.get_thumb_option('offset_loop', 17));
 		this.thumb_btn.css('left', 'auto').css('top', 'auto').css('right', 'auto').css('bottom', 'auto');
 		this.position_btns_looping();
 		window.onresize = function(event) {
 			clearInterval(alg_wc_wl_thumb_btn_positioner.repeater);
 			alg_wc_wl_thumb_btn_positioner.position_btns_looping();
 		};
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
 			var offset_single = alg_wc_wl_thumb_btn_positioner.offset_single;
 			var offset_loop = alg_wc_wl_thumb_btn_positioner.offset_loop;
 			var single = false;

		    if (jQuery(this).hasClass('alg-wc-wl-thumb-btn-loop')) {
			    offset = offset_loop;
			    single = false;
		    } else if (jQuery(this).hasClass('alg-wc-wl-thumb-btn-single')) {
			    offset = offset_single;
			    single = true;
		    }

 			var img = jQuery(this).parent().find('img').eq(0);
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

 				if(single){
                    if (!jQuery(this).hasClass('positioned-on-parent')) {
                        var img_wrapper_guess_levels_single = parseInt(alg_wc_wl_thumb_btn_positioner.get_thumb_option('img_wrapper_guess_levels_single', 2));
                        switch (img_wrapper_guess_levels_single) {
                            case 1:
                                var product_gallery = jQuery(this).parent();
                                break;
                            case 2:
                                var product_gallery = jQuery(this).parent().parent();
                                break;
                            case 3:
                                var product_gallery = jQuery(this).parent().parent().parent();
                                break;
                        }
                        if (product_gallery) {
                            product_gallery.append(jQuery(this));
                        }
                        jQuery(this).addClass('positioned-on-parent');
                    }
 				}
 				jQuery(this).show();
 				alg_wc_wl_thumb_btn_positioner.buttons_count++;
 				alg_wc_wl_thumb_btn_positioner.stopRepeater();
 			}

 		});
 	},

 	/**
 	 * Knows when function "position_btns()" has to stop
 	 */
 	stopRepeater: function () {
 		if (alg_wc_wl_thumb_btn_positioner.buttons_count == alg_wc_wl_thumb_btn_positioner.thumb_btn.length) {
 			clearInterval(alg_wc_wl_thumb_btn_positioner.repeater);
 			alg_wc_wl_thumb_btn_positioner.buttons_count=0;
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
 			if (alg_wc_wl_thumb.hasOwnProperty(option) && !jQuery.isEmptyObject(alg_wc_wl_thumb[option])) {
 				result = alg_wc_wl_thumb[option];
 			}
 		}
 		return result;
 	}
}

jQuery(function ($) {
	alg_wc_wl_thumb_btn_positioner.init();
	jQuery("body").trigger({
		type: "alg_wc_wl_thumb_btn_position",
		obj: alg_wc_wl_thumb_btn_positioner
	});
});