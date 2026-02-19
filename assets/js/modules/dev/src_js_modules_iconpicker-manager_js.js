(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_iconpicker-manager_js"],{

/***/ "./src/js/modules/iconpicker-manager.js":
/*!**********************************************!*\
  !*** ./src/js/modules/iconpicker-manager.js ***!
  \**********************************************/
/***/ ((module) => {

var iconpickerManager = {
  init: function init() {
    jQuery(function ($) {
      var icon_input = $('.alg-wc-wl-icon-picker');
      iconpickerManager.createIconNextToInput(icon_input);
      if (icon_input.length) {
        iconpickerManager.callIconPicker(icon_input);
      }
    });
  },
  createIconNextToInput: function createIconNextToInput(input) {
    jQuery('<span style="cursor:pointer;position:relative;top:2px;margin:0px 7px 0 10px;vertical-align: middle" class="input-group-addon"></span>').insertAfter(input);
  },
  callIconPicker: function callIconPicker(element) {
    element.iconpicker({
      selectedCustomClass: 'alg-wc-wl-iconpicker-selected',
      hideOnSelect: true,
      placement: 'top',
      templates: {
        iconpickerItem: '<a role="button" href="javascript://" class="iconpicker-item"><i></i></a>'
      }
    });
  }
};
module.exports = iconpickerManager;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_iconpicker-manager_js.js.map