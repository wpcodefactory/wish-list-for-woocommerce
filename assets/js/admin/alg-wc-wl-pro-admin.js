jQuery(function ($) {
	var alg_wc_wl_pro_admin_iconpicker = {
		init: function () {
			var icon_input = $('.alg-wc-wl-icon-picker');
			this.createIconNextToInput(icon_input);
			if (icon_input.length) {
				this.callIconPicker(icon_input);
			}
		},
		createIconNextToInput: function (input) {
			jQuery('<span style="cursor:pointer;position:relative;top:2px;margin:0px 7px 0 10px;vertical-align: middle" class="input-group-addon"></span>').insertAfter(input);
		},
		callIconPicker: function (element) {
			element.iconpicker({
				selectedCustomClass: 'alg-wc-wl-iconpicker-selected',
				hideOnSelect: true,
				placement: 'top',
				templates: {
					iconpickerItem: '<a role="button" href="javascript://" class="iconpicker-item"><i></i></a>',
				}
			});
		}
	};
	alg_wc_wl_pro_admin_iconpicker.init();
});