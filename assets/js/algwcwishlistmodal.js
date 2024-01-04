;( function( $, window, document, undefined ) {

    'use strict';

    var pluginName = 'algwcwishlistmodal',
        defaults = {
            algwcwishlistmodal:             	'.js-algwcwishlistmodal',
            /*algwcwishlistmodalBtn:          	'.js-algwcwishlistmodal-btn',*/
            algwcwishlistmodalBtn:          	'.alg-wc-wl-toggle-btn',
            algwcwishlistmodalBtnClose:     	'.js-algwcwishlistmodal-btn-close',
            algwcwishlistmodalContainer:    	'.js-algwcwishlistmodal-container',
            algwcwishlistmodalOverlay:      	'.js-algwcwishlistmodal-overlay',
            algwcwishlistmodalCreate:      		'.js-algwcwishlistmodal-btn-create',
            algwcwishlistmodalSaveWishlist:     '.js-algwcwishlistmodal-btn-save-wishlist',
            algwcwishlistmodalSave:      		'.js-algwcwishlistmodal-btn-save',
            algwcwishlistmodalCancel:      		'.js-algwcwishlistmodal-btn-cancel',
            algwcwishlistmodalForm:      		'.create-wishlist-form',
            algwcwishlistmodalSelect:      		'.select-wishlist',
            algwcwishlistContainer:      		'.algwc-wishlist-collections-wrapper',
            algwcwishlistDeleteWishlist:      	'.delete-customized-wishlist'
        };
		
	var alg_wc_wl_save_multiple_item_data = function () {
		var this_btn = jQuery("input#wishlist_name");
		var data = {
			action: alg_wc_wl_ajax.action_save_wishlist,
			nonce: alg_wc_wl_ajax.toggle_nonce,
			value: this_btn.val()
		};
		return data;
	}
	
	var alg_wc_wl_get_multiple_item_data = function () {
		var productid = $("#wishlist_form_product_id").val();
		var data = {
			action: alg_wc_wl_ajax.action_get_multiple_wishlist,
			nonce: alg_wc_wl_ajax.toggle_nonce,
			item_id: productid
		};
		return data;
	}
	
	var alg_wc_wl_delete_wishlist_multiple_item_data = function () {
		var wishlist_tab_id = $('.delete-customized-wishlist').attr('data-wishlist_tab_id');
		var data = {
			action: alg_wc_wl_ajax.action_delete_multiple_wishlist,
			nonce: alg_wc_wl_ajax.toggle_nonce,
			wishlist_tab_id: wishlist_tab_id
		};
		return data;
	}
	
	var alg_wc_wl_save_wishlist_multiple_item_data = function () {
		var arr = [];
		jQuery('input.whichlist-check:checkbox:checked').each(function () {
			arr.push($(this).val());
		});
		var product_id = $("#wishlist_form_product_id").val();
		var data = {
			action: alg_wc_wl_ajax.action_save_multiple_wishlist,
			nonce: alg_wc_wl_ajax.toggle_nonce,
			value: arr,
			item_id: product_id
		};
		return data;
	}


    function Plugin ( element, options ) {
        this.element = element;
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    $.extend( Plugin.prototype, {
        init: function() {
            var _obj = this.settings;
            Plugin.prototype.handlerMethods( _obj );
        },

        handlerMethods: function( _obj ) {
            $( document ).on( 'click', _obj.algwcwishlistmodalBtn, function() {
				var itemid = $(this).attr('data-item_id');
                Plugin.prototype.show( _obj );
                Plugin.prototype.showContainer( _obj );
                Plugin.prototype.showOverlay( _obj );
				$("#wishlist_form_product_id").val(itemid);
				Plugin.prototype.loadWishlist( _obj );
				jQuery("input#wishlist_name").val('');
				
            });

            $( document ).on( 'click', _obj.algwcwishlistmodalBtnClose, function() {
                Plugin.prototype.hide( _obj );
                Plugin.prototype.hideContainer( _obj );
                Plugin.prototype.hideOverlay( _obj );
            });

            $( document ).on( 'click', _obj.algwcwishlistmodalContainer, function() {
                Plugin.prototype.hide( _obj );
                Plugin.prototype.hideContainer( _obj );
                Plugin.prototype.hideOverlay( _obj );
            });
			
			$( document ).on( 'click', _obj.algwcwishlistmodalCreate, function() {
                Plugin.prototype.hideSelect( _obj );
                Plugin.prototype.showForm( _obj );
            });
			
			$( document ).on( 'click', _obj.algwcwishlistmodalCancel, function() {
               Plugin.prototype.showSelect( _obj );
               Plugin.prototype.hideForm( _obj );
            });
			
			$( document ).on( 'click', _obj.algwcwishlistmodalSave, function() {
               
			       var data = alg_wc_wl_save_multiple_item_data();
				   jQuery.post(alg_wc_wl.ajaxurl, data, function (response) {
						if (response.success) {
							Plugin.prototype.showSelect( _obj );
							Plugin.prototype.hideForm( _obj );
							Plugin.prototype.loadWishlist( _obj );
							jQuery("input#wishlist_name").val('');
						}
					});
            });
			
			$( document ).on( 'click', _obj.algwcwishlistmodalSaveWishlist, function() {
				
					var data = alg_wc_wl_save_wishlist_multiple_item_data();
				    jQuery.post(alg_wc_wl.ajaxurl, data, function (response) {
						if (response.success) {
							Plugin.prototype.hide( _obj );
							Plugin.prototype.hideContainer( _obj );
							Plugin.prototype.hideOverlay( _obj );
							alg_wc_wish_list.show_notification(response);
						}
					});
            });

            $( document ).on( 'click', _obj.algwcwishlistDeleteWishlist, function( event ) {
				
				var data = alg_wc_wl_delete_wishlist_multiple_item_data();
				    jQuery.post(alg_wc_wl.ajaxurl, data, function (response) {
						if (response.success) {
							window.location.href = response.data.redirect_url;
						}
					});
			});
			
            $( document ).on( 'click', _obj.algwcwishlistmodal, function( event ) {
                event.stopPropagation();
            });
        },
		
		loadWishlist: function( _obj ) {
			var data = alg_wc_wl_get_multiple_item_data();
			$( _obj.algwcwishlistContainer ).html('<li><i class="loading fas fa-sync-alt fa-spin fa-fw"></i></li>');
			jQuery.post(alg_wc_wl.ajaxurl, data, function (response) {
				if (response.success) {
					$( _obj.algwcwishlistContainer ).html(response.data.list_html);
				}
			});
		},

        show: function( _obj ) {
            $( _obj.algwcwishlistmodal ).addClass( 'is-open' );
        },

        hide: function( _obj ) {
            $( _obj.algwcwishlistmodal ).removeClass( 'is-open' );
        },

        showContainer: function( _obj ) {
            $( _obj.algwcwishlistmodalContainer ).addClass( 'is-open' );
        },

        hideContainer: function( _obj ) {
            $( _obj.algwcwishlistmodalContainer ).removeClass( 'is-open' );
        },

        showOverlay: function( _obj ) {
            $( _obj.algwcwishlistmodalOverlay ).addClass( 'is-open' );
        },

        hideOverlay: function( _obj ) {
            $( _obj.algwcwishlistmodalOverlay ).removeClass( 'is-open' );
        },
		
		showSelect: function( _obj ) {
            $( _obj.algwcwishlistmodalSelect ).removeClass( 'is-hidden' );
        },

        hideSelect: function( _obj ) {
            $( _obj.algwcwishlistmodalSelect ).addClass( 'is-hidden' );
        },
		
		showForm: function( _obj ) {
            $( _obj.algwcwishlistmodalForm ).removeClass( 'is-hidden' );
        },

        hideForm: function( _obj ) {
            $( _obj.algwcwishlistmodalForm ).addClass( 'is-hidden' );
        }
    });

    $.fn[ pluginName ] = function( options ) {
        return this.each( function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" +
                    pluginName, new Plugin( this, options ) );
            }
        } );
    };

} )( jQuery, window, document );


jQuery(function ($) {
	$('.js-algwcwishlistmodal').algwcwishlistmodal();
});
