jQuery(document).ready(function(t) {
    function a() {
        "use strict";
        var a = {
                gallery: "product-thumbnails-list",
                cursor: "crosshair",
                imageCrossfade: !1,
                zoomType: "inner"
            },
            e = t(".product-thumbnails-item"),
            i = t("#bow-spimg"),
            o = t(".zoomLink");
			o.hide();
        i.ezPlus(a), o.hide(), t("form.cart select").prop("selectedIndex", 0), e.on("click", function() {
            t(".zoomContainer").remove(), o.show(), t(this).addClass("active").siblings().removeClass("active"), i.attr("src", t(this).data("zoom-image")), i.data("zoom-image", t(this).data("image")), i.attr("data-id", t(this).attr("data-id"));
            var e = t(this).attr("data-id");
            t(".product-large-image").find(o).each(function() {
                t(this).attr("data-id") == e && t(this).addClass("activeZoom").siblings().removeClass("activeZoom")
            }), i.attr("srcset", ""), t("form.cart select").prop("selectedIndex", 0), t(".reset_variations").hide(), t(".woocommerce-variation-price").hide(), i.ezPlus(a)
        }), t(".variations").on("change", "select", function() {
            t(".zoomWindow").css({
                background: "transparent"
            }), o.hide();
			t('img.zoomed').removeData('elevateZoom');
			t('.zoomWrapper img.zoomed').unwrap();
			t('.zoomContainer').remove();
        })
    }
    a();
	
	
});