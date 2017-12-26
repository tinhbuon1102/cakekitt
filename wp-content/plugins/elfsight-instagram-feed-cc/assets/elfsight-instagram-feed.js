/*

Elfsight Instagram Feed
Version: 3.0.0
Release date: Thu Dec 21 2017

https://elfsight.com

Copyright (c) 2017 Elfsight, LLC. ALL RIGHTS RESERVED

*/
!function(e, t) {
    "use strict";
    window.onEappsInstagramFeedReady = function() {
        jQuery("[data-elfsight-instagram-feed-options]").each(function() {
            jQuery(this).eappsInstagramFeed(JSON.parse(decodeURIComponent(jQuery(this).attr("data-elfsight-instagram-feed-options"))))
        })
    }
}(window.jQuery),
function(e) {
    function t(i) {
        if (n[i])
            return n[i].exports;
        var a = n[i] = {
            i: i,
            l: !1,
            exports: {}
        };
        return e[i].call(a.exports, a, a.exports, t),
        a.l = !0,
        a.exports
    }
    var n = {};
    t.m = e,
    t.c = n,
    t.d = function(e, n, i) {
        t.o(e, n) || Object.defineProperty(e, n, {
            configurable: !1,
            enumerable: !0,
            get: i
        })
    }
    ,
    t.n = function(e) {
        var n = e && e.__esModule ? function() {
            return e.default
        }
        : function() {
            return e
        }
        ;
        return t.d(n, "a", n),
        n
    }
    ,
    t.o = function(e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }
    ,
    t.p = "",
    t(t.s = 9)
}([function(e, t, n) {
    "use strict";
    e.exports = {
        name: "EappsInstagramFeed",
        alias: "eapps-instagram-feed",
        version: "3.0.0",
        apiUrl: "https://api.instagram.com/v1"
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = i(n(36))
      , r = i(n(37))
      , o = i(n(38))
      , s = i(n(39))
      , l = i(n(40))
      , p = new function e() {
        (function(e, t) {
            if (!(e instanceof t))
                throw new TypeError("Cannot call a class as a function")
        }
        )(this, e),
        this.dates = new a.default,
        this.links = new r.default,
        this.numbers = new o.default,
        this.text = new s.default,
        this.others = new l.default
    }
    ;
    t.default = p
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(31))
      , r = function() {
        function e(t, n, i, r) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.client = new a.default(t,n),
            this.maxIdParameterName = r || "max_id",
            this.filterFunc = i || null,
            this.items = [],
            this.cursor = 0,
            this.maxCount = 33,
            this.nextPageUrl = !0,
            this.lastItemId = !1
        }
        return i(e, [{
            key: "fetch",
            value: function(e, t, n) {
                var i = this;
                if (e = e || this.maxCount,
                t = t || jQuery.Deferred(),
                e + this.cursor <= this.items.length || !this.hasNextPage()) {
                    var a = this.items.slice(this.cursor, this.cursor + e);
                    this.cursor += a.length,
                    t.resolve(a)
                } else
                    n = n || {},
                    n.count = this.maxCount,
                    this.lastItemId && (n[this.maxIdParameterName] = this.lastItemId),
                    this.request(n).then(function(n) {
                        n.data || t.reject("error");
                        var a = n.data;
                        a instanceof Array || (a = [a]),
                        a.length && (i.lastItemId = a[a.length - 1].id);
                        var r = function(n) {
                            i.items = i.items.concat(n),
                            i.fetch(e, t)
                        };
                        i.filterFunc ? i.filterFunc(a).then(r) : r(a)
                    }).fail(function(e) {
                        return t.reject(e)
                    });
                return t.promise()
            }
        }, {
            key: "request",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                return this.client.get(e).then(function(e) {
                    t.nextPageUrl = e.pagination ? e.pagination.next_url : null,
                    n.resolve(e)
                }, function(e) {
                    n.reject(e)
                }),
                n.promise()
            }
        }, {
            key: "hasNext",
            value: function() {
                return this.hasNextPage() || this.items.length > this.cursor
            }
        }, {
            key: "hasNextPage",
            value: function() {
                return !!this.nextPageUrl
            }
        }, {
            key: "reset",
            value: function() {
                this.cursor = 0
            }
        }]),
        e
    }();
    t.default = r
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    }),
    t.default = {
        api: null,
        apiEnc: !1,
        accessToken: null,
        source: null,
        filterOnly: null,
        filterExcept: null,
        filter: null,
        limit: 0,
        layout: "slider",
        postTemplate: "tile",
        columns: 4,
        rows: 2,
        gutter: 0,
        responsive: null,
        width: "auto",
        callToActionButtons: null,
        postElements: ["user", "date", "instagramLink", "likesCount", "commentsCount", "share", "text"],
        popupElements: ["user", "location", "followButton", "instagramLink", "likesCount", "share", "text", "comments", "date"],
        imageClickAction: "popup",
        sliderArrows: !0,
        sliderDrag: !0,
        sliderSpeed: .6,
        sliderAutoplay: 0,
        colorPostBg: "rgb(255, 255, 255)",
        colorPostText: "rgb(0, 0, 0)",
        colorPostLinks: "rgb(0, 53, 105)",
        colorPostOverlayBg: "rgba(0, 0, 0, 0.8)",
        colorPostOverlayText: "rgb(255, 255, 255)",
        colorSliderArrows: "rgb(255, 255, 255)",
        colorSliderArrowsBg: "rgba(0, 0, 0, 0.9)",
        colorGridLoadMoreButton: "rgb(56, 151, 240)",
        colorPopupOverlay: "rgba(43, 43, 43, 0.9)",
        colorPopupBg: "rgb(255, 255, 255)",
        colorPopupText: "rgb(0, 0, 0)",
        colorPopupLinks: "rgb(0, 53, 105)",
        colorPopupFollowButton: "rgb(56, 151, 240)",
        colorPopupCtaButton: "rgb(56, 151, 240)",
        widgetTitle: "",
        lang: "en",
        cacheTime: 300,
        popupDeepLinking: !1,
        debug: !1
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(28))
      , o = i(n(55))
      , s = i(n(63))
      , l = i(n(65))
      , p = i(n(0))
      , u = i(n(3))
      , c = function() {
        function e(t) {
            var n = this;
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.widget = t,
            this.tuner = t.tuner,
            this.lang = t.lang,
            this.$window = t.$window,
            this.posts = new r.default(t),
            this.imageClickAction = this.tuner.get("imageClickAction"),
            this.hasPopup = "popup" === this.imageClickAction,
            this.popupDeepLinking = this.tuner.get("popupDeepLinking"),
            this.hasPopup && (this.popup = new o.default(this.posts,{
                deepLinking: this.popupDeepLinking,
                id: this.widget.id,
                loadItemsFunc: function() {
                    return n.addView()
                }
            })),
            this.itemsPerPage = null,
            this.columns = parseInt(this.tuner.get("columns"), 10) || u.default.columns,
            this.rows = parseInt(this.tuner.get("rows"), 10) || u.default.rows,
            this.gutter = parseInt(this.tuner.get("gutter"), 10) || u.default.gutter,
            this.breakpoints = this.prepareBreakpoints(this.tuner.get("responsive")),
            this.defaultBreakpoint = {
                columns: this.columns,
                rows: this.rows,
                gutter: this.gutter
            },
            this.currentBreakpoint = this.defaultBreakpoint
        }
        return a(e, [{
            key: "init",
            value: function() {
                this.views = [],
                this.$element = this.createElement(),
                this.$postsInner = this.$element.find("." + p.default.alias + "-posts-inner"),
                this.widget.$postsContainer.html(this.$element),
                this.redLike = new l.default(this),
                this.clearViews(),
                this.showLoader(),
                this.posts.init(),
                this.updateBreakpoint()
            }
        }, {
            key: "prepareBreakpoints",
            value: function(e) {
                var t = []
                  , n = void 0;
                return (jQuery.isPlainObject(e) || jQuery.isArray(e)) && (jQuery.each(e || [], function(e, i) {
                    e = parseInt(i.minWidth || e, 10),
                    (n = jQuery.extend(!1, {}, i, {
                        minWidth: e
                    })).hasOwnProperty("columns") && (n.columns = parseInt(n.columns, 10) || u.default.columns),
                    n.hasOwnProperty("rows") && (n.rows = parseInt(n.rows, 10) || u.default.rows),
                    n.hasOwnProperty("gutter") && (n.gutter = parseInt(n.gutter, 10) || u.default.gutter),
                    t.push(n)
                }),
                t = t.sort(function(e, t) {
                    return e.minWidth < t.minWidth ? -1 : e.minWidth > t.minWidth ? 1 : 0
                })),
                t
            }
        }, {
            key: "clearViews",
            value: function() {
                this.views.forEach(function(e) {
                    e.clear()
                }),
                this.views = [],
                this.cursor = 0,
                this.$postsInner.empty()
            }
        }, {
            key: "addView",
            value: function() {
                var e = this
                  , t = this.calculateLackCount()
                  , n = jQuery.Deferred();
                return this.loadItems(t).then(function(t) {
                    if (e.destroyed)
                        return !1;
                    e.createView(),
                    e.hasPopup && e.popupDeepLinking && e.followHash(),
                    e.redLike.checkRun(),
                    e.hideLoader(),
                    n.resolve(t)
                }, function(t) {
                    if (e.destroyed)
                        return !1;
                    t && e.widget.showError(t),
                    n.reject()
                }),
                n.promise()
            }
        }, {
            key: "createView",
            value: function() {
                var e = this
                  , t = this.posts.items.slice(this.cursor, this.cursor + this.itemsPerPage);
                if (!t.length)
                    return !1;
                this.cursor += t.length;
                var n = new s.default(t,this);
                return n.$element.appendTo(this.$postsInner),
                this.views.push(n),
                setTimeout(function() {
                    n.init(),
                    e.fitItems()
                }),
                n
            }
        }, {
            key: "loadItems",
            value: function(e) {
                var t = this;
                return this.posts.addItems(e).then().fail(function() {
                    t.widget.showError("No posts found by your specified sources.")
                })
            }
        }, {
            key: "calculateLackCount",
            value: function() {
                return this.itemsPerPage - this.posts.items.length % this.itemsPerPage
            }
        }, {
            key: "fit",
            value: function() {
                var e = this;
                this.updateBreakpoint() && this.rebuildViews().then(function() {
                    e.fitItems()
                })
            }
        }, {
            key: "updateBreakpoint",
            value: function() {
                var e = !1
                  , t = null
                  , n = this.$window.innerWidth();
                return this.breakpoints.forEach(function(e) {
                    !t && n <= e.minWidth && (t = e)
                }),
                (t = t || this.defaultBreakpoint) !== this.currentBreakpoint && (this.currentBreakpoint = t,
                e = !0,
                this.columns = t.hasOwnProperty("columns") ? t.columns : this.defaultBreakpoint.columns,
                this.rows = t.hasOwnProperty("rows") ? t.rows : this.defaultBreakpoint.rows,
                this.gutter = t.hasOwnProperty("gutter") ? t.gutter : this.defaultBreakpoint.gutter),
                this.itemsPerPage = this.columns * this.rows,
                e
            }
        }, {
            key: "rebuildViews",
            value: function() {
                var e = this
                  , t = jQuery.Deferred();
                this.clearViews();
                var n = Math.floor(this.posts.items.length / this.itemsPerPage)
                  , i = function() {
                    for (var i = 0, a = n; i < a; i++)
                        e.createView();
                    t.resolve()
                };
                if (0 === n) {
                    var a = this.calculateLackCount();
                    this.loadItems(a).then(function() {
                        n++,
                        i()
                    })
                } else
                    i();
                return t.promise()
            }
        }, {
            key: "setAdaptiveClasses",
            value: function() {
                if (this.posts.items.length) {
                    var e = this.posts.items[0].$element.outerWidth();
                    this.$element.toggleClass(p.default.alias + "-posts-extra-small", e <= 150),
                    this.$element.toggleClass(p.default.alias + "-posts-small", e > 150 && e <= 250),
                    this.$element.toggleClass(p.default.alias + "-posts-medium", e > 250 && e <= 350),
                    this.$element.toggleClass(p.default.alias + "-posts-large", e > 350)
                }
            }
        }, {
            key: "fitItems",
            value: function() {
                var e = this
                  , t = (100 / this.columns).toFixed(6);
                this.views.forEach(function(n) {
                    n.$element.css({
                        padding: e.gutter / 2 + "px"
                    }),
                    n.items.forEach(function(n) {
                        n.$element.css({
                            width: "calc(" + t + "% - " + e.gutter + "px)",
                            margin: e.gutter / 2 + "px"
                        }),
                        n.$element.addClass(p.default.alias + "-posts-item-visible")
                    })
                }),
                this.setAdaptiveClasses()
            }
        }, {
            key: "watch",
            value: function() {
                var e = this
                  , t = void 0;
                this.$window.on("resize", function() {
                    e.fitItems(),
                    clearTimeout(t),
                    t = setTimeout(function() {
                        e.fit()
                    }, 100)
                })
            }
        }, {
            key: "showLoader",
            value: function() {
                var e = this.widget.$element.width() / this.columns;
                this.$postsInner.css("min-height", e),
                this.widget.$loader.addClass(p.default.alias + "-loader-visible")
            }
        }, {
            key: "hideLoader",
            value: function() {
                this.$postsInner.css("min-height", 0),
                this.widget.$loader.removeClass(p.default.alias + "-loader-visible")
            }
        }, {
            key: "followHash",
            value: function() {
                var e = this
                  , t = window.location.hash.match(new RegExp("#!is" + this.popup.id + "/\\$(.+)$"));
                if (t && t[1]) {
                    var n = t[1];
                    this.posts.items.some(function(t) {
                        if (t.data.code === n)
                            return e.popup.open(t.data.id),
                            !0
                    }) || this.addView()
                }
            }
        }, {
            key: "destroy",
            value: function() {
                this.popup && this.popup.isShowing() && this.popup.close(),
                this.destroyed = !0
            }
        }]),
        e
    }();
    t.default = c
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(2))
      , a = function(e) {
        function t(e, n, i) {
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t),
            function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).call(this, e, "/media/shortcode/" + n, i))
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, i.default),
        t
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(41))
      , o = i(n(42))
      , s = null
      , l = function() {
        function e(t) {
            if (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            s)
                return s;
            this.alias = t,
            this.STYLES_CLASSES = {
                bottom: "eui-popover-bottom"
            },
            this.opened = !1,
            this.items = [],
            this.$element = this.createElement(),
            this.$inner = this.$element.find(".eui-popover-content-inner"),
            this.$content = this.$element.find(".eui-popover-content"),
            this.watch(),
            s = this
        }
        return a(e, [{
            key: "addPopoverItems",
            value: function() {
                var e = this;
                this.$inner.empty(),
                this.items.forEach(function(t) {
                    var n = jQuery((0,
                    o.default)(t));
                    e.$inner.append(n),
                    t.handler && n.click(function(n) {
                        t.handler(),
                        e.hide(),
                        n.stopPropagation()
                    })
                })
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                r.default)())
            }
        }, {
            key: "open",
            value: function(e, t) {
                var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : [];
                this.items = e,
                this.$parent = t,
                this.optionalStylesClasses = n,
                this.addPopoverItems(),
                this.$element.appendTo(this.$parent),
                this.fit(),
                this.$element.addClass("eui-popover-open"),
                this.opened = !0
            }
        }, {
            key: "fit",
            value: function() {
                this.$widget && this.$widget.length || (this.$widget = jQuery("." + this.alias));
                var e = parseInt(this.$parent.offset().top - this.$widget.offset().top, 10)
                  , t = this.$inner.height() + parseInt(this.$content.css("padding-bottom"), 10);
                this.$element.toggleClass("eui-popover-bottom", e < t),
                this.stylize()
            }
        }, {
            key: "stylize",
            value: function() {
                var e = this;
                this.optionalStylesClasses && this.optionalStylesClasses.forEach(function(t) {
                    e.STYLES_CLASSES[t] && e.$element.addClass(e.STYLES_CLASSES[t])
                })
            }
        }, {
            key: "hide",
            value: function() {
                this.$element.removeClass("eui-popover-open"),
                this.opened = !1
            }
        }, {
            key: "isOpened",
            value: function() {
                return this.opened
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                jQuery("body").on("click touchend", function(t) {
                    !e.opened || jQuery(t.target).is(e.$element) || jQuery(t.target).closest(".eui-popover").length || e.hide()
                })
            }
        }]),
        e
    }();
    t.default = l
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i((i(n(0)),
    n(57)))
      , o = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.popupItem = t,
            this.$window = t.popup.opener.$window,
            this.isImage = !0,
            this.data = n,
            this.$element = this.createElement(),
            this.$image = this.$element.find("img")
        }
        return a(e, [{
            key: "init",
            value: function() {
                return this.watch(),
                this.fitImage()
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                r.default)(this.data))
            }
        }, {
            key: "fitImage",
            value: function() {
                var e = this
                  , t = jQuery.Deferred()
                  , n = this.$element.outerWidth()
                  , i = null;
                return this.data.images.forEach(function(t, a) {
                    !i && (Math.min(t.width, t.height) > n || a === e.data.images.length - 1) && (i = t)
                }),
                (!this.data.currentImage || this.data.currentImage.url !== i.url && i.width > this.data.currentImage.width) && (this.data.currentImage = i,
                this.$image.attr("src", this.data.currentImage.url).one("load", function() {
                    t.resolve()
                })),
                t.promise()
            }
        }, {
            key: "watch",
            value: function() {
                var e = this
                  , t = void 0;
                this.$window.on("resize", function() {
                    clearTimeout(t),
                    t = setTimeout(function() {
                        e.fitImage()
                    }, 200)
                })
            }
        }]),
        e
    }();
    t.default = o
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(0))
      , o = i(n(58))
      , s = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.popup = t.popup,
            this.isVideo = !0,
            this.data = n,
            this.$element = this.createElement(),
            this.$video = this.$element.find("video"),
            this.video = this.$video.get(0),
            this.loaded = !1
        }
        return a(e, [{
            key: "init",
            value: function() {
                var e = jQuery.Deferred();
                return this.loaded ? e.resolve() : (this.loaded = !0,
                this.$video.attr("src", this.data.videoUrl),
                this.$video.on("canplay", function() {
                    e.resolve()
                }),
                this.watch()),
                e.promise()
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                o.default)(this.data))
            }
        }, {
            key: "play",
            value: function() {
                var e = this;
                this.toggleMuted(this.popup.defaultVideoMute),
                this.video.currentTime = 0;
                var t = this.video.play();
                null !== t && t.catch(function() {
                    return e.video.play()
                }),
                this.video.play()
            }
        }, {
            key: "pause",
            value: function() {
                this.video.pause()
            }
        }, {
            key: "toggleMuted",
            value: function(e) {
                this.popup.defaultVideoMute = this.video.muted = void 0 !== e ? e : !this.video.muted,
                this.$element.toggleClass(r.default.alias + "-popup-item-media-video-unmuted", !this.video.muted)
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                this.$element.on("click touchend", "video", function(t) {
                    e.toggleMuted()
                })
            }
        }]),
        e
    }();
    t.default = s
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }();
    n(10);
    var r = i(n(20))
      , o = i(n(0))
      , s = i(n(3))
      , l = i(n(77));
    new (function() {
        function e() {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.dependencies = [{
                src: "https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js",
                test: function() {
                    return !!window.jQuery && 1 === (0,
                    l.default)(window.jQuery.fn.jquery, "1.7.1")
                }
            }],
            this.loadDependencies(this.initialize)
        }
        return a(e, [{
            key: "loadDependencies",
            value: function(e) {
                for (var t = 0, n = 0, i = 0; i < this.dependencies.length; ++i)
                    (function(i, a) {
                        if (!a.test.call()) {
                            ++t;
                            var r = document.createElement("script");
                            r.src = a.src,
                            r.onload = function() {
                                ++n === t && e()
                            }
                            ,
                            document.head.appendChild(r)
                        }
                    }
                    ).call(this.dependencies[i], i, this.dependencies[i]);
                t || e()
            }
        }, {
            key: "initialize",
            value: function() {
                var e = 0;
                jQuery.fn.eappsInstagramFeed = function(t) {
                    return t = t || {},
                    t = jQuery.extend(!1, {}, s.default, t),
                    this.each(function(n, i) {
                        var a = jQuery(i);
                        a.data(o.default.name) || a.data(o.default.name, new r.default(++e,a,t))
                    }),
                    this
                }
                ,
                setTimeout(function() {
                    window["on" + o.default.name + "Ready"] && window["on" + o.default.name + "Ready"]()
                }),
                jQuery("[data-is]").each(function(e, t) {
                    var n = jQuery(t)
                      , i = {};
                    jQuery.each(n[0].attributes, function(e, t) {
                        var a = t.name.replace(/(\-\w)/g, function(e) {
                            return e[1].toUpperCase()
                        }).replace("dataIs", "");
                        a = a.charAt(0).toLowerCase() + a.slice(1);
                        var r = n.attr(t.name);
                        "undefined" !== jQuery.type(r) && "" !== r && ("true" === r ? r = !0 : "false" === r && (r = !1),
                        i[a] = r)
                    }),
                    n.eappsInstagramFeed(i)
                })
            }
        }]),
        e
    }())
}
, function(e, t, n) {
    var i = n(11);
    "string" == typeof i && (i = [[e.i, i, ""]]);
    var a = {
        hmr: !0
    };
    a.transform = void 0,
    n(18)(i, a),
    i.locals && (e.exports = i.locals)
}
, function(e, t, n) {
    (e.exports = n(12)(void 0)).push([e.i, ".eapps-widget {\n  -webkit-font-smoothing: antialiased;\n  animation: none;\n  animation-delay: 0;\n  animation-direction: normal;\n  animation-duration: 0;\n  animation-fill-mode: none;\n  animation-iteration-count: 1;\n  animation-name: none;\n  animation-play-state: running;\n  animation-timing-function: ease;\n  backface-visibility: visible;\n  background: 0;\n  background-attachment: scroll;\n  background-clip: border-box;\n  background-color: transparent;\n  background-image: none;\n  background-origin: padding-box;\n  background-position: 0 0;\n  background-position-x: 0;\n  background-position-y: 0;\n  background-repeat: repeat;\n  background-size: auto auto;\n  border: 0;\n  border-style: none;\n  border-width: medium;\n  border-color: inherit;\n  border-bottom: 0;\n  border-bottom-color: inherit;\n  border-bottom-left-radius: 0;\n  border-bottom-right-radius: 0;\n  border-bottom-style: none;\n  border-bottom-width: medium;\n  border-collapse: separate;\n  border-image: none;\n  border-left: 0;\n  border-left-color: inherit;\n  border-left-style: none;\n  border-left-width: medium;\n  border-radius: 0;\n  border-right: 0;\n  border-right-color: inherit;\n  border-right-style: none;\n  border-right-width: medium;\n  border-spacing: 0;\n  border-top: 0;\n  border-top-color: inherit;\n  border-top-left-radius: 0;\n  border-top-right-radius: 0;\n  border-top-style: none;\n  border-top-width: medium;\n  bottom: auto;\n  box-shadow: none;\n  box-sizing: content-box;\n  caption-side: top;\n  clear: none;\n  clip: auto;\n  color: inherit;\n  columns: auto;\n  column-count: auto;\n  column-fill: balance;\n  column-gap: normal;\n  column-rule: medium none currentColor;\n  column-rule-color: currentColor;\n  column-rule-style: none;\n  column-rule-width: none;\n  column-span: 1;\n  column-width: auto;\n  content: normal;\n  counter-increment: none;\n  counter-reset: none;\n  direction: ltr;\n  empty-cells: show;\n  float: none;\n  font: normal;\n  font-family: inherit;\n  font-size: medium;\n  font-style: normal;\n  font-variant: normal;\n  font-weight: normal;\n  height: auto;\n  hyphens: none;\n  left: auto;\n  letter-spacing: normal;\n  line-height: normal;\n  list-style: none;\n  list-style-image: none;\n  list-style-position: outside;\n  list-style-type: disc;\n  margin: 0;\n  margin-bottom: 0;\n  margin-left: 0;\n  margin-right: 0;\n  margin-top: 0;\n  opacity: 1;\n  orphans: 0;\n  outline: 0;\n  outline-color: invert;\n  outline-style: none;\n  outline-width: medium;\n  overflow: visible;\n  overflow-x: visible;\n  overflow-y: visible;\n  padding: 0;\n  padding-bottom: 0;\n  padding-left: 0;\n  padding-right: 0;\n  padding-top: 0;\n  page-break-after: auto;\n  page-break-before: auto;\n  page-break-inside: auto;\n  perspective: none;\n  perspective-origin: 50% 50%;\n  position: static;\n  quotes: '\\201C' '\\201D' '\\2018' '\\2019';\n  right: auto;\n  tab-size: 8;\n  table-layout: auto;\n  text-align: inherit;\n  text-align-last: auto;\n  text-decoration: none;\n  text-decoration-color: inherit;\n  text-decoration-line: none;\n  text-decoration-style: solid;\n  text-indent: 0;\n  text-shadow: none;\n  text-transform: none;\n  top: auto;\n  transform: none;\n  transform-style: flat;\n  transition: none;\n  transition-delay: 0s;\n  transition-duration: 0s;\n  transition-property: none;\n  transition-timing-function: ease;\n  unicode-bidi: normal;\n  vertical-align: baseline;\n  visibility: visible;\n  white-space: normal;\n  widows: 0;\n  width: auto;\n  word-spacing: normal;\n  z-index: auto;\n}\n.eui-popover {\n  opacity: 0;\n  visibility: hidden;\n}\n.eui-popover-content {\n  position: absolute;\n  bottom: 100%;\n  right: 0;\n  padding-bottom: 11px;\n  width: 192px;\n  z-index: 6;\n}\n.eui-popover-content-inner {\n  position: relative;\n  background: #fff;\n  border-radius: 4px;\n  box-shadow: 0 4px 16px rgba(0,0,0,0.2);\n}\n.eui-popover-content-item {\n  display: flex;\n  align-items: center;\n  color: #17191a;\n  font-size: 13px;\n  font-weight: 400;\n  line-height: 1;\n  border-top: 1px solid #e5e6e7;\n  padding: 12px 0;\n  cursor: pointer;\n  transition: all 0.2s ease;\n  position: relative;\n  text-align: left;\n}\n.eui-popover-content-item:first-child {\n  border: none;\n}\n.eui-popover-content-item:hover {\n  background: #fafafa;\n  border-radius: 4px;\n}\n.eui-popover-content-item-icon {\n  height: 100%;\n  width: 12px;\n  float: left;\n  align-items: center;\n  justify-content: center;\n  display: inline-flex;\n  margin-left: 12px;\n}\n.eui-popover-content-item-title {\n  display: inline-block;\n  margin-left: 12px;\n}\n.eui-popover-open {\n  opacity: 1;\n  visibility: visible;\n}\n.eui-popover-left .eui-popover-content-inner:before {\n  right: 40px;\n  left: auto;\n  margin-left: auto;\n}\n.eui-extra-small .eui-popover-left .eui-popover-content-inner:before {\n  right: 10px;\n}\n.eui-popover-bottom .eui-popover-content {\n  top: 100%;\n  padding-top: 11px;\n}\n.eui-popover-bottom .eui-popover-content-inner:before {\n  top: -5px;\n}\n.eui-slider {\n  position: relative;\n  height: 100%;\n  user-select: none;\n}\n.eui-slider-inner {\n  z-index: 1;\n  position: relative;\n  height: 100%;\n  display: flex;\n  transition-property: transform;\n}\n.eui-slider-inner-animating {\n  will-change: transform;\n}\n.eui-slider-slide {\n  width: 100%;\n  height: 100%;\n  display: inline-block;\n  overflow: hidden;\n  flex-grow: 1;\n  flex-shrink: 0;\n}\n.eui-slider-arrow {\n  display: none;\n}\n.eui-slider-arrow-enabled {\n  display: block;\n}\n.eapps-instagram-feed {\n  font-size: 14px;\n  font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif;\n  line-height: 18px;\n  font-weight: 400;\n  overflow: hidden !important;\n  width: 100%;\n  position: relative;\n  box-sizing: border-box;\n  min-width: 150px;\n}\n.eapps-instagram-feed-has-error.eapps-instagram-feed-debug .eapps-instagram-feed-content {\n  min-height: 300px;\n}\n.eapps-instagram-feed-has-error:not(.eapps-instagram-feed-debug) {\n  display: none;\n}\n.eapps-instagram-feed-title {\n  font-size: 24px;\n  font-weight: 600;\n  text-align: center;\n  line-height: 32px;\n  padding: 24px 10px;\n  opacity: 0;\n  visibility: hidden;\n  transition: all 1s ease;\n}\n.eapps-instagram-feed-title-visible {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-content {\n  position: relative;\n}\n.eapps-instagram-feed-content-loader {\n  color: rgba(0,0,0,0.2);\n}\n.eapps-instagram-feed a {\n  text-decoration: none;\n  color: #000;\n}\n.eapps-instagram-feed a:hover {\n  text-decoration: underline;\n}\n.eui-popover-content {\n  width: 162px;\n}\n.eui-popover-content-inner {\n  position: relative;\n  background: #2f353a;\n  border-radius: 4px;\n  box-shadow: 0 4px 16px rgba(0,0,0,0.2);\n}\n.eui-popover-content-inner:before {\n  content: '';\n  background: #2f353a;\n  position: absolute;\n  bottom: -5px;\n  right: 20px;\n  width: 10px;\n  height: 10px;\n  transform: rotateZ(-45deg);\n}\n.eui-popover-content-item {\n  color: #fff;\n  border-top: 1px solid #42474c;\n  transition: all 0.2s ease;\n}\n.eui-popover-content-item:hover {\n  background: #42474c;\n}\n.eui-popover-content-item-icon {\n  height: 100%;\n  width: 12px;\n  float: left;\n  align-items: center;\n  justify-content: center;\n  display: inline-flex;\n  margin-left: 12px;\n}\n.eui-popover-content-item-icon img {\n  width: 100%;\n  height: 100%;\n}\n.eui-popover-content-item-title {\n  display: inline-block;\n  margin-left: 12px;\n}\n.eapps-instagram-feed-loader {\n  box-sizing: border-box;\n  display: block;\n  position: absolute;\n  top: 50%;\n  left: 50%;\n  border-radius: 50%;\n  border-style: solid;\n  border-top-color: transparent;\n  animation: eapps-instagram-feed-loader 1s infinite linear;\n  opacity: 0;\n  visibility: hidden;\n  width: 32px;\n  height: 32px;\n  margin: -16px 0 0 -16px;\n  border-width: 2px;\n  z-index: 1;\n}\n.eapps-instagram-feed-loader-visible {\n  opacity: 1;\n  visibility: visible;\n}\n@-moz-keyframes eapps-instagram-feed-loader {\n  0% {\n    transform: rotate(0deg);\n  }\n  100% {\n    transform: rotate(360deg);\n  }\n}\n@-webkit-keyframes eapps-instagram-feed-loader {\n  0% {\n    transform: rotate(0deg);\n  }\n  100% {\n    transform: rotate(360deg);\n  }\n}\n@-o-keyframes eapps-instagram-feed-loader {\n  0% {\n    transform: rotate(0deg);\n  }\n  100% {\n    transform: rotate(360deg);\n  }\n}\n@keyframes eapps-instagram-feed-loader {\n  0% {\n    transform: rotate(0deg);\n  }\n  100% {\n    transform: rotate(360deg);\n  }\n}\n.eapps-instagram-feed-error-container .eui-error {\n  display: flex;\n  flex-direction: column;\n  justify-content: center;\n  align-items: center;\n  top: 0;\n  right: 0;\n  bottom: 0;\n  left: 0;\n  position: absolute;\n  z-index: 101;\n  font-size: 15px;\n  font-weight: 400;\n  color: #7f8588;\n  background: #fff;\n}\n.eapps-instagram-feed-posts-slider {\n  position: relative;\n  height: 100%;\n  user-select: none;\n  cursor: -webkit-grab;\n  cursor: grab;\n}\n.eapps-instagram-feed-posts-slider-inner {\n  z-index: 1;\n  position: relative;\n  height: 100%;\n  display: flex;\n}\n.eapps-instagram-feed-posts-slider-inner-disable-click .eapps-instagram-feed-posts-item {\n  pointer-events: none;\n}\n.eapps-instagram-feed-posts-slider-item {\n  width: 100%;\n  height: 100%;\n  display: inline-block;\n  overflow: hidden;\n  flex-grow: 1;\n  flex-shrink: 0;\n}\n.eapps-instagram-feed-posts-slider-nav {\n  background: #000;\n  position: absolute;\n  top: 50%;\n  width: 60px;\n  height: 60px;\n  justify-content: center;\n  align-items: center;\n  border-radius: 50%;\n  z-index: 1;\n  cursor: pointer;\n  opacity: 0.85;\n  transition: all 0.2s;\n  backface-visibility: hidden;\n}\n.eapps-instagram-feed-posts-slider-nav-icon {\n  width: 12px;\n  height: 16px;\n  fill: #fff;\n  display: block;\n  position: absolute;\n  top: 50%;\n  transform: translateY(-50%);\n}\n.eapps-instagram-feed-posts-slider-nav:hover {\n  opacity: 1;\n}\n.eapps-instagram-feed-posts-slider-nav-disabled {\n  display: none;\n}\n.eapps-instagram-feed-posts-slider-prev {\n  left: 0;\n  transform: translate3d(-50%, -50%, 0);\n  box-shadow: 2px 0 5px rgba(0,0,0,0.3);\n}\n.eapps-instagram-feed-posts-slider-prev .eapps-instagram-feed-posts-slider-nav-icon {\n  right: 12px;\n}\n.eapps-instagram-feed-posts-slider-prev:active {\n  transform: translate3d(-50%, -50%, 0) scale(0.9);\n}\n.eapps-instagram-feed-posts-slider-next {\n  right: 0;\n  transform: translate(50%, -50%);\n  box-shadow: -2px 0 5px rgba(0,0,0,0.3);\n}\n.eapps-instagram-feed-posts-slider-next .eapps-instagram-feed-posts-slider-nav-icon {\n  left: 12px;\n}\n.eapps-instagram-feed-posts-slider-next:active {\n  transform: translate3d(50%, -50%, 0) scale(0.9);\n}\n.eapps-instagram-feed-posts-grid .eapps-instagram-feed-posts-inner {\n  display: flex;\n  flex-direction: row;\n  flex-wrap: wrap;\n  justify-content: space-between;\n}\n.eapps-instagram-feed-posts-grid .eapps-instagram-feed-posts-view:not(:first-child) {\n  padding-top: 0 !important;\n}\n.eapps-instagram-feed-posts-grid .eapps-instagram-feed-posts-item {\n  display: flex;\n  flex-direction: column;\n  flex-grow: 0;\n}\n.eapps-instagram-feed-posts-grid-load-more {\n  position: relative;\n  width: 228px;\n  height: 32px;\n  line-height: 32px;\n  border-radius: 4px;\n  border: none;\n  background: #3897f0;\n  color: #fff;\n  text-align: center;\n  outline: none;\n  cursor: pointer;\n  margin: 24px auto;\n  font-size: 14px;\n  transition: opacity 0.5s ease, visibility 0.5s ease, background 0.2s ease;\n  opacity: 0;\n  visibility: hidden;\n}\n.eapps-instagram-feed-posts-grid-load-more:hover {\n  background: #45a1f8;\n}\n.eapps-instagram-feed-posts-grid-load-more-visible {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-posts-grid-load-more-loading .eapps-instagram-feed-posts-grid-load-more-text {\n  opacity: 0;\n  visibility: hidden;\n}\n.eapps-instagram-feed-posts-grid-load-more .eapps-instagram-feed-loader {\n  width: 12px;\n  height: 12px;\n  margin: -7px 0 0 -7px;\n}\n.eapps-instagram-feed-posts-grid-load-more-loading.eapps-instagram-feed-posts-grid-load-more .eapps-instagram-feed-loader {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-posts {\n  width: 100%;\n}\n.eapps-instagram-feed-posts-inner {\n  box-sizing: border-box;\n  position: relative;\n}\n.eapps-instagram-feed-posts-view {\n  display: inline-flex;\n  flex-direction: row;\n  justify-content: flex-start;\n  flex-wrap: wrap;\n  box-sizing: border-box;\n  width: 100%;\n  height: 100%;\n}\n.eapps-instagram-feed-posts-view-empty {\n  height: 1px !important;\n}\n.eapps-instagram-feed-posts-item {\n  box-sizing: border-box;\n  display: flex;\n  flex-direction: column;\n  position: relative;\n  background: #fff;\n  transition: opacity 0.2s ease, visibility 0.2s ease;\n  opacity: 0;\n  visibility: hidden;\n  overflow: hidden;\n  flex-grow: 0;\n  flex-shrink: 0;\n}\n.eui-slider-slide-clone .eapps-instagram-feed-posts-item {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-posts-item-loaded {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-posts-item a,\n.eapps-instagram-feed-posts-item a:hover {\n  color: #003569;\n}\n.eapps-instagram-feed-posts-item-header {\n  display: flex;\n  flex-direction: row;\n  flex-wrap: nowrap;\n  align-items: center;\n  box-sizing: border-box;\n}\n.eapps-instagram-feed-posts-item-user {\n  display: flex;\n  flex-direction: row;\n  flex-wrap: nowrap;\n  align-items: center;\n  padding: 12px;\n  box-sizing: border-box;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  white-space: nowrap;\n}\n.eapps-instagram-feed-posts-item-user-image {\n  display: block;\n  width: 28px;\n  height: 28px;\n  border-radius: 50%;\n  box-shadow: 0 0 0 2px #fff;\n  box-sizing: border-box;\n  transition: all 0.2s ease;\n}\n.eapps-instagram-feed-posts-item-user-image:hover {\n  opacity: 0.95;\n}\n.eapps-instagram-feed-posts-item-user-image-wrapper {\n  background: linear-gradient(40deg, #f99b4a 15%, #dd3071 50%, #c72e8d 85%);\n  width: 36px;\n  height: 36px;\n  border-radius: 50%;\n  padding: 4px;\n  box-sizing: border-box;\n  margin-right: 12px;\n}\n.eapps-instagram-feed-posts-item-user-image-wrapper > a {\n  width: 100%;\n  height: 100%;\n  display: block;\n  background: #fff;\n  border-radius: 50%;\n}\n.eapps-instagram-feed-posts-item-user-name {\n  display: block;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  white-space: nowrap;\n  font-size: 14px;\n  font-weight: 600;\n}\n.eapps-instagram-feed-posts-item-user-name-wrapper {\n  display: flex;\n  flex-direction: column;\n  overflow: hidden;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n}\n.eapps-instagram-feed-posts-item-date {\n  text-transform: uppercase;\n  font-size: 10px;\n  opacity: 0.6;\n  overflow: hidden;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n}\n.eapps-instagram-feed-posts-item-instagram-link {\n  display: block;\n  width: 24px;\n  height: 24px;\n  margin-left: auto;\n  padding: 12px 12px 12px 0;\n  box-sizing: content-box;\n}\n.eapps-instagram-feed-posts-item-instagram-link > a {\n  display: block;\n}\n.eapps-instagram-feed-posts-item-instagram-link > a > svg {\n  display: block;\n  fill: currentColor;\n}\n.eapps-instagram-feed-posts-item-media > a {\n  display: block;\n}\n.eapps-instagram-feed-posts-item-link {\n  cursor: pointer;\n}\n.eapps-instagram-feed-posts-item-link-disabled {\n  cursor: default;\n}\n.eapps-instagram-feed-posts-item-image {\n  position: absolute;\n  top: 50%;\n  left: 50%;\n  transform: translate(-50%, -50%);\n}\n.eapps-instagram-feed-posts-item-image-portrait .eapps-instagram-feed-posts-item-image {\n  width: 100%;\n}\n.eapps-instagram-feed-posts-item-image-landscape .eapps-instagram-feed-posts-item-image {\n  height: 100%;\n  min-width: 100%;\n  max-width: inherit;\n}\n.eapps-instagram-feed-posts-item-image-wrapper {\n  padding-top: 100%;\n  position: relative;\n  overflow: hidden;\n  margin: -1px;\n}\n.eapps-instagram-feed-posts-item-image-icon {\n  display: none;\n  position: absolute;\n  z-index: 2;\n  top: 0.6em;\n  right: 0.6em;\n  width: 1.8em;\n  height: 1.8em;\n  transition: all 0.3s ease;\n}\n.eapps-instagram-feed-posts-item-image-icon svg {\n  display: block;\n  width: 100%;\n  height: 100%;\n  fill: rgba(255,255,255,0.8);\n}\n.eapps-instagram-feed-posts-item-type-video .eapps-instagram-feed-posts-item-image-icon-video {\n  display: block;\n}\n.eapps-instagram-feed-posts-item-type-carousel .eapps-instagram-feed-posts-item-image-icon-carousel {\n  display: block;\n}\n.eapps-instagram-feed-posts-item-counters a:hover {\n  text-decoration: none;\n}\n.eapps-instagram-feed-posts-item-likes-count,\n.eapps-instagram-feed-posts-item-comments-count {\n  display: inline-flex;\n  align-items: center;\n}\n.eapps-instagram-feed-posts-item-likes-count-icon,\n.eapps-instagram-feed-posts-item-comments-count-icon {\n  fill: currentColor;\n}\n.eapps-instagram-feed-posts-item-likes-count-label,\n.eapps-instagram-feed-posts-item-comments-count-label {\n  margin-left: 6px;\n  font-size: 14px;\n}\n.eapps-instagram-feed-posts-item-red-like {\n  position: absolute;\n  z-index: 10;\n  left: 0;\n  bottom: 0;\n  right: 0;\n  color: #fff;\n  background: #ee4957;\n  height: 28px;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n  font-size: 12px;\n  opacity: 0;\n  visibility: hidden;\n  pointer-events: none;\n  transition: opacity 0.2s ease, visibility 0.2s ease, bottom 0.2s ease;\n}\n.eui-slider-slide-clone .eapps-instagram-feed-posts-item-red-like {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-red-like-visible {\n  opacity: 1;\n  visibility: visible;\n  pointer-events: all;\n}\n.eapps-instagram-feed-posts-item-red-like-icon {\n  height: 12px;\n  width: 12px;\n  display: inline-block;\n  fill: #fff;\n}\n.eapps-instagram-feed-posts-item-red-like-count {\n  margin-left: 4px;\n}\n.eapps-instagram-feed-posts-item-red-like-label {\n  margin-left: 8px;\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-red-like-label {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-share {\n  position: relative;\n  display: inline-flex;\n  align-items: center;\n  cursor: pointer;\n}\n.eapps-instagram-feed-posts-item-share-icon {\n  fill: currentColor;\n}\n.eapps-instagram-feed-posts-item-share-label {\n  margin-left: 6px;\n  font-size: 14px;\n}\n.eapps-instagram-feed-posts-item-content .eui-item-text-see-more {\n  color: inherit;\n  transition: all 0.2s ease;\n}\n.eapps-instagram-feed-posts-item-content .eui-item-text-see-more:hover {\n  opacity: 1;\n}\n.eapps-instagram-feed-posts-item-template-tile {\n  user-select: none;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-link {\n  width: 100%;\n  box-sizing: border-box;\n  display: flex;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-overlay {\n  position: absolute;\n  z-index: 2;\n  top: -1px;\n  right: -1px;\n  bottom: -1px;\n  left: -1px;\n  opacity: 0;\n  visibility: hidden;\n  pointer-events: none;\n  transition: 0.3s all ease;\n  text-align: center;\n  box-sizing: border-box;\n  color: #fff;\n  background: rgba(0,0,0,0.8);\n  display: flex;\n  align-items: center;\n}\n.eapps-instagram-feed-posts-item:hover.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-overlay {\n  opacity: 1;\n  visibility: visible;\n  pointer-events: all;\n  padding-top: 0;\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-overlay {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-content {\n  transform: translateY(10%);\n  width: 80%;\n  max-height: 80%;\n  margin: 0 auto;\n  position: relative;\n  transition: 0.3s all ease;\n}\n.eapps-instagram-feed-posts-item:hover.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-content {\n  transform: translateY(0);\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-content {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-content-cropped::after {\n  content: '...';\n  display: block;\n  line-height: 1;\n  letter-spacing: 2px;\n  font-size: 18px;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-media {\n  width: 100%;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-image {\n  transition: 0.3s all ease;\n  left: 50%;\n  transform: scale(1) translate(-50%, -50%);\n  transform-origin: 0 0;\n  backface-visibility: hidden;\n}\n.eapps-instagram-feed-posts-item:hover.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-image {\n  transform: scale(1.1) translate(-50%, -50%);\n  transform-origin: 0 0;\n  filter: grayscale(1);\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item:hover.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-image {\n  filter: none;\n}\n.eapps-instagram-feed-posts-item:hover.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-image-icon {\n  opacity: 0;\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-counters,\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-counters {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-likes-count-label,\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-comments-count-label {\n  font-size: 16px;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-likes-count + .eapps-instagram-feed-posts-item-comments-count {\n  margin-left: 20px;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-text {\n  font-size: 14px;\n  line-height: 21px;\n  display: inline-block;\n  overflow: hidden;\n  margin-top: 12%;\n  text-align: center;\n  max-height: 84px;\n  width: 100%;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-text {\n  font-size: 14px;\n  line-height: 18px;\n  max-height: 72px;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-text-clone {\n  opacity: 0;\n  visibility: hidden;\n  position: absolute;\n  left: 0;\n  right: 0;\n  max-height: none;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-text-clone {\n  max-height: none;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-red-like {\n  bottom: -10px;\n}\n.eapps-instagram-feed-posts-item-template-tile .eapps-instagram-feed-posts-item-red-like-visible {\n  bottom: 0;\n}\n.eapps-instagram-feed-posts-item-template-classic {\n  border: 1px solid rgba(0,0,0,0.1);\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-instagram-link,\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-instagram-link {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-meta {\n  display: flex;\n  flex-direction: row;\n  align-items: baseline;\n  padding: 12px;\n  line-height: 1;\n  position: relative;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-meta + .eapps-instagram-feed-posts-item-content {\n  margin-top: 0px;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-counters {\n  display: flex;\n  align-items: center;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-likes-count,\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-comments-count {\n  margin-right: 12px;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-likes-count-label,\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-comments-count-label,\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-likes-count-label,\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-comments-count-label {\n  font-size: 13px;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-likes-count-icon,\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-comments-count-icon,\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-likes-count-icon,\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-comments-count-icon {\n  width: 20px;\n  height: 20px;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-share,\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-share {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-text {\n  font-size: 14px;\n  display: inline-block;\n  overflow: hidden;\n  max-height: 90px;\n  width: 100%;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-text {\n  font-size: 14px;\n  line-height: 18px;\n  max-height: 72px;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-text-clone {\n  opacity: 0;\n  visibility: hidden;\n  position: absolute;\n  left: 0;\n  right: 0;\n  max-height: none;\n  top: 0;\n}\n.eapps-instagram-feed-posts-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-text-clone {\n  max-height: none;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-red-like {\n  width: 148px;\n  top: -32px;\n  left: 6px;\n  border-radius: 6px;\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-red-like {\n  width: 40px;\n  left: 4px;\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-red-like:after {\n  bottom: -42px;\n}\n.eapps-instagram-feed-posts-extra-small .eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-red-like-label {\n  display: none;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-red-like:before {\n  content: '';\n  position: absolute;\n  bottom: -6px;\n  left: 12px;\n  background: #ee4957;\n  transform: rotateZ(-45deg);\n  width: 12px;\n  height: 12px;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-red-like:after {\n  content: '';\n  position: absolute;\n  bottom: -45px;\n  left: 16px;\n  background: #ee4957;\n  width: 4px;\n  height: 4px;\n  border-radius: 50%;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-share {\n  margin-left: auto;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-content {\n  margin: 12px;\n  position: relative;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-content-cropped {\n  text-decoration: none !important;\n}\n.eapps-instagram-feed-posts-item-template-classic .eapps-instagram-feed-posts-item-content-cropped:after {\n  content: '...';\n  line-height: 1;\n  font-weight: 600;\n  opacity: 0.6;\n  margin-top: -4px;\n  display: block;\n  text-decoration: none !important;\n}\n.eapps-widget.eapps-instagram-feed-popup {\n  position: fixed;\n  z-index: 99999;\n  top: 0;\n  right: 0;\n  bottom: 0;\n  left: 0;\n  opacity: 0;\n  visibility: hidden;\n  pointer-events: none;\n  box-sizing: border-box;\n  background: rgba(0,0,0,0.8);\n}\n.eapps-widget.eapps-instagram-feed-popup-visible {\n  pointer-events: all;\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-popup-wrapper {\n  position: fixed;\n  top: 0;\n  right: 0;\n  bottom: 0;\n  left: 0;\n  overflow-x: hidden;\n  overflow-y: auto;\n  -webkit-overflow-scrolling: touch;\n}\n@media only screen and (max-width: 767px) {\n  .eapps-instagram-feed-popup-wrapper {\n    top: 56px;\n  }\n}\n@media only screen and (max-width: 639px) {\n  .eapps-instagram-feed-popup-wrapper {\n    top: 48px;\n  }\n}\n.eapps-instagram-feed-popup-inner {\n  width: 100%;\n  padding-bottom: 100vh;\n  max-width: 640px;\n  margin: 0 auto;\n  position: relative;\n  z-index: 3;\n}\n.eapps-instagram-feed-popup-close {\n  position: fixed;\n  top: 20px;\n  left: 50%;\n  width: 32px;\n  height: 32px;\n  margin-left: 332px;\n  cursor: pointer;\n  z-index: 11;\n  opacity: 0.7;\n  transform: rotate(45deg);\n  transition: all 0.25s ease;\n}\n@media only screen and (max-width: 767px) {\n  .eapps-instagram-feed-popup-close {\n    opacity: 1;\n    left: 12px;\n    top: 14px;\n    margin: 0;\n  }\n}\n@media only screen and (max-width: 639px) {\n  .eapps-instagram-feed-popup-close {\n    top: 8px;\n  }\n}\n.eapps-instagram-feed-popup-close:hover {\n  opacity: 1;\n}\n.eapps-instagram-feed-popup-close::before,\n.eapps-instagram-feed-popup-close::after {\n  content: '';\n  display: block;\n  position: absolute;\n  top: 50%;\n  left: 50%;\n  border-radius: 10px;\n  background: #fff;\n  transition: all 0.25s ease;\n}\n@media only screen and (max-width: 767px) {\n  .eapps-instagram-feed-popup-close::before,\n  .eapps-instagram-feed-popup-close::after {\n    background: #000;\n  }\n}\n.eapps-instagram-feed-popup-close::before {\n  width: 24px;\n  height: 2px;\n  margin: -1px 0 0 -12px;\n}\n.eapps-instagram-feed-popup-close::after {\n  width: 2px;\n  height: 24px;\n  margin: -12px 0 0 -1px;\n}\n.eapps-instagram-feed-popup-close:active {\n  transform: rotate(45deg) scale(0.85);\n}\n.eapps-instagram-feed-popup-mobile-panel {\n  background: #f8f8f8;\n  border-bottom: 1px solid #f1f1f1;\n  display: none;\n  height: 56px;\n  position: fixed;\n  z-index: 10;\n  top: 0;\n  right: 0;\n  left: 0;\n  align-items: center;\n  box-sizing: border-box;\n  padding: 0 12px;\n}\n@media only screen and (max-width: 767px) {\n  .eapps-instagram-feed-popup-mobile-panel {\n    display: flex;\n  }\n}\n@media only screen and (max-width: 639px) {\n  .eapps-instagram-feed-popup-mobile-panel {\n    height: 48px;\n  }\n}\n.eapps-instagram-feed-popup-mobile-panel-title {\n  display: block;\n  width: 100%;\n  text-align: center;\n  color: #000;\n  font-size: 16px;\n  font-weight: 600;\n  white-space: nowrap;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  padding: 0 40px;\n}\n.eapps-instagram-feed-popup-scroll-indicator {\n  display: block;\n  position: fixed;\n  top: 50%;\n  left: 50%;\n  margin-top: -26px;\n  margin-left: 332px;\n  text-align: center;\n  z-index: 11;\n  opacity: 0;\n  visibility: hidden;\n  transition: all 0.6s ease;\n}\n.eapps-instagram-feed-popup-scroll-indicator-visible {\n  opacity: 1;\n  visibility: visible;\n}\n@media only screen and (max-width: 767px) {\n  .eapps-instagram-feed-popup-scroll-indicator {\n    display: none;\n  }\n}\n.eapps-instagram-feed-popup-scroll-indicator-mouse {\n  position: relative;\n  display: block;\n  width: 32px;\n  height: 52px;\n  margin: 0 auto 4px;\n  box-sizing: border-box;\n  border: 2px solid #fff;\n  border-radius: 23px;\n  opacity: 0.5;\n}\n.eapps-instagram-feed-popup-scroll-indicator-mouse-wheel {\n  position: absolute;\n  display: block;\n  top: 12px;\n  left: 50%;\n  width: 4px;\n  height: 4px;\n  margin-left: -2px;\n  background: #fff;\n  border-radius: 50%;\n  animation: eapps-instagram-feed-popup-scroll-indicator 1.5s cubic-bezier(0.32, 0, 0.6, 1.01) infinite;\n}\n.eapps-instagram-feed-popup-scroll-indicator-label {\n  display: inline-block;\n  line-height: 18px;\n  font-size: 13px;\n  font-weight: normal;\n  color: #fff;\n}\n@-moz-keyframes eapps-instagram-feed-popup-scroll-indicator {\n  0% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n  90% {\n    opacity: 0;\n    transform: scale(0.7) translateY(32px);\n  }\n  95% {\n    opacity: 0;\n    transform: scale(1) translateY(0);\n  }\n  100% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n}\n@-webkit-keyframes eapps-instagram-feed-popup-scroll-indicator {\n  0% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n  90% {\n    opacity: 0;\n    transform: scale(0.7) translateY(32px);\n  }\n  95% {\n    opacity: 0;\n    transform: scale(1) translateY(0);\n  }\n  100% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n}\n@-o-keyframes eapps-instagram-feed-popup-scroll-indicator {\n  0% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n  90% {\n    opacity: 0;\n    transform: scale(0.7) translateY(32px);\n  }\n  95% {\n    opacity: 0;\n    transform: scale(1) translateY(0);\n  }\n  100% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n}\n@keyframes eapps-instagram-feed-popup-scroll-indicator {\n  0% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n  90% {\n    opacity: 0;\n    transform: scale(0.7) translateY(32px);\n  }\n  95% {\n    opacity: 0;\n    transform: scale(1) translateY(0);\n  }\n  100% {\n    opacity: 1;\n    transform: scale(1) translateY(0);\n  }\n}\n.eapps-instagram-feed-popup-item {\n  color: #000;\n  background: #fff;\n  width: 100%;\n  min-height: 300px;\n  overflow: hidden;\n  position: relative;\n  border-bottom: 1px solid rgba(0,0,0,0.05);\n}\n.eapps-instagram-feed-popup-item:first-child {\n  margin-top: 0;\n}\n.eapps-instagram-feed-popup-item-inner {\n  opacity: 0;\n  visibility: hidden;\n}\n.eapps-instagram-feed-popup-item-loaded .eapps-instagram-feed-popup-item-inner {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-popup-item .eapps-instagram-feed-loader {\n  color: rgba(0,0,0,0.2);\n  opacity: 1;\n  visibility: visible;\n  width: 48px;\n  height: 48px;\n  margin: -24px 0 0 -24px;\n  border-width: 4px;\n}\n.eapps-instagram-feed-popup-item-loaded.eapps-instagram-feed-popup-item .eapps-instagram-feed-loader {\n  display: none;\n}\n.eapps-instagram-feed-popup-item-header {\n  position: relative;\n  display: flex;\n  flex-direction: row;\n  flex-wrap: nowrap;\n  align-items: center;\n}\n.eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-user-location {\n  font-size: 12px;\n  display: block;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  white-space: nowrap;\n}\n.eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-user-actions {\n  display: flex;\n  align-items: center;\n  margin-left: auto;\n}\n.eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-user-follow-link {\n  margin-left: auto;\n  font-size: 14px;\n  font-weight: 600;\n  padding-left: 12px;\n}\n@media only screen and (max-width: 319px) {\n  .eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-user-follow-link {\n    display: none;\n  }\n}\n.eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-user-follow-link a,\n.eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-user-follow-link a:hover {\n  color: #3897f0;\n}\n.eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-instagram-link {\n  margin-left: 24px;\n}\n@media only screen and (max-width: 319px) {\n  .eapps-instagram-feed-popup-item-header .eapps-instagram-feed-posts-item-instagram-link {\n    margin-left: 0;\n  }\n}\n.eapps-instagram-feed-popup-item-cta-button {\n  display: block;\n  position: relative;\n  padding: 12px;\n  font-weight: 600;\n  transition: all 0.2s ease;\n}\na.eapps-instagram-feed-popup-item-cta-button {\n  color: #3897f0;\n  text-decoration: none;\n  transition: all 0.2s ease;\n}\na.eapps-instagram-feed-popup-item-cta-button:hover {\n  color: #3897f0;\n  text-decoration: none;\n  opacity: 0.9;\n}\n.eapps-instagram-feed-popup-item-cta-button::after {\n  content: '';\n  display: block;\n  position: absolute;\n  right: 12px;\n  bottom: -1px;\n  left: 12px;\n  background: #f1f1f1;\n  height: 1px;\n}\n.eapps-instagram-feed-popup-item-cta-button-icon {\n  display: block;\n  width: 6px;\n  height: 10px;\n  position: absolute;\n  top: 50%;\n  right: 12px;\n  margin-top: -5px;\n  fill: currentColor;\n}\n.eapps-instagram-feed-popup-item-cta-highlighted .eapps-instagram-feed-popup-item-cta-button {\n  background: #3897f0;\n  color: #fff;\n}\n.eapps-instagram-feed-popup-item-cta-highlighted .eapps-instagram-feed-popup-item-cta-button::after {\n  display: none;\n}\n.eapps-instagram-feed-popup-item-cta-highlighted .eapps-instagram-feed-popup-item-cta-button:hover {\n  background: #45a1f8;\n  color: #fff;\n}\n.eapps-instagram-feed-popup-item-content {\n  width: 100%;\n  box-sizing: border-box;\n  display: flex;\n  flex-direction: column;\n  position: relative;\n  font-size: 14px;\n  padding: 16px 12px 24px;\n}\n.eapps-instagram-feed-popup-item-meta {\n  display: flex;\n  flex-direction: row;\n  justify-content: space-between;\n}\n.eapps-instagram-feed-popup-item-share {\n  margin-left: auto;\n  display: flex;\n  align-items: center;\n  position: relative;\n  font-size: 14px;\n  font-weight: 400;\n  cursor: pointer;\n  margin-top: -4px;\n}\n.eapps-instagram-feed-popup-item-share-icon {\n  fill: currentColor;\n}\n.eapps-instagram-feed-popup-item-share-label {\n  margin-left: 6px;\n}\n.eapps-instagram-feed-popup-item-text {\n  word-break: break-word;\n  margin-top: 12px;\n}\n.eapps-instagram-feed-popup-item-text a,\n.eapps-instagram-feed-popup-item-text a:hover {\n  color: #003569;\n}\na.eapps-instagram-feed-popup-item-text-author,\na.eapps-instagram-feed-popup-item-text-author:hover {\n  color: #000;\n  font-weight: 600;\n}\n.eapps-instagram-feed-popup-item-comments-item {\n  margin: 8px 0;\n}\n.eapps-instagram-feed-popup-item-comments-item-author {\n  font-weight: 600;\n}\n.eapps-instagram-feed-popup-item-comments-item-text {\n  word-break: break-word;\n}\n.eapps-instagram-feed-popup-item-comments-item-text a,\n.eapps-instagram-feed-popup-item-comments-item-text a:hover {\n  color: #003569;\n}\n.eapps-instagram-feed-popup-item-comments-more a {\n  opacity: 0.6;\n}\n.eapps-instagram-feed-popup-item-date {\n  font-size: 10px;\n  line-height: 12px;\n  text-transform: uppercase;\n  margin-top: 8px;\n  opacity: 0.6;\n}\n.eapps-instagram-feed-popup-item-media {\n  position: absolute;\n  right: 0;\n  left: 0;\n  overflow: hidden;\n  min-height: 300px;\n  visibility: hidden;\n}\n@media only screen and (max-width: 639px) {\n  .eapps-instagram-feed-popup-item-media {\n    min-height: 0;\n  }\n}\n.eapps-instagram-feed-popup-item-loaded .eapps-instagram-feed-popup-item-media {\n  visibility: visible;\n  position: relative;\n}\n.eapps-instagram-feed-popup-item-media-image img {\n  display: block;\n  max-width: 100%;\n  margin: 0 auto;\n}\n@media only screen and (max-width: 639px) {\n  .eapps-instagram-feed-popup-item-media-image img {\n    height: auto;\n  }\n}\n.eapps-instagram-feed-popup-item-media-video {\n  position: relative;\n}\n.eapps-instagram-feed-popup-item-media-video video {\n  position: relative;\n  z-index: 1;\n  display: block;\n  width: 100%;\n  cursor: pointer;\n}\n.eapps-instagram-feed-popup-item-media-video-sound {\n  display: block;\n  position: absolute;\n  z-index: 2;\n  width: 32px;\n  height: 32px;\n  bottom: 12px;\n  left: 12px;\n  pointer-events: none;\n  background: rgba(0,0,0,0.6);\n  border-radius: 50%;\n  backface-visibility: hidden;\n}\n.eapps-instagram-feed-popup-item-media-video-sound-off,\n.eapps-instagram-feed-popup-item-media-video-sound-on {\n  width: 16px;\n  height: 16px;\n  display: block;\n  position: absolute;\n  top: 50%;\n  left: 50%;\n  margin: -8px 0 0 -8px;\n}\n.eapps-instagram-feed-popup-item-media-video-sound-off path,\n.eapps-instagram-feed-popup-item-media-video-sound-on path {\n  fill: #fff;\n}\n.eapps-instagram-feed-popup-item-media-video-unmuted .eapps-instagram-feed-popup-item-media-video-sound-off {\n  visibility: hidden;\n  opacity: 0;\n}\n.eapps-instagram-feed-popup-item-media-video-sound-on {\n  visibility: hidden;\n  opacity: 0;\n}\n.eapps-instagram-feed-popup-item-media-video-unmuted .eapps-instagram-feed-popup-item-media-video-sound-on {\n  visibility: visible;\n  opacity: 1;\n}\n.eapps-instagram-feed-popup-item-media-carousel-inner {\n  display: flex;\n}\n.eapps-instagram-feed-popup-item-media-carousel-item {\n  width: 100%;\n  flex-shrink: 0;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow {\n  display: block;\n  position: absolute;\n  z-index: 2;\n  width: 24px;\n  height: 40px;\n  top: 50%;\n  margin: -20px 0 0 0;\n  cursor: pointer;\n  user-select: none;\n  visibility: hidden;\n  opacity: 0;\n  transform: scale(1);\n  transition: all 0.2s ease;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow:hover {\n  transform: scaleY(0.9);\n}\n.eapps-instagram-feed-popup-item-media-carousel:hover .eapps-instagram-feed-popup-item-media-carousel-arrow:not(.eapps-instagram-feed-popup-item-media-carousel-arrow-disabled) {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-disabled {\n  visibility: hidden;\n  opacity: 0;\n  transform: scale(0.85);\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow::before,\n.eapps-instagram-feed-popup-item-media-carousel-arrow::after {\n  background: rgba(255,255,255,0.9);\n  display: block;\n  position: absolute;\n  width: 28px;\n  height: 3px;\n  top: 20px;\n  transition: all 0.3s ease;\n  content: '';\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-prev {\n  left: 12px;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-prev:active {\n  transform: scaleY(0.85) translateX(-20%);\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-prev::before,\n.eapps-instagram-feed-popup-item-media-carousel-arrow-prev::after {\n  border-radius: 0 10px 10px 0;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-prev::before {\n  transform-origin: 0 110%;\n  transform: rotate(-45deg);\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-prev::after {\n  transform-origin: 0 -10%;\n  transform: rotate(45deg);\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-next {\n  right: 12px;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-next:active {\n  transform: scaleY(0.85) translateX(20%);\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-next::before,\n.eapps-instagram-feed-popup-item-media-carousel-arrow-next::after {\n  right: 0;\n  border-radius: 10px 0 0 10px;\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-next::before {\n  transform-origin: 100% 110%;\n  transform: rotate(45deg);\n}\n.eapps-instagram-feed-popup-item-media-carousel-arrow-next::after {\n  transform-origin: 100% -10%;\n  transform: rotate(-45deg);\n}\n.eapps-instagram-feed-popup-item-media-carousel-pagination {\n  display: flex;\n  justify-content: center;\n  position: absolute;\n  right: 12px;\n  bottom: 12px;\n  left: 12px;\n  z-index: 1;\n  opacity: 0;\n  visibility: hidden;\n  transition: all 0.2s ease;\n}\n.eapps-instagram-feed-popup-item-media-carousel:hover .eapps-instagram-feed-popup-item-media-carousel-pagination {\n  opacity: 1;\n  visibility: visible;\n}\n.eapps-instagram-feed-popup-item-media-carousel-pagination-item {\n  display: block;\n  width: 12px;\n  height: 12px;\n  cursor: pointer;\n  padding: 3px;\n  box-sizing: border-box;\n}\n.eapps-instagram-feed-popup-item-media-carousel-pagination-item::before {\n  content: '';\n  display: block;\n  width: 6px;\n  height: 6px;\n  border-radius: 50%;\n  background: rgba(255,255,255,0.6);\n  box-shadow: 0 0 3px rgba(0,0,0,0.3);\n  transition: all 0.2s ease;\n}\n.eapps-instagram-feed-popup-item-media-carousel-pagination-item:hover::before {\n  background: rgba(255,255,255,0.8);\n}\n.eapps-instagram-feed-popup-item-media-carousel-pagination-item-active::before {\n  background: #fff;\n}\n", ""])
}
, function(e, t, n) {
    "use strict";
    (function(t) {
        function n(e, n) {
            var i = e[1] || ""
              , a = e[3];
            if (!a)
                return i;
            if (n) {
                var r = function(e) {
                    return "/*# sourceMappingURL=data:application/json;charset=utf-8;base64," + new t(JSON.stringify(e)).toString("base64") + " */"
                }(a);
                return [i].concat(a.sources.map(function(e) {
                    return "/*# sourceURL=" + a.sourceRoot + e + " */"
                })).concat([r]).join("\n")
            }
            return [i].join("\n")
        }
        e.exports = function(e) {
            var t = [];
            return t.toString = function() {
                return this.map(function(t) {
                    var i = n(t, e);
                    return t[2] ? "@media " + t[2] + "{" + i + "}" : i
                }).join("")
            }
            ,
            t.i = function(e, n) {
                "string" == typeof e && (e = [[null, e, ""]]);
                for (var i = {}, a = 0; a < this.length; a++) {
                    var r = this[a][0];
                    "number" == typeof r && (i[r] = !0)
                }
                for (a = 0; a < e.length; a++) {
                    var o = e[a];
                    "number" == typeof o[0] && i[o[0]] || (n && !o[2] ? o[2] = n : n && (o[2] = "(" + o[2] + ") and (" + n + ")"),
                    t.push(o))
                }
            }
            ,
            t
        }
    }
    ).call(t, n(13).Buffer)
}
, function(e, t, n) {
    "use strict";
    (function(e) {
        function i() {
            return r.TYPED_ARRAY_SUPPORT ? 2147483647 : 1073741823
        }
        function a(e, t) {
            if (i() < t)
                throw new RangeError("Invalid typed array length");
            return r.TYPED_ARRAY_SUPPORT ? (e = new Uint8Array(t),
            e.__proto__ = r.prototype) : (null === e && (e = new r(t)),
            e.length = t),
            e
        }
        function r(e, t, n) {
            if (!(r.TYPED_ARRAY_SUPPORT || this instanceof r))
                return new r(e,t,n);
            if ("number" == typeof e) {
                if ("string" == typeof t)
                    throw new Error("If encoding is specified then the first argument must be a string");
                return l(this, e)
            }
            return o(this, e, t, n)
        }
        function o(e, t, n, i) {
            if ("number" == typeof t)
                throw new TypeError('"value" argument must not be a number');
            return "undefined" != typeof ArrayBuffer && t instanceof ArrayBuffer ? function(e, t, n, i) {
                if (t.byteLength,
                n < 0 || t.byteLength < n)
                    throw new RangeError("'offset' is out of bounds");
                if (t.byteLength < n + (i || 0))
                    throw new RangeError("'length' is out of bounds");
                return t = void 0 === n && void 0 === i ? new Uint8Array(t) : void 0 === i ? new Uint8Array(t,n) : new Uint8Array(t,n,i),
                r.TYPED_ARRAY_SUPPORT ? (e = t,
                e.__proto__ = r.prototype) : e = p(e, t),
                e
            }(e, t, n, i) : "string" == typeof t ? function(e, t, n) {
                if ("string" == typeof n && "" !== n || (n = "utf8"),
                !r.isEncoding(n))
                    throw new TypeError('"encoding" must be a valid string encoding');
                var i = 0 | c(t, n)
                  , o = (e = a(e, i)).write(t, n);
                return o !== i && (e = e.slice(0, o)),
                e
            }(e, t, n) : function(e, t) {
                if (r.isBuffer(t)) {
                    var n = 0 | u(t.length);
                    return 0 === (e = a(e, n)).length ? e : (t.copy(e, 0, 0, n),
                    e)
                }
                if (t) {
                    if ("undefined" != typeof ArrayBuffer && t.buffer instanceof ArrayBuffer || "length"in t)
                        return "number" != typeof t.length || function(e) {
                            return e != e
                        }(t.length) ? a(e, 0) : p(e, t);
                    if ("Buffer" === t.type && D(t.data))
                        return p(e, t.data)
                }
                throw new TypeError("First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.")
            }(e, t)
        }
        function s(e) {
            if ("number" != typeof e)
                throw new TypeError('"size" argument must be a number');
            if (e < 0)
                throw new RangeError('"size" argument must not be negative')
        }
        function l(e, t) {
            if (s(t),
            e = a(e, t < 0 ? 0 : 0 | u(t)),
            !r.TYPED_ARRAY_SUPPORT)
                for (var n = 0; n < t; ++n)
                    e[n] = 0;
            return e
        }
        function p(e, t) {
            var n = t.length < 0 ? 0 : 0 | u(t.length);
            e = a(e, n);
            for (var i = 0; i < n; i += 1)
                e[i] = 255 & t[i];
            return e
        }
        function u(e) {
            if (e >= i())
                throw new RangeError("Attempt to allocate Buffer larger than maximum size: 0x" + i().toString(16) + " bytes");
            return 0 | e
        }
        function c(e, t) {
            if (r.isBuffer(e))
                return e.length;
            if ("undefined" != typeof ArrayBuffer && "function" == typeof ArrayBuffer.isView && (ArrayBuffer.isView(e) || e instanceof ArrayBuffer))
                return e.byteLength;
            "string" != typeof e && (e = "" + e);
            var n = e.length;
            if (0 === n)
                return 0;
            for (var i = !1; ; )
                switch (t) {
                case "ascii":
                case "latin1":
                case "binary":
                    return n;
                case "utf8":
                case "utf-8":
                case void 0:
                    return P(e).length;
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return 2 * n;
                case "hex":
                    return n >>> 1;
                case "base64":
                    return E(e).length;
                default:
                    if (i)
                        return P(e).length;
                    t = ("" + t).toLowerCase(),
                    i = !0
                }
        }
        function d(e, t, n) {
            var i = !1;
            if ((void 0 === t || t < 0) && (t = 0),
            t > this.length)
                return "";
            if ((void 0 === n || n > this.length) && (n = this.length),
            n <= 0)
                return "";
            if (n >>>= 0,
            t >>>= 0,
            n <= t)
                return "";
            for (e || (e = "utf8"); ; )
                switch (e) {
                case "hex":
                    return function(e, t, n) {
                        var i = e.length;
                        (!t || t < 0) && (t = 0),
                        (!n || n < 0 || n > i) && (n = i);
                        for (var a = "", r = t; r < n; ++r)
                            a += function(e) {
                                return e < 16 ? "0" + e.toString(16) : e.toString(16)
                            }(e[r]);
                        return a
                    }(this, t, n);
                case "utf8":
                case "utf-8":
                    return k(this, t, n);
                case "ascii":
                    return function(e, t, n) {
                        var i = "";
                        n = Math.min(e.length, n);
                        for (var a = t; a < n; ++a)
                            i += String.fromCharCode(127 & e[a]);
                        return i
                    }(this, t, n);
                case "latin1":
                case "binary":
                    return function(e, t, n) {
                        var i = "";
                        n = Math.min(e.length, n);
                        for (var a = t; a < n; ++a)
                            i += String.fromCharCode(e[a]);
                        return i
                    }(this, t, n);
                case "base64":
                    return function(e, t, n) {
                        return 0 === t && n === e.length ? A.fromByteArray(e) : A.fromByteArray(e.slice(t, n))
                    }(this, t, n);
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return function(e, t, n) {
                        for (var i = e.slice(t, n), a = "", r = 0; r < i.length; r += 2)
                            a += String.fromCharCode(i[r] + 256 * i[r + 1]);
                        return a
                    }(this, t, n);
                default:
                    if (i)
                        throw new TypeError("Unknown encoding: " + e);
                    e = (e + "").toLowerCase(),
                    i = !0
                }
        }
        function f(e, t, n) {
            var i = e[t];
            e[t] = e[n],
            e[n] = i
        }
        function h(e, t, n, i, a) {
            if (0 === e.length)
                return -1;
            if ("string" == typeof n ? (i = n,
            n = 0) : n > 2147483647 ? n = 2147483647 : n < -2147483648 && (n = -2147483648),
            n = +n,
            isNaN(n) && (n = a ? 0 : e.length - 1),
            n < 0 && (n = e.length + n),
            n >= e.length) {
                if (a)
                    return -1;
                n = e.length - 1
            } else if (n < 0) {
                if (!a)
                    return -1;
                n = 0
            }
            if ("string" == typeof t && (t = r.from(t, i)),
            r.isBuffer(t))
                return 0 === t.length ? -1 : m(e, t, n, i, a);
            if ("number" == typeof t)
                return t &= 255,
                r.TYPED_ARRAY_SUPPORT && "function" == typeof Uint8Array.prototype.indexOf ? a ? Uint8Array.prototype.indexOf.call(e, t, n) : Uint8Array.prototype.lastIndexOf.call(e, t, n) : m(e, [t], n, i, a);
            throw new TypeError("val must be string, number or Buffer")
        }
        function m(e, t, n, i, a) {
            function r(e, t) {
                return 1 === o ? e[t] : e.readUInt16BE(t * o)
            }
            var o = 1
              , s = e.length
              , l = t.length;
            if (void 0 !== i && ("ucs2" === (i = String(i).toLowerCase()) || "ucs-2" === i || "utf16le" === i || "utf-16le" === i)) {
                if (e.length < 2 || t.length < 2)
                    return -1;
                o = 2,
                s /= 2,
                l /= 2,
                n /= 2
            }
            var p;
            if (a) {
                var u = -1;
                for (p = n; p < s; p++)
                    if (r(e, p) === r(t, -1 === u ? 0 : p - u)) {
                        if (-1 === u && (u = p),
                        p - u + 1 === l)
                            return u * o
                    } else
                        -1 !== u && (p -= p - u),
                        u = -1
            } else
                for (n + l > s && (n = s - l),
                p = n; p >= 0; p--) {
                    for (var c = !0, d = 0; d < l; d++)
                        if (r(e, p + d) !== r(t, d)) {
                            c = !1;
                            break
                        }
                    if (c)
                        return p
                }
            return -1
        }
        function g(e, t, n, i) {
            n = Number(n) || 0;
            var a = e.length - n;
            i ? (i = Number(i)) > a && (i = a) : i = a;
            var r = t.length;
            if (r % 2 != 0)
                throw new TypeError("Invalid hex string");
            i > r / 2 && (i = r / 2);
            for (var o = 0; o < i; ++o) {
                var s = parseInt(t.substr(2 * o, 2), 16);
                if (isNaN(s))
                    return o;
                e[n + o] = s
            }
            return o
        }
        function y(e, t, n, i) {
            return _(P(t, e.length - n), e, n, i)
        }
        function v(e, t, n, i) {
            return _(function(e) {
                for (var t = [], n = 0; n < e.length; ++n)
                    t.push(255 & e.charCodeAt(n));
                return t
            }(t), e, n, i)
        }
        function w(e, t, n, i) {
            return v(e, t, n, i)
        }
        function b(e, t, n, i) {
            return _(E(t), e, n, i)
        }
        function x(e, t, n, i) {
            return _(function(e, t) {
                for (var n, i, a, r = [], o = 0; o < e.length && !((t -= 2) < 0); ++o)
                    n = e.charCodeAt(o),
                    i = n >> 8,
                    a = n % 256,
                    r.push(a),
                    r.push(i);
                return r
            }(t, e.length - n), e, n, i)
        }
        function k(e, t, n) {
            n = Math.min(e.length, n);
            for (var i = [], a = t; a < n; ) {
                var r = e[a]
                  , o = null
                  , s = r > 239 ? 4 : r > 223 ? 3 : r > 191 ? 2 : 1;
                if (a + s <= n) {
                    var l, p, u, c;
                    switch (s) {
                    case 1:
                        r < 128 && (o = r);
                        break;
                    case 2:
                        128 == (192 & (l = e[a + 1])) && (c = (31 & r) << 6 | 63 & l) > 127 && (o = c);
                        break;
                    case 3:
                        l = e[a + 1],
                        p = e[a + 2],
                        128 == (192 & l) && 128 == (192 & p) && (c = (15 & r) << 12 | (63 & l) << 6 | 63 & p) > 2047 && (c < 55296 || c > 57343) && (o = c);
                        break;
                    case 4:
                        l = e[a + 1],
                        p = e[a + 2],
                        u = e[a + 3],
                        128 == (192 & l) && 128 == (192 & p) && 128 == (192 & u) && (c = (15 & r) << 18 | (63 & l) << 12 | (63 & p) << 6 | 63 & u) > 65535 && c < 1114112 && (o = c)
                    }
                }
                null === o ? (o = 65533,
                s = 1) : o > 65535 && (o -= 65536,
                i.push(o >>> 10 & 1023 | 55296),
                o = 56320 | 1023 & o),
                i.push(o),
                a += s
            }
            return function(e) {
                var t = e.length;
                if (t <= $)
                    return String.fromCharCode.apply(String, e);
                for (var n = "", i = 0; i < t; )
                    n += String.fromCharCode.apply(String, e.slice(i, i += $));
                return n
            }(i)
        }
        function I(e, t, n) {
            if (e % 1 != 0 || e < 0)
                throw new RangeError("offset is not uint");
            if (e + t > n)
                throw new RangeError("Trying to access beyond buffer length")
        }
        function C(e, t, n, i, a, o) {
            if (!r.isBuffer(e))
                throw new TypeError('"buffer" argument must be a Buffer instance');
            if (t > a || t < o)
                throw new RangeError('"value" argument is out of bounds');
            if (n + i > e.length)
                throw new RangeError("Index out of range")
        }
        function j(e, t, n, i) {
            t < 0 && (t = 65535 + t + 1);
            for (var a = 0, r = Math.min(e.length - n, 2); a < r; ++a)
                e[n + a] = (t & 255 << 8 * (i ? a : 1 - a)) >>> 8 * (i ? a : 1 - a)
        }
        function L(e, t, n, i) {
            t < 0 && (t = 4294967295 + t + 1);
            for (var a = 0, r = Math.min(e.length - n, 4); a < r; ++a)
                e[n + a] = t >>> 8 * (i ? a : 3 - a) & 255
        }
        function M(e, t, n, i, a, r) {
            if (n + i > e.length)
                throw new RangeError("Index out of range");
            if (n < 0)
                throw new RangeError("Index out of range")
        }
        function S(e, t, n, i, a) {
            return a || M(e, 0, n, 4),
            O.write(e, t, n, i, 23, 4),
            n + 4
        }
        function T(e, t, n, i, a) {
            return a || M(e, 0, n, 8),
            O.write(e, t, n, i, 52, 8),
            n + 8
        }
        function P(e, t) {
            t = t || 1 / 0;
            for (var n, i = e.length, a = null, r = [], o = 0; o < i; ++o) {
                if ((n = e.charCodeAt(o)) > 55295 && n < 57344) {
                    if (!a) {
                        if (n > 56319) {
                            (t -= 3) > -1 && r.push(239, 191, 189);
                            continue
                        }
                        if (o + 1 === i) {
                            (t -= 3) > -1 && r.push(239, 191, 189);
                            continue
                        }
                        a = n;
                        continue
                    }
                    if (n < 56320) {
                        (t -= 3) > -1 && r.push(239, 191, 189),
                        a = n;
                        continue
                    }
                    n = 65536 + (a - 55296 << 10 | n - 56320)
                } else
                    a && (t -= 3) > -1 && r.push(239, 191, 189);
                if (a = null,
                n < 128) {
                    if ((t -= 1) < 0)
                        break;
                    r.push(n)
                } else if (n < 2048) {
                    if ((t -= 2) < 0)
                        break;
                    r.push(n >> 6 | 192, 63 & n | 128)
                } else if (n < 65536) {
                    if ((t -= 3) < 0)
                        break;
                    r.push(n >> 12 | 224, n >> 6 & 63 | 128, 63 & n | 128)
                } else {
                    if (!(n < 1114112))
                        throw new Error("Invalid code point");
                    if ((t -= 4) < 0)
                        break;
                    r.push(n >> 18 | 240, n >> 12 & 63 | 128, n >> 6 & 63 | 128, 63 & n | 128)
                }
            }
            return r
        }
        function E(e) {
            return A.toByteArray(function(e) {
                if ((e = function(e) {
                    return e.trim ? e.trim() : e.replace(/^\s+|\s+$/g, "")
                }(e).replace(B, "")).length < 2)
                    return "";
                for (; e.length % 4 != 0; )
                    e += "=";
                return e
            }(e))
        }
        function _(e, t, n, i) {
            for (var a = 0; a < i && !(a + n >= t.length || a >= e.length); ++a)
                t[a + n] = e[a];
            return a
        }
        var A = n(15)
          , O = n(16)
          , D = n(17);
        t.Buffer = r,
        t.SlowBuffer = function(e) {
            return +e != e && (e = 0),
            r.alloc(+e)
        }
        ,
        t.INSPECT_MAX_BYTES = 50,
        r.TYPED_ARRAY_SUPPORT = void 0 !== e.TYPED_ARRAY_SUPPORT ? e.TYPED_ARRAY_SUPPORT : function() {
            try {
                var e = new Uint8Array(1);
                return e.__proto__ = {
                    __proto__: Uint8Array.prototype,
                    foo: function() {
                        return 42
                    }
                },
                42 === e.foo() && "function" == typeof e.subarray && 0 === e.subarray(1, 1).byteLength
            } catch (e) {
                return !1
            }
        }(),
        t.kMaxLength = i(),
        r.poolSize = 8192,
        r._augment = function(e) {
            return e.__proto__ = r.prototype,
            e
        }
        ,
        r.from = function(e, t, n) {
            return o(null, e, t, n)
        }
        ,
        r.TYPED_ARRAY_SUPPORT && (r.prototype.__proto__ = Uint8Array.prototype,
        r.__proto__ = Uint8Array,
        "undefined" != typeof Symbol && Symbol.species && r[Symbol.species] === r && Object.defineProperty(r, Symbol.species, {
            value: null,
            configurable: !0
        })),
        r.alloc = function(e, t, n) {
            return function(e, t, n, i) {
                return s(t),
                t <= 0 ? a(e, t) : void 0 !== n ? "string" == typeof i ? a(e, t).fill(n, i) : a(e, t).fill(n) : a(e, t)
            }(null, e, t, n)
        }
        ,
        r.allocUnsafe = function(e) {
            return l(null, e)
        }
        ,
        r.allocUnsafeSlow = function(e) {
            return l(null, e)
        }
        ,
        r.isBuffer = function(e) {
            return !(null == e || !e._isBuffer)
        }
        ,
        r.compare = function(e, t) {
            if (!r.isBuffer(e) || !r.isBuffer(t))
                throw new TypeError("Arguments must be Buffers");
            if (e === t)
                return 0;
            for (var n = e.length, i = t.length, a = 0, o = Math.min(n, i); a < o; ++a)
                if (e[a] !== t[a]) {
                    n = e[a],
                    i = t[a];
                    break
                }
            return n < i ? -1 : i < n ? 1 : 0
        }
        ,
        r.isEncoding = function(e) {
            switch (String(e).toLowerCase()) {
            case "hex":
            case "utf8":
            case "utf-8":
            case "ascii":
            case "latin1":
            case "binary":
            case "base64":
            case "ucs2":
            case "ucs-2":
            case "utf16le":
            case "utf-16le":
                return !0;
            default:
                return !1
            }
        }
        ,
        r.concat = function(e, t) {
            if (!D(e))
                throw new TypeError('"list" argument must be an Array of Buffers');
            if (0 === e.length)
                return r.alloc(0);
            var n;
            if (void 0 === t)
                for (t = 0,
                n = 0; n < e.length; ++n)
                    t += e[n].length;
            var i = r.allocUnsafe(t)
              , a = 0;
            for (n = 0; n < e.length; ++n) {
                var o = e[n];
                if (!r.isBuffer(o))
                    throw new TypeError('"list" argument must be an Array of Buffers');
                o.copy(i, a),
                a += o.length
            }
            return i
        }
        ,
        r.byteLength = c,
        r.prototype._isBuffer = !0,
        r.prototype.swap16 = function() {
            var e = this.length;
            if (e % 2 != 0)
                throw new RangeError("Buffer size must be a multiple of 16-bits");
            for (var t = 0; t < e; t += 2)
                f(this, t, t + 1);
            return this
        }
        ,
        r.prototype.swap32 = function() {
            var e = this.length;
            if (e % 4 != 0)
                throw new RangeError("Buffer size must be a multiple of 32-bits");
            for (var t = 0; t < e; t += 4)
                f(this, t, t + 3),
                f(this, t + 1, t + 2);
            return this
        }
        ,
        r.prototype.swap64 = function() {
            var e = this.length;
            if (e % 8 != 0)
                throw new RangeError("Buffer size must be a multiple of 64-bits");
            for (var t = 0; t < e; t += 8)
                f(this, t, t + 7),
                f(this, t + 1, t + 6),
                f(this, t + 2, t + 5),
                f(this, t + 3, t + 4);
            return this
        }
        ,
        r.prototype.toString = function() {
            var e = 0 | this.length;
            return 0 === e ? "" : 0 === arguments.length ? k(this, 0, e) : d.apply(this, arguments)
        }
        ,
        r.prototype.equals = function(e) {
            if (!r.isBuffer(e))
                throw new TypeError("Argument must be a Buffer");
            return this === e || 0 === r.compare(this, e)
        }
        ,
        r.prototype.inspect = function() {
            var e = ""
              , n = t.INSPECT_MAX_BYTES;
            return this.length > 0 && (e = this.toString("hex", 0, n).match(/.{2}/g).join(" "),
            this.length > n && (e += " ... ")),
            "<Buffer " + e + ">"
        }
        ,
        r.prototype.compare = function(e, t, n, i, a) {
            if (!r.isBuffer(e))
                throw new TypeError("Argument must be a Buffer");
            if (void 0 === t && (t = 0),
            void 0 === n && (n = e ? e.length : 0),
            void 0 === i && (i = 0),
            void 0 === a && (a = this.length),
            t < 0 || n > e.length || i < 0 || a > this.length)
                throw new RangeError("out of range index");
            if (i >= a && t >= n)
                return 0;
            if (i >= a)
                return -1;
            if (t >= n)
                return 1;
            if (t >>>= 0,
            n >>>= 0,
            i >>>= 0,
            a >>>= 0,
            this === e)
                return 0;
            for (var o = a - i, s = n - t, l = Math.min(o, s), p = this.slice(i, a), u = e.slice(t, n), c = 0; c < l; ++c)
                if (p[c] !== u[c]) {
                    o = p[c],
                    s = u[c];
                    break
                }
            return o < s ? -1 : s < o ? 1 : 0
        }
        ,
        r.prototype.includes = function(e, t, n) {
            return -1 !== this.indexOf(e, t, n)
        }
        ,
        r.prototype.indexOf = function(e, t, n) {
            return h(this, e, t, n, !0)
        }
        ,
        r.prototype.lastIndexOf = function(e, t, n) {
            return h(this, e, t, n, !1)
        }
        ,
        r.prototype.write = function(e, t, n, i) {
            if (void 0 === t)
                i = "utf8",
                n = this.length,
                t = 0;
            else if (void 0 === n && "string" == typeof t)
                i = t,
                n = this.length,
                t = 0;
            else {
                if (!isFinite(t))
                    throw new Error("Buffer.write(string, encoding, offset[, length]) is no longer supported");
                t |= 0,
                isFinite(n) ? (n |= 0,
                void 0 === i && (i = "utf8")) : (i = n,
                n = void 0)
            }
            var a = this.length - t;
            if ((void 0 === n || n > a) && (n = a),
            e.length > 0 && (n < 0 || t < 0) || t > this.length)
                throw new RangeError("Attempt to write outside buffer bounds");
            i || (i = "utf8");
            for (var r = !1; ; )
                switch (i) {
                case "hex":
                    return g(this, e, t, n);
                case "utf8":
                case "utf-8":
                    return y(this, e, t, n);
                case "ascii":
                    return v(this, e, t, n);
                case "latin1":
                case "binary":
                    return w(this, e, t, n);
                case "base64":
                    return b(this, e, t, n);
                case "ucs2":
                case "ucs-2":
                case "utf16le":
                case "utf-16le":
                    return x(this, e, t, n);
                default:
                    if (r)
                        throw new TypeError("Unknown encoding: " + i);
                    i = ("" + i).toLowerCase(),
                    r = !0
                }
        }
        ,
        r.prototype.toJSON = function() {
            return {
                type: "Buffer",
                data: Array.prototype.slice.call(this._arr || this, 0)
            }
        }
        ;
        var $ = 4096;
        r.prototype.slice = function(e, t) {
            var n = this.length;
            e = ~~e,
            t = void 0 === t ? n : ~~t,
            e < 0 ? (e += n) < 0 && (e = 0) : e > n && (e = n),
            t < 0 ? (t += n) < 0 && (t = 0) : t > n && (t = n),
            t < e && (t = e);
            var i;
            if (r.TYPED_ARRAY_SUPPORT)
                i = this.subarray(e, t),
                i.__proto__ = r.prototype;
            else {
                var a = t - e;
                i = new r(a,void 0);
                for (var o = 0; o < a; ++o)
                    i[o] = this[o + e]
            }
            return i
        }
        ,
        r.prototype.readUIntLE = function(e, t, n) {
            e |= 0,
            t |= 0,
            n || I(e, t, this.length);
            for (var i = this[e], a = 1, r = 0; ++r < t && (a *= 256); )
                i += this[e + r] * a;
            return i
        }
        ,
        r.prototype.readUIntBE = function(e, t, n) {
            e |= 0,
            t |= 0,
            n || I(e, t, this.length);
            for (var i = this[e + --t], a = 1; t > 0 && (a *= 256); )
                i += this[e + --t] * a;
            return i
        }
        ,
        r.prototype.readUInt8 = function(e, t) {
            return t || I(e, 1, this.length),
            this[e]
        }
        ,
        r.prototype.readUInt16LE = function(e, t) {
            return t || I(e, 2, this.length),
            this[e] | this[e + 1] << 8
        }
        ,
        r.prototype.readUInt16BE = function(e, t) {
            return t || I(e, 2, this.length),
            this[e] << 8 | this[e + 1]
        }
        ,
        r.prototype.readUInt32LE = function(e, t) {
            return t || I(e, 4, this.length),
            (this[e] | this[e + 1] << 8 | this[e + 2] << 16) + 16777216 * this[e + 3]
        }
        ,
        r.prototype.readUInt32BE = function(e, t) {
            return t || I(e, 4, this.length),
            16777216 * this[e] + (this[e + 1] << 16 | this[e + 2] << 8 | this[e + 3])
        }
        ,
        r.prototype.readIntLE = function(e, t, n) {
            e |= 0,
            t |= 0,
            n || I(e, t, this.length);
            for (var i = this[e], a = 1, r = 0; ++r < t && (a *= 256); )
                i += this[e + r] * a;
            return a *= 128,
            i >= a && (i -= Math.pow(2, 8 * t)),
            i
        }
        ,
        r.prototype.readIntBE = function(e, t, n) {
            e |= 0,
            t |= 0,
            n || I(e, t, this.length);
            for (var i = t, a = 1, r = this[e + --i]; i > 0 && (a *= 256); )
                r += this[e + --i] * a;
            return a *= 128,
            r >= a && (r -= Math.pow(2, 8 * t)),
            r
        }
        ,
        r.prototype.readInt8 = function(e, t) {
            return t || I(e, 1, this.length),
            128 & this[e] ? -1 * (255 - this[e] + 1) : this[e]
        }
        ,
        r.prototype.readInt16LE = function(e, t) {
            t || I(e, 2, this.length);
            var n = this[e] | this[e + 1] << 8;
            return 32768 & n ? 4294901760 | n : n
        }
        ,
        r.prototype.readInt16BE = function(e, t) {
            t || I(e, 2, this.length);
            var n = this[e + 1] | this[e] << 8;
            return 32768 & n ? 4294901760 | n : n
        }
        ,
        r.prototype.readInt32LE = function(e, t) {
            return t || I(e, 4, this.length),
            this[e] | this[e + 1] << 8 | this[e + 2] << 16 | this[e + 3] << 24
        }
        ,
        r.prototype.readInt32BE = function(e, t) {
            return t || I(e, 4, this.length),
            this[e] << 24 | this[e + 1] << 16 | this[e + 2] << 8 | this[e + 3]
        }
        ,
        r.prototype.readFloatLE = function(e, t) {
            return t || I(e, 4, this.length),
            O.read(this, e, !0, 23, 4)
        }
        ,
        r.prototype.readFloatBE = function(e, t) {
            return t || I(e, 4, this.length),
            O.read(this, e, !1, 23, 4)
        }
        ,
        r.prototype.readDoubleLE = function(e, t) {
            return t || I(e, 8, this.length),
            O.read(this, e, !0, 52, 8)
        }
        ,
        r.prototype.readDoubleBE = function(e, t) {
            return t || I(e, 8, this.length),
            O.read(this, e, !1, 52, 8)
        }
        ,
        r.prototype.writeUIntLE = function(e, t, n, i) {
            e = +e,
            t |= 0,
            n |= 0,
            i || C(this, e, t, n, Math.pow(2, 8 * n) - 1, 0);
            var a = 1
              , r = 0;
            for (this[t] = 255 & e; ++r < n && (a *= 256); )
                this[t + r] = e / a & 255;
            return t + n
        }
        ,
        r.prototype.writeUIntBE = function(e, t, n, i) {
            e = +e,
            t |= 0,
            n |= 0,
            i || C(this, e, t, n, Math.pow(2, 8 * n) - 1, 0);
            var a = n - 1
              , r = 1;
            for (this[t + a] = 255 & e; --a >= 0 && (r *= 256); )
                this[t + a] = e / r & 255;
            return t + n
        }
        ,
        r.prototype.writeUInt8 = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 1, 255, 0),
            r.TYPED_ARRAY_SUPPORT || (e = Math.floor(e)),
            this[t] = 255 & e,
            t + 1
        }
        ,
        r.prototype.writeUInt16LE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 2, 65535, 0),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = 255 & e,
            this[t + 1] = e >>> 8) : j(this, e, t, !0),
            t + 2
        }
        ,
        r.prototype.writeUInt16BE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 2, 65535, 0),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 8,
            this[t + 1] = 255 & e) : j(this, e, t, !1),
            t + 2
        }
        ,
        r.prototype.writeUInt32LE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 4, 4294967295, 0),
            r.TYPED_ARRAY_SUPPORT ? (this[t + 3] = e >>> 24,
            this[t + 2] = e >>> 16,
            this[t + 1] = e >>> 8,
            this[t] = 255 & e) : L(this, e, t, !0),
            t + 4
        }
        ,
        r.prototype.writeUInt32BE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 4, 4294967295, 0),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 24,
            this[t + 1] = e >>> 16,
            this[t + 2] = e >>> 8,
            this[t + 3] = 255 & e) : L(this, e, t, !1),
            t + 4
        }
        ,
        r.prototype.writeIntLE = function(e, t, n, i) {
            if (e = +e,
            t |= 0,
            !i) {
                var a = Math.pow(2, 8 * n - 1);
                C(this, e, t, n, a - 1, -a)
            }
            var r = 0
              , o = 1
              , s = 0;
            for (this[t] = 255 & e; ++r < n && (o *= 256); )
                e < 0 && 0 === s && 0 !== this[t + r - 1] && (s = 1),
                this[t + r] = (e / o >> 0) - s & 255;
            return t + n
        }
        ,
        r.prototype.writeIntBE = function(e, t, n, i) {
            if (e = +e,
            t |= 0,
            !i) {
                var a = Math.pow(2, 8 * n - 1);
                C(this, e, t, n, a - 1, -a)
            }
            var r = n - 1
              , o = 1
              , s = 0;
            for (this[t + r] = 255 & e; --r >= 0 && (o *= 256); )
                e < 0 && 0 === s && 0 !== this[t + r + 1] && (s = 1),
                this[t + r] = (e / o >> 0) - s & 255;
            return t + n
        }
        ,
        r.prototype.writeInt8 = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 1, 127, -128),
            r.TYPED_ARRAY_SUPPORT || (e = Math.floor(e)),
            e < 0 && (e = 255 + e + 1),
            this[t] = 255 & e,
            t + 1
        }
        ,
        r.prototype.writeInt16LE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 2, 32767, -32768),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = 255 & e,
            this[t + 1] = e >>> 8) : j(this, e, t, !0),
            t + 2
        }
        ,
        r.prototype.writeInt16BE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 2, 32767, -32768),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 8,
            this[t + 1] = 255 & e) : j(this, e, t, !1),
            t + 2
        }
        ,
        r.prototype.writeInt32LE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 4, 2147483647, -2147483648),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = 255 & e,
            this[t + 1] = e >>> 8,
            this[t + 2] = e >>> 16,
            this[t + 3] = e >>> 24) : L(this, e, t, !0),
            t + 4
        }
        ,
        r.prototype.writeInt32BE = function(e, t, n) {
            return e = +e,
            t |= 0,
            n || C(this, e, t, 4, 2147483647, -2147483648),
            e < 0 && (e = 4294967295 + e + 1),
            r.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 24,
            this[t + 1] = e >>> 16,
            this[t + 2] = e >>> 8,
            this[t + 3] = 255 & e) : L(this, e, t, !1),
            t + 4
        }
        ,
        r.prototype.writeFloatLE = function(e, t, n) {
            return S(this, e, t, !0, n)
        }
        ,
        r.prototype.writeFloatBE = function(e, t, n) {
            return S(this, e, t, !1, n)
        }
        ,
        r.prototype.writeDoubleLE = function(e, t, n) {
            return T(this, e, t, !0, n)
        }
        ,
        r.prototype.writeDoubleBE = function(e, t, n) {
            return T(this, e, t, !1, n)
        }
        ,
        r.prototype.copy = function(e, t, n, i) {
            if (n || (n = 0),
            i || 0 === i || (i = this.length),
            t >= e.length && (t = e.length),
            t || (t = 0),
            i > 0 && i < n && (i = n),
            i === n)
                return 0;
            if (0 === e.length || 0 === this.length)
                return 0;
            if (t < 0)
                throw new RangeError("targetStart out of bounds");
            if (n < 0 || n >= this.length)
                throw new RangeError("sourceStart out of bounds");
            if (i < 0)
                throw new RangeError("sourceEnd out of bounds");
            i > this.length && (i = this.length),
            e.length - t < i - n && (i = e.length - t + n);
            var a, o = i - n;
            if (this === e && n < t && t < i)
                for (a = o - 1; a >= 0; --a)
                    e[a + t] = this[a + n];
            else if (o < 1e3 || !r.TYPED_ARRAY_SUPPORT)
                for (a = 0; a < o; ++a)
                    e[a + t] = this[a + n];
            else
                Uint8Array.prototype.set.call(e, this.subarray(n, n + o), t);
            return o
        }
        ,
        r.prototype.fill = function(e, t, n, i) {
            if ("string" == typeof e) {
                if ("string" == typeof t ? (i = t,
                t = 0,
                n = this.length) : "string" == typeof n && (i = n,
                n = this.length),
                1 === e.length) {
                    var a = e.charCodeAt(0);
                    a < 256 && (e = a)
                }
                if (void 0 !== i && "string" != typeof i)
                    throw new TypeError("encoding must be a string");
                if ("string" == typeof i && !r.isEncoding(i))
                    throw new TypeError("Unknown encoding: " + i)
            } else
                "number" == typeof e && (e &= 255);
            if (t < 0 || this.length < t || this.length < n)
                throw new RangeError("Out of range index");
            if (n <= t)
                return this;
            t >>>= 0,
            n = void 0 === n ? this.length : n >>> 0,
            e || (e = 0);
            var o;
            if ("number" == typeof e)
                for (o = t; o < n; ++o)
                    this[o] = e;
            else {
                var s = r.isBuffer(e) ? e : P(new r(e,i).toString())
                  , l = s.length;
                for (o = 0; o < n - t; ++o)
                    this[o + t] = s[o % l]
            }
            return this
        }
        ;
        var B = /[^+\/0-9A-Za-z-_]/g
    }
    ).call(t, n(14))
}
, function(e, t, n) {
    "use strict";
    var i, a = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    }
    : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    }
    ;
    i = function() {
        return this
    }();
    try {
        i = i || Function("return this")() || (0,
        eval)("this")
    } catch (e) {
        "object" === ("undefined" == typeof window ? "undefined" : a(window)) && (i = window)
    }
    e.exports = i
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        var t = e.length;
        if (t % 4 > 0)
            throw new Error("Invalid string. Length must be a multiple of 4");
        return "=" === e[t - 2] ? 2 : "=" === e[t - 1] ? 1 : 0
    }
    function a(e) {
        return o[e >> 18 & 63] + o[e >> 12 & 63] + o[e >> 6 & 63] + o[63 & e]
    }
    function r(e, t, n) {
        for (var i, r = [], o = t; o < n; o += 3)
            i = (e[o] << 16) + (e[o + 1] << 8) + e[o + 2],
            r.push(a(i));
        return r.join("")
    }
    t.byteLength = function(e) {
        return 3 * e.length / 4 - i(e)
    }
    ,
    t.toByteArray = function(e) {
        var t, n, a, r, o, p = e.length;
        r = i(e),
        o = new l(3 * p / 4 - r),
        n = r > 0 ? p - 4 : p;
        var u = 0;
        for (t = 0; t < n; t += 4)
            a = s[e.charCodeAt(t)] << 18 | s[e.charCodeAt(t + 1)] << 12 | s[e.charCodeAt(t + 2)] << 6 | s[e.charCodeAt(t + 3)],
            o[u++] = a >> 16 & 255,
            o[u++] = a >> 8 & 255,
            o[u++] = 255 & a;
        return 2 === r ? (a = s[e.charCodeAt(t)] << 2 | s[e.charCodeAt(t + 1)] >> 4,
        o[u++] = 255 & a) : 1 === r && (a = s[e.charCodeAt(t)] << 10 | s[e.charCodeAt(t + 1)] << 4 | s[e.charCodeAt(t + 2)] >> 2,
        o[u++] = a >> 8 & 255,
        o[u++] = 255 & a),
        o
    }
    ,
    t.fromByteArray = function(e) {
        for (var t, n = e.length, i = n % 3, a = "", s = [], l = 0, p = n - i; l < p; l += 16383)
            s.push(r(e, l, l + 16383 > p ? p : l + 16383));
        return 1 === i ? (t = e[n - 1],
        a += o[t >> 2],
        a += o[t << 4 & 63],
        a += "==") : 2 === i && (t = (e[n - 2] << 8) + e[n - 1],
        a += o[t >> 10],
        a += o[t >> 4 & 63],
        a += o[t << 2 & 63],
        a += "="),
        s.push(a),
        s.join("")
    }
    ;
    for (var o = [], s = [], l = "undefined" != typeof Uint8Array ? Uint8Array : Array, p = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", u = 0, c = p.length; u < c; ++u)
        o[u] = p[u],
        s[p.charCodeAt(u)] = u;
    s["-".charCodeAt(0)] = 62,
    s["_".charCodeAt(0)] = 63
}
, function(e, t, n) {
    "use strict";
    t.read = function(e, t, n, i, a) {
        var r, o, s = 8 * a - i - 1, l = (1 << s) - 1, p = l >> 1, u = -7, c = n ? a - 1 : 0, d = n ? -1 : 1, f = e[t + c];
        for (c += d,
        r = f & (1 << -u) - 1,
        f >>= -u,
        u += s; u > 0; r = 256 * r + e[t + c],
        c += d,
        u -= 8)
            ;
        for (o = r & (1 << -u) - 1,
        r >>= -u,
        u += i; u > 0; o = 256 * o + e[t + c],
        c += d,
        u -= 8)
            ;
        if (0 === r)
            r = 1 - p;
        else {
            if (r === l)
                return o ? NaN : 1 / 0 * (f ? -1 : 1);
            o += Math.pow(2, i),
            r -= p
        }
        return (f ? -1 : 1) * o * Math.pow(2, r - i)
    }
    ,
    t.write = function(e, t, n, i, a, r) {
        var o, s, l, p = 8 * r - a - 1, u = (1 << p) - 1, c = u >> 1, d = 23 === a ? Math.pow(2, -24) - Math.pow(2, -77) : 0, f = i ? 0 : r - 1, h = i ? 1 : -1, m = t < 0 || 0 === t && 1 / t < 0 ? 1 : 0;
        for (t = Math.abs(t),
        isNaN(t) || t === 1 / 0 ? (s = isNaN(t) ? 1 : 0,
        o = u) : (o = Math.floor(Math.log(t) / Math.LN2),
        t * (l = Math.pow(2, -o)) < 1 && (o--,
        l *= 2),
        (t += o + c >= 1 ? d / l : d * Math.pow(2, 1 - c)) * l >= 2 && (o++,
        l /= 2),
        o + c >= u ? (s = 0,
        o = u) : o + c >= 1 ? (s = (t * l - 1) * Math.pow(2, a),
        o += c) : (s = t * Math.pow(2, c - 1) * Math.pow(2, a),
        o = 0)); a >= 8; e[n + f] = 255 & s,
        f += h,
        s /= 256,
        a -= 8)
            ;
        for (o = o << a | s,
        p += a; p > 0; e[n + f] = 255 & o,
        f += h,
        o /= 256,
        p -= 8)
            ;
        e[n + f - h] |= 128 * m
    }
}
, function(e, t, n) {
    "use strict";
    var i = {}.toString;
    e.exports = Array.isArray || function(e) {
        return "[object Array]" == i.call(e)
    }
}
, function(e, t, n) {
    function i(e, t) {
        for (var n = 0; n < e.length; n++) {
            var i = e[n]
              , a = c[i.id];
            if (a) {
                a.refs++;
                for (var r = 0; r < a.parts.length; r++)
                    a.parts[r](i.parts[r]);
                for (; r < i.parts.length; r++)
                    a.parts.push(p(i.parts[r], t))
            } else {
                var o = [];
                for (r = 0; r < i.parts.length; r++)
                    o.push(p(i.parts[r], t));
                c[i.id] = {
                    id: i.id,
                    refs: 1,
                    parts: o
                }
            }
        }
    }
    function a(e, t) {
        for (var n = [], i = {}, a = 0; a < e.length; a++) {
            var r = e[a]
              , o = t.base ? r[0] + t.base : r[0]
              , s = {
                css: r[1],
                media: r[2],
                sourceMap: r[3]
            };
            i[o] ? i[o].parts.push(s) : n.push(i[o] = {
                id: o,
                parts: [s]
            })
        }
        return n
    }
    function r(e, t) {
        var n = f(e.insertInto);
        if (!n)
            throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
        var i = g[g.length - 1];
        if ("top" === e.insertAt)
            i ? i.nextSibling ? n.insertBefore(t, i.nextSibling) : n.appendChild(t) : n.insertBefore(t, n.firstChild),
            g.push(t);
        else if ("bottom" === e.insertAt)
            n.appendChild(t);
        else {
            if ("object" != typeof e.insertAt || !e.insertAt.before)
                throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
            var a = f(e.insertInto + " " + e.insertAt.before);
            n.insertBefore(t, a)
        }
    }
    function o(e) {
        if (null === e.parentNode)
            return !1;
        e.parentNode.removeChild(e);
        var t = g.indexOf(e);
        t >= 0 && g.splice(t, 1)
    }
    function s(e) {
        var t = document.createElement("style");
        return e.attrs.type = "text/css",
        l(t, e.attrs),
        r(e, t),
        t
    }
    function l(e, t) {
        Object.keys(t).forEach(function(n) {
            e.setAttribute(n, t[n])
        })
    }
    function p(e, t) {
        var n, i, a, p;
        if (t.transform && e.css) {
            if (!(p = t.transform(e.css)))
                return function() {}
                ;
            e.css = p
        }
        if (t.singleton) {
            var c = m++;
            n = h || (h = s(t)),
            i = u.bind(null, n, c, !1),
            a = u.bind(null, n, c, !0)
        } else
            e.sourceMap && "function" == typeof URL && "function" == typeof URL.createObjectURL && "function" == typeof URL.revokeObjectURL && "function" == typeof Blob && "function" == typeof btoa ? (n = function(e) {
                var t = document.createElement("link");
                return e.attrs.type = "text/css",
                e.attrs.rel = "stylesheet",
                l(t, e.attrs),
                r(e, t),
                t
            }(t),
            i = function(e, t, n) {
                var i = n.css
                  , a = n.sourceMap
                  , r = void 0 === t.convertToAbsoluteUrls && a;
                (t.convertToAbsoluteUrls || r) && (i = y(i)),
                a && (i += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(a)))) + " */");
                var o = new Blob([i],{
                    type: "text/css"
                })
                  , s = e.href;
                e.href = URL.createObjectURL(o),
                s && URL.revokeObjectURL(s)
            }
            .bind(null, n, t),
            a = function() {
                o(n),
                n.href && URL.revokeObjectURL(n.href)
            }
            ) : (n = s(t),
            i = function(e, t) {
                var n = t.css
                  , i = t.media;
                if (i && e.setAttribute("media", i),
                e.styleSheet)
                    e.styleSheet.cssText = n;
                else {
                    for (; e.firstChild; )
                        e.removeChild(e.firstChild);
                    e.appendChild(document.createTextNode(n))
                }
            }
            .bind(null, n),
            a = function() {
                o(n)
            }
            );
        return i(e),
        function(t) {
            if (t) {
                if (t.css === e.css && t.media === e.media && t.sourceMap === e.sourceMap)
                    return;
                i(e = t)
            } else
                a()
        }
    }
    function u(e, t, n, i) {
        var a = n ? "" : i.css;
        if (e.styleSheet)
            e.styleSheet.cssText = v(t, a);
        else {
            var r = document.createTextNode(a)
              , o = e.childNodes;
            o[t] && e.removeChild(o[t]),
            o.length ? e.insertBefore(r, o[t]) : e.appendChild(r)
        }
    }
    var c = {}
      , d = function(e) {
        var t;
        return function() {
            return void 0 === t && (t = function() {
                return window && document && document.all && !window.atob
            }
            .apply(this, arguments)),
            t
        }
    }()
      , f = function(e) {
        var t = {};
        return function(e) {
            if (void 0 === t[e]) {
                var n = function(e) {
                    return document.querySelector(e)
                }
                .call(this, e);
                if (n instanceof window.HTMLIFrameElement)
                    try {
                        n = n.contentDocument.head
                    } catch (e) {
                        n = null
                    }
                t[e] = n
            }
            return t[e]
        }
    }()
      , h = null
      , m = 0
      , g = []
      , y = n(19);
    e.exports = function(e, t) {
        if ("undefined" != typeof DEBUG && DEBUG && "object" != typeof document)
            throw new Error("The style-loader cannot be used in a non-browser environment");
        (t = t || {}).attrs = "object" == typeof t.attrs ? t.attrs : {},
        t.singleton || "boolean" == typeof t.singleton || (t.singleton = d()),
        t.insertInto || (t.insertInto = "head"),
        t.insertAt || (t.insertAt = "bottom");
        var n = a(e, t);
        return i(n, t),
        function(e) {
            for (var r = [], o = 0; o < n.length; o++) {
                var s = n[o];
                (l = c[s.id]).refs--,
                r.push(l)
            }
            e && i(a(e, t), t);
            for (o = 0; o < r.length; o++) {
                var l;
                if (0 === (l = r[o]).refs) {
                    for (var p = 0; p < l.parts.length; p++)
                        l.parts[p]();
                    delete c[l.id]
                }
            }
        }
    }
    ;
    var v = function() {
        var e = [];
        return function(t, n) {
            return e[t] = n,
            e.filter(Boolean).join("\n")
        }
    }()
}
, function(e, t, n) {
    "use strict";
    e.exports = function(e) {
        var t = "undefined" != typeof window && window.location;
        if (!t)
            throw new Error("fixUrls requires window.location");
        if (!e || "string" != typeof e)
            return e;
        var n = t.protocol + "//" + t.host
          , i = n + t.pathname.replace(/\/[^\/]*$/, "/");
        return e.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(e, t) {
            var a = t.trim().replace(/^"(.*)"$/, function(e, t) {
                return t
            }).replace(/^'(.*)'$/, function(e, t) {
                return t
            });
            if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(a))
                return e;
            var r;
            return r = 0 === a.indexOf("//") ? a : 0 === a.indexOf("/") ? n + a : i + a.replace(/^\.\//, ""),
            "url(" + JSON.stringify(r) + ")"
        })
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(21))
      , o = i(n(22))
      , s = i(n(23))
      , l = i(n(25))
      , p = i(n(26))
      , u = i(n(27))
      , c = i(n(70))
      , d = i(n(72))
      , f = i(n(0))
      , h = i(n(74))
      , m = i(n(3))
      , g = i(n(75))
      , y = i(n(76))
      , v = function() {
        function e(t, n, i) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.id = t,
            this.$parent = n,
            this.options = this.formatOptions(this.adaptOptions(i)),
            this.$window = jQuery(window),
            this.tuner = new o.default(this.options),
            this.cache = new r.default(f.default.alias),
            this.init()
        }
        return a(e, [{
            key: "init",
            value: function() {
                this.$parent.attr("id", f.default.alias + "-" + this.id),
                this.$element = this.createElement(),
                this.$titleContainer = this.$element.find("." + f.default.alias + "-title-container"),
                this.$postsContainer = this.$element.find("." + f.default.alias + "-posts-container"),
                this.$errorContainer = this.$element.find("." + f.default.alias + "-error-container"),
                this.$loader = this.$element.find("." + f.default.alias + "-loader"),
                this.$element.appendTo(this.$parent),
                this.error = new s.default(this.$errorContainer),
                this.stylize(),
                this.tune(),
                this.initDebug(),
                this.initLang(),
                this.checkErrors() || (this.initLayout(),
                this.initTitle())
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                g.default)())
            }
        }, {
            key: "stylize",
            value: function() {
                this.style = new p.default({
                    parent: this.$parent[0],
                    template: y.default,
                    props: jQuery.extend({}, {
                        id: this.id,
                        className: f.default.alias,
                        feedId: "#" + f.default.alias + "-" + this.id,
                        feedPrefix: "." + f.default.alias,
                        popupId: "#" + f.default.alias + "-popup-" + this.id,
                        popupPrefix: "." + f.default.alias + "-popup"
                    }, this.tuner.options)
                })
            }
        }, {
            key: "tune",
            value: function() {
                var e = this;
                this.tuner.change(["accessToken", "source", "filterOnly", "filterExcept", "filter", "limit", "layout", "postTemplate", "columns", "rows", "gutter", "responsive", "callToActionButtons", "postElements", "imageClickAction", "sliderArrows", "sliderDrag", "sliderSpeed", "sliderAutoplay"], function() {
                    e.checkErrors() || e.initLayout()
                }),
                this.tuner.change(["popupElements"], function() {
                    e.reinitPopup()
                }),
                this.tuner.change(["widgetTitle"], function() {
                    return e.initTitle()
                }),
                this.tuner.change(["lang"], function() {
                    e.initLang(),
                    e.initLayout()
                }),
                this.tuner.change(["width", "colorPostBg", "colorPostText", "colorPostLinks", "colorPostOverlayBg", "colorPostOverlayText", "colorSliderArrows", "colorSliderArrowsBg", "colorGridLoadMoreButton", "colorPopupOverlay", "colorPopupBg", "colorPopupText", "colorPopupLinks", "colorPopupFollowButton", "colorPopupCtaButton"], function(t, n) {
                    return e.style.update(e.tuner.options)
                }),
                this.tuner.change(["debug"], function() {
                    return e.initDebug()
                })
            }
        }, {
            key: "initLang",
            value: function() {
                this.lang = new l.default(this.tuner.get("lang"),h.default)
            }
        }, {
            key: "initLayout",
            value: function() {
                this.layout && (this.layout.destroy(),
                delete this.layout,
                this.$postsContainer.empty());
                var e = this.tuner.get("layout")
                  , t = {
                    slider: u.default,
                    grid: c.default
                };
                this.layout = new t[e](this),
                this.layout.init(),
                this.hideError()
            }
        }, {
            key: "initTitle",
            value: function() {
                var e = this;
                this.$titleContainer.empty(),
                this.tuner.get("widgetTitle") ? (this.title = new d.default(this),
                this.$titleContainer.append(this.title.$element),
                setTimeout(function() {
                    e.title.show()
                })) : this.title = null
            }
        }, {
            key: "reinitPopup",
            value: function() {
                this.layout && this.layout.popup && (this.layout.popup.clearItems(),
                this.layout.popup.renderItems(),
                this.layout.popup.moveToItem(0),
                this.layout.popup.setCurrentItem())
            }
        }, {
            key: "setOption",
            value: function(e, t) {
                var n = {};
                n[e] = t,
                this.setOptions(n)
            }
        }, {
            key: "setOptions",
            value: function(e) {
                this.tuner.setMany(this.formatOptions(e))
            }
        }, {
            key: "initDebug",
            value: function() {
                var e = this.tuner.get("debug");
                this.$element.toggleClass(f.default.alias + "-debug", !!e)
            }
        }, {
            key: "checkErrors",
            value: function() {
                var e = null
                  , t = this.tuner.get("source")
                  , n = this.tuner.get("accessToken");
                return Array.isArray(t) && 0 !== t.length || n || (e = "Please set a correct Instagram source"),
                e && this.showError(e),
                e
            }
        }, {
            key: "hideError",
            value: function() {
                this.error.hide(),
                this.$element.removeClass(f.default.alias + "-has-error")
            }
        }, {
            key: "showError",
            value: function(e) {
                this.error.show(e),
                this.$element.addClass(f.default.alias + "-has-error")
            }
        }, {
            key: "formatOptions",
            value: function(e) {
                return ["source", "filterOnly", "filterExcept", "postElements", "popupElements"].forEach(function(t) {
                    e.hasOwnProperty(t) && "string" == typeof e[t] && (e[t] = e[t].split(/[\s,;\|]+/).filter(function(e) {
                        return !!e
                    }))
                }),
                e.hasOwnProperty("limit") && (e.limit = parseInt(e.limit, 10)),
                e.hasOwnProperty("width") && ("auto" === e.width && (e.width = "100%"),
                isNaN(e.width) || (e.width = parseInt(e.width, 10) + "px")),
                e.hasOwnProperty("layout") && -1 === ["slider", "grid"].indexOf(e.layout) && (e.layout = m.default.layout),
                e.hasOwnProperty("postTemplate") && -1 === ["tile", "classic"].indexOf(e.postTemplate) && (e.postTemplate = m.default.postTemplate),
                e.hasOwnProperty("sliderAutoplay") && (e.sliderAutoplay = parseFloat(e.sliderAutoplay)),
                e.hasOwnProperty("sliderSpeed") && (e.sliderSpeed = parseFloat(e.sliderSpeed)),
                e.hasOwnProperty("cacheTime") && (e.cacheTime = parseInt(e.cacheTime, 10)),
                e
            }
        }, {
            key: "adaptOptions",
            value: function(e) {
                var t = {
                    postElements: "info",
                    popupElements: "popupInfo",
                    sliderArrows: "arrowsControl",
                    sliderDrag: "dragControl",
                    sliderSpeed: "speed",
                    sliderAutoplay: "auto",
                    imageClickAction: "mode",
                    cacheTime: "cacheMediaTime",
                    colorPostOverlayText: "colorGalleryDescription",
                    colorPostOverlayBg: "colorGalleryOverlay",
                    colorSliderArrows: "colorGalleryArrows",
                    colorSliderArrowsBg: "colorGalleryArrowsBg",
                    colorPopupLinks: "colorPopupAnchor",
                    colorPopupFollowButton: "colorPopupInstagramLink"
                };
                for (var n in t) {
                    var i = t[n];
                    e.hasOwnProperty(i) && ("info" !== i && "popupInfo" !== i || (Array.isArray(e[i]) && (e[i] = e[i].join(" ")),
                    e[i] = (e[i] || "").replace("username", "user").replace("likesCounter", "likesCount").replace("commentsCounter", "commentsCount").replace("description", "text"),
                    e[i] = e[i].split(" ")),
                    "auto" !== i && "speed" !== i || (e[i] = parseFloat((parseInt(e[i], 10) / 1e3).toFixed(2))),
                    e[n] = e[i])
                }
                return e
            }
        }]),
        e
    }();
    t.default = v
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB,
            this.db = null,
            this.cacheBaseId = t + "Cache"
        }
        return i(e, [{
            key: "isSupported",
            value: function() {
                return !!this.indexedDB
            }
        }, {
            key: "isConnected",
            value: function() {
                return !!this.db
            }
        }, {
            key: "connect",
            value: function(e) {
                var t = this;
                if (e = e || jQuery.Deferred(),
                this.isConnected())
                    e.resolve();
                else if (this.isSupported()) {
                    var n = void 0;
                    try {
                        (n = this.indexedDB.open(this.cacheBaseId, 1)).onsuccess = function() {
                            t.db = n.result,
                            e.resolve()
                        }
                        ,
                        n.onerror = function() {
                            e.reject()
                        }
                        ,
                        n.onupgradeneeded = function(n) {
                            n.currentTarget.result.createObjectStore("Items", {
                                keyPath: "key"
                            }).createIndex("key", "key", {
                                unique: !0
                            }),
                            t.connect(e)
                        }
                    } catch (t) {
                        e.reject()
                    }
                } else
                    e.reject();
                return e.promise()
            }
        }, {
            key: "save",
            value: function(e, t) {
                if (this.isConnected()) {
                    var n = this.db.transaction("Items", "readwrite")
                      , i = {
                        key: e,
                        result: t,
                        date: Math.floor(Date.now() / 1e3)
                    };
                    n.objectStore("Items").put(i)
                }
            }
        }, {
            key: "getSaved",
            value: function(e, t, n) {
                var i = this;
                n = n || jQuery.Deferred();
                try {
                    var a = void 0
                      , r = void 0;
                    this.isConnected() && t ? (a = this.db.transaction(["Items"], "readonly"),
                    r = a.objectStore("Items").get(e),
                    r.onsuccess = function() {
                        var a = r.result;
                        a && a.date + t > Math.floor(Date.now() / 1e3) ? n.resolve(a.result) : (i.remove(e),
                        n.reject())
                    }
                    ,
                    r.onerror = function() {
                        n.reject()
                    }
                    ) : n.reject()
                } catch (e) {
                    n.reject()
                }
                return n.promise()
            }
        }, {
            key: "remove",
            value: function(e, t) {
                t = t || jQuery.Deferred();
                var n = void 0
                  , i = void 0;
                return this.isConnected() ? (n = this.db.transaction(["Items"], "readwrite"),
                i = n.objectStore("Items").delete(e),
                i.onsuccess = function() {
                    t.resolve()
                }
                ,
                i.onerror = function() {
                    t.reject()
                }
                ) : t.reject(),
                t.promise()
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    }
    : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    }
      , a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e);
            this.options = t || {},
            this.listeners = {},
            this.casts = {},
            this.casters = {
                int: function(e) {
                    return parseInt(e, 10)
                },
                bool: function(e) {
                    return !!e
                }
            }
        }
        return a(e, [{
            key: "get",
            value: function(e) {
                var t = this.options[e]
                  , n = this.casts[e];
                if (!n)
                    return t;
                var i = this.casters[n];
                if (!i)
                    throw new Error('Can`t cast "' + e + '" to "' + n + '", caster is undefined.');
                return i(t)
            }
        }, {
            key: "set",
            value: function(e, t) {
                if (this.isPlainObject(e))
                    this.setMany(e);
                else if (e) {
                    var n = this.options[e];
                    this.options[e] = t,
                    this.optionChanged(e, t, n)
                }
            }
        }, {
            key: "setMany",
            value: function(e) {
                var t = this;
                Object.keys(e).forEach(function(n) {
                    t.set(n, e[n])
                })
            }
        }, {
            key: "change",
            value: function(e, t) {
                Array.isArray(e) ? this.changeMany(e, t) : (this.listeners[e] || (this.listeners[e] = []),
                this.listeners[e].push(t))
            }
        }, {
            key: "changeMany",
            value: function(e, t) {
                var n = this;
                e.forEach(function(e) {
                    n.change(e, t)
                })
            }
        }, {
            key: "optionChanged",
            value: function(e, t, n) {
                this.listeners[e] && this.listeners[e].length && this.listeners[e].forEach(function(i) {
                    i(e, t, n)
                })
            }
        }, {
            key: "castOption",
            value: function(e, t) {
                this.isPlainObject(e) ? this.castManyOptions(e) : this.casts[e] = t
            }
        }, {
            key: "castManyOptions",
            value: function(e) {
                var t = this;
                Object.keys(e).forEach(function(n) {
                    t.castOption(n, e[n])
                })
            }
        }, {
            key: "trigger",
            value: function(e) {
                var t = this;
                t.listeners[e] && t.listeners[e].length && t.listeners[e].forEach(function(n) {
                    n(e, t.get(e))
                })
            }
        }, {
            key: "triggerMany",
            value: function(e) {
                var t = this;
                e.forEach(function(e) {
                    t.trigger(e)
                })
            }
        }, {
            key: "isPlainObject",
            value: function(e) {
                return !!e && "object" === (void 0 === e ? "undefined" : i(e)) && "[object Object]" === Object.prototype.toString.call(e)
            }
        }]),
        e
    }();
    t.default = r
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(24))
      , r = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.$parent = t
        }
        return i(e, [{
            key: "show",
            value: function(e) {
                var t = jQuery((0,
                a.default)({
                    message: e
                }));
                this.$parent && t.appendTo(this.$parent)
            }
        }, {
            key: "hide",
            value: function() {
                this.$parent && this.$parent.empty()
            }
        }]),
        e
    }();
    t.default = r
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eui-error">\n    ' + e.message + "\n</div>"
    }
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.dictionary = n,
            this.dictionary[t] ? (this.lang = t,
            this.langDictionary = this.dictionary[this.lang]) : (this.lang = "en",
            this.langDictionary = this.dictionary.en)
        }
        return i(e, [{
            key: "parse",
            value: function(e, t) {
                if (t[1])
                    for (var n in t)
                        e = e.replace("%" + ++n, t[n] || "");
                return e
            }
        }, {
            key: "get",
            value: function(e) {
                return e && 0 !== e.length ? "en" === this.lang ? this.parse(e, arguments) : this.langDictionary.hasOwnProperty(e) ? this.parse(this.langDictionary[e], arguments) : e : e
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.element = null,
            this.params = t,
            this.template = t.template ? t.template : null,
            this.parent = t.parent ? t.parent : document.body,
            this.props = t.props ? t.props : {},
            this.render()
        }
        return i(e, [{
            key: "update",
            value: function(e) {
                this.element && this.remove(),
                this.props = Object.assign(this.props, e),
                this.render()
            }
        }, {
            key: "render",
            value: function() {
                var e = document.createElement("div");
                e.innerHTML = this.template(this.props),
                this.element = e.firstChild,
                this.parent.appendChild(this.element)
            }
        }, {
            key: "remove",
            value: function() {
                this.element.parentNode.removeChild(this.element)
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = function e(t, n, i) {
        null === t && (t = Function.prototype);
        var a = Object.getOwnPropertyDescriptor(t, n);
        if (void 0 === a) {
            var r = Object.getPrototypeOf(t);
            return null === r ? void 0 : e(r, n, i)
        }
        if ("value"in a)
            return a.value;
        var o = a.get;
        return void 0 !== o ? o.call(i) : void 0
    }
      , o = i(n(4))
      , s = i(n(67))
      , l = i(n(69))
      , p = function(e) {
        function t() {
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t),
            function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).apply(this, arguments))
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, o.default),
        a(t, [{
            key: "init",
            value: function() {
                var e = this;
                return r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "init", this).call(this),
                this.addView().then(function() {
                    var t = {
                        arrows: e.tuner.get("sliderArrows"),
                        drag: e.tuner.get("sliderDrag"),
                        speed: 1e3 * e.tuner.get("sliderSpeed"),
                        autoplayDelay: 1e3 * e.tuner.get("sliderAutoplay"),
                        loop: !0
                    };
                    e.slider = new s.default(e,e.$element,t),
                    setTimeout(function() {
                        e.slider.init(),
                        e.watch()
                    })
                })
            }
        }, {
            key: "addView",
            value: function() {
                return this.showLoader(),
                r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "addView", this).call(this)
            }
        }, {
            key: "createView",
            value: function() {
                var e = r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "createView", this).call(this);
                return setTimeout(function() {
                    e && e.$element.addClass("eui-slider-slide")
                }),
                e
            }
        }, {
            key: "rebuildViews",
            value: function() {
                var e = this;
                return r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "rebuildViews", this).call(this).then(function() {
                    e.slider.loop && e.slider.enableLoop(),
                    e.slider.moveTo(0)
                })
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                l.default)())
            }
        }]),
        t
    }();
    t.default = p
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(29))
      , o = i(n(35))
      , s = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.widget = t,
            this.tuner = t.tuner,
            this.cache = t.cache,
            this.error = t.error,
            this.lang = t.lang,
            this.$window = t.$window
        }
        return a(e, [{
            key: "init",
            value: function() {
                this.items = [],
                this.fetcher = new r.default(this),
                this.actions = this.tuner.get("callToActionButtons") || []
            }
        }, {
            key: "addItems",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                return e ? this.fetcher.fetch(e).then(function(e) {
                    if (e instanceof Array) {
                        var i = [];
                        e.forEach(function(e) {
                            t.actions.forEach(function(t) {
                                e.link === t.postUrl && (e.callToAction = t)
                            }),
                            i.push(new o.default(jQuery.extend({}, e),t))
                        }),
                        Array.prototype.push.apply(t.items, i),
                        n.resolve(i)
                    }
                }, function(e) {
                    return n.reject(e)
                }) : n.resolve([]),
                n.promise()
            }
        }]),
        e
    }();
    t.default = s
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    }
    : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    }
      , r = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , o = i(n(30))
      , s = i(n(33))
      , l = i(n(5))
      , p = i(n(34))
      , u = i(n(0))
      , c = [{
        type: "user",
        regex: /^@([^$]+)$/,
        index: 1
    }, {
        type: "tag",
        regex: /^#([^$]+)$/,
        index: 1
    }, {
        type: "specific_media_shortcode",
        regex: /^\$([^$]+)$/i,
        index: 1
    }, {
        type: "user",
        regex: /^https?\:\/\/(www\.)?instagram.com\/([^\/]+)\/?(\?[^\$]+)?$/,
        index: 2
    }, {
        type: "tag",
        regex: /^https?\:\/\/(www\.)?instagram.com\/explore\/tags\/([^\/]+)\/?(\?[^\$]+)?$/,
        index: 2
    }, {
        type: "specific_media_shortcode",
        regex: /^https?\:\/\/(www\.)?instagram.com\/p\/([^\/]+)\/?(\?[^\$]+)?$/,
        index: 2
    }, {
        type: "location",
        regex: /^https?\:\/\/(www\.)?instagram.com\/explore\/locations\/([^\/]+)\/?[^\/]*\/?(\?[^\$]+)?$/,
        index: 2
    }, {
        type: "user",
        regex: /^([^$]+)$/,
        index: 1
    }]
      , d = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.tuner = t.tuner,
            this.error = t.error,
            this.cache = t.cache,
            this.apiUrl = this.tuner.get("api"),
            this.isAlternativeApi = this.apiUrl && this.apiUrl !== u.default.apiUrl,
            this.accessToken = this.tuner.get("accessToken"),
            this.source = this.tuner.get("source"),
            this.limit = this.tuner.get("limit"),
            this.isSandbox() && (this.source = ["@self"]),
            this.filters = this.getFilters(),
            this.items = [],
            this.cursor = 0,
            this.maxCount = 33,
            this.users = [],
            this.fetcherControllers = [],
            this.disabledFetcherControllers = [],
            this.createFetchers(),
            this.fetchUser = this.checkUserFetch()
        }
        return r(e, [{
            key: "createScheme",
            value: function(e) {
                var t = [];
                return e.length ? (e.forEach(function(e) {
                    var n = void 0;
                    if ("string" == typeof (n = "object" === (void 0 === e ? "undefined" : a(e)) ? e.source : e)) {
                        var i = void 0
                          , r = void 0;
                        c.forEach(function(e) {
                            if (!i) {
                                var t = n.match(e.regex);
                                t && t[e.index] && (i = e.type,
                                r = t[e.index])
                            }
                        }),
                        i && ("specific_media_shortcode" !== i && (r = r.toLowerCase()),
                        t.push({
                            type: i,
                            name: r,
                            source: n
                        }))
                    }
                }),
                t) : t
            }
        }, {
            key: "createFetchers",
            value: function() {
                var e = this;
                this.sourceScheme = this.createScheme(this.source),
                this.sourceScheme && this.sourceScheme.length && this.sourceScheme.forEach(function(t) {
                    var n = void 0;
                    switch (t.type) {
                    default:
                        break;
                    case "user":
                        n = new o.default(e,t.name,e.filterData.bind(e));
                        break;
                    case "tag":
                        n = new p.default(e,t.name,e.filterData.bind(e));
                        break;
                    case "location":
                        n = new s.default(e,t.name,e.filterData.bind(e));
                        break;
                    case "specific_media_shortcode":
                        n = new l.default(e,t.name,e.filterData.bind(e))
                    }
                    e.fetcherControllers.push({
                        fetcher: n,
                        source: t.source,
                        stack: [],
                        disabled: !1
                    })
                })
            }
        }, {
            key: "getFilters",
            value: function() {
                var e = {};
                return e.filtersScheme = this.createFiltersScheme({
                    only: this.tuner.get("filterOnly") || null,
                    except: this.tuner.get("filterExcept") || null
                }),
                e.customFilter = this.tuner.get("filter"),
                "string" === jQuery.type(e.customFilter) && "function" === jQuery.type(window[e.customFilter]) && (e.customFilter = window[e.customFilter]),
                e
            }
        }, {
            key: "createFiltersScheme",
            value: function(e) {
                var t = this
                  , n = [];
                return e && "object" === (void 0 === e ? "undefined" : a(e)) && jQuery.each(e, function(e, i) {
                    if (i && i.length) {
                        var a = t.createScheme(i);
                        jQuery.each(a, function(t, n) {
                            n.logic = e
                        }),
                        Array.prototype.push.apply(n, a)
                    }
                }),
                n
            }
        }, {
            key: "filterData",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                if (this.filters.filtersScheme) {
                    var i = {}
                      , a = [];
                    jQuery.each(this.filters.filtersScheme, function(e, n) {
                        void 0 === i[n.type] && (i[n.type] = {}),
                        void 0 === i[n.type][n.logic] && (i[n.type][n.logic] = []);
                        var r = n.name;
                        if ("user" === n.type) {
                            if (!t.accessToken) {
                                var o = t.getUserByName(r).then(function(e) {
                                    i[n.type][n.logic].push(e.id)
                                }, function() {
                                    t.filters.filtersScheme.splice(e, 1)
                                });
                                a.push(o)
                            }
                        } else
                            i[n.type][n.logic].push(r)
                    }),
                    jQuery.when.apply(jQuery, a).always(function() {
                        n.resolve(e.filter(function(e) {
                            var n = !0;
                            return jQuery.each(i, function(t, i) {
                                switch (t) {
                                case "specific_media_shortcode":
                                    n &= !i.only || i.only.some(function(t) {
                                        return !!~e.link.indexOf(t)
                                    }),
                                    n &= !(i.except && i.except.some(function(t) {
                                        return !!~e.link.indexOf(t)
                                    }));
                                    break;
                                case "tag":
                                    e.tags = e.tags || [],
                                    e.tags = e.tags.map(function(e) {
                                        return e.toLowerCase()
                                    }),
                                    i.only && jQuery.each(i.only, function(t, i) {
                                        n &= !!~e.tags.indexOf(i)
                                    }),
                                    n &= !(i.except && i.except.some(function(t) {
                                        return !!~e.tags.indexOf(t)
                                    }));
                                    break;
                                case "user":
                                    n &= !(i.only && i.only.length && !~i.only.indexOf(e.user.id)),
                                    n &= !(i.except && i.except.length && ~i.except.indexOf(e.user.id));
                                    break;
                                case "location":
                                    e.location ? (n &= !i.only || !!~i.only.indexOf(e.location.id),
                                    n &= !i.except || !~i.except.indexOf(e.location.id)) : n = !1
                                }
                            }),
                            n && "function" === jQuery.type(t.filters.customFilter) && (n = !!t.filters.customFilter(e)),
                            n
                        }))
                    })
                } else
                    n.resolve(e);
                return n.promise()
            }
        }, {
            key: "fetch",
            value: function(e, t) {
                var n = this;
                e = e || this.maxCount,
                t = t || jQuery.Deferred(),
                this.fetcherControllers.length || t.reject("Please set Instagram source (@username, #hashtag, location URL or post URL)."),
                this.limit && (this.cursor >= this.limit ? t.resolve([]) : e = Math.min(this.limit - this.items.length, e));
                var i = []
                  , a = [];
                return this.fetcherControllers.forEach(function(t) {
                    if (!t.disabled) {
                        var r = e - t.stack.length;
                        if (r > 0) {
                            var o = jQuery.Deferred(function(e) {
                                return t.fetcher.fetch(r).then(function(n) {
                                    n && n.length && (t.stack = t.stack.concat(n)),
                                    e.resolve()
                                }, function(i) {
                                    var r = "The service is temporarily unavailable. Please contact support@elfsight.com.";
                                    i.meta && i.meta.error_message && (r = 403 === i.meta.code ? "<div><strong>" + t.source + ":</strong> is a private account and can't be viewed</div>" : "<div><strong>" + t.source + ":</strong> " + i.meta.error_message + "</div>"),
                                    a.push(r),
                                    n.disableFetcherController(t),
                                    e.resolve()
                                })
                            }).promise();
                            i.push(o)
                        }
                    }
                }),
                jQuery.when.apply(jQuery, i).then(function() {
                    if (a.length === n.fetcherControllers.length)
                        t.reject(a.join(" "));
                    else {
                        var i = [];
                        n.fetcherControllers.forEach(function(e) {
                            if (!e.disabled) {
                                var t = n.removeItems(e.stack, n.items);
                                t = n.removeItems(t, i),
                                i = i.concat(t)
                            }
                        }),
                        i.sort(function(e, t) {
                            return t.created_time - e.created_time
                        }),
                        i = i.slice(0, e),
                        n.fetcherControllers.forEach(function(e) {
                            e.disabled || (e.stack = n.removeItems(e.stack, i)),
                            e.stack.length || e.fetcher.hasNext() || n.disableFetcherController(e)
                        }),
                        n.fillItemsUser(i).then(function() {
                            n.items = n.items.concat(i),
                            n.cursor += i.length,
                            n.disabledFetcherControllers.length !== n.fetcherControllers.length || n.items.length || t.reject("No posts found by your specified sources."),
                            t.resolve(i)
                        }).fail(function() {
                            t.reject()
                        })
                    }
                }),
                t.promise()
            }
        }, {
            key: "disableFetcherController",
            value: function(e) {
                this.disabledFetcherControllers.push(e),
                e.disabled = !0
            }
        }, {
            key: "removeItems",
            value: function(e, t) {
                var n = [];
                return (e || []).forEach(function(e) {
                    t.some(function(t) {
                        return t.id === e.id
                    }) || n.push(e)
                }),
                n
            }
        }, {
            key: "hasNext",
            value: function() {
                var e = !0;
                return this.limit && (e = this.cursor < this.limit),
                e = e && this.fetcherControllers.some(function(e) {
                    return e.fetcher.hasNext() || !(!e.stack || !e.stack.length)
                })
            }
        }, {
            key: "checkUserFetch",
            value: function() {
                var e = this.sourceScheme.some(function(e) {
                    return "tag" === e.type || "location" === e.type
                })
                  , t = this.filters.filtersScheme.some(function(e) {
                    return "user" === e.type
                });
                return e && t || "classic" === this.tuner.get("postTemplate") && -1 !== this.tuner.get("postElements").indexOf("user")
            }
        }, {
            key: "fillItemsUser",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                if (e.length || this.items.length || n.reject(),
                this.fetchUser) {
                    var i = [];
                    e.forEach(function(e) {
                        if (e.user.id)
                            if (e.user.username && (t.users[e.user.id] = e.user),
                            "object" === a(t.users[e.user.id])) {
                                var n = jQuery.Deferred();
                                i.push(n),
                                e.user = t.users[e.user.id],
                                n.resolve()
                            } else {
                                var r = new l.default(t,e.code).fetch().then(function(n) {
                                    t.users[e.user.id] = n[0].user,
                                    e.user = n[0].user
                                });
                                i.push(r)
                            }
                    }),
                    jQuery.when.apply(jQuery, i).then(function() {
                        n.resolve()
                    })
                } else
                    n.resolve();
                return n.promise()
            }
        }, {
            key: "getUserByName",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                return new o.default(this,e).fetch().then(function(e) {
                    if (e && e[0] && e[0].user) {
                        var i = e[0].user;
                        t.users[i.id] = i,
                        n.resolve(i)
                    } else
                        n.reject()
                }, function() {
                    return n.reject()
                }),
                n.promise()
            }
        }, {
            key: "isSandbox",
            value: function() {
                return !this.isAlternativeApi && this.accessToken && !this.source
            }
        }]),
        e
    }();
    t.default = d
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(2))
      , a = function(e) {
        function t(e, n, i) {
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t),
            function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).call(this, e, "/users/" + n + "/media/recent/", i))
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, i.default),
        t
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(32))
      , o = i(n(0))
      , s = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.path = n.replace(/\?.+$/, ""),
            this.tuner = t.tuner,
            this.cache = t.cache,
            this.apiUrl = this.tuner.get("api"),
            this.apiEnc = this.tuner.get("apiEnc"),
            this.accessToken = this.tuner.get("accessToken"),
            !this.accessToken && this.apiUrl || (this.apiUrl = o.default.apiUrl),
            this.isAlternativeApi = this.apiUrl !== o.default.apiUrl,
            this.cacheTime = this.tuner.get("cacheTime")
        }
        return a(e, [{
            key: "send",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred()
                  , i = e
                  , a = {
                    url: e,
                    dataType: "jsonp",
                    type: "get",
                    beforeSend: function(e, n) {
                        if (t.apiEnc) {
                            var i = n.url.replace(t.apiUrl, "");
                            n.url = t.apiUrl + r.default.enc(i, t.p())
                        }
                    }
                }
                  , o = function() {
                    return jQuery.ajax(a).then(function(e) {
                        200 !== e.meta.code ? n.reject(e) : (t.cache.save(i, e),
                        n.resolve(e))
                    }).fail(function(e) {
                        n.reject(e)
                    })
                };
                return this.cache.connect().then(function() {
                    t.cache.getSaved(i, t.cacheTime).then(function(e) {
                        n.resolve(e)
                    }).fail(function() {
                        o()
                    })
                }).fail(function() {
                    o()
                }),
                n.promise()
            }
        }, {
            key: "get",
            value: function(e) {
                var t = void 0;
                return !this.isAlternativeApi && this.accessToken && (e.access_token = this.accessToken),
                this.isAlternativeApi && !this.apiEnc ? (e.path = "/v1" + this.path.replace("/v1", ""),
                t = this.apiUrl + "?" + jQuery.param(e)) : t = (this.apiUrl + this.path + "?" + jQuery.param(e)).replace(/\?$/, ""),
                this.send(t)
            }
        }, {
            key: "p",
            value: function() {
                var e = document.location.hostname.match(/[^\.]+(\.[^.$]+)?$/);
                return e && e.length ? e[0] + "xnKdl21x0" : "xnKdl21x0"
            }
        }]),
        e
    }();
    t.default = s
}
, function(e, t, n) {
    "use strict";
    var i, a, r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    }
    : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    }
    ;
    !function(o, s) {
        "object" == r(t) ? e.exports = s() : (i = s,
        void 0 !== (a = "function" == typeof i ? i.call(t, n, t, e) : i) && (e.exports = a))
    }(0, function() {
        var e = 14
          , t = 8
          , n = !1
          , i = function(e) {
            var t, n, i = [];
            for (16 > e.length && (t = 16 - e.length,
            i = [t, t, t, t, t, t, t, t, t, t, t, t, t, t, t, t]),
            n = 0; e.length > n; n++)
                i[n] = e[n];
            return i
        }
          , a = function(e, t) {
            var n, i, a = "";
            if (t) {
                if ((n = e[15]) > 16)
                    throw "Decryption error: Maybe bad key";
                if (16 === n)
                    return "";
                for (i = 0; 16 - n > i; i++)
                    a += String.fromCharCode(e[i])
            } else
                for (i = 0; 16 > i; i++)
                    a += String.fromCharCode(e[i]);
            return a
        }
          , r = function(e, t) {
            var n, i = [];
            for (t || (e = function(e) {
                try {
                    return unescape(encodeURIComponent(e))
                } catch (e) {
                    throw "Error on UTF-8 encode"
                }
            }(e)),
            n = 0; e.length > n; n++)
                i[n] = e.charCodeAt(n);
            return i
        }
          , o = function(n, i) {
            var a, r = e >= 12 ? 3 : 2, o = [], s = [], l = [], p = [], u = n.concat(i);
            for (l[0] = E(u),
            p = l[0],
            a = 1; r > a; a++)
                l[a] = E(l[a - 1].concat(u)),
                p = p.concat(l[a]);
            return o = p.slice(0, 4 * t),
            s = p.slice(4 * t, 4 * t + 16),
            {
                key: o,
                iv: s
            }
        }
          , s = function(e, t, n) {
            t = g(t);
            var a, r = Math.ceil(e.length / 16), o = [], s = [];
            for (a = 0; r > a; a++)
                o[a] = i(e.slice(16 * a, 16 * a + 16));
            for (0 == e.length % 16 && (o.push([16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16]),
            r++),
            a = 0; o.length > a; a++)
                o[a] = 0 === a ? m(o[a], n) : m(o[a], s[a - 1]),
                s[a] = p(o[a], t);
            return s
        }
          , l = function(e, t, n, i) {
            t = g(t);
            var r, o = e.length / 16, s = [], l = [], p = "";
            for (r = 0; o > r; r++)
                s.push(e.slice(16 * r, 16 * (r + 1)));
            for (r = s.length - 1; r >= 0; r--)
                l[r] = u(s[r], t),
                l[r] = 0 === r ? m(l[r], n) : m(l[r], s[r - 1]);
            for (r = 0; o - 1 > r; r++)
                p += a(l[r]);
            return p += a(l[r], !0),
            i ? p : function(e) {
                try {
                    return decodeURIComponent(escape(e))
                } catch (e) {
                    throw "Bad Key"
                }
            }(p)
        }
          , p = function(t, i) {
            n = !1;
            var a, r = h(t, i, 0);
            for (a = 1; e + 1 > a; a++)
                r = c(r),
                r = d(r),
                e > a && (r = f(r)),
                r = h(r, i, a);
            return r
        }
          , u = function(t, i) {
            n = !0;
            var a, r = h(t, i, e);
            for (a = e - 1; a > -1; a--)
                r = d(r),
                r = c(r),
                r = h(r, i, a),
                a > 0 && (r = f(r));
            return r
        }
          , c = function(e) {
            var t, i = n ? I : k, a = [];
            for (t = 0; 16 > t; t++)
                a[t] = i[e[t]];
            return a
        }
          , d = function(e) {
            var t, i = [], a = n ? [0, 13, 10, 7, 4, 1, 14, 11, 8, 5, 2, 15, 12, 9, 6, 3] : [0, 5, 10, 15, 4, 9, 14, 3, 8, 13, 2, 7, 12, 1, 6, 11];
            for (t = 0; 16 > t; t++)
                i[t] = e[a[t]];
            return i
        }
          , f = function(e) {
            var t, i = [];
            if (n)
                for (t = 0; 4 > t; t++)
                    i[4 * t] = P[e[4 * t]] ^ S[e[1 + 4 * t]] ^ T[e[2 + 4 * t]] ^ M[e[3 + 4 * t]],
                    i[1 + 4 * t] = M[e[4 * t]] ^ P[e[1 + 4 * t]] ^ S[e[2 + 4 * t]] ^ T[e[3 + 4 * t]],
                    i[2 + 4 * t] = T[e[4 * t]] ^ M[e[1 + 4 * t]] ^ P[e[2 + 4 * t]] ^ S[e[3 + 4 * t]],
                    i[3 + 4 * t] = S[e[4 * t]] ^ T[e[1 + 4 * t]] ^ M[e[2 + 4 * t]] ^ P[e[3 + 4 * t]];
            else
                for (t = 0; 4 > t; t++)
                    i[4 * t] = j[e[4 * t]] ^ L[e[1 + 4 * t]] ^ e[2 + 4 * t] ^ e[3 + 4 * t],
                    i[1 + 4 * t] = e[4 * t] ^ j[e[1 + 4 * t]] ^ L[e[2 + 4 * t]] ^ e[3 + 4 * t],
                    i[2 + 4 * t] = e[4 * t] ^ e[1 + 4 * t] ^ j[e[2 + 4 * t]] ^ L[e[3 + 4 * t]],
                    i[3 + 4 * t] = L[e[4 * t]] ^ e[1 + 4 * t] ^ e[2 + 4 * t] ^ j[e[3 + 4 * t]];
            return i
        }
          , h = function(e, t, n) {
            var i, a = [];
            for (i = 0; 16 > i; i++)
                a[i] = e[i] ^ t[n][i];
            return a
        }
          , m = function(e, t) {
            var n, i = [];
            for (n = 0; 16 > n; n++)
                i[n] = e[n] ^ t[n];
            return i
        }
          , g = function(n) {
            var i, a, r, o, s = [], l = [], p = [];
            for (i = 0; t > i; i++)
                a = [n[4 * i], n[4 * i + 1], n[4 * i + 2], n[4 * i + 3]],
                s[i] = a;
            for (i = t; 4 * (e + 1) > i; i++) {
                for (s[i] = [],
                r = 0; 4 > r; r++)
                    l[r] = s[i - 1][r];
                for (0 == i % t ? (l = y(v(l)),
                l[0] ^= C[i / t - 1]) : t > 6 && 4 == i % t && (l = y(l)),
                r = 0; 4 > r; r++)
                    s[i][r] = s[i - t][r] ^ l[r]
            }
            for (i = 0; e + 1 > i; i++)
                for (p[i] = [],
                o = 0; 4 > o; o++)
                    p[i].push(s[4 * i + o][0], s[4 * i + o][1], s[4 * i + o][2], s[4 * i + o][3]);
            return p
        }
          , y = function(e) {
            for (var t = 0; 4 > t; t++)
                e[t] = k[e[t]];
            return e
        }
          , v = function(e) {
            var t, n = e[0];
            for (t = 0; 4 > t; t++)
                e[t] = e[t + 1];
            return e[3] = n,
            e
        }
          , w = function(e, t) {
            var n, i = [];
            for (n = 0; e.length > n; n += t)
                i[n / t] = parseInt(e.substr(n, t), 16);
            return i
        }
          , b = function(e, t) {
            var n, i;
            for (i = 0,
            n = 0; 8 > n; n++)
                i = 1 == (1 & t) ? i ^ e : i,
                e = e > 127 ? 283 ^ e << 1 : e << 1,
                t >>>= 1;
            return i
        }
          , x = function(e) {
            var t, n = [];
            for (t = 0; 256 > t; t++)
                n[t] = b(e, t);
            return n
        }
          , k = w("637c777bf26b6fc53001672bfed7ab76ca82c97dfa5947f0add4a2af9ca472c0b7fd9326363ff7cc34a5e5f171d8311504c723c31896059a071280e2eb27b27509832c1a1b6e5aa0523bd6b329e32f8453d100ed20fcb15b6acbbe394a4c58cfd0efaafb434d338545f9027f503c9fa851a3408f929d38f5bcb6da2110fff3d2cd0c13ec5f974417c4a77e3d645d197360814fdc222a908846eeb814de5e0bdbe0323a0a4906245cc2d3ac629195e479e7c8376d8dd54ea96c56f4ea657aae08ba78252e1ca6b4c6e8dd741f4bbd8b8a703eb5664803f60e613557b986c11d9ee1f8981169d98e949b1e87e9ce5528df8ca1890dbfe6426841992d0fb054bb16", 2)
          , I = function(e) {
            var t, n = [];
            for (t = 0; e.length > t; t++)
                n[e[t]] = t;
            return n
        }(k)
          , C = w("01020408102040801b366cd8ab4d9a2f5ebc63c697356ad4b37dfaefc591", 2)
          , j = x(2)
          , L = x(3)
          , M = x(9)
          , S = x(11)
          , T = x(13)
          , P = x(14)
          , E = function(e) {
            function t(e, t) {
                return e << t | e >>> 32 - t
            }
            function n(e, t) {
                var n, i, a, r, o;
                return a = 2147483648 & e,
                r = 2147483648 & t,
                n = 1073741824 & e,
                i = 1073741824 & t,
                o = (1073741823 & e) + (1073741823 & t),
                n & i ? 2147483648 ^ o ^ a ^ r : n | i ? 1073741824 & o ? 3221225472 ^ o ^ a ^ r : 1073741824 ^ o ^ a ^ r : o ^ a ^ r
            }
            function i(e, i, a, r, o, s, l) {
                return e = n(e, n(n(function(e, t, n) {
                    return e & t | ~e & n
                }(i, a, r), o), l)),
                n(t(e, s), i)
            }
            function a(e, i, a, r, o, s, l) {
                return e = n(e, n(n(function(e, t, n) {
                    return e & n | t & ~n
                }(i, a, r), o), l)),
                n(t(e, s), i)
            }
            function r(e, i, a, r, o, s, l) {
                return e = n(e, n(n(function(e, t, n) {
                    return e ^ t ^ n
                }(i, a, r), o), l)),
                n(t(e, s), i)
            }
            function o(e, i, a, r, o, s, l) {
                return e = n(e, n(n(function(e, t, n) {
                    return t ^ (e | ~n)
                }(i, a, r), o), l)),
                n(t(e, s), i)
            }
            function s(e) {
                var t, n, i = [];
                for (n = 0; 3 >= n; n++)
                    t = 255 & e >>> 8 * n,
                    i = i.concat(t);
                return i
            }
            var l, p, u, c, d, f, h, m, g, y = [], v = w("67452301efcdab8998badcfe10325476d76aa478e8c7b756242070dbc1bdceeef57c0faf4787c62aa8304613fd469501698098d88b44f7afffff5bb1895cd7be6b901122fd987193a679438e49b40821f61e2562c040b340265e5a51e9b6c7aad62f105d02441453d8a1e681e7d3fbc821e1cde6c33707d6f4d50d87455a14eda9e3e905fcefa3f8676f02d98d2a4c8afffa39428771f6816d9d6122fde5380ca4beea444bdecfa9f6bb4b60bebfbc70289b7ec6eaa127fad4ef308504881d05d9d4d039e6db99e51fa27cf8c4ac5665f4292244432aff97ab9423a7fc93a039655b59c38f0ccc92ffeff47d85845dd16fa87e4ffe2ce6e0a30143144e0811a1f7537e82bd3af2352ad7d2bbeb86d391", 8);
            for (y = function(e) {
                for (var t, n = e.length, i = n + 8, a = 16 * ((i - i % 64) / 64 + 1), r = [], o = 0, s = 0; n > s; )
                    t = (s - s % 4) / 4,
                    o = s % 4 * 8,
                    r[t] = r[t] | e[s] << o,
                    s++;
                return t = (s - s % 4) / 4,
                o = s % 4 * 8,
                r[t] = r[t] | 128 << o,
                r[a - 2] = n << 3,
                r[a - 1] = n >>> 29,
                r
            }(e),
            f = v[0],
            h = v[1],
            m = v[2],
            g = v[3],
            l = 0; y.length > l; l += 16)
                p = f,
                u = h,
                c = m,
                d = g,
                f = i(f, h, m, g, y[l + 0], 7, v[4]),
                g = i(g, f, h, m, y[l + 1], 12, v[5]),
                m = i(m, g, f, h, y[l + 2], 17, v[6]),
                h = i(h, m, g, f, y[l + 3], 22, v[7]),
                f = i(f, h, m, g, y[l + 4], 7, v[8]),
                g = i(g, f, h, m, y[l + 5], 12, v[9]),
                m = i(m, g, f, h, y[l + 6], 17, v[10]),
                h = i(h, m, g, f, y[l + 7], 22, v[11]),
                f = i(f, h, m, g, y[l + 8], 7, v[12]),
                g = i(g, f, h, m, y[l + 9], 12, v[13]),
                m = i(m, g, f, h, y[l + 10], 17, v[14]),
                h = i(h, m, g, f, y[l + 11], 22, v[15]),
                f = i(f, h, m, g, y[l + 12], 7, v[16]),
                g = i(g, f, h, m, y[l + 13], 12, v[17]),
                m = i(m, g, f, h, y[l + 14], 17, v[18]),
                h = i(h, m, g, f, y[l + 15], 22, v[19]),
                f = a(f, h, m, g, y[l + 1], 5, v[20]),
                g = a(g, f, h, m, y[l + 6], 9, v[21]),
                m = a(m, g, f, h, y[l + 11], 14, v[22]),
                h = a(h, m, g, f, y[l + 0], 20, v[23]),
                f = a(f, h, m, g, y[l + 5], 5, v[24]),
                g = a(g, f, h, m, y[l + 10], 9, v[25]),
                m = a(m, g, f, h, y[l + 15], 14, v[26]),
                h = a(h, m, g, f, y[l + 4], 20, v[27]),
                f = a(f, h, m, g, y[l + 9], 5, v[28]),
                g = a(g, f, h, m, y[l + 14], 9, v[29]),
                m = a(m, g, f, h, y[l + 3], 14, v[30]),
                h = a(h, m, g, f, y[l + 8], 20, v[31]),
                f = a(f, h, m, g, y[l + 13], 5, v[32]),
                g = a(g, f, h, m, y[l + 2], 9, v[33]),
                m = a(m, g, f, h, y[l + 7], 14, v[34]),
                h = a(h, m, g, f, y[l + 12], 20, v[35]),
                f = r(f, h, m, g, y[l + 5], 4, v[36]),
                g = r(g, f, h, m, y[l + 8], 11, v[37]),
                m = r(m, g, f, h, y[l + 11], 16, v[38]),
                h = r(h, m, g, f, y[l + 14], 23, v[39]),
                f = r(f, h, m, g, y[l + 1], 4, v[40]),
                g = r(g, f, h, m, y[l + 4], 11, v[41]),
                m = r(m, g, f, h, y[l + 7], 16, v[42]),
                h = r(h, m, g, f, y[l + 10], 23, v[43]),
                f = r(f, h, m, g, y[l + 13], 4, v[44]),
                g = r(g, f, h, m, y[l + 0], 11, v[45]),
                m = r(m, g, f, h, y[l + 3], 16, v[46]),
                h = r(h, m, g, f, y[l + 6], 23, v[47]),
                f = r(f, h, m, g, y[l + 9], 4, v[48]),
                g = r(g, f, h, m, y[l + 12], 11, v[49]),
                m = r(m, g, f, h, y[l + 15], 16, v[50]),
                h = r(h, m, g, f, y[l + 2], 23, v[51]),
                f = o(f, h, m, g, y[l + 0], 6, v[52]),
                g = o(g, f, h, m, y[l + 7], 10, v[53]),
                m = o(m, g, f, h, y[l + 14], 15, v[54]),
                h = o(h, m, g, f, y[l + 5], 21, v[55]),
                f = o(f, h, m, g, y[l + 12], 6, v[56]),
                g = o(g, f, h, m, y[l + 3], 10, v[57]),
                m = o(m, g, f, h, y[l + 10], 15, v[58]),
                h = o(h, m, g, f, y[l + 1], 21, v[59]),
                f = o(f, h, m, g, y[l + 8], 6, v[60]),
                g = o(g, f, h, m, y[l + 15], 10, v[61]),
                m = o(m, g, f, h, y[l + 6], 15, v[62]),
                h = o(h, m, g, f, y[l + 13], 21, v[63]),
                f = o(f, h, m, g, y[l + 4], 6, v[64]),
                g = o(g, f, h, m, y[l + 11], 10, v[65]),
                m = o(m, g, f, h, y[l + 2], 15, v[66]),
                h = o(h, m, g, f, y[l + 9], 21, v[67]),
                f = n(f, p),
                h = n(h, u),
                m = n(m, c),
                g = n(g, d);
            return s(f).concat(s(h), s(m), s(g))
        }
          , _ = function() {
            var e = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"
              , t = e.split("");
            return "function" == typeof Array.indexOf && (e = t),
            {
                encode: function(e) {
                    var n, i, a = [], r = "";
                    for (Math.floor(16 * e.length / 3),
                    n = 0; 16 * e.length > n; n++)
                        a.push(e[Math.floor(n / 16)][n % 16]);
                    for (n = 0; a.length > n; n += 3)
                        r += t[a[n] >> 2],
                        r += t[(3 & a[n]) << 4 | a[n + 1] >> 4],
                        r += void 0 !== a[n + 1] ? t[(15 & a[n + 1]) << 2 | a[n + 2] >> 6] : "=",
                        r += void 0 !== a[n + 2] ? t[63 & a[n + 2]] : "=";
                    for (i = r.slice(0, 64) + "\n",
                    n = 1; Math.ceil(r.length / 64) > n; n++)
                        i += r.slice(64 * n, 64 * n + 64) + (Math.ceil(r.length / 64) === n + 1 ? "" : "\n");
                    return i
                },
                decode: function(t) {
                    t = t.replace(/\n/g, "");
                    var n, i = [], a = [], r = [];
                    for (n = 0; t.length > n; n += 4)
                        a[0] = e.indexOf(t.charAt(n)),
                        a[1] = e.indexOf(t.charAt(n + 1)),
                        a[2] = e.indexOf(t.charAt(n + 2)),
                        a[3] = e.indexOf(t.charAt(n + 3)),
                        r[0] = a[0] << 2 | a[1] >> 4,
                        r[1] = (15 & a[1]) << 4 | a[2] >> 2,
                        r[2] = (3 & a[2]) << 6 | a[3],
                        i.push(r[0], r[1], r[2]);
                    return i = i.slice(0, i.length - i.length % 16)
                }
            }
        }();
        return {
            size: function(n) {
                switch (n) {
                case 128:
                    e = 10,
                    t = 4;
                    break;
                case 192:
                    e = 12,
                    t = 6;
                    break;
                case 256:
                    e = 14,
                    t = 8;
                    break;
                default:
                    throw "Invalid Key Size Specified:" + n
                }
            },
            h2a: function(e) {
                var t = [];
                return e.replace(/(..)/g, function(e) {
                    t.push(parseInt(e, 16))
                }),
                t
            },
            expandKey: g,
            encryptBlock: p,
            decryptBlock: u,
            Decrypt: n,
            s2a: r,
            rawEncrypt: s,
            rawDecrypt: l,
            dec: function(e, t, n) {
                var i = _.decode(e)
                  , a = i.slice(8, 16)
                  , s = o(r(t, n), a)
                  , p = s.key
                  , u = s.iv;
                return i = i.slice(16, i.length),
                e = l(i, p, u, n)
            },
            openSSLKey: o,
            a2h: function(e) {
                var t, n = "";
                for (t = 0; e.length > t; t++)
                    n += (16 > e[t] ? "0" : "") + e[t].toString(16);
                return n
            },
            enc: function(e, t, n) {
                var i, a = function(e) {
                    var t, n = [];
                    for (t = 0; e > t; t++)
                        n = n.concat(Math.floor(256 * Math.random()));
                    return n
                }(8), l = o(r(t, n), a), p = l.key, u = l.iv, c = [[83, 97, 108, 116, 101, 100, 95, 95].concat(a)];
                return e = r(e, n),
                i = s(e, p, u),
                i = c.concat(i),
                _.encode(i)
            },
            Hash: {
                MD5: E
            },
            Base64: _
        }
    })
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(2))
      , a = function(e) {
        function t(e, n, i) {
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t),
            function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).call(this, e, "/locations/" + n + "/media/recent/", i))
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, i.default),
        t
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(2))
      , a = function(e) {
        function t(e, n, i) {
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t),
            function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).call(this, e, "/tags/" + n + "/media/recent/", i, "max_tag_id"))
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, i.default),
        t
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(1))
      , o = i(n(6))
      , s = i(n(43))
      , l = i(n(0))
      , p = i(n(44))
      , u = i(n(45))
      , c = i(n(46))
      , d = i(n(47))
      , f = i(n(48))
      , h = i(n(49))
      , m = i(n(50))
      , g = i(n(51))
      , y = i(n(52))
      , v = i(n(53))
      , w = i(n(54))
      , b = ["user", "date", "instagramLink", "likesCount", "commentsCount", "share", "text"]
      , x = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.posts = n,
            this.tuner = n.tuner,
            this.popup = n.widget.layout.popup,
            this.lang = n.lang,
            this.$window = n.$window;
            var i = {
                tile: p.default,
                classic: u.default
            };
            this.templateName = this.tuner.get("postTemplate"),
            this.template = i[this.templateName],
            this.elements = this.tuner.get("postElements"),
            this.popover = new o.default(l.default.alias),
            this.data = this.formatData(t),
            this.data = jQuery.extend({}, this.data, {
                displaying: this.getDisplaying()
            }),
            this.init()
        }
        return a(e, [{
            key: "init",
            value: function() {
                this.$element = this.createElement(),
                this.$image = this.$element.find("." + l.default.alias + "-posts-item-image"),
                this.$link = this.$element.find("." + l.default.alias + "-posts-item-link"),
                this.$text = this.$element.find("." + l.default.alias + "-posts-item-text"),
                this.$share = this.$element.find("." + l.default.alias + "-posts-item-share"),
                this.$redLikeContainer = this.$element.find("." + l.default.alias + "-posts-item-red-like-container"),
                this.$element.data(this),
                this.formatText(),
                this.initPopover(),
                this.watch()
            }
        }, {
            key: "createElement",
            value: function() {
                return this.parts = {},
                this.data.parts = this.parts,
                this.parts.user = {
                    image: (0,
                    c.default)(this.data),
                    name: (0,
                    d.default)(this.data)
                },
                this.parts.date = (0,
                f.default)(this.data),
                this.parts.instagramLink = (0,
                h.default)(this.data),
                this.parts.image = (0,
                m.default)(this.data),
                this.parts.likesCount = (0,
                g.default)(this.data),
                this.parts.commentsCount = (0,
                y.default)(this.data),
                this.parts.share = (0,
                v.default)(this.data),
                this.parts.text = (0,
                w.default)(this.data),
                jQuery(this.template(this.data))
            }
        }, {
            key: "formatText",
            value: function() {
                var e = this;
                this.data.text = r.default.text.removeLineBreaks(this.data.text) || "",
                this.$overlay = this.$element.find("." + l.default.alias + "-posts-item-overlay"),
                this.$content = this.$element.find("." + l.default.alias + "-posts-item-content"),
                this.$text.html(this.data.text),
                this.$textClone = this.$text.clone(),
                this.$textClone.addClass(l.default.alias + "-posts-item-text-clone").appendTo(this.$content),
                setTimeout(function() {
                    e.fitText(e.$overlay, e.$text)
                })
            }
        }, {
            key: "formatData",
            value: function(e) {
                var t = {};
                return t.id = e.id,
                t.code = e.code,
                t.text = e.caption && e.caption.text ? e.caption.text : "",
                e.images && (t.images = [],
                ["thumbnail", "low_resolution", "standard_resolution", "__original"].forEach(function(n) {
                    e.images[n] && t.images.push(e.images[n])
                }),
                e.currentImage = null),
                t.likesCount = e.likes.count,
                t.likesCountFormatted = r.default.numbers.formatNumber(e.likes.count, !0),
                t.commentsCount = e.comments.count,
                t.commentsCountFormatted = r.default.numbers.formatNumber(e.comments.count, !0),
                t.showAllComments = t.commentsCount > 10,
                t.createdTime = s.default.formatDate(e.created_time, this.lang),
                t.link = e.link,
                t.type = e.type,
                t.location = e.location,
                t.callToAction = e.callToAction || null,
                e.user && (t.user = {},
                e.user.username && (t.user.username = e.user.username,
                t.followButtonLink = (r.default.others.isMobileDevice() ? "instagram://user?username=" : "https://www.instagram.com/") + e.user.username),
                e.user.profile_picture && (t.user.profilePicture = e.user.profile_picture),
                e.user.full_name && (t.user.fullName = e.user.full_name)),
                t.labels = {
                    share: this.lang.get("Share"),
                    viewOnInstagram: this.lang.get("View on Instagram")
                },
                t
            }
        }, {
            key: "fitImage",
            value: function() {
                var e = this
                  , t = this.$element.outerWidth()
                  , n = null;
                this.data.images.forEach(function(i, a) {
                    !n && (Math.min(i.width, i.height) > t || a === e.data.images.length - 1) && (n = i)
                }),
                !this.data.currentImage || this.data.currentImage.url !== n.url && n.width > this.data.currentImage.width ? (this.data.currentImage = n,
                this.$element.removeClass(l.default.alias + "-posts-item-loaded"),
                this.$image.attr("src", this.data.currentImage.url).one("load", function() {
                    e.$element.addClass(l.default.alias + "-posts-item-loaded")
                }),
                this.data.currentImage.height / this.data.currentImage.width > 1 ? this.$element.addClass(l.default.alias + "-posts-item-image-portrait") : this.$element.addClass(l.default.alias + "-posts-item-image-landscape")) : this.$element.addClass(l.default.alias + "-posts-item-loaded")
            }
        }, {
            key: "fitText",
            value: function() {
                if (this.$text && this.$textClone) {
                    var e = Math.floor(this.$text.outerHeight())
                      , t = Math.floor(this.$textClone.outerHeight());
                    this.$text.parent().toggleClass(l.default.alias + "-posts-item-content-cropped", e < t)
                }
            }
        }, {
            key: "fit",
            value: function() {
                this.fitImage(),
                this.fitText()
            }
        }, {
            key: "getDisplaying",
            value: function() {
                var e = {};
                return this.elements = this.elements.filter(function(e) {
                    return !!~b.indexOf(e)
                }),
                this.elements.forEach(function(t) {
                    e[t] = !0
                }),
                e.user = e.user && this.data.user && this.data.user.username,
                e.date = e.date && this.data.createdTime,
                e.header = e.user || e.instagramLink,
                e.likesCount = e.likesCount && this.data.likesCount,
                e.commentsCount = e.commentsCount && this.data.commentsCount,
                e.counters = e.likesCount || e.commentsCount,
                e.meta = e.counters || e.share,
                e.overlay = e.counters || e.text,
                e
            }
        }, {
            key: "initPopover",
            value: function() {
                var e = this;
                this.popoverShareItems = [{
                    title: this.lang.get("Share on Facebook"),
                    icon: "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjIsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNNS43LDEzVjguMWgzLjZWNi4yYzAtMy4zLDIuNS02LjIsNS41LTYuMmgzLjl2NC45aC0zLjljLTAuNCwwLTAuOSwwLjUtMC45LDEuM3YxLjloNC45VjEzaC00Ljl2MTENCgkJSDkuM1YxM0g1Ljd6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==",
                    handler: function() {
                        window.open("https://www.facebook.com/sharer/sharer.php?u=" + e.data.link, "facebook", "width=600px,height=600px,menubar=no,toolbar=no,resizable=yes,scrollbars=yes")
                    }
                }, {
                    title: this.lang.get("Share on Twitter"),
                    icon: "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjIsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxwYXRoIGlkPSJ0d2l0dGVyLTQtaWNvbl8xXyIgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0yMS41LDcuMWMwLjMsNi45LTQuOSwxNC42LTE0LDE0LjZjLTIuOCwwLTUuNC0wLjgtNy41LTIuMg0KCQljMi42LDAuMyw1LjItMC40LDcuMy0yYy0yLjIsMC00LTEuNS00LjYtMy40YzAuOCwwLjEsMS41LDAuMSwyLjItMC4xYy0yLjQtMC41LTQtMi42LTMuOS00LjljMC43LDAuNCwxLjQsMC42LDIuMiwwLjYNCgkJQzEsOC4yLDAuNCw1LjMsMS43LDMuMWMyLjQsMyw2LjEsNC45LDEwLjEsNS4xYy0wLjctMy4xLDEuNi02LDQuOC02YzEuNCwwLDIuNywwLjYsMy42LDEuNmMxLjEtMC4yLDIuMi0wLjYsMy4xLTEuMg0KCQljLTAuNCwxLjEtMS4xLDIuMS0yLjIsMi43YzEtMC4xLDEuOS0wLjQsMi44LTAuOEMyMy4zLDUuNSwyMi41LDYuNCwyMS41LDcuMXoiLz4NCjwvZz4NCjwvc3ZnPg0K",
                    handler: function() {
                        window.open("https://twitter.com/home?status=" + e.data.link, "facebook", "width=600px,height=600px,menubar=no,toolbar=no,resizable=yes,scrollbars=yes")
                    }
                }, {
                    title: this.lang.get("Share on Google+"),
                    icon: "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjIsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNNy42LDEwLjl2Mi42SDEyYy0wLjIsMS4xLTEuMywzLjItNC4zLDMuMmMtMi42LDAtNC43LTIuMS00LjctNC43UzUsNy4zLDcuNiw3LjMNCgkJYzEuNSwwLDIuNSwwLjYsMywxLjJsMi4xLTJjLTEuMy0xLjItMy4xLTItNS4xLTJDMy40LDQuNSwwLDcuOSwwLDEyczMuNCw3LjUsNy42LDcuNWM0LjQsMCw3LjMtMyw3LjMtNy4zYzAtMC41LTAuMS0wLjktMC4xLTEuMg0KCQlMNy42LDEwLjlMNy42LDEwLjl6Ii8+DQoJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0yMS44LDEwLjlWOC44aC0yLjJ2Mi4xaC0yLjJ2Mi4xaDIuMnYyLjFoMi4ydi0yLjFIMjRjMCwwLDAtMi4xLDAtMi4xSDIxLjh6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==",
                    handler: function() {
                        window.open("https://plus.google.com/share?url=" + e.data.link, "facebook", "width=600px,height=600px,menubar=no,toolbar=no,resizable=yes,scrollbars=yes")
                    }
                }]
            }
        }, {
            key: "watch",
            value: function() {
                var e = this
                  , t = void 0;
                this.$window.on("resize", function() {
                    clearTimeout(t),
                    t = setTimeout(function() {
                        e.fit()
                    }, 100)
                }),
                this.$share.on("click touchend", function(t) {
                    e.popover.open(e.popoverShareItems, e.$share),
                    t.stopPropagation()
                });
                var n = this.tuner.get("imageClickAction");
                this.$link.on("click", function(t) {
                    switch (n) {
                    case "instagram":
                        return !0;
                    case "none":
                        return !1;
                    case "popup":
                        t.preventDefault(),
                        e.popup.open(e.data.id)
                    }
                }).toggleClass(l.default.alias + "-posts-item-link-disabled", "none" === n)
            }
        }]),
        e
    }();
    t.default = x
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e)
        }
        return i(e, [{
            key: "getDateParts",
            value: function(e) {
                return e instanceof Date || (e = new Date(Date.parse(e))),
                {
                    year: e.getFullYear(),
                    month: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][e.getMonth()],
                    day: e.getDate(),
                    hours: e.getHours(),
                    minutes: ("0" + e.getMinutes()).slice(-2)
                }
            }
        }, {
            key: "formatDate",
            value: function(e) {
                var t = new Date(e)
                  , n = new Date
                  , i = Math.round(n.getTime() / 1e3)
                  , a = Math.abs(i - Math.round(t.getTime() / 1e3))
                  , r = this.getDateParts(t);
                return r.hours = r.hours % 12,
                r.hours = r.hours ? r.hours : 12,
                r.ampm = r.hours >= 12 ? "pm" : "am",
                r.year < n.getFullYear() ? r.month + " " + r.day + ", " + r.year : a >= 86400 ? r.month + " " + r.day + " at " + r.hours + ":" + r.minutes + r.ampm : a >= 3600 ? Math.round(a / 3600) + " hrs ago" : a >= 60 ? Math.round(a / 60) + " mins ago" : Math.round(a) + " secs ago"
            }
        }, {
            key: "formatInstagramDate",
            value: function(e, t) {
                var n = {};
                t && t.labels && (n = t.labels);
                var i = new Date(1e3 * e)
                  , a = new Date
                  , r = Math.round(a.getTime() / 1e3)
                  , o = Math.abs(r - Math.round(i.getTime() / 1e3))
                  , s = this.getDateParts(i);
                return s.year < a.getFullYear() ? s.month + " " + s.day + ", " + s.year : o >= 604800 ? s.month + " " + s.day : o >= 86400 ? this.getInstagramLangLabel(n["days ago"], "days ago", Math.floor(o / 86400)) : o >= 3600 ? this.getInstagramLangLabel(n["hours ago"], "hours ago", Math.floor(o / 3600)) : o >= 60 ? this.getInstagramLangLabel(n["minutes ago"], "minutes ago", Math.floor(o / 60)) : this.getInstagramLangLabel(n["seconds ago"], "seconds ago", Math.floor(o))
            }
        }, {
            key: "getInstagramLangLabel",
            value: function(e, t, n) {
                return "function" == typeof e ? e("%1 " + t, n) : n + " " + (e || t)
            }
        }, {
            key: "castDate",
            value: function(e) {
                var t = e;
                if (isNaN(t) || (t = new Date(t)),
                !(t instanceof Date)) {
                    var n = (t || "").split(/[^0-9]/);
                    t = new Date(n[0],n[1] - 1,n[2],n[3],n[4],n[5])
                }
                return t
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e)
        }
        return i(e, [{
            key: "formatInstagramAnchors",
            value: function(e) {
                return e = e.replace(/(https?|ftp):\/\/[^\s\t<]+/g, function(e) {
                    return '<a href="' + e + '" target="_blank" rel="nofollow">' + e + "</a>"
                }),
                e = e.replace(/(#)([^\s<#]+)/g, function(e, t, n) {
                    return '<a href="https://www.instagram.com/explore/tags/' + n + '/" target="_blank" rel="nofollow">' + e + "</a>"
                }),
                e = e.replace(/(@)([^\s<@]+)/g, function(e, t, n) {
                    return '<a href="https://www.instagram.com/' + n + '/" target="_blank" rel="nofollow">' + e + "</a>"
                })
            }
        }, {
            key: "formatAnchors",
            value: function(e) {
                return e = e.replace(/(https?|ftp):\/\/[^\s\t<]+/g, function(e) {
                    return '<a href="' + e + '" target="_blank" rel="nofollow">' + e + "</a>"
                }),
                e = e.replace(/(#)([^\s#]+)/g, function(e, t, n) {
                    return '<a href="https://www.facebook.com/hashtag/' + n + '" target="_blank" rel="nofollow">' + e + "</a>"
                })
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e)
        }
        return i(e, [{
            key: "formatNumber",
            value: function(e, t) {
                var n = null
                  , i = "";
                return "number" == typeof e && (t ? (e >= 1e9 ? (n = e / 1e9,
                i = "B") : e >= 1e6 ? (n = e / 1e6,
                i = "M") : e >= 1e3 ? (n = e / 1e3,
                i = "K") : n = e,
                n = Math.round(10 * n) / 10) : n = e,
                n = n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + i)
            }
        }, {
            key: "formatDuration",
            value: function(e) {
                var t = Math.ceil(e)
                  , n = Math.floor(t / 3600) % 24
                  , i = Math.floor(t / 60) % 60
                  , a = t % 60
                  , r = n ? n + ":" : "";
                return r += i < 10 && n ? "0" + i : i,
                r += ":" + (a < 10 ? "0" + a : a)
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e)
        }
        return i(e, [{
            key: "nl2br",
            value: function(e) {
                return e.replace(/\n/g, "<br>")
            }
        }, {
            key: "removeLineBreaks",
            value: function(e) {
                return e.replace(/\n/g, " ")
            }
        }, {
            key: "cutSpaces",
            value: function(e) {
                return e.replace(/[\⠀]{2,}/, "")
            }
        }, {
            key: "textHighlight",
            value: function(e, t, n) {
                var i = e;
                return t.forEach(function(e) {
                    i = i.replace(e, '<span class="' + n.alias + '-selected-text">' + e + "</span>")
                }),
                i
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e)
        }
        return i(e, [{
            key: "isMobileDevice",
            value: function() {
                return /android|webos|iphone|ipad|ipod|blackberry|windows\sphone/i.test(navigator.userAgent)
            }
        }]),
        e
    }();
    t.default = a
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eui-popover eui-popover-left">\n    <div class="eui-popover-content">\n        <div class="eui-popover-content-inner"></div>\n    </div>\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        var t = '<div class="eui-popover-content-item">\n    ';
        return e.icon && (t += '\n    <div class="eui-popover-content-item-icon">\n        <img src="' + e.icon + '">\n    </div>\n    '),
        t += '\n\n    <div class="eui-popover-content-item-title">\n        ' + e.title + "\n    </div>\n</div>"
    }
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(1))
      , r = new (function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e)
        }
        return i(e, [{
            key: "formatDate",
            value: function(e, t) {
                if ("en" === t.lang)
                    return a.default.dates.formatInstagramDate(e);
                var n = 1e3 * e
                  , i = new Date(n)
                  , r = new Date
                  , o = Math.round(r.getTime() / 1e3)
                  , s = Math.abs(o - Math.round(i.getTime() / 1e3))
                  , l = a.default.dates.getDateParts(i);
                if (s >= 604800) {
                    var p = {
                        month: "long",
                        day: "numeric"
                    };
                    return l.year < r.getFullYear() && (p.year = "numeric"),
                    a.default.dates.castDate(n).toLocaleDateString(t.lang, p)
                }
                return a.default.dates.formatInstagramDate(e, {
                    labels: {
                        "days ago": function(e, n) {
                            return t.get(e, n)
                        },
                        "hours ago": function(e, n) {
                            return t.get(e, n)
                        },
                        "minutes ago": function(e, n) {
                            return t.get(e, n)
                        },
                        "seconds ago": function(e, n) {
                            return t.get(e, n)
                        }
                    }
                })
            }
        }]),
        e
    }());
    t.default = r
}
, function(e, t) {
    e.exports = function(e) {
        var t = '<div class="eapps-instagram-feed-posts-item-template-tile eapps-instagram-feed-posts-item eapps-instagram-feed-posts-item-type-' + e.type + '">\n    <a class="eapps-instagram-feed-posts-item-link" href="' + e.link + '" target="_blank" rel="nofollow">\n        <div class="eapps-instagram-feed-posts-item-media">\n            ' + e.parts.image + "\n        </div>\n\n        ";
        return e.displaying.overlay && (t += '\n            <div class="eapps-instagram-feed-posts-item-overlay">\n                <div class="eapps-instagram-feed-posts-item-content">\n                    ',
        e.displaying.counters && (t += '\n                        <div class="eapps-instagram-feed-posts-item-counters">\n                            ',
        e.displaying.likesCount && (t += "\n                                " + e.parts.likesCount + "\n                            "),
        t += "\n\n                            ",
        e.displaying.commentsCount && (t += "\n                                " + e.parts.commentsCount + "\n                            "),
        t += "\n                        </div>\n                    "),
        t += "\n\n                    ",
        e.displaying.text && (t += "\n                        " + e.parts.text + "\n                    "),
        t += "\n                </div>\n            </div>\n        "),
        t += "\n\n        ",
        e.displaying.likesCount && (t += '\n            <div class="eapps-instagram-feed-posts-item-red-like-container"></div>\n        '),
        t += "\n    </a>\n</div>"
    }
}
, function(e, t) {
    e.exports = function(e) {
        var t = '<div class="eapps-instagram-feed-posts-item-template-classic eapps-instagram-feed-posts-item eapps-instagram-feed-posts-item-type-' + e.type + '">\n    ';
        return e.displaying.header && (t += '\n        <div class="eapps-instagram-feed-posts-item-header">\n            ',
        e.displaying.user && (t += '\n                <div class="eapps-instagram-feed-posts-item-user">\n                    ' + e.parts.user.image + '\n\n                    <div class="eapps-instagram-feed-posts-item-user-name-wrapper">\n                        ' + e.parts.user.name + "\n\n                        ",
        e.displaying.date && (t += "\n                            " + e.parts.date + "\n                        "),
        t += "\n                    </div>\n                </div>\n            "),
        t += "\n\n            ",
        e.displaying.instagramLink && (t += "\n                " + e.parts.instagramLink + "\n            "),
        t += "\n        </div>\n    "),
        t += '\n\n    <div class="eapps-instagram-feed-posts-item-media">\n        <a class="eapps-instagram-feed-posts-item-link" href="' + e.link + '" target="_blank" rel="nofollow">\n            ' + e.parts.image + "\n        </a>\n    </div>\n\n    ",
        e.displaying.meta && (t += '\n        <div class="eapps-instagram-feed-posts-item-meta">\n            ',
        e.displaying.counters && (t += '\n                <div class="eapps-instagram-feed-posts-item-counters">\n                    ',
        e.displaying.likesCount && (t += '\n                        <a class="eapps-instagram-feed-posts-item-link" href="' + e.link + '" target="_blank" rel="nofollow">\n                            ' + e.parts.likesCount + '\n\n                            <div class="eapps-instagram-feed-posts-item-red-like-container"></div>\n                        </a>\n                    '),
        t += "\n\n                    ",
        e.displaying.commentsCount && (t += '\n                        <a class="eapps-instagram-feed-posts-item-link" href="' + e.link + '" target="_blank" rel="nofollow">\n                            ' + e.parts.commentsCount + "\n                        </a>\n                    "),
        t += "\n                </div>\n            "),
        t += "\n\n            ",
        e.displaying.share && (t += "\n                " + e.parts.share + "\n            "),
        t += "\n        </div>\n    "),
        t += "\n\n    ",
        e.displaying.text && (t += '\n        <div class="eapps-instagram-feed-posts-item-content">\n            <a class="eapps-instagram-feed-posts-item-link" href="' + e.link + '" target="_blank" rel="nofollow">\n                ' + e.parts.text + "\n            </a>\n        </div>\n    "),
        t += "\n</div>"
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-user-image-wrapper">\n    <a href="' + e.followButtonLink + '" title="' + e.user.username + '" target="_blank" rel="nofollow">\n        <img class="eapps-instagram-feed-posts-item-user-image" src="' + e.user.profilePicture + '" alt="' + e.user.username + '">\n    </a>\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-user-name">\n    <a href="' + e.followButtonLink + '" title="' + e.user.username + '" target="_blank" rel="nofollow">' + e.user.username + "</a>\n</div>"
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-date">' + e.createdTime + "</div>"
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-instagram-link">\n    <a href="' + e.link + '" target="_blank" rel="nofolow" title="' + e.labels.viewOnInstagram + '">\n        <svg class="eapps-instagram-feed-item-source-link-icon" viewBox="0 0 24 24" width="24" height="24">\n            <path d="M17.1,1H6.9C3.7,1,1,3.7,1,6.9v10.1C1,20.3,3.7,23,6.9,23h10.1c3.3,0,5.9-2.7,5.9-5.9V6.9C23,3.7,20.3,1,17.1,1z\n            M21.5,17.1c0,2.4-2,4.4-4.4,4.4H6.9c-2.4,0-4.4-2-4.4-4.4V6.9c0-2.4,2-4.4,4.4-4.4h10.3c2.4,0,4.4,2,4.4,4.4V17.1z"></path>\n            <path d="M16.9,11.2c-0.2-1.1-0.6-2-1.4-2.8c-0.8-0.8-1.7-1.2-2.8-1.4c-0.5-0.1-1-0.1-1.4,0C10,7.3,8.9,8,8.1,9S7,11.4,7.2,12.7\n            C7.4,14,8,15.1,9.1,15.9c0.9,0.6,1.9,1,2.9,1c0.2,0,0.5,0,0.7-0.1c1.3-0.2,2.5-0.9,3.2-1.9C16.8,13.8,17.1,12.5,16.9,11.2z\n             M12.6,15.4c-0.9,0.1-1.8-0.1-2.6-0.6c-0.7-0.6-1.2-1.4-1.4-2.3c-0.1-0.9,0.1-1.8,0.6-2.6c0.6-0.7,1.4-1.2,2.3-1.4\n            c0.2,0,0.3,0,0.5,0s0.3,0,0.5,0c1.5,0.2,2.7,1.4,2.9,2.9C15.8,13.3,14.5,15.1,12.6,15.4z"></path>\n            <path d="M18.4,5.6c-0.2-0.2-0.4-0.3-0.6-0.3s-0.5,0.1-0.6,0.3c-0.2,0.2-0.3,0.4-0.3,0.6s0.1,0.5,0.3,0.6c0.2,0.2,0.4,0.3,0.6,0.3\n            s0.5-0.1,0.6-0.3c0.2-0.2,0.3-0.4,0.3-0.6C18.7,5.9,18.6,5.7,18.4,5.6z"></path>\n        </svg>\n    </a>\n</div>\n'
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-image-wrapper">\n    <img class="eapps-instagram-feed-posts-item-image" src="">\n\n    <span class="eapps-instagram-feed-posts-item-image-icon-video eapps-instagram-feed-posts-item-image-icon">\n        <svg viewBox="0 0 24 24">\n            <path d="M23.467,5.762c-0.118-0.045-0.232-0.068-0.342-0.068c-0.246,0-0.451,0.087-0.615,0.26l-3.76,3.217v5.766l3.76,3.578c0.164,0.173,0.369,0.26,0.615,0.26c0.109,0,0.223-0.023,0.342-0.068C23.822,18.552,24,18.284,24,17.901V6.57C24,6.186,23.822,5.917,23.467,5.762z"></path>\n            <path d="M16.33,4.412c-0.77-0.769-1.696-1.154-2.78-1.154H3.934c-1.084,0-2.01,0.385-2.78,1.154C0.385,5.182,0,6.108,0,7.192v9.616c0,1.084,0.385,2.01,1.154,2.78c0.77,0.77,1.696,1.154,2.78,1.154h9.616c1.084,0,2.01-0.385,2.78-1.154c0.77-0.77,1.154-1.696,1.154-2.78v-3.076v-3.478V7.192C17.484,6.108,17.099,5.182,16.33,4.412z M8.742,17.229c-2.888,0-5.229-2.341-5.229-5.229c0-2.888,2.341-5.229,5.229-5.229S13.971,9.112,13.971,12C13.971,14.888,11.63,17.229,8.742,17.229z"></path>\n            <circle cx="8.742" cy="12" r="3.5"></circle>\n        </svg>\n    </span>\n\n    <span class="eapps-instagram-feed-posts-item-image-icon-carousel eapps-instagram-feed-posts-item-image-icon">\n        <svg viewBox="0 0 45.964 45.964">\n            <path d="M32.399,40.565H11.113v1.297c0,2.24,1.838,4.051,4.076,4.051h26.733c2.239,0,4.042-1.811,4.042-4.051V15.13c0-2.237-1.803-4.068-4.042-4.068h-1.415v21.395C40.507,36.904,36.845,40.566,32.399,40.565z"></path>\n            <path d="M0,4.102l0,28.355c0,2.241,1.814,4.067,4.051,4.067h28.365c2.237,0,4.066-1.826,4.066-4.067l0-28.356c0-2.238-1.828-4.051-4.066-4.051H4.051C1.814,0.05,0,1.862,0,4.102z"></path>\n        </svg>\n    </span>\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-likes-count">\n    <svg class="eapps-instagram-feed-posts-item-likes-count-icon" viewBox="0 0 24 24" width="24" height="24">\n        <path d="M17.7,1.5c-2,0-3.3,0.5-4.9,2.1c0,0-0.4,0.4-0.7,0.7c-0.3-0.3-0.7-0.7-0.7-0.7c-1.6-1.6-3-2.1-5-2.1C2.6,1.5,0,4.6,0,8.3\n        c0,4.2,3.4,7.1,8.6,11.5c0.9,0.8,1.9,1.6,2.9,2.5c0.1,0.1,0.3,0.2,0.5,0.2s0.3-0.1,0.5-0.2c1.1-1,2.1-1.8,3.1-2.7\n        c4.8-4.1,8.5-7.1,8.5-11.4C24,4.6,21.4,1.5,17.7,1.5z M14.6,18.6c-0.8,0.7-1.7,1.5-2.6,2.3c-0.9-0.7-1.7-1.4-2.5-2.1\n        c-5-4.2-8.1-6.9-8.1-10.5c0-3.1,2.1-5.5,4.9-5.5c1.5,0,2.6,0.3,3.8,1.5c1,1,1.2,1.2,1.2,1.2C11.6,5.9,11.7,6,12,6.1\n        c0.3,0,0.5-0.2,0.7-0.4c0,0,0.2-0.2,1.2-1.3c1.3-1.3,2.1-1.5,3.8-1.5c2.8,0,4.9,2.4,4.9,5.5C22.6,11.9,19.4,14.6,14.6,18.6z"></path>\n    </svg>\n\n    <div class="eapps-instagram-feed-posts-item-likes-count-label">' + e.likesCountFormatted + "</div>\n</div>"
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<span class="eapps-instagram-feed-posts-item-comments-count">\n    <svg class="eapps-instagram-feed-posts-item-comments-count-icon" viewBox="0 0 24 24" width="24" height="24">\n        <path d="M1,11.9C1,17.9,5.8,23,12,23c1.9,0,3.7-1,5.3-1.8l5,1.3l0,0c0.1,0,0.1,0,0.2,0c0.4,0,0.6-0.3,0.6-0.6c0-0.1,0-0.1,0-0.2\n        l-1.3-4.9c0.9-1.6,1.4-2.9,1.4-4.8C23,5.8,18,1,12,1C5.9,1,1,5.9,1,11.9z M2.4,11.9c0-5.2,4.3-9.5,9.5-9.5c5.3,0,9.6,4.2,9.6,9.5\n        c0,1.7-0.5,3-1.3,4.4l0,0c-0.1,0.1-0.1,0.2-0.1,0.3c0,0.1,0,0.1,0,0.1l0,0l1.1,4.1l-4.1-1.1l0,0c-0.1,0-0.1,0-0.2,0\n        c-0.1,0-0.2,0-0.3,0.1l0,0c-1.4,0.8-3.1,1.8-4.8,1.8C6.7,21.6,2.4,17.2,2.4,11.9z"></path>\n    </svg>\n\n    <span class="eapps-instagram-feed-posts-item-comments-count-label">' + e.commentsCountFormatted + "</span>\n</span>"
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<span class="eapps-instagram-feed-posts-item-share">\n    <svg class="eapps-instagram-feed-posts-item-share-icon" viewBox="0 0 24 24" width="24" height="24">\n        <path d="M22.8,10.5l-9.8-7.9c-0.2-0.2-0.5-0.2-0.7-0.1c-0.2,0.1-0.4,0.4-0.4,0.6v3.7C6.5,7,4.5,8.9,2.6,12.4C1,15.4,1,18.9,1,21.3\n        c0,0.2,0,0.4,0,0.5c0,0.3,0.2,0.6,0.5,0.7c0.1,0,0.1,0,0.2,0c0.2,0,0.5-0.1,0.6-0.3c3.7-6.5,5.5-6.8,9.5-6.8V19\n        c0,0.3,0.2,0.5,0.4,0.6s0.5,0.1,0.7-0.1l9.8-8c0.2-0.1,0.2-0.3,0.2-0.5S22.9,10.7,22.8,10.5z M13.2,17.6v-2.9\n        c0-0.2-0.1-0.4-0.2-0.5c-0.1-0.1-0.3-0.2-0.5-0.2c-2.7,0-3.8,0-5.9,0.9c-1.8,0.8-2.8,2.3-4.2,4.5c0.1-2,0.3-4.4,1.4-6.4\n        c1.7-3.2,3.5-4.8,8.7-4.8c0.4,0,0.7-0.3,0.7-0.7V4.6l8.1,6.5L13.2,17.6z"></path>\n    </svg>\n\n    <span class="eapps-instagram-feed-posts-item-share-label">' + e.labels.share + "</span>\n</span>\n\n"
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-text"></div>'
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(56))
      , o = i(n(0))
      , s = i(n(62))
      , l = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.opener = t,
            this.tuner = t.tuner,
            this.lang = t.lang,
            this.showing = !1,
            this.$element = null,
            this.$wrapper = null,
            this.$inner = null,
            this.$close = null,
            this.loadItemsFunc = n.loadItemsFunc || t.addItems,
            this.id = n.id || null,
            this.deepLinking = n.deepLinking || !1,
            this.forwardItemsCount = 2,
            this.localStorageSupport = !!window.localStorage,
            this.bodyOverflowState = jQuery("body").css("overflow"),
            this.htmlOverflowState = jQuery("html").css("overflow"),
            this.init()
        }
        return a(e, [{
            key: "init",
            value: function() {
                this.items = [],
                this.defaultVideoMute = !0,
                this.$element = this.createPopupElement(),
                this.$wrapper = this.$element.find("." + o.default.alias + "-popup-wrapper"),
                this.$inner = this.$element.find("." + o.default.alias + "-popup-inner"),
                this.$close = this.$element.find("." + o.default.alias + "-popup-close"),
                this.$scrollIndicator = this.$element.find("." + o.default.alias + "-popup-scroll-indicator"),
                this.$element.appendTo(document.body),
                this.watch()
            }
        }, {
            key: "createPopupElement",
            value: function() {
                return jQuery((0,
                s.default)({
                    id: o.default.alias + "-popup-" + this.id,
                    mobilePanelTitle: this.tuner.get("widgetTitle")
                }))
            }
        }, {
            key: "open",
            value: function(e) {
                var t = this;
                if (this.showing)
                    return !1;
                this.showing = !0,
                this.currentItem = null,
                this.currentItemIndex = null,
                this.opener.widget.layout && this.opener.widget.layout.slider && this.opener.widget.layout.slider.autoplay && this.opener.widget.layout.slider.disableAutoplay(),
                this.renderItems();
                var n = 0;
                this.items.forEach(function(t, i) {
                    t.data.id === e && (n = i)
                }),
                setTimeout(function() {
                    t.moveToItem(n),
                    t.setCurrentItem(),
                    t.checkScrollIndicator(),
                    jQuery("html, body").css("overflow", "hidden"),
                    t.$element.addClass(o.default.alias + "-popup-visible")
                })
            }
        }, {
            key: "renderItems",
            value: function() {
                var e = this;
                this.opener.items.forEach(function(t, n) {
                    e.hasItem(n) || e.addItem(t.data)
                })
            }
        }, {
            key: "clearItems",
            value: function() {
                this.$inner.empty(),
                this.items = [],
                this.currentItem = null,
                this.currentItemIndex = null
            }
        }, {
            key: "hasItem",
            value: function(e) {
                return !!this.getItem(e)
            }
        }, {
            key: "getItem",
            value: function(e) {
                return this.items[e] || null
            }
        }, {
            key: "addItem",
            value: function(e) {
                var t = new r.default(this,e);
                return this.items.push(t),
                t.$element.appendTo(this.$inner)
            }
        }, {
            key: "calculateCurrentItemIndex",
            value: function() {
                var e = window.innerHeight
                  , t = null;
                return this.items.forEach(function(n, i) {
                    var a = n.$element.get(0).getBoundingClientRect();
                    a.top < e && (e = Math.abs(a.top),
                    t = i)
                }),
                t
            }
        }, {
            key: "setCurrentItem",
            value: function() {
                var e = this
                  , t = this.calculateCurrentItemIndex();
                this.currentItemIndex && this.currentItemIndex === t || (this.currentItem && this.currentItem.media && this.currentItem.media.isVideo && !this.currentItem.media.video.paused && this.currentItem.media.pause(),
                this.currentItemIndex = t,
                this.currentItem = this.items[t],
                this.currentItem.init().then(function() {
                    e.currentItem.media && e.currentItem.media.isVideo && e.currentItem.media.play()
                }),
                this.deepLinking && (window.location.hash = "#!is" + this.id + "/$" + this.currentItem.data.code),
                this.initForwardItems(),
                this.currentItem.onInitCallback())
            }
        }, {
            key: "loadItems",
            value: function() {
                var e = this
                  , t = jQuery.Deferred();
                return this.loadItemsFunc().then(function(n) {
                    e.renderItems(),
                    t.resolve(n)
                }),
                t.promise()
            }
        }, {
            key: "initForwardItems",
            value: function() {
                this.getForwardItems().then(function(e) {
                    setTimeout(function() {
                        (e || []).forEach(function(e) {
                            e.init()
                        })
                    })
                })
            }
        }, {
            key: "getForwardItems",
            value: function(e) {
                var t = this;
                e = e || jQuery.Deferred();
                var n = this.items.slice(this.currentItemIndex + 1, this.currentItemIndex + 1 + this.forwardItemsCount);
                return n.length < this.forwardItemsCount ? this.loadItems().then(function(i) {
                    i && i.length ? t.getForwardItems(e) : e.resolve(n)
                }) : e.resolve(n),
                e.promise()
            }
        }, {
            key: "moveToItem",
            value: function(e, t) {
                t = t || 0;
                var n = this.items[e];
                this.$wrapper.animate({
                    scrollTop: n.$element.position().top
                }, t)
            }
        }, {
            key: "close",
            value: function() {
                this.currentItem.media && this.currentItem.media.isVideo && !this.currentItem.media.video.paused && this.currentItem.media.pause(),
                jQuery("html").css("overflow", this.htmlOverflowState),
                jQuery("body").css("overflow", this.bodyOverflowState),
                this.$element.removeClass(o.default.alias + "-popup-visible"),
                this.showing = !1,
                this.deepLinking && (window.location.hash = "!"),
                this.opener.widget.layout.slider && (this.opener.widget.layout.slider.updateSlides(),
                this.opener.widget.layout.slider.checkLoopNeed(),
                this.opener.widget.layout.slider.updateArrows(),
                this.opener.widget.layout.slider.autoplay && this.opener.widget.layout.slider.enableAutoplay())
            }
        }, {
            key: "isShowing",
            value: function() {
                return this.showing
            }
        }, {
            key: "checkScrollIndicator",
            value: function() {
                var e = this;
                this.localStorageSupport && (this.scrollIndicatorAlreadyShown = window.localStorage.getItem(o.default.alias + "-popup-scroll-indicator-already-shown")),
                this.scrollIndicatorAlreadyShown || (this.$scrollIndicator.addClass(o.default.alias + "-popup-scroll-indicator-visible"),
                setTimeout(function() {
                    e.$scrollIndicator.removeClass(o.default.alias + "-popup-scroll-indicator-visible"),
                    e.localStorageSupport && window.localStorage.setItem(o.default.alias + "-popup-scroll-indicator-already-shown", !0)
                }, 3800))
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                this.$wrapper.on("click touchend", function(t) {
                    jQuery(t.target).closest("." + o.default.alias + "-popup-inner").length || (t.preventDefault(),
                    e.close())
                }),
                this.$close.on("click touchend", function(t) {
                    t.preventDefault(),
                    e.close()
                });
                var t = null;
                this.$wrapper.on("scroll touchmove", function() {
                    t && clearTimeout(t),
                    t = setTimeout(function() {
                        e.setCurrentItem()
                    }, 50)
                })
            }
        }]),
        e
    }();
    t.default = l
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(5))
      , o = i(n(6))
      , s = i(n(1))
      , l = i(n(7))
      , p = i(n(8))
      , u = i(n(59))
      , c = i(n(0))
      , d = i(n(61))
      , f = ["user", "date", "location", "followButton", "instagramLink", "likesCount", "share", "text", "comments"]
      , h = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.popup = t,
            this.tuner = t.tuner,
            this.lang = t.lang,
            this.elements = this.tuner.get("popupElements"),
            this.data = n,
            this.data = jQuery.extend({}, n, {
                displaying: this.getDisplaying()
            }),
            this.data.currentImage = null,
            this.tuner.get("accessToken") || (this.fetcher = new r.default(t.opener,this.data.code)),
            this.popover = new o.default(c.default.alias),
            this.data.labels = {
                viewOnInstagram: this.lang.get("View on Instagram"),
                follow: this.lang.get("Follow"),
                share: this.lang.get("Share")
            },
            this.loaded = !1,
            this.callToActionHighlighted = !1,
            this.$element = this.createElement(),
            this.$media = this.$element.find("." + c.default.alias + "-popup-item-media"),
            this.$cta = this.$element.find("." + c.default.alias + "-popup-item-cta"),
            this.$share = this.$element.find("." + c.default.alias + "-popup-item-share")
        }
        return a(e, [{
            key: "init",
            value: function() {
                var e = this
                  , t = jQuery.Deferred();
                if (this.loaded)
                    t.resolve();
                else {
                    if (this.initDef)
                        return this.initDef;
                    this.initDef = t;
                    var n = function() {
                        e.data.displaying = e.getDisplaying(),
                        e.data.text = s.default.links.formatInstagramAnchors(e.data.text),
                        e.initMedia().then(function() {
                            e.$element.addClass(c.default.alias + "-popup-item-loaded"),
                            e.loaded = !0,
                            e.initDef.resolve()
                        });
                        var t = e.createElement();
                        ["header", "likes", "text", "comments"].forEach(function(n) {
                            var i = t.find("." + c.default.alias + "-popup-item-" + n);
                            e.$element.find("." + c.default.alias + "-popup-item-" + n).replaceWith(i)
                        }),
                        e.initPopover(),
                        e.watch()
                    };
                    this.fetcher ? this.fetchData().then(n) : n()
                }
                return t.promise()
            }
        }, {
            key: "showCTA",
            value: function() {
                var e = this;
                !this.callToActionHighlighted && this.data.callToAction && (this.callToActionHighlighted = !0,
                setTimeout(function() {
                    e.$cta.addClass(c.default.alias + "-popup-item-cta-highlighted")
                }, 3e3))
            }
        }, {
            key: "onInitCallback",
            value: function() {
                this.showCTA()
            }
        }, {
            key: "initMedia",
            value: function() {
                var e = new {
                    image: l.default,
                    video: p.default,
                    carousel: u.default
                }[this.data.type](this,this.data);
                return e.$element.appendTo(this.$media),
                "carousel" === this.data.type ? this.mediaCarousel = e : this.media = e,
                e.init()
            }
        }, {
            key: "fetchData",
            value: function() {
                var e = this;
                return this.fetcher.fetch().then(function(t) {
                    t && t.length && (e.data = jQuery.extend(!0, {}, e.data, e.formatData(t[0])),
                    e.data.likes.length && (e.data.labels.likedBy = e.lang.get("Liked by %1 and %2 others", '<strong><a href="https://www.instagram.com/' + e.data.likes[0].username + '" target="_blank" rel="nofollow">' + e.data.likes[0].username + "</a></strong>", e.data.likesCountFormatted)),
                    e.data.labels.viewAllComments = e.lang.get("View all %1 comments", e.data.commentsCountFormatted))
                })
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                d.default)(this.data))
            }
        }, {
            key: "formatData",
            value: function(e) {
                var t = {};
                return t.location = e.location,
                t.likes = e.likes.data,
                t.text = e.caption && e.caption.text ? e.caption.text : "",
                t.comments = (e.comments.data || []).slice(0, 5),
                t.comments.forEach(function(e) {
                    e.text = s.default.links.formatInstagramAnchors(e.text)
                }),
                e.user && (t.user = {},
                e.user.username && (t.user.username = e.user.username,
                t.followButtonLink = (s.default.others.isMobileDevice() ? "instagram://user?username=" : "https://www.instagram.com/") + e.user.username),
                e.user.profile_picture && (t.user.profilePicture = e.user.profile_picture),
                e.user.full_name && (t.user.fullName = e.user.full_name)),
                e.video_url && (t.videoUrl = e.video_url),
                e.carousel && (t.carousel = [],
                (e.carousel || []).forEach(function(e) {
                    var n = {
                        type: e.is_video ? "video" : "image",
                        images: []
                    };
                    e.is_video ? n.videoUrl = e.video_url : (e.display_resources || []).forEach(function(e) {
                        n.images.push({
                            url: e.src,
                            width: e.config_width,
                            height: e.config_height
                        })
                    }),
                    t.carousel.push(n)
                })),
                t
            }
        }, {
            key: "getDisplaying",
            value: function() {
                var e = {};
                return this.elements = this.elements.filter(function(e) {
                    return !!~f.indexOf(e)
                }),
                this.elements.forEach(function(t) {
                    e[t] = !0
                }),
                e.likesCount = e.likesCount && this.data.likes,
                e.meta = e.likesCount || e.share,
                e.text = e.text && this.data.text,
                e.comments = e.comments && this.data.comments,
                e.content = e.meta || e.text || e.comments || e.date,
                e
            }
        }, {
            key: "initPopover",
            value: function() {
                var e = this;
                this.popoverShareItems = [{
                    title: this.lang.get("Share on Facebook"),
                    icon: "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjIsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNNS43LDEzVjguMWgzLjZWNi4yYzAtMy4zLDIuNS02LjIsNS41LTYuMmgzLjl2NC45aC0zLjljLTAuNCwwLTAuOSwwLjUtMC45LDEuM3YxLjloNC45VjEzaC00Ljl2MTENCgkJSDkuM1YxM0g1Ljd6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==",
                    handler: function() {
                        window.open("https://www.facebook.com/sharer/sharer.php?u=" + e.data.link, "facebook", "width=600px,height=600px,menubar=no,toolbar=no,resizable=yes,scrollbars=yes")
                    }
                }, {
                    title: this.lang.get("Share on Twitter"),
                    icon: "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjIsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxwYXRoIGlkPSJ0d2l0dGVyLTQtaWNvbl8xXyIgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0yMS41LDcuMWMwLjMsNi45LTQuOSwxNC42LTE0LDE0LjZjLTIuOCwwLTUuNC0wLjgtNy41LTIuMg0KCQljMi42LDAuMyw1LjItMC40LDcuMy0yYy0yLjIsMC00LTEuNS00LjYtMy40YzAuOCwwLjEsMS41LDAuMSwyLjItMC4xYy0yLjQtMC41LTQtMi42LTMuOS00LjljMC43LDAuNCwxLjQsMC42LDIuMiwwLjYNCgkJQzEsOC4yLDAuNCw1LjMsMS43LDMuMWMyLjQsMyw2LjEsNC45LDEwLjEsNS4xYy0wLjctMy4xLDEuNi02LDQuOC02YzEuNCwwLDIuNywwLjYsMy42LDEuNmMxLjEtMC4yLDIuMi0wLjYsMy4xLTEuMg0KCQljLTAuNCwxLjEtMS4xLDIuMS0yLjIsMi43YzEtMC4xLDEuOS0wLjQsMi44LTAuOEMyMy4zLDUuNSwyMi41LDYuNCwyMS41LDcuMXoiLz4NCjwvZz4NCjwvc3ZnPg0K",
                    handler: function() {
                        window.open("https://twitter.com/home?status=" + e.data.link, "facebook", "width=600px,height=600px,menubar=no,toolbar=no,resizable=yes,scrollbars=yes")
                    }
                }, {
                    title: this.lang.get("Share on Google+"),
                    icon: "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMS4wLjIsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjQgMjQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNNy42LDEwLjl2Mi42SDEyYy0wLjIsMS4xLTEuMywzLjItNC4zLDMuMmMtMi42LDAtNC43LTIuMS00LjctNC43UzUsNy4zLDcuNiw3LjMNCgkJYzEuNSwwLDIuNSwwLjYsMywxLjJsMi4xLTJjLTEuMy0xLjItMy4xLTItNS4xLTJDMy40LDQuNSwwLDcuOSwwLDEyczMuNCw3LjUsNy42LDcuNWM0LjQsMCw3LjMtMyw3LjMtNy4zYzAtMC41LTAuMS0wLjktMC4xLTEuMg0KCQlMNy42LDEwLjlMNy42LDEwLjl6Ii8+DQoJPHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0yMS44LDEwLjlWOC44aC0yLjJ2Mi4xaC0yLjJ2Mi4xaDIuMnYyLjFoMi4ydi0yLjFIMjRjMCwwLDAtMi4xLDAtMi4xSDIxLjh6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==",
                    handler: function() {
                        window.open("https://plus.google.com/share?url=" + e.data.link, "facebook", "width=600px,height=600px,menubar=no,toolbar=no,resizable=yes,scrollbars=yes")
                    }
                }]
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                this.$element.on("click touchend", "." + c.default.alias + "-popup-item-share", function(t) {
                    e.popover.open(e.popoverShareItems, e.$share),
                    t.stopPropagation()
                })
            }
        }]),
        e
    }();
    t.default = h
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-popup-item-media-image">\n    <img src="">\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-popup-item-media-video">\n    <video src="" preload="false" loop webkit-playsinline></video>\n\n    <div class="eapps-instagram-feed-popup-item-media-video-sound">\n        <svg class="eapps-instagram-feed-popup-item-media-video-sound-off" viewBox="0 0 612 612">\n            <path d="M306,0h-38.3L133.9,133.9H38.3C17.1,133.9,0,151,0,172.1v267.8c0,21.1,17.1,38.3,38.3,38.3h95.6L267.8,612H306\n            c21.1,0,38.3-17.1,38.3-38.3V38.3C344.3,17.1,327.1,0,306,0z M286.9,550L157.6,420.8H57.4V191.3h100.3L286.9,62V550z"></path>\n            <path d="M422.2,396.7L422.2,396.7c-11.3-11.3-11.3-29.7,0-41l140.4-140.4c11.3-11.3,29.7-11.3,41,0l0,0c11.3,11.3,11.3,29.7,0,41\n            L463.2,396.7C451.9,408,433.4,408,422.2,396.7z"></path>\n            <path d="M603.5,396.7L603.5,396.7c-11.3,11.3-29.7,11.3-41,0L422.2,256.3c-11.3-11.3-11.3-29.7,0-41l0,0c11.3-11.3,29.7-11.3,41,0\n            l140.4,140.4C614.8,367,614.8,385.4,603.5,396.7z"></path>\n        </svg>\n\n        <svg class="eapps-instagram-feed-popup-item-media-video-sound-on" viewBox="0 0 612 612">\n            <path d="M306,0h-38.25L133.875,133.875H38.25c-21.114,0-38.25,17.136-38.25,38.25v267.75c0,21.114,17.136,38.25,38.25,38.25\n            h95.625L267.75,612H306c21.114,0,38.25-17.136,38.25-38.25V38.25C344.25,17.136,327.133,0,306,0z M286.875,549.997\n            L157.647,420.75H57.375v-229.5h100.272L286.875,62.003V549.997z M539.669,130.873c-5.622-5.642-12.985-8.473-20.368-8.473\n            c-7.306,0-14.63,2.792-20.234,8.338c-11.226,11.169-11.303,29.338-0.134,40.583C534.85,207.449,554.625,255.28,554.625,306\n            s-19.775,98.551-55.673,134.679c-11.169,11.245-11.093,29.414,0.134,40.583c5.604,5.546,12.929,8.338,20.234,8.338\n            c7.382,0,14.745-2.83,20.349-8.472C586.296,434.156,612,371.962,612,306S586.296,177.843,539.669,130.873z M449.228,203.375\n            c-5.375-4.494-11.915-6.713-18.418-6.713c-8.204,0-16.333,3.5-21.993,10.251c-10.175,12.163-8.588,30.236,3.557,40.411\n            c17.48,14.649,27.502,36.031,27.502,58.675c0,22.625-10.021,44.025-27.502,58.656c-12.145,10.175-13.731,28.267-3.557,40.411\n            c5.66,6.771,13.808,10.251,21.993,10.251c6.503,0,13.043-2.199,18.418-6.713C479.751,383.055,497.25,345.646,497.25,306\n            C497.25,266.354,479.751,228.945,449.228,203.375z"></path>\n        </svg>\n    </div>\n</div>'
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(7))
      , o = i(n(8))
      , s = i(n(0))
      , l = i(n(60))
      , p = function() {
        function e(t, n) {
            var i = this;
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.popupItem = t,
            this.isCarousel = !0,
            this.data = n,
            this.items = [],
            this.currentItemIndex = 0,
            this.$element = this.createElement(),
            this.$wrapper = this.$element.find("." + s.default.alias + "-popup-item-media-carousel-wrapper"),
            this.$inner = this.$element.find("." + s.default.alias + "-popup-item-media-carousel-inner"),
            this.$pagination = this.$element.find("." + s.default.alias + "-popup-item-media-carousel-pagination"),
            this.$arrowPrev = this.$element.find("." + s.default.alias + "-popup-item-media-carousel-arrow-prev"),
            this.$arrowNext = this.$element.find("." + s.default.alias + "-popup-item-media-carousel-arrow-next"),
            n.carousel && n.carousel.length && n.carousel.forEach(function(e) {
                var t = new {
                    image: r.default,
                    video: o.default
                }[e.type](i.popupItem,e)
                  , n = jQuery("<div></div>").addClass(s.default.alias + "-popup-item-media-carousel-item").appendTo(i.$inner);
                t.$element.appendTo(n),
                jQuery("<div></div>").addClass(s.default.alias + "-popup-item-media-carousel-pagination-item").appendTo(i.$pagination),
                i.items.push(t)
            }),
            setTimeout(function() {
                i.fitItems()
            }),
            this.moveTo(0),
            this.watch()
        }
        return a(e, [{
            key: "init",
            value: function() {
                var e = jQuery.Deferred();
                return this.items.length || e.reject(),
                this.items[0].init().then(function() {
                    e.resolve()
                }),
                this.items.forEach(function(e, t) {
                    t > 0 && e.init()
                }),
                e.promise()
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                l.default)(this.data))
            }
        }, {
            key: "moveToPrev",
            value: function() {
                this.hasPrev() && this.moveTo(this.currentItemIndex - 1)
            }
        }, {
            key: "moveToNext",
            value: function() {
                this.hasNext() && this.moveTo(this.currentItemIndex + 1)
            }
        }, {
            key: "moveTo",
            value: function(e) {
                var t = this;
                if (this.currentItemIndex = e,
                !this.items[e])
                    return !1;
                var n = this.items[e];
                n !== this.currentItem && (this.currentItem && this.currentItem.isVideo && this.currentItem.pause(),
                this.currentItem = n,
                this.popupItem.media = this.currentItem,
                this.translateTo(e),
                this.currentItem.init().then(function() {
                    t.currentItem.isVideo && t.currentItem.play()
                }),
                this.fitArrows(),
                this.fitPagination())
            }
        }, {
            key: "translateTo",
            value: function(e) {
                var t = e * this.$element.width();
                this.$wrapper.css("transition-duration", "400ms"),
                this.$wrapper.css("transform", "translate3d(-" + t + "px, 0, 0)")
            }
        }, {
            key: "hasPrev",
            value: function() {
                return this.currentItemIndex > 0
            }
        }, {
            key: "hasNext",
            value: function() {
                return this.currentItemIndex < this.items.length - 1
            }
        }, {
            key: "fitArrows",
            value: function() {
                this.$arrowPrev.toggleClass(s.default.alias + "-popup-item-media-carousel-arrow-disabled", !this.hasPrev()),
                this.$arrowNext.toggleClass(s.default.alias + "-popup-item-media-carousel-arrow-disabled", !this.hasNext())
            }
        }, {
            key: "fitPagination",
            value: function() {
                this.$pagination.find("." + s.default.alias + "-popup-item-media-carousel-pagination-item").removeClass(s.default.alias + "-popup-item-media-carousel-pagination-item-active").eq(this.currentItemIndex).addClass(s.default.alias + "-popup-item-media-carousel-pagination-item-active")
            }
        }, {
            key: "fitItems",
            value: function() {
                var e = this.$wrapper.width();
                this.items.forEach(function(t) {
                    t.$element.css("width", e + "px")
                })
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                this.$arrowPrev.on("click touchend", function() {
                    e.moveToPrev()
                }),
                this.$arrowNext.on("click touchend", function() {
                    e.moveToNext()
                }),
                this.$pagination.on("click touchend", "." + s.default.alias + "-popup-item-media-carousel-pagination-item", function(t) {
                    e.moveTo(jQuery(t.target).index())
                })
            }
        }]),
        e
    }();
    t.default = p
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-popup-item-media-carousel">\n    <div class="eapps-instagram-feed-popup-item-media-carousel-wrapper">\n        <div class="eapps-instagram-feed-popup-item-media-carousel-inner"></div>\n    </div>\n\n    <div class="eapps-instagram-feed-popup-item-media-carousel-pagination"></div>\n\n    <div class="eapps-instagram-feed-popup-item-media-carousel-arrow-prev eapps-instagram-feed-popup-item-media-carousel-arrow"></div>\n    <div class="eapps-instagram-feed-popup-item-media-carousel-arrow-next eapps-instagram-feed-popup-item-media-carousel-arrow"></div>\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        var t = '<div class="eapps-instagram-feed-popup-item">\n    <div class="eapps-instagram-feed-popup-item-inner">\n        ';
        return (e.displaying.user || e.displaying.followButton || e.displaying.instagramLink) && (t += '\n            <div class="eapps-instagram-feed-popup-item-header">\n                ',
        e.user && e.displaying.user && (t += '\n                    <div class="eapps-instagram-feed-posts-item-user">\n                        ',
        e.user.profilePicture && (t += '\n                            <div class="eapps-instagram-feed-posts-item-user-image-wrapper">\n                                <a href="' + e.followButtonLink + '" title="' + e.user.username + '" target="_blank" rel="nofollow">\n                                    <img class="eapps-instagram-feed-posts-item-user-image" src="' + e.user.profilePicture + '" alt="' + e.user.username + '">\n                                </a>\n                            </div>\n                        '),
        t += '\n\n                        <div class="eapps-instagram-feed-posts-item-user-name-wrapper">\n                            ',
        e.user.username && (t += '\n                                <div class="eapps-instagram-feed-posts-item-user-name">\n                                    <a href="' + e.followButtonLink + '" title="' + e.user.username + '" target="_blank" rel="nofollow">\n                                        ' + e.user.username + "\n                                    </a>\n                                </div>\n                            "),
        t += "\n\n                            ",
        e.location && (t += '\n                                <div class="eapps-instagram-feed-posts-item-user-location">\n                                    <a href="https://www.instagram.com/explore/locations/' + e.location.id + '" title="' + e.location.name + '" target="_blank" rel="nofollow">\n                                        ' + e.location.name + "\n                                    </a>\n                                </div>\n                            "),
        t += "\n                        </div>\n                    </div>\n                "),
        t += "\n\n                ",
        (e.displaying.followButton || e.displaying.instagramLink) && (t += '\n                    <div class="eapps-instagram-feed-posts-item-user-actions">\n                        ',
        e.displaying.followButton && (t += '\n                            <div class="eapps-instagram-feed-posts-item-user-follow-link">\n                                <a href="' + e.followButtonLink + '" target="_blank" rel="nofollow">' + e.labels.follow + "</a>\n                            </div>\n                        "),
        t += "\n\n                        ",
        e.displaying.instagramLink && (t += '\n                            <div class="eapps-instagram-feed-posts-item-instagram-link">\n                                <a href="' + e.link + '" target="_blank" rel="nofolow" title="' + e.labels.viewOnInstagram + '">\n                                    <svg viewBox="0 0 24 24" width="24" height="24">\n                                        <path d="M17.1,1H6.9C3.7,1,1,3.7,1,6.9v10.1C1,20.3,3.7,23,6.9,23h10.1c3.3,0,5.9-2.7,5.9-5.9V6.9C23,3.7,20.3,1,17.1,1z\n                                                M21.5,17.1c0,2.4-2,4.4-4.4,4.4H6.9c-2.4,0-4.4-2-4.4-4.4V6.9c0-2.4,2-4.4,4.4-4.4h10.3c2.4,0,4.4,2,4.4,4.4V17.1z"></path>\n                                        <path d="M16.9,11.2c-0.2-1.1-0.6-2-1.4-2.8c-0.8-0.8-1.7-1.2-2.8-1.4c-0.5-0.1-1-0.1-1.4,0C10,7.3,8.9,8,8.1,9S7,11.4,7.2,12.7\n                                                C7.4,14,8,15.1,9.1,15.9c0.9,0.6,1.9,1,2.9,1c0.2,0,0.5,0,0.7-0.1c1.3-0.2,2.5-0.9,3.2-1.9C16.8,13.8,17.1,12.5,16.9,11.2z\n                                                 M12.6,15.4c-0.9,0.1-1.8-0.1-2.6-0.6c-0.7-0.6-1.2-1.4-1.4-2.3c-0.1-0.9,0.1-1.8,0.6-2.6c0.6-0.7,1.4-1.2,2.3-1.4\n                                                c0.2,0,0.3,0,0.5,0s0.3,0,0.5,0c1.5,0.2,2.7,1.4,2.9,2.9C15.8,13.3,14.5,15.1,12.6,15.4z"></path>\n                                        <path d="M18.4,5.6c-0.2-0.2-0.4-0.3-0.6-0.3s-0.5,0.1-0.6,0.3c-0.2,0.2-0.3,0.4-0.3,0.6s0.1,0.5,0.3,0.6c0.2,0.2,0.4,0.3,0.6,0.3\n                                                s0.5-0.1,0.6-0.3c0.2-0.2,0.3-0.4,0.3-0.6C18.7,5.9,18.6,5.7,18.4,5.6z"></path>\n                                    </svg>\n                                </a>\n                            </div>\n                        '),
        t += "\n                    </div>\n                "),
        t += "\n            </div>\n        "),
        t += '\n\n        <div class="eapps-instagram-feed-popup-item-media"></div>\n\n        ',
        e.callToAction && (t += '\n            <div class="eapps-instagram-feed-popup-item-cta">\n                <a href="' + e.callToAction.buttonLink + '" class="eapps-instagram-feed-popup-item-cta-button" target="_blank" rel="nofollow">\n                    <span class="eapps-instagram-feed-popup-item-cta-button-label">' + e.callToAction.buttonLabel + '</span>\n\n                    <svg class="eapps-instagram-feed-popup-item-cta-button-icon" viewBox="0 0 6 10">\n                        <path d="M5.71,4.286L1.727,0.302c-0.39-0.392-1.023-0.392-1.414,0c-0.39,0.39-0.39,1.023,0,1.414L3.59,4.992\n                        L0.289,8.284c-0.39,0.39-0.39,1.025,0,1.415c0.39,0.39,1.023,0.39,1.414,0l4.008-4C6.101,5.309,6.101,4.675,5.71,4.286z"></path>\n                    </svg>\n                </a>\n            </div>\n        '),
        t += "\n\n        ",
        e.displaying.content && (t += '\n            <div class="eapps-instagram-feed-popup-item-content">\n                ',
        e.displaying.meta && (t += '\n                    <div class="eapps-instagram-feed-popup-item-meta">\n                        <div class="eapps-instagram-feed-popup-item-likes">\n                            ',
        e.displaying.likesCount && (t += "\n                                " + e.labels.likedBy + "\n                            "),
        t += "\n                        </div>\n\n                        ",
        e.displaying.share && (t += '\n                            <div class="eapps-instagram-feed-popup-item-share">\n                                <svg class="eapps-instagram-feed-popup-item-share-icon" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" width="16" height="16">\n                                    <g><path d="M15.7,6.8l-7-5.6C8.6,1.1,8.4,1.1,8.3,1.1C8.1,1.2,8,1.4,8,1.6v2.9c-3.8,0.1-5.5,1.5-6.9,4C0,10.5,0,13,0,14.7\n                                    c0,0.1,0,0.3,0,0.4c0,0.2,0.1,0.8,0.7,0.8s0.7-0.3,0.8-0.5c2.6-4.6,3.6-4.8,6.5-4.8v2.8c0,0.2,0.1,0.4,0.3,0.4\n                                    c0.1,0.1,0.4,0.1,0.5-0.1l7-5.7C16.1,7.6,16.1,7.2,15.7,6.8z M9.2,11.6V9.8c0-0.1-0.1-0.3-0.1-0.4C9,9.4,8.8,9.3,8.7,9.3\n                                    c-1.9,0-3,0-4.4,0.6c-1.3,0.6-2,1.6-3,3.2c0.1-1.4,0.2-2.6,1-4C3.5,6.8,5,5.7,8.7,5.7c0.3,0,0.5-0.2,0.5-0.5V3.1l5.2,4.4L9.2,11.6z\n                                    "/></g>\n                                </svg>\n\n                                <span class="eapps-instagram-feed-popup-item-share-label">' + e.labels.share + "</span>\n                            </div>\n                        "),
        t += "\n                    </div>\n                "),
        t += "\n\n                ",
        e.displaying.text && (t += '\n                    <div class="eapps-instagram-feed-popup-item-text">\n                        ',
        e.user.username && (t += '\n                            <a href="' + e.link + '" title="' + e.user.username + '" class="eapps-instagram-feed-popup-item-text-author" target="_blank" rel="nofollow">\n                                ' + e.user.username + "\n                            </a>\n                        "),
        t += "\n\n                        " + e.text + "\n                    </div>\n                "),
        t += '\n                <div class="eapps-instagram-feed-popup-item-comments">\n                    ',
        e.displaying.comments && (t += "\n                        ",
        e.comments.forEach(function(e) {
            t += '\n                            <div class="eapps-instagram-feed-popup-item-comments-item">\n                                <span class="eapps-instagram-feed-popup-item-comments-item-author">\n                                    <a href="https://www.instagram.com/' + e.from.username + '" target="_blank" rel="nofollow">\n                                        ' + e.from.username + '\n                                    </a>\n                                </span>\n\n                                <span class="eapps-instagram-feed-popup-item-comments-item-text">\n                                    ' + e.text + "\n                                </span>\n                            </div>\n                        "
        }),
        t += "\n\n                        ",
        e.showAllComments && (t += '\n                            <div class="eapps-instagram-feed-popup-item-comments-more">\n                                <a href="' + e.link + '" target="_blank" rel="nofollow">\n                                    ' + e.labels.viewAllComments + "\n                                </a>\n                            </div>\n                        "),
        t += "\n                    "),
        t += "\n                </div>\n\n                ",
        e.displaying.date && (t += '\n                    <div class="eapps-instagram-feed-popup-item-date">' + e.createdTime + "</div>\n                "),
        t += "\n            </div>\n        "),
        t += '\n    </div>\n\n    <div class="eapps-instagram-feed-loader"></div>\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-popup eapps-instagram-feed eapps-widget" id="' + e.id + '">\n    <div class="eapps-instagram-feed-popup-mobile-panel">\n        <div class="eapps-instagram-feed-popup-mobile-panel-title">' + e.mobilePanelTitle + '</div>\n    </div>\n\n    <div class="eapps-instagram-feed-popup-wrapper">\n        <div class="eapps-instagram-feed-popup-inner"></div>\n    </div>\n\n    <div class="eapps-instagram-feed-popup-close"></div>\n\n    <span class="eapps-instagram-feed-popup-scroll-indicator">\n        <span class="eapps-instagram-feed-popup-scroll-indicator-mouse">\n            <span class="eapps-instagram-feed-popup-scroll-indicator-mouse-wheel"></span>\n        </span>\n    </span>\n</div>'
    }
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(64))
      , r = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.items = t || [],
            this.layout = n,
            this.tuner = n.tuner,
            this.$window = n.$window,
            this.$element = this.createElement()
        }
        return i(e, [{
            key: "init",
            value: function() {
                var e = this;
                this.items.forEach(function(t) {
                    t.$element.appendTo(e.$element),
                    t.fit(),
                    setTimeout(function() {
                        return t.fit()
                    })
                })
            }
        }, {
            key: "createElement",
            value: function() {
                return jQuery((0,
                a.default)())
            }
        }, {
            key: "clear",
            value: function() {
                this.items.forEach(function(e) {
                    e.$element.detach()
                })
            }
        }]),
        e
    }();
    t.default = r
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-view"></div>'
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(0))
      , o = i(n(66))
      , s = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.layout = t,
            this.lang = t.lang,
            this.$window = t.$window,
            this.$layout = t.$element,
            this.alreadyShown = !1,
            this.data = {
                labels: {
                    redLike: this.lang.get("Liked a while ago")
                }
            },
            this.localStorageSupport = !!window.localStorage,
            this.$element = this.createElement(),
            this.watch()
        }
        return a(e, [{
            key: "createElement",
            value: function() {
                return jQuery((0,
                o.default)(this.data))
            }
        }, {
            key: "checkInViewport",
            value: function(e) {
                var t = e.offset().top
                  , n = t + e.outerHeight()
                  , i = this.$window.scrollTop()
                  , a = i + this.$window.height();
                return n > i && t < a
            }
        }, {
            key: "checkRun",
            value: function() {
                !this.alreadyShown && this.checkInViewport(this.$layout) && this.run()
            }
        }, {
            key: "run",
            value: function() {
                var e = this
                  , t = this.generateTimeout(3e3, 5e3)
                  , n = !0
                  , i = (new Date).getTime();
                if (this.localStorageSupport && (this.prevShowTime = parseInt(window.localStorage.getItem(r.default.alias + "-red-like-time"), 10),
                this.prevShowTime && (n = i - this.prevShowTime > 36e5)),
                !n)
                    return !1;
                this.localStorageSupport && window.localStorage.setItem(r.default.alias + "-red-like-time", i),
                this.alreadyShown = !0,
                setTimeout(function() {
                    var t = e.findCurrentView();
                    if (t) {
                        var n = null;
                        t.items.forEach(function(e) {
                            !n && e.data.likesCount && (n = e)
                        }),
                        n && e.show(n)
                    }
                }, t)
            }
        }, {
            key: "show",
            value: function(e) {
                var t = this;
                this.$element.appendTo(e.$redLikeContainer),
                setTimeout(function() {
                    t.$element.addClass(r.default.alias + "-posts-item-red-like-visible")
                }, 50),
                setTimeout(function() {
                    t.hide(),
                    setTimeout(function() {
                        e.$redLikeContainer.empty()
                    }, 300)
                }, 5e3)
            }
        }, {
            key: "hide",
            value: function() {
                this.$element.removeClass(r.default.alias + "-posts-item-red-like-visible")
            }
        }, {
            key: "findCurrentView",
            value: function() {
                var e = this
                  , t = null;
                return this.layout.views.forEach(function(n) {
                    e.checkInViewport(n.$element) && (t = n)
                }),
                t
            }
        }, {
            key: "generateTimeout",
            value: function(e, t) {
                return Math.random() * (t - e) + e
            }
        }, {
            key: "watch",
            value: function() {
                var e = this
                  , t = void 0;
                this.$window.on("scroll resize", function() {
                    clearTimeout(t),
                    t = setTimeout(function() {
                        return e.checkRun()
                    }, 100)
                })
            }
        }]),
        e
    }();
    t.default = s
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-item-red-like">\n    <svg class="eapps-instagram-feed-posts-item-red-like-icon" viewBox="0 0 512 512">\n        <path d="M377,31c-42.3,0-69.4,10.1-105,45.7c0,0-8.6,7.6-16,15c-6.9-6.9-15.3-14.9-15.3-14.9C207.3,43.4,176.4,31,134,31\n\t\t\tC54.8,31,0,97.7,0,177.5c0,90.4,72.8,152.4,183,246.3c19.8,16.9,40.3,34.4,62.1,53.4c2.8,2.5,6.4,3.7,9.9,3.7s7-1.2,9.9-3.7\n\t\t\tc23.2-20.3,44.9-38.7,65.9-56.6C433.5,333.6,512,269,512,177.5C512,97.6,456.1,31,377,31z"></path>\n    </svg>\n\n    <span class="eapps-instagram-feed-posts-item-red-like-count">1</span>\n\n    <span class="eapps-instagram-feed-posts-item-red-like-label">\n        ' + e.labels.redLike + "\n    </span>\n</div>"
    }
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(68))
      , r = function(e) {
        function t(e, n, i) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t);
            var a = function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).call(this, n, i));
            return a.layout = e,
            a.options = i,
            a
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, a.default),
        i(t, [{
            key: "hasNext",
            value: function() {
                return this.currentIndex < this.$slides.length - 1 || this.options.loop && this.$slides.length > 1 || this.layout.posts.fetcher.hasNext()
            }
        }, {
            key: "moveNext",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                this.translating || (this.layout.posts.fetcher.hasNext() && this.isEnd() ? this.layout.addView().then(function() {
                    setTimeout(function() {
                        t.updateSlides(),
                        n.resolve()
                    })
                }) : n.reject(),
                this.hasNext() && (this.currentIndex++,
                this.realIndex++),
                this.translate(e).then(function() {
                    n.promise().then(function() {
                        t.checkLoopNeed()
                    })
                }))
            }
        }, {
            key: "checkLoopNeed",
            value: function() {
                this.layout.posts.fetcher.hasNext() || this.loop || !this.options.loop || this.enableLoop()
            }
        }]),
        t
    }();
    t.default = r
}
, function(e, t, n) {
    "use strict";
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var i = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , a = function(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }(n(1))
      , r = {
        arrows: !0,
        drag: !0,
        speed: 600,
        autoplayDelay: 0,
        loop: !0
    }
      , o = function() {
        function e(t, n) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.$element = t,
            this.options = jQuery.extend({}, r, n)
        }
        return i(e, [{
            key: "init",
            value: function() {
                this.$inner = this.$element.find(".eui-slider-inner"),
                this.$realSlides = this.$element.find(".eui-slider-slide"),
                this.$arrowPrev = this.$element.find(".eui-slider-arrow-prev"),
                this.$arrowNext = this.$element.find(".eui-slider-arrow-next"),
                this.currentIndex = 0,
                this.realIndex = 0,
                this.sliderWidth = null,
                this.sliderHeight = null,
                this.diffX = 0,
                this.diffY = 0,
                this.minDiff = 3,
                this.startX = null,
                this.startY = null,
                this.verticalMove = !1,
                this.horizontalMove = !1,
                this.translating = !1,
                this.loop = !1,
                this.isHovered = !1,
                this.isTouched = !1,
                this.allowClick = !0,
                this.updateSlides(),
                this.updateArrows(),
                this.speed = parseInt(this.options.speed, 10),
                this.autoplayDelay = parseInt(this.options.autoplayDelay, 10),
                this.autoplay = this.autoplayDelay > 0,
                this.autoplay && (this.autoplayEnabled = !0,
                this.autoplayPaused = !1,
                this.autoplayTimeout = null,
                this.autoplayDelay = Math.max(this.autoplayDelay, this.speed),
                this.startAutoplay()),
                this.watch()
            }
        }, {
            key: "hasPrev",
            value: function() {
                return this.currentIndex > 0 || this.loop
            }
        }, {
            key: "hasNext",
            value: function() {
                return this.currentIndex < this.$slides.length - 1 || this.loop
            }
        }, {
            key: "hasIndex",
            value: function(e) {
                return e < this.$slides.length
            }
        }, {
            key: "isBeginning",
            value: function() {
                return 0 === this.currentIndex
            }
        }, {
            key: "isEnd",
            value: function() {
                return this.currentIndex === this.$slides.length - 1
            }
        }, {
            key: "isRealBeginning",
            value: function() {
                return 0 === this.realIndex
            }
        }, {
            key: "isRealEnd",
            value: function() {
                return this.realIndex === this.$realSlides.length - 1
            }
        }, {
            key: "updateArrows",
            value: function() {
                this.$arrowPrev.toggleClass("eui-slider-arrow-enabled", this.options.arrows && this.hasPrev()),
                this.$arrowNext.toggleClass("eui-slider-arrow-enabled", this.options.arrows && this.hasNext())
            }
        }, {
            key: "movePrev",
            value: function(e) {
                this.translating || (this.hasPrev() && (this.currentIndex--,
                this.realIndex--),
                this.translate(e))
            }
        }, {
            key: "moveNext",
            value: function(e) {
                this.translating || (this.hasNext() && (this.currentIndex++,
                this.realIndex++),
                this.translate(e))
            }
        }, {
            key: "moveTo",
            value: function(e, t) {
                if (!this.hasIndex(e))
                    return !1;
                this.currentIndex = e,
                this.realIndex = e,
                this.translate(t)
            }
        }, {
            key: "translate",
            value: function(e) {
                var t = this
                  , n = jQuery.Deferred();
                return this.stopAutoplay(),
                e ? (this.$inner.css("transition-duration", "0ms"),
                this.$inner.css("transform", "translate3d(" + 100 * -this.realIndex + "%,0,0)"),
                this.translateEnd(),
                n.resolve()) : (this.translating = !0,
                this.$inner.css("transition-duration", this.speed + "ms"),
                this.$inner.addClass("eui-slider-inner-translating"),
                this.$inner.css("transform", "translate3d(" + 100 * -this.realIndex + "%,0,0)"),
                setTimeout(function() {
                    t.$inner.css("transition-duration", "0ms"),
                    t.$inner.removeClass("eui-slider-inner-translating"),
                    t.translating = !1,
                    t.translateEnd(),
                    n.resolve()
                }, this.speed)),
                n.promise()
            }
        }, {
            key: "updateSlidesClasses",
            value: function() {
                this.$slides.removeClass("eui-slider-slide-active"),
                this.$slides.eq(this.currentIndex).addClass("eui-slider-slide-active")
            }
        }, {
            key: "translateEnd",
            value: function() {
                this.updateLoopPosition(),
                this.updateSlidesClasses(),
                this.updateArrows(),
                this.startAutoplay()
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                if (this.options.drag) {
                    this.$inner.get(0).addEventListener("click", function(t) {
                        !e.allowClick && (Math.abs(e.diffX) > 1 || Math.abs(e.diffY) > 1) && (t.preventDefault(),
                        t.stopPropagation(),
                        t.stopImmediatePropagation())
                    }, !0),
                    this.$inner.get(0).addEventListener("dragstart", function(e) {
                        return e.preventDefault()
                    }, !0),
                    this.$inner.on("mousedown touchstart", function(t) {
                        e.translating || (e.isTouched = !0,
                        e.verticalMove = !1,
                        e.horizontalMove = !1,
                        e.diffX = 0,
                        e.startX = a.default.others.isMobileDevice() && t.originalEvent.changedTouches ? t.originalEvent.changedTouches[0].clientX : t.pageX,
                        e.startY = a.default.others.isMobileDevice() && t.originalEvent.changedTouches ? t.originalEvent.changedTouches[0].clientY : t.pageY,
                        e.sliderWidth = e.$element.outerWidth(),
                        e.sliderHeight = e.$element.outerHeight())
                    });
                    var t = function(t) {
                        if (e.isTouched) {
                            e.allowClick = !1;
                            var n = a.default.others.isMobileDevice() && t.changedTouches ? t.changedTouches[0].clientX : t.pageX
                              , i = a.default.others.isMobileDevice() && t.changedTouches ? t.changedTouches[0].clientY : t.pageY;
                            e.diffX = (e.startX - n) / e.sliderWidth * 100,
                            e.diffY = (e.startY - i) / e.sliderHeight * 100,
                            Math.abs(e.diffX) > e.minDiff && !e.verticalMove && (e.horizontalMove = !0),
                            Math.abs(e.diffY) > e.minDiff && !e.horizontalMove && (e.verticalMove = !0),
                            e.verticalMove || (e.loop || (!e.realIndex && e.diffX < 0 || e.realIndex === e.$realSlides.length && e.diffX > 0) && (e.diffX /= 2),
                            e.$inner.css({
                                transform: "translate3d(" + (100 * -e.realIndex - e.diffX) + "%,0,0)"
                            }),
                            t.preventDefault())
                        }
                    };
                    jQuery(document).get(0).addEventListener("mousemove", t, {
                        passive: !1
                    }),
                    jQuery(document).get(0).addEventListener("touchmove", t, {
                        passive: !1
                    }),
                    jQuery(document).on("mouseup touchend", function(t) {
                        if (e.isTouched) {
                            if (e.isTouched = !1,
                            setTimeout(function() {
                                e.allowClick = !0
                            }),
                            e.translating)
                                return;
                            e.horizontalMove && e.diffX <= -e.minDiff ? e.movePrev() : e.horizontalMove && e.diffX >= e.minDiff ? e.moveNext() : e.translate()
                        }
                    })
                }
                this.$element.mouseenter(function() {
                    e.isHovered = !0,
                    e.stopAutoplay()
                }),
                this.$element.mouseleave(function() {
                    e.isHovered = !1,
                    e.startAutoplay()
                }),
                this.$arrowPrev.on("click touchend", function(t) {
                    e.movePrev(),
                    t.stopPropagation(),
                    t.preventDefault()
                }),
                this.$arrowNext.on("click touchend", function(t) {
                    e.moveNext(),
                    t.stopPropagation(),
                    t.preventDefault()
                })
            }
        }, {
            key: "startAutoplay",
            value: function() {
                !this.isHovered && this.autoplayEnabled && (this.autoplayPaused = !1,
                this.tickAutoplay())
            }
        }, {
            key: "stopAutoplay",
            value: function() {
                this.autoplayEnabled && (clearTimeout(this.autoplayTimeout),
                this.autoplayPaused = !0)
            }
        }, {
            key: "tickAutoplay",
            value: function() {
                var e = this;
                this.autoplayTimeout = setTimeout(function() {
                    e.autoplayEnabled && !e.autoplayPaused && e.moveNext()
                }, this.autoplayDelay)
            }
        }, {
            key: "enableAutoplay",
            value: function() {
                this.autoplayEnabled = !0,
                this.startAutoplay()
            }
        }, {
            key: "disableAutoplay",
            value: function() {
                this.autoplayEnabled = !1,
                this.stopAutoplay()
            }
        }, {
            key: "enableLoop",
            value: function() {
                if (!this.loop) {
                    var e = this.$slides.eq(0).clone(!0).addClass("eui-slider-slide-clone");
                    this.$slides.eq(this.$slides.length - 1).clone(!0).addClass("eui-slider-slide-clone").prependTo(this.$inner),
                    e.appendTo(this.$inner),
                    this.updateSlides(),
                    this.realIndex++,
                    this.translate(!0),
                    this.loop = !0
                }
            }
        }, {
            key: "updateLoopPosition",
            value: function() {
                this.loop && (this.isRealBeginning() && (this.currentIndex = this.$slides.length - 1,
                this.realIndex = this.$realSlides.length - 2,
                this.translate(!0)),
                this.isRealEnd() && (this.realIndex = 1,
                this.currentIndex = 0,
                this.translate(!0)))
            }
        }, {
            key: "updateSlides",
            value: function() {
                this.$realSlides = this.$element.find(".eui-slider-slide"),
                this.$slides = this.$realSlides.filter(":not(.eui-slider-slide-clone)")
            }
        }]),
        e
    }();
    t.default = o
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-slider eapps-instagram-feed-posts eui-slider">\n    <div class="eapps-instagram-feed-posts-slider-inner eapps-instagram-feed-posts-inner eui-slider-inner"></div>\n\n    <div class="eapps-instagram-feed-posts-slider-prev eapps-instagram-feed-posts-slider-nav eui-slider-arrow-prev eui-slider-arrow">\n        <svg viewBox="4 0 8 16" width="12" height="16" class="eapps-instagram-feed-posts-slider-nav-icon">\n            <path d="M4.3,8.7l6,5.9c0.4,0.4,1.1,0.4,1.5,0c0.4-0.4,0.4-1.1,0-1.5L6.5,8l5.2-5.2c0.4-0.4,0.4-1.1,0-1.5\n        c-0.4-0.4-1.1-0.4-1.5,0l-6,6C3.9,7.7,3.9,8.3,4.3,8.7z"></path>\n        </svg>\n    </div>\n\n    <div class="eapps-instagram-feed-posts-slider-next eapps-instagram-feed-posts-slider-nav eui-slider-arrow-next eui-slider-arrow">\n        <svg viewBox="4 0 8 16" width="12" height="16" class="eapps-instagram-feed-posts-slider-nav-icon">\n            <path d="M11.7,7.3l-6-5.9c-0.4-0.4-1.1-0.4-1.5,0c-0.4,0.4-0.4,1.1,0,1.5L9.5,8l-5.2,5.2\n        c-0.4,0.4-0.4,1.1,0,1.5c0.4,0.4,1.1,0.4,1.5,0l6-6C12.1,8.3,12.1,7.7,11.7,7.3z"></path>\n        </svg>\n    </div>\n</div>'
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = function e(t, n, i) {
        null === t && (t = Function.prototype);
        var a = Object.getOwnPropertyDescriptor(t, n);
        if (void 0 === a) {
            var r = Object.getPrototypeOf(t);
            return null === r ? void 0 : e(r, n, i)
        }
        if ("value"in a)
            return a.value;
        var o = a.get;
        return void 0 !== o ? o.call(i) : void 0
    }
      , o = i(n(4))
      , s = i(n(0))
      , l = i(n(71))
      , p = function(e) {
        function t() {
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, t),
            function(e, t) {
                if (!e)
                    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
                return !t || "object" != typeof t && "function" != typeof t ? e : t
            }(this, (t.__proto__ || Object.getPrototypeOf(t)).apply(this, arguments))
        }
        return function(e, t) {
            if ("function" != typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function, not " + typeof t);
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && (Object.setPrototypeOf ? Object.setPrototypeOf(e, t) : e.__proto__ = t)
        }(t, o.default),
        a(t, [{
            key: "init",
            value: function(e) {
                return !e && (r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "init", this).call(this),
                this.$loadMore = this.$element.find("." + s.default.alias + "-posts-grid-load-more"),
                this.watch(),
                this.addView())
            }
        }, {
            key: "createElement",
            value: function() {
                var e = {
                    loadMore: this.lang.get("Load more")
                };
                return jQuery((0,
                l.default)(e))
            }
        }, {
            key: "addView",
            value: function() {
                var e = this;
                return r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "addView", this).call(this).then(function() {
                    e.$loadMore.toggleClass(s.default.alias + "-posts-grid-load-more-visible", e.posts.fetcher.hasNext())
                })
            }
        }, {
            key: "watch",
            value: function() {
                var e = this;
                r(t.prototype.__proto__ || Object.getPrototypeOf(t.prototype), "watch", this).call(this),
                this.$loadMore.click(function() {
                    var t = s.default.alias + "-posts-grid-load-more-loading";
                    e.$loadMore.addClass(t),
                    e.addView().then(function() {
                        e.$loadMore.removeClass(t)
                    })
                })
            }
        }]),
        t
    }();
    t.default = p
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-posts-grid eapps-instagram-feed-posts">\n    <div class="eapps-instagram-feed-posts-inner"></div>\n\n    <div class="eapps-instagram-feed-posts-grid-load-more-container">\n        <div class="eapps-instagram-feed-posts-grid-load-more">\n            <div class="eapps-instagram-feed-posts-grid-load-more-text eapps-instagram-feed-posts-grid-load-more-text-visible">\n                ' + e.loadMore + '\n            </div>\n\n            <div class="eapps-instagram-feed-loader"></div>\n        </div>\n    </div>\n</div>'
    }
}
, function(e, t, n) {
    "use strict";
    function i(e) {
        return e && e.__esModule ? e : {
            default: e
        }
    }
    Object.defineProperty(t, "__esModule", {
        value: !0
    });
    var a = function() {
        function e(e, t) {
            for (var n = 0; n < t.length; n++) {
                var i = t[n];
                i.enumerable = i.enumerable || !1,
                i.configurable = !0,
                "value"in i && (i.writable = !0),
                Object.defineProperty(e, i.key, i)
            }
        }
        return function(t, n, i) {
            return n && e(t.prototype, n),
            i && e(t, i),
            t
        }
    }()
      , r = i(n(1))
      , o = i(n(0))
      , s = i(n(73))
      , l = function() {
        function e(t) {
            (function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }
            )(this, e),
            this.widget = t,
            this.tuner = t.tuner,
            this.data = {
                title: r.default.links.formatInstagramAnchors(this.tuner.get("widgetTitle"))
            },
            this.$element = this.createElement()
        }
        return a(e, [{
            key: "createElement",
            value: function() {
                return jQuery((0,
                s.default)(this.data))
            }
        }, {
            key: "show",
            value: function() {
                this.$element.addClass(o.default.alias + "-title-visible")
            }
        }]),
        e
    }();
    t.default = l
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed-title">' + e.title + "</div>"
    }
}
, function(e, t, n) {
    "use strict";
    e.exports = {
        en: {},
        de: {
            "View on Instagram": "Auf Instagram anzeigen",
            Follow: "Folgen",
            "Liked by %1 and %2 others": "Gefallen von %1 und %2 anderen",
            "View all %1 comments": "Alle %1 Kommentare anzeigen",
            more: "mehr anzeigen",
            "Load more": "Weitere anzeigen",
            Share: "Teilen",
            "Share on Facebook": "Teilen auf Facebook",
            "Share on Twitter": "Teilen auf Twitter",
            "Share on Google+": "Teilen auf Google+",
            "%1 days ago": "vor %1 Tagen",
            "%1 hours ago": "vor %1 Stunden",
            "%1 minutes ago": "vor %1 Minuten",
            "%1 seconds ago": "vor %1 Sekunden"
        },
        es: {
            "View on Instagram": "Ver en Instagram",
            Follow: "Seguir",
            "Liked by %1 and %2 others": "Me gustó %1 y otros %2",
            "View all %1 comments": "Ver los %1 comentarios",
            more: "ver más",
            "Load more": "Cargar más",
            Share: "Compartir",
            "Share on Facebook": "Compartir en Facebook",
            "Share on Twitter": "Compartir en Twitter",
            "Share on Google+": "Compartir en Google+",
            "%1 days ago": "hace %1 días",
            "%1 hours ago": "hace %1 horas",
            "%1 minutes ago": "hace %1 minutos",
            "%1 seconds ago": "hace %1 segundos"
        },
        fi: {
            "View on Instagram": "Katsele Instagramissa",
            Follow: "Seurata",
            "Liked by %1 and %2 others": "Toivoi %1 ja %2 muuta",
            "View all %1 comments": "Näytä kaikki %1 kommenttia",
            more: "lisää",
            "Load more": "Lataa lisää",
            Share: "Jaa",
            "Share on Facebook": "Jaa Facebookissa",
            "Share on Twitter": "Jaa Twitterissä",
            "Share on Google+": "Jaa Google+",
            "%1 days ago": "%1 päivää sitten",
            "%1 hours ago": "%1 tuntia sitten",
            "%1 minutes ago": "%1 minuuttia sitten",
            "%1 seconds ago": "%1 sekuntia sitten"
        },
        fr: {
            "View on Instagram": "Voir sur Instagram",
            Follow: "S`abonner",
            "Liked by %1 and %2 others": "Aimé par %1 et %2 autres",
            "View all %1 comments": "Afficher tous les %1 commentaires",
            more: "voir plus",
            "Load more": "Charger plus",
            Share: "Partager",
            "Share on Facebook": "Partager sur Facebook",
            "Share on Twitter": "Partager sur Twitter",
            "Share on Google+": "Partager sur Google+",
            "%1 days ago": "ll Y A %1 jours",
            "%1 hours ago": "ll Y A %1 heures",
            "%1 minutes ago": "ll Y A %1 minutes",
            "%1 seconds ago": "ll y a %1 secondes"
        },
        it: {
            "View on Instagram": "Vedi su Instagram",
            Follow: "Segui",
            "Liked by %1 and %2 others": "Mi è piaciuto %1 e altri %2",
            "View all %1 comments": "Visualizza tutti i %1 commenti",
            more: "vedi altro",
            "Load more": "Caricare di più",
            Share: "Condividere",
            "Share on Facebook": "Condividi su Facebook",
            "Share on Twitter": "Condividi su Twitter",
            "Share on Google+": "Condividi su Google+",
            "%1 days ago": "%1 giorni fa",
            "%1 hours ago": "%1 ore fa",
            "%1 minutes ago": "%1 minuti fa",
            "%1 seconds ago": "%1 secondi fa"
        },
        nl: {
            "View on Instagram": "Bekijk op Instagram",
            Follow: "Volgen",
            "Liked by %1 and %2 others": "Leuk gevonden door %1 en %2 anderen",
            "View all %1 comments": "Bekijk alle %1 reacties",
            more: "meer",
            "Load more": "Meer laden",
            Share: "Delen",
            "Share on Facebook": "Delen op Facebook",
            "Share on Twitter": "Delen op Twitter",
            "Share on Google+": "Delen op Google+",
            "%1 days ago": "%1 dagen geleden",
            "%1 hours ago": "%1 uur geleden",
            "%1 minutes ago": "%1 minuten geleden",
            "%1 seconds ago": "%1 seconden geleden"
        },
        no: {
            "View on Instagram": "Se på Instagram",
            Follow: "Følg",
            "Liked by %1 and %2 others": "Likte av %1 og %2 andre",
            "View all %1 comments": "Se alle %1 kommentarer",
            more: "mer",
            "Load more": "Last mer",
            Share: "Dele",
            "Share on Facebook": "Del på Facebook",
            "Share on Twitter": "Del på Twitter",
            "Share on Google+": "Del på Google+",
            "%1 days ago": "%1 dager siden",
            "%1 hours ago": "%1 timer siden",
            "%1 minutes ago": "%1 minutter siden",
            "%1 seconds ago": "%1 sekunder siden"
        },
        pl: {
            "View on Instagram": "Zobacz na Instagramie",
            Follow: "Obserwuj",
            "Liked by %1 and %2 others": "Polubione przez %1 i %2 innych",
            "View all %1 comments": "Zobacz wszystkie %1 komentarzy",
            more: "jeszcze",
            "Load more": "Załaduj więcej",
            Share: "Dzielić",
            "Share on Facebook": "Udostępnij na Facebooku",
            "Share on Twitter": "Podziel się na Twitterze",
            "Share on Google+": "Udostępnij w Google+",
            "%1 days ago": "%1 dni temu",
            "%1 hours ago": "%1 godzin temu",
            "%1 minutes ago": "%1 min temu",
            "%1 seconds ago": "%1 sekundy temu"
        },
        "pt-BR": {
            "View on Instagram": "Ver no Instagram",
            Follow: "Seguir",
            "Liked by %1 and %2 others": "Gostei de %1 e outros %2",
            "View all %1 comments": "Exibir todos os %1 comentários",
            more: "ver mais",
            "Load more": "Carregar mais",
            Share: "Compartilhar",
            "Share on Facebook": "Compartilhar no Facebook",
            "Share on Twitter": "Compartilhar no Twitter",
            "Share on Google+": "Compartilhar no Google+",
            "%1 days ago": "hà %1 dias",
            "%1 hours ago": "hà %1 horas",
            "%1 minutes ago": "hà %1 minutos",
            "%1 seconds ago": "hà %1 segundos"
        },
        sl: {
            "View on Instagram": "Poglej na Instagramu",
            Follow: "Sledite",
            "Liked by %1 and %2 others": "Všeč %1 in %2 drugih",
            "View all %1 comments": "Ogled vseh %1 komentarjev",
            more: "več",
            "Load more": "Naloži več",
            Share: "Deliti",
            "Share on Facebook": "Deli na Facebooku",
            "Share on Twitter": "Delite na Twitterju",
            "Share on Google+": "Deli na Google+",
            "%1 days ago": "pred %1 dnevi",
            "%1 hours ago": "pred %1 urami",
            "%1 minutes ago": "pred %1 minutami",
            "%1 seconds ago": "pred %1 sekundami"
        },
        sv: {
            "View on Instagram": "Titta på Instagram",
            Follow: "Följ",
            "Liked by %1 and %2 others": "Gillade av %1 och %2 andra",
            "View all %1 comments": "Visa alla %1 kommentarer",
            more: "mer",
            "Load more": "Ladda mer",
            Share: "Dela med sig",
            "Share on Facebook": "Dela på Facebook",
            "Share on Twitter": "Dela på Twitter",
            "Share on Google+": "Dela på Google+",
            "%1 days ago": "för %1 dag sedan",
            "%1 hours ago": "för %1 timmar sedan",
            "%1 minutes ago": "för %1 minuter sedan",
            "%1 seconds ago": "för %1 sekunder sedan"
        },
        tr: {
            "View on Instagram": "Instagram'da görüntüle",
            Follow: "Takip et",
            "Liked by %1 and %2 others": "%1 ve diğer %2 kişi beğendi",
            "View all %1 comments": "Tüm %1 yorum",
            more: "еще",
            "Load more": "Daha fazla yükle",
            Share: "Pay",
            "Share on Facebook": "Facebook'ta Paylaş",
            "Share on Twitter": "Twitter'da paylaş",
            "Share on Google+": "Google + 'da paylaşın",
            "%1 days ago": "%1 gün önce",
            "%1 hours ago": "%1 saat önce",
            "%1 minutes ago": "%1 dakika önce",
            "%1 seconds ago": "%1 saniye önce"
        },
        ru: {
            "View on Instagram": "Посмотреть в Instagram",
            Follow: "Подписаться",
            "Liked by %1 and %2 others": "Понравилось %1 и еще %2 людям",
            "View all %1 comments": "Посмотреть все %1 комментариев",
            more: "еще",
            "Load more": "Загрузить еще",
            Share: "Поделиться",
            "Share on Facebook": "Поделиться на Facebook",
            "Share on Twitter": "Поделиться на Twitter",
            "Share on Google+": "Поделиться на Google+",
            "%1 days ago": "%1 дней назад",
            "%1 hours ago": "%1 часов назад",
            "%1 minutes ago": "%1 минут назад",
            "%1 seconds ago": "%1 секунд назад"
        },
        hi: {
            "View on Instagram": "इन्सटाग्राम पर देखें",
            Follow: "फ़ॉलो करें",
            "Liked by %1 and %2 others": "%1 और %2 अन्य की पसंद",
            "View all %1 comments": "सभी %1 टिप्पणियां देखेंв",
            more: "अधिक",
            "Load more": "और लोड करें",
            Share: "शेयर",
            "Share on Facebook": "फेसबुक पर सांझा करें",
            "Share on Twitter": "ट्विटर पर साझा करें",
            "Share on Google+": "Google+ पर साझा करें",
            "%1 days ago": "%1 दिन पहले",
            "%1 hours ago": "%1 घंटे पहले",
            "%1 minutes ago": "%1 मिनट पहले",
            "%1 seconds ago": "%1 सेकंड पहले"
        },
        ko: {
            "View on Instagram": "Instagram보기",
            Follow: "팔로우",
            "Liked by %1 and %2 others": "%1 외 %2 명이 좋아함",
            "View all %1 comments": "모두보기 %1 댓글",
            more: "더",
            "Load more": "더 많은로드",
            Share: "몫",
            "Share on Facebook": "Facebook에서 공유",
            "Share on Twitter": "Twitter에서 공유",
            "Share on Google+": "Google+에서 공유",
            "%1 days ago": "%1일 전",
            "%1 hours ago": "%1시간 전",
            "%1 minutes ago": "%1분 전",
            "%1 seconds ago": "%1초 전"
        },
        "zh-CN": {
            "View on Instagram": "在Instagram上查看",
            Follow: "天注",
            "Liked by %1 and %2 others": "%1和其他12人喜欢",
            "View all %1 comments": "查看所有%1条评论",
            more: "更多",
            "Load more": "装载更多",
            Share: "分享",
            "Share on Facebook": "分享到Facebook",
            "Share on Twitter": "分享到Twitter",
            "Share on Google+": "分享到Google+",
            "%1 days ago": "%1天前",
            "%1 hours ago": "%1小时前",
            "%1 minutes ago": "%1分钟前",
            "%1 seconds ago": "%1秒前"
        },
        "zh-HK": {
            "View on Instagram": "在Instagram上查看",
            Follow: "追蹤",
            "Liked by %1 and %2 others": "%1和其他%2人讚好",
            "View all %1 comments": "查看全部 %1 則回應",
            more: "更多",
            "Load more": "載入更多",
            Share: "分享",
            "Share on Facebook": "分享到Facebook",
            "Share on Twitter": "分享到Twitter",
            "Share on Google+": "分享到Google+",
            "%1 days ago": "%1天前",
            "%1 hours ago": "%1小時前",
            "%1 minutes ago": "%1分鐘前",
            "%1 seconds ago": "%1秒前"
        },
        ja: {
            "View on Instagram": "インスタグラムで見る",
            Follow: "フォローする",
            "Liked by %1 and %2 others": "%1と他%2人が好き",
            "View all %1 comments": "%1件のコメントをすべて表示",
            more: "もっと",
            "Load more": "もっと読み込む",
            Share: "シェア",
            "Share on Facebook": "Facebookでシェア",
            "Share on Twitter": "Twitterで共有する",
            "Share on Google+": "Google+で共有する",
            "%1 days ago": "%1日前",
            "%1 hours ago": "%1時間前",
            "%1 minutes ago": "%1分前",
            "%1 seconds ago": "%1秒前"
        },
        vn: {
            "View on Instagram": "Xem trên Instagram",
            Follow: "Theo",
            "Liked by %1 and %2 others": "Thích bởi %1 và %2 người khác",
            "View all %1 comments": "Xem tất cả %1 ý kiến",
            more: "hơn",
            "Load more": "Tải thêm",
            Share: "Chia sẻ",
            "Share on Facebook": "Chia sẽ trên Facebook",
            "Share on Twitter": "Chia sẽ trên Twitter",
            "Share on Google+": "Chia sẻ trên Google+",
            "%1 days ago": "%1 ngày trước",
            "%1 hours ago": "%1 giờ trước",
            "%1 minutes ago": "%1 phút trước",
            "%1 seconds ago": "%1 giây trước"
        },
        "he-IL": {
            "View on Instagram": "לצפייה באינסטגרם",
            Follow: "לעקוב אחרי",
            "Liked by %1 and %2 others": "%1 ועוד %2 אחרים אהבו",
            "View all %1 comments": "לצפייה בכל %1 התגובות",
            more: "עוד",
            "Load more": "טען עוד",
            Share: "שיתוף",
            "Share on Facebook": "שיתוף בפייסבוק",
            "Share on Twitter": "שיתוף בטוויטר",
            "Share on Google+": "שיתוף בגוגל+",
            "%1 days ago": "לפני %1 ימים",
            "%1 hours ago": "לפני %1 שעות",
            "%1 minutes ago": "לפני %1 דקות",
            "%1 seconds ago": "לפני %1 שניות"
        }
    }
}
, function(e, t) {
    e.exports = function(e) {
        return '<div class="eapps-instagram-feed eapps-widget">\n    <div class="eapps-instagram-feed-title-container"></div>\n\n    <div class="eapps-instagram-feed-content">\n        <div class="eapps-instagram-feed-posts-container"></div>\n\n        <div class="eapps-instagram-feed-error-container"></div>\n\n        <div class="eapps-instagram-feed-content-loader eapps-instagram-feed-loader"></div>\n    </div>\n</div>'
    }
}
, function(e, t) {
    e.exports = function(e) {
        var t = "<style>\n    ";
        return e.width && (t += "\n        " + e.feedId + " {\n            width: " + e.width + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPostBg && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-template-classic {\n            background: " + e.colorPostBg + ";\n        }\n\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-user-image {\n            box-shadow: 0 0 0 2px " + e.colorPostBg + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPostText && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-item,\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-content,\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-text,\n        " + e.feedId + " " + e.feedPrefix + "-posts-item a,\n        " + e.feedId + " " + e.feedPrefix + "-posts-item a:hover {\n            color: " + e.colorPostText + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPostLinks && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-content a,\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-content a:hover {\n            color: " + e.colorPostLinks + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPostOverlayText && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-overlay " + e.feedPrefix + "-posts-item-content,\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-overlay " + e.feedPrefix + "-posts-item-text {\n            color: " + e.colorPostOverlayText + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPostOverlayBg && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-item-overlay {\n            background: " + e.colorPostOverlayBg + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorSliderArrows && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-slider-nav-icon {\n            fill: " + e.colorSliderArrows + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorSliderArrowsBg && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-slider-nav {\n            background: " + e.colorSliderArrowsBg + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorGridLoadMoreButton && (t += "\n        " + e.feedId + " " + e.feedPrefix + "-posts-grid-load-more {\n            background: " + e.colorGridLoadMoreButton + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPopupOverlay && (t += "\n        " + e.popupId + " {\n            background: " + e.colorPopupOverlay + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPopupBg && (t += "\n        " + e.popupId + " " + e.popupPrefix + "-item {\n            background: " + e.colorPopupBg + ";\n        }\n\n        " + e.popupId + " ." + e.className + "-posts-item-user-image {\n            box-shadow: 0 0 0 2px " + e.colorPopupBg + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPopupText && (t += "\n        " + e.popupId + " " + e.popupPrefix + "-item,\n        " + e.popupId + " " + e.popupPrefix + "-item a,\n        " + e.popupId + " " + e.popupPrefix + "-item a:hover {\n            color: " + e.colorPopupText + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPopupLinks && (t += "\n        " + e.popupId + " " + e.popupPrefix + "-item-text a:not(" + e.popupPrefix + "-item-text-author),\n        " + e.popupId + " " + e.popupPrefix + "-item-text a:not(" + e.popupPrefix + "-item-text-author):hover,\n        " + e.popupId + " " + e.popupPrefix + "-item-comments-item-text a,\n        " + e.popupId + " " + e.popupPrefix + "-item-comments-item-text a:hover {\n            color: " + e.colorPopupLinks + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPopupFollowButton && (t += "\n        " + e.popupId + " " + e.popupPrefix + "-item ." + e.className + "-posts-item-user-follow-link a,\n        " + e.popupId + " " + e.popupPrefix + "-item ." + e.className + "-posts-item-user-follow-link a:hover {\n            color: " + e.colorPopupFollowButton + ";\n        }\n    "),
        t += "\n\n    ",
        e.colorPopupCtaButton && (t += "\n        " + e.popupId + " a" + e.popupPrefix + "-item-cta-button,\n        " + e.popupId + " a" + e.popupPrefix + "-item-cta-button:hover {\n            color: " + e.colorPopupCtaButton + ";\n        }\n\n        " + e.popupId + " " + e.popupPrefix + "-item-cta-highlighted a" + e.popupPrefix + "-item-cta-button,\n        " + e.popupId + " " + e.popupPrefix + "-item-cta-highlighted a" + e.popupPrefix + "-item-cta-button:hover {\n            background: " + e.colorPopupCtaButton + ";\n            color: #fff;\n        }\n    "),
        t += "\n</style>"
    }
}
, function(e, t, n) {
    "use strict";
    var i, a, r;
    "function" == typeof Symbol && Symbol.iterator,
    a = [],
    void 0 !== (r = "function" == typeof (i = function() {
        function e(e) {
            var t = e.replace(/^v/, "").split(".")
              , n = t.splice(0, 2);
            return n.push(t.join(".")),
            n
        }
        function t(e) {
            return isNaN(Number(e)) ? e : Number(e)
        }
        function n(e) {
            if ("string" != typeof e)
                throw new TypeError("Invalid argument expected string");
            if (!i.test(e))
                throw new Error("Invalid argument not valid semver")
        }
        var i = /^v?(?:\d+)(\.(?:[x*]|\d+)(\.(?:[x*]|\d+)(?:-[\da-z\-]+(?:\.[\da-z\-]+)*)?(?:\+[\da-z\-]+(?:\.[\da-z\-]+)*)?)?)?$/i
          , a = /-([0-9A-Za-z-.]+)/;
        return function(i, r) {
            [i, r].forEach(n);
            for (var o = e(i), s = e(r), l = 0; l < 3; l++) {
                var p = parseInt(o[l] || 0, 10)
                  , u = parseInt(s[l] || 0, 10);
                if (p > u)
                    return 1;
                if (u > p)
                    return -1
            }
            if ([o[2], s[2]].every(a.test.bind(a))) {
                var c = a.exec(o[2])[1].split(".").map(t)
                  , d = a.exec(s[2])[1].split(".").map(t);
                for (l = 0; l < Math.max(c.length, d.length); l++) {
                    if (void 0 === c[l] || "string" == typeof d[l] && "number" == typeof c[l])
                        return -1;
                    if (void 0 === d[l] || "string" == typeof c[l] && "number" == typeof d[l])
                        return 1;
                    if (c[l] > d[l])
                        return 1;
                    if (d[l] > c[l])
                        return -1
                }
            } else if ([o[2], s[2]].some(a.test.bind(a)))
                return a.test(o[2]) ? -1 : 1;
            return 0
        }
    }
    ) ? i.apply(t, a) : i) && (e.exports = r)
}
]);
