jQuery(function ($) {
	var alg_wc_wish_list = {
		init: function () {
			console.log(alg_wc_wl_toggle_btn);
			$(document.body).on('click', alg_wc_wl_toggle_btn.btn_class, this.toggle_wishlist_item);
		},
		toggle_wishlist_item: function () {
			var btns_with_same_item_id = jQuery(alg_wc_wl_toggle_btn.btn_class+'[data-item_id="'+jQuery(this).attr('data-item_id')+'"]');
			var this_btn = jQuery(this);
			var data = {
				action: alg_wc_wl_ajax.action_toggle_item,
				alg_wc_wl_item_id: this_btn.attr('data-item_id')
			};
			
			
			jQuery.post(alg_wc_wl.ajaxurl, data, function(response) {
				console.log('response:');
				console.log(response);
				
				if(response.success){
					if(btns_with_same_item_id.hasClass('remove')){
						btns_with_same_item_id.removeClass('remove');
						btns_with_same_item_id.addClass('add');
					}else{
						btns_with_same_item_id.removeClass('add');
						btns_with_same_item_id.addClass('remove');
					}
					//jQuery(this).removeClass('data-item_id')
					/*if(response.data.added){
						movieBtn.addClass('active');
						modal.find('.content').html(response.data.message);
						inst.open();
						if(movieBtn.hasClass('change-text')){
							movieBtn.find('.btn-text').html('Remover');
						}
					}else{
						movieBtn.removeClass('active');
						modal.find('.content').html(response.data.message);
						inst.open();
						if(movieBtn.hasClass('change-text')){
							movieBtn.find('.btn-text').html('Salvar');
						}
					}*/
				}else{
					/*modal.find('.content').html(response.data.message);
					inst.open();
					console.log(response);*/
				}
			});
		}
	}
	alg_wc_wish_list.init();
});