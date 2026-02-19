"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_multi-wishlist_js"],{

/***/ "./src/js/modules/multi-wishlist.js":
/*!******************************************!*\
  !*** ./src/js/modules/multi-wishlist.js ***!
  \******************************************/
/***/ ((module) => {



var $ = jQuery;
var multiWishlist = {
  defaults: {
    algwcwishlistmodal: '.js-algwcwishlistmodal',
    algwcwishlistmodalBtn: '.alg-wc-wl-toggle-btn',
    algwcwishlistmodalThumbBtn: '.alg-wc-wl-thumb-btn,.alg-wc-wl-thumb-btn-shortcode-wrapper .alg-wc-wl-btn',
    algwcwishlistmodalBtnClose: '.js-algwcwishlistmodal-btn-close',
    algwcwishlistmodalContainer: '.js-algwcwishlistmodal-container',
    algwcwishlistmodalOverlay: '.js-algwcwishlistmodal-overlay',
    algwcwishlistmodalCreate: '.js-algwcwishlistmodal-btn-create',
    algwcwishlistmodalSaveWishlist: '.js-algwcwishlistmodal-btn-save-wishlist',
    algwcwishlistmodalSave: '.js-algwcwishlistmodal-btn-save',
    algwcwishlistmodalSaveCopy: '.js-algwcwishlistmodal-btn-save-copy',
    algwcwishlistmodalCancel: '.js-algwcwishlistmodal-btn-cancel',
    algwcwishlistmodalCancelCopy: '.js-algwcwishlistmodal-btn-cancel-copy',
    algwcwishlistmodalForm: '.create-wishlist-form',
    algwcwishlistmodalFormCopy: '.copy-wishlist-form',
    algwcwishlistmodalSelect: '.select-wishlist',
    algwcwishlistContainer: '.algwc-wishlist-collections-wrapper',
    algwcwishlistDeleteWishlist: '.delete-customized-wishlist',
    algwcwishlistCopyWishlist: '.copy-wishlist'
  },
  init: function init(options) {
    this.settings = $.extend({}, this.defaults, options);
    this.bindEvents();
  },
  isTouchScreen: function isTouchScreen() {
    return window.matchMedia("(pointer: coarse)").matches;
  },
  bindEvents: function bindEvents() {
    var self = this;
    var settings = self.settings;
    var toggleEvents = self.isTouchScreen() ? alg_wc_wl_ajax.toggle_item_events.touchscreen.join(' ') : alg_wc_wl_ajax.toggle_item_events["default"].join(' ');

    // Open modal
    $(document).on('click', settings.algwcwishlistmodalBtn, function () {
      var itemid = $(this).data('item_id');
      self.show();
      self.showContainer();
      self.showOverlay();
      $("#wishlist_form_product_id").val(itemid);
      self.loadWishlist();
      $("input#wishlist_name").val('');
    });

    // Thumb button
    $(document).on(toggleEvents, settings.algwcwishlistmodalThumbBtn, function () {
      if (alg_wc_wl_ajax.allow_unlogged_user_add_remove !== 'yes') {
        return;
      }
      var itemid = $(this).data('item_id');
      self.show();
      self.showContainer();
      self.showOverlay();
      $("#wishlist_form_product_id").val(itemid);
      self.loadWishlist();
      $("input#wishlist_name").val('');
    });

    // Close modal
    $(document).on('click', settings.algwcwishlistmodalBtnClose, function () {
      self.closeModal();
    });
    $(document).on('click', settings.algwcwishlistmodalContainer, function () {
      self.closeModal();
    });

    // Create wishlist
    $(document).on('click', settings.algwcwishlistmodalCreate, function () {
      self.hideSelect();
      self.showForm();
    });
    $(document).on('click', settings.algwcwishlistmodalCancel, function () {
      self.showSelect();
      self.hideForm();
    });

    // Save new wishlist
    $(document).on('click', settings.algwcwishlistmodalSave, function () {
      var data = self.getSaveWishlistData();
      $.post(alg_wc_wl.ajaxurl, data, function (response) {
        if (!response.success) return;
        self.showSelect();
        self.hideForm();
        self.loadWishlist();
        $("input#wishlist_name").val('');
      });
    });

    // Save wishlist (add/remove product)
    $(document).on('click', settings.algwcwishlistmodalSaveWishlist, function () {
      var data = self.getSaveMultipleWishlistItemData();
      var btns = $(alg_wc_wl_toggle_btn.btn_class + '[data-item_id="' + data.item_id + '"]');
      $.post(alg_wc_wl.ajaxurl, data, function (response) {
        if (!response.success) return;
        self.closeModal();
        window.alg_wc_wish_list.show_notification(response);
        btns.removeClass('remove add');
        if (response.data.added_or_removed === 'removed') {
          btns.addClass('add');
        } else if (response.data.added_or_removed === 'added') {
          btns.addClass('remove');
        }
      });
    });

    // Delete wishlist
    $(document).on('click', settings.algwcwishlistDeleteWishlist, function () {
      var data = self.getDeleteWishlistData();
      $.post(alg_wc_wl.ajaxurl, data, function (response) {
        if (response.success) {
          window.location.href = response.data.redirect_url;
        }
      });
    });

    // Copy wishlist (open modal)
    $(document).on('click', settings.algwcwishlistCopyWishlist, function () {
      var title = $(this).data('wishlist_tab_title');
      var tabid = $(this).data('wishlist_tab_id');
      self.show();
      self.showContainer();
      self.showOverlay();
      self.hideSelect();
      self.hideForm();
      self.showFormCopy();
      $("#duplicate_wishlist_name").val(title + ' (Copy)');
      $("#wishlist_tab_id").val(tabid);
    });

    // Cancel copy
    $(document).on('click', settings.algwcwishlistmodalCancelCopy, function () {
      self.closeModal();
      $("#duplicate_wishlist_name").val('');
    });

    // Save copy
    $(document).on('click', settings.algwcwishlistmodalSaveCopy, function () {
      var data = self.getDuplicateWishlistData();
      $.post(alg_wc_wl.ajaxurl, data, function (response) {
        if (response.success) {
          location.reload();
        }
      });
    });

    // Prevent modal click propagation
    $(document).on('click', settings.algwcwishlistmodal, function (event) {
      event.stopPropagation();
    });
  },
  /* =========================
     Data Builders
  ========================== */

  getSaveWishlistData: function getSaveWishlistData() {
    return {
      action: alg_wc_wl_ajax.action_save_wishlist,
      nonce: alg_wc_wl_ajax.toggle_nonce,
      value: $("input#wishlist_name").val()
    };
  },
  getSaveMultipleWishlistItemData: function getSaveMultipleWishlistItemData() {
    var arr = [];
    $('input.whichlist-check:checkbox:checked').each(function () {
      arr.push($(this).val());
    });
    return {
      action: alg_wc_wl_ajax.action_save_multiple_wishlist,
      nonce: alg_wc_wl_ajax.toggle_nonce,
      value: arr,
      item_id: $("#wishlist_form_product_id").val()
    };
  },
  getDuplicateWishlistData: function getDuplicateWishlistData() {
    return {
      action: alg_wc_wl_ajax.action_duplicate_wishlist,
      nonce: alg_wc_wl_ajax.toggle_nonce,
      value_tab_id: $("#wishlist_tab_id").val(),
      value: $("#duplicate_wishlist_name").val()
    };
  },
  getDeleteWishlistData: function getDeleteWishlistData() {
    var el = $('.delete-customized-wishlist');
    return {
      action: alg_wc_wl_ajax.action_delete_multiple_wishlist,
      nonce: alg_wc_wl_ajax.toggle_nonce,
      wishlist_tab_id: el.data('wishlist_tab_id'),
      wishlist_page_id: el.data('page')
    };
  },
  /* =========================
     UI Helpers
  ========================== */

  loadWishlist: function loadWishlist() {
    var s = this.settings;
    $(s.algwcwishlistContainer).html('<li><i class="loading fas fa-sync-alt fa-spin fa-fw"></i></li>');
    $.post(alg_wc_wl.ajaxurl, {
      action: alg_wc_wl_ajax.action_get_multiple_wishlist,
      nonce: alg_wc_wl_ajax.toggle_nonce,
      item_id: $("#wishlist_form_product_id").val()
    }, function (response) {
      if (response.success) {
        $(s.algwcwishlistContainer).html(response.data.list_html);
      }
    });
  },
  show: function show() {
    $(this.settings.algwcwishlistmodal).addClass('is-open');
  },
  hide: function hide() {
    $(this.settings.algwcwishlistmodal).removeClass('is-open');
  },
  showContainer: function showContainer() {
    $(this.settings.algwcwishlistmodalContainer).addClass('is-open');
  },
  hideContainer: function hideContainer() {
    $(this.settings.algwcwishlistmodalContainer).removeClass('is-open');
  },
  showOverlay: function showOverlay() {
    $(this.settings.algwcwishlistmodalOverlay).addClass('is-open');
  },
  hideOverlay: function hideOverlay() {
    $(this.settings.algwcwishlistmodalOverlay).removeClass('is-open');
  },
  showSelect: function showSelect() {
    $(this.settings.algwcwishlistmodalSelect).removeClass('is-hidden');
  },
  hideSelect: function hideSelect() {
    $(this.settings.algwcwishlistmodalSelect).addClass('is-hidden');
  },
  showForm: function showForm() {
    $(this.settings.algwcwishlistmodalForm).removeClass('is-hidden');
  },
  hideForm: function hideForm() {
    $(this.settings.algwcwishlistmodalForm).addClass('is-hidden');
  },
  showFormCopy: function showFormCopy() {
    $(this.settings.algwcwishlistmodalFormCopy).removeClass('is-hidden');
  },
  hideFormCopy: function hideFormCopy() {
    $(this.settings.algwcwishlistmodalFormCopy).addClass('is-hidden');
  },
  closeModal: function closeModal() {
    this.hide();
    this.hideContainer();
    this.hideOverlay();
  }
};
module.exports = multiWishlist;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_multi-wishlist_js.js.map