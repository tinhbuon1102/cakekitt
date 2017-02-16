jQuery( document ).ready( function($){
	"use strict";
    var update_wishlist_count = function() {
		
        $.ajax({
            beforeSend: function () {
				
            },
            complete  : function (data) {
				
            },
            data      : {
                action: 'cake_update_wishlist_count'
            },
            success   : function (data) {
				var wishlisttotal = $('.cake-wishlist-icon span');
				wishlisttotal.html(data);
            },

            url: yith_wcwl_l10n.ajax_url
        });
		
    };

    $('body').on( 'added_to_wishlist removed_from_wishlist', update_wishlist_count );
	
} );