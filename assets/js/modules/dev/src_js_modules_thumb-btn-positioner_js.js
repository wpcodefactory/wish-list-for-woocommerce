(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_thumb-btn-positioner_js"],{

/***/ "./src/js/modules/thumb-btn-positioner.js":
/*!************************************************!*\
  !*** ./src/js/modules/thumb-btn-positioner.js ***!
  \************************************************/
/***/ ((module) => {

/**
 * Controls thumb button position
 */

var algWcWlThumbBtnPositioner = {
  thumbBtn: null,
  offset: 0,
  offsetSingle: 0,
  offsetLoop: 0,
  thumbBtnPosition: 'topRight',
  buttonsCount: 0,
  guideImgSelector: 'img.wp-post-image',
  repeater: null,
  /**
   * Initializes.
   */
  init: function init() {
    this.thumbBtn = jQuery('.' + this.getThumbOption('thumb_css_class', 'alg-wc-wl-thumb-btn'));
    this.thumbBtnPosition = this.getThumbOption('position', 'topLeft');
    this.offset = parseInt(this.getThumbOption('offset_loop', 17));
    this.offsetSingle = parseInt(this.getThumbOption('offset_single', 17));
    this.offsetLoop = parseInt(this.getThumbOption('offset_loop', 17));
    this.thumbBtn.css('left', 'auto').css('top', 'auto').css('right', 'auto').css('bottom', 'auto');
    this.guideImgSelector = this.getThumbOption('guide_img_selector', 'img.wp-post-image');
    this.positionBtnsLooping();
    window.onresize = function () {
      clearInterval(algWcWlThumbBtnPositioner.repeater);
      algWcWlThumbBtnPositioner.positionBtnsLooping();
    };
  },
  /**
   * Initiates a repeater to positioning buttons.
   *
   * It has to be a set interval because we need to wait images loaded to calculate its offset and position.
   * But don't need to worry because we are always checking when it is complete with stopRepeater().
   */
  positionBtnsLooping: function positionBtnsLooping() {
    this.repeater = setInterval(function () {
      algWcWlThumbBtnPositioner.positionBtns();
    }, 200);
  },
  /**
   * Positions thumb buttons where they belong (bottomRight, bottomLeft, topRight, topLeft for now).
   */
  positionBtns: function positionBtns() {
    algWcWlThumbBtnPositioner.thumbBtn.each(function () {
      var offset = algWcWlThumbBtnPositioner.offset;
      var offsetSingle = algWcWlThumbBtnPositioner.offsetSingle;
      var offsetLoop = algWcWlThumbBtnPositioner.offsetLoop;
      var guideImgSelector = algWcWlThumbBtnPositioner.guideImgSelector;
      var single = false;
      if (jQuery(this).hasClass('alg-wc-wl-thumb-btn-loop')) {
        offset = offsetLoop;
        single = false;
      } else if (jQuery(this).hasClass('alg-wc-wl-thumb-btn-single')) {
        offset = offsetSingle;
        single = true;
      }
      var img = jQuery(this).parent().find(guideImgSelector).eq(0);
      if (img.offset() && img.parent().offset()) {
        var positionBottom = img.height() - jQuery(this).height() - offset;
        var positionTop = offset;
        var positionLeft = offset + img.offset().left - img.parent().offset().left;
        var positionRight = offset + img.offset().left - img.parent().offset().left;
        if (algWcWlThumbBtnPositioner.thumbBtnPosition.match(/bottom/i)) {
          jQuery(this).css('top', positionBottom);
        }
        if (algWcWlThumbBtnPositioner.thumbBtnPosition.match(/top/i)) {
          jQuery(this).css('top', positionTop);
        }
        if (algWcWlThumbBtnPositioner.thumbBtnPosition.match(/right/i)) {
          jQuery(this).css('right', positionRight);
        }
        if (algWcWlThumbBtnPositioner.thumbBtnPosition.match(/left/i)) {
          jQuery(this).css('left', positionLeft);
        }
        if (single) {
          if (!jQuery(this).hasClass('positioned-on-parent')) {
            var imgWrapperGuessLevelsSingle = parseInt(algWcWlThumbBtnPositioner.getThumbOption('img_wrapper_guess_levels_single', 2));
            var productGallery = null;
            switch (imgWrapperGuessLevelsSingle) {
              case 1:
                productGallery = jQuery(this).parent();
                break;
              case 2:
                productGallery = jQuery(this).parent().parent();
                break;
              case 3:
                productGallery = jQuery(this).parent().parent().parent();
                break;
            }
            if (productGallery) {
              productGallery.append(jQuery(this));
            }
            jQuery(this).addClass('positioned-on-parent');
          }
        }
        jQuery(this).show();
        algWcWlThumbBtnPositioner.buttonsCount++;
        algWcWlThumbBtnPositioner.stopRepeater();
      }
    });
  },
  /**
   * Knows when function "position_btns()" has to stop.
   */
  stopRepeater: function stopRepeater() {
    if (algWcWlThumbBtnPositioner.buttonsCount == algWcWlThumbBtnPositioner.thumbBtn.length) {
      clearInterval(algWcWlThumbBtnPositioner.repeater);
      algWcWlThumbBtnPositioner.buttonsCount = 0;
    }
  },
  /**
   * Gets thumb options dynamically through the object called 'alg_wc_wl_thumb'.
   *
   * @param option
   * @param default_opt
   * @returns {*}
   */
  getThumbOption: function getThumbOption(option, default_opt) {
    var result = null;
    if (typeof default_opt !== 'undefined') {
      result = default_opt;
    }
    if (typeof alg_wc_wl_thumb !== 'undefined') {
      if (alg_wc_wl_thumb.hasOwnProperty(option) && !jQuery.isEmptyObject(alg_wc_wl_thumb[option])) {
        result = alg_wc_wl_thumb[option];
      }
    }
    return result;
  }
};
var thumbBtnPositioner = {
  init: function init() {
    jQuery(function () {
      algWcWlThumbBtnPositioner.init();
      jQuery('body').trigger({
        type: 'alg_wc_wl_thumb_btn_position',
        obj: algWcWlThumbBtnPositioner
      });
    });
  }
};
module.exports = thumbBtnPositioner;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_thumb-btn-positioner_js.js.map