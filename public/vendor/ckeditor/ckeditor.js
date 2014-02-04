/*
 Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(function () {
    if (!window.CKEDITOR || !window.CKEDITOR.dom)window.CKEDITOR || (window.CKEDITOR = function () {
        var b = {timestamp: "D4GD", version: "4.1.1 DEV (Full)", revision: "3cc33a9", rnd: Math.floor(900 * Math.random()) + 100, _: {pending: []}, status: "unloaded", basePath: function () {
            var a = window.CKEDITOR_BASEPATH || "";
            if (!a)for (var b = document.getElementsByTagName("script"), g = 0; g < b.length; g++) {
                var d = b[g].src.match(/(^|.*[\\\/])ckeditor(?:_basic)?(?:_source)?.js(?:\?.*)?$/i);
                if (d) {
                    a = d[1];
                    break
                }
            }
            -1 == a.indexOf(":/") && (a = 0 ===
                a.indexOf("/") ? location.href.match(/^.*?:\/\/[^\/]*/)[0] + a : location.href.match(/^[^\?]*\/(?:)/)[0] + a);
            if (!a)throw'The CKEditor installation path could not be automatically detected. Please set the global variable "CKEDITOR_BASEPATH" before creating editor instances.';
            return a
        }(), getUrl: function (a) {
            -1 == a.indexOf(":/") && 0 !== a.indexOf("/") && (a = this.basePath + a);
            this.timestamp && ("/" != a.charAt(a.length - 1) && !/[&?]t=/.test(a)) && (a += (0 <= a.indexOf("?") ? "&" : "?") + "t=" + this.timestamp);
            return a
        }, domReady: function () {
            function a() {
                try {
                    document.addEventListener ?
                        (document.removeEventListener("DOMContentLoaded", a, !1), b()) : document.attachEvent && "complete" === document.readyState && (document.detachEvent("onreadystatechange", a), b())
                } catch (d) {
                }
            }

            function b() {
                for (var a; a = g.shift();)a()
            }

            var g = [];
            return function (b) {
                g.push(b);
                "complete" === document.readyState && setTimeout(a, 1);
                if (1 == g.length)if (document.addEventListener)document.addEventListener("DOMContentLoaded", a, !1), window.addEventListener("load", a, !1); else if (document.attachEvent) {
                    document.attachEvent("onreadystatechange",
                        a);
                    window.attachEvent("onload", a);
                    b = !1;
                    try {
                        b = !window.frameElement
                    } catch (h) {
                    }
                    if (document.documentElement.doScroll && b) {
                        var e = function () {
                            try {
                                document.documentElement.doScroll("left")
                            } catch (b) {
                                setTimeout(e, 1);
                                return
                            }
                            a()
                        };
                        e()
                    }
                }
            }
        }()}, c = window.CKEDITOR_GETURL;
        if (c) {
            var a = b.getUrl;
            b.getUrl = function (f) {
                return c.call(b, f) || a.call(b, f)
            }
        }
        return b
    }()), CKEDITOR.event || (CKEDITOR.event = function () {
    }, CKEDITOR.event.implementOn = function (b) {
        var c = CKEDITOR.event.prototype, a;
        for (a in c)b[a] == void 0 && (b[a] = c[a])
    }, CKEDITOR.event.prototype =
        function () {
            function b(f) {
                var b = c(this);
                return b[f] || (b[f] = new a(f))
            }

            var c = function (a) {
                a = a.getPrivate && a.getPrivate() || a._ || (a._ = {});
                return a.events || (a.events = {})
            }, a = function (a) {
                this.name = a;
                this.listeners = []
            };
            a.prototype = {getListenerIndex: function (a) {
                for (var b = 0, g = this.listeners; b < g.length; b++)if (g[b].fn == a)return b;
                return-1
            }};
            return{define: function (a, h) {
                var g = b.call(this, a);
                CKEDITOR.tools.extend(g, h, true)
            }, on: function (a, h, g, d, i) {
                function e(b, e, i, k) {
                    b = {name: a, sender: this, editor: b, data: e, listenerData: d,
                        stop: i, cancel: k, removeListener: c};
                    return h.call(g, b) === false ? false : b.data
                }

                function c() {
                    m.removeListener(a, h)
                }

                var k = b.call(this, a);
                if (k.getListenerIndex(h) < 0) {
                    k = k.listeners;
                    g || (g = this);
                    isNaN(i) && (i = 10);
                    var m = this;
                    e.fn = h;
                    e.priority = i;
                    for (var n = k.length - 1; n >= 0; n--)if (k[n].priority <= i) {
                        k.splice(n + 1, 0, e);
                        return{removeListener: c}
                    }
                    k.unshift(e)
                }
                return{removeListener: c}
            }, once: function () {
                var a = arguments[1];
                arguments[1] = function (b) {
                    b.removeListener();
                    return a.apply(this, arguments)
                };
                return this.on.apply(this,
                    arguments)
            }, capture: function () {
                CKEDITOR.event.useCapture = 1;
                var a = this.on.apply(this, arguments);
                CKEDITOR.event.useCapture = 0;
                return a
            }, fire: function () {
                var a = 0, b = function () {
                    a = 1
                }, g = 0, d = function () {
                    g = 1
                };
                return function (i, e, j) {
                    var k = c(this)[i], i = a, m = g;
                    a = g = 0;
                    if (k) {
                        var n = k.listeners;
                        if (n.length)for (var n = n.slice(0), l, o = 0; o < n.length; o++) {
                            if (k.errorProof)try {
                                l = n[o].call(this, j, e, b, d)
                            } catch (q) {
                            } else l = n[o].call(this, j, e, b, d);
                            l === false ? g = 1 : typeof l != "undefined" && (e = l);
                            if (a || g)break
                        }
                    }
                    e = g ? false : typeof e == "undefined" ?
                        true : e;
                    a = i;
                    g = m;
                    return e
                }
            }(), fireOnce: function (a, b, g) {
                b = this.fire(a, b, g);
                delete c(this)[a];
                return b
            }, removeListener: function (a, b) {
                var g = c(this)[a];
                if (g) {
                    var d = g.getListenerIndex(b);
                    d >= 0 && g.listeners.splice(d, 1)
                }
            }, removeAllListeners: function () {
                var a = c(this), b;
                for (b in a)delete a[b]
            }, hasListeners: function (a) {
                return(a = c(this)[a]) && a.listeners.length > 0
            }}
        }()), CKEDITOR.editor || (CKEDITOR.editor = function () {
        CKEDITOR._.pending.push([this, arguments]);
        CKEDITOR.event.call(this)
    }, CKEDITOR.editor.prototype.fire =
        function (b, c) {
            b in{instanceReady: 1, loaded: 1} && (this[b] = true);
            return CKEDITOR.event.prototype.fire.call(this, b, c, this)
        }, CKEDITOR.editor.prototype.fireOnce = function (b, c) {
        b in{instanceReady: 1, loaded: 1} && (this[b] = true);
        return CKEDITOR.event.prototype.fireOnce.call(this, b, c, this)
    }, CKEDITOR.event.implementOn(CKEDITOR.editor.prototype)), CKEDITOR.env || (CKEDITOR.env = function () {
        var b = navigator.userAgent.toLowerCase(), c = window.opera, a = {ie: eval("/*@cc_on!@*/false"), opera: !!c && c.version, webkit: b.indexOf(" applewebkit/") > -1, air: b.indexOf(" adobeair/") > -1, mac: b.indexOf("macintosh") > -1, quirks: document.compatMode == "BackCompat", mobile: b.indexOf("mobile") > -1, iOS: /(ipad|iphone|ipod)/.test(b), isCustomDomain: function () {
            if (!this.ie)return false;
            var a = document.domain, f = window.location.hostname;
            return a != f && a != "[" + f + "]"
        }, secure: location.protocol == "https:"};
        a.gecko = navigator.product == "Gecko" && !a.webkit && !a.opera;
        if (a.webkit)b.indexOf("chrome") > -1 ? a.chrome = true : a.safari = true;
        var f = 0;
        if (a.ie) {
            f = a.quirks || !document.documentMode ?
                parseFloat(b.match(/msie (\d+)/)[1]) : document.documentMode;
            a.ie9Compat = f == 9;
            a.ie8Compat = f == 8;
            a.ie7Compat = f == 7;
            a.ie6Compat = f < 7 || a.quirks
        }
        if (a.gecko) {
            var h = b.match(/rv:([\d\.]+)/);
            if (h) {
                h = h[1].split(".");
                f = h[0] * 1E4 + (h[1] || 0) * 100 + (h[2] || 0) * 1
            }
        }
        a.opera && (f = parseFloat(c.version()));
        a.air && (f = parseFloat(b.match(/ adobeair\/(\d+)/)[1]));
        a.webkit && (f = parseFloat(b.match(/ applewebkit\/(\d+)/)[1]));
        a.version = f;
        a.isCompatible = a.iOS && f >= 534 || !a.mobile && (a.ie && f > 6 || a.gecko && f >= 10801 || a.opera && f >= 9.5 || a.air && f >=
            1 || a.webkit && f >= 522 || false);
        a.cssClass = "cke_browser_" + (a.ie ? "ie" : a.gecko ? "gecko" : a.opera ? "opera" : a.webkit ? "webkit" : "unknown");
        if (a.quirks)a.cssClass = a.cssClass + " cke_browser_quirks";
        if (a.ie) {
            a.cssClass = a.cssClass + (" cke_browser_ie" + (a.quirks || a.version < 7 ? "6" : a.version));
            if (a.quirks)a.cssClass = a.cssClass + " cke_browser_iequirks"
        }
        if (a.gecko)if (f < 10900)a.cssClass = a.cssClass + " cke_browser_gecko18"; else if (f <= 11E3)a.cssClass = a.cssClass + " cke_browser_gecko19";
        if (a.air)a.cssClass = a.cssClass + " cke_browser_air";
        return a
    }()), "unloaded" == CKEDITOR.status && function () {
        CKEDITOR.event.implementOn(CKEDITOR);
        CKEDITOR.loadFullCore = function () {
            if (CKEDITOR.status != "basic_ready")CKEDITOR.loadFullCore._load = 1; else {
                delete CKEDITOR.loadFullCore;
                var b = document.createElement("script");
                b.type = "text/javascript";
                b.src = CKEDITOR.basePath + "ckeditor.js";
                document.getElementsByTagName("head")[0].appendChild(b)
            }
        };
        CKEDITOR.loadFullCoreTimeout = 0;
        CKEDITOR.add = function (b) {
            (this._.pending || (this._.pending = [])).push(b)
        };
        (function () {
            CKEDITOR.domReady(function () {
                var b =
                    CKEDITOR.loadFullCore, c = CKEDITOR.loadFullCoreTimeout;
                if (b) {
                    CKEDITOR.status = "basic_ready";
                    b && b._load ? b() : c && setTimeout(function () {
                        CKEDITOR.loadFullCore && CKEDITOR.loadFullCore()
                    }, c * 1E3)
                }
            })
        })();
        CKEDITOR.status = "basic_loaded"
    }(), CKEDITOR.dom = {}, function () {
        var b = [], c = CKEDITOR.env.gecko ? "-moz-" : CKEDITOR.env.webkit ? "-webkit-" : CKEDITOR.env.opera ? "-o-" : CKEDITOR.env.ie ? "-ms-" : "";
        CKEDITOR.on("reset", function () {
            b = []
        });
        CKEDITOR.tools = {arrayCompare: function (a, f) {
            if (!a && !f)return true;
            if (!a || !f || a.length != f.length)return false;
            for (var b = 0; b < a.length; b++)if (a[b] != f[b])return false;
            return true
        }, clone: function (a) {
            var f;
            if (a && a instanceof Array) {
                f = [];
                for (var b = 0; b < a.length; b++)f[b] = CKEDITOR.tools.clone(a[b]);
                return f
            }
            if (a === null || typeof a != "object" || a instanceof String || a instanceof Number || a instanceof Boolean || a instanceof Date || a instanceof RegExp)return a;
            f = new a.constructor;
            for (b in a)f[b] = CKEDITOR.tools.clone(a[b]);
            return f
        }, capitalize: function (a) {
            return a.charAt(0).toUpperCase() + a.substring(1).toLowerCase()
        }, extend: function (a) {
            var f =
                arguments.length, b, g;
            if (typeof(b = arguments[f - 1]) == "boolean")f--; else if (typeof(b = arguments[f - 2]) == "boolean") {
                g = arguments[f - 1];
                f = f - 2
            }
            for (var d = 1; d < f; d++) {
                var i = arguments[d], e;
                for (e in i)if (b === true || a[e] == void 0)if (!g || e in g)a[e] = i[e]
            }
            return a
        }, prototypedCopy: function (a) {
            var f = function () {
            };
            f.prototype = a;
            return new f
        }, copy: function (a) {
            var f = {}, b;
            for (b in a)f[b] = a[b];
            return f
        }, isArray: function (a) {
            return!!a && a instanceof Array
        }, isEmpty: function (a) {
            for (var f in a)if (a.hasOwnProperty(f))return false;
            return true
        },
            cssVendorPrefix: function (a, f, b) {
                if (b)return c + a + ":" + f + ";" + a + ":" + f;
                b = {};
                b[a] = f;
                b[c + a] = f;
                return b
            }, cssStyleToDomStyle: function () {
                var a = document.createElement("div").style, f = typeof a.cssFloat != "undefined" ? "cssFloat" : typeof a.styleFloat != "undefined" ? "styleFloat" : "float";
                return function (a) {
                    return a == "float" ? f : a.replace(/-./g, function (a) {
                        return a.substr(1).toUpperCase()
                    })
                }
            }(), buildStyleHtml: function (a) {
                for (var a = [].concat(a), f, b = [], g = 0; g < a.length; g++)if (f = a[g])/@import|[{}]/.test(f) ? b.push("<style>" + f +
                    "</style>") : b.push('<link type="text/css" rel=stylesheet href="' + f + '">');
                return b.join("")
            }, htmlEncode: function (a) {
                return("" + a).replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;")
            }, htmlEncodeAttr: function (a) {
                return a.replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
            }, getNextNumber: function () {
                var a = 0;
                return function () {
                    return++a
                }
            }(), getNextId: function () {
                return"cke_" + this.getNextNumber()
            }, override: function (a, f) {
                var b = f(a);
                b.prototype = a.prototype;
                return b
            }, setTimeout: function (a, f, b, g, d) {
                d || (d = window);
                b || (b = d);
                return d.setTimeout(function () {
                    g ? a.apply(b, [].concat(g)) : a.apply(b)
                }, f || 0)
            }, trim: function () {
                var a = /(?:^[ \t\n\r]+)|(?:[ \t\n\r]+$)/g;
                return function (f) {
                    return f.replace(a, "")
                }
            }(), ltrim: function () {
                var a = /^[ \t\n\r]+/g;
                return function (f) {
                    return f.replace(a, "")
                }
            }(), rtrim: function () {
                var a = /[ \t\n\r]+$/g;
                return function (f) {
                    return f.replace(a, "")
                }
            }(), indexOf: function (a, f) {
                if (typeof f == "function")for (var b = 0, g = a.length; b < g; b++) {
                    if (f(a[b]))return b
                } else {
                    if (a.indexOf)return a.indexOf(f);
                    b = 0;
                    for (g = a.length; b < g; b++)if (a[b] === f)return b
                }
                return-1
            }, search: function (a, b) {
                var h = CKEDITOR.tools.indexOf(a, b);
                return h >= 0 ? a[h] : null
            }, bind: function (a, b) {
                return function () {
                    return a.apply(b, arguments)
                }
            }, createClass: function (a) {
                var b = a.$, h = a.base, g = a.privates || a._, d = a.proto, a = a.statics;
                !b && (b = function () {
                    h && this.base.apply(this, arguments)
                });
                if (g)var i = b, b = function () {
                    var a = this._ || (this._ = {}), b;
                    for (b in g) {
                        var f = g[b];
                        a[b] = typeof f == "function" ? CKEDITOR.tools.bind(f, this) : f
                    }
                    i.apply(this, arguments)
                };
                if (h) {
                    b.prototype =
                        this.prototypedCopy(h.prototype);
                    b.prototype.constructor = b;
                    b.base = h;
                    b.baseProto = h.prototype;
                    b.prototype.base = function () {
                        this.base = h.prototype.base;
                        h.apply(this, arguments);
                        this.base = arguments.callee
                    }
                }
                d && this.extend(b.prototype, d, true);
                a && this.extend(b, a, true);
                return b
            }, addFunction: function (a, f) {
                return b.push(function () {
                    return a.apply(f || this, arguments)
                }) - 1
            }, removeFunction: function (a) {
                b[a] = null
            }, callFunction: function (a) {
                var f = b[a];
                return f && f.apply(window, Array.prototype.slice.call(arguments, 1))
            }, cssLength: function () {
                var a =
                    /^-?\d+\.?\d*px$/, b;
                return function (h) {
                    b = CKEDITOR.tools.trim(h + "") + "px";
                    return a.test(b) ? b : h || ""
                }
            }(), convertToPx: function () {
                var a;
                return function (b) {
                    if (!a) {
                        a = CKEDITOR.dom.element.createFromHtml('<div style="position:absolute;left:-9999px;top:-9999px;margin:0px;padding:0px;border:0px;"></div>', CKEDITOR.document);
                        CKEDITOR.document.getBody().append(a)
                    }
                    if (!/%$/.test(b)) {
                        a.setStyle("width", b);
                        return a.$.clientWidth
                    }
                    return b
                }
            }(), repeat: function (a, b) {
                return Array(b + 1).join(a)
            }, tryThese: function () {
                for (var a,
                         b = 0, h = arguments.length; b < h; b++) {
                    var g = arguments[b];
                    try {
                        a = g();
                        break
                    } catch (d) {
                    }
                }
                return a
            }, genKey: function () {
                return Array.prototype.slice.call(arguments).join("-")
            }, defer: function (a) {
                return function () {
                    var b = arguments, h = this;
                    window.setTimeout(function () {
                        a.apply(h, b)
                    }, 0)
                }
            }, normalizeCssText: function (a, b) {
                var h = [], g, d = CKEDITOR.tools.parseCssText(a, true, b);
                for (g in d)h.push(g + ":" + d[g]);
                h.sort();
                return h.length ? h.join(";") + ";" : ""
            }, convertRgbToHex: function (a) {
                return a.replace(/(?:rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\))/gi,
                    function (a, b, g, d) {
                        a = [b, g, d];
                        for (b = 0; b < 3; b++)a[b] = ("0" + parseInt(a[b], 10).toString(16)).slice(-2);
                        return"#" + a.join("")
                    })
            }, parseCssText: function (a, b, h) {
                var g = {};
                if (h) {
                    h = new CKEDITOR.dom.element("span");
                    h.setAttribute("style", a);
                    a = CKEDITOR.tools.convertRgbToHex(h.getAttribute("style") || "")
                }
                if (!a || a == ";")return g;
                a.replace(/&quot;/g, '"').replace(/\s*([^:;\s]+)\s*:\s*([^;]+)\s*(?=;|$)/g, function (a, i, e) {
                    if (b) {
                        i = i.toLowerCase();
                        i == "font-family" && (e = e.toLowerCase().replace(/["']/g, "").replace(/\s*,\s*/g, ","));
                        e = CKEDITOR.tools.trim(e)
                    }
                    g[i] = e
                });
                return g
            }, writeCssText: function (a, b) {
                var h, g = [];
                for (h in a)g.push(h + ":" + a[h]);
                b && g.sort();
                return g.join("; ")
            }, objectCompare: function (a, b, h) {
                var g;
                if (!a && !b)return true;
                if (!a || !b)return false;
                for (g in a)if (a[g] != b[g])return false;
                if (!h)for (g in b)if (a[g] != b[g])return false;
                return true
            }, objectKeys: function (a) {
                var b = [], h;
                for (h in a)b.push(h);
                return b
            }, convertArrayToObject: function (a, b) {
                var h = {};
                arguments.length == 1 && (b = true);
                for (var g = 0, d = a.length; g < d; ++g)h[a[g]] = b;
                return h
            }}
    }(), CKEDITOR.dtd = function () {
        var b = CKEDITOR.tools.extend, c = function (a, b) {
            for (var f = CKEDITOR.tools.clone(a), d = 1; d < arguments.length; d++) {
                var b = arguments[d], e;
                for (e in b)delete f[e]
            }
            return f
        }, a = {}, f = {}, h = {address: 1, article: 1, aside: 1, blockquote: 1, details: 1, div: 1, dl: 1, fieldset: 1, figure: 1, footer: 1, form: 1, h1: 1, h2: 1, h3: 1, h4: 1, h5: 1, h6: 1, header: 1, hgroup: 1, hr: 1, menu: 1, nav: 1, ol: 1, p: 1, pre: 1, section: 1, table: 1, ul: 1}, g = {command: 1, link: 1, meta: 1, noscript: 1, script: 1, style: 1}, d = {}, i = {"#": 1}, e = {center: 1, dir: 1,
            noframes: 1};
        b(a, {a: 1, abbr: 1, area: 1, audio: 1, b: 1, bdi: 1, bdo: 1, br: 1, button: 1, canvas: 1, cite: 1, code: 1, command: 1, datalist: 1, del: 1, dfn: 1, em: 1, embed: 1, i: 1, iframe: 1, img: 1, input: 1, ins: 1, kbd: 1, keygen: 1, label: 1, map: 1, mark: 1, meter: 1, noscript: 1, object: 1, output: 1, progress: 1, q: 1, ruby: 1, s: 1, samp: 1, script: 1, select: 1, small: 1, span: 1, strong: 1, sub: 1, sup: 1, textarea: 1, time: 1, u: 1, "var": 1, video: 1, wbr: 1}, i, {acronym: 1, applet: 1, basefont: 1, big: 1, font: 1, isindex: 1, strike: 1, style: 1, tt: 1});
        b(f, h, a, e);
        c = {a: c(a, {a: 1, button: 1}), abbr: a,
            address: f, area: d, article: b({style: 1}, f), aside: b({style: 1}, f), audio: b({source: 1, track: 1}, f), b: a, base: d, bdi: a, bdo: a, blockquote: f, body: f, br: d, button: c(a, {a: 1, button: 1}), canvas: a, caption: f, cite: a, code: a, col: d, colgroup: {col: 1}, command: d, datalist: b({option: 1}, a), dd: f, del: a, details: b({summary: 1}, f), dfn: a, div: b({style: 1}, f), dl: {dt: 1, dd: 1}, dt: f, em: a, embed: d, fieldset: b({legend: 1}, f), figcaption: f, figure: b({figcaption: 1}, f), footer: f, form: f, h1: a, h2: a, h3: a, h4: a, h5: a, h6: a, head: b({title: 1, base: 1}, g), header: f, hgroup: {h1: 1,
                h2: 1, h3: 1, h4: 1, h5: 1, h6: 1}, hr: d, html: b({head: 1, body: 1}, f, g), i: a, iframe: i, img: d, input: d, ins: a, kbd: a, keygen: d, label: a, legend: a, li: f, link: d, map: f, mark: a, menu: b({li: 1}, f), meta: d, meter: c(a, {meter: 1}), nav: f, noscript: b({link: 1, meta: 1, style: 1}, a), object: b({param: 1}, a), ol: {li: 1}, optgroup: {option: 1}, option: i, output: a, p: a, param: d, pre: a, progress: c(a, {progress: 1}), q: a, rp: a, rt: a, ruby: b({rp: 1, rt: 1}, a), s: a, samp: a, script: i, section: b({style: 1}, f), select: {optgroup: 1, option: 1}, small: a, source: d, span: a, strong: a, style: i,
            sub: a, summary: a, sup: a, table: {caption: 1, colgroup: 1, thead: 1, tfoot: 1, tbody: 1, tr: 1}, tbody: {tr: 1}, td: f, textarea: i, tfoot: {tr: 1}, th: f, thead: {tr: 1}, time: c(a, {time: 1}), title: i, tr: {th: 1, td: 1}, track: d, u: a, ul: {li: 1}, "var": a, video: b({source: 1, track: 1}, f), wbr: d, acronym: a, applet: b({param: 1}, f), basefont: d, big: a, center: f, dialog: d, dir: {li: 1}, font: a, isindex: d, noframes: f, strike: a, tt: a};
        b(c, {$block: b({audio: 1, dd: 1, dt: 1, li: 1, video: 1}, h, e), $blockLimit: {article: 1, aside: 1, audio: 1, body: 1, caption: 1, details: 1, dir: 1, div: 1, dl: 1,
            fieldset: 1, figure: 1, footer: 1, form: 1, header: 1, hgroup: 1, menu: 1, nav: 1, ol: 1, section: 1, table: 1, td: 1, th: 1, tr: 1, ul: 1, video: 1}, $cdata: {script: 1, style: 1}, $editable: {address: 1, article: 1, aside: 1, blockquote: 1, body: 1, details: 1, div: 1, fieldset: 1, footer: 1, form: 1, h1: 1, h2: 1, h3: 1, h4: 1, h5: 1, h6: 1, header: 1, hgroup: 1, nav: 1, p: 1, pre: 1, section: 1}, $empty: {area: 1, base: 1, basefont: 1, br: 1, col: 1, command: 1, dialog: 1, embed: 1, hr: 1, img: 1, input: 1, isindex: 1, keygen: 1, link: 1, meta: 1, param: 1, source: 1, track: 1, wbr: 1}, $inline: a, $list: {dl: 1, ol: 1,
            ul: 1}, $listItem: {dd: 1, dt: 1, li: 1}, $nonBodyContent: b({body: 1, head: 1, html: 1}, c.head), $nonEditable: {applet: 1, audio: 1, button: 1, embed: 1, iframe: 1, map: 1, object: 1, option: 1, param: 1, script: 1, textarea: 1, video: 1}, $object: {applet: 1, audio: 1, button: 1, hr: 1, iframe: 1, img: 1, input: 1, object: 1, select: 1, table: 1, textarea: 1, video: 1}, $removeEmpty: {abbr: 1, acronym: 1, b: 1, bdi: 1, bdo: 1, big: 1, cite: 1, code: 1, del: 1, dfn: 1, em: 1, font: 1, i: 1, ins: 1, label: 1, kbd: 1, mark: 1, meter: 1, output: 1, q: 1, ruby: 1, s: 1, samp: 1, small: 1, span: 1, strike: 1, strong: 1,
            sub: 1, sup: 1, time: 1, tt: 1, u: 1, "var": 1}, $tabIndex: {a: 1, area: 1, button: 1, input: 1, object: 1, select: 1, textarea: 1}, $tableContent: {caption: 1, col: 1, colgroup: 1, tbody: 1, td: 1, tfoot: 1, th: 1, thead: 1, tr: 1}, $transparent: {a: 1, audio: 1, canvas: 1, del: 1, ins: 1, map: 1, noscript: 1, object: 1, video: 1}, $intermediate: {caption: 1, colgroup: 1, dd: 1, dt: 1, figcaption: 1, legend: 1, li: 1, optgroup: 1, option: 1, rp: 1, rt: 1, summary: 1, tbody: 1, td: 1, tfoot: 1, th: 1, thead: 1, tr: 1}});
        return c
    }(), CKEDITOR.dom.event = function (b) {
        this.$ = b
    }, CKEDITOR.dom.event.prototype =
    {getKey: function () {
        return this.$.keyCode || this.$.which
    }, getKeystroke: function () {
        var b = this.getKey();
        if (this.$.ctrlKey || this.$.metaKey)b = b + CKEDITOR.CTRL;
        this.$.shiftKey && (b = b + CKEDITOR.SHIFT);
        this.$.altKey && (b = b + CKEDITOR.ALT);
        return b
    }, preventDefault: function (b) {
        var c = this.$;
        c.preventDefault ? c.preventDefault() : c.returnValue = false;
        b && this.stopPropagation()
    }, stopPropagation: function () {
        var b = this.$;
        b.stopPropagation ? b.stopPropagation() : b.cancelBubble = true
    }, getTarget: function () {
        var b = this.$.target || this.$.srcElement;
        return b ? new CKEDITOR.dom.node(b) : null
    }, getPhase: function () {
        return this.$.eventPhase || 2
    }, getPageOffset: function () {
        var b = this.getTarget().getDocument().$;
        return{x: this.$.pageX || this.$.clientX + (b.documentElement.scrollLeft || b.body.scrollLeft), y: this.$.pageY || this.$.clientY + (b.documentElement.scrollTop || b.body.scrollTop)}
    }}, CKEDITOR.CTRL = 1114112, CKEDITOR.SHIFT = 2228224, CKEDITOR.ALT = 4456448, CKEDITOR.EVENT_PHASE_CAPTURING = 1, CKEDITOR.EVENT_PHASE_AT_TARGET = 2, CKEDITOR.EVENT_PHASE_BUBBLING = 3, CKEDITOR.dom.domObject =
        function (b) {
            if (b)this.$ = b
        }, CKEDITOR.dom.domObject.prototype = function () {
        var b = function (b, a) {
            return function (f) {
                typeof CKEDITOR != "undefined" && b.fire(a, new CKEDITOR.dom.event(f))
            }
        };
        return{getPrivate: function () {
            var b;
            if (!(b = this.getCustomData("_")))this.setCustomData("_", b = {});
            return b
        }, on: function (c) {
            var a = this.getCustomData("_cke_nativeListeners");
            if (!a) {
                a = {};
                this.setCustomData("_cke_nativeListeners", a)
            }
            if (!a[c]) {
                a = a[c] = b(this, c);
                this.$.addEventListener ? this.$.addEventListener(c, a, !!CKEDITOR.event.useCapture) :
                    this.$.attachEvent && this.$.attachEvent("on" + c, a)
            }
            return CKEDITOR.event.prototype.on.apply(this, arguments)
        }, removeListener: function (b) {
            CKEDITOR.event.prototype.removeListener.apply(this, arguments);
            if (!this.hasListeners(b)) {
                var a = this.getCustomData("_cke_nativeListeners"), f = a && a[b];
                if (f) {
                    this.$.removeEventListener ? this.$.removeEventListener(b, f, false) : this.$.detachEvent && this.$.detachEvent("on" + b, f);
                    delete a[b]
                }
            }
        }, removeAllListeners: function () {
            var b = this.getCustomData("_cke_nativeListeners"), a;
            for (a in b) {
                var f =
                    b[a];
                this.$.detachEvent ? this.$.detachEvent("on" + a, f) : this.$.removeEventListener && this.$.removeEventListener(a, f, false);
                delete b[a]
            }
        }}
    }(), function (b) {
        var c = {};
        CKEDITOR.on("reset", function () {
            c = {}
        });
        b.equals = function (a) {
            try {
                return a && a.$ === this.$
            } catch (b) {
                return false
            }
        };
        b.setCustomData = function (a, b) {
            var h = this.getUniqueId();
            (c[h] || (c[h] = {}))[a] = b;
            return this
        };
        b.getCustomData = function (a) {
            var b = this.$["data-cke-expando"];
            return(b = b && c[b]) && a in b ? b[a] : null
        };
        b.removeCustomData = function (a) {
            var b = this.$["data-cke-expando"],
                b = b && c[b], h, g;
            if (b) {
                h = b[a];
                g = a in b;
                delete b[a]
            }
            return g ? h : null
        };
        b.clearCustomData = function () {
            this.removeAllListeners();
            var a = this.$["data-cke-expando"];
            a && delete c[a]
        };
        b.getUniqueId = function () {
            return this.$["data-cke-expando"] || (this.$["data-cke-expando"] = CKEDITOR.tools.getNextNumber())
        };
        CKEDITOR.event.implementOn(b)
    }(CKEDITOR.dom.domObject.prototype), CKEDITOR.dom.node = function (b) {
        return b ? new CKEDITOR.dom[b.nodeType == CKEDITOR.NODE_DOCUMENT ? "document" : b.nodeType == CKEDITOR.NODE_ELEMENT ? "element" :
            b.nodeType == CKEDITOR.NODE_TEXT ? "text" : b.nodeType == CKEDITOR.NODE_COMMENT ? "comment" : b.nodeType == CKEDITOR.NODE_DOCUMENT_FRAGMENT ? "documentFragment" : "domObject"](b) : this
    }, CKEDITOR.dom.node.prototype = new CKEDITOR.dom.domObject, CKEDITOR.NODE_ELEMENT = 1, CKEDITOR.NODE_DOCUMENT = 9, CKEDITOR.NODE_TEXT = 3, CKEDITOR.NODE_COMMENT = 8, CKEDITOR.NODE_DOCUMENT_FRAGMENT = 11, CKEDITOR.POSITION_IDENTICAL = 0, CKEDITOR.POSITION_DISCONNECTED = 1, CKEDITOR.POSITION_FOLLOWING = 2, CKEDITOR.POSITION_PRECEDING = 4, CKEDITOR.POSITION_IS_CONTAINED =
        8, CKEDITOR.POSITION_CONTAINS = 16, CKEDITOR.tools.extend(CKEDITOR.dom.node.prototype, {appendTo: function (b, c) {
        b.append(this, c);
        return b
    }, clone: function (b, c) {
        var a = this.$.cloneNode(b), f = function (a) {
            a["data-cke-expando"] && (a["data-cke-expando"] = false);
            if (a.nodeType == CKEDITOR.NODE_ELEMENT) {
                c || a.removeAttribute("id", false);
                if (b)for (var a = a.childNodes, g = 0; g < a.length; g++)f(a[g])
            }
        };
        f(a);
        return new CKEDITOR.dom.node(a)
    }, hasPrevious: function () {
        return!!this.$.previousSibling
    }, hasNext: function () {
        return!!this.$.nextSibling
    },
        insertAfter: function (b) {
            b.$.parentNode.insertBefore(this.$, b.$.nextSibling);
            return b
        }, insertBefore: function (b) {
            b.$.parentNode.insertBefore(this.$, b.$);
            return b
        }, insertBeforeMe: function (b) {
            this.$.parentNode.insertBefore(b.$, this.$);
            return b
        }, getAddress: function (b) {
            for (var c = [], a = this.getDocument().$.documentElement, f = this.$; f && f != a;) {
                var h = f.parentNode;
                h && c.unshift(this.getIndex.call({$: f}, b));
                f = h
            }
            return c
        }, getDocument: function () {
            return new CKEDITOR.dom.document(this.$.ownerDocument || this.$.parentNode.ownerDocument)
        },
        getIndex: function (b) {
            var c = this.$, a = -1, f;
            if (!this.$.parentNode)return a;
            do if (!b || !(c != this.$ && c.nodeType == CKEDITOR.NODE_TEXT && (f || !c.nodeValue))) {
                a++;
                f = c.nodeType == CKEDITOR.NODE_TEXT
            } while (c = c.previousSibling);
            return a
        }, getNextSourceNode: function (b, c, a) {
            if (a && !a.call)var f = a, a = function (a) {
                return!a.equals(f)
            };
            var b = !b && this.getFirst && this.getFirst(), h;
            if (!b) {
                if (this.type == CKEDITOR.NODE_ELEMENT && a && a(this, true) === false)return null;
                b = this.getNext()
            }
            for (; !b && (h = (h || this).getParent());) {
                if (a && a(h, true) ===
                    false)return null;
                b = h.getNext()
            }
            return!b || a && a(b) === false ? null : c && c != b.type ? b.getNextSourceNode(false, c, a) : b
        }, getPreviousSourceNode: function (b, c, a) {
            if (a && !a.call)var f = a, a = function (a) {
                return!a.equals(f)
            };
            var b = !b && this.getLast && this.getLast(), h;
            if (!b) {
                if (this.type == CKEDITOR.NODE_ELEMENT && a && a(this, true) === false)return null;
                b = this.getPrevious()
            }
            for (; !b && (h = (h || this).getParent());) {
                if (a && a(h, true) === false)return null;
                b = h.getPrevious()
            }
            return!b || a && a(b) === false ? null : c && b.type != c ? b.getPreviousSourceNode(false,
                c, a) : b
        }, getPrevious: function (b) {
            var c = this.$, a;
            do a = (c = c.previousSibling) && c.nodeType != 10 && new CKEDITOR.dom.node(c); while (a && b && !b(a));
            return a
        }, getNext: function (b) {
            var c = this.$, a;
            do a = (c = c.nextSibling) && new CKEDITOR.dom.node(c); while (a && b && !b(a));
            return a
        }, getParent: function (b) {
            var c = this.$.parentNode;
            return c && (c.nodeType == CKEDITOR.NODE_ELEMENT || b && c.nodeType == CKEDITOR.NODE_DOCUMENT_FRAGMENT) ? new CKEDITOR.dom.node(c) : null
        }, getParents: function (b) {
            var c = this, a = [];
            do a[b ? "push" : "unshift"](c); while (c =
                c.getParent());
            return a
        }, getCommonAncestor: function (b) {
            if (b.equals(this))return this;
            if (b.contains && b.contains(this))return b;
            var c = this.contains ? this : this.getParent();
            do if (c.contains(b))return c; while (c = c.getParent());
            return null
        }, getPosition: function (b) {
            var c = this.$, a = b.$;
            if (c.compareDocumentPosition)return c.compareDocumentPosition(a);
            if (c == a)return CKEDITOR.POSITION_IDENTICAL;
            if (this.type == CKEDITOR.NODE_ELEMENT && b.type == CKEDITOR.NODE_ELEMENT) {
                if (c.contains) {
                    if (c.contains(a))return CKEDITOR.POSITION_CONTAINS +
                        CKEDITOR.POSITION_PRECEDING;
                    if (a.contains(c))return CKEDITOR.POSITION_IS_CONTAINED + CKEDITOR.POSITION_FOLLOWING
                }
                if ("sourceIndex"in c)return c.sourceIndex < 0 || a.sourceIndex < 0 ? CKEDITOR.POSITION_DISCONNECTED : c.sourceIndex < a.sourceIndex ? CKEDITOR.POSITION_PRECEDING : CKEDITOR.POSITION_FOLLOWING
            }
            for (var c = this.getAddress(), b = b.getAddress(), a = Math.min(c.length, b.length), f = 0; f <= a - 1; f++)if (c[f] != b[f]) {
                if (f < a)return c[f] < b[f] ? CKEDITOR.POSITION_PRECEDING : CKEDITOR.POSITION_FOLLOWING;
                break
            }
            return c.length < b.length ?
                CKEDITOR.POSITION_CONTAINS + CKEDITOR.POSITION_PRECEDING : CKEDITOR.POSITION_IS_CONTAINED + CKEDITOR.POSITION_FOLLOWING
        }, getAscendant: function (b, c) {
            var a = this.$, f;
            if (!c)a = a.parentNode;
            for (; a;) {
                if (a.nodeName && (f = a.nodeName.toLowerCase(), typeof b == "string" ? f == b : f in b))return new CKEDITOR.dom.node(a);
                try {
                    a = a.parentNode
                } catch (h) {
                    a = null
                }
            }
            return null
        }, hasAscendant: function (b, c) {
            var a = this.$;
            if (!c)a = a.parentNode;
            for (; a;) {
                if (a.nodeName && a.nodeName.toLowerCase() == b)return true;
                a = a.parentNode
            }
            return false
        }, move: function (b, c) {
            b.append(this.remove(), c)
        }, remove: function (b) {
            var c = this.$, a = c.parentNode;
            if (a) {
                if (b)for (; b = c.firstChild;)a.insertBefore(c.removeChild(b), c);
                a.removeChild(c)
            }
            return this
        }, replace: function (b) {
            this.insertBefore(b);
            b.remove()
        }, trim: function () {
            this.ltrim();
            this.rtrim()
        }, ltrim: function () {
            for (var b; this.getFirst && (b = this.getFirst());) {
                if (b.type == CKEDITOR.NODE_TEXT) {
                    var c = CKEDITOR.tools.ltrim(b.getText()), a = b.getLength();
                    if (c) {
                        if (c.length < a) {
                            b.split(a - c.length);
                            this.$.removeChild(this.$.firstChild)
                        }
                    } else {
                        b.remove();
                        continue
                    }
                }
                break
            }
        }, rtrim: function () {
            for (var b; this.getLast && (b = this.getLast());) {
                if (b.type == CKEDITOR.NODE_TEXT) {
                    var c = CKEDITOR.tools.rtrim(b.getText()), a = b.getLength();
                    if (c) {
                        if (c.length < a) {
                            b.split(c.length);
                            this.$.lastChild.parentNode.removeChild(this.$.lastChild)
                        }
                    } else {
                        b.remove();
                        continue
                    }
                }
                break
            }
            if (!CKEDITOR.env.ie && !CKEDITOR.env.opera)(b = this.$.lastChild) && (b.type == 1 && b.nodeName.toLowerCase() == "br") && b.parentNode.removeChild(b)
        }, isReadOnly: function () {
            var b = this;
            this.type != CKEDITOR.NODE_ELEMENT &&
            (b = this.getParent());
            if (b && typeof b.$.isContentEditable != "undefined")return!(b.$.isContentEditable || b.data("cke-editable"));
            for (; b;) {
                if (b.data("cke-editable"))break;
                if (b.getAttribute("contentEditable") == "false")return true;
                if (b.getAttribute("contentEditable") == "true")break;
                b = b.getParent()
            }
            return!b
        }}), CKEDITOR.dom.window = function (b) {
        CKEDITOR.dom.domObject.call(this, b)
    }, CKEDITOR.dom.window.prototype = new CKEDITOR.dom.domObject, CKEDITOR.tools.extend(CKEDITOR.dom.window.prototype, {focus: function () {
        this.$.focus()
    },
        getViewPaneSize: function () {
            var b = this.$.document, c = b.compatMode == "CSS1Compat";
            return{width: (c ? b.documentElement.clientWidth : b.body.clientWidth) || 0, height: (c ? b.documentElement.clientHeight : b.body.clientHeight) || 0}
        }, getScrollPosition: function () {
            var b = this.$;
            if ("pageXOffset"in b)return{x: b.pageXOffset || 0, y: b.pageYOffset || 0};
            b = b.document;
            return{x: b.documentElement.scrollLeft || b.body.scrollLeft || 0, y: b.documentElement.scrollTop || b.body.scrollTop || 0}
        }, getFrame: function () {
            var b = this.$.frameElement;
            return b ?
                new CKEDITOR.dom.element.get(b) : null
        }}), CKEDITOR.dom.document = function (b) {
        CKEDITOR.dom.domObject.call(this, b)
    }, CKEDITOR.dom.document.prototype = new CKEDITOR.dom.domObject, CKEDITOR.tools.extend(CKEDITOR.dom.document.prototype, {type: CKEDITOR.NODE_DOCUMENT, appendStyleSheet: function (b) {
        if (this.$.createStyleSheet)this.$.createStyleSheet(b); else {
            var c = new CKEDITOR.dom.element("link");
            c.setAttributes({rel: "stylesheet", type: "text/css", href: b});
            this.getHead().append(c)
        }
    }, appendStyleText: function (b) {
        if (this.$.createStyleSheet) {
            var c =
                this.$.createStyleSheet("");
            c.cssText = b
        } else {
            var a = new CKEDITOR.dom.element("style", this);
            a.append(new CKEDITOR.dom.text(b, this));
            this.getHead().append(a)
        }
        return c || a.$.sheet
    }, createElement: function (b, c) {
        var a = new CKEDITOR.dom.element(b, this);
        if (c) {
            c.attributes && a.setAttributes(c.attributes);
            c.styles && a.setStyles(c.styles)
        }
        return a
    }, createText: function (b) {
        return new CKEDITOR.dom.text(b, this)
    }, focus: function () {
        this.getWindow().focus()
    }, getActive: function () {
        return new CKEDITOR.dom.element(this.$.activeElement)
    },
        getById: function (b) {
            return(b = this.$.getElementById(b)) ? new CKEDITOR.dom.element(b) : null
        }, getByAddress: function (b, c) {
            for (var a = this.$.documentElement, f = 0; a && f < b.length; f++) {
                var h = b[f];
                if (c)for (var g = -1, d = 0; d < a.childNodes.length; d++) {
                    var i = a.childNodes[d];
                    if (!(c === true && i.nodeType == 3 && i.previousSibling && i.previousSibling.nodeType == 3)) {
                        g++;
                        if (g == h) {
                            a = i;
                            break
                        }
                    }
                } else a = a.childNodes[h]
            }
            return a ? new CKEDITOR.dom.node(a) : null
        }, getElementsByTag: function (b, c) {
            if ((!CKEDITOR.env.ie || document.documentMode > 8) &&
                c)b = c + ":" + b;
            return new CKEDITOR.dom.nodeList(this.$.getElementsByTagName(b))
        }, getHead: function () {
            var b = this.$.getElementsByTagName("head")[0];
            return b = b ? new CKEDITOR.dom.element(b) : this.getDocumentElement().append(new CKEDITOR.dom.element("head"), true)
        }, getBody: function () {
            return new CKEDITOR.dom.element(this.$.body)
        }, getDocumentElement: function () {
            return new CKEDITOR.dom.element(this.$.documentElement)
        }, getWindow: function () {
            var b = new CKEDITOR.dom.window(this.$.parentWindow || this.$.defaultView);
            return(this.getWindow =
                function () {
                    return b
                })()
        }, write: function (b) {
            this.$.open("text/html", "replace");
            CKEDITOR.env.isCustomDomain() && (this.$.domain = document.domain);
            this.$.write(b);
            this.$.close()
        }}), CKEDITOR.dom.nodeList = function (b) {
        this.$ = b
    }, CKEDITOR.dom.nodeList.prototype = {count: function () {
        return this.$.length
    }, getItem: function (b) {
        if (b < 0 || b >= this.$.length)return null;
        return(b = this.$[b]) ? new CKEDITOR.dom.node(b) : null
    }}, CKEDITOR.dom.element = function (b, c) {
        typeof b == "string" && (b = (c ? c.$ : document).createElement(b));
        CKEDITOR.dom.domObject.call(this,
            b)
    }, CKEDITOR.dom.element.get = function (b) {
        return(b = typeof b == "string" ? document.getElementById(b) || document.getElementsByName(b)[0] : b) && (b.$ ? b : new CKEDITOR.dom.element(b))
    }, CKEDITOR.dom.element.prototype = new CKEDITOR.dom.node, CKEDITOR.dom.element.createFromHtml = function (b, c) {
        var a = new CKEDITOR.dom.element("div", c);
        a.setHtml(b);
        return a.getFirst().remove()
    }, CKEDITOR.dom.element.setMarker = function (b, c, a, f) {
        var h = c.getCustomData("list_marker_id") || c.setCustomData("list_marker_id", CKEDITOR.tools.getNextNumber()).getCustomData("list_marker_id"),
            g = c.getCustomData("list_marker_names") || c.setCustomData("list_marker_names", {}).getCustomData("list_marker_names");
        b[h] = c;
        g[a] = 1;
        return c.setCustomData(a, f)
    }, CKEDITOR.dom.element.clearAllMarkers = function (b) {
        for (var c in b)CKEDITOR.dom.element.clearMarkers(b, b[c], 1)
    }, CKEDITOR.dom.element.clearMarkers = function (b, c, a) {
        var f = c.getCustomData("list_marker_names"), h = c.getCustomData("list_marker_id"), g;
        for (g in f)c.removeCustomData(g);
        c.removeCustomData("list_marker_names");
        if (a) {
            c.removeCustomData("list_marker_id");
            delete b[h]
        }
    }, function () {
        function b(a) {
            for (var b = 0, h = 0, g = c[a].length; h < g; h++)b = b + (parseInt(this.getComputedStyle(c[a][h]) || 0, 10) || 0);
            return b
        }

        CKEDITOR.tools.extend(CKEDITOR.dom.element.prototype, {type: CKEDITOR.NODE_ELEMENT, addClass: function (a) {
            var b = this.$.className;
            b && (RegExp("(?:^|\\s)" + a + "(?:\\s|$)", "").test(b) || (b = b + (" " + a)));
            this.$.className = b || a
        }, removeClass: function (a) {
            var b = this.getAttribute("class");
            if (b) {
                a = RegExp("(?:^|\\s+)" + a + "(?=\\s|$)", "i");
                if (a.test(b))(b = b.replace(a, "").replace(/^\s+/,
                    "")) ? this.setAttribute("class", b) : this.removeAttribute("class")
            }
            return this
        }, hasClass: function (a) {
            return RegExp("(?:^|\\s+)" + a + "(?=\\s|$)", "").test(this.getAttribute("class"))
        }, append: function (a, b) {
            typeof a == "string" && (a = this.getDocument().createElement(a));
            b ? this.$.insertBefore(a.$, this.$.firstChild) : this.$.appendChild(a.$);
            return a
        }, appendHtml: function (a) {
            if (this.$.childNodes.length) {
                var b = new CKEDITOR.dom.element("div", this.getDocument());
                b.setHtml(a);
                b.moveChildren(this)
            } else this.setHtml(a)
        },
            appendText: function (a) {
                this.$.text != void 0 ? this.$.text = this.$.text + a : this.append(new CKEDITOR.dom.text(a))
            }, appendBogus: function () {
                for (var a = this.getLast(); a && a.type == CKEDITOR.NODE_TEXT && !CKEDITOR.tools.rtrim(a.getText());)a = a.getPrevious();
                if (!a || !a.is || !a.is("br")) {
                    a = CKEDITOR.env.opera ? this.getDocument().createText("") : this.getDocument().createElement("br");
                    CKEDITOR.env.gecko && a.setAttribute("type", "_moz");
                    this.append(a)
                }
            }, breakParent: function (a) {
                var b = new CKEDITOR.dom.range(this.getDocument());
                b.setStartAfter(this);
                b.setEndAfter(a);
                a = b.extractContents();
                b.insertNode(this.remove());
                a.insertAfterNode(this)
            }, contains: CKEDITOR.env.ie || CKEDITOR.env.webkit ? function (a) {
                var b = this.$;
                return a.type != CKEDITOR.NODE_ELEMENT ? b.contains(a.getParent().$) : b != a.$ && b.contains(a.$)
            } : function (a) {
                return!!(this.$.compareDocumentPosition(a.$) & 16)
            }, focus: function () {
                function a() {
                    try {
                        this.$.focus()
                    } catch (a) {
                    }
                }

                return function (b) {
                    b ? CKEDITOR.tools.setTimeout(a, 100, this) : a.call(this)
                }
            }(), getHtml: function () {
                var a = this.$.innerHTML;
                return CKEDITOR.env.ie ? a.replace(/<\?[^>]*>/g, "") : a
            }, getOuterHtml: function () {
                if (this.$.outerHTML)return this.$.outerHTML.replace(/<\?[^>]*>/, "");
                var a = this.$.ownerDocument.createElement("div");
                a.appendChild(this.$.cloneNode(true));
                return a.innerHTML
            }, getClientRect: function () {
                var a = CKEDITOR.tools.extend({}, this.$.getBoundingClientRect());
                !a.width && (a.width = a.right - a.left);
                !a.height && (a.height = a.bottom - a.top);
                return a
            }, setHtml: function () {
                var a = function (a) {
                    return this.$.innerHTML = a
                };
                return CKEDITOR.env.ie &&
                    CKEDITOR.env.version < 9 ? function (a) {
                    try {
                        return this.$.innerHTML = a
                    } catch (b) {
                        this.$.innerHTML = "";
                        var g = new CKEDITOR.dom.element("body", this.getDocument());
                        g.$.innerHTML = a;
                        for (g = g.getChildren(); g.count();)this.append(g.getItem(0));
                        return a
                    }
                } : a
            }(), setText: function (a) {
                CKEDITOR.dom.element.prototype.setText = this.$.innerText != void 0 ? function (a) {
                    return this.$.innerText = a
                } : function (a) {
                    return this.$.textContent = a
                };
                return this.setText(a)
            }, getAttribute: function () {
                var a = function (a) {
                    return this.$.getAttribute(a,
                        2)
                };
                return CKEDITOR.env.ie && (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat) ? function (a) {
                    switch (a) {
                        case "class":
                            a = "className";
                            break;
                        case "http-equiv":
                            a = "httpEquiv";
                            break;
                        case "name":
                            return this.$.name;
                        case "tabindex":
                            a = this.$.getAttribute(a, 2);
                            a !== 0 && this.$.tabIndex === 0 && (a = null);
                            return a;
                        case "checked":
                            a = this.$.attributes.getNamedItem(a);
                            return(a.specified ? a.nodeValue : this.$.checked) ? "checked" : null;
                        case "hspace":
                        case "value":
                            return this.$[a];
                        case "style":
                            return this.$.style.cssText;
                        case "contenteditable":
                        case "contentEditable":
                            return this.$.attributes.getNamedItem("contentEditable").specified ?
                                this.$.getAttribute("contentEditable") : null
                    }
                    return this.$.getAttribute(a, 2)
                } : a
            }(), getChildren: function () {
                return new CKEDITOR.dom.nodeList(this.$.childNodes)
            }, getComputedStyle: CKEDITOR.env.ie ? function (a) {
                return this.$.currentStyle[CKEDITOR.tools.cssStyleToDomStyle(a)]
            } : function (a) {
                var b = this.getWindow().$.getComputedStyle(this.$, null);
                return b ? b.getPropertyValue(a) : ""
            }, getDtd: function () {
                var a = CKEDITOR.dtd[this.getName()];
                this.getDtd = function () {
                    return a
                };
                return a
            }, getElementsByTag: CKEDITOR.dom.document.prototype.getElementsByTag,
            getTabIndex: CKEDITOR.env.ie ? function () {
                var a = this.$.tabIndex;
                a === 0 && (!CKEDITOR.dtd.$tabIndex[this.getName()] && parseInt(this.getAttribute("tabindex"), 10) !== 0) && (a = -1);
                return a
            } : CKEDITOR.env.webkit ? function () {
                var a = this.$.tabIndex;
                if (a == void 0) {
                    a = parseInt(this.getAttribute("tabindex"), 10);
                    isNaN(a) && (a = -1)
                }
                return a
            } : function () {
                return this.$.tabIndex
            }, getText: function () {
                return this.$.textContent || this.$.innerText || ""
            }, getWindow: function () {
                return this.getDocument().getWindow()
            }, getId: function () {
                return this.$.id ||
                    null
            }, getNameAtt: function () {
                return this.$.name || null
            }, getName: function () {
                var a = this.$.nodeName.toLowerCase();
                if (CKEDITOR.env.ie && !(document.documentMode > 8)) {
                    var b = this.$.scopeName;
                    b != "HTML" && (a = b.toLowerCase() + ":" + a)
                }
                return(this.getName = function () {
                    return a
                })()
            }, getValue: function () {
                return this.$.value
            }, getFirst: function (a) {
                var b = this.$.firstChild;
                (b = b && new CKEDITOR.dom.node(b)) && (a && !a(b)) && (b = b.getNext(a));
                return b
            }, getLast: function (a) {
                var b = this.$.lastChild;
                (b = b && new CKEDITOR.dom.node(b)) && (a && !a(b)) &&
                (b = b.getPrevious(a));
                return b
            }, getStyle: function (a) {
                return this.$.style[CKEDITOR.tools.cssStyleToDomStyle(a)]
            }, is: function () {
                var a = this.getName();
                if (typeof arguments[0] == "object")return!!arguments[0][a];
                for (var b = 0; b < arguments.length; b++)if (arguments[b] == a)return true;
                return false
            }, isEditable: function (a) {
                var b = this.getName();
                if (this.isReadOnly() || this.getComputedStyle("display") == "none" || this.getComputedStyle("visibility") == "hidden" || CKEDITOR.dtd.$nonEditable[b] || CKEDITOR.dtd.$empty[b] || this.is("a") &&
                    (this.data("cke-saved-name") || this.hasAttribute("name")) && !this.getChildCount())return false;
                if (a !== false) {
                    a = CKEDITOR.dtd[b] || CKEDITOR.dtd.span;
                    return!(!a || !a["#"])
                }
                return true
            }, isIdentical: function (a) {
                var b = this.clone(0, 1), a = a.clone(0, 1);
                b.removeAttributes(["_moz_dirty", "data-cke-expando", "data-cke-saved-href", "data-cke-saved-name"]);
                a.removeAttributes(["_moz_dirty", "data-cke-expando", "data-cke-saved-href", "data-cke-saved-name"]);
                if (b.$.isEqualNode) {
                    b.$.style.cssText = CKEDITOR.tools.normalizeCssText(b.$.style.cssText);
                    a.$.style.cssText = CKEDITOR.tools.normalizeCssText(a.$.style.cssText);
                    return b.$.isEqualNode(a.$)
                }
                b = b.getOuterHtml();
                a = a.getOuterHtml();
                if (CKEDITOR.env.ie && CKEDITOR.env.version < 9 && this.is("a")) {
                    var h = this.getParent();
                    if (h.type == CKEDITOR.NODE_ELEMENT) {
                        h = h.clone();
                        h.setHtml(b);
                        b = h.getHtml();
                        h.setHtml(a);
                        a = h.getHtml()
                    }
                }
                return b == a
            }, isVisible: function () {
                var a = (this.$.offsetHeight || this.$.offsetWidth) && this.getComputedStyle("visibility") != "hidden", b, h;
                if (a && (CKEDITOR.env.webkit || CKEDITOR.env.opera)) {
                    b =
                        this.getWindow();
                    if (!b.equals(CKEDITOR.document.getWindow()) && (h = b.$.frameElement))a = (new CKEDITOR.dom.element(h)).isVisible()
                }
                return!!a
            }, isEmptyInlineRemoveable: function () {
                if (!CKEDITOR.dtd.$removeEmpty[this.getName()])return false;
                for (var a = this.getChildren(), b = 0, h = a.count(); b < h; b++) {
                    var g = a.getItem(b);
                    if (!(g.type == CKEDITOR.NODE_ELEMENT && g.data("cke-bookmark")) && (g.type == CKEDITOR.NODE_ELEMENT && !g.isEmptyInlineRemoveable() || g.type == CKEDITOR.NODE_TEXT && CKEDITOR.tools.trim(g.getText())))return false
                }
                return true
            },
            hasAttributes: CKEDITOR.env.ie && (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat) ? function () {
                for (var a = this.$.attributes, b = 0; b < a.length; b++) {
                    var h = a[b];
                    switch (h.nodeName) {
                        case "class":
                            if (this.getAttribute("class"))return true;
                        case "data-cke-expando":
                            continue;
                        default:
                            if (h.specified)return true
                    }
                }
                return false
            } : function () {
                var a = this.$.attributes, b = a.length, h = {"data-cke-expando": 1, _moz_dirty: 1};
                return b > 0 && (b > 2 || !h[a[0].nodeName] || b == 2 && !h[a[1].nodeName])
            }, hasAttribute: function () {
                function a(a) {
                    a = this.$.attributes.getNamedItem(a);
                    return!(!a || !a.specified)
                }

                return CKEDITOR.env.ie && CKEDITOR.env.version < 8 ? function (b) {
                    return b == "name" ? !!this.$.name : a.call(this, b)
                } : a
            }(), hide: function () {
                this.setStyle("display", "none")
            }, moveChildren: function (a, b) {
                var h = this.$, a = a.$;
                if (h != a) {
                    var g;
                    if (b)for (; g = h.lastChild;)a.insertBefore(h.removeChild(g), a.firstChild); else for (; g = h.firstChild;)a.appendChild(h.removeChild(g))
                }
            }, mergeSiblings: function () {
                function a(a, b, g) {
                    if (b && b.type == CKEDITOR.NODE_ELEMENT) {
                        for (var d = []; b.data("cke-bookmark") || b.isEmptyInlineRemoveable();) {
                            d.push(b);
                            b = g ? b.getNext() : b.getPrevious();
                            if (!b || b.type != CKEDITOR.NODE_ELEMENT)return
                        }
                        if (a.isIdentical(b)) {
                            for (var i = g ? a.getLast() : a.getFirst(); d.length;)d.shift().move(a, !g);
                            b.moveChildren(a, !g);
                            b.remove();
                            i && i.type == CKEDITOR.NODE_ELEMENT && i.mergeSiblings()
                        }
                    }
                }

                return function (b) {
                    if (b === false || CKEDITOR.dtd.$removeEmpty[this.getName()] || this.is("a")) {
                        a(this, this.getNext(), true);
                        a(this, this.getPrevious())
                    }
                }
            }(), show: function () {
                this.setStyles({display: "", visibility: ""})
            }, setAttribute: function () {
                var a = function (a, b) {
                    this.$.setAttribute(a, b);
                    return this
                };
                return CKEDITOR.env.ie && (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat) ? function (b, h) {
                    b == "class" ? this.$.className = h : b == "style" ? this.$.style.cssText = h : b == "tabindex" ? this.$.tabIndex = h : b == "checked" ? this.$.checked = h : b == "contenteditable" ? a.call(this, "contentEditable", h) : a.apply(this, arguments);
                    return this
                } : CKEDITOR.env.ie8Compat && CKEDITOR.env.secure ? function (b, h) {
                    if (b == "src" && h.match(/^http:\/\//))try {
                        a.apply(this, arguments)
                    } catch (g) {
                    } else a.apply(this, arguments);
                    return this
                } : a
            }(), setAttributes: function (a) {
                for (var b in a)this.setAttribute(b, a[b]);
                return this
            }, setValue: function (a) {
                this.$.value = a;
                return this
            }, removeAttribute: function () {
                var a = function (a) {
                    this.$.removeAttribute(a)
                };
                return CKEDITOR.env.ie && (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat) ? function (a) {
                    a == "class" ? a = "className" : a == "tabindex" ? a = "tabIndex" : a == "contenteditable" && (a = "contentEditable");
                    this.$.removeAttribute(a)
                } : a
            }(), removeAttributes: function (a) {
                if (CKEDITOR.tools.isArray(a))for (var b = 0; b <
                    a.length; b++)this.removeAttribute(a[b]); else for (b in a)a.hasOwnProperty(b) && this.removeAttribute(b)
            }, removeStyle: function (a) {
                var b = this.$.style;
                if (!b.removeProperty && (a == "border" || a == "margin" || a == "padding")) {
                    var h = ["top", "left", "right", "bottom"], g;
                    a == "border" && (g = ["color", "style", "width"]);
                    for (var b = [], d = 0; d < h.length; d++)if (g)for (var i = 0; i < g.length; i++)b.push([a, h[d], g[i]].join("-")); else b.push([a, h[d]].join("-"));
                    for (a = 0; a < b.length; a++)this.removeStyle(b[a])
                } else {
                    b.removeProperty ? b.removeProperty(a) :
                        b.removeAttribute(CKEDITOR.tools.cssStyleToDomStyle(a));
                    this.$.style.cssText || this.removeAttribute("style")
                }
            }, setStyle: function (a, b) {
                this.$.style[CKEDITOR.tools.cssStyleToDomStyle(a)] = b;
                return this
            }, setStyles: function (a) {
                for (var b in a)this.setStyle(b, a[b]);
                return this
            }, setOpacity: function (a) {
                if (CKEDITOR.env.ie && CKEDITOR.env.version < 9) {
                    a = Math.round(a * 100);
                    this.setStyle("filter", a >= 100 ? "" : "progid:DXImageTransform.Microsoft.Alpha(opacity=" + a + ")")
                } else this.setStyle("opacity", a)
            }, unselectable: function () {
                this.setStyles(CKEDITOR.tools.cssVendorPrefix("user-select",
                    "none"));
                if (CKEDITOR.env.ie || CKEDITOR.env.opera) {
                    this.setAttribute("unselectable", "on");
                    for (var a, b = this.getElementsByTag("*"), h = 0, g = b.count(); h < g; h++) {
                        a = b.getItem(h);
                        a.setAttribute("unselectable", "on")
                    }
                }
            }, getPositionedAncestor: function () {
                for (var a = this; a.getName() != "html";) {
                    if (a.getComputedStyle("position") != "static")return a;
                    a = a.getParent()
                }
                return null
            }, getDocumentPosition: function (a) {
                var b = 0, h = 0, g = this.getDocument(), d = g.getBody(), i = g.$.compatMode == "BackCompat";
                if (document.documentElement.getBoundingClientRect) {
                    var e =
                        this.$.getBoundingClientRect(), c = g.$.documentElement, k = c.clientTop || d.$.clientTop || 0, m = c.clientLeft || d.$.clientLeft || 0, n = true;
                    if (CKEDITOR.env.ie) {
                        n = g.getDocumentElement().contains(this);
                        g = g.getBody().contains(this);
                        n = i && g || !i && n
                    }
                    if (n) {
                        b = e.left + (!i && c.scrollLeft || d.$.scrollLeft);
                        b = b - m;
                        h = e.top + (!i && c.scrollTop || d.$.scrollTop);
                        h = h - k
                    }
                } else {
                    d = this;
                    for (g = null; d && !(d.getName() == "body" || d.getName() == "html");) {
                        b = b + (d.$.offsetLeft - d.$.scrollLeft);
                        h = h + (d.$.offsetTop - d.$.scrollTop);
                        if (!d.equals(this)) {
                            b = b + (d.$.clientLeft ||
                                0);
                            h = h + (d.$.clientTop || 0)
                        }
                        for (; g && !g.equals(d);) {
                            b = b - g.$.scrollLeft;
                            h = h - g.$.scrollTop;
                            g = g.getParent()
                        }
                        g = d;
                        d = (e = d.$.offsetParent) ? new CKEDITOR.dom.element(e) : null
                    }
                }
                if (a) {
                    d = this.getWindow();
                    g = a.getWindow();
                    if (!d.equals(g) && d.$.frameElement) {
                        a = (new CKEDITOR.dom.element(d.$.frameElement)).getDocumentPosition(a);
                        b = b + a.x;
                        h = h + a.y
                    }
                }
                if (!document.documentElement.getBoundingClientRect && CKEDITOR.env.gecko && !i) {
                    b = b + (this.$.clientLeft ? 1 : 0);
                    h = h + (this.$.clientTop ? 1 : 0)
                }
                return{x: b, y: h}
            }, scrollIntoView: function (a) {
                var b =
                    this.getParent();
                if (b) {
                    do {
                        (b.$.clientWidth && b.$.clientWidth < b.$.scrollWidth || b.$.clientHeight && b.$.clientHeight < b.$.scrollHeight) && !b.is("body") && this.scrollIntoParent(b, a, 1);
                        if (b.is("html")) {
                            var c = b.getWindow();
                            try {
                                var g = c.$.frameElement;
                                g && (b = new CKEDITOR.dom.element(g))
                            } catch (d) {
                            }
                        }
                    } while (b = b.getParent())
                }
            }, scrollIntoParent: function (a, b, c) {
                var g, d, i, e;

                function j(b, d) {
                    if (/body|html/.test(a.getName()))a.getWindow().$.scrollBy(b, d); else {
                        a.$.scrollLeft = a.$.scrollLeft + b;
                        a.$.scrollTop = a.$.scrollTop + d
                    }
                }

                function k(a, b) {
                    var d = {x: 0, y: 0};
                    if (!a.is(n ? "body" : "html")) {
                        var e = a.$.getBoundingClientRect();
                        d.x = e.left;
                        d.y = e.top
                    }
                    e = a.getWindow();
                    if (!e.equals(b)) {
                        e = k(CKEDITOR.dom.element.get(e.$.frameElement), b);
                        d.x = d.x + e.x;
                        d.y = d.y + e.y
                    }
                    return d
                }

                function m(a, b) {
                    return parseInt(a.getComputedStyle("margin-" + b) || 0, 10) || 0
                }

                !a && (a = this.getWindow());
                i = a.getDocument();
                var n = i.$.compatMode == "BackCompat";
                a instanceof CKEDITOR.dom.window && (a = n ? i.getBody() : i.getDocumentElement());
                i = a.getWindow();
                d = k(this, i);
                var l = k(a, i), o = this.$.offsetHeight;
                g = this.$.offsetWidth;
                var q = a.$.clientHeight, s = a.$.clientWidth;
                i = d.x - m(this, "left") - l.x || 0;
                e = d.y - m(this, "top") - l.y || 0;
                g = d.x + g + m(this, "right") - (l.x + s) || 0;
                d = d.y + o + m(this, "bottom") - (l.y + q) || 0;
                if (e < 0 || d > 0)j(0, b === true ? e : b === false ? d : e < 0 ? e : d);
                if (c && (i < 0 || g > 0))j(i < 0 ? i : g, 0)
            }, setState: function (a, b, c) {
                b = b || "cke";
                switch (a) {
                    case CKEDITOR.TRISTATE_ON:
                        this.addClass(b + "_on");
                        this.removeClass(b + "_off");
                        this.removeClass(b + "_disabled");
                        c && this.setAttribute("aria-pressed", true);
                        c && this.removeAttribute("aria-disabled");
                        break;
                    case CKEDITOR.TRISTATE_DISABLED:
                        this.addClass(b + "_disabled");
                        this.removeClass(b + "_off");
                        this.removeClass(b + "_on");
                        c && this.setAttribute("aria-disabled", true);
                        c && this.removeAttribute("aria-pressed");
                        break;
                    default:
                        this.addClass(b + "_off");
                        this.removeClass(b + "_on");
                        this.removeClass(b + "_disabled");
                        c && this.removeAttribute("aria-pressed");
                        c && this.removeAttribute("aria-disabled")
                }
            }, getFrameDocument: function () {
                var a = this.$;
                try {
                    a.contentWindow.document
                } catch (b) {
                    a.src = a.src
                }
                return a && new CKEDITOR.dom.document(a.contentWindow.document)
            },
            copyAttributes: function (a, b) {
                for (var c = this.$.attributes, b = b || {}, g = 0; g < c.length; g++) {
                    var d = c[g], i = d.nodeName.toLowerCase(), e;
                    if (!(i in b))if (i == "checked" && (e = this.getAttribute(i)))a.setAttribute(i, e); else if (d.specified || CKEDITOR.env.ie && d.nodeValue && i == "value") {
                        e = this.getAttribute(i);
                        if (e === null)e = d.nodeValue;
                        a.setAttribute(i, e)
                    }
                }
                if (this.$.style.cssText !== "")a.$.style.cssText = this.$.style.cssText
            }, renameNode: function (a) {
                if (this.getName() != a) {
                    var b = this.getDocument(), a = new CKEDITOR.dom.element(a,
                        b);
                    this.copyAttributes(a);
                    this.moveChildren(a);
                    this.getParent() && this.$.parentNode.replaceChild(a.$, this.$);
                    a.$["data-cke-expando"] = this.$["data-cke-expando"];
                    this.$ = a.$
                }
            }, getChild: function () {
                function a(a, b) {
                    var g = a.childNodes;
                    if (b >= 0 && b < g.length)return g[b]
                }

                return function (b) {
                    var c = this.$;
                    if (b.slice)for (; b.length > 0 && c;)c = a(c, b.shift()); else c = a(c, b);
                    return c ? new CKEDITOR.dom.node(c) : null
                }
            }(), getChildCount: function () {
                return this.$.childNodes.length
            }, disableContextMenu: function () {
                this.on("contextmenu",
                    function (a) {
                        a.data.getTarget().hasClass("cke_enable_context_menu") || a.data.preventDefault()
                    })
            }, getDirection: function (a) {
                return a ? this.getComputedStyle("direction") || this.getDirection() || this.getParent() && this.getParent().getDirection(1) || this.getDocument().$.dir || "ltr" : this.getStyle("direction") || this.getAttribute("dir")
            }, data: function (a, b) {
                a = "data-" + a;
                if (b === void 0)return this.getAttribute(a);
                b === false ? this.removeAttribute(a) : this.setAttribute(a, b);
                return null
            }, getEditor: function () {
                var a = CKEDITOR.instances,
                    b, c;
                for (b in a) {
                    c = a[b];
                    if (c.element.equals(this) && c.elementMode != CKEDITOR.ELEMENT_MODE_APPENDTO)return c
                }
                return null
            }});
        var c = {width: ["border-left-width", "border-right-width", "padding-left", "padding-right"], height: ["border-top-width", "border-bottom-width", "padding-top", "padding-bottom"]};
        CKEDITOR.dom.element.prototype.setSize = function (a, f, c) {
            if (typeof f == "number") {
                if (c && (!CKEDITOR.env.ie || !CKEDITOR.env.quirks))f = f - b.call(this, a);
                this.setStyle(a, f + "px")
            }
        };
        CKEDITOR.dom.element.prototype.getSize = function (a, f) {
            var c = Math.max(this.$["offset" + CKEDITOR.tools.capitalize(a)], this.$["client" + CKEDITOR.tools.capitalize(a)]) || 0;
            f && (c = c - b.call(this, a));
            return c
        }
    }(), CKEDITOR.dom.documentFragment = function (b) {
        b = b || CKEDITOR.document;
        this.$ = b.type == CKEDITOR.NODE_DOCUMENT ? b.$.createDocumentFragment() : b
    }, CKEDITOR.tools.extend(CKEDITOR.dom.documentFragment.prototype, CKEDITOR.dom.element.prototype, {type: CKEDITOR.NODE_DOCUMENT_FRAGMENT, insertAfterNode: function (b) {
            b = b.$;
            b.parentNode.insertBefore(this.$, b.nextSibling)
        }},
        !0, {append: 1, appendBogus: 1, getFirst: 1, getLast: 1, getParent: 1, getNext: 1, getPrevious: 1, appendTo: 1, moveChildren: 1, insertBefore: 1, insertAfterNode: 1, replace: 1, trim: 1, type: 1, ltrim: 1, rtrim: 1, getDocument: 1, getChildCount: 1, getChild: 1, getChildren: 1}), function () {
        function b(a, b) {
            var e = this.range;
            if (this._.end)return null;
            if (!this._.start) {
                this._.start = 1;
                if (e.collapsed) {
                    this.end();
                    return null
                }
                e.optimize()
            }
            var g, f = e.startContainer;
            g = e.endContainer;
            var c = e.startOffset, h = e.endOffset, l, o = this.guard, q = this.type, s = a ?
                "getPreviousSourceNode" : "getNextSourceNode";
            if (!a && !this._.guardLTR) {
                var p = g.type == CKEDITOR.NODE_ELEMENT ? g : g.getParent(), t = g.type == CKEDITOR.NODE_ELEMENT ? g.getChild(h) : g.getNext();
                this._.guardLTR = function (a, b) {
                    return(!b || !p.equals(a)) && (!t || !a.equals(t)) && (a.type != CKEDITOR.NODE_ELEMENT || !b || !a.equals(e.root))
                }
            }
            if (a && !this._.guardRTL) {
                var z = f.type == CKEDITOR.NODE_ELEMENT ? f : f.getParent(), x = f.type == CKEDITOR.NODE_ELEMENT ? c ? f.getChild(c - 1) : null : f.getPrevious();
                this._.guardRTL = function (a, b) {
                    return(!b || !z.equals(a)) &&
                        (!x || !a.equals(x)) && (a.type != CKEDITOR.NODE_ELEMENT || !b || !a.equals(e.root))
                }
            }
            var w = a ? this._.guardRTL : this._.guardLTR;
            l = o ? function (a, b) {
                return w(a, b) === false ? false : o(a, b)
            } : w;
            if (this.current)g = this.current[s](false, q, l); else {
                if (a)g.type == CKEDITOR.NODE_ELEMENT && (g = h > 0 ? g.getChild(h - 1) : l(g, true) === false ? null : g.getPreviousSourceNode(true, q, l)); else {
                    g = f;
                    if (g.type == CKEDITOR.NODE_ELEMENT && !(g = g.getChild(c)))g = l(f, true) === false ? null : f.getNextSourceNode(true, q, l)
                }
                g && l(g) === false && (g = null)
            }
            for (; g && !this._.end;) {
                this.current =
                    g;
                if (!this.evaluator || this.evaluator(g) !== false) {
                    if (!b)return g
                } else if (b && this.evaluator)return false;
                g = g[s](false, q, l)
            }
            this.end();
            return this.current = null
        }

        function c(a) {
            for (var g, e = null; g = b.call(this, a);)e = g;
            return e
        }

        CKEDITOR.dom.walker = CKEDITOR.tools.createClass({$: function (a) {
            this.range = a;
            this._ = {}
        }, proto: {end: function () {
            this._.end = 1
        }, next: function () {
            return b.call(this)
        }, previous: function () {
            return b.call(this, 1)
        }, checkForward: function () {
            return b.call(this, 0, 1) !== false
        }, checkBackward: function () {
            return b.call(this,
                1, 1) !== false
        }, lastForward: function () {
            return c.call(this)
        }, lastBackward: function () {
            return c.call(this, 1)
        }, reset: function () {
            delete this.current;
            this._ = {}
        }}});
        var a = {block: 1, "list-item": 1, table: 1, "table-row-group": 1, "table-header-group": 1, "table-footer-group": 1, "table-row": 1, "table-column-group": 1, "table-column": 1, "table-cell": 1, "table-caption": 1};
        CKEDITOR.dom.element.prototype.isBlockBoundary = function (b) {
            b = b ? CKEDITOR.tools.extend({}, CKEDITOR.dtd.$block, b || {}) : CKEDITOR.dtd.$block;
            return this.getComputedStyle("float") ==
                "none" && a[this.getComputedStyle("display")] || b[this.getName()]
        };
        CKEDITOR.dom.walker.blockBoundary = function (a) {
            return function (b) {
                return!(b.type == CKEDITOR.NODE_ELEMENT && b.isBlockBoundary(a))
            }
        };
        CKEDITOR.dom.walker.listItemBoundary = function () {
            return this.blockBoundary({br: 1})
        };
        CKEDITOR.dom.walker.bookmark = function (a, b) {
            function e(a) {
                return a && a.getName && a.getName() == "span" && a.data("cke-bookmark")
            }

            return function (g) {
                var f, c;
                f = g && g.type != CKEDITOR.NODE_ELEMENT && (c = g.getParent()) && e(c);
                f = a ? f : f || e(g);
                return!!(b ^
                    f)
            }
        };
        CKEDITOR.dom.walker.whitespaces = function (a) {
            return function (b) {
                var e;
                b && b.type == CKEDITOR.NODE_TEXT && (e = !CKEDITOR.tools.trim(b.getText()) || CKEDITOR.env.webkit && b.getText() == "​");
                return!!(a ^ e)
            }
        };
        CKEDITOR.dom.walker.invisible = function (a) {
            var b = CKEDITOR.dom.walker.whitespaces();
            return function (e) {
                if (b(e))e = 1; else {
                    e.type == CKEDITOR.NODE_TEXT && (e = e.getParent());
                    e = !e.$.offsetHeight
                }
                return!!(a ^ e)
            }
        };
        CKEDITOR.dom.walker.nodeType = function (a, b) {
            return function (e) {
                return!!(b ^ e.type == a)
            }
        };
        CKEDITOR.dom.walker.bogus =
            function (a) {
                function b(a) {
                    return!h(a) && !g(a)
                }

                return function (e) {
                    var g = !CKEDITOR.env.ie ? e.is && e.is("br") : e.getText && f.test(e.getText());
                    if (g) {
                        g = e.getParent();
                        e = e.getNext(b);
                        g = g.isBlockBoundary() && (!e || e.type == CKEDITOR.NODE_ELEMENT && e.isBlockBoundary())
                    }
                    return!!(a ^ g)
                }
            };
        var f = /^[\t\r\n ]*(?:&nbsp;|\xa0)$/, h = CKEDITOR.dom.walker.whitespaces(), g = CKEDITOR.dom.walker.bookmark();
        CKEDITOR.dom.element.prototype.getBogus = function () {
            var a = this;
            do a = a.getPreviousSourceNode(); while (g(a) || h(a) || a.type == CKEDITOR.NODE_ELEMENT &&
                a.getName()in CKEDITOR.dtd.$inline && !(a.getName()in CKEDITOR.dtd.$empty));
            return a && (!CKEDITOR.env.ie ? a.is && a.is("br") : a.getText && f.test(a.getText())) ? a : false
        }
    }(), CKEDITOR.dom.range = function (b) {
        this.endOffset = this.endContainer = this.startOffset = this.startContainer = null;
        this.collapsed = true;
        var c = b instanceof CKEDITOR.dom.document;
        this.document = c ? b : b.getDocument();
        this.root = c ? b.getBody() : b
    }, function () {
        function b() {
            var a = false, b = CKEDITOR.dom.walker.whitespaces(), d = CKEDITOR.dom.walker.bookmark(true), e =
                CKEDITOR.dom.walker.bogus();
            return function (f) {
                if (d(f) || b(f))return true;
                if (e(f) && !a)return a = true;
                return f.type == CKEDITOR.NODE_TEXT && (f.hasAscendant("pre") || CKEDITOR.tools.trim(f.getText()).length) || f.type == CKEDITOR.NODE_ELEMENT && !f.is(g) ? false : true
            }
        }

        function c(a) {
            var b = CKEDITOR.dom.walker.whitespaces(), e = CKEDITOR.dom.walker.bookmark(1);
            return function (g) {
                return e(g) || b(g) ? true : !a && d(g) || g.type == CKEDITOR.NODE_ELEMENT && g.is(CKEDITOR.dtd.$removeEmpty)
            }
        }

        function a(a) {
            return!i(a) && !e(a)
        }

        var f = function (a) {
            a.collapsed =
                a.startContainer && a.endContainer && a.startContainer.equals(a.endContainer) && a.startOffset == a.endOffset
        }, h = function (a, b, d, e) {
            a.optimizeBookmark();
            var g = a.startContainer, f = a.endContainer, i = a.startOffset, c = a.endOffset, h, j;
            if (f.type == CKEDITOR.NODE_TEXT)f = f.split(c); else if (f.getChildCount() > 0)if (c >= f.getChildCount()) {
                f = f.append(a.document.createText(""));
                j = true
            } else f = f.getChild(c);
            if (g.type == CKEDITOR.NODE_TEXT) {
                g.split(i);
                g.equals(f) && (f = g.getNext())
            } else if (i)if (i >= g.getChildCount()) {
                g = g.append(a.document.createText(""));
                h = true
            } else g = g.getChild(i).getPrevious(); else {
                g = g.append(a.document.createText(""), 1);
                h = true
            }
            var i = g.getParents(), c = f.getParents(), x, w, v;
            for (x = 0; x < i.length; x++) {
                w = i[x];
                v = c[x];
                if (!w.equals(v))break
            }
            for (var r = d, u, A, B, y = x; y < i.length; y++) {
                u = i[y];
                r && !u.equals(g) && (A = r.append(u.clone()));
                for (u = u.getNext(); u;) {
                    if (u.equals(c[y]) || u.equals(f))break;
                    B = u.getNext();
                    if (b == 2)r.append(u.clone(true)); else {
                        u.remove();
                        b == 1 && r.append(u)
                    }
                    u = B
                }
                r && (r = A)
            }
            r = d;
            for (d = x; d < c.length; d++) {
                u = c[d];
                b > 0 && !u.equals(f) && (A = r.append(u.clone()));
                if (!i[d] || u.$.parentNode != i[d].$.parentNode)for (u = u.getPrevious(); u;) {
                    if (u.equals(i[d]) || u.equals(g))break;
                    B = u.getPrevious();
                    if (b == 2)r.$.insertBefore(u.$.cloneNode(true), r.$.firstChild); else {
                        u.remove();
                        b == 1 && r.$.insertBefore(u.$, r.$.firstChild)
                    }
                    u = B
                }
                r && (r = A)
            }
            if (b == 2) {
                w = a.startContainer;
                if (w.type == CKEDITOR.NODE_TEXT) {
                    w.$.data = w.$.data + w.$.nextSibling.data;
                    w.$.parentNode.removeChild(w.$.nextSibling)
                }
                a = a.endContainer;
                if (a.type == CKEDITOR.NODE_TEXT && a.$.nextSibling) {
                    a.$.data = a.$.data + a.$.nextSibling.data;
                    a.$.parentNode.removeChild(a.$.nextSibling)
                }
            } else {
                if (w && v && (g.$.parentNode != w.$.parentNode || f.$.parentNode != v.$.parentNode)) {
                    b = v.getIndex();
                    h && v.$.parentNode == g.$.parentNode && b--;
                    if (e && w.type == CKEDITOR.NODE_ELEMENT) {
                        e = CKEDITOR.dom.element.createFromHtml('<span data-cke-bookmark="1" style="display:none">&nbsp;</span>', a.document);
                        e.insertAfter(w);
                        w.mergeSiblings(false);
                        a.moveToBookmark({startNode: e})
                    } else a.setStart(v.getParent(), b)
                }
                a.collapse(true)
            }
            h && g.remove();
            j && f.$.parentNode && f.remove()
        }, g = {abbr: 1,
            acronym: 1, b: 1, bdo: 1, big: 1, cite: 1, code: 1, del: 1, dfn: 1, em: 1, font: 1, i: 1, ins: 1, label: 1, kbd: 1, q: 1, samp: 1, small: 1, span: 1, strike: 1, strong: 1, sub: 1, sup: 1, tt: 1, u: 1, "var": 1}, d = CKEDITOR.dom.walker.bogus(), i = new CKEDITOR.dom.walker.whitespaces, e = new CKEDITOR.dom.walker.bookmark, j = /^[\t\r\n ]*(?:&nbsp;|\xa0)$/;
        CKEDITOR.dom.range.prototype = {clone: function () {
            var a = new CKEDITOR.dom.range(this.root);
            a.startContainer = this.startContainer;
            a.startOffset = this.startOffset;
            a.endContainer = this.endContainer;
            a.endOffset = this.endOffset;
            a.collapsed = this.collapsed;
            return a
        }, collapse: function (a) {
            if (a) {
                this.endContainer = this.startContainer;
                this.endOffset = this.startOffset
            } else {
                this.startContainer = this.endContainer;
                this.startOffset = this.endOffset
            }
            this.collapsed = true
        }, cloneContents: function () {
            var a = new CKEDITOR.dom.documentFragment(this.document);
            this.collapsed || h(this, 2, a);
            return a
        }, deleteContents: function (a) {
            this.collapsed || h(this, 0, null, a)
        }, extractContents: function (a) {
            var b = new CKEDITOR.dom.documentFragment(this.document);
            this.collapsed ||
            h(this, 1, b, a);
            return b
        }, createBookmark: function (a) {
            var b, d, e, g, f = this.collapsed;
            b = this.document.createElement("span");
            b.data("cke-bookmark", 1);
            b.setStyle("display", "none");
            b.setHtml("&nbsp;");
            if (a) {
                e = "cke_bm_" + CKEDITOR.tools.getNextNumber();
                b.setAttribute("id", e + (f ? "C" : "S"))
            }
            if (!f) {
                d = b.clone();
                d.setHtml("&nbsp;");
                a && d.setAttribute("id", e + "E");
                g = this.clone();
                g.collapse();
                g.insertNode(d)
            }
            g = this.clone();
            g.collapse(true);
            g.insertNode(b);
            if (d) {
                this.setStartAfter(b);
                this.setEndBefore(d)
            } else this.moveToPosition(b,
                CKEDITOR.POSITION_AFTER_END);
            return{startNode: a ? e + (f ? "C" : "S") : b, endNode: a ? e + "E" : d, serializable: a, collapsed: f}
        }, createBookmark2: function (a) {
            var b = this.startContainer, d = this.endContainer, e = this.startOffset, g = this.endOffset, f = this.collapsed, i, c;
            if (!b || !d)return{start: 0, end: 0};
            if (a) {
                if (b.type == CKEDITOR.NODE_ELEMENT) {
                    if ((i = b.getChild(e)) && i.type == CKEDITOR.NODE_TEXT && e > 0 && i.getPrevious().type == CKEDITOR.NODE_TEXT) {
                        b = i;
                        e = 0
                    }
                    i && i.type == CKEDITOR.NODE_ELEMENT && (e = i.getIndex(1))
                }
                for (; b.type == CKEDITOR.NODE_TEXT &&
                           (c = b.getPrevious()) && c.type == CKEDITOR.NODE_TEXT;) {
                    b = c;
                    e = e + c.getLength()
                }
                if (!f) {
                    if (d.type == CKEDITOR.NODE_ELEMENT) {
                        if ((i = d.getChild(g)) && i.type == CKEDITOR.NODE_TEXT && g > 0 && i.getPrevious().type == CKEDITOR.NODE_TEXT) {
                            d = i;
                            g = 0
                        }
                        i && i.type == CKEDITOR.NODE_ELEMENT && (g = i.getIndex(1))
                    }
                    for (; d.type == CKEDITOR.NODE_TEXT && (c = d.getPrevious()) && c.type == CKEDITOR.NODE_TEXT;) {
                        d = c;
                        g = g + c.getLength()
                    }
                }
            }
            return{start: b.getAddress(a), end: f ? null : d.getAddress(a), startOffset: e, endOffset: g, normalized: a, collapsed: f, is2: true}
        }, moveToBookmark: function (a) {
            if (a.is2) {
                var b =
                    this.document.getByAddress(a.start, a.normalized), d = a.startOffset, e = a.end && this.document.getByAddress(a.end, a.normalized), a = a.endOffset;
                this.setStart(b, d);
                e ? this.setEnd(e, a) : this.collapse(true)
            } else {
                b = (d = a.serializable) ? this.document.getById(a.startNode) : a.startNode;
                a = d ? this.document.getById(a.endNode) : a.endNode;
                this.setStartBefore(b);
                b.remove();
                if (a) {
                    this.setEndBefore(a);
                    a.remove()
                } else this.collapse(true)
            }
        }, getBoundaryNodes: function () {
            var a = this.startContainer, b = this.endContainer, d = this.startOffset,
                e = this.endOffset, g;
            if (a.type == CKEDITOR.NODE_ELEMENT) {
                g = a.getChildCount();
                if (g > d)a = a.getChild(d); else if (g < 1)a = a.getPreviousSourceNode(); else {
                    for (a = a.$; a.lastChild;)a = a.lastChild;
                    a = new CKEDITOR.dom.node(a);
                    a = a.getNextSourceNode() || a
                }
            }
            if (b.type == CKEDITOR.NODE_ELEMENT) {
                g = b.getChildCount();
                if (g > e)b = b.getChild(e).getPreviousSourceNode(true); else if (g < 1)b = b.getPreviousSourceNode(); else {
                    for (b = b.$; b.lastChild;)b = b.lastChild;
                    b = new CKEDITOR.dom.node(b)
                }
            }
            a.getPosition(b) & CKEDITOR.POSITION_FOLLOWING && (a = b);
            return{startNode: a,
                endNode: b}
        }, getCommonAncestor: function (a, b) {
            var d = this.startContainer, e = this.endContainer, d = d.equals(e) ? a && d.type == CKEDITOR.NODE_ELEMENT && this.startOffset == this.endOffset - 1 ? d.getChild(this.startOffset) : d : d.getCommonAncestor(e);
            return b && !d.is ? d.getParent() : d
        }, optimize: function () {
            var a = this.startContainer, b = this.startOffset;
            a.type != CKEDITOR.NODE_ELEMENT && (b ? b >= a.getLength() && this.setStartAfter(a) : this.setStartBefore(a));
            a = this.endContainer;
            b = this.endOffset;
            a.type != CKEDITOR.NODE_ELEMENT && (b ? b >= a.getLength() &&
                this.setEndAfter(a) : this.setEndBefore(a))
        }, optimizeBookmark: function () {
            var a = this.startContainer, b = this.endContainer;
            a.is && (a.is("span") && a.data("cke-bookmark")) && this.setStartAt(a, CKEDITOR.POSITION_BEFORE_START);
            b && (b.is && b.is("span") && b.data("cke-bookmark")) && this.setEndAt(b, CKEDITOR.POSITION_AFTER_END)
        }, trim: function (a, b) {
            var d = this.startContainer, e = this.startOffset, g = this.collapsed;
            if ((!a || g) && d && d.type == CKEDITOR.NODE_TEXT) {
                if (e)if (e >= d.getLength()) {
                    e = d.getIndex() + 1;
                    d = d.getParent()
                } else {
                    var f =
                        d.split(e), e = d.getIndex() + 1, d = d.getParent();
                    if (this.startContainer.equals(this.endContainer))this.setEnd(f, this.endOffset - this.startOffset); else if (d.equals(this.endContainer))this.endOffset = this.endOffset + 1
                } else {
                    e = d.getIndex();
                    d = d.getParent()
                }
                this.setStart(d, e);
                if (g) {
                    this.collapse(true);
                    return
                }
            }
            d = this.endContainer;
            e = this.endOffset;
            if (!b && !g && d && d.type == CKEDITOR.NODE_TEXT) {
                if (e) {
                    e >= d.getLength() || d.split(e);
                    e = d.getIndex() + 1
                } else e = d.getIndex();
                d = d.getParent();
                this.setEnd(d, e)
            }
        }, enlarge: function (a, b) {
            switch (a) {
                case CKEDITOR.ENLARGE_INLINE:
                    var d = 1;
                case CKEDITOR.ENLARGE_ELEMENT:
                    if (this.collapsed)break;
                    var e = this.getCommonAncestor(), g = this.root, f, i, c, h, j, x = false, w, v;
                    w = this.startContainer;
                    v = this.startOffset;
                    if (w.type == CKEDITOR.NODE_TEXT) {
                        if (v) {
                            w = !CKEDITOR.tools.trim(w.substring(0, v)).length && w;
                            x = !!w
                        }
                        if (w && !(h = w.getPrevious()))c = w.getParent()
                    } else {
                        v && (h = w.getChild(v - 1) || w.getLast());
                        h || (c = w)
                    }
                    for (; c || h;) {
                        if (c && !h) {
                            !j && c.equals(e) && (j = true);
                            if (d ? c.isBlockBoundary() : !g.contains(c))break;
                            if (!x || c.getComputedStyle("display") !=
                                "inline") {
                                x = false;
                                j ? f = c : this.setStartBefore(c)
                            }
                            h = c.getPrevious()
                        }
                        for (; h;) {
                            w = false;
                            if (h.type == CKEDITOR.NODE_COMMENT)h = h.getPrevious(); else {
                                if (h.type == CKEDITOR.NODE_TEXT) {
                                    v = h.getText();
                                    /[^\s\ufeff]/.test(v) && (h = null);
                                    w = /[\s\ufeff]$/.test(v)
                                } else if ((h.$.offsetWidth > 0 || b && h.is("br")) && !h.data("cke-bookmark"))if (x && CKEDITOR.dtd.$removeEmpty[h.getName()]) {
                                    v = h.getText();
                                    if (/[^\s\ufeff]/.test(v))h = null; else for (var r = h.$.getElementsByTagName("*"), u = 0, A; A = r[u++];)if (!CKEDITOR.dtd.$removeEmpty[A.nodeName.toLowerCase()]) {
                                        h =
                                            null;
                                        break
                                    }
                                    h && (w = !!v.length)
                                } else h = null;
                                w && (x ? j ? f = c : c && this.setStartBefore(c) : x = true);
                                if (h) {
                                    w = h.getPrevious();
                                    if (!c && !w) {
                                        c = h;
                                        h = null;
                                        break
                                    }
                                    h = w
                                } else c = null
                            }
                        }
                        c && (c = c.getParent())
                    }
                    w = this.endContainer;
                    v = this.endOffset;
                    c = h = null;
                    j = x = false;
                    if (w.type == CKEDITOR.NODE_TEXT) {
                        w = !CKEDITOR.tools.trim(w.substring(v)).length && w;
                        x = !(w && w.getLength());
                        if (w && !(h = w.getNext()))c = w.getParent()
                    } else(h = w.getChild(v)) || (c = w);
                    for (; c || h;) {
                        if (c && !h) {
                            !j && c.equals(e) && (j = true);
                            if (d ? c.isBlockBoundary() : !g.contains(c))break;
                            if (!x ||
                                c.getComputedStyle("display") != "inline") {
                                x = false;
                                j ? i = c : c && this.setEndAfter(c)
                            }
                            h = c.getNext()
                        }
                        for (; h;) {
                            w = false;
                            if (h.type == CKEDITOR.NODE_TEXT) {
                                v = h.getText();
                                /[^\s\ufeff]/.test(v) && (h = null);
                                w = /^[\s\ufeff]/.test(v)
                            } else if (h.type == CKEDITOR.NODE_ELEMENT) {
                                if ((h.$.offsetWidth > 0 || b && h.is("br")) && !h.data("cke-bookmark"))if (x && CKEDITOR.dtd.$removeEmpty[h.getName()]) {
                                    v = h.getText();
                                    if (/[^\s\ufeff]/.test(v))h = null; else {
                                        r = h.$.getElementsByTagName("*");
                                        for (u = 0; A = r[u++];)if (!CKEDITOR.dtd.$removeEmpty[A.nodeName.toLowerCase()]) {
                                            h =
                                                null;
                                            break
                                        }
                                    }
                                    h && (w = !!v.length)
                                } else h = null
                            } else w = 1;
                            w && x && (j ? i = c : this.setEndAfter(c));
                            if (h) {
                                w = h.getNext();
                                if (!c && !w) {
                                    c = h;
                                    h = null;
                                    break
                                }
                                h = w
                            } else c = null
                        }
                        c && (c = c.getParent())
                    }
                    if (f && i) {
                        e = f.contains(i) ? i : f;
                        this.setStartBefore(e);
                        this.setEndAfter(e)
                    }
                    break;
                case CKEDITOR.ENLARGE_BLOCK_CONTENTS:
                case CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS:
                    c = new CKEDITOR.dom.range(this.root);
                    g = this.root;
                    c.setStartAt(g, CKEDITOR.POSITION_AFTER_START);
                    c.setEnd(this.startContainer, this.startOffset);
                    c = new CKEDITOR.dom.walker(c);
                    var B,
                        y, C = CKEDITOR.dom.walker.blockBoundary(a == CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS ? {br: 1} : null), D = function (a) {
                            var b = C(a);
                            b || (B = a);
                            return b
                        }, d = function (a) {
                            var b = D(a);
                            !b && (a.is && a.is("br")) && (y = a);
                            return b
                        };
                    c.guard = D;
                    c = c.lastBackward();
                    B = B || g;
                    this.setStartAt(B, !B.is("br") && (!c && this.checkStartOfBlock() || c && B.contains(c)) ? CKEDITOR.POSITION_AFTER_START : CKEDITOR.POSITION_AFTER_END);
                    if (a == CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS) {
                        c = this.clone();
                        c = new CKEDITOR.dom.walker(c);
                        var F = CKEDITOR.dom.walker.whitespaces(),
                            E = CKEDITOR.dom.walker.bookmark();
                        c.evaluator = function (a) {
                            return!F(a) && !E(a)
                        };
                        if ((c = c.previous()) && c.type == CKEDITOR.NODE_ELEMENT && c.is("br"))break
                    }
                    c = this.clone();
                    c.collapse();
                    c.setEndAt(g, CKEDITOR.POSITION_BEFORE_END);
                    c = new CKEDITOR.dom.walker(c);
                    c.guard = a == CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS ? d : D;
                    B = null;
                    c = c.lastForward();
                    B = B || g;
                    this.setEndAt(B, !c && this.checkEndOfBlock() || c && B.contains(c) ? CKEDITOR.POSITION_BEFORE_END : CKEDITOR.POSITION_BEFORE_START);
                    y && this.setEndAfter(y)
            }
        }, shrink: function (a, b, d) {
            if (!this.collapsed) {
                var a =
                    a || CKEDITOR.SHRINK_TEXT, e = this.clone(), g = this.startContainer, f = this.endContainer, c = this.startOffset, i = this.endOffset, h = 1, j = 1;
                if (g && g.type == CKEDITOR.NODE_TEXT)if (c)if (c >= g.getLength())e.setStartAfter(g); else {
                    e.setStartBefore(g);
                    h = 0
                } else e.setStartBefore(g);
                if (f && f.type == CKEDITOR.NODE_TEXT)if (i)if (i >= f.getLength())e.setEndAfter(f); else {
                    e.setEndAfter(f);
                    j = 0
                } else e.setEndBefore(f);
                var e = new CKEDITOR.dom.walker(e), x = CKEDITOR.dom.walker.bookmark();
                e.evaluator = function (b) {
                    return b.type == (a == CKEDITOR.SHRINK_ELEMENT ?
                        CKEDITOR.NODE_ELEMENT : CKEDITOR.NODE_TEXT)
                };
                var w;
                e.guard = function (b, e) {
                    if (x(b))return true;
                    if (a == CKEDITOR.SHRINK_ELEMENT && b.type == CKEDITOR.NODE_TEXT || e && b.equals(w) || d === false && b.type == CKEDITOR.NODE_ELEMENT && b.isBlockBoundary())return false;
                    !e && b.type == CKEDITOR.NODE_ELEMENT && (w = b);
                    return true
                };
                if (h)(g = e[a == CKEDITOR.SHRINK_ELEMENT ? "lastForward" : "next"]()) && this.setStartAt(g, b ? CKEDITOR.POSITION_AFTER_START : CKEDITOR.POSITION_BEFORE_START);
                if (j) {
                    e.reset();
                    (e = e[a == CKEDITOR.SHRINK_ELEMENT ? "lastBackward" :
                        "previous"]()) && this.setEndAt(e, b ? CKEDITOR.POSITION_BEFORE_END : CKEDITOR.POSITION_AFTER_END)
                }
                return!(!h && !j)
            }
        }, insertNode: function (a) {
            this.optimizeBookmark();
            this.trim(false, true);
            var b = this.startContainer, d = b.getChild(this.startOffset);
            d ? a.insertBefore(d) : b.append(a);
            a.getParent() && a.getParent().equals(this.endContainer) && this.endOffset++;
            this.setStartBefore(a)
        }, moveToPosition: function (a, b) {
            this.setStartAt(a, b);
            this.collapse(true)
        }, moveToRange: function (a) {
            this.setStart(a.startContainer, a.startOffset);
            this.setEnd(a.endContainer, a.endOffset)
        }, selectNodeContents: function (a) {
            this.setStart(a, 0);
            this.setEnd(a, a.type == CKEDITOR.NODE_TEXT ? a.getLength() : a.getChildCount())
        }, setStart: function (a, b) {
            if (a.type == CKEDITOR.NODE_ELEMENT && CKEDITOR.dtd.$empty[a.getName()]) {
                b = a.getIndex();
                a = a.getParent()
            }
            this.startContainer = a;
            this.startOffset = b;
            if (!this.endContainer) {
                this.endContainer = a;
                this.endOffset = b
            }
            f(this)
        }, setEnd: function (a, b) {
            if (a.type == CKEDITOR.NODE_ELEMENT && CKEDITOR.dtd.$empty[a.getName()]) {
                b = a.getIndex() +
                    1;
                a = a.getParent()
            }
            this.endContainer = a;
            this.endOffset = b;
            if (!this.startContainer) {
                this.startContainer = a;
                this.startOffset = b
            }
            f(this)
        }, setStartAfter: function (a) {
            this.setStart(a.getParent(), a.getIndex() + 1)
        }, setStartBefore: function (a) {
            this.setStart(a.getParent(), a.getIndex())
        }, setEndAfter: function (a) {
            this.setEnd(a.getParent(), a.getIndex() + 1)
        }, setEndBefore: function (a) {
            this.setEnd(a.getParent(), a.getIndex())
        }, setStartAt: function (a, b) {
            switch (b) {
                case CKEDITOR.POSITION_AFTER_START:
                    this.setStart(a, 0);
                    break;
                case CKEDITOR.POSITION_BEFORE_END:
                    a.type ==
                        CKEDITOR.NODE_TEXT ? this.setStart(a, a.getLength()) : this.setStart(a, a.getChildCount());
                    break;
                case CKEDITOR.POSITION_BEFORE_START:
                    this.setStartBefore(a);
                    break;
                case CKEDITOR.POSITION_AFTER_END:
                    this.setStartAfter(a)
            }
            f(this)
        }, setEndAt: function (a, b) {
            switch (b) {
                case CKEDITOR.POSITION_AFTER_START:
                    this.setEnd(a, 0);
                    break;
                case CKEDITOR.POSITION_BEFORE_END:
                    a.type == CKEDITOR.NODE_TEXT ? this.setEnd(a, a.getLength()) : this.setEnd(a, a.getChildCount());
                    break;
                case CKEDITOR.POSITION_BEFORE_START:
                    this.setEndBefore(a);
                    break;
                case CKEDITOR.POSITION_AFTER_END:
                    this.setEndAfter(a)
            }
            f(this)
        }, fixBlock: function (a, b) {
            var d = this.createBookmark(), e = this.document.createElement(b);
            this.collapse(a);
            this.enlarge(CKEDITOR.ENLARGE_BLOCK_CONTENTS);
            this.extractContents().appendTo(e);
            e.trim();
            CKEDITOR.env.ie || e.appendBogus();
            this.insertNode(e);
            this.moveToBookmark(d);
            return e
        }, splitBlock: function (a) {
            var b = new CKEDITOR.dom.elementPath(this.startContainer, this.root), d = new CKEDITOR.dom.elementPath(this.endContainer, this.root), e = b.block, g = d.block,
                f = null;
            if (!b.blockLimit.equals(d.blockLimit))return null;
            if (a != "br") {
                if (!e) {
                    e = this.fixBlock(true, a);
                    g = (new CKEDITOR.dom.elementPath(this.endContainer, this.root)).block
                }
                g || (g = this.fixBlock(false, a))
            }
            a = e && this.checkStartOfBlock();
            b = g && this.checkEndOfBlock();
            this.deleteContents();
            if (e && e.equals(g))if (b) {
                f = new CKEDITOR.dom.elementPath(this.startContainer, this.root);
                this.moveToPosition(g, CKEDITOR.POSITION_AFTER_END);
                g = null
            } else if (a) {
                f = new CKEDITOR.dom.elementPath(this.startContainer, this.root);
                this.moveToPosition(e,
                    CKEDITOR.POSITION_BEFORE_START);
                e = null
            } else {
                g = this.splitElement(e);
                !CKEDITOR.env.ie && !e.is("ul", "ol") && e.appendBogus()
            }
            return{previousBlock: e, nextBlock: g, wasStartOfBlock: a, wasEndOfBlock: b, elementPath: f}
        }, splitElement: function (a) {
            if (!this.collapsed)return null;
            this.setEndAt(a, CKEDITOR.POSITION_BEFORE_END);
            var b = this.extractContents(), d = a.clone(false);
            b.appendTo(d);
            d.insertAfter(a);
            this.moveToPosition(a, CKEDITOR.POSITION_AFTER_END);
            return d
        }, removeEmptyBlocksAtEnd: function () {
            function a(e) {
                return function (a) {
                    return b(a) ||
                        (d(a) || a.type == CKEDITOR.NODE_ELEMENT && a.isEmptyInlineRemoveable()) || e.is("table") && a.is("caption") ? false : true
                }
            }

            var b = CKEDITOR.dom.walker.whitespaces(), d = CKEDITOR.dom.walker.bookmark(false);
            return function (b) {
                for (var d = this.createBookmark(), e = this[b ? "endPath" : "startPath"](), g = e.block || e.blockLimit, f; g && !g.equals(e.root) && !g.getFirst(a(g));) {
                    f = g.getParent();
                    this[b ? "setEndAt" : "setStartAt"](g, CKEDITOR.POSITION_AFTER_END);
                    g.remove(1);
                    g = f
                }
                this.moveToBookmark(d)
            }
        }(), startPath: function () {
            return new CKEDITOR.dom.elementPath(this.startContainer,
                this.root)
        }, endPath: function () {
            return new CKEDITOR.dom.elementPath(this.endContainer, this.root)
        }, checkBoundaryOfElement: function (a, b) {
            var d = b == CKEDITOR.START, e = this.clone();
            e.collapse(d);
            e[d ? "setStartAt" : "setEndAt"](a, d ? CKEDITOR.POSITION_AFTER_START : CKEDITOR.POSITION_BEFORE_END);
            e = new CKEDITOR.dom.walker(e);
            e.evaluator = c(d);
            return e[d ? "checkBackward" : "checkForward"]()
        }, checkStartOfBlock: function () {
            var a = this.startContainer, d = this.startOffset;
            if (CKEDITOR.env.ie && d && a.type == CKEDITOR.NODE_TEXT) {
                a = CKEDITOR.tools.ltrim(a.substring(0,
                    d));
                j.test(a) && this.trim(0, 1)
            }
            this.trim();
            a = new CKEDITOR.dom.elementPath(this.startContainer, this.root);
            d = this.clone();
            d.collapse(true);
            d.setStartAt(a.block || a.blockLimit, CKEDITOR.POSITION_AFTER_START);
            a = new CKEDITOR.dom.walker(d);
            a.evaluator = b();
            return a.checkBackward()
        }, checkEndOfBlock: function () {
            var a = this.endContainer, d = this.endOffset;
            if (CKEDITOR.env.ie && a.type == CKEDITOR.NODE_TEXT) {
                a = CKEDITOR.tools.rtrim(a.substring(d));
                j.test(a) && this.trim(1, 0)
            }
            this.trim();
            a = new CKEDITOR.dom.elementPath(this.endContainer,
                this.root);
            d = this.clone();
            d.collapse(false);
            d.setEndAt(a.block || a.blockLimit, CKEDITOR.POSITION_BEFORE_END);
            a = new CKEDITOR.dom.walker(d);
            a.evaluator = b();
            return a.checkForward()
        }, getPreviousNode: function (a, b, d) {
            var e = this.clone();
            e.collapse(1);
            e.setStartAt(d || this.root, CKEDITOR.POSITION_AFTER_START);
            d = new CKEDITOR.dom.walker(e);
            d.evaluator = a;
            d.guard = b;
            return d.previous()
        }, getNextNode: function (a, b, d) {
            var e = this.clone();
            e.collapse();
            e.setEndAt(d || this.root, CKEDITOR.POSITION_BEFORE_END);
            d = new CKEDITOR.dom.walker(e);
            d.evaluator = a;
            d.guard = b;
            return d.next()
        }, checkReadOnly: function () {
            function a(b, d) {
                for (; b;) {
                    if (b.type == CKEDITOR.NODE_ELEMENT) {
                        if (b.getAttribute("contentEditable") == "false" && !b.data("cke-editable"))return 0;
                        if (b.is("html") || b.getAttribute("contentEditable") == "true" && (b.contains(d) || b.equals(d)))break
                    }
                    b = b.getParent()
                }
                return 1
            }

            return function () {
                var b = this.startContainer, d = this.endContainer;
                return!(a(b, d) && a(d, b))
            }
        }(), moveToElementEditablePosition: function (b, d) {
            if (b.type == CKEDITOR.NODE_ELEMENT && !b.isEditable(false)) {
                this.moveToPosition(b,
                    d ? CKEDITOR.POSITION_AFTER_END : CKEDITOR.POSITION_BEFORE_START);
                return true
            }
            for (var e = 0; b;) {
                if (b.type == CKEDITOR.NODE_TEXT) {
                    d && this.checkEndOfBlock() && j.test(b.getText()) ? this.moveToPosition(b, CKEDITOR.POSITION_BEFORE_START) : this.moveToPosition(b, d ? CKEDITOR.POSITION_AFTER_END : CKEDITOR.POSITION_BEFORE_START);
                    e = 1;
                    break
                }
                if (b.type == CKEDITOR.NODE_ELEMENT)if (b.isEditable()) {
                    this.moveToPosition(b, d ? CKEDITOR.POSITION_BEFORE_END : CKEDITOR.POSITION_AFTER_START);
                    e = 1
                } else d && (b.is("br") && this.checkEndOfBlock()) &&
                this.moveToPosition(b, CKEDITOR.POSITION_BEFORE_START);
                var g = b, f = e, c = void 0;
                g.type == CKEDITOR.NODE_ELEMENT && g.isEditable(false) && (c = g[d ? "getLast" : "getFirst"](a));
                !f && !c && (c = g[d ? "getPrevious" : "getNext"](a));
                b = c
            }
            return!!e
        }, moveToElementEditStart: function (a) {
            return this.moveToElementEditablePosition(a)
        }, moveToElementEditEnd: function (a) {
            return this.moveToElementEditablePosition(a, true)
        }, getEnclosedNode: function () {
            var a = this.clone();
            a.optimize();
            if (a.startContainer.type != CKEDITOR.NODE_ELEMENT || a.endContainer.type !=
                CKEDITOR.NODE_ELEMENT)return null;
            var a = new CKEDITOR.dom.walker(a), b = CKEDITOR.dom.walker.bookmark(false, true), d = CKEDITOR.dom.walker.whitespaces(true);
            a.evaluator = function (a) {
                return d(a) && b(a)
            };
            var e = a.next();
            a.reset();
            return e && e.equals(a.previous()) ? e : null
        }, getTouchedStartNode: function () {
            var a = this.startContainer;
            return this.collapsed || a.type != CKEDITOR.NODE_ELEMENT ? a : a.getChild(this.startOffset) || a
        }, getTouchedEndNode: function () {
            var a = this.endContainer;
            return this.collapsed || a.type != CKEDITOR.NODE_ELEMENT ?
                a : a.getChild(this.endOffset - 1) || a
        }, scrollIntoView: function () {
            var a = new CKEDITOR.dom.element.createFromHtml("<span>&nbsp;</span>", this.document), b, d, e, g = this.clone();
            g.optimize();
            if (e = g.startContainer.type == CKEDITOR.NODE_TEXT) {
                d = g.startContainer.getText();
                b = g.startContainer.split(g.startOffset);
                a.insertAfter(g.startContainer)
            } else g.insertNode(a);
            a.scrollIntoView();
            if (e) {
                g.startContainer.setText(d);
                b.remove()
            }
            a.remove()
        }}
    }(), CKEDITOR.POSITION_AFTER_START = 1, CKEDITOR.POSITION_BEFORE_END = 2, CKEDITOR.POSITION_BEFORE_START =
        3, CKEDITOR.POSITION_AFTER_END = 4, CKEDITOR.ENLARGE_ELEMENT = 1, CKEDITOR.ENLARGE_BLOCK_CONTENTS = 2, CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS = 3, CKEDITOR.ENLARGE_INLINE = 4, CKEDITOR.START = 1, CKEDITOR.END = 2, CKEDITOR.SHRINK_ELEMENT = 1, CKEDITOR.SHRINK_TEXT = 2, function () {
        function b(a) {
            if (!(arguments.length < 1)) {
                this.range = a;
                this.forceBrBreak = 0;
                this.enlargeBr = 1;
                this.enforceRealBlocks = 0;
                this._ || (this._ = {})
            }
        }

        function c(a, b, e) {
            for (a = a.getNextSourceNode(b, null, e); !f(a);)a = a.getNextSourceNode(b, null, e);
            return a
        }

        var a = /^[\r\n\t ]+$/,
            f = CKEDITOR.dom.walker.bookmark(false, true), h = CKEDITOR.dom.walker.whitespaces(true), g = function (a) {
                return f(a) && h(a)
            };
        b.prototype = {getNextParagraph: function (b) {
            b = b || "p";
            if (!CKEDITOR.dtd[this.range.root.getName()][b])return null;
            var i, e, h, k, m, n;
            if (!this._.started) {
                e = this.range.clone();
                e.shrink(CKEDITOR.NODE_ELEMENT, true);
                k = e.endContainer.hasAscendant("pre", true) || e.startContainer.hasAscendant("pre", true);
                e.enlarge(this.forceBrBreak && !k || !this.enlargeBr ? CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS : CKEDITOR.ENLARGE_BLOCK_CONTENTS);
                if (!e.collapsed) {
                    k = new CKEDITOR.dom.walker(e.clone());
                    var l = CKEDITOR.dom.walker.bookmark(true, true);
                    k.evaluator = l;
                    this._.nextNode = k.next();
                    k = new CKEDITOR.dom.walker(e.clone());
                    k.evaluator = l;
                    k = k.previous();
                    this._.lastNode = k.getNextSourceNode(true);
                    if (this._.lastNode && this._.lastNode.type == CKEDITOR.NODE_TEXT && !CKEDITOR.tools.trim(this._.lastNode.getText()) && this._.lastNode.getParent().isBlockBoundary()) {
                        l = this.range.clone();
                        l.moveToPosition(this._.lastNode, CKEDITOR.POSITION_AFTER_END);
                        if (l.checkEndOfBlock()) {
                            l =
                                new CKEDITOR.dom.elementPath(l.endContainer, l.root);
                            this._.lastNode = (l.block || l.blockLimit).getNextSourceNode(true)
                        }
                    }
                    if (!this._.lastNode) {
                        this._.lastNode = this._.docEndMarker = e.document.createText("");
                        this._.lastNode.insertAfter(k)
                    }
                    e = null
                }
                this._.started = 1
            }
            l = this._.nextNode;
            k = this._.lastNode;
            for (this._.nextNode = null; l;) {
                var o = 0, q = l.hasAscendant("pre"), s = l.type != CKEDITOR.NODE_ELEMENT, p = 0;
                if (s)l.type == CKEDITOR.NODE_TEXT && a.test(l.getText()) && (s = 0); else {
                    var t = l.getName();
                    if (l.isBlockBoundary(this.forceBrBreak && !q && {br: 1})) {
                        if (t == "br")s = 1; else if (!e && !l.getChildCount() && t != "hr") {
                            i = l;
                            h = l.equals(k);
                            break
                        }
                        if (e) {
                            e.setEndAt(l, CKEDITOR.POSITION_BEFORE_START);
                            if (t != "br")this._.nextNode = l
                        }
                        o = 1
                    } else {
                        if (l.getFirst()) {
                            if (!e) {
                                e = this.range.clone();
                                e.setStartAt(l, CKEDITOR.POSITION_BEFORE_START)
                            }
                            l = l.getFirst();
                            continue
                        }
                        s = 1
                    }
                }
                if (s && !e) {
                    e = this.range.clone();
                    e.setStartAt(l, CKEDITOR.POSITION_BEFORE_START)
                }
                h = (!o || s) && l.equals(k);
                if (e && !o)for (; !l.getNext(g) && !h;) {
                    t = l.getParent();
                    if (t.isBlockBoundary(this.forceBrBreak && !q && {br: 1})) {
                        o =
                            1;
                        s = 0;
                        h || t.equals(k);
                        e.setEndAt(t, CKEDITOR.POSITION_BEFORE_END);
                        break
                    }
                    l = t;
                    s = 1;
                    h = l.equals(k);
                    p = 1
                }
                s && e.setEndAt(l, CKEDITOR.POSITION_AFTER_END);
                l = c(l, p, k);
                if ((h = !l) || o && e)break
            }
            if (!i) {
                if (!e) {
                    this._.docEndMarker && this._.docEndMarker.remove();
                    return this._.nextNode = null
                }
                i = new CKEDITOR.dom.elementPath(e.startContainer, e.root);
                l = i.blockLimit;
                o = {div: 1, th: 1, td: 1};
                i = i.block;
                if (!i && l && !this.enforceRealBlocks && o[l.getName()] && e.checkStartOfBlock() && e.checkEndOfBlock() && !l.equals(e.root))i = l; else if (!i || this.enforceRealBlocks &&
                    i.getName() == "li") {
                    i = this.range.document.createElement(b);
                    e.extractContents().appendTo(i);
                    i.trim();
                    e.insertNode(i);
                    m = n = true
                } else if (i.getName() != "li") {
                    if (!e.checkStartOfBlock() || !e.checkEndOfBlock()) {
                        i = i.clone(false);
                        e.extractContents().appendTo(i);
                        i.trim();
                        n = e.splitBlock();
                        m = !n.wasStartOfBlock;
                        n = !n.wasEndOfBlock;
                        e.insertNode(i)
                    }
                } else if (!h)this._.nextNode = i.equals(k) ? null : c(e.getBoundaryNodes().endNode, 1, k)
            }
            if (m)(e = i.getPrevious()) && e.type == CKEDITOR.NODE_ELEMENT && (e.getName() == "br" ? e.remove() : e.getLast() &&
                e.getLast().$.nodeName.toLowerCase() == "br" && e.getLast().remove());
            if (n)(e = i.getLast()) && e.type == CKEDITOR.NODE_ELEMENT && e.getName() == "br" && (CKEDITOR.env.ie || e.getPrevious(f) || e.getNext(f)) && e.remove();
            if (!this._.nextNode)this._.nextNode = h || i.equals(k) || !k ? null : c(i, 1, k);
            return i
        }};
        CKEDITOR.dom.range.prototype.createIterator = function () {
            return new b(this)
        }
    }(), CKEDITOR.command = function (b, c) {
        this.uiItems = [];
        this.exec = function (a) {
            if (this.state == CKEDITOR.TRISTATE_DISABLED || !this.checkAllowed())return false;
            this.editorFocus && b.focus();
            return this.fire("exec") === false ? true : c.exec.call(this, b, a) !== false
        };
        this.refresh = function (a, b) {
            if (!this.readOnly && a.readOnly)return true;
            if (this.context && !b.isContextFor(this.context)) {
                this.disable();
                return true
            }
            this.enable();
            return this.fire("refresh", {editor: a, path: b}) === false ? true : c.refresh && c.refresh.apply(this, arguments) !== false
        };
        var a;
        this.checkAllowed = function () {
            return typeof a == "boolean" ? a : a = b.filter.checkFeature(this)
        };
        CKEDITOR.tools.extend(this, c, {modes: {wysiwyg: 1},
            editorFocus: 1, contextSensitive: !!c.context, state: CKEDITOR.TRISTATE_DISABLED});
        CKEDITOR.event.call(this)
    }, CKEDITOR.command.prototype = {enable: function () {
        this.state == CKEDITOR.TRISTATE_DISABLED && this.checkAllowed() && this.setState(!this.preserveState || typeof this.previousState == "undefined" ? CKEDITOR.TRISTATE_OFF : this.previousState)
    }, disable: function () {
        this.setState(CKEDITOR.TRISTATE_DISABLED)
    }, setState: function (b) {
        if (this.state == b || !this.checkAllowed())return false;
        this.previousState = this.state;
        this.state =
            b;
        this.fire("state");
        return true
    }, toggleState: function () {
        this.state == CKEDITOR.TRISTATE_OFF ? this.setState(CKEDITOR.TRISTATE_ON) : this.state == CKEDITOR.TRISTATE_ON && this.setState(CKEDITOR.TRISTATE_OFF)
    }}, CKEDITOR.event.implementOn(CKEDITOR.command.prototype), CKEDITOR.ENTER_P = 1, CKEDITOR.ENTER_BR = 2, CKEDITOR.ENTER_DIV = 3, CKEDITOR.config = {customConfig: "config.js", autoUpdateElement: !0, language: "", defaultLanguage: "en", contentsLangDirection: "", enterMode: CKEDITOR.ENTER_P, forceEnterMode: !1, shiftEnterMode: CKEDITOR.ENTER_BR,
        docType: "<!DOCTYPE html>", bodyId: "", bodyClass: "", fullPage: !1, height: 200, extraPlugins: "", removePlugins: "", protectedSource: [], tabIndex: 0, width: "", baseFloatZIndex: 1E4, blockedKeystrokes: [CKEDITOR.CTRL + 66, CKEDITOR.CTRL + 73, CKEDITOR.CTRL + 85]}, function () {
        function b(a, b, d, e, g) {
            var f = b.name;
            if ((e || typeof a.elements != "function" || a.elements(f)) && (!a.match || a.match(b))) {
                if (e = !g) {
                    a:if (a.nothingRequired)e = true; else {
                        if (g = a.requiredClasses) {
                            f = b.classes;
                            for (e = 0; e < g.length; ++e)if (CKEDITOR.tools.indexOf(f, g[e]) == -1) {
                                e =
                                    false;
                                break a
                            }
                        }
                        e = h(b.styles, a.requiredStyles) && h(b.attributes, a.requiredAttributes)
                    }
                    e = !e
                }
                if (!e) {
                    if (!a.propertiesOnly)d.valid = true;
                    if (!d.allAttributes)d.allAttributes = c(a.attributes, b.attributes, d.validAttributes);
                    if (!d.allStyles)d.allStyles = c(a.styles, b.styles, d.validStyles);
                    if (!d.allClasses) {
                        a = a.classes;
                        b = b.classes;
                        e = d.validClasses;
                        if (a)if (a === true)b = true; else {
                            for (var g = 0, f = b.length, i; g < f; ++g) {
                                i = b[g];
                                e[i] || (e[i] = a(i))
                            }
                            b = false
                        } else b = false;
                        d.allClasses = b
                    }
                }
            }
        }

        function c(a, b, d) {
            if (!a)return false;
            if (a ===
                true)return true;
            for (var e in b)d[e] || (d[e] = a(e, b[e]));
            return false
        }

        function a(a, b) {
            if (!a)return false;
            if (a === true)return a;
            if (typeof a == "string") {
                a = v(a);
                return a == "*" ? true : CKEDITOR.tools.convertArrayToObject(a.split(b))
            }
            if (CKEDITOR.tools.isArray(a))return a.length ? CKEDITOR.tools.convertArrayToObject(a) : false;
            var d = {}, e = 0, g;
            for (g in a) {
                d[g] = a[g];
                e++
            }
            return e ? d : false
        }

        function f(a) {
            if (a._.filterFunction)return a._.filterFunction;
            var d = /^cke:(object|embed|param)$/, g = /^(object|embed|param)$/;
            return a._.filterFunction =
                function (f, c, i, h, o, l, p) {
                    var m = f.name, n, s = false;
                    if (o)f.name = m = m.replace(d, "$1");
                    if (i = i && i[m]) {
                        e(f);
                        for (m = 0; m < i.length; ++m)q(a, f, i[m]);
                        j(f)
                    }
                    if (c) {
                        var m = f.name, i = c.elements[m], t = c.generic, c = {valid: false, validAttributes: {}, validClasses: {}, validStyles: {}, allAttributes: false, allClasses: false, allStyles: false};
                        if (!i && !t) {
                            h.push(f);
                            return true
                        }
                        e(f);
                        if (i) {
                            m = 0;
                            for (n = i.length; m < n; ++m)b(i[m], f, c, true, l)
                        }
                        if (t) {
                            m = 0;
                            for (n = t.length; m < n; ++m)b(t[m], f, c, false, l)
                        }
                        if (!c.valid) {
                            h.push(f);
                            return true
                        }
                        l = c.validAttributes;
                        m = c.validStyles;
                        i = c.validClasses;
                        n = f.attributes;
                        var t = f.styles, v = n["class"], w = n.style, y, x, z = [], r = [], u = /^data-cke-/, C = false;
                        delete n.style;
                        delete n["class"];
                        if (!c.allAttributes)for (y in n)if (!l[y])if (u.test(y)) {
                            if (y != (x = y.replace(/^data-cke-saved-/, "")) && !l[x]) {
                                delete n[y];
                                C = true
                            }
                        } else {
                            delete n[y];
                            C = true
                        }
                        if (c.allStyles) {
                            if (w)n.style = w
                        } else {
                            for (y in t)m[y] ? z.push(y + ":" + t[y]) : C = true;
                            if (z.length)n.style = z.sort().join("; ")
                        }
                        if (c.allClasses)v && (n["class"] = v); else {
                            for (y in i)i[y] && r.push(y);
                            r.length && (n["class"] =
                                r.sort().join(" "));
                            v && r.length < v.split(/\s+/).length && (C = true)
                        }
                        C && (s = true);
                        if (!p && !k(f)) {
                            h.push(f);
                            return true
                        }
                    }
                    if (o)f.name = f.name.replace(g, "cke:$1");
                    return s
                }
        }

        function h(a, b) {
            if (!b)return true;
            for (var d = 0; d < b.length; ++d)if (!(b[d]in a))return false;
            return true
        }

        function g(a) {
            if (!a)return{};
            for (var a = a.split(/\s*,\s*/).sort(), b = {}; a.length;)b[a.shift()] = r;
            return b
        }

        function d(a) {
            for (var b, d, e, g, f = {}, c = 1, a = v(a); b = a.match(B);) {
                if (d = b[2]) {
                    e = i(d, "styles");
                    g = i(d, "attrs");
                    d = i(d, "classes")
                } else e = g = d = null;
                f["$" + c++] = {elements: b[1], classes: d, styles: e, attributes: g};
                a = a.slice(b[0].length)
            }
            return f
        }

        function i(a, b) {
            var d = a.match(y[b]);
            return d ? v(d[1]) : null
        }

        function e(a) {
            if (!a.styles)a.styles = CKEDITOR.tools.parseCssText(a.attributes.style || "", 1);
            if (!a.classes)a.classes = a.attributes["class"] ? a.attributes["class"].split(/\s+/) : []
        }

        function j(a) {
            var b = a.attributes, d;
            delete b.style;
            delete b["class"];
            if (d = CKEDITOR.tools.writeCssText(a.styles, true))b.style = d;
            a.classes.length && (b["class"] = a.classes.sort().join(" "))
        }

        function k(a) {
            switch (a.name) {
                case "a":
                    if (!a.children.length && !a.attributes.name)return false;
                    break;
                case "img":
                    if (!a.attributes.src)return false
            }
            return true
        }

        function m(a) {
            return!a ? false : a === true ? true : function (b) {
                return b in a
            }
        }

        function n() {
            return new CKEDITOR.htmlParser.element("br")
        }

        function l(a) {
            return a.type == CKEDITOR.NODE_ELEMENT && (a.name == "br" || x.$block[a.name])
        }

        function o(a, b, d) {
            var e = a.name;
            if (x.$empty[e] || !a.children.length)if (e == "hr" && b == "br")a.replaceWith(n()); else {
                a.parent && d.push({check: "it",
                    el: a.parent});
                a.remove()
            } else if (x.$block[e] || e == "tr")if (b == "br") {
                if (a.previous && !l(a.previous)) {
                    b = n();
                    b.insertBefore(a)
                }
                if (a.next && !l(a.next)) {
                    b = n();
                    b.insertAfter(a)
                }
                a.replaceWithChildren()
            } else {
                var e = a.children, g;
                b:{
                    g = x[b];
                    for (var f = 0, c = e.length, i; f < c; ++f) {
                        i = e[f];
                        if (i.type == CKEDITOR.NODE_ELEMENT && !g[i.name]) {
                            g = false;
                            break b
                        }
                    }
                    g = true
                }
                if (g) {
                    a.name = b;
                    a.attributes = {};
                    d.push({check: "parent-down", el: a})
                } else {
                    g = a.parent;
                    for (var f = g.type == CKEDITOR.NODE_DOCUMENT_FRAGMENT || g.name == "body", h, c = e.length; c > 0;) {
                        i =
                            e[--c];
                        if (f && (i.type == CKEDITOR.NODE_TEXT || i.type == CKEDITOR.NODE_ELEMENT && x.$inline[i.name])) {
                            if (!h) {
                                h = new CKEDITOR.htmlParser.element(b);
                                h.insertAfter(a);
                                d.push({check: "parent-down", el: h})
                            }
                            h.add(i, 0)
                        } else {
                            h = null;
                            i.insertAfter(a);
                            g.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT && (i.type == CKEDITOR.NODE_ELEMENT && !x[g.name][i.name]) && d.push({check: "el-up", el: i})
                        }
                    }
                    a.remove()
                }
            } else if (e == "style")a.remove(); else {
                a.parent && d.push({check: "it", el: a.parent});
                a.replaceWithChildren()
            }
        }

        function q(a, b, d) {
            var e, g;
            for (e = 0; e <
                d.length; ++e) {
                g = d[e];
                if ((!g.check || a.check(g.check, false)) && (!g.left || g.left(b))) {
                    g.right(b, C);
                    break
                }
            }
        }

        function s(a, b) {
            var d = b.getDefinition(), e = d.attributes, g = d.styles, f, c, i, h;
            if (a.name != d.element)return false;
            for (f in e)if (f == "class") {
                d = e[f].split(/\s+/);
                for (i = a.classes.join("|"); h = d.pop();)if (i.indexOf(h) == -1)return false
            } else if (a.attributes[f] != e[f])return false;
            for (c in g)if (a.styles[c] != g[c])return false;
            return true
        }

        function p(a, b) {
            var d, e;
            if (typeof a == "string")d = a; else if (a instanceof CKEDITOR.style)e =
                a; else {
                d = a[0];
                e = a[1]
            }
            return[
                {element: d, left: e, right: function (a, d) {
                    d.transform(a, b)
                }}
            ]
        }

        function t(a) {
            return function (b) {
                return s(b, a)
            }
        }

        function z(a) {
            return function (b, d) {
                d[a](b)
            }
        }

        var x = CKEDITOR.dtd, w = CKEDITOR.tools.copy, v = CKEDITOR.tools.trim, r = "cke-test";
        CKEDITOR.filter = function (a) {
            this.allowedContent = [];
            this.disabled = false;
            this.editor = null;
            this.enterMode = CKEDITOR.ENTER_P;
            this._ = {rules: {}, transformations: {}, cachedTests: {}};
            if (a instanceof CKEDITOR.editor) {
                var b = this.editor = a;
                this.customConfig = true;
                var a = b.config.allowedContent, d;
                if (a === true)this.disabled = true; else {
                    if (!a)this.customConfig = false;
                    this.enterMode = d = b.blockless ? CKEDITOR.ENTER_BR : b.config.enterMode;
                    this.allow("br " + (d == CKEDITOR.ENTER_P ? "p" : d == CKEDITOR.ENTER_DIV ? "div" : ""), "default", 1);
                    this.allow(a, "config", 1);
                    this.allow(b.config.extraAllowedContent, "extra", 1);
                    this._.toHtmlListener = b.on("toHtml", function (a) {
                        this.applyTo(a.data.dataValue, true, a.data.dontFilter) && b.fire("dataFiltered")
                    }, this, null, 6);
                    this._.toDataFormatListener = b.on("toDataFormat",
                        function (a) {
                            this.applyTo(a.data.dataValue, false, true)
                        }, this, null, 11)
                }
            } else {
                this.customConfig = false;
                this.allow(a, "default", 1)
            }
        };
        CKEDITOR.filter.prototype = {allow: function (b, e, g) {
            if (this.disabled || this.customConfig && !g || !b)return false;
            this._.cachedChecks = {};
            var f, c;
            if (typeof b == "string")b = d(b); else if (b instanceof CKEDITOR.style) {
                c = b.getDefinition();
                g = {};
                b = c.attributes;
                g[c.element] = c = {styles: c.styles, requiredStyles: c.styles && CKEDITOR.tools.objectKeys(c.styles)};
                if (b) {
                    b = w(b);
                    c.classes = b["class"] ? b["class"].split(/\s+/) :
                        null;
                    c.requiredClasses = c.classes;
                    delete b["class"];
                    c.attributes = b;
                    c.requiredAttributes = b && CKEDITOR.tools.objectKeys(b)
                }
                b = g
            } else if (CKEDITOR.tools.isArray(b)) {
                for (f = 0; f < b.length; ++f)c = this.allow(b[f], e, g);
                return c
            }
            var i, g = [];
            for (i in b) {
                c = b[i];
                c = typeof c == "boolean" ? {} : typeof c == "function" ? {match: c} : w(c);
                if (i.charAt(0) != "$")c.elements = i;
                if (e)c.featureName = e.toLowerCase();
                var h = c;
                h.elements = a(h.elements, /\s+/) || null;
                h.propertiesOnly = h.propertiesOnly || h.elements === true;
                var o = /\s*,\s*/, j = void 0;
                for (j in u) {
                    h[j] =
                        a(h[j], o) || null;
                    var l = h, q = A[j], p = a(h[A[j]], o), k = h[j], n = [], s = true, t = void 0;
                    p ? s = false : p = {};
                    for (t in k)if (t.charAt(0) == "!") {
                        t = t.slice(1);
                        n.push(t);
                        p[t] = true;
                        s = false
                    }
                    for (; t = n.pop();) {
                        k[t] = k["!" + t];
                        delete k["!" + t]
                    }
                    l[q] = (s ? false : p) || null
                }
                h.match = h.match || null;
                this.allowedContent.push(c);
                g.push(c)
            }
            e = this._.rules;
            i = e.elements || {};
            b = e.generic || [];
            c = 0;
            for (h = g.length; c < h; ++c) {
                o = w(g[c]);
                j = o.classes === true || o.styles === true || o.attributes === true;
                l = o;
                q = void 0;
                for (q in u)l[q] = m(l[q]);
                p = true;
                for (q in A) {
                    q = A[q];
                    l[q] =
                        CKEDITOR.tools.objectKeys(l[q]);
                    l[q] && (p = false)
                }
                l.nothingRequired = p;
                if (o.elements === true || o.elements === null) {
                    o.elements = m(o.elements);
                    b[j ? "unshift" : "push"](o)
                } else {
                    l = o.elements;
                    delete o.elements;
                    for (f in l)if (i[f])i[f][j ? "unshift" : "push"](o); else i[f] = [o]
                }
            }
            e.elements = i;
            e.generic = b.length ? b : null;
            return true
        }, applyTo: function (a, b, d) {
            var e = [], g = !d && this._.rules, c = this._.transformations, i = f(this), h = this.editor && this.editor.config.protectedSource, j = false;
            a.forEach(function (a) {
                if (a.type == CKEDITOR.NODE_ELEMENT) {
                    if (!b || !(a.name == "span" && ~CKEDITOR.tools.objectKeys(a.attributes).join("|").indexOf("data-cke-")))i(a, g, c, e, b) && (j = true)
                } else if (a.type == CKEDITOR.NODE_COMMENT && a.value.match(/^\{cke_protected\}(?!\{C\})/)) {
                    var d;
                    a:{
                        var f = decodeURIComponent(a.value.replace(/^\{cke_protected\}/, ""));
                        d = [];
                        var o, l, q;
                        if (h)for (l = 0; l < h.length; ++l)if ((q = f.match(h[l])) && q[0].length == f.length) {
                            d = true;
                            break a
                        }
                        f = CKEDITOR.htmlParser.fragment.fromHtml(f);
                        f.children.length == 1 && (o = f.children[0]).type == CKEDITOR.NODE_ELEMENT && i(o, g, c, d, b);
                        d = !d.length
                    }
                    d || e.push(a)
                }
            }, null, true);
            e.length && (j = true);
            for (var l, q, a = [], d = ["p", "br", "div"][this.enterMode - 1]; l = e.pop();)l.type == CKEDITOR.NODE_ELEMENT ? o(l, d, a) : l.remove();
            for (; q = a.pop();) {
                l = q.el;
                if (l.parent)switch (q.check) {
                    case "it":
                        x.$removeEmpty[l.name] && !l.children.length ? o(l, d, a) : k(l) || o(l, d, a);
                        break;
                    case "el-up":
                        l.parent.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT && !x[l.parent.name][l.name] && o(l, d, a);
                        break;
                    case "parent-down":
                        l.parent.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT && !x[l.parent.name][l.name] &&
                        o(l.parent, d, a)
                }
            }
            return j
        }, checkFeature: function (a) {
            if (this.disabled || !a)return true;
            a.toFeature && (a = a.toFeature(this.editor));
            return!a.requiredContent || this.check(a.requiredContent)
        }, disable: function () {
            this.disabled = true;
            this._.toHtmlListener && this._.toHtmlListener.removeListener();
            this._.toDataFormatListener && this._.toDataFormatListener.removeListener()
        }, addContentForms: function (a) {
            if (!this.disabled && a) {
                var b, d, e = [], g;
                for (b = 0; b < a.length && !g; ++b) {
                    d = a[b];
                    if ((typeof d == "string" || d instanceof CKEDITOR.style) &&
                        this.check(d))g = d
                }
                if (g) {
                    for (b = 0; b < a.length; ++b)e.push(p(a[b], g));
                    this.addTransformations(e)
                }
            }
        }, addFeature: function (a) {
            if (this.disabled || !a)return true;
            a.toFeature && (a = a.toFeature(this.editor));
            this.allow(a.allowedContent, a.name);
            this.addTransformations(a.contentTransformations);
            this.addContentForms(a.contentForms);
            return this.customConfig && a.requiredContent ? this.check(a.requiredContent) : true
        }, addTransformations: function (a) {
            var b, d;
            if (!this.disabled && a) {
                var e = this._.transformations, g;
                for (g = 0; g < a.length; ++g) {
                    b =
                        a[g];
                    var f = void 0, c = void 0, i = void 0, h = void 0, o = void 0, j = void 0;
                    d = [];
                    for (c = 0; c < b.length; ++c) {
                        i = b[c];
                        if (typeof i == "string") {
                            i = i.split(/\s*:\s*/);
                            h = i[0];
                            o = null;
                            j = i[1]
                        } else {
                            h = i.check;
                            o = i.left;
                            j = i.right
                        }
                        if (!f) {
                            f = i;
                            f = f.element ? f.element : h ? h.match(/^([a-z0-9]+)/i)[0] : f.left.getDefinition().element
                        }
                        o instanceof CKEDITOR.style && (o = t(o));
                        d.push({check: h == f ? null : h, left: o, right: typeof j == "string" ? z(j) : j})
                    }
                    b = f;
                    e[b] || (e[b] = []);
                    e[b].push(d)
                }
            }
        }, check: function (a, b, e) {
            if (this.disabled)return true;
            if (CKEDITOR.tools.isArray(a)) {
                for (var c =
                    a.length; c--;)if (this.check(a[c], b, e))return true;
                return false
            }
            var i, h;
            if (typeof a == "string") {
                h = a + "<" + (b === false ? "0" : "1") + (e ? "1" : "0") + ">";
                if (h in this._.cachedChecks)return this._.cachedChecks[h];
                c = d(a).$1;
                i = c.styles;
                var o = c.classes;
                c.name = c.elements;
                c.classes = o = o ? o.split(/\s*,\s*/) : [];
                c.styles = g(i);
                c.attributes = g(c.attributes);
                c.children = [];
                o.length && (c.attributes["class"] = o.join(" "));
                if (i)c.attributes.style = CKEDITOR.tools.writeCssText(c.styles);
                i = c
            } else {
                c = a.getDefinition();
                i = c.styles;
                o = c.attributes ||
                {};
                if (i) {
                    i = w(i);
                    o.style = CKEDITOR.tools.writeCssText(i, true)
                } else i = {};
                i = {name: c.element, attributes: o, classes: o["class"] ? o["class"].split(/\s+/) : [], styles: i, children: []}
            }
            var o = CKEDITOR.tools.clone(i), l = [], p;
            if (b !== false && (p = this._.transformations[i.name])) {
                for (c = 0; c < p.length; ++c)q(this, i, p[c]);
                j(i)
            }
            f(this)(o, this._.rules, b === false ? false : this._.transformations, l, false, !e, !e);
            b = l.length > 0 ? false : CKEDITOR.tools.objectCompare(i.attributes, o.attributes, true) ? true : false;
            typeof a == "string" && (this._.cachedChecks[h] =
                b);
            return b
        }};
        var u = {styles: 1, attributes: 1, classes: 1}, A = {styles: "requiredStyles", attributes: "requiredAttributes", classes: "requiredClasses"}, B = /^([a-z0-9*\s]+)((?:\s*\{[!\w\-,\s\*]+\}\s*|\s*\[[!\w\-,\s\*]+\]\s*|\s*\([!\w\-,\s\*]+\)\s*){0,3})(?:;\s*|$)/i, y = {styles: /{([^}]+)}/, attrs: /\[([^\]]+)\]/, classes: /\(([^\)]+)\)/}, C = CKEDITOR.filter.transformationsTools = {sizeToStyle: function (a) {
            this.lengthToStyle(a, "width");
            this.lengthToStyle(a, "height")
        }, sizeToAttribute: function (a) {
            this.lengthToAttribute(a, "width");
            this.lengthToAttribute(a, "height")
        }, lengthToStyle: function (a, b, d) {
            d = d || b;
            if (!(d in a.styles)) {
                var e = a.attributes[b];
                if (e) {
                    /^\d+$/.test(e) && (e = e + "px");
                    a.styles[d] = e
                }
            }
            delete a.attributes[b]
        }, lengthToAttribute: function (a, b, d) {
            d = d || b;
            if (!(d in a.attributes)) {
                var e = a.styles[b], g = e && e.match(/^(\d+)(?:\.\d*)?px$/);
                g ? a.attributes[d] = g[1] : e == r && (a.attributes[d] = r)
            }
            delete a.styles[b]
        }, alignmentToStyle: function (a) {
            if (!("float"in a.styles)) {
                var b = a.attributes.align;
                if (b == "left" || b == "right")a.styles["float"] =
                    b
            }
            delete a.attributes.align
        }, alignmentToAttribute: function (a) {
            if (!("align"in a.attributes)) {
                var b = a.styles["float"];
                if (b == "left" || b == "right")a.attributes.align = b
            }
            delete a.styles["float"]
        }, matchesStyle: s, transform: function (a, b) {
            if (typeof b == "string")a.name = b; else {
                var d = b.getDefinition(), e = d.styles, g = d.attributes, c, f, i, h;
                a.name = d.element;
                for (c in g)if (c == "class") {
                    d = a.classes.join("|");
                    for (i = g[c].split(/\s+/); h = i.pop();)d.indexOf(h) == -1 && a.classes.push(h)
                } else a.attributes[c] = g[c];
                for (f in e)a.styles[f] =
                    e[f]
            }
        }}
    }(), function () {
        CKEDITOR.focusManager = function (b) {
            if (b.focusManager)return b.focusManager;
            this.hasFocus = false;
            this.currentActive = null;
            this._ = {editor: b};
            return this
        };
        CKEDITOR.focusManager._ = {blurDelay: 200};
        CKEDITOR.focusManager.prototype = {focus: function () {
            this._.timer && clearTimeout(this._.timer);
            if (!this.hasFocus && !this._.locked) {
                var b = CKEDITOR.currentInstance;
                b && b.focusManager.blur(1);
                this.hasFocus = true;
                (b = this._.editor.container) && b.addClass("cke_focus");
                this._.editor.fire("focus")
            }
        }, lock: function () {
            this._.locked =
                1
        }, unlock: function () {
            delete this._.locked
        }, blur: function (b) {
            function c() {
                if (this.hasFocus) {
                    this.hasFocus = false;
                    var a = this._.editor.container;
                    a && a.removeClass("cke_focus");
                    this._.editor.fire("blur")
                }
            }

            if (!this._.locked) {
                this._.timer && clearTimeout(this._.timer);
                var a = CKEDITOR.focusManager._.blurDelay;
                b || !a ? c.call(this) : this._.timer = CKEDITOR.tools.setTimeout(function () {
                    delete this._.timer;
                    c.call(this)
                }, a, this)
            }
        }, add: function (b, c) {
            var a = b.getCustomData("focusmanager");
            if (!a || a != this) {
                a && a.remove(b);
                var a =
                    "focus", f = "blur";
                if (c)if (CKEDITOR.env.ie) {
                    a = "focusin";
                    f = "focusout"
                } else CKEDITOR.event.useCapture = 1;
                var h = {blur: function () {
                    b.equals(this.currentActive) && this.blur()
                }, focus: function () {
                    this.currentActive = b;
                    this.focus()
                }};
                b.on(a, h.focus, this);
                b.on(f, h.blur, this);
                if (c)CKEDITOR.event.useCapture = 0;
                b.setCustomData("focusmanager", this);
                b.setCustomData("focusmanager_handlers", h)
            }
        }, remove: function (b) {
            b.removeCustomData("focusmanager");
            var c = b.removeCustomData("focusmanager_handlers");
            b.removeListener("blur",
                c.blur);
            b.removeListener("focus", c.focus)
        }}
    }(), CKEDITOR.keystrokeHandler = function (b) {
        if (b.keystrokeHandler)return b.keystrokeHandler;
        this.keystrokes = {};
        this.blockedKeystrokes = {};
        this._ = {editor: b};
        return this
    }, function () {
        var b, c = function (a) {
            var a = a.data, c = a.getKeystroke(), g = this.keystrokes[c], d = this._.editor;
            b = d.fire("key", {keyCode: c}) === false;
            if (!b) {
                g && (b = d.execCommand(g, {from: "keystrokeHandler"}) !== false);
                b || (b = !!this.blockedKeystrokes[c])
            }
            b && a.preventDefault(true);
            return!b
        }, a = function (a) {
            if (b) {
                b =
                    false;
                a.data.preventDefault(true)
            }
        };
        CKEDITOR.keystrokeHandler.prototype = {attach: function (b) {
            b.on("keydown", c, this);
            if (CKEDITOR.env.opera || CKEDITOR.env.gecko && CKEDITOR.env.mac)b.on("keypress", a, this)
        }}
    }(), function () {
        CKEDITOR.lang = {languages: {af: 1, ar: 1, bg: 1, bn: 1, bs: 1, ca: 1, cs: 1, cy: 1, da: 1, de: 1, el: 1, "en-au": 1, "en-ca": 1, "en-gb": 1, en: 1, eo: 1, es: 1, et: 1, eu: 1, fa: 1, fi: 1, fo: 1, "fr-ca": 1, fr: 1, gl: 1, gu: 1, he: 1, hi: 1, hr: 1, hu: 1, is: 1, it: 1, ja: 1, ka: 1, km: 1, ko: 1, ku: 1, lt: 1, lv: 1, mk: 1, mn: 1, ms: 1, nb: 1, nl: 1, no: 1, pl: 1, "pt-br": 1,
            pt: 1, ro: 1, ru: 1, sk: 1, sl: 1, sq: 1, "sr-latn": 1, sr: 1, sv: 1, th: 1, tr: 1, ug: 1, uk: 1, vi: 1, "zh-cn": 1, zh: 1}, load: function (b, c, a) {
            if (!b || !CKEDITOR.lang.languages[b])b = this.detect(c, b);
            this[b] ? a(b, this[b]) : CKEDITOR.scriptLoader.load(CKEDITOR.getUrl("lang/" + b + ".js"), function () {
                a(b, this[b])
            }, this)
        }, detect: function (b, c) {
            var a = this.languages, c = c || navigator.userLanguage || navigator.language || b, f = c.toLowerCase().match(/([a-z]+)(?:-([a-z]+))?/), h = f[1], f = f[2];
            a[h + "-" + f] ? h = h + "-" + f : a[h] || (h = null);
            CKEDITOR.lang.detect = h ? function () {
                return h
            } :
                function (a) {
                    return a
                };
            return h || b
        }}
    }(), CKEDITOR.scriptLoader = function () {
        var b = {}, c = {};
        return{load: function (a, f, h, g) {
            var d = typeof a == "string";
            d && (a = [a]);
            h || (h = CKEDITOR);
            var i = a.length, e = [], j = [], k = function (a) {
                f && (d ? f.call(h, a) : f.call(h, e, j))
            };
            if (i === 0)k(true); else {
                var m = function (a, b) {
                    (b ? e : j).push(a);
                    if (--i <= 0) {
                        g && CKEDITOR.document.getDocumentElement().removeStyle("cursor");
                        k(b)
                    }
                }, n = function (a, d) {
                    b[a] = 1;
                    var e = c[a];
                    delete c[a];
                    for (var g = 0; g < e.length; g++)e[g](a, d)
                }, l = function (a) {
                    if (b[a])m(a, true); else {
                        var d =
                            c[a] || (c[a] = []);
                        d.push(m);
                        if (!(d.length > 1)) {
                            var e = new CKEDITOR.dom.element("script");
                            e.setAttributes({type: "text/javascript", src: a});
                            if (f)if (CKEDITOR.env.ie)e.$.onreadystatechange = function () {
                                if (e.$.readyState == "loaded" || e.$.readyState == "complete") {
                                    e.$.onreadystatechange = null;
                                    n(a, true)
                                }
                            }; else {
                                e.$.onload = function () {
                                    setTimeout(function () {
                                        n(a, true)
                                    }, 0)
                                };
                                e.$.onerror = function () {
                                    n(a, false)
                                }
                            }
                            e.appendTo(CKEDITOR.document.getHead())
                        }
                    }
                };
                g && CKEDITOR.document.getDocumentElement().setStyle("cursor", "wait");
                for (var o =
                    0; o < i; o++)l(a[o])
            }
        }}
    }(), CKEDITOR.resourceManager = function (b, c) {
        this.basePath = b;
        this.fileName = c;
        this.registered = {};
        this.loaded = {};
        this.externals = {};
        this._ = {waitingList: {}}
    }, CKEDITOR.resourceManager.prototype = {add: function (b, c) {
        if (this.registered[b])throw'[CKEDITOR.resourceManager.add] The resource name "' + b + '" is already registered.';
        var a = this.registered[b] = c || {};
        a.name = b;
        a.path = this.getPath(b);
        CKEDITOR.fire(b + CKEDITOR.tools.capitalize(this.fileName) + "Ready", a);
        return this.get(b)
    }, get: function (b) {
        return this.registered[b] ||
            null
    }, getPath: function (b) {
        var c = this.externals[b];
        return CKEDITOR.getUrl(c && c.dir || this.basePath + b + "/")
    }, getFilePath: function (b) {
        var c = this.externals[b];
        return CKEDITOR.getUrl(this.getPath(b) + (c && typeof c.file == "string" ? c.file : this.fileName + ".js"))
    }, addExternal: function (b, c, a) {
        for (var b = b.split(","), f = 0; f < b.length; f++)this.externals[b[f]] = {dir: c, file: a}
    }, load: function (b, c, a) {
        CKEDITOR.tools.isArray(b) || (b = b ? [b] : []);
        for (var f = this.loaded, h = this.registered, g = [], d = {}, i = {}, e = 0; e < b.length; e++) {
            var j = b[e];
            if (j)if (!f[j] && !h[j]) {
                var k = this.getFilePath(j);
                g.push(k);
                k in d || (d[k] = []);
                d[k].push(j)
            } else i[j] = this.get(j)
        }
        CKEDITOR.scriptLoader.load(g, function (b, e) {
            if (e.length)throw'[CKEDITOR.resourceManager.load] Resource name "' + d[e[0]].join(",") + '" was not found at "' + e[0] + '".';
            for (var g = 0; g < b.length; g++)for (var h = d[b[g]], j = 0; j < h.length; j++) {
                var k = h[j];
                i[k] = this.get(k);
                f[k] = 1
            }
            c.call(a, i)
        }, this)
    }}, CKEDITOR.plugins = new CKEDITOR.resourceManager("plugins/", "plugin"), CKEDITOR.plugins.load = CKEDITOR.tools.override(CKEDITOR.plugins.load,
        function (b) {
            var c = {};
            return function (a, f, h) {
                var g = {}, d = function (a) {
                    b.call(this, a, function (a) {
                        CKEDITOR.tools.extend(g, a);
                        var b = [], i;
                        for (i in a) {
                            var m = a[i], n = m && m.requires;
                            if (!c[i]) {
                                if (m.icons)for (var l = m.icons.split(","), o = 0; o < l.length; o++)CKEDITOR.skin.addIcon(l[o], m.path + "icons/" + l[o] + ".png");
                                c[i] = 1
                            }
                            if (n) {
                                n.split && (n = n.split(","));
                                for (m = 0; m < n.length; m++)g[n[m]] || b.push(n[m])
                            }
                        }
                        if (b.length)d.call(this, b); else {
                            for (i in g) {
                                m = g[i];
                                if (m.onLoad && !m.onLoad._called) {
                                    m.onLoad() === false && delete g[i];
                                    m.onLoad._called =
                                        1
                                }
                            }
                            f && f.call(h || window, g)
                        }
                    }, this)
                };
                d.call(this, a)
            }
        }), CKEDITOR.plugins.setLang = function (b, c, a) {
        var f = this.get(b), b = f.langEntries || (f.langEntries = {}), f = f.lang || (f.lang = []);
        f.split && (f = f.split(","));
        CKEDITOR.tools.indexOf(f, c) == -1 && f.push(c);
        b[c] = a
    }, CKEDITOR.ui = function (b) {
        if (b.ui)return b.ui;
        this.items = {};
        this.instances = {};
        this.editor = b;
        this._ = {handlers: {}};
        return this
    }, CKEDITOR.ui.prototype = {add: function (b, c, a) {
        a.name = b.toLowerCase();
        var f = this.items[b] = {type: c, command: a.command || null, args: Array.prototype.slice.call(arguments,
            2)};
        CKEDITOR.tools.extend(f, a)
    }, get: function (b) {
        return this.instances[b]
    }, create: function (b) {
        var c = this.items[b], a = c && this._.handlers[c.type], f = c && c.command && this.editor.getCommand(c.command), a = a && a.create.apply(this, c.args);
        this.instances[b] = a;
        f && f.uiItems.push(a);
        if (a && !a.type)a.type = c.type;
        return a
    }, addHandler: function (b, c) {
        this._.handlers[b] = c
    }, space: function (b) {
        return CKEDITOR.document.getById(this.spaceId(b))
    }, spaceId: function (b) {
        return this.editor.id + "_" + b
    }}, CKEDITOR.event.implementOn(CKEDITOR.ui),
        function () {
            function b(b, e, g) {
                CKEDITOR.event.call(this);
                b = b && CKEDITOR.tools.clone(b);
                if (e !== void 0) {
                    if (e instanceof CKEDITOR.dom.element) {
                        if (!g)throw Error("One of the element modes must be specified.");
                    } else throw Error("Expect element of type CKEDITOR.dom.element.");
                    if (CKEDITOR.env.ie && CKEDITOR.env.quirks && g == CKEDITOR.ELEMENT_MODE_INLINE)throw Error("Inline element mode is not supported on IE quirks.");
                    if (g == CKEDITOR.ELEMENT_MODE_INLINE && !e.is(CKEDITOR.dtd.$editable) || g == CKEDITOR.ELEMENT_MODE_REPLACE &&
                        e.is(CKEDITOR.dtd.$nonBodyContent))throw Error('The specified element mode is not supported on element: "' + e.getName() + '".');
                    this.element = e;
                    this.elementMode = g;
                    this.name = this.elementMode != CKEDITOR.ELEMENT_MODE_APPENDTO && (e.getId() || e.getNameAtt())
                } else this.elementMode = CKEDITOR.ELEMENT_MODE_NONE;
                this._ = {};
                this.commands = {};
                this.templates = {};
                this.name = this.name || c();
                this.id = CKEDITOR.tools.getNextId();
                this.status = "unloaded";
                this.config = CKEDITOR.tools.prototypedCopy(CKEDITOR.config);
                this.ui = new CKEDITOR.ui(this);
                this.focusManager = new CKEDITOR.focusManager(this);
                this.keystrokeHandler = new CKEDITOR.keystrokeHandler(this);
                this.on("readOnly", a);
                this.on("selectionChange", h);
                this.on("mode", a);
                this.on("instanceReady", function () {
                    this.config.startupFocus && this.focus()
                });
                CKEDITOR.fire("instanceCreated", null, this);
                CKEDITOR.add(this);
                CKEDITOR.tools.setTimeout(function () {
                    d(this, b)
                }, 0, this)
            }

            function c() {
                do var a = "editor" + ++n; while (CKEDITOR.instances[a]);
                return a
            }

            function a() {
                var a = this.commands, b;
                for (b in a)f(this, a[b])
            }

            function f(a, b) {
                b[b.startDisabled ? "disable" : a.readOnly && !b.readOnly ? "disable" : b.modes[a.mode] ? "enable" : "disable"]()
            }

            function h(a) {
                var b = this.commands, d = a.editor, e = a.data.path, g;
                for (g in b) {
                    a = b[g];
                    a.contextSensitive && a.refresh(d, e)
                }
            }

            function g(a) {
                var b = a.config.customConfig;
                if (!b)return false;
                var b = CKEDITOR.getUrl(b), d = l[b] || (l[b] = {});
                if (d.fn) {
                    d.fn.call(a, a.config);
                    (CKEDITOR.getUrl(a.config.customConfig) == b || !g(a)) && a.fireOnce("customConfigLoaded")
                } else CKEDITOR.scriptLoader.load(b, function () {
                    d.fn =
                        CKEDITOR.editorConfig ? CKEDITOR.editorConfig : function () {
                        };
                    g(a)
                });
                return true
            }

            function d(a, b) {
                a.on("customConfigLoaded", function () {
                    if (b) {
                        if (b.on)for (var d in b.on)a.on(d, b.on[d]);
                        CKEDITOR.tools.extend(a.config, b, true);
                        delete a.config.on
                    }
                    a.readOnly = !(!a.config.readOnly && !(a.elementMode == CKEDITOR.ELEMENT_MODE_INLINE ? a.element.isReadOnly() : a.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE && a.element.getAttribute("disabled")));
                    a.blockless = a.elementMode == CKEDITOR.ELEMENT_MODE_INLINE && !CKEDITOR.dtd[a.element.getName()].p;
                    a.tabIndex = a.config.tabIndex || a.element && a.element.getAttribute("tabindex") || 0;
                    if (a.config.skin)CKEDITOR.skinName = a.config.skin;
                    a.fireOnce("configLoaded");
                    a.dataProcessor = new CKEDITOR.htmlDataProcessor(a);
                    a.filter = new CKEDITOR.filter(a);
                    i(a)
                });
                if (b && b.customConfig != void 0)a.config.customConfig = b.customConfig;
                g(a) || a.fireOnce("customConfigLoaded")
            }

            function i(a) {
                CKEDITOR.skin.loadPart("editor", function () {
                    e(a)
                })
            }

            function e(a) {
                CKEDITOR.lang.load(a.config.language, a.config.defaultLanguage, function (b, d) {
                    a.langCode =
                        b;
                    a.lang = CKEDITOR.tools.prototypedCopy(d);
                    if (CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 && a.lang.dir == "rtl")a.lang.dir = "ltr";
                    if (!a.config.contentsLangDirection)a.config.contentsLangDirection = a.elementMode == CKEDITOR.ELEMENT_MODE_INLINE ? a.element.getDirection(1) : a.lang.dir;
                    a.fire("langLoaded");
                    j(a)
                })
            }

            function j(a) {
                a.getStylesSet(function (b) {
                    a.once("loaded", function () {
                        a.fire("stylesSet", {styles: b})
                    }, null, null, 1);
                    k(a)
                })
            }

            function k(a) {
                var b = a.config, d = b.plugins, e = b.extraPlugins, g = b.removePlugins;
                if (e)var c =
                    RegExp("(?:^|,)(?:" + e.replace(/\s*,\s*/g, "|") + ")(?=,|$)", "g"), d = d.replace(c, ""), d = d + ("," + e);
                if (g)var f = RegExp("(?:^|,)(?:" + g.replace(/\s*,\s*/g, "|") + ")(?=,|$)", "g"), d = d.replace(f, "");
                CKEDITOR.env.air && (d = d + ",adobeair");
                CKEDITOR.plugins.load(d.split(","), function (d) {
                    var e = [], g = [], c = [];
                    a.plugins = d;
                    for (var i in d) {
                        var h = d[i], j = h.lang, l = null, m = h.requires, p;
                        CKEDITOR.tools.isArray(m) && (m = m.join(","));
                        if (m && (p = m.match(f)))for (; m = p.pop();)CKEDITOR.tools.setTimeout(function (a, b) {
                            throw Error('Plugin "' + a.replace(",",
                                "") + '" cannot be removed from the plugins list, because it\'s required by "' + b + '" plugin.');
                        }, 0, null, [m, i]);
                        if (j && !a.lang[i]) {
                            j.split && (j = j.split(","));
                            if (CKEDITOR.tools.indexOf(j, a.langCode) >= 0)l = a.langCode; else {
                                l = a.langCode.replace(/-.*/, "");
                                l = l != a.langCode && CKEDITOR.tools.indexOf(j, l) >= 0 ? l : CKEDITOR.tools.indexOf(j, "en") >= 0 ? "en" : j[0]
                            }
                            if (!h.langEntries || !h.langEntries[l])c.push(CKEDITOR.getUrl(h.path + "lang/" + l + ".js")); else {
                                a.lang[i] = h.langEntries[l];
                                l = null
                            }
                        }
                        g.push(l);
                        e.push(h)
                    }
                    CKEDITOR.scriptLoader.load(c,
                        function () {
                            for (var d = ["beforeInit", "init", "afterInit"], c = 0; c < d.length; c++)for (var f = 0; f < e.length; f++) {
                                var i = e[f];
                                c === 0 && (g[f] && i.lang && i.langEntries) && (a.lang[i.name] = i.langEntries[g[f]]);
                                if (i[d[c]])i[d[c]](a)
                            }
                            a.fireOnce("pluginsLoaded");
                            b.keystrokes && a.setKeystroke(a.config.keystrokes);
                            for (f = 0; f < a.config.blockedKeystrokes.length; f++)a.keystrokeHandler.blockedKeystrokes[a.config.blockedKeystrokes[f]] = 1;
                            a.status = "loaded";
                            a.fireOnce("loaded");
                            CKEDITOR.fire("instanceLoaded", null, a)
                        })
                })
            }

            function m() {
                var a =
                    this.element;
                if (a && this.elementMode != CKEDITOR.ELEMENT_MODE_APPENDTO) {
                    var b = this.getData();
                    this.config.htmlEncodeOutput && (b = CKEDITOR.tools.htmlEncode(b));
                    a.is("textarea") ? a.setValue(b) : a.setHtml(b);
                    return true
                }
                return false
            }

            b.prototype = CKEDITOR.editor.prototype;
            CKEDITOR.editor = b;
            var n = 0, l = {};
            CKEDITOR.tools.extend(CKEDITOR.editor.prototype, {addCommand: function (a, b) {
                b.name = a.toLowerCase();
                var d = new CKEDITOR.command(this, b);
                this.mode && f(this, d);
                return this.commands[a] = d
            }, destroy: function (a) {
                this.fire("beforeDestroy");
                !a && m.call(this);
                this.editable(null);
                this.status = "destroyed";
                this.fire("destroy");
                this.removeAllListeners();
                CKEDITOR.remove(this);
                CKEDITOR.fire("instanceDestroyed", null, this)
            }, elementPath: function (a) {
                return(a = a || this.getSelection().getStartElement()) ? new CKEDITOR.dom.elementPath(a, this.editable()) : null
            }, createRange: function () {
                var a = this.editable();
                return a ? new CKEDITOR.dom.range(a) : null
            }, execCommand: function (a, b) {
                var d = this.getCommand(a), e = {name: a, commandData: b, command: d};
                if (d && d.state != CKEDITOR.TRISTATE_DISABLED &&
                    this.fire("beforeCommandExec", e) !== true) {
                    e.returnValue = d.exec(e.commandData);
                    if (!d.async && this.fire("afterCommandExec", e) !== true)return e.returnValue
                }
                return false
            }, getCommand: function (a) {
                return this.commands[a]
            }, getData: function (a) {
                !a && this.fire("beforeGetData");
                var b = this._.data;
                if (typeof b != "string")b = (b = this.element) && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE ? b.is("textarea") ? b.getValue() : b.getHtml() : "";
                b = {dataValue: b};
                !a && this.fire("getData", b);
                return b.dataValue
            }, getSnapshot: function () {
                var a =
                    this.fire("getSnapshot");
                if (typeof a != "string") {
                    var b = this.element;
                    b && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE && (a = b.is("textarea") ? b.getValue() : b.getHtml())
                }
                return a
            }, loadSnapshot: function (a) {
                this.fire("loadSnapshot", a)
            }, setData: function (a, b, d) {
                if (b)this.on("dataReady", function (a) {
                    a.removeListener();
                    b.call(a.editor)
                });
                a = {dataValue: a};
                !d && this.fire("setData", a);
                this._.data = a.dataValue;
                !d && this.fire("afterSetData", a)
            }, setReadOnly: function (a) {
                a = a == void 0 || a;
                if (this.readOnly != a) {
                    this.readOnly =
                        a;
                    this.editable().setReadOnly(a);
                    this.fire("readOnly")
                }
            }, insertHtml: function (a, b) {
                this.fire("insertHtml", {dataValue: a, mode: b})
            }, insertText: function (a) {
                this.fire("insertText", a)
            }, insertElement: function (a) {
                this.fire("insertElement", a)
            }, focus: function () {
                this.fire("beforeFocus")
            }, checkDirty: function () {
                return this.status == "ready" && this._.previousValue !== this.getSnapshot()
            }, resetDirty: function () {
                this._.previousValue = this.getSnapshot()
            }, updateElement: function () {
                return m.call(this)
            }, setKeystroke: function () {
                for (var a =
                    this.keystrokeHandler.keystrokes, b = CKEDITOR.tools.isArray(arguments[0]) ? arguments[0] : [[].slice.call(arguments, 0)], d, e, g = b.length; g--;) {
                    d = b[g];
                    e = 0;
                    if (CKEDITOR.tools.isArray(d)) {
                        e = d[1];
                        d = d[0]
                    }
                    e ? a[d] = e : delete a[d]
                }
            }, addFeature: function (a) {
                return this.filter.addFeature(a)
            }})
        }(), CKEDITOR.ELEMENT_MODE_NONE = 0, CKEDITOR.ELEMENT_MODE_REPLACE = 1, CKEDITOR.ELEMENT_MODE_APPENDTO = 2, CKEDITOR.ELEMENT_MODE_INLINE = 3, CKEDITOR.htmlParser = function () {
        this._ = {htmlPartsRegex: RegExp("<(?:(?:\\/([^>]+)>)|(?:!--([\\S|\\s]*?)--\>)|(?:([^\\s>]+)\\s*((?:(?:\"[^\"]*\")|(?:'[^']*')|[^\"'>])*)\\/?>))",
            "g")}
    }, function () {
        var b = /([\w\-:.]+)(?:(?:\s*=\s*(?:(?:"([^"]*)")|(?:'([^']*)')|([^\s>]+)))|(?=\s|$))/g, c = {checked: 1, compact: 1, declare: 1, defer: 1, disabled: 1, ismap: 1, multiple: 1, nohref: 1, noresize: 1, noshade: 1, nowrap: 1, readonly: 1, selected: 1};
        CKEDITOR.htmlParser.prototype = {onTagOpen: function () {
        }, onTagClose: function () {
        }, onText: function () {
        }, onCDATA: function () {
        }, onComment: function () {
        }, parse: function (a) {
            for (var f, h, g = 0, d; f = this._.htmlPartsRegex.exec(a);) {
                h = f.index;
                if (h > g) {
                    g = a.substring(g, h);
                    if (d)d.push(g);
                    else this.onText(g)
                }
                g = this._.htmlPartsRegex.lastIndex;
                if (h = f[1]) {
                    h = h.toLowerCase();
                    if (d && CKEDITOR.dtd.$cdata[h]) {
                        this.onCDATA(d.join(""));
                        d = null
                    }
                    if (!d) {
                        this.onTagClose(h);
                        continue
                    }
                }
                if (d)d.push(f[0]); else if (h = f[3]) {
                    h = h.toLowerCase();
                    if (!/="/.test(h)) {
                        var i = {}, e;
                        f = f[4];
                        var j = !!(f && f.charAt(f.length - 1) == "/");
                        if (f)for (; e = b.exec(f);) {
                            var k = e[1].toLowerCase();
                            e = e[2] || e[3] || e[4] || "";
                            i[k] = !e && c[k] ? k : e
                        }
                        this.onTagOpen(h, i, j);
                        !d && CKEDITOR.dtd.$cdata[h] && (d = [])
                    }
                } else if (h = f[2])this.onComment(h)
            }
            if (a.length >
                g)this.onText(a.substring(g, a.length))
        }}
    }(), CKEDITOR.htmlParser.basicWriter = CKEDITOR.tools.createClass({$: function () {
        this._ = {output: []}
    }, proto: {openTag: function (b) {
        this._.output.push("<", b)
    }, openTagClose: function (b, c) {
        c ? this._.output.push(" />") : this._.output.push(">")
    }, attribute: function (b, c) {
        typeof c == "string" && (c = CKEDITOR.tools.htmlEncodeAttr(c));
        this._.output.push(" ", b, '="', c, '"')
    }, closeTag: function (b) {
        this._.output.push("</", b, ">")
    }, text: function (b) {
        this._.output.push(b)
    }, comment: function (b) {
        this._.output.push("<\!--",
            b, "--\>")
    }, write: function (b) {
        this._.output.push(b)
    }, reset: function () {
        this._.output = [];
        this._.indent = false
    }, getHtml: function (b) {
        var c = this._.output.join("");
        b && this.reset();
        return c
    }}}), "use strict", function () {
        CKEDITOR.htmlParser.node = function () {
        };
        CKEDITOR.htmlParser.node.prototype = {remove: function () {
            var b = this.parent.children, c = CKEDITOR.tools.indexOf(b, this), a = this.previous, f = this.next;
            a && (a.next = f);
            f && (f.previous = a);
            b.splice(c, 1);
            this.parent = null
        }, replaceWith: function (b) {
            var c = this.parent.children,
                a = CKEDITOR.tools.indexOf(c, this), f = b.previous = this.previous, h = b.next = this.next;
            f && (f.next = b);
            h && (h.previous = b);
            c[a] = b;
            b.parent = this.parent;
            this.parent = null
        }, insertAfter: function (b) {
            var c = b.parent.children, a = CKEDITOR.tools.indexOf(c, b), f = b.next;
            c.splice(a + 1, 0, this);
            this.next = b.next;
            this.previous = b;
            b.next = this;
            f && (f.previous = this);
            this.parent = b.parent
        }, insertBefore: function (b) {
            var c = b.parent.children, a = CKEDITOR.tools.indexOf(c, b);
            c.splice(a, 0, this);
            this.next = b;
            (this.previous = b.previous) && (b.previous.next =
                this);
            b.previous = this;
            this.parent = b.parent
        }}
    }(), "use strict", CKEDITOR.htmlParser.comment = function (b) {
        this.value = b;
        this._ = {isBlockLike: false}
    }, CKEDITOR.htmlParser.comment.prototype = CKEDITOR.tools.extend(new CKEDITOR.htmlParser.node, {type: CKEDITOR.NODE_COMMENT, filter: function (b) {
        var c = this.value;
        if (!(c = b.onComment(c, this))) {
            this.remove();
            return false
        }
        if (typeof c != "string") {
            this.replaceWith(c);
            return false
        }
        this.value = c;
        return true
    }, writeHtml: function (b, c) {
        c && this.filter(c);
        b.comment(this.value)
    }}), "use strict",
        function () {
            CKEDITOR.htmlParser.text = function (b) {
                this.value = b;
                this._ = {isBlockLike: false}
            };
            CKEDITOR.htmlParser.text.prototype = CKEDITOR.tools.extend(new CKEDITOR.htmlParser.node, {type: CKEDITOR.NODE_TEXT, filter: function (b) {
                if (!(this.value = b.onText(this.value, this))) {
                    this.remove();
                    return false
                }
            }, writeHtml: function (b, c) {
                c && this.filter(c);
                b.text(this.value)
            }})
        }(), "use strict", function () {
        CKEDITOR.htmlParser.cdata = function (b) {
            this.value = b
        };
        CKEDITOR.htmlParser.cdata.prototype = CKEDITOR.tools.extend(new CKEDITOR.htmlParser.node,
            {type: CKEDITOR.NODE_TEXT, filter: function () {
            }, writeHtml: function (b) {
                b.write(this.value)
            }})
    }(), "use strict", CKEDITOR.htmlParser.fragment = function () {
        this.children = [];
        this.parent = null;
        this._ = {isBlockLike: true, hasInlineStarted: false}
    }, function () {
        function b(a) {
            return a.name == "a" && a.attributes.href || CKEDITOR.dtd.$removeEmpty[a.name]
        }

        var c = CKEDITOR.tools.extend({table: 1, ul: 1, ol: 1, dl: 1}, CKEDITOR.dtd.table, CKEDITOR.dtd.ul, CKEDITOR.dtd.ol, CKEDITOR.dtd.dl), a = {ol: 1, ul: 1}, f = CKEDITOR.tools.extend({}, {html: 1}, CKEDITOR.dtd.html,
            CKEDITOR.dtd.body, CKEDITOR.dtd.head, {style: 1, script: 1});
        CKEDITOR.htmlParser.fragment.fromHtml = function (h, g, d) {
            function i(a) {
                var b;
                if (q.length > 0)for (var d = 0; d < q.length; d++) {
                    var g = q[d], c = g.name, f = CKEDITOR.dtd[c], i = p.name && CKEDITOR.dtd[p.name];
                    if ((!i || i[c]) && (!a || !f || f[a] || !CKEDITOR.dtd[a])) {
                        if (!b) {
                            e();
                            b = 1
                        }
                        g = g.clone();
                        g.parent = p;
                        p = g;
                        q.splice(d, 1);
                        d--
                    } else if (c == p.name) {
                        k(p, p.parent, 1);
                        d--
                    }
                }
            }

            function e() {
                for (; s.length;)k(s.shift(), p)
            }

            function j(a) {
                if (a._.isBlockLike && a.name != "pre" && a.name != "textarea") {
                    var b =
                        a.children.length, d = a.children[b - 1], e;
                    if (d && d.type == CKEDITOR.NODE_TEXT)(e = CKEDITOR.tools.rtrim(d.value)) ? d.value = e : a.children.length = b - 1
                }
            }

            function k(a, e, g) {
                var e = e || p || o, c = p;
                if (a.previous === void 0) {
                    if (m(e, a)) {
                        p = e;
                        l.onTagOpen(d, {});
                        a.returnPoint = e = p
                    }
                    j(a);
                    (!b(a) || a.children.length) && e.add(a);
                    a.name == "pre" && (z = false);
                    a.name == "textarea" && (t = false)
                }
                if (a.returnPoint) {
                    p = a.returnPoint;
                    delete a.returnPoint
                } else p = g ? e : c
            }

            function m(a, b) {
                if ((a == o || a.name == "body") && d && (!a.name || CKEDITOR.dtd[a.name][d])) {
                    var e,
                        g;
                    return(e = b.attributes && (g = b.attributes["data-cke-real-element-type"]) ? g : b.name) && e in CKEDITOR.dtd.$inline && !(e in CKEDITOR.dtd.head) && !b.isOrphan || b.type == CKEDITOR.NODE_TEXT
                }
            }

            function n(a, b) {
                return a in CKEDITOR.dtd.$listItem || a in CKEDITOR.dtd.$tableContent ? a == b || a == "dt" && b == "dd" || a == "dd" && b == "dt" : false
            }

            var l = new CKEDITOR.htmlParser, o = g instanceof CKEDITOR.htmlParser.element ? g : typeof g == "string" ? new CKEDITOR.htmlParser.element(g) : new CKEDITOR.htmlParser.fragment, q = [], s = [], p = o, t = o.name == "textarea",
                z = o.name == "pre";
            l.onTagOpen = function (d, g, h, j) {
                g = new CKEDITOR.htmlParser.element(d, g);
                if (g.isUnknown && h)g.isEmpty = true;
                g.isOptionalClose = j;
                if (b(g))q.push(g); else {
                    if (d == "pre")z = true; else {
                        if (d == "br" && z) {
                            p.add(new CKEDITOR.htmlParser.text("\n"));
                            return
                        }
                        d == "textarea" && (t = true)
                    }
                    if (d == "br")s.push(g); else {
                        for (; ;) {
                            j = (h = p.name) ? CKEDITOR.dtd[h] || (p._.isBlockLike ? CKEDITOR.dtd.div : CKEDITOR.dtd.span) : f;
                            if (!g.isUnknown && !p.isUnknown && !j[d])if (p.isOptionalClose)l.onTagClose(h); else if (d in a && h in a) {
                                h = p.children;
                                (h = h[h.length - 1]) && h.name == "li" || k(h = new CKEDITOR.htmlParser.element("li"), p);
                                !g.returnPoint && (g.returnPoint = p);
                                p = h
                            } else if (d in CKEDITOR.dtd.$listItem && !n(d, h))l.onTagOpen(d == "li" ? "ul" : "dl", {}, 0, 1); else if (h in c && !n(d, h)) {
                                !g.returnPoint && (g.returnPoint = p);
                                p = p.parent
                            } else {
                                h in CKEDITOR.dtd.$inline && q.unshift(p);
                                if (p.parent)k(p, p.parent, 1); else {
                                    g.isOrphan = 1;
                                    break
                                }
                            } else break
                        }
                        i(d);
                        e();
                        g.parent = p;
                        g.isEmpty ? k(g) : p = g
                    }
                }
            };
            l.onTagClose = function (a) {
                for (var b = q.length - 1; b >= 0; b--)if (a == q[b].name) {
                    q.splice(b,
                        1);
                    return
                }
                for (var g = [], c = [], f = p; f != o && f.name != a;) {
                    f._.isBlockLike || c.unshift(f);
                    g.push(f);
                    f = f.returnPoint || f.parent
                }
                if (f != o) {
                    for (b = 0; b < g.length; b++) {
                        var i = g[b];
                        k(i, i.parent)
                    }
                    p = f;
                    f._.isBlockLike && e();
                    k(f, f.parent);
                    if (f == p)p = p.parent;
                    q = q.concat(c)
                }
                a == "body" && (d = false)
            };
            l.onText = function (b) {
                if ((!p._.hasInlineStarted || s.length) && !z && !t) {
                    b = CKEDITOR.tools.ltrim(b);
                    if (b.length === 0)return
                }
                var g = p.name, h = g ? CKEDITOR.dtd[g] || (p._.isBlockLike ? CKEDITOR.dtd.div : CKEDITOR.dtd.span) : f;
                if (!t && !h["#"] && g in c) {
                    l.onTagOpen(g in
                        a ? "li" : g == "dl" ? "dd" : g == "table" ? "tr" : g == "tr" ? "td" : "");
                    l.onText(b)
                } else {
                    e();
                    i();
                    !z && !t && (b = b.replace(/[\t\r\n ]{2,}|[\t\r\n]/g, " "));
                    b = new CKEDITOR.htmlParser.text(b);
                    if (m(p, b))this.onTagOpen(d, {}, 0, 1);
                    p.add(b)
                }
            };
            l.onCDATA = function (a) {
                p.add(new CKEDITOR.htmlParser.cdata(a))
            };
            l.onComment = function (a) {
                e();
                i();
                p.add(new CKEDITOR.htmlParser.comment(a))
            };
            l.parse(h);
            for (e(!CKEDITOR.env.ie && 1); p != o;)k(p, p.parent, 1);
            j(o);
            return o
        };
        CKEDITOR.htmlParser.fragment.prototype = {type: CKEDITOR.NODE_DOCUMENT_FRAGMENT,
            add: function (a, b) {
                isNaN(b) && (b = this.children.length);
                var d = b > 0 ? this.children[b - 1] : null;
                if (d) {
                    if (a._.isBlockLike && d.type == CKEDITOR.NODE_TEXT) {
                        d.value = CKEDITOR.tools.rtrim(d.value);
                        if (d.value.length === 0) {
                            this.children.pop();
                            this.add(a);
                            return
                        }
                    }
                    d.next = a
                }
                a.previous = d;
                a.parent = this;
                this.children.splice(b, 0, a);
                if (!this._.hasInlineStarted)this._.hasInlineStarted = a.type == CKEDITOR.NODE_TEXT || a.type == CKEDITOR.NODE_ELEMENT && !a._.isBlockLike
            }, filter: function (a) {
                a.onRoot(this);
                this.filterChildren(a)
            }, filterChildren: function (a, b) {
                if (this.childrenFilteredBy != a.id) {
                    if (b && !this.parent)a.onRoot(this);
                    this.childrenFilteredBy = a.id;
                    for (var d = 0; d < this.children.length; d++)this.children[d].filter(a) === false && d--
                }
            }, writeHtml: function (a, b) {
                b && this.filter(b);
                this.writeChildrenHtml(a)
            }, writeChildrenHtml: function (a, b, d) {
                if (d && !this.parent && b)b.onRoot(this);
                b && this.filterChildren(b);
                for (var b = 0, d = this.children, c = d.length; b < c; b++)d[b].writeHtml(a)
            }, forEach: function (a, b, d) {
                !d && (!b || this.type == b) && a(this);
                for (var d = this.children, c, e = 0, f =
                    d.length; e < f; e++) {
                    c = d[e];
                    c.type == CKEDITOR.NODE_ELEMENT ? c.forEach(a, b) : (!b || c.type == b) && a(c)
                }
            }}
    }(), function () {
        function b(a, b) {
            for (var c = 0; a && c < b.length; c++)var e = b[c], a = a.replace(e[0], e[1]);
            return a
        }

        function c(a, b, c) {
            typeof b == "function" && (b = [b]);
            var e, f;
            f = a.length;
            var h = b && b.length;
            if (h) {
                for (e = 0; e < f && a[e].pri <= c; e++);
                for (f = h - 1; f >= 0; f--)if (h = b[f]) {
                    h.pri = c;
                    a.splice(e, 0, h)
                }
            }
        }

        function a(a, b, c) {
            if (b)for (var e in b) {
                var h = a[e];
                a[e] = f(h, b[e], c);
                h || a.$length++
            }
        }

        function f(a, b, f) {
            if (b) {
                b.pri = f;
                if (a) {
                    if (a.splice)c(a,
                        b, f); else {
                        a = a.pri > f ? [b, a] : [a, b];
                        a.filter = h
                    }
                    return a
                }
                return b.filter = b
            }
        }

        function h(a) {
            for (var b = a.type || a instanceof CKEDITOR.htmlParser.fragment, c = 0; c < this.length; c++) {
                if (b)var e = a.type, f = a.name;
                var h = this[c].apply(window, arguments);
                if (h === false)return h;
                if (b) {
                    if (h && (h.name != f || h.type != e))return h
                } else if (typeof h != "string")return h;
                h != void 0 && (a = h)
            }
            return a
        }

        CKEDITOR.htmlParser.filter = CKEDITOR.tools.createClass({$: function (a) {
            this.id = CKEDITOR.tools.getNextNumber();
            this._ = {elementNames: [], attributeNames: [],
                elements: {$length: 0}, attributes: {$length: 0}};
            a && this.addRules(a, 10)
        }, proto: {addRules: function (b, d) {
            typeof d != "number" && (d = 10);
            c(this._.elementNames, b.elementNames, d);
            c(this._.attributeNames, b.attributeNames, d);
            a(this._.elements, b.elements, d);
            a(this._.attributes, b.attributes, d);
            this._.text = f(this._.text, b.text, d) || this._.text;
            this._.comment = f(this._.comment, b.comment, d) || this._.comment;
            this._.root = f(this._.root, b.root, d) || this._.root
        }, applyTo: function (a) {
            a.filter(this)
        }, onElementName: function (a) {
            return b(a,
                this._.elementNames)
        }, onAttributeName: function (a) {
            return b(a, this._.attributeNames)
        }, onText: function (a) {
            var b = this._.text;
            return b ? b.filter(a) : a
        }, onComment: function (a, b) {
            var c = this._.comment;
            return c ? c.filter(a, b) : a
        }, onRoot: function (a) {
            var b = this._.root;
            return b ? b.filter(a) : a
        }, onElement: function (a) {
            for (var b = [this._.elements["^"], this._.elements[a.name], this._.elements.$], c, e = 0; e < 3; e++)if (c = b[e]) {
                c = c.filter(a, this);
                if (c === false)return null;
                if (c && c != a)return this.onNode(c);
                if (a.parent && !a.name)break
            }
            return a
        },
            onNode: function (a) {
                var b = a.type;
                return b == CKEDITOR.NODE_ELEMENT ? this.onElement(a) : b == CKEDITOR.NODE_TEXT ? new CKEDITOR.htmlParser.text(this.onText(a.value)) : b == CKEDITOR.NODE_COMMENT ? new CKEDITOR.htmlParser.comment(this.onComment(a.value)) : null
            }, onAttribute: function (a, b, c) {
                if (b = this._.attributes[b]) {
                    a = b.filter(c, a, this);
                    if (a === false)return false;
                    if (typeof a != "undefined")return a
                }
                return c
            }}})
    }(), function () {
        function b(b, c) {
            function j(a) {
                return a || CKEDITOR.env.ie ? new CKEDITOR.htmlParser.text(" ") : new CKEDITOR.htmlParser.element("br",
                    {"data-cke-bogus": 1})
            }

            function o(b, d) {
                return function (c) {
                    if (c.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT) {
                        var i = [], h = a(c), o, m;
                        if (h)for (l(h, 1) && i.push(h); h;) {
                            if (g(h) && (o = f(h)) && l(o))if ((m = f(o)) && !g(m))i.push(o); else {
                                var n = o, k = j(p), q = n.parent.children, s = CKEDITOR.tools.indexOf(q, n);
                                q.splice(s + 1, 0, k);
                                q = n.next;
                                n.next = k;
                                k.previous = n;
                                k.parent = n.parent;
                                k.next = q;
                                e(o)
                            }
                            h = h.previous
                        }
                        for (h = 0; h < i.length; h++)e(i[h]);
                        if (i = CKEDITOR.env.opera && !b || (typeof d == "function" ? d(c) !== false : d))if (!p && CKEDITOR.env.ie && c.type == CKEDITOR.NODE_DOCUMENT_FRAGMENT)i =
                            false; else if (!p && CKEDITOR.env.ie && (document.documentMode > 7 || c.name in CKEDITOR.dtd.tr || c.name in CKEDITOR.dtd.$listItem))i = false; else {
                            i = a(c);
                            i = !i || c.name == "form" && i.name == "input"
                        }
                        i && c.add(j(b))
                    }
                }
            }

            function l(a, b) {
                if ((!p || !CKEDITOR.env.ie) && a.type == CKEDITOR.NODE_ELEMENT && a.name == "br" && !a.attributes["data-cke-eol"])return true;
                var e;
                if (a.type == CKEDITOR.NODE_TEXT && (e = a.value.match(z))) {
                    if (e.index) {
                        d(a, new CKEDITOR.htmlParser.text(a.value.substring(0, e.index)));
                        a.value = e[0]
                    }
                    if (CKEDITOR.env.ie && p && (!b ||
                        a.parent.name in n))return true;
                    if (!p)if ((e = a.previous) && e.name == "br" || !e || g(e))return true
                }
                return false
            }

            var m = {elements: {}}, p = c == "html", n = CKEDITOR.tools.extend({}, r), k;
            for (k in n)"#"in w[k] || delete n[k];
            for (k in n)m.elements[k] = o(p, b.config.fillEmptyBlocks !== false);
            m.root = o(p);
            m.elements.br = function (a) {
                return function (b) {
                    if (b.parent.type != CKEDITOR.NODE_DOCUMENT_FRAGMENT) {
                        var e = b.attributes;
                        if ("data-cke-bogus"in e || "data-cke-eol"in e)delete e["data-cke-bogus"]; else {
                            for (e = b.next; e && h(e);)e = e.next;
                            var c = f(b);
                            !e && g(b.parent) ? i(b.parent, j(a)) : g(e) && (c && !g(c)) && d(e, j(a))
                        }
                    }
                }
            }(p);
            return m
        }

        function c(a) {
            return a.enterMode != CKEDITOR.ENTER_BR && a.autoParagraph !== false ? a.enterMode == CKEDITOR.ENTER_DIV ? "div" : "p" : false
        }

        function a(a) {
            for (a = a.children[a.children.length - 1]; a && h(a);)a = a.previous;
            return a
        }

        function f(a) {
            for (a = a.previous; a && h(a);)a = a.previous;
            return a
        }

        function h(a) {
            return a.type == CKEDITOR.NODE_TEXT && !CKEDITOR.tools.trim(a.value) || a.type == CKEDITOR.NODE_ELEMENT && a.attributes["data-cke-bookmark"]
        }

        function g(a) {
            return a && (a.type == CKEDITOR.NODE_ELEMENT && a.name in r || a.type == CKEDITOR.NODE_DOCUMENT_FRAGMENT)
        }

        function d(a, b) {
            var d = a.parent.children, e = CKEDITOR.tools.indexOf(d, a);
            d.splice(e, 0, b);
            d = a.previous;
            a.previous = b;
            b.next = a;
            b.parent = a.parent;
            if (d) {
                b.previous = d;
                d.next = b
            }
        }

        function i(a, b) {
            var d = a.children[a.children.length - 1];
            a.children.push(b);
            b.parent = a;
            if (d) {
                d.next = b;
                b.previous = d
            }
        }

        function e(a) {
            var b = a.parent.children, d = CKEDITOR.tools.indexOf(b, a), e = a.previous, a = a.next;
            e && (e.next = a);
            a && (a.previous =
                e);
            b.splice(d, 1)
        }

        function j(a) {
            var b = a.parent;
            return b ? CKEDITOR.tools.indexOf(b.children, a) : -1
        }

        function k(a) {
            a = a.attributes;
            a.contenteditable != "false" && (a["data-cke-editable"] = a.contenteditable ? "true" : 1);
            a.contenteditable = "false"
        }

        function m(a) {
            a = a.attributes;
            switch (a["data-cke-editable"]) {
                case "true":
                    a.contenteditable = "true";
                    break;
                case "1":
                    delete a.contenteditable
            }
        }

        function n(a) {
            return a.replace(y, function (a, b, d) {
                return"<" + b + d.replace(C, function (a, b) {
                    return!/^on/.test(b) && d.indexOf("data-cke-saved-" +
                        b) == -1 ? " data-cke-saved-" + a + " data-cke-" + CKEDITOR.rnd + "-" + a : a
                }) + ">"
            })
        }

        function l(a, b) {
            return a.replace(b, function (a, b, d) {
                a.indexOf("<textarea") == 0 && (a = b + s(d).replace(/</g, "&lt;").replace(/>/g, "&gt;") + "</textarea>");
                return"<cke:encoded>" + encodeURIComponent(a) + "</cke:encoded>"
            })
        }

        function o(a) {
            return a.replace(E, function (a, b) {
                return decodeURIComponent(b)
            })
        }

        function q(a) {
            return a.replace(/<\!--(?!{cke_protected})[\s\S]+?--\>/g, function (a) {
                return"<\!--" + x + "{C}" + encodeURIComponent(a).replace(/--/g, "%2D%2D") +
                    "--\>"
            })
        }

        function s(a) {
            return a.replace(/<\!--\{cke_protected\}\{C\}([\s\S]+?)--\>/g, function (a, b) {
                return decodeURIComponent(b)
            })
        }

        function p(a, b) {
            var d = b._.dataStore;
            return a.replace(/<\!--\{cke_protected\}([\s\S]+?)--\>/g,function (a, b) {
                return decodeURIComponent(b)
            }).replace(/\{cke_protected_(\d+)\}/g, function (a, b) {
                return d && d[b] || ""
            })
        }

        function t(a, b) {
            for (var d = [], e = b.config.protectedSource, c = b._.dataStore || (b._.dataStore = {id: 1}), g = /<\!--\{cke_temp(comment)?\}(\d*?)--\>/g, e = [/<script[\s\S]*?<\/script>/gi,
                /<noscript[\s\S]*?<\/noscript>/gi].concat(e), a = a.replace(/<\!--[\s\S]*?--\>/g, function (a) {
                return"<\!--{cke_tempcomment}" + (d.push(a) - 1) + "--\>"
            }), f = 0; f < e.length; f++)a = a.replace(e[f], function (a) {
                a = a.replace(g, function (a, b, e) {
                    return d[e]
                });
                return/cke_temp(comment)?/.test(a) ? a : "<\!--{cke_temp}" + (d.push(a) - 1) + "--\>"
            });
            a = a.replace(g, function (a, b, e) {
                return"<\!--" + x + (b ? "{C}" : "") + encodeURIComponent(d[e]).replace(/--/g, "%2D%2D") + "--\>"
            });
            return a.replace(/(['"]).*?\1/g, function (a) {
                return a.replace(/<\!--\{cke_protected\}([\s\S]+?)--\>/g,
                    function (a, b) {
                        c[c.id] = decodeURIComponent(b);
                        return"{cke_protected_" + c.id++ + "}"
                    })
            })
        }

        CKEDITOR.htmlDataProcessor = function (a) {
            var d, e, g = this;
            this.editor = a;
            this.dataFilter = d = new CKEDITOR.htmlParser.filter;
            this.htmlFilter = e = new CKEDITOR.htmlParser.filter;
            this.writer = new CKEDITOR.htmlParser.basicWriter;
            d.addRules(u);
            d.addRules(b(a, "data"));
            e.addRules(A);
            e.addRules(b(a, "html"));
            a.on("toHtml", function (b) {
                var b = b.data, d = b.dataValue, d = t(d, a), d = l(d, F), d = n(d), d = l(d, D), d = d.replace(K, "$1cke:$2"), d = d.replace(G,
                    "<cke:$1$2></cke:$1>"), d = CKEDITOR.env.opera ? d : d.replace(/(<pre\b[^>]*>)(\r\n|\n)/g, "$1$2$2"), e = b.context || a.editable().getName(), g;
                if (CKEDITOR.env.ie && CKEDITOR.env.version < 9 && e == "pre") {
                    e = "div";
                    d = "<pre>" + d + "</pre>";
                    g = 1
                }
                e = a.document.createElement(e);
                e.setHtml("a" + d);
                d = e.getHtml().substr(1);
                d = d.replace(RegExp(" data-cke-" + CKEDITOR.rnd + "-", "ig"), " ");
                g && (d = d.replace(/^<pre>|<\/pre>$/gi, ""));
                d = d.replace(I, "$1$2");
                d = o(d);
                d = s(d);
                b.dataValue = CKEDITOR.htmlParser.fragment.fromHtml(d, b.context, b.fixForBody ===
                    false ? false : c(a.config))
            }, null, null, 5);
            a.on("toHtml", function (a) {
                a.data.dataValue.filterChildren(g.dataFilter, true)
            }, null, null, 10);
            a.on("toHtml", function (a) {
                var a = a.data, b = a.dataValue, d = new CKEDITOR.htmlParser.basicWriter;
                b.writeChildrenHtml(d);
                b = d.getHtml(true);
                a.dataValue = q(b)
            }, null, null, 15);
            a.on("toDataFormat", function (b) {
                b.data.dataValue = CKEDITOR.htmlParser.fragment.fromHtml(b.data.dataValue, a.editable().getName(), c(a.config))
            }, null, null, 5);
            a.on("toDataFormat", function (a) {
                a.data.dataValue.filterChildren(g.htmlFilter,
                    true)
            }, null, null, 10);
            a.on("toDataFormat", function (b) {
                var d = b.data.dataValue, e = g.writer;
                e.reset();
                d.writeChildrenHtml(e);
                d = e.getHtml(true);
                d = s(d);
                d = p(d, a);
                b.data.dataValue = d
            }, null, null, 15)
        };
        CKEDITOR.htmlDataProcessor.prototype = {toHtml: function (a, b, d, e) {
            var c = this.editor;
            !b && b !== null && (b = c.editable().getName());
            return c.fire("toHtml", {dataValue: a, context: b, fixForBody: d, dontFilter: !!e}).dataValue
        }, toDataFormat: function (a) {
            return this.editor.fire("toDataFormat", {dataValue: a}).dataValue
        }};
        var z = /(?:&nbsp;|\xa0)$/,
            x = "{cke_protected}", w = CKEDITOR.dtd, v = ["caption", "colgroup", "col", "thead", "tfoot", "tbody"], r = CKEDITOR.tools.extend({}, w.$blockLimit, w.$block), u = {elements: {}, attributeNames: [
                [/^on/, "data-cke-pa-on"]
            ]}, A = {elementNames: [
                [/^cke:/, ""],
                [/^\?xml:namespace$/, ""]
            ], attributeNames: [
                [/^data-cke-(saved|pa)-/, ""],
                [/^data-cke-.*/, ""],
                ["hidefocus", ""]
            ], elements: {$: function (a) {
                var b = a.attributes;
                if (b) {
                    if (b["data-cke-temp"])return false;
                    for (var d = ["name", "href", "src"], e, c = 0; c < d.length; c++) {
                        e = "data-cke-saved-" + d[c];
                        e in b && delete b[d[c]]
                    }
                }
                return a
            }, table: function (a) {
                a.children.slice(0).sort(function (a, b) {
                    var d, e;
                    if (a.type == CKEDITOR.NODE_ELEMENT && b.type == a.type) {
                        d = CKEDITOR.tools.indexOf(v, a.name);
                        e = CKEDITOR.tools.indexOf(v, b.name)
                    }
                    if (!(d > -1 && e > -1 && d != e)) {
                        d = j(a);
                        e = j(b)
                    }
                    return d > e ? 1 : -1
                })
            }, embed: function (a) {
                var b = a.parent;
                if (b && b.name == "object") {
                    var d = b.attributes.width, b = b.attributes.height;
                    d && (a.attributes.width = d);
                    b && (a.attributes.height = b)
                }
            }, param: function (a) {
                a.children = [];
                a.isEmpty = true;
                return a
            }, a: function (a) {
                if (!a.children.length && !a.attributes.name && !a.attributes["data-cke-saved-name"])return false
            }, span: function (a) {
                a.attributes["class"] == "Apple-style-span" && delete a.name
            }, html: function (a) {
                delete a.attributes.contenteditable;
                delete a.attributes["class"]
            }, body: function (a) {
                delete a.attributes.spellcheck;
                delete a.attributes.contenteditable
            }, style: function (a) {
                var b = a.children[0];
                b && b.value && (b.value = CKEDITOR.tools.trim(b.value));
                if (!a.attributes.type)a.attributes.type = "text/css"
            }, title: function (a) {
                var b = a.children[0];
                !b && i(a,
                    b = new CKEDITOR.htmlParser.text);
                b.value = a.attributes["data-cke-title"] || ""
            }}, attributes: {"class": function (a) {
                return CKEDITOR.tools.ltrim(a.replace(/(?:^|\s+)cke_[^\s]*/g, "")) || false
            }}};
        if (CKEDITOR.env.ie)A.attributes.style = function (a) {
            return a.replace(/(^|;)([^\:]+)/g, function (a) {
                return a.toLowerCase()
            })
        };
        for (var B in{input: 1, textarea: 1}) {
            u.elements[B] = k;
            A.elements[B] = m
        }
        var y = /<(a|area|img|input|source)\b([^>]*)>/gi, C = /\b(on\w+|href|src|name)\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|(?:[^ "'>]+))/gi, D = /(?:<style(?=[ >])[^>]*>[\s\S]*?<\/style>)|(?:<(:?link|meta|base)[^>]*>)/gi,
            F = /(<textarea(?=[ >])[^>]*>)([\s\S]*?)(?:<\/textarea>)/gi, E = /<cke:encoded>([^<]*)<\/cke:encoded>/gi, K = /(<\/?)((?:object|embed|param|html|body|head|title)[^>]*>)/gi, I = /(<\/?)cke:((?:html|body|head|title)[^>]*>)/gi, G = /<cke:(param|embed)([^>]*?)\/?>(?!\s*<\/cke:\1)/gi
    }(), "use strict", CKEDITOR.htmlParser.element = function (b, c) {
        this.name = b;
        this.attributes = c || {};
        this.children = [];
        var a = b || "", f = a.match(/^cke:(.*)/);
        f && (a = f[1]);
        a = !(!CKEDITOR.dtd.$nonBodyContent[a] && !CKEDITOR.dtd.$block[a] && !CKEDITOR.dtd.$listItem[a] && !CKEDITOR.dtd.$tableContent[a] && !(CKEDITOR.dtd.$nonEditable[a] || a == "br"));
        this.isEmpty = !!CKEDITOR.dtd.$empty[b];
        this.isUnknown = !CKEDITOR.dtd[b];
        this._ = {isBlockLike: a, hasInlineStarted: this.isEmpty || !a}
    }, CKEDITOR.htmlParser.cssStyle = function (b) {
        var c = {};
        ((b instanceof CKEDITOR.htmlParser.element ? b.attributes.style : b) || "").replace(/&quot;/g, '"').replace(/\s*([^ :;]+)\s*:\s*([^;]+)\s*(?=;|$)/g, function (a, b, h) {
            b == "font-family" && (h = h.replace(/["']/g, ""));
            c[b.toLowerCase()] = h
        });
        return{rules: c, populate: function (a) {
            var b =
                this.toString();
            if (b)a instanceof CKEDITOR.dom.element ? a.setAttribute("style", b) : a instanceof CKEDITOR.htmlParser.element ? a.attributes.style = b : a.style = b
        }, toString: function () {
            var a = [], b;
            for (b in c)c[b] && a.push(b, ":", c[b], ";");
            return a.join("")
        }}
    }, function () {
        var b = function (a, b) {
            a = a[0];
            b = b[0];
            return a < b ? -1 : a > b ? 1 : 0
        }, c = CKEDITOR.htmlParser.fragment.prototype;
        CKEDITOR.htmlParser.element.prototype = CKEDITOR.tools.extend(new CKEDITOR.htmlParser.node, {type: CKEDITOR.NODE_ELEMENT, add: c.add, clone: function () {
            return new CKEDITOR.htmlParser.element(this.name,
                this.attributes)
        }, filter: function (a) {
            var b = this, c, g;
            if (!b.parent)a.onRoot(b);
            for (; ;) {
                c = b.name;
                if (!(g = a.onElementName(c))) {
                    this.remove();
                    return false
                }
                b.name = g;
                if (!(b = a.onElement(b))) {
                    this.remove();
                    return false
                }
                if (b !== this) {
                    this.replaceWith(b);
                    return false
                }
                if (b.name == c)break;
                if (b.type != CKEDITOR.NODE_ELEMENT) {
                    this.replaceWith(b);
                    return false
                }
                if (!b.name) {
                    this.replaceWithChildren();
                    return false
                }
            }
            c = b.attributes;
            var d, i;
            for (d in c) {
                i = d;
                for (g = c[d]; ;)if (i = a.onAttributeName(d))if (i != d) {
                    delete c[d];
                    d = i
                } else break;
                else {
                    delete c[d];
                    break
                }
                i && ((g = a.onAttribute(b, i, g)) === false ? delete c[i] : c[i] = g)
            }
            b.isEmpty || this.filterChildren(a);
            return true
        }, filterChildren: c.filterChildren, writeHtml: function (a, c) {
            c && this.filter(c);
            var h = this.name, g = [], d = this.attributes, i, e;
            a.openTag(h, d);
            for (i in d)g.push([i, d[i]]);
            a.sortAttributes && g.sort(b);
            i = 0;
            for (e = g.length; i < e; i++) {
                d = g[i];
                a.attribute(d[0], d[1])
            }
            a.openTagClose(h, this.isEmpty);
            this.writeChildrenHtml(a);
            this.isEmpty || a.closeTag(h)
        }, writeChildrenHtml: c.writeChildrenHtml, replaceWithChildren: function () {
            for (var a =
                this.children, b = a.length; b;)a[--b].insertAfter(this);
            this.remove()
        }, forEach: c.forEach})
    }(), function () {
        var b = {};
        CKEDITOR.template = function (c) {
            if (b[c])this.output = b[c]; else {
                var a = c.replace(/'/g, "\\'").replace(/{([^}]+)}/g, function (a, b) {
                    return"',data['" + b + "']==undefined?'{" + b + "}':data['" + b + "'],'"
                });
                this.output = b[c] = Function("data", "buffer", "return buffer?buffer.push('" + a + "'):['" + a + "'].join('');")
            }
        }
    }(), delete CKEDITOR.loadFullCore, CKEDITOR.instances = {}, CKEDITOR.document = new CKEDITOR.dom.document(document),
        CKEDITOR.add = function (b) {
            CKEDITOR.instances[b.name] = b;
            b.on("focus", function () {
                if (CKEDITOR.currentInstance != b) {
                    CKEDITOR.currentInstance = b;
                    CKEDITOR.fire("currentInstance")
                }
            });
            b.on("blur", function () {
                if (CKEDITOR.currentInstance == b) {
                    CKEDITOR.currentInstance = null;
                    CKEDITOR.fire("currentInstance")
                }
            });
            CKEDITOR.fire("instance", null, b)
        }, CKEDITOR.remove = function (b) {
        delete CKEDITOR.instances[b.name]
    }, function () {
        var b = {};
        CKEDITOR.addTemplate = function (c, a) {
            var f = b[c];
            if (f)return f;
            f = {name: c, source: a};
            CKEDITOR.fire("template",
                f);
            return b[c] = new CKEDITOR.template(f.source)
        };
        CKEDITOR.getTemplate = function (c) {
            return b[c]
        }
    }(), function () {
        var b = [];
        CKEDITOR.addCss = function (c) {
            b.push(c)
        };
        CKEDITOR.getCss = function () {
            return b.join("\n")
        }
    }(), CKEDITOR.on("instanceDestroyed", function () {
        CKEDITOR.tools.isEmpty(this.instances) && CKEDITOR.fire("reset")
    }), CKEDITOR.TRISTATE_ON = 1, CKEDITOR.TRISTATE_OFF = 2, CKEDITOR.TRISTATE_DISABLED = 0, function () {
        CKEDITOR.inline = function (b, c) {
            if (!CKEDITOR.env.isCompatible)return null;
            b = CKEDITOR.dom.element.get(b);
            if (b.getEditor())throw'The editor instance "' + b.getEditor().name + '" is already attached to the provided element.';
            var a = new CKEDITOR.editor(c, b, CKEDITOR.ELEMENT_MODE_INLINE);
            a.setData(b.getHtml(), null, true);
            a.on("loaded", function () {
                a.fire("uiReady");
                a.editable(b);
                a.container = b;
                a.setData(a.getData(1));
                a.resetDirty();
                a.fire("contentDom");
                a.mode = "wysiwyg";
                a.fire("mode");
                a.status = "ready";
                a.fireOnce("instanceReady");
                CKEDITOR.fire("instanceReady", null, a)
            }, null, null, 1E4);
            a.on("destroy", function () {
                a.element.clearCustomData();
                delete a.element
            });
            return a
        };
        CKEDITOR.inlineAll = function () {
            var b, c, a;
            for (a in CKEDITOR.dtd.$editable)for (var f = CKEDITOR.document.getElementsByTag(a), h = 0, g = f.count(); h < g; h++) {
                b = f.getItem(h);
                if (b.getAttribute("contenteditable") == "true") {
                    c = {element: b, config: {}};
                    CKEDITOR.fire("inline", c) !== false && CKEDITOR.inline(b, c.config)
                }
            }
        };
        CKEDITOR.domReady(function () {
            !CKEDITOR.disableAutoInline && CKEDITOR.inlineAll()
        })
    }(), CKEDITOR.replaceClass = "ckeditor", function () {
        function b(b, d, i, e) {
            if (!CKEDITOR.env.isCompatible)return null;
            b = CKEDITOR.dom.element.get(b);
            if (b.getEditor())throw'The editor instance "' + b.getEditor().name + '" is already attached to the provided element.';
            var h = new CKEDITOR.editor(d, b, e);
            e == CKEDITOR.ELEMENT_MODE_REPLACE && b.setStyle("visibility", "hidden");
            i && h.setData(i, null, true);
            h.on("loaded", function () {
                a(h);
                e == CKEDITOR.ELEMENT_MODE_REPLACE && h.config.autoUpdateElement && f(h);
                h.setMode(h.config.startupMode, function () {
                    h.resetDirty();
                    h.status = "ready";
                    h.fireOnce("instanceReady");
                    CKEDITOR.fire("instanceReady", null,
                        h)
                })
            });
            h.on("destroy", c);
            return h
        }

        function c() {
            var a = this.container, b = this.element;
            if (a) {
                a.clearCustomData();
                a.remove()
            }
            if (b) {
                b.clearCustomData();
                this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE && b.show();
                delete this.element
            }
        }

        function a(a) {
            var b = a.name, c = a.element, e = a.elementMode, f = a.fire("uiSpace", {space: "top", html: ""}).html, k = a.fire("uiSpace", {space: "bottom", html: ""}).html;
            h || (h = CKEDITOR.addTemplate("maincontainer", '<{outerEl} id="cke_{name}" class="{id} cke cke_reset cke_chrome cke_editor_{name} cke_{langDir} ' +
                CKEDITOR.env.cssClass + '"  dir="{langDir}" lang="{langCode}" role="application" aria-labelledby="cke_{name}_arialbl"><span id="cke_{name}_arialbl" class="cke_voice_label">{voiceLabel}</span><{outerEl} class="cke_inner cke_reset" role="presentation">{topHtml}<{outerEl} id="{contentId}" class="cke_contents cke_reset" role="presentation"></{outerEl}>{bottomHtml}</{outerEl}></{outerEl}>'));
            b = CKEDITOR.dom.element.createFromHtml(h.output({id: a.id, name: b, langDir: a.lang.dir, langCode: a.langCode, voiceLabel: a.lang.editor,
                topHtml: f ? '<span id="' + a.ui.spaceId("top") + '" class="cke_top cke_reset_all" role="presentation" style="height:auto">' + f + "</span>" : "", contentId: a.ui.spaceId("contents"), bottomHtml: k ? '<span id="' + a.ui.spaceId("bottom") + '" class="cke_bottom cke_reset_all" role="presentation">' + k + "</span>" : "", outerEl: CKEDITOR.env.ie ? "span" : "div"}));
            if (e == CKEDITOR.ELEMENT_MODE_REPLACE) {
                c.hide();
                b.insertAfter(c)
            } else c.append(b);
            a.container = b;
            f && a.ui.space("top").unselectable();
            k && a.ui.space("bottom").unselectable();
            c =
                a.config.width;
            e = a.config.height;
            c && b.setStyle("width", CKEDITOR.tools.cssLength(c));
            e && a.ui.space("contents").setStyle("height", CKEDITOR.tools.cssLength(e));
            b.disableContextMenu();
            CKEDITOR.env.webkit && b.on("focus", function () {
                a.focus()
            });
            a.fireOnce("uiReady")
        }

        function f(a) {
            var b = a.element;
            if (a.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE && b.is("textarea")) {
                var c = b.$.form && new CKEDITOR.dom.element(b.$.form);
                if (c) {
                    var e = function () {
                        a.updateElement()
                    };
                    c.on("submit", e);
                    if (!c.$.submit.nodeName && !c.$.submit.length)c.$.submit =
                        CKEDITOR.tools.override(c.$.submit, function (b) {
                            return function () {
                                a.updateElement();
                                b.apply ? b.apply(this, arguments) : b()
                            }
                        });
                    a.on("destroy", function () {
                        c.removeListener("submit", e)
                    })
                }
            }
        }

        CKEDITOR.replace = function (a, d) {
            return b(a, d, null, CKEDITOR.ELEMENT_MODE_REPLACE)
        };
        CKEDITOR.appendTo = function (a, d, c) {
            return b(a, d, c, CKEDITOR.ELEMENT_MODE_APPENDTO)
        };
        CKEDITOR.replaceAll = function () {
            for (var a = document.getElementsByTagName("textarea"), b = 0; b < a.length; b++) {
                var c = null, e = a[b];
                if (e.name || e.id) {
                    if (typeof arguments[0] ==
                        "string") {
                        if (!RegExp("(?:^|\\s)" + arguments[0] + "(?:$|\\s)").test(e.className))continue
                    } else if (typeof arguments[0] == "function") {
                        c = {};
                        if (arguments[0](e, c) === false)continue
                    }
                    this.replace(e, c)
                }
            }
        };
        CKEDITOR.editor.prototype.addMode = function (a, b) {
            (this._.modes || (this._.modes = {}))[a] = b
        };
        CKEDITOR.editor.prototype.setMode = function (a, b) {
            var c = this, e = this._.modes;
            if (!(a == c.mode || !e || !e[a])) {
                c.fire("beforeSetMode", a);
                if (c.mode) {
                    var f = c.checkDirty();
                    c._.previousMode = c.mode;
                    c.fire("beforeModeUnload");
                    c.editable(0);
                    c.ui.space("contents").setHtml("");
                    c.mode = ""
                }
                this._.modes[a](function () {
                    c.mode = a;
                    f !== void 0 && !f && c.resetDirty();
                    setTimeout(function () {
                        c.fire("mode");
                        b && b.call(c)
                    }, 0)
                })
            }
        };
        CKEDITOR.editor.prototype.resize = function (a, b, c, e) {
            var f = this.container, h = this.ui.space("contents"), m = CKEDITOR.env.webkit && this.document && this.document.getWindow().$.frameElement, e = e ? f.getChild(1) : f;
            e.setSize("width", a, true);
            m && (m.style.width = "1%");
            h.setStyle("height", Math.max(b - (c ? 0 : (e.$.offsetHeight || 0) - (h.$.clientHeight || 0)), 0) +
                "px");
            m && (m.style.width = "100%");
            this.fire("resize")
        };
        CKEDITOR.editor.prototype.getResizable = function (a) {
            return a ? this.ui.space("contents") : this.container
        };
        var h;
        CKEDITOR.domReady(function () {
            CKEDITOR.replaceClass && CKEDITOR.replaceAll(CKEDITOR.replaceClass)
        })
    }(), CKEDITOR.config.startupMode = "wysiwyg", function () {
        function b(b) {
            var d = b.editor, e = d.editable(), c = b.data.path, f = c.blockLimit, g = b.data.selection.getRanges()[0], i = d.config.enterMode;
            if (CKEDITOR.env.gecko) {
                var h = c.block || c.blockLimit || c.root, j = h &&
                    h.getLast(a);
                h && (h.isBlockBoundary() && (!j || !(j.type == CKEDITOR.NODE_ELEMENT && j.isBlockBoundary())) && !h.is("pre") && !h.getBogus()) && h.appendBogus()
            }
            if (d.config.autoParagraph !== false && i != CKEDITOR.ENTER_BR && g.collapsed && e.equals(f) && !c.block) {
                e = g.clone();
                e.enlarge(CKEDITOR.ENLARGE_BLOCK_CONTENTS);
                c = new CKEDITOR.dom.walker(e);
                c.guard = function (b) {
                    return!a(b) || b.type == CKEDITOR.NODE_COMMENT || b.isReadOnly()
                };
                if (!c.checkForward() || e.checkStartOfBlock() && e.checkEndOfBlock()) {
                    d = g.fixBlock(true, d.config.enterMode ==
                        CKEDITOR.ENTER_DIV ? "div" : "p");
                    if (CKEDITOR.env.ie)(d = d.getFirst(a)) && (d.type == CKEDITOR.NODE_TEXT && CKEDITOR.tools.trim(d.getText()).match(/^(?:&nbsp;|\xa0)$/)) && d.remove();
                    g.select();
                    b.cancel()
                }
            }
        }

        function c(a) {
            var b = a.data.getTarget();
            if (b.is("input")) {
                b = b.getAttribute("type");
                (b == "submit" || b == "reset") && a.data.preventDefault()
            }
        }

        function a(a) {
            return e(a) && j(a)
        }

        function f(a, b) {
            return function (d) {
                var e = CKEDITOR.dom.element.get(d.data.$.toElement || d.data.$.fromElement || d.data.$.relatedTarget);
                (!e || !b.equals(e) && !b.contains(e)) && a.call(this, d)
            }
        }

        function h(b) {
            var d, e = b.getRanges()[0], b = b.root, c = e.startPath(), f = {table: 1, ul: 1, ol: 1, dl: 1}, g = CKEDITOR.dom.walker.bogus();
            if (c.contains(f)) {
                var i = e.clone();
                i.collapse(1);
                i.setStartAt(b, CKEDITOR.POSITION_AFTER_START);
                i = new CKEDITOR.dom.walker(i);
                c = function (b, e) {
                    return function (b, c) {
                        c && (b.type == CKEDITOR.NODE_ELEMENT && b.is(f)) && (d = b);
                        if (a(b) && !c && (!e || !g(b)))return false
                    }
                };
                i.guard = c(i);
                i.checkBackward();
                if (d) {
                    i = e.clone();
                    i.collapse();
                    i.setEndAt(b, CKEDITOR.POSITION_BEFORE_END);
                    i = new CKEDITOR.dom.walker(i);
                    i.guard = c(i, 1);
                    d = 0;
                    i.checkForward();
                    return d
                }
            }
            return null
        }

        function g(a) {
            a.editor.focus();
            a.editor.fire("saveSnapshot")
        }

        function d(a, b) {
            var d = a.editor;
            !b && d.getSelection().scrollIntoView();
            setTimeout(function () {
                d.fire("saveSnapshot")
            }, 0)
        }

        CKEDITOR.editable = CKEDITOR.tools.createClass({base: CKEDITOR.dom.element, $: function (a, b) {
            this.base(b.$ || b);
            this.editor = a;
            this.hasFocus = false;
            this.setup()
        }, proto: {focus: function () {
            this.$[CKEDITOR.env.ie && this.getDocument().equals(CKEDITOR.document) ?
                "setActive" : "focus"]();
            CKEDITOR.env.safari && !this.isInline() && (CKEDITOR.document.getActive().equals(this.getWindow().getFrame()) || this.getWindow().focus())
        }, on: function (a, b) {
            var d = Array.prototype.slice.call(arguments, 0);
            if (CKEDITOR.env.ie && /^focus|blur$/.exec(a)) {
                a = a == "focus" ? "focusin" : "focusout";
                b = f(b, this);
                d[0] = a;
                d[1] = b
            }
            return CKEDITOR.dom.element.prototype.on.apply(this, d)
        }, attachListener: function (a, b, d, e, c, f) {
            !this._.listeners && (this._.listeners = []);
            var g = Array.prototype.slice.call(arguments,
                1);
            this._.listeners.push(a.on.apply(a, g))
        }, clearListeners: function () {
            var a = this._.listeners;
            try {
                for (; a.length;)a.pop().removeListener()
            } catch (b) {
            }
        }, restoreAttrs: function () {
            var a = this._.attrChanges, b, d;
            for (d in a)if (a.hasOwnProperty(d)) {
                b = a[d];
                b !== null ? this.setAttribute(d, b) : this.removeAttribute(d)
            }
        }, attachClass: function (a) {
            var b = this.getCustomData("classes");
            if (!this.hasClass(a)) {
                !b && (b = []);
                b.push(a);
                this.setCustomData("classes", b);
                this.addClass(a)
            }
        }, changeAttr: function (a, b) {
            var d = this.getAttribute(a);
            if (b !== d) {
                !this._.attrChanges && (this._.attrChanges = {});
                a in this._.attrChanges || (this._.attrChanges[a] = d);
                this.setAttribute(a, b)
            }
        }, insertHtml: function (a, b) {
            g(this);
            k(this, b || "html", a)
        }, insertText: function (a) {
            g(this);
            var b = this.editor, d = b.getSelection().getStartElement().hasAscendant("pre", true) ? CKEDITOR.ENTER_BR : b.config.enterMode, b = d == CKEDITOR.ENTER_BR, e = CKEDITOR.tools, a = e.htmlEncode(a.replace(/\r\n/g, "\n")), a = a.replace(/\t/g, "&nbsp;&nbsp; &nbsp;"), d = d == CKEDITOR.ENTER_P ? "p" : "div";
            if (!b) {
                var c = /\n{2}/g;
                if (c.test(a))var f = "<" + d + ">", i = "</" + d + ">", a = f + a.replace(c, function () {
                    return i + f
                }) + i
            }
            a = a.replace(/\n/g, "<br>");
            b || (a = a.replace(RegExp("<br>(?=</" + d + ">)"), function (a) {
                return e.repeat(a, 2)
            }));
            a = a.replace(/^ | $/g, "&nbsp;");
            a = a.replace(/(>|\s) /g,function (a, b) {
                return b + "&nbsp;"
            }).replace(/ (?=<)/g, "&nbsp;");
            k(this, "text", a)
        }, insertElement: function (b) {
            g(this);
            for (var e = this.editor, c = e.config.enterMode, f = e.getSelection(), i = f.getRanges(), h = b.getName(), j = CKEDITOR.dtd.$block[h], k, z, x, w = i.length - 1; w >= 0; w--) {
                k =
                    i[w];
                if (!k.checkReadOnly()) {
                    k.deleteContents(1);
                    z = !w && b || b.clone(1);
                    var v, r;
                    if (j)for (; (v = k.getCommonAncestor(0, 1)) && (r = CKEDITOR.dtd[v.getName()]) && (!r || !r[h]);)if (v.getName()in CKEDITOR.dtd.span)k.splitElement(v); else if (k.checkStartOfBlock() && k.checkEndOfBlock()) {
                        k.setStartBefore(v);
                        k.collapse(true);
                        v.remove()
                    } else k.splitBlock(c == CKEDITOR.ENTER_DIV ? "div" : "p", e.editable());
                    k.insertNode(z);
                    x || (x = z)
                }
            }
            if (x) {
                k.moveToPosition(x, CKEDITOR.POSITION_AFTER_END);
                if (j)if ((b = x.getNext(a)) && b.type == CKEDITOR.NODE_ELEMENT &&
                    b.is(CKEDITOR.dtd.$block))b.getDtd()["#"] ? k.moveToElementEditStart(b) : k.moveToElementEditEnd(x); else if (!b && c != CKEDITOR.ENTER_BR) {
                    b = k.fixBlock(true, c == CKEDITOR.ENTER_DIV ? "div" : "p");
                    k.moveToElementEditStart(b)
                }
            }
            f.selectRanges([k]);
            d(this, CKEDITOR.env.opera)
        }, setData: function (a, b) {
            !b && this.editor.dataProcessor && (a = this.editor.dataProcessor.toHtml(a));
            this.setHtml(a);
            this.editor.fire("dataReady")
        }, getData: function (a) {
            var b = this.getHtml();
            !a && this.editor.dataProcessor && (b = this.editor.dataProcessor.toDataFormat(b));
            return b
        }, setReadOnly: function (a) {
            this.setAttribute("contenteditable", !a)
        }, detach: function () {
            this.removeClass("cke_editable");
            var a = this.editor;
            this._.detach();
            delete a.document;
            delete a.window
        }, isInline: function () {
            return this.getDocument().equals(CKEDITOR.document)
        }, setup: function () {
            var a = this.editor;
            this.attachListener(a, "beforeGetData", function () {
                var b = this.getData();
                this.is("textarea") || a.config.ignoreEmptyParagraph !== false && (b = b.replace(i, function (a, b) {
                    return b
                }));
                a.setData(b, null, 1)
            }, this);
            this.attachListener(a, "getSnapshot", function (a) {
                a.data = this.getData(1)
            }, this);
            this.attachListener(a, "afterSetData", function () {
                this.setData(a.getData(1))
            }, this);
            this.attachListener(a, "loadSnapshot", function (a) {
                this.setData(a.data, 1)
            }, this);
            this.attachListener(a, "beforeFocus", function () {
                var b = a.getSelection();
                (b = b && b.getNative()) && b.type == "Control" || this.focus()
            }, this);
            this.attachListener(a, "insertHtml", function (a) {
                this.insertHtml(a.data.dataValue, a.data.mode)
            }, this);
            this.attachListener(a, "insertElement",
                function (a) {
                    this.insertElement(a.data)
                }, this);
            this.attachListener(a, "insertText", function (a) {
                this.insertText(a.data)
            }, this);
            this.setReadOnly(a.readOnly);
            this.attachClass("cke_editable");
            this.attachClass(a.elementMode == CKEDITOR.ELEMENT_MODE_INLINE ? "cke_editable_inline" : a.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE || a.elementMode == CKEDITOR.ELEMENT_MODE_APPENDTO ? "cke_editable_themed" : "");
            this.attachClass("cke_contents_" + a.config.contentsLangDirection);
            a.keystrokeHandler.blockedKeystrokes[8] = a.readOnly;
            a.keystrokeHandler.attach(this);
            this.on("blur", function (a) {
                CKEDITOR.env.opera && CKEDITOR.document.getActive().equals(this.isInline() ? this : this.getWindow().getFrame()) ? a.cancel() : this.hasFocus = false
            }, null, null, -1);
            this.on("focus", function () {
                this.hasFocus = true
            }, null, null, -1);
            a.focusManager.add(this);
            if (this.equals(CKEDITOR.document.getActive())) {
                this.hasFocus = true;
                a.once("contentDom", function () {
                    a.focusManager.focus()
                })
            }
            this.isInline() && this.changeAttr("tabindex", a.tabIndex);
            if (!this.is("textarea")) {
                a.document =
                    this.getDocument();
                a.window = this.getWindow();
                var b = a.document;
                this.changeAttr("spellcheck", !a.config.disableNativeSpellChecker);
                var d = a.config.contentsLangDirection;
                this.getDirection(1) != d && this.changeAttr("dir", d);
                var f = CKEDITOR.getCss();
                if (f) {
                    d = b.getHead();
                    if (!d.getCustomData("stylesheet")) {
                        f = b.appendStyleText(f);
                        f = new CKEDITOR.dom.element(f.ownerNode || f.owningElement);
                        d.setCustomData("stylesheet", f);
                        f.data("cke-temp", 1)
                    }
                }
                d = b.getCustomData("stylesheet_ref") || 0;
                b.setCustomData("stylesheet_ref", d +
                    1);
                this.setCustomData("cke_includeReadonly", !a.config.disableReadonlyStyling);
                this.attachListener(this, "click", function (a) {
                    var a = a.data, b = a.getTarget();
                    b.is("a") && (a.$.button != 2 && b.isReadOnly()) && a.preventDefault()
                });
                this.attachListener(a, "key", function (b) {
                    if (a.readOnly)return true;
                    var d = b.data.keyCode, c;
                    if (d in{8: 1, 46: 1}) {
                        var f = a.getSelection(), b = f.getRanges()[0], g = b.startPath(), i, j, o, d = d == 8;
                        if (f = h(f)) {
                            a.fire("saveSnapshot");
                            b.moveToPosition(f, CKEDITOR.POSITION_BEFORE_START);
                            f.remove();
                            b.select();
                            a.fire("saveSnapshot");
                            c = 1
                        } else if (b.collapsed)if ((i = g.block) && b[d ? "checkStartOfBlock" : "checkEndOfBlock"]() && (o = i[d ? "getPrevious" : "getNext"](e)) && o.is("table")) {
                            a.fire("saveSnapshot");
                            b[d ? "checkEndOfBlock" : "checkStartOfBlock"]() && i.remove();
                            b["moveToElementEdit" + (d ? "End" : "Start")](o);
                            b.select();
                            a.fire("saveSnapshot");
                            c = 1
                        } else if (g.blockLimit && g.blockLimit.is("td") && (j = g.blockLimit.getAscendant("table")) && b.checkBoundaryOfElement(j, d ? CKEDITOR.START : CKEDITOR.END) && (o = j[d ? "getPrevious" : "getNext"](e))) {
                            a.fire("saveSnapshot");
                            b["moveToElementEdit" + (d ? "End" : "Start")](o);
                            b.checkStartOfBlock() && b.checkEndOfBlock() ? o.remove() : b.select();
                            a.fire("saveSnapshot");
                            c = 1
                        } else if ((j = g.contains(["td", "th", "caption"])) && b.checkBoundaryOfElement(j, d ? CKEDITOR.START : CKEDITOR.END))c = 1
                    }
                    return!c
                });
                CKEDITOR.env.ie && this.attachListener(this, "click", c);
                !CKEDITOR.env.ie && !CKEDITOR.env.opera && this.attachListener(this, "mousedown", function (b) {
                    var d = b.data.getTarget();
                    if (d.is("img", "hr", "input", "textarea", "select")) {
                        a.getSelection().selectElement(d);
                        d.is("input", "textarea", "select") && b.data.preventDefault()
                    }
                });
                CKEDITOR.env.gecko && this.attachListener(this, "mouseup", function (b) {
                    if (b.data.$.button == 2) {
                        b = b.data.getTarget();
                        if (!b.getOuterHtml().replace(i, "")) {
                            var d = a.createRange();
                            d.moveToElementEditStart(b);
                            d.select(true)
                        }
                    }
                });
                if (CKEDITOR.env.webkit) {
                    this.attachListener(this, "click", function (a) {
                        a.data.getTarget().is("input", "select") && a.data.preventDefault()
                    });
                    this.attachListener(this, "mouseup", function (a) {
                        a.data.getTarget().is("input", "textarea") &&
                        a.data.preventDefault()
                    })
                }
            }
        }}, _: {detach: function () {
            this.editor.setData(this.editor.getData(), 0, 1);
            this.clearListeners();
            this.restoreAttrs();
            var a;
            if (a = this.removeCustomData("classes"))for (; a.length;)this.removeClass(a.pop());
            a = this.getDocument();
            var b = a.getHead();
            if (b.getCustomData("stylesheet")) {
                var d = a.getCustomData("stylesheet_ref");
                if (--d)a.setCustomData("stylesheet_ref", d); else {
                    a.removeCustomData("stylesheet_ref");
                    b.removeCustomData("stylesheet").remove()
                }
            }
            delete this.editor
        }}});
        CKEDITOR.editor.prototype.editable =
            function (a) {
                var b = this._.editable;
                if (b && a)return 0;
                if (arguments.length)b = this._.editable = a ? a instanceof CKEDITOR.editable ? a : new CKEDITOR.editable(this, a) : (b && b.detach(), null);
                return b
            };
        var i = /(^|<body\b[^>]*>)\s*<(p|div|address|h\d|center|pre)[^>]*>\s*(?:<br[^>]*>|&nbsp;|\u00A0|&#160;)?\s*(:?<\/\2>)?\s*(?=$|<\/body>)/gi, e = CKEDITOR.dom.walker.whitespaces(true), j = CKEDITOR.dom.walker.bookmark(false, true);
        CKEDITOR.on("instanceLoaded", function (a) {
            var d = a.editor;
            d.on("insertElement", function (a) {
                a = a.data;
                if (a.type == CKEDITOR.NODE_ELEMENT && (a.is("input") || a.is("textarea"))) {
                    a.getAttribute("contentEditable") != "false" && a.data("cke-editable", a.hasAttribute("contenteditable") ? "true" : "1");
                    a.setAttribute("contentEditable", false)
                }
            });
            d.on("selectionChange", function (a) {
                if (!d.readOnly) {
                    var e = d.getSelection();
                    if (e && !e.isLocked) {
                        e = d.checkDirty();
                        d.fire("lockSnapshot");
                        b(a);
                        d.fire("unlockSnapshot");
                        !e && d.resetDirty()
                    }
                }
            })
        });
        CKEDITOR.on("instanceCreated", function (a) {
            var b = a.editor;
            b.on("mode", function () {
                var a = b.editable();
                if (a && a.isInline()) {
                    var d = this.lang.editor + ", " + this.name;
                    a.changeAttr("role", "textbox");
                    a.changeAttr("aria-label", d);
                    a.changeAttr("title", d);
                    if (d = this.ui.space(this.elementMode == CKEDITOR.ELEMENT_MODE_INLINE ? "top" : "contents")) {
                        var e = CKEDITOR.tools.getNextId(), c = CKEDITOR.dom.element.createFromHtml('<span id="' + e + '" class="cke_voice_label">' + this.lang.common.editorHelp + "</span>");
                        d.append(c);
                        a.changeAttr("aria-describedby", e)
                    }
                }
            })
        });
        CKEDITOR.addCss(".cke_editable{cursor:text}.cke_editable img,.cke_editable input,.cke_editable textarea{cursor:default}");
        var k = function () {
            function b(a) {
                return a.type == CKEDITOR.NODE_ELEMENT
            }

            function e(a, d) {
                var c, f, g, i, j = [], o = d.range.startContainer;
                c = d.range.startPath();
                for (var o = h[o.getName()], l = 0, k = a.getChildren(), q = k.count(), s = -1, t = -1, z = 0, x = c.contains(h.$list); l < q; ++l) {
                    c = k.getItem(l);
                    if (b(c)) {
                        g = c.getName();
                        if (x && g in CKEDITOR.dtd.$list)j = j.concat(e(c, d)); else {
                            i = !!o[g];
                            if (g == "br" && c.data("cke-eol") && (!l || l == q - 1)) {
                                z = (f = l ? j[l - 1].node : k.getItem(l + 1)) && (!b(f) || !f.is("br"));
                                f = f && b(f) && h.$block[f.getName()]
                            }
                            s == -1 && !i && (s =
                                l);
                            i || (t = l);
                            j.push({isElement: 1, isLineBreak: z, isBlock: c.isBlockBoundary(), hasBlockSibling: f, node: c, name: g, allowed: i});
                            f = z = 0
                        }
                    } else j.push({isElement: 0, node: c, allowed: 1})
                }
                if (s > -1)j[s].firstNotAllowed = 1;
                if (t > -1)j[t].lastNotAllowed = 1;
                return j
            }

            function c(a, d) {
                var e = [], f = a.getChildren(), g = f.count(), i, j = 0, o = h[d], k = !a.is(h.$inline) || a.is("br");
                for (k && e.push(" "); j < g; j++) {
                    i = f.getItem(j);
                    b(i) && !i.is(o) ? e = e.concat(c(i, d)) : e.push(i)
                }
                k && e.push(" ");
                return e
            }

            function f(a) {
                return a && b(a) && (a.is(h.$removeEmpty) ||
                    a.is("a") && !a.isBlockBoundary())
            }

            function g(a, d, e, c) {
                var f = a.clone(), i, h;
                f.setEndAt(d, CKEDITOR.POSITION_BEFORE_END);
                if ((i = (new CKEDITOR.dom.walker(f)).next()) && b(i) && j[i.getName()] && (h = i.getPrevious()) && b(h) && !h.getParent().equals(a.startContainer) && e.contains(h) && c.contains(i) && i.isIdentical(h)) {
                    i.moveChildren(h);
                    i.remove();
                    g(a, d, e, c)
                }
            }

            function i(a, d) {
                function e(a, d) {
                    if (d.isBlock && d.isElement && !d.node.is("br") && b(a) && a.is("br")) {
                        a.remove();
                        return 1
                    }
                }

                var c = d.endContainer.getChild(d.endOffset), f = d.endContainer.getChild(d.endOffset -
                    1);
                c && e(c, a[a.length - 1]);
                if (f && e(f, a[0])) {
                    d.setEnd(d.endContainer, d.endOffset - 1);
                    d.collapse()
                }
            }

            var h = CKEDITOR.dtd, j = {p: 1, div: 1, h1: 1, h2: 1, h3: 1, h4: 1, h5: 1, h6: 1, ul: 1, ol: 1, li: 1, pre: 1, dl: 1, blockquote: 1}, k = {p: 1, div: 1, h1: 1, h2: 1, h3: 1, h4: 1, h5: 1, h6: 1}, x = CKEDITOR.tools.extend({}, h.$inline);
            delete x.br;
            return function (j, t, r) {
                var u = j.editor;
                j.getDocument();
                var A = u.getSelection().getRanges()[0], B = false;
                if (t == "unfiltered_html") {
                    t = "html";
                    B = true
                }
                if (!A.checkReadOnly()) {
                    var y = (new CKEDITOR.dom.elementPath(A.startContainer,
                        A.root)).blockLimit || A.root, t = {type: t, dontFilter: B, editable: j, editor: u, range: A, blockLimit: y, mergeCandidates: [], zombies: []}, u = t.range, B = t.mergeCandidates, C, D, F, E;
                    if (t.type == "text" && u.shrink(CKEDITOR.SHRINK_ELEMENT, true, false)) {
                        C = CKEDITOR.dom.element.createFromHtml("<span>&nbsp;</span>", u.document);
                        u.insertNode(C);
                        u.setStartAfter(C)
                    }
                    D = new CKEDITOR.dom.elementPath(u.startContainer);
                    t.endPath = F = new CKEDITOR.dom.elementPath(u.endContainer);
                    if (!u.collapsed) {
                        var y = F.block || F.blockLimit, K = u.getCommonAncestor();
                        y && (!y.equals(K) && !y.contains(K) && u.checkEndOfBlock()) && t.zombies.push(y);
                        u.deleteContents()
                    }
                    for (; (E = b(u.startContainer) && u.startContainer.getChild(u.startOffset - 1)) && b(E) && E.isBlockBoundary() && D.contains(E);)u.moveToPosition(E, CKEDITOR.POSITION_BEFORE_END);
                    g(u, t.blockLimit, D, F);
                    if (C) {
                        u.setEndBefore(C);
                        u.collapse();
                        C.remove()
                    }
                    C = u.startPath();
                    if (y = C.contains(f, false, 1)) {
                        u.splitElement(y);
                        t.inlineStylesRoot = y;
                        t.inlineStylesPeak = C.lastElement
                    }
                    C = u.createBookmark();
                    (y = C.startNode.getPrevious(a)) && b(y) &&
                        f(y) && B.push(y);
                    (y = C.startNode.getNext(a)) && b(y) && f(y) && B.push(y);
                    for (y = C.startNode; (y = y.getParent()) && f(y);)B.push(y);
                    u.moveToBookmark(C);
                    if (r) {
                        E = r;
                        r = t.range;
                        if (t.type == "text" && t.inlineStylesRoot) {
                            C = E;
                            E = t.inlineStylesPeak;
                            u = E.getDocument().createText("{cke-peak}");
                            for (B = t.inlineStylesRoot.getParent(); !E.equals(B);) {
                                u = u.appendTo(E.clone());
                                E = E.getParent()
                            }
                            E = u.getOuterHtml().replace("{cke-peak}", C)
                        }
                        C = t.blockLimit.getName();
                        if (/^\s+|\s+$/.test(E) && "span"in CKEDITOR.dtd[C]) {
                            var I = '<span data-cke-marker="1">&nbsp;</span>';
                            E = I + E + I
                        }
                        E = t.editor.dataProcessor.toHtml(E, null, false, t.dontFilter);
                        C = r.document.createElement("body");
                        C.setHtml(E);
                        if (I) {
                            C.getFirst().remove();
                            C.getLast().remove()
                        }
                        if ((I = r.startPath().block) && !(I.getChildCount() == 1 && I.getBogus()))a:{
                            var G;
                            if (C.getChildCount() == 1 && b(G = C.getFirst()) && G.is(k)) {
                                I = G.getElementsByTag("*");
                                r = 0;
                                for (u = I.count(); r < u; r++) {
                                    E = I.getItem(r);
                                    if (!E.is(x))break a
                                }
                                G.moveChildren(G.getParent(1));
                                G.remove()
                            }
                        }
                        t.dataWrapper = C;
                        G = t.range;
                        var I = G.document, H, r = t.blockLimit;
                        C = 0;
                        var L;
                        E = [];
                        var J,
                            P, B = u = 0, M, Q;
                        D = G.startContainer;
                        var y = t.endPath.elements[0], R;
                        F = y.getPosition(D);
                        K = !!y.getCommonAncestor(D) && F != CKEDITOR.POSITION_IDENTICAL && !(F & CKEDITOR.POSITION_CONTAINS + CKEDITOR.POSITION_IS_CONTAINED);
                        D = e(t.dataWrapper, t);
                        for (i(D, G); C < D.length; C++) {
                            F = D[C];
                            if (H = F.isLineBreak) {
                                H = G;
                                M = r;
                                var N = void 0, U = void 0;
                                if (F.hasBlockSibling)H = 1; else {
                                    N = H.startContainer.getAscendant(h.$block, 1);
                                    if (!N || !N.is({div: 1, p: 1}))H = 0; else {
                                        U = N.getPosition(M);
                                        if (U == CKEDITOR.POSITION_IDENTICAL || U == CKEDITOR.POSITION_CONTAINS)H =
                                            0; else {
                                            M = H.splitElement(N);
                                            H.moveToPosition(M, CKEDITOR.POSITION_AFTER_START);
                                            H = 1
                                        }
                                    }
                                }
                            }
                            if (H)B = C > 0; else {
                                H = G.startPath();
                                if (!F.isBlock && (P = t.editor.config.enterMode != CKEDITOR.ENTER_BR && t.editor.config.autoParagraph !== false ? t.editor.config.enterMode == CKEDITOR.ENTER_DIV ? "div" : "p" : false) && !H.block && H.blockLimit && H.blockLimit.equals(G.root)) {
                                    P = I.createElement(P);
                                    !CKEDITOR.env.ie && P.appendBogus();
                                    G.insertNode(P);
                                    !CKEDITOR.env.ie && (L = P.getBogus()) && L.remove();
                                    G.moveToPosition(P, CKEDITOR.POSITION_BEFORE_END)
                                }
                                if ((H =
                                    G.startPath().block) && !H.equals(J)) {
                                    if (L = H.getBogus()) {
                                        L.remove();
                                        E.push(H)
                                    }
                                    J = H
                                }
                                F.firstNotAllowed && (u = 1);
                                if (u && F.isElement) {
                                    H = G.startContainer;
                                    for (M = null; H && !h[H.getName()][F.name];) {
                                        if (H.equals(r)) {
                                            H = null;
                                            break
                                        }
                                        M = H;
                                        H = H.getParent()
                                    }
                                    if (H) {
                                        if (M) {
                                            Q = G.splitElement(M);
                                            t.zombies.push(Q);
                                            t.zombies.push(M)
                                        }
                                    } else {
                                        M = r.getName();
                                        R = !C;
                                        H = C == D.length - 1;
                                        M = c(F.node, M);
                                        for (var N = [], U = M.length, T = 0, V = void 0, W = 0, S = -1; T < U; T++) {
                                            V = M[T];
                                            if (V == " ") {
                                                if (!W && (!R || T)) {
                                                    N.push(new CKEDITOR.dom.text(" "));
                                                    S = N.length
                                                }
                                                W = 1
                                            } else {
                                                N.push(V);
                                                W = 0
                                            }
                                        }
                                        H && S == N.length && N.pop();
                                        R = N
                                    }
                                }
                                if (R) {
                                    for (; H = R.pop();)G.insertNode(H);
                                    R = 0
                                } else G.insertNode(F.node);
                                if (F.lastNotAllowed && C < D.length - 1) {
                                    (Q = K ? y : Q) && G.setEndAt(Q, CKEDITOR.POSITION_AFTER_START);
                                    u = 0
                                }
                                G.collapse()
                            }
                        }
                        t.dontMoveCaret = B;
                        t.bogusNeededBlocks = E
                    }
                    L = t.range;
                    var O;
                    Q = t.bogusNeededBlocks;
                    for (R = L.createBookmark(); J = t.zombies.pop();)if (J.getParent()) {
                        P = L.clone();
                        P.moveToElementEditStart(J);
                        P.removeEmptyBlocksAtEnd()
                    }
                    if (Q)for (; J = Q.pop();)J.append(CKEDITOR.env.ie ? L.document.createText(" ") : L.document.createElement("br"));
                    for (; J = t.mergeCandidates.pop();)J.mergeSiblings();
                    L.moveToBookmark(R);
                    if (!t.dontMoveCaret) {
                        for (J = b(L.startContainer) && L.startContainer.getChild(L.startOffset - 1); J && b(J) && !J.is(h.$empty);) {
                            if (J.isBlockBoundary())L.moveToPosition(J, CKEDITOR.POSITION_BEFORE_END); else {
                                if (f(J) && J.getHtml().match(/(\s|&nbsp;)$/g)) {
                                    O = null;
                                    break
                                }
                                O = L.clone();
                                O.moveToPosition(J, CKEDITOR.POSITION_BEFORE_END)
                            }
                            J = J.getLast(a)
                        }
                        O && L.moveToRange(O)
                    }
                    A.select();
                    d(j)
                }
            }
        }()
    }(), function () {
        function b() {
            var a = this.getSelection(1);
            if (a.getType() !=
                CKEDITOR.SELECTION_NONE) {
                this.fire("selectionCheck", a);
                var b = this.elementPath();
                if (!b.compare(this._.selectionPreviousPath)) {
                    this._.selectionPreviousPath = b;
                    this.fire("selectionChange", {selection: a, path: b})
                }
            }
        }

        function c() {
            e = true;
            if (!i) {
                a.call(this);
                i = CKEDITOR.tools.setTimeout(a, 200, this)
            }
        }

        function a() {
            i = null;
            if (e) {
                CKEDITOR.tools.setTimeout(b, 0, this);
                e = false
            }
        }

        function f(a) {
            function b(d, e) {
                return!d || d.type == CKEDITOR.NODE_TEXT ? false : a.clone()["moveToElementEdit" + (e ? "End" : "Start")](d)
            }

            if (!(a.root instanceof
                CKEDITOR.editable))return false;
            var d = a.startContainer, e = a.getPreviousNode(j, null, d), c = a.getNextNode(j, null, d);
            return b(e) || b(c, 1) || !e && !c && !(d.type == CKEDITOR.NODE_ELEMENT && d.isBlockBoundary() && d.getBogus()) ? true : false
        }

        function h(a) {
            return a.getCustomData("cke-fillingChar")
        }

        function g(a, b) {
            var e = a && a.removeCustomData("cke-fillingChar");
            if (e) {
                if (b !== false) {
                    var c, f = a.getDocument().getSelection().getNative(), g = f && f.type != "None" && f.getRangeAt(0);
                    if (e.getLength() > 1 && g && g.intersectsNode(e.$)) {
                        c = [f.anchorOffset,
                            f.focusOffset];
                        g = f.focusNode == e.$ && f.focusOffset > 0;
                        f.anchorNode == e.$ && f.anchorOffset > 0 && c[0]--;
                        g && c[1]--;
                        var i;
                        g = f;
                        if (!g.isCollapsed) {
                            i = g.getRangeAt(0);
                            i.setStart(g.anchorNode, g.anchorOffset);
                            i.setEnd(g.focusNode, g.focusOffset);
                            i = i.collapsed
                        }
                        i && c.unshift(c.pop())
                    }
                }
                e.setText(d(e.getText()));
                if (c) {
                    e = f.getRangeAt(0);
                    e.setStart(e.startContainer, c[0]);
                    e.setEnd(e.startContainer, c[1]);
                    f.removeAllRanges();
                    f.addRange(e)
                }
            }
        }

        function d(a) {
            return a.replace(/\u200B( )?/g, function (a) {
                return a[1] ? " " : ""
            })
        }

        var i,
            e, j = CKEDITOR.dom.walker.invisible(1);
        CKEDITOR.on("instanceCreated", function (a) {
            function d() {
                var a = e.getSelection();
                a && a.removeAllRanges()
            }

            var e = a.editor;
            e.define("selectionChange", {errorProof: 1});
            e.on("contentDom", function () {
                var a = e.document, d = CKEDITOR.document, f = e.editable(), i = a.getBody(), h = a.getDocumentElement(), j = f.isInline(), l;
                CKEDITOR.env.gecko && f.attachListener(f, "focus", function (a) {
                    a.removeListener();
                    if (l !== 0) {
                        a = e.getSelection().getNative();
                        if (a.isCollapsed && a.anchorNode == f.$) {
                            a = e.createRange();
                            a.moveToElementEditStart(f);
                            a.select()
                        }
                    }
                }, null, null, -2);
                f.attachListener(f, "focus", function () {
                    e.unlockSelection(l);
                    l = 0
                }, null, null, -1);
                f.attachListener(f, "mousedown", function () {
                    l = 0
                });
                if (CKEDITOR.env.ie || CKEDITOR.env.opera || j) {
                    var n, m = function () {
                        n = e.getSelection(1);
                        n.lock()
                    };
                    k ? f.attachListener(f, "beforedeactivate", m, null, null, -1) : f.attachListener(e, "selectionCheck", m, null, null, -1);
                    f.attachListener(f, "blur", function () {
                        e.lockSelection(n);
                        l = 1
                    }, null, null, -1)
                }
                if (CKEDITOR.env.ie && !j) {
                    var u;
                    f.attachListener(f,
                        "mousedown", function (a) {
                            a.data.$.button == 2 && e.document.$.selection.type == "None" && (u = e.window.getScrollPosition())
                        });
                    f.attachListener(f, "mouseup", function (a) {
                        if (a.data.$.button == 2 && u) {
                            e.document.$.documentElement.scrollLeft = u.x;
                            e.document.$.documentElement.scrollTop = u.y
                        }
                        u = null
                    });
                    if (a.$.compatMode != "BackCompat") {
                        if (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat)h.on("mousedown", function (a) {
                            function b(a) {
                                a = a.data.$;
                                if (c) {
                                    var d = i.$.createTextRange();
                                    try {
                                        d.moveToPoint(a.x, a.y)
                                    } catch (e) {
                                    }
                                    c.setEndPoint(g.compareEndPoints("StartToStart",
                                        d) < 0 ? "EndToEnd" : "StartToStart", d);
                                    c.select()
                                }
                            }

                            function e() {
                                h.removeListener("mousemove", b);
                                d.removeListener("mouseup", e);
                                h.removeListener("mouseup", e);
                                c.select()
                            }

                            a = a.data;
                            if (a.getTarget().is("html") && a.$.y < h.$.clientHeight && a.$.x < h.$.clientWidth) {
                                var c = i.$.createTextRange();
                                try {
                                    c.moveToPoint(a.$.x, a.$.y)
                                } catch (f) {
                                }
                                var g = c.duplicate();
                                h.on("mousemove", b);
                                d.on("mouseup", e);
                                h.on("mouseup", e)
                            }
                        });
                        if (CKEDITOR.env.version > 7) {
                            h.on("mousedown", function (a) {
                                if (a.data.getTarget().is("html")) {
                                    d.on("mouseup", A);
                                    h.on("mouseup", A)
                                }
                            });
                            var A = function () {
                                d.removeListener("mouseup", A);
                                h.removeListener("mouseup", A);
                                var b = CKEDITOR.document.$.selection, e = b.createRange();
                                b.type != "None" && e.parentElement().ownerDocument == a.$ && e.select()
                            }
                        }
                    }
                }
                f.attachListener(f, "selectionchange", b, e);
                f.attachListener(f, "keyup", c, e);
                f.attachListener(f, "focus", function () {
                    e.forceNextSelectionCheck();
                    e.selectionChange(1)
                });
                if (j ? CKEDITOR.env.webkit || CKEDITOR.env.gecko : CKEDITOR.env.opera) {
                    var B;
                    f.attachListener(f, "mousedown", function () {
                        B = 1
                    });
                    f.attachListener(a.getDocumentElement(), "mouseup", function () {
                        B && c.call(e);
                        B = 0
                    })
                } else f.attachListener(CKEDITOR.env.ie ? f : a.getDocumentElement(), "mouseup", c, e);
                CKEDITOR.env.webkit && f.attachListener(a, "keydown", function (a) {
                    switch (a.data.getKey()) {
                        case 13:
                        case 33:
                        case 34:
                        case 35:
                        case 36:
                        case 37:
                        case 39:
                        case 8:
                        case 45:
                        case 46:
                            g(f)
                    }
                }, null, null, -1)
            });
            e.on("contentDomUnload", e.forceNextSelectionCheck, e);
            e.on("dataReady", function () {
                e.selectionChange(1)
            });
            CKEDITOR.env.ie9Compat && e.on("beforeDestroy", d, null,
                null, 9);
            CKEDITOR.env.webkit && e.on("setData", d);
            e.on("contentDomUnload", function () {
                e.unlockSelection()
            })
        });
        CKEDITOR.on("instanceReady", function (a) {
            var b = a.editor;
            if (CKEDITOR.env.webkit) {
                b.on("selectionChange", function () {
                    var a = b.editable(), d = h(a);
                    d && (d.getCustomData("ready") ? g(a) : d.setCustomData("ready", 1))
                }, null, null, -1);
                b.on("beforeSetMode", function () {
                    g(b.editable())
                }, null, null, -1);
                var e, c, a = function () {
                    var a = b.editable();
                    if (a)if (a = h(a)) {
                        var f = b.document.$.defaultView.getSelection();
                        f.type == "Caret" &&
                            f.anchorNode == a.$ && (c = 1);
                        e = a.getText();
                        a.setText(d(e))
                    }
                }, f = function () {
                    var a = b.editable();
                    if (a)if (a = h(a)) {
                        a.setText(e);
                        if (c) {
                            b.document.$.defaultView.getSelection().setPosition(a.$, a.getLength());
                            c = 0
                        }
                    }
                };
                b.on("beforeUndoImage", a);
                b.on("afterUndoImage", f);
                b.on("beforeGetData", a, null, null, 0);
                b.on("getData", f)
            }
        });
        CKEDITOR.editor.prototype.selectionChange = function (a) {
            (a ? b : c).call(this)
        };
        CKEDITOR.editor.prototype.getSelection = function (a) {
            if (this._.savedSelection && !a)return this._.savedSelection;
            return(a =
                this.editable()) ? new CKEDITOR.dom.selection(a) : null
        };
        CKEDITOR.editor.prototype.lockSelection = function (a) {
            a = a || this.getSelection(1);
            if (a.getType() != CKEDITOR.SELECTION_NONE) {
                !a.isLocked && a.lock();
                this._.savedSelection = a;
                return true
            }
            return false
        };
        CKEDITOR.editor.prototype.unlockSelection = function (a) {
            var b = this._.savedSelection;
            if (b) {
                b.unlock(a);
                delete this._.savedSelection;
                return true
            }
            return false
        };
        CKEDITOR.editor.prototype.forceNextSelectionCheck = function () {
            delete this._.selectionPreviousPath
        };
        CKEDITOR.dom.document.prototype.getSelection =
            function () {
                return new CKEDITOR.dom.selection(this)
            };
        CKEDITOR.dom.range.prototype.select = function () {
            var a = this.root instanceof CKEDITOR.editable ? this.root.editor.getSelection() : new CKEDITOR.dom.selection(this.root);
            a.selectRanges([this]);
            return a
        };
        CKEDITOR.SELECTION_NONE = 1;
        CKEDITOR.SELECTION_TEXT = 2;
        CKEDITOR.SELECTION_ELEMENT = 3;
        var k = typeof window.getSelection != "function";
        CKEDITOR.dom.selection = function (a) {
            var b = a instanceof CKEDITOR.dom.element;
            this.document = a instanceof CKEDITOR.dom.document ? a : a.getDocument();
            this.root = b ? a : this.document.getBody();
            this.isLocked = 0;
            this._ = {cache: {}};
            if (CKEDITOR.env.webkit) {
                a = this.document.getWindow().$.getSelection();
                if (a.type == "None" && this.document.getActive().equals(this.root) || a.type == "Caret" && a.anchorNode.nodeType == CKEDITOR.NODE_DOCUMENT) {
                    var d = new CKEDITOR.dom.range(this.root);
                    d.moveToPosition(this.root, CKEDITOR.POSITION_AFTER_START);
                    b = this.document.$.createRange();
                    b.setStart(d.startContainer.$, d.startOffset);
                    b.collapse(1);
                    var e = this.root.on("focus", function (a) {
                            a.cancel()
                        },
                        null, null, -100);
                    a.addRange(b);
                    e.removeListener()
                }
            }
            var a = this.getNative(), c;
            if (a)if (a.getRangeAt)c = (d = a.rangeCount && a.getRangeAt(0)) && new CKEDITOR.dom.node(d.commonAncestorContainer); else {
                try {
                    d = a.createRange()
                } catch (f) {
                }
                c = d && CKEDITOR.dom.element.get(d.item && d.item(0) || d.parentElement())
            }
            if (!c || !this.root.equals(c) && !this.root.contains(c)) {
                this._.cache.type = CKEDITOR.SELECTION_NONE;
                this._.cache.startElement = null;
                this._.cache.selectedElement = null;
                this._.cache.selectedText = "";
                this._.cache.ranges = new CKEDITOR.dom.rangeList
            }
            return this
        };
        var m = {img: 1, hr: 1, li: 1, table: 1, tr: 1, td: 1, th: 1, embed: 1, object: 1, ol: 1, ul: 1, a: 1, input: 1, form: 1, select: 1, textarea: 1, button: 1, fieldset: 1, thead: 1, tfoot: 1};
        CKEDITOR.dom.selection.prototype = {getNative: function () {
            return this._.cache.nativeSel !== void 0 ? this._.cache.nativeSel : this._.cache.nativeSel = k ? this.document.$.selection : this.document.getWindow().$.getSelection()
        }, getType: k ? function () {
            var a = this._.cache;
            if (a.type)return a.type;
            var b = CKEDITOR.SELECTION_NONE;
            try {
                var d = this.getNative(), e = d.type;
                if (e == "Text")b =
                    CKEDITOR.SELECTION_TEXT;
                if (e == "Control")b = CKEDITOR.SELECTION_ELEMENT;
                if (d.createRange().parentElement())b = CKEDITOR.SELECTION_TEXT
            } catch (c) {
            }
            return a.type = b
        } : function () {
            var a = this._.cache;
            if (a.type)return a.type;
            var b = CKEDITOR.SELECTION_TEXT, d = this.getNative();
            if (!d || !d.rangeCount)b = CKEDITOR.SELECTION_NONE; else if (d.rangeCount == 1) {
                var d = d.getRangeAt(0), e = d.startContainer;
                if (e == d.endContainer && e.nodeType == 1 && d.endOffset - d.startOffset == 1 && m[e.childNodes[d.startOffset].nodeName.toLowerCase()])b = CKEDITOR.SELECTION_ELEMENT
            }
            return a.type =
                b
        }, getRanges: function () {
            var a = k ? function () {
                function a(b) {
                    return(new CKEDITOR.dom.node(b)).getIndex()
                }

                var b = function (b, d) {
                    b = b.duplicate();
                    b.collapse(d);
                    var e = b.parentElement(), c = e.ownerDocument;
                    if (!e.hasChildNodes())return{container: e, offset: 0};
                    for (var f = e.children, g, i, h = b.duplicate(), j = 0, o = f.length - 1, k = -1, m, n; j <= o;) {
                        k = Math.floor((j + o) / 2);
                        g = f[k];
                        h.moveToElementText(g);
                        m = h.compareEndPoints("StartToStart", b);
                        if (m > 0)o = k - 1; else if (m < 0)j = k + 1; else {
                            if (CKEDITOR.env.ie9Compat && g.tagName == "BR") {
                                f = c.defaultView.getSelection();
                                return{container: f[d ? "anchorNode" : "focusNode"], offset: f[d ? "anchorOffset" : "focusOffset"]}
                            }
                            return{container: e, offset: a(g)}
                        }
                    }
                    if (k == -1 || k == f.length - 1 && m < 0) {
                        h.moveToElementText(e);
                        h.setEndPoint("StartToStart", b);
                        c = h.text.replace(/(\r\n|\r)/g, "\n").length;
                        f = e.childNodes;
                        if (!c) {
                            g = f[f.length - 1];
                            return g.nodeType != CKEDITOR.NODE_TEXT ? {container: e, offset: f.length} : {container: g, offset: g.nodeValue.length}
                        }
                        for (e = f.length; c > 0 && e > 0;) {
                            i = f[--e];
                            if (i.nodeType == CKEDITOR.NODE_TEXT) {
                                n = i;
                                c = c - i.nodeValue.length
                            }
                        }
                        return{container: n,
                            offset: -c}
                    }
                    h.collapse(m > 0 ? true : false);
                    h.setEndPoint(m > 0 ? "StartToStart" : "EndToStart", b);
                    c = h.text.replace(/(\r\n|\r)/g, "\n").length;
                    if (!c)return{container: e, offset: a(g) + (m > 0 ? 0 : 1)};
                    for (; c > 0;)try {
                        i = g[m > 0 ? "previousSibling" : "nextSibling"];
                        if (i.nodeType == CKEDITOR.NODE_TEXT) {
                            c = c - i.nodeValue.length;
                            n = i
                        }
                        g = i
                    } catch (C) {
                        return{container: e, offset: a(g)}
                    }
                    return{container: n, offset: m > 0 ? -c : n.nodeValue.length + c}
                };
                return function () {
                    var a = this.getNative(), d = a && a.createRange(), e = this.getType();
                    if (!a)return[];
                    if (e == CKEDITOR.SELECTION_TEXT) {
                        a =
                            new CKEDITOR.dom.range(this.root);
                        e = b(d, true);
                        a.setStart(new CKEDITOR.dom.node(e.container), e.offset);
                        e = b(d);
                        a.setEnd(new CKEDITOR.dom.node(e.container), e.offset);
                        a.endContainer.getPosition(a.startContainer) & CKEDITOR.POSITION_PRECEDING && a.endOffset <= a.startContainer.getIndex() && a.collapse();
                        return[a]
                    }
                    if (e == CKEDITOR.SELECTION_ELEMENT) {
                        for (var e = [], c = 0; c < d.length; c++) {
                            for (var f = d.item(c), g = f.parentNode, i = 0, a = new CKEDITOR.dom.range(this.root); i < g.childNodes.length && g.childNodes[i] != f; i++);
                            a.setStart(new CKEDITOR.dom.node(g),
                                i);
                            a.setEnd(new CKEDITOR.dom.node(g), i + 1);
                            e.push(a)
                        }
                        return e
                    }
                    return[]
                }
            }() : function () {
                var a = [], b, d = this.getNative();
                if (!d)return a;
                for (var e = 0; e < d.rangeCount; e++) {
                    var c = d.getRangeAt(e);
                    b = new CKEDITOR.dom.range(this.root);
                    b.setStart(new CKEDITOR.dom.node(c.startContainer), c.startOffset);
                    b.setEnd(new CKEDITOR.dom.node(c.endContainer), c.endOffset);
                    a.push(b)
                }
                return a
            };
            return function (b) {
                var d = this._.cache;
                if (d.ranges && !b)return d.ranges;
                if (!d.ranges)d.ranges = new CKEDITOR.dom.rangeList(a.call(this));
                if (b)for (var e =
                    d.ranges, c = 0; c < e.length; c++) {
                    var f = e[c];
                    f.getCommonAncestor().isReadOnly() && e.splice(c, 1);
                    if (!f.collapsed) {
                        if (f.startContainer.isReadOnly())for (var b = f.startContainer, g; b;) {
                            if ((g = b.type == CKEDITOR.NODE_ELEMENT) && b.is("body") || !b.isReadOnly())break;
                            g && b.getAttribute("contentEditable") == "false" && f.setStartAfter(b);
                            b = b.getParent()
                        }
                        b = f.startContainer;
                        g = f.endContainer;
                        var i = f.startOffset, h = f.endOffset, j = f.clone();
                        b && b.type == CKEDITOR.NODE_TEXT && (i >= b.getLength() ? j.setStartAfter(b) : j.setStartBefore(b));
                        g &&
                            g.type == CKEDITOR.NODE_TEXT && (h ? j.setEndAfter(g) : j.setEndBefore(g));
                        b = new CKEDITOR.dom.walker(j);
                        b.evaluator = function (a) {
                            if (a.type == CKEDITOR.NODE_ELEMENT && a.isReadOnly()) {
                                var b = f.clone();
                                f.setEndBefore(a);
                                f.collapsed && e.splice(c--, 1);
                                if (!(a.getPosition(j.endContainer) & CKEDITOR.POSITION_CONTAINS)) {
                                    b.setStartAfter(a);
                                    b.collapsed || e.splice(c + 1, 0, b)
                                }
                                return true
                            }
                            return false
                        };
                        b.next()
                    }
                }
                return d.ranges
            }
        }(), getStartElement: function () {
            var a = this._.cache;
            if (a.startElement !== void 0)return a.startElement;
            var b;
            switch (this.getType()) {
                case CKEDITOR.SELECTION_ELEMENT:
                    return this.getSelectedElement();
                case CKEDITOR.SELECTION_TEXT:
                    var d = this.getRanges()[0];
                    if (d) {
                        if (d.collapsed) {
                            b = d.startContainer;
                            b.type != CKEDITOR.NODE_ELEMENT && (b = b.getParent())
                        } else {
                            for (d.optimize(); ;) {
                                b = d.startContainer;
                                if (d.startOffset == (b.getChildCount ? b.getChildCount() : b.getLength()) && !b.isBlockBoundary())d.setStartAfter(b); else break
                            }
                            b = d.startContainer;
                            if (b.type != CKEDITOR.NODE_ELEMENT)return b.getParent();
                            b = b.getChild(d.startOffset);
                            if (!b ||
                                b.type != CKEDITOR.NODE_ELEMENT)b = d.startContainer; else for (d = b.getFirst(); d && d.type == CKEDITOR.NODE_ELEMENT;) {
                                b = d;
                                d = d.getFirst()
                            }
                        }
                        b = b.$
                    }
            }
            return a.startElement = b ? new CKEDITOR.dom.element(b) : null
        }, getSelectedElement: function () {
            var a = this._.cache;
            if (a.selectedElement !== void 0)return a.selectedElement;
            var b = this, d = CKEDITOR.tools.tryThese(function () {
                return b.getNative().createRange().item(0)
            }, function () {
                for (var a = b.getRanges()[0], d, e, c = 2; c && (!(d = a.getEnclosedNode()) || !(d.type == CKEDITOR.NODE_ELEMENT && m[d.getName()] &&
                    (e = d))); c--)a.shrink(CKEDITOR.SHRINK_ELEMENT);
                return e.$
            });
            return a.selectedElement = d ? new CKEDITOR.dom.element(d) : null
        }, getSelectedText: function () {
            var a = this._.cache;
            if (a.selectedText !== void 0)return a.selectedText;
            var b = this.getNative(), b = k ? b.type == "Control" ? "" : b.createRange().text : b.toString();
            return a.selectedText = b
        }, lock: function () {
            this.getRanges();
            this.getStartElement();
            this.getSelectedElement();
            this.getSelectedText();
            this._.cache.nativeSel = null;
            this.isLocked = 1
        }, unlock: function (a) {
            if (this.isLocked) {
                if (a)var b =
                    this.getSelectedElement(), d = !b && this.getRanges();
                this.isLocked = 0;
                this.reset();
                if (a)(a = b || d[0] && d[0].getCommonAncestor()) && a.getAscendant("body", 1) && (b ? this.selectElement(b) : this.selectRanges(d))
            }
        }, reset: function () {
            this._.cache = {}
        }, selectElement: function (a) {
            var b = new CKEDITOR.dom.range(this.root);
            b.setStartBefore(a);
            b.setEndAfter(a);
            this.selectRanges([b])
        }, selectRanges: function (a) {
            if (a.length)if (this.isLocked) {
                var b = CKEDITOR.document.getActive();
                this.unlock();
                this.selectRanges(a);
                this.lock();
                !b.equals(this.root) &&
                b.focus()
            } else {
                if (k) {
                    var d = CKEDITOR.dom.walker.whitespaces(true), e = /\ufeff|\u00a0/, c = {table: 1, tbody: 1, tr: 1};
                    if (a.length > 1) {
                        b = a[a.length - 1];
                        a[0].setEnd(b.endContainer, b.endOffset)
                    }
                    var b = a[0], a = b.collapsed, i, h, j, x = b.getEnclosedNode();
                    if (x && x.type == CKEDITOR.NODE_ELEMENT && x.getName()in m && (!x.is("a") || !x.getText()))try {
                        j = x.$.createControlRange();
                        j.addElement(x.$);
                        j.select();
                        return
                    } catch (w) {
                    }
                    (b.startContainer.type == CKEDITOR.NODE_ELEMENT && b.startContainer.getName()in c || b.endContainer.type == CKEDITOR.NODE_ELEMENT &&
                        b.endContainer.getName()in c) && b.shrink(CKEDITOR.NODE_ELEMENT, true);
                    j = b.createBookmark();
                    var c = j.startNode, v;
                    if (!a)v = j.endNode;
                    j = b.document.$.body.createTextRange();
                    j.moveToElementText(c.$);
                    j.moveStart("character", 1);
                    if (v) {
                        e = b.document.$.body.createTextRange();
                        e.moveToElementText(v.$);
                        j.setEndPoint("EndToEnd", e);
                        j.moveEnd("character", -1)
                    } else {
                        i = c.getNext(d);
                        h = c.hasAscendant("pre");
                        i = !(i && i.getText && i.getText().match(e)) && (h || !c.hasPrevious() || c.getPrevious().is && c.getPrevious().is("br"));
                        h = b.document.createElement("span");
                        h.setHtml("&#65279;");
                        h.insertBefore(c);
                        i && b.document.createText("﻿").insertBefore(c)
                    }
                    b.setStartBefore(c);
                    c.remove();
                    if (a) {
                        if (i) {
                            j.moveStart("character", -1);
                            j.select();
                            b.document.$.selection.clear()
                        } else j.select();
                        b.moveToPosition(h, CKEDITOR.POSITION_BEFORE_START);
                        h.remove()
                    } else {
                        b.setEndBefore(v);
                        v.remove();
                        j.select()
                    }
                } else {
                    v = this.getNative();
                    if (!v)return;
                    if (CKEDITOR.env.opera) {
                        b = this.document.$.createRange();
                        b.selectNodeContents(this.root.$);
                        v.addRange(b)
                    }
                    this.removeAllRanges();
                    for (e = 0; e < a.length; e++) {
                        if (e <
                            a.length - 1) {
                            b = a[e];
                            j = a[e + 1];
                            h = b.clone();
                            h.setStart(b.endContainer, b.endOffset);
                            h.setEnd(j.startContainer, j.startOffset);
                            if (!h.collapsed) {
                                h.shrink(CKEDITOR.NODE_ELEMENT, true);
                                i = h.getCommonAncestor();
                                h = h.getEnclosedNode();
                                if (i.isReadOnly() || h && h.isReadOnly()) {
                                    j.setStart(b.startContainer, b.startOffset);
                                    a.splice(e--, 1);
                                    continue
                                }
                            }
                        }
                        b = a[e];
                        j = this.document.$.createRange();
                        i = b.startContainer;
                        if (CKEDITOR.env.opera && b.collapsed && i.type == CKEDITOR.NODE_ELEMENT) {
                            h = i.getChild(b.startOffset - 1);
                            d = i.getChild(b.startOffset);
                            if (!h && !d && i.is(CKEDITOR.dtd.$removeEmpty) || h && h.type == CKEDITOR.NODE_ELEMENT || d && d.type == CKEDITOR.NODE_ELEMENT) {
                                b.insertNode(this.document.createText(""));
                                b.collapse(1)
                            }
                        }
                        if (b.collapsed && CKEDITOR.env.webkit && f(b)) {
                            i = this.root;
                            g(i, false);
                            h = i.getDocument().createText("​");
                            i.setCustomData("cke-fillingChar", h);
                            b.insertNode(h);
                            if ((i = h.getNext()) && !h.getPrevious() && i.type == CKEDITOR.NODE_ELEMENT && i.getName() == "br") {
                                g(this.root);
                                b.moveToPosition(i, CKEDITOR.POSITION_BEFORE_START)
                            } else b.moveToPosition(h, CKEDITOR.POSITION_AFTER_END)
                        }
                        j.setStart(b.startContainer.$,
                            b.startOffset);
                        try {
                            j.setEnd(b.endContainer.$, b.endOffset)
                        } catch (r) {
                            if (r.toString().indexOf("NS_ERROR_ILLEGAL_VALUE") >= 0) {
                                b.collapse(1);
                                j.setEnd(b.endContainer.$, b.endOffset)
                            } else throw r;
                        }
                        v.addRange(j)
                    }
                }
                this.reset();
                this.root.fire("selectionchange")
            }
        }, createBookmarks: function (a) {
            return this.getRanges().createBookmarks(a)
        }, createBookmarks2: function (a) {
            return this.getRanges().createBookmarks2(a)
        }, selectBookmarks: function (a) {
            for (var b = [], d = 0; d < a.length; d++) {
                var e = new CKEDITOR.dom.range(this.root);
                e.moveToBookmark(a[d]);
                b.push(e)
            }
            this.selectRanges(b);
            return this
        }, getCommonAncestor: function () {
            var a = this.getRanges();
            return a[0].startContainer.getCommonAncestor(a[a.length - 1].endContainer)
        }, scrollIntoView: function () {
            this.type != CKEDITOR.SELECTION_NONE && this.getRanges()[0].scrollIntoView()
        }, removeAllRanges: function () {
            var a = this.getNative();
            try {
                a && a[k ? "empty" : "removeAllRanges"]()
            } catch (b) {
            }
            this.reset()
        }}
    }(), CKEDITOR.editor.prototype.attachStyleStateChange = function (b, c) {
        var a = this._.styleStateChangeCallbacks;
        if (!a) {
            a = this._.styleStateChangeCallbacks =
                [];
            this.on("selectionChange", function (b) {
                for (var c = 0; c < a.length; c++) {
                    var g = a[c], d = g.style.checkActive(b.data.path) ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF;
                    g.fn.call(this, d)
                }
            })
        }
        a.push({style: b, fn: c})
    }, CKEDITOR.STYLE_BLOCK = 1, CKEDITOR.STYLE_INLINE = 2, CKEDITOR.STYLE_OBJECT = 3, function () {
        function b(a, b) {
            for (var d, e; a = a.getParent();) {
                if (a.equals(b))break;
                if (a.getAttribute("data-nostyle"))d = a; else if (!e) {
                    var c = a.getAttribute("contentEditable");
                    c == "false" ? d = a : c == "true" && (e = 1)
                }
            }
            return d
        }

        function c(a) {
            var d =
                a.document;
            if (a.collapsed) {
                d = q(this, d);
                a.insertNode(d);
                a.moveToPosition(d, CKEDITOR.POSITION_BEFORE_END)
            } else {
                var e = this.element, c = this._.definition, f, g = c.ignoreReadonly, i = g || c.includeReadonly;
                i == void 0 && (i = a.root.getCustomData("cke_includeReadonly"));
                var h = CKEDITOR.dtd[e] || (f = true, CKEDITOR.dtd.span);
                a.enlarge(CKEDITOR.ENLARGE_INLINE, 1);
                a.trim();
                var j = a.createBookmark(), k = j.startNode, o = j.endNode, m = k, l;
                if (!g) {
                    var p = a.getCommonAncestor(), g = b(k, p), p = b(o, p);
                    g && (m = g.getNextSourceNode(true));
                    p && (o = p)
                }
                for (m.getPosition(o) ==
                         CKEDITOR.POSITION_FOLLOWING && (m = 0); m;) {
                    g = false;
                    if (m.equals(o)) {
                        m = null;
                        g = true
                    } else {
                        var s = m.type, t = s == CKEDITOR.NODE_ELEMENT ? m.getName() : null, p = t && m.getAttribute("contentEditable") == "false", x = t && m.getAttribute("data-nostyle");
                        if (t && m.data("cke-bookmark")) {
                            m = m.getNextSourceNode(true);
                            continue
                        }
                        if (!t || h[t] && !x && (!p || i) && (m.getPosition(o) | CKEDITOR.POSITION_PRECEDING | CKEDITOR.POSITION_IDENTICAL | CKEDITOR.POSITION_IS_CONTAINED) == CKEDITOR.POSITION_PRECEDING + CKEDITOR.POSITION_IDENTICAL + CKEDITOR.POSITION_IS_CONTAINED &&
                            (!c.childRule || c.childRule(m))) {
                            var v = m.getParent();
                            if (v && ((v.getDtd() || CKEDITOR.dtd.span)[e] || f) && (!c.parentRule || c.parentRule(v))) {
                                if (!l && (!t || !CKEDITOR.dtd.$removeEmpty[t] || (m.getPosition(o) | CKEDITOR.POSITION_PRECEDING | CKEDITOR.POSITION_IDENTICAL | CKEDITOR.POSITION_IS_CONTAINED) == CKEDITOR.POSITION_PRECEDING + CKEDITOR.POSITION_IDENTICAL + CKEDITOR.POSITION_IS_CONTAINED)) {
                                    l = a.clone();
                                    l.setStartBefore(m)
                                }
                                if (s == CKEDITOR.NODE_TEXT || p || s == CKEDITOR.NODE_ELEMENT && !m.getChildCount()) {
                                    for (var s = m, w; (g = !s.getNext(A)) &&
                                        (w = s.getParent(), h[w.getName()]) && (w.getPosition(k) | CKEDITOR.POSITION_FOLLOWING | CKEDITOR.POSITION_IDENTICAL | CKEDITOR.POSITION_IS_CONTAINED) == CKEDITOR.POSITION_FOLLOWING + CKEDITOR.POSITION_IDENTICAL + CKEDITOR.POSITION_IS_CONTAINED && (!c.childRule || c.childRule(w));)s = w;
                                    l.setEndAfter(s)
                                }
                            } else g = true
                        } else g = true;
                        m = m.getNextSourceNode(x || p && !i)
                    }
                    if (g && l && !l.collapsed) {
                        for (var g = q(this, d), p = g.hasAttributes(), x = l.getCommonAncestor(), s = {}, t = {}, v = {}, z = {}, r, O, u; g && x;) {
                            if (x.getName() == e) {
                                for (r in c.attributes)if (!z[r] &&
                                    (u = x.getAttribute(O)))g.getAttribute(r) == u ? t[r] = 1 : z[r] = 1;
                                for (O in c.styles)if (!v[O] && (u = x.getStyle(O)))g.getStyle(O) == u ? s[O] = 1 : v[O] = 1
                            }
                            x = x.getParent()
                        }
                        for (r in t)g.removeAttribute(r);
                        for (O in s)g.removeStyle(O);
                        p && !g.hasAttributes() && (g = null);
                        if (g) {
                            l.extractContents().appendTo(g);
                            n.call(this, g);
                            l.insertNode(g);
                            g.mergeSiblings();
                            CKEDITOR.env.ie || g.$.normalize()
                        } else {
                            g = new CKEDITOR.dom.element("span");
                            l.extractContents().appendTo(g);
                            l.insertNode(g);
                            n.call(this, g);
                            g.remove(true)
                        }
                        l = null
                    }
                }
                a.moveToBookmark(j);
                a.shrink(CKEDITOR.SHRINK_TEXT)
            }
        }

        function a(a) {
            a.enlarge(CKEDITOR.ENLARGE_INLINE, 1);
            var b = a.createBookmark(), d = b.startNode;
            if (a.collapsed) {
                for (var e = new CKEDITOR.dom.elementPath(d.getParent(), a.root), c, f = 0, g; f < e.elements.length && (g = e.elements[f]); f++) {
                    if (g == e.block || g == e.blockLimit)break;
                    if (this.checkElementRemovable(g)) {
                        var i;
                        if (a.collapsed && (a.checkBoundaryOfElement(g, CKEDITOR.END) || (i = a.checkBoundaryOfElement(g, CKEDITOR.START)))) {
                            c = g;
                            c.match = i ? "start" : "end"
                        } else {
                            g.mergeSiblings();
                            g.getName() == this.element ?
                                m.call(this, g) : l(g, t(this)[g.getName()])
                        }
                    }
                }
                if (c) {
                    g = d;
                    for (f = 0; ; f++) {
                        i = e.elements[f];
                        if (i.equals(c))break; else if (i.match)continue; else i = i.clone();
                        i.append(g);
                        g = i
                    }
                    g[c.match == "start" ? "insertBefore" : "insertAfter"](c)
                }
            } else {
                var h = b.endNode, j = this, e = function () {
                    for (var a = new CKEDITOR.dom.elementPath(d.getParent()), b = new CKEDITOR.dom.elementPath(h.getParent()), e = null, c = null, f = 0; f < a.elements.length; f++) {
                        var g = a.elements[f];
                        if (g == a.block || g == a.blockLimit)break;
                        j.checkElementRemovable(g) && (e = g)
                    }
                    for (f = 0; f < b.elements.length; f++) {
                        g =
                            b.elements[f];
                        if (g == b.block || g == b.blockLimit)break;
                        j.checkElementRemovable(g) && (c = g)
                    }
                    c && h.breakParent(c);
                    e && d.breakParent(e)
                };
                e();
                for (c = d; !c.equals(h);) {
                    f = c.getNextSourceNode();
                    if (c.type == CKEDITOR.NODE_ELEMENT && this.checkElementRemovable(c)) {
                        c.getName() == this.element ? m.call(this, c) : l(c, t(this)[c.getName()]);
                        if (f.type == CKEDITOR.NODE_ELEMENT && f.contains(d)) {
                            e();
                            f = d.getNext()
                        }
                    }
                    c = f
                }
            }
            a.moveToBookmark(b)
        }

        function f(a) {
            var b = a.getEnclosedNode() || a.getCommonAncestor(false, true);
            (a = (new CKEDITOR.dom.elementPath(b,
                a.root)).contains(this.element, 1)) && !a.isReadOnly() && s(a, this)
        }

        function h(a) {
            var b = a.getCommonAncestor(true, true);
            if (a = (new CKEDITOR.dom.elementPath(b, a.root)).contains(this.element, 1)) {
                var b = this._.definition, d = b.attributes;
                if (d)for (var e in d)a.removeAttribute(e, d[e]);
                if (b.styles)for (var c in b.styles)b.styles.hasOwnProperty(c) && a.removeStyle(c)
            }
        }

        function g(a) {
            var b = a.createBookmark(true), d = a.createIterator();
            d.enforceRealBlocks = true;
            if (this._.enterMode)d.enlargeBr = this._.enterMode != CKEDITOR.ENTER_BR;
            for (var e, c = a.document; e = d.getNextParagraph();)if (!e.isReadOnly()) {
                var f = q(this, c, e);
                i(e, f)
            }
            a.moveToBookmark(b)
        }

        function d(a) {
            var b = a.createBookmark(1), d = a.createIterator();
            d.enforceRealBlocks = true;
            d.enlargeBr = this._.enterMode != CKEDITOR.ENTER_BR;
            for (var e; e = d.getNextParagraph();)if (this.checkElementRemovable(e))if (e.is("pre")) {
                var c = this._.enterMode == CKEDITOR.ENTER_BR ? null : a.document.createElement(this._.enterMode == CKEDITOR.ENTER_P ? "p" : "div");
                c && e.copyAttributes(c);
                i(e, c)
            } else m.call(this, e);
            a.moveToBookmark(b)
        }

        function i(a, b) {
            var d = !b;
            if (d) {
                b = a.getDocument().createElement("div");
                a.copyAttributes(b)
            }
            var c = b && b.is("pre"), f = a.is("pre"), g = !c && f;
            if (c && !f) {
                f = b;
                (g = a.getBogus()) && g.remove();
                g = a.getHtml();
                g = j(g, /(?:^[ \t\n\r]+)|(?:[ \t\n\r]+$)/g, "");
                g = g.replace(/[ \t\r\n]*(<br[^>]*>)[ \t\r\n]*/gi, "$1");
                g = g.replace(/([ \t\n\r]+|&nbsp;)/g, " ");
                g = g.replace(/<br\b[^>]*>/gi, "\n");
                if (CKEDITOR.env.ie) {
                    var i = a.getDocument().createElement("div");
                    i.append(f);
                    f.$.outerHTML = "<pre>" + g + "</pre>";
                    f.copyAttributes(i.getFirst());
                    f = i.getFirst().remove()
                } else f.setHtml(g);
                b = f
            } else g ? b = k(d ? [a.getHtml()] : e(a), b) : a.moveChildren(b);
            b.replace(a);
            if (c) {
                var d = b, h;
                if ((h = d.getPrevious(B)) && h.is && h.is("pre")) {
                    c = j(h.getHtml(), /\n$/, "") + "\n\n" + j(d.getHtml(), /^\n/, "");
                    CKEDITOR.env.ie ? d.$.outerHTML = "<pre>" + c + "</pre>" : d.setHtml(c);
                    h.remove()
                }
            } else d && o(b)
        }

        function e(a) {
            a.getName();
            var b = [];
            j(a.getOuterHtml(), /(\S\s*)\n(?:\s|(<span[^>]+data-cke-bookmark.*?\/span>))*\n(?!$)/gi,function (a, b, d) {
                return b + "</pre>" + d + "<pre>"
            }).replace(/<pre\b.*?>([\s\S]*?)<\/pre>/gi,
                function (a, d) {
                    b.push(d)
                });
            return b
        }

        function j(a, b, d) {
            var e = "", c = "", a = a.replace(/(^<span[^>]+data-cke-bookmark.*?\/span>)|(<span[^>]+data-cke-bookmark.*?\/span>$)/gi, function (a, b, d) {
                b && (e = b);
                d && (c = d);
                return""
            });
            return e + a.replace(b, d) + c
        }

        function k(a, b) {
            var d;
            a.length > 1 && (d = new CKEDITOR.dom.documentFragment(b.getDocument()));
            for (var e = 0; e < a.length; e++) {
                var c = a[e], c = c.replace(/(\r\n|\r)/g, "\n"), c = j(c, /^[ \t]*\n/, ""), c = j(c, /\n$/, ""), c = j(c, /^[ \t]+|[ \t]+$/g, function (a, b) {
                    return a.length == 1 ? "&nbsp;" : b ?
                        " " + CKEDITOR.tools.repeat("&nbsp;", a.length - 1) : CKEDITOR.tools.repeat("&nbsp;", a.length - 1) + " "
                }), c = c.replace(/\n/g, "<br>"), c = c.replace(/[ \t]{2,}/g, function (a) {
                    return CKEDITOR.tools.repeat("&nbsp;", a.length - 1) + " "
                });
                if (d) {
                    var f = b.clone();
                    f.setHtml(c);
                    d.append(f)
                } else b.setHtml(c)
            }
            return d || b
        }

        function m(a) {
            var b = this._.definition, d = b.attributes, b = b.styles, e = t(this)[a.getName()], c = CKEDITOR.tools.isEmpty(d) && CKEDITOR.tools.isEmpty(b), f;
            for (f in d)if (!((f == "class" || this._.definition.fullMatch) && a.getAttribute(f) !=
                z(f, d[f]))) {
                c = a.hasAttribute(f);
                a.removeAttribute(f)
            }
            for (var g in b)if (!(this._.definition.fullMatch && a.getStyle(g) != z(g, b[g], true))) {
                c = c || !!a.getStyle(g);
                a.removeStyle(g)
            }
            l(a, e, w[a.getName()]);
            c && (this._.definition.alwaysRemoveElement ? o(a, 1) : !CKEDITOR.dtd.$block[a.getName()] || this._.enterMode == CKEDITOR.ENTER_BR && !a.hasAttributes() ? o(a) : a.renameNode(this._.enterMode == CKEDITOR.ENTER_P ? "p" : "div"))
        }

        function n(a) {
            for (var b = t(this), d = a.getElementsByTag(this.element), e = d.count(); --e >= 0;)m.call(this, d.getItem(e));
            for (var c in b)if (c != this.element) {
                d = a.getElementsByTag(c);
                for (e = d.count() - 1; e >= 0; e--) {
                    var f = d.getItem(e);
                    l(f, b[c])
                }
            }
        }

        function l(a, b, d) {
            if (b = b && b.attributes)for (var e = 0; e < b.length; e++) {
                var c = b[e][0], f;
                if (f = a.getAttribute(c)) {
                    var g = b[e][1];
                    (g === null || g.test && g.test(f) || typeof g == "string" && f == g) && a.removeAttribute(c)
                }
            }
            d || o(a)
        }

        function o(a, b) {
            if (!a.hasAttributes() || b)if (CKEDITOR.dtd.$block[a.getName()]) {
                var d = a.getPrevious(B), e = a.getNext(B);
                d && (d.type == CKEDITOR.NODE_TEXT || !d.isBlockBoundary({br: 1})) &&
                a.append("br", 1);
                e && (e.type == CKEDITOR.NODE_TEXT || !e.isBlockBoundary({br: 1})) && a.append("br");
                a.remove(true)
            } else {
                d = a.getFirst();
                e = a.getLast();
                a.remove(true);
                if (d) {
                    d.type == CKEDITOR.NODE_ELEMENT && d.mergeSiblings();
                    e && (!d.equals(e) && e.type == CKEDITOR.NODE_ELEMENT) && e.mergeSiblings()
                }
            }
        }

        function q(a, b, d) {
            var e;
            e = a.element;
            e == "*" && (e = "span");
            e = new CKEDITOR.dom.element(e, b);
            d && d.copyAttributes(e);
            e = s(e, a);
            b.getCustomData("doc_processing_style") && e.hasAttribute("id") ? e.removeAttribute("id") : b.setCustomData("doc_processing_style",
                1);
            return e
        }

        function s(a, b) {
            var d = b._.definition, e = d.attributes, d = CKEDITOR.style.getStyleText(d);
            if (e)for (var c in e)a.setAttribute(c, e[c]);
            d && a.setAttribute("style", d);
            return a
        }

        function p(a, b) {
            for (var d in a)a[d] = a[d].replace(u, function (a, d) {
                return b[d]
            })
        }

        function t(a) {
            if (a._.overrides)return a._.overrides;
            var b = a._.overrides = {}, d = a._.definition.overrides;
            if (d) {
                CKEDITOR.tools.isArray(d) || (d = [d]);
                for (var e = 0; e < d.length; e++) {
                    var c = d[e], f, g;
                    if (typeof c == "string")f = c.toLowerCase(); else {
                        f = c.element ? c.element.toLowerCase() :
                            a.element;
                        g = c.attributes
                    }
                    c = b[f] || (b[f] = {});
                    if (g) {
                        var c = c.attributes = c.attributes || [], i;
                        for (i in g)c.push([i.toLowerCase(), g[i]])
                    }
                }
            }
            return b
        }

        function z(a, b, d) {
            var e = new CKEDITOR.dom.element("span");
            e[d ? "setStyle" : "setAttribute"](a, b);
            return e[d ? "getStyle" : "getAttribute"](a)
        }

        function x(a, b) {
            for (var d = a.document, e = a.getRanges(), c = b ? this.removeFromRange : this.applyToRange, f, g = e.createIterator(); f = g.getNextRange();)c.call(this, f);
            a.selectRanges(e);
            d.removeCustomData("doc_processing_style")
        }

        var w = {address: 1,
            div: 1, h1: 1, h2: 1, h3: 1, h4: 1, h5: 1, h6: 1, p: 1, pre: 1, section: 1, header: 1, footer: 1, nav: 1, article: 1, aside: 1, figure: 1, dialog: 1, hgroup: 1, time: 1, meter: 1, menu: 1, command: 1, keygen: 1, output: 1, progress: 1, details: 1, datagrid: 1, datalist: 1}, v = {a: 1, embed: 1, hr: 1, img: 1, li: 1, object: 1, ol: 1, table: 1, td: 1, tr: 1, th: 1, ul: 1, dl: 1, dt: 1, dd: 1, form: 1, audio: 1, video: 1}, r = /\s*(?:;\s*|$)/, u = /#\((.+?)\)/g, A = CKEDITOR.dom.walker.bookmark(0, 1), B = CKEDITOR.dom.walker.whitespaces(1);
        CKEDITOR.style = function (a, b) {
            var d = a.attributes;
            if (d && d.style) {
                a.styles =
                    CKEDITOR.tools.extend({}, a.styles, CKEDITOR.tools.parseCssText(d.style));
                delete d.style
            }
            if (b) {
                a = CKEDITOR.tools.clone(a);
                p(a.attributes, b);
                p(a.styles, b)
            }
            d = this.element = a.element ? typeof a.element == "string" ? a.element.toLowerCase() : a.element : "*";
            this.type = a.type || (w[d] ? CKEDITOR.STYLE_BLOCK : v[d] ? CKEDITOR.STYLE_OBJECT : CKEDITOR.STYLE_INLINE);
            if (typeof this.element == "object")this.type = CKEDITOR.STYLE_OBJECT;
            this._ = {definition: a}
        };
        CKEDITOR.editor.prototype.applyStyle = function (a) {
            x.call(a, this.getSelection())
        };
        CKEDITOR.editor.prototype.removeStyle = function (a) {
            x.call(a, this.getSelection(), 1)
        };
        CKEDITOR.style.prototype = {apply: function (a) {
            x.call(this, a.getSelection())
        }, remove: function (a) {
            x.call(this, a.getSelection(), 1)
        }, applyToRange: function (a) {
            return(this.applyToRange = this.type == CKEDITOR.STYLE_INLINE ? c : this.type == CKEDITOR.STYLE_BLOCK ? g : this.type == CKEDITOR.STYLE_OBJECT ? f : null).call(this, a)
        }, removeFromRange: function (b) {
            return(this.removeFromRange = this.type == CKEDITOR.STYLE_INLINE ? a : this.type == CKEDITOR.STYLE_BLOCK ?
                d : this.type == CKEDITOR.STYLE_OBJECT ? h : null).call(this, b)
        }, applyToObject: function (a) {
            s(a, this)
        }, checkActive: function (a) {
            switch (this.type) {
                case CKEDITOR.STYLE_BLOCK:
                    return this.checkElementRemovable(a.block || a.blockLimit, true);
                case CKEDITOR.STYLE_OBJECT:
                case CKEDITOR.STYLE_INLINE:
                    for (var b = a.elements, d = 0, e; d < b.length; d++) {
                        e = b[d];
                        if (!(this.type == CKEDITOR.STYLE_INLINE && (e == a.block || e == a.blockLimit))) {
                            if (this.type == CKEDITOR.STYLE_OBJECT) {
                                var c = e.getName();
                                if (!(typeof this.element == "string" ? c == this.element :
                                    c in this.element))continue
                            }
                            if (this.checkElementRemovable(e, true))return true
                        }
                    }
            }
            return false
        }, checkApplicable: function (a) {
            switch (this.type) {
                case CKEDITOR.STYLE_OBJECT:
                    return a.contains(this.element)
            }
            return true
        }, checkElementMatch: function (a, b) {
            var d = this._.definition;
            if (!a || !d.ignoreReadonly && a.isReadOnly())return false;
            var e = a.getName();
            if (typeof this.element == "string" ? e == this.element : e in this.element) {
                if (!b && !a.hasAttributes())return true;
                if (e = d._AC)d = e; else {
                    var e = {}, c = 0, f = d.attributes;
                    if (f)for (var g in f) {
                        c++;
                        e[g] = f[g]
                    }
                    if (g = CKEDITOR.style.getStyleText(d)) {
                        e.style || c++;
                        e.style = g
                    }
                    e._length = c;
                    d = d._AC = e
                }
                if (d._length) {
                    for (var i in d)if (i != "_length") {
                        c = a.getAttribute(i) || "";
                        if (i == "style")a:{
                            e = d[i];
                            typeof e == "string" && (e = CKEDITOR.tools.parseCssText(e));
                            typeof c == "string" && (c = CKEDITOR.tools.parseCssText(c, true));
                            g = void 0;
                            for (g in e)if (!(g in c && (c[g] == e[g] || e[g] == "inherit" || c[g] == "inherit"))) {
                                e = false;
                                break a
                            }
                            e = true
                        } else e = d[i] == c;
                        if (e) {
                            if (!b)return true
                        } else if (b)return false
                    }
                    if (b)return true
                } else return true
            }
            return false
        },
            checkElementRemovable: function (a, b) {
                if (this.checkElementMatch(a, b))return true;
                var d = t(this)[a.getName()];
                if (d) {
                    var e;
                    if (!(d = d.attributes))return true;
                    for (var c = 0; c < d.length; c++) {
                        e = d[c][0];
                        if (e = a.getAttribute(e)) {
                            var f = d[c][1];
                            if (f === null || typeof f == "string" && e == f || f.test(e))return true
                        }
                    }
                }
                return false
            }, buildPreview: function (a) {
                var b = this._.definition, d = [], e = b.element;
                e == "bdo" && (e = "span");
                var d = ["<", e], c = b.attributes;
                if (c)for (var f in c)d.push(" ", f, '="', c[f], '"');
                (c = CKEDITOR.style.getStyleText(b)) &&
                d.push(' style="', c, '"');
                d.push(">", a || b.name, "</", e, ">");
                return d.join("")
            }, getDefinition: function () {
                return this._.definition
            }};
        CKEDITOR.style.getStyleText = function (a) {
            var b = a._ST;
            if (b)return b;
            var b = a.styles, d = a.attributes && a.attributes.style || "", e = "";
            d.length && (d = d.replace(r, ";"));
            for (var c in b) {
                var f = b[c], g = (c + ":" + f).replace(r, ";");
                f == "inherit" ? e = e + g : d = d + g
            }
            d.length && (d = CKEDITOR.tools.normalizeCssText(d, true));
            return a._ST = d + e
        }
    }(), CKEDITOR.styleCommand = function (b, c) {
        this.requiredContent = this.allowedContent =
            this.style = b;
        CKEDITOR.tools.extend(this, c, true)
    }, CKEDITOR.styleCommand.prototype.exec = function (b) {
        b.focus();
        this.state == CKEDITOR.TRISTATE_OFF ? b.applyStyle(this.style) : this.state == CKEDITOR.TRISTATE_ON && b.removeStyle(this.style)
    }, CKEDITOR.stylesSet = new CKEDITOR.resourceManager("", "stylesSet"), CKEDITOR.addStylesSet = CKEDITOR.tools.bind(CKEDITOR.stylesSet.add, CKEDITOR.stylesSet), CKEDITOR.loadStylesSet = function (b, c, a) {
        CKEDITOR.stylesSet.addExternal(b, c, "");
        CKEDITOR.stylesSet.load(b, a)
    }, CKEDITOR.editor.prototype.getStylesSet =
        function (b) {
            if (this._.stylesDefinitions)b(this._.stylesDefinitions); else {
                var c = this, a = c.config.stylesCombo_stylesSet || c.config.stylesSet;
                if (a === false)b(null); else if (a instanceof Array) {
                    c._.stylesDefinitions = a;
                    b(a)
                } else {
                    a || (a = "default");
                    var a = a.split(":"), f = a[0];
                    CKEDITOR.stylesSet.addExternal(f, a[1] ? a.slice(1).join(":") : CKEDITOR.getUrl("styles.js"), "");
                    CKEDITOR.stylesSet.load(f, function (a) {
                        c._.stylesDefinitions = a[f];
                        b(c._.stylesDefinitions)
                    })
                }
            }
        }, CKEDITOR.dom.comment = function (b, c) {
        typeof b == "string" &&
        (b = (c ? c.$ : document).createComment(b));
        CKEDITOR.dom.domObject.call(this, b)
    }, CKEDITOR.dom.comment.prototype = new CKEDITOR.dom.node, CKEDITOR.tools.extend(CKEDITOR.dom.comment.prototype, {type: CKEDITOR.NODE_COMMENT, getOuterHtml: function () {
        return"<\!--" + this.$.nodeValue + "--\>"
    }}), function () {
        var b = {}, c;
        for (c in CKEDITOR.dtd.$blockLimit)c in CKEDITOR.dtd.$list || (b[c] = 1);
        var a = {};
        for (c in CKEDITOR.dtd.$block)c in CKEDITOR.dtd.$blockLimit || c in CKEDITOR.dtd.$empty || (a[c] = 1);
        CKEDITOR.dom.elementPath = function (c, h) {
            var g = null, d = null, i = [], h = h || c.getDocument().getBody(), e = c;
            do if (e.type == CKEDITOR.NODE_ELEMENT) {
                i.push(e);
                if (!this.lastElement) {
                    this.lastElement = e;
                    if (e.is(CKEDITOR.dtd.$object))continue
                }
                var j = e.getName();
                if (!d) {
                    !g && a[j] && (g = e);
                    if (b[j]) {
                        var k;
                        if (k = !g) {
                            if (j = j == "div") {
                                a:{
                                    j = e.getChildren();
                                    k = 0;
                                    for (var m = j.count(); k < m; k++) {
                                        var n = j.getItem(k);
                                        if (n.type == CKEDITOR.NODE_ELEMENT && CKEDITOR.dtd.$block[n.getName()]) {
                                            j = true;
                                            break a
                                        }
                                    }
                                    j = false
                                }
                                j = !j && !e.equals(h)
                            }
                            k = j
                        }
                        k ? g = e : d = e
                    }
                }
                if (e.equals(h))break
            } while (e = e.getParent());
            this.block = g;
            this.blockLimit = d;
            this.root = h;
            this.elements = i
        }
    }(), CKEDITOR.dom.elementPath.prototype = {compare: function (b) {
        var c = this.elements, b = b && b.elements;
        if (!b || c.length != b.length)return false;
        for (var a = 0; a < c.length; a++)if (!c[a].equals(b[a]))return false;
        return true
    }, contains: function (b, c, a) {
        var f;
        typeof b == "string" && (f = function (a) {
            return a.getName() == b
        });
        b instanceof CKEDITOR.dom.element ? f = function (a) {
            return a.equals(b)
        } : CKEDITOR.tools.isArray(b) ? f = function (a) {
            return CKEDITOR.tools.indexOf(b, a.getName()) > -1
        } : typeof b == "function" ? f = b : typeof b == "object" && (f = function (a) {
            return a.getName()in b
        });
        var h = this.elements, g = h.length;
        c && g--;
        if (a) {
            h = Array.prototype.slice.call(h, 0);
            h.reverse()
        }
        for (c = 0; c < g; c++)if (f(h[c]))return h[c];
        return null
    }, isContextFor: function (b) {
        var c;
        if (b in CKEDITOR.dtd.$block) {
            c = this.contains(CKEDITOR.dtd.$intermediate) || this.root.equals(this.block) && this.block || this.blockLimit;
            return!!c.getDtd()[b]
        }
        return true
    }, direction: function () {
        return(this.block || this.blockLimit || this.root).getDirection(1)
    }},
        CKEDITOR.dom.text = function (b, c) {
            typeof b == "string" && (b = (c ? c.$ : document).createTextNode(b));
            this.$ = b
        }, CKEDITOR.dom.text.prototype = new CKEDITOR.dom.node, CKEDITOR.tools.extend(CKEDITOR.dom.text.prototype, {type: CKEDITOR.NODE_TEXT, getLength: function () {
        return this.$.nodeValue.length
    }, getText: function () {
        return this.$.nodeValue
    }, setText: function (b) {
        this.$.nodeValue = b
    }, split: function (b) {
        var c = this.$.parentNode, a = c.childNodes.length, f = this.getLength(), h = this.getDocument(), g = new CKEDITOR.dom.text(this.$.splitText(b),
            h);
        if (c.childNodes.length == a)if (b >= f) {
            g = h.createText("");
            g.insertAfter(this)
        } else {
            b = h.createText("");
            b.insertAfter(g);
            b.remove()
        }
        return g
    }, substring: function (b, c) {
        return typeof c != "number" ? this.$.nodeValue.substr(b) : this.$.nodeValue.substring(b, c)
    }}), function () {
        function b(a, b, c) {
            var g = a.serializable, d = b[c ? "endContainer" : "startContainer"], i = c ? "endOffset" : "startOffset", e = g ? b.document.getById(a.startNode) : a.startNode, a = g ? b.document.getById(a.endNode) : a.endNode;
            if (d.equals(e.getPrevious())) {
                b.startOffset =
                    b.startOffset - d.getLength() - a.getPrevious().getLength();
                d = a.getNext()
            } else if (d.equals(a.getPrevious())) {
                b.startOffset = b.startOffset - d.getLength();
                d = a.getNext()
            }
            d.equals(e.getParent()) && b[i]++;
            d.equals(a.getParent()) && b[i]++;
            b[c ? "endContainer" : "startContainer"] = d;
            return b
        }

        CKEDITOR.dom.rangeList = function (a) {
            if (a instanceof CKEDITOR.dom.rangeList)return a;
            a ? a instanceof CKEDITOR.dom.range && (a = [a]) : a = [];
            return CKEDITOR.tools.extend(a, c)
        };
        var c = {createIterator: function () {
            var a = this, b = CKEDITOR.dom.walker.bookmark(),
                c = [], g;
            return{getNextRange: function (d) {
                g = g == void 0 ? 0 : g + 1;
                var i = a[g];
                if (i && a.length > 1) {
                    if (!g)for (var e = a.length - 1; e >= 0; e--)c.unshift(a[e].createBookmark(true));
                    if (d)for (var j = 0; a[g + j + 1];) {
                        for (var k = i.document, d = 0, e = k.getById(c[j].endNode), k = k.getById(c[j + 1].startNode); ;) {
                            e = e.getNextSourceNode(false);
                            if (k.equals(e))d = 1; else if (b(e) || e.type == CKEDITOR.NODE_ELEMENT && e.isBlockBoundary())continue;
                            break
                        }
                        if (!d)break;
                        j++
                    }
                    for (i.moveToBookmark(c.shift()); j--;) {
                        e = a[++g];
                        e.moveToBookmark(c.shift());
                        i.setEnd(e.endContainer,
                            e.endOffset)
                    }
                }
                return i
            }}
        }, createBookmarks: function (a) {
            for (var c = [], h, g = 0; g < this.length; g++) {
                c.push(h = this[g].createBookmark(a, true));
                for (var d = g + 1; d < this.length; d++) {
                    this[d] = b(h, this[d]);
                    this[d] = b(h, this[d], true)
                }
            }
            return c
        }, createBookmarks2: function (a) {
            for (var b = [], c = 0; c < this.length; c++)b.push(this[c].createBookmark2(a));
            return b
        }, moveToBookmarks: function (a) {
            for (var b = 0; b < this.length; b++)this[b].moveToBookmark(a[b])
        }}
    }(), function () {
        function b() {
            return CKEDITOR.getUrl(CKEDITOR.skinName.split(",")[1] ||
                "skins/" + CKEDITOR.skinName.split(",")[0] + "/")
        }

        function c(a) {
            var d = CKEDITOR.skin["ua_" + a], e = CKEDITOR.env;
            if (d)for (var d = d.split(",").sort(function (a, b) {
                return a > b ? -1 : 1
            }), c = 0, f; c < d.length; c++) {
                f = d[c];
                if (e.ie && (f.replace(/^ie/, "") == e.version || e.quirks && f == "iequirks"))f = "ie";
                if (e[f]) {
                    a = a + ("_" + d[c]);
                    break
                }
            }
            return CKEDITOR.getUrl(b() + a + ".css")
        }

        function a(a, b) {
            if (!g[a]) {
                CKEDITOR.document.appendStyleSheet(c(a));
                g[a] = 1
            }
            b && b()
        }

        function f(a) {
            var b = a.getById(d);
            if (!b) {
                b = a.getHead().append("style");
                b.setAttribute("id",
                    d);
                b.setAttribute("type", "text/css")
            }
            return b
        }

        function h(a, b, d) {
            var e, c, f;
            if (CKEDITOR.env.webkit) {
                b = b.split("}").slice(0, -1);
                for (c = 0; c < b.length; c++)b[c] = b[c].split("{")
            }
            for (var g = 0; g < a.length; g++)if (CKEDITOR.env.webkit)for (c = 0; c < b.length; c++) {
                f = b[c][1];
                for (e = 0; e < d.length; e++)f = f.replace(d[e][0], d[e][1]);
                a[g].$.sheet.addRule(b[c][0], f)
            } else {
                f = b;
                for (e = 0; e < d.length; e++)f = f.replace(d[e][0], d[e][1]);
                CKEDITOR.env.ie ? a[g].$.styleSheet.cssText = a[g].$.styleSheet.cssText + f : a[g].$.innerHTML = a[g].$.innerHTML +
                    f
            }
        }

        var g = {};
        CKEDITOR.skin = {path: b, loadPart: function (d, e) {
            CKEDITOR.skin.name != CKEDITOR.skinName.split(",")[0] ? CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(b() + "skin.js"), function () {
                a(d, e)
            }) : a(d, e)
        }, getPath: function (a) {
            return CKEDITOR.getUrl(c(a))
        }, icons: {}, addIcon: function (a, b, d) {
            a = a.toLowerCase();
            this.icons[a] || (this.icons[a] = {path: b, offset: d || 0})
        }, getIconStyle: function (a, b, d, e) {
            var c;
            if (a) {
                a = a.toLowerCase();
                b && (c = this.icons[a + "-rtl"]);
                c || (c = this.icons[a])
            }
            a = d || c && c.path || "";
            e = e || c && c.offset;
            return a &&
                "background-image:url(" + CKEDITOR.getUrl(a) + ");background-position:0 " + e + "px;"
        }};
        CKEDITOR.tools.extend(CKEDITOR.editor.prototype, {getUiColor: function () {
            return this.uiColor
        }, setUiColor: function (a) {
            var b = f(CKEDITOR.document);
            return(this.setUiColor = function (a) {
                var d = CKEDITOR.skin.chameleon, c = [
                    [e, a]
                ];
                this.uiColor = a;
                h([b], d(this, "editor"), c);
                h(i, d(this, "panel"), c)
            }).call(this, a)
        }});
        var d = "cke_ui_color", i = [], e = /\$color/g;
        CKEDITOR.on("instanceLoaded", function (a) {
            if (!CKEDITOR.env.ie || !CKEDITOR.env.quirks) {
                var b =
                    a.editor, a = function (a) {
                    a = (a.data[0] || a.data).element.getElementsByTag("iframe").getItem(0).getFrameDocument();
                    if (!a.getById("cke_ui_color")) {
                        a = f(a);
                        i.push(a);
                        var d = b.getUiColor();
                        d && h([a], CKEDITOR.skin.chameleon(b, "panel"), [
                            [e, d]
                        ])
                    }
                };
                b.on("panelShow", a);
                b.on("menuShow", a);
                b.config.uiColor && b.setUiColor(b.config.uiColor)
            }
        })
    }(), function () {
        if (CKEDITOR.env.webkit)CKEDITOR.env.hc = false; else {
            var b = CKEDITOR.dom.element.createFromHtml('<div style="width:0px;height:0px;position:absolute;left:-10000px;border: 1px solid;border-color: red blue;"></div>',
                CKEDITOR.document);
            b.appendTo(CKEDITOR.document.getHead());
            try {
                CKEDITOR.env.hc = b.getComputedStyle("border-top-color") == b.getComputedStyle("border-right-color")
            } catch (c) {
                CKEDITOR.env.hc = false
            }
            b.remove()
        }
        if (CKEDITOR.env.hc)CKEDITOR.env.cssClass = CKEDITOR.env.cssClass + " cke_hc";
        CKEDITOR.document.appendStyleText(".cke{visibility:hidden;}");
        CKEDITOR.status = "loaded";
        CKEDITOR.fireOnce("loaded");
        if (b = CKEDITOR._.pending) {
            delete CKEDITOR._.pending;
            for (var a = 0; a < b.length; a++) {
                CKEDITOR.editor.prototype.constructor.apply(b[a][0],
                    b[a][1]);
                CKEDITOR.add(b[a][0])
            }
        }
    }(), CKEDITOR.skin.name = "moono", CKEDITOR.skin.ua_editor = "ie,iequirks,ie7,ie8,gecko", CKEDITOR.skin.ua_dialog = "ie,iequirks,ie7,ie8,opera", CKEDITOR.skin.chameleon = function () {
        var b = function () {
            return function (a, b) {
                for (var c = a.match(/[^#]./g), d = 0; d < 3; d++) {
                    var i = c, e = d, j;
                    j = parseInt(c[d], 16);
                    j = ("0" + (b < 0 ? 0 | j * (1 + b) : 0 | j + (255 - j) * b).toString(16)).slice(-2);
                    i[e] = j
                }
                return"#" + c.join("")
            }
        }(), c = function () {
            var a = new CKEDITOR.template("background:#{to};background-image:-webkit-gradient(linear,lefttop,leftbottom,from({from}),to({to}));background-image:-moz-linear-gradient(top,{from},{to});background-image:-webkit-linear-gradient(top,{from},{to});background-image:-o-linear-gradient(top,{from},{to});background-image:-ms-linear-gradient(top,{from},{to});background-image:linear-gradient(top,{from},{to});filter:progid:DXImageTransform.Microsoft.gradient(gradientType=0,startColorstr='{from}',endColorstr='{to}');");
            return function (b, c) {
                return a.output({from: b, to: c})
            }
        }(), a = {editor: new CKEDITOR.template("{id}.cke_chrome [border-color:{defaultBorder};] {id} .cke_top [ {defaultGradient}border-bottom-color:{defaultBorder};] {id} .cke_bottom [{defaultGradient}border-top-color:{defaultBorder};] {id} .cke_resizer [border-right-color:{ckeResizer}] {id} .cke_dialog_title [{defaultGradient}border-bottom-color:{defaultBorder};] {id} .cke_dialog_footer [{defaultGradient}outline-color:{defaultBorder};border-top-color:{defaultBorder};] {id} .cke_dialog_tab [{lightGradient}border-color:{defaultBorder};] {id} .cke_dialog_tab:hover [{mediumGradient}] {id} .cke_dialog_contents [border-top-color:{defaultBorder};] {id} .cke_dialog_tab_selected, {id} .cke_dialog_tab_selected:hover [background:{dialogTabSelected};border-bottom-color:{dialogTabSelectedBorder};] {id} .cke_dialog_body [background:{dialogBody};border-color:{defaultBorder};] {id} .cke_toolgroup [{lightGradient}border-color:{defaultBorder};] {id} a.cke_button_off:hover, {id} a.cke_button_off:focus, {id} a.cke_button_off:active [{mediumGradient}] {id} .cke_button_on [{ckeButtonOn}] {id} .cke_toolbar_separator [background-color: {ckeToolbarSeparator};] {id} .cke_combo_button [border-color:{defaultBorder};{lightGradient}] {id} a.cke_combo_button:hover, {id} a.cke_combo_button:focus, {id} .cke_combo_on a.cke_combo_button [border-color:{defaultBorder};{mediumGradient}] {id} .cke_path_item [color:{elementsPathColor};] {id} a.cke_path_item:hover, {id} a.cke_path_item:focus, {id} a.cke_path_item:active [background-color:{elementsPathBg};] {id}.cke_panel [border-color:{defaultBorder};] "),
            panel: new CKEDITOR.template(".cke_panel_grouptitle [{lightGradient}border-color:{defaultBorder};] .cke_menubutton_icon [background-color:{menubuttonIcon};] .cke_menubutton:hover .cke_menubutton_icon, .cke_menubutton:focus .cke_menubutton_icon, .cke_menubutton:active .cke_menubutton_icon [background-color:{menubuttonIconHover};] .cke_menuseparator [background-color:{menubuttonIcon};] a:hover.cke_colorbox, a:focus.cke_colorbox, a:active.cke_colorbox [border-color:{defaultBorder};] a:hover.cke_colorauto, a:hover.cke_colormore, a:focus.cke_colorauto, a:focus.cke_colormore, a:active.cke_colorauto, a:active.cke_colormore [background-color:{ckeColorauto};border-color:{defaultBorder};] ")};
        return function (f, h) {
            var g = f.uiColor, g = {id: "." + f.id, defaultBorder: b(g, -0.1), defaultGradient: c(b(g, 0.9), g), lightGradient: c(b(g, 1), b(g, 0.7)), mediumGradient: c(b(g, 0.8), b(g, 0.5)), ckeButtonOn: c(b(g, 0.6), b(g, 0.7)), ckeResizer: b(g, -0.4), ckeToolbarSeparator: b(g, 0.5), ckeColorauto: b(g, 0.8), dialogBody: b(g, 0.7), dialogTabSelected: c("#FFFFFF", "#FFFFFF"), dialogTabSelectedBorder: "#FFF", elementsPathColor: b(g, -0.6), elementsPathBg: g, menubuttonIcon: b(g, 0.5), menubuttonIconHover: b(g, 0.3)};
            return a[h].output(g).replace(/\[/g,
                "{").replace(/\]/g, "}")
        }
    }(), CKEDITOR.plugins.add("dialogui", {onLoad: function () {
        var b = function (a) {
            this._ || (this._ = {});
            this._["default"] = this._.initValue = a["default"] || "";
            this._.required = a.required || false;
            for (var b = [this._], d = 1; d < arguments.length; d++)b.push(arguments[d]);
            b.push(true);
            CKEDITOR.tools.extend.apply(CKEDITOR.tools, b);
            return this._
        }, c = {build: function (a, b, d) {
            return new CKEDITOR.ui.dialog.textInput(a, b, d)
        }}, a = {build: function (a, b, d) {
            return new CKEDITOR.ui.dialog[b.type](a, b, d)
        }}, f = {isChanged: function () {
            return this.getValue() !=
                this.getInitValue()
        }, reset: function (a) {
            this.setValue(this.getInitValue(), a)
        }, setInitValue: function () {
            this._.initValue = this.getValue()
        }, resetInitValue: function () {
            this._.initValue = this._["default"]
        }, getInitValue: function () {
            return this._.initValue
        }}, h = CKEDITOR.tools.extend({}, CKEDITOR.ui.dialog.uiElement.prototype.eventProcessors, {onChange: function (a, b) {
            if (!this._.domOnChangeRegistered) {
                a.on("load", function () {
                    this.getInputElement().on("change", function () {
                        a.parts.dialog.isVisible() && this.fire("change",
                            {value: this.getValue()})
                    }, this)
                }, this);
                this._.domOnChangeRegistered = true
            }
            this.on("change", b)
        }}, true), g = /^on([A-Z]\w+)/, d = function (a) {
            for (var b in a)(g.test(b) || b == "title" || b == "type") && delete a[b];
            return a
        };
        CKEDITOR.tools.extend(CKEDITOR.ui.dialog, {labeledElement: function (a, d, c, f) {
            if (!(arguments.length < 4)) {
                var g = b.call(this, d);
                g.labelId = CKEDITOR.tools.getNextId() + "_label";
                this._.children = [];
                CKEDITOR.ui.dialog.uiElement.call(this, a, d, c, "div", null, {role: "presentation"}, function () {
                    var b = [], c = d.required ?
                        " cke_required" : "";
                    if (d.labelLayout != "horizontal")b.push('<label class="cke_dialog_ui_labeled_label' + c + '" ', ' id="' + g.labelId + '"', g.inputId ? ' for="' + g.inputId + '"' : "", (d.labelStyle ? ' style="' + d.labelStyle + '"' : "") + ">", d.label, "</label>", '<div class="cke_dialog_ui_labeled_content"' + (d.controlStyle ? ' style="' + d.controlStyle + '"' : "") + ' role="presentation">', f.call(this, a, d), "</div>"); else {
                        c = {type: "hbox", widths: d.widths, padding: 0, children: [
                            {type: "html", html: '<label class="cke_dialog_ui_labeled_label' + c +
                                '" id="' + g.labelId + '" for="' + g.inputId + '"' + (d.labelStyle ? ' style="' + d.labelStyle + '"' : "") + ">" + CKEDITOR.tools.htmlEncode(d.label) + "</span>"},
                            {type: "html", html: '<span class="cke_dialog_ui_labeled_content"' + (d.controlStyle ? ' style="' + d.controlStyle + '"' : "") + ">" + f.call(this, a, d) + "</span>"}
                        ]};
                        CKEDITOR.dialog._.uiElementBuilders.hbox.build(a, c, b)
                    }
                    return b.join("")
                })
            }
        }, textInput: function (a, d, c) {
            if (!(arguments.length < 3)) {
                b.call(this, d);
                var f = this._.inputId = CKEDITOR.tools.getNextId() + "_textInput", g = {"class": "cke_dialog_ui_input_" +
                    d.type, id: f, type: d.type};
                if (d.validate)this.validate = d.validate;
                if (d.maxLength)g.maxlength = d.maxLength;
                if (d.size)g.size = d.size;
                if (d.inputStyle)g.style = d.inputStyle;
                var h = this, l = false;
                a.on("load", function () {
                    h.getInputElement().on("keydown", function (a) {
                        a.data.getKeystroke() == 13 && (l = true)
                    });
                    h.getInputElement().on("keyup", function (b) {
                        if (b.data.getKeystroke() == 13 && l) {
                            a.getButton("ok") && setTimeout(function () {
                                a.getButton("ok").click()
                            }, 0);
                            l = false
                        }
                    }, null, null, 1E3)
                });
                CKEDITOR.ui.dialog.labeledElement.call(this,
                    a, d, c, function () {
                        var a = ['<div class="cke_dialog_ui_input_', d.type, '" role="presentation"'];
                        d.width && a.push('style="width:' + d.width + '" ');
                        a.push("><input ");
                        g["aria-labelledby"] = this._.labelId;
                        this._.required && (g["aria-required"] = this._.required);
                        for (var b in g)a.push(b + '="' + g[b] + '" ');
                        a.push(" /></div>");
                        return a.join("")
                    })
            }
        }, textarea: function (a, d, c) {
            if (!(arguments.length < 3)) {
                b.call(this, d);
                var f = this, g = this._.inputId = CKEDITOR.tools.getNextId() + "_textarea", h = {};
                if (d.validate)this.validate = d.validate;
                h.rows = d.rows || 5;
                h.cols = d.cols || 20;
                h["class"] = "cke_dialog_ui_input_textarea " + (d["class"] || "");
                if (typeof d.inputStyle != "undefined")h.style = d.inputStyle;
                if (d.dir)h.dir = d.dir;
                CKEDITOR.ui.dialog.labeledElement.call(this, a, d, c, function () {
                    h["aria-labelledby"] = this._.labelId;
                    this._.required && (h["aria-required"] = this._.required);
                    var a = ['<div class="cke_dialog_ui_input_textarea" role="presentation"><textarea id="', g, '" '], b;
                    for (b in h)a.push(b + '="' + CKEDITOR.tools.htmlEncode(h[b]) + '" ');
                    a.push(">", CKEDITOR.tools.htmlEncode(f._["default"]),
                        "</textarea></div>");
                    return a.join("")
                })
            }
        }, checkbox: function (a, e, c) {
            if (!(arguments.length < 3)) {
                var f = b.call(this, e, {"default": !!e["default"]});
                if (e.validate)this.validate = e.validate;
                CKEDITOR.ui.dialog.uiElement.call(this, a, e, c, "span", null, null, function () {
                    var b = CKEDITOR.tools.extend({}, e, {id: e.id ? e.id + "_checkbox" : CKEDITOR.tools.getNextId() + "_checkbox"}, true), c = [], g = CKEDITOR.tools.getNextId() + "_label", h = {"class": "cke_dialog_ui_checkbox_input", type: "checkbox", "aria-labelledby": g};
                    d(b);
                    if (e["default"])h.checked =
                        "checked";
                    if (typeof b.inputStyle != "undefined")b.style = b.inputStyle;
                    f.checkbox = new CKEDITOR.ui.dialog.uiElement(a, b, c, "input", null, h);
                    c.push(' <label id="', g, '" for="', h.id, '"' + (e.labelStyle ? ' style="' + e.labelStyle + '"' : "") + ">", CKEDITOR.tools.htmlEncode(e.label), "</label>");
                    return c.join("")
                })
            }
        }, radio: function (a, e, c) {
            if (!(arguments.length < 3)) {
                b.call(this, e);
                if (!this._["default"])this._["default"] = this._.initValue = e.items[0][1];
                if (e.validate)this.validate = e.valdiate;
                var f = [], g = this;
                CKEDITOR.ui.dialog.labeledElement.call(this,
                    a, e, c, function () {
                        for (var b = [], c = [], h = e.id ? e.id + "_radio" : CKEDITOR.tools.getNextId() + "_radio", j = 0; j < e.items.length; j++) {
                            var s = e.items[j], p = s[2] !== void 0 ? s[2] : s[0], t = s[1] !== void 0 ? s[1] : s[0], z = CKEDITOR.tools.getNextId() + "_radio_input", x = z + "_label", z = CKEDITOR.tools.extend({}, e, {id: z, title: null, type: null}, true), p = CKEDITOR.tools.extend({}, z, {title: p}, true), w = {type: "radio", "class": "cke_dialog_ui_radio_input", name: h, value: t, "aria-labelledby": x}, v = [];
                            if (g._["default"] == t)w.checked = "checked";
                            d(z);
                            d(p);
                            if (typeof z.inputStyle !=
                                "undefined")z.style = z.inputStyle;
                            f.push(new CKEDITOR.ui.dialog.uiElement(a, z, v, "input", null, w));
                            v.push(" ");
                            new CKEDITOR.ui.dialog.uiElement(a, p, v, "label", null, {id: x, "for": w.id}, s[0]);
                            b.push(v.join(""))
                        }
                        new CKEDITOR.ui.dialog.hbox(a, f, b, c);
                        return c.join("")
                    });
                this._.children = f
            }
        }, button: function (a, d, c) {
            if (arguments.length) {
                typeof d == "function" && (d = d(a.getParentEditor()));
                b.call(this, d, {disabled: d.disabled || false});
                CKEDITOR.event.implementOn(this);
                var f = this;
                a.on("load", function () {
                    var a = this.getElement();
                    (function () {
                        a.on("click", f.click, f);
                        a.on("keydown", function (a) {
                            if (a.data.getKeystroke()in{32: 1}) {
                                f.click();
                                a.data.preventDefault()
                            }
                        })
                    })();
                    a.unselectable()
                }, this);
                var g = CKEDITOR.tools.extend({}, d);
                delete g.style;
                var h = CKEDITOR.tools.getNextId() + "_label";
                CKEDITOR.ui.dialog.uiElement.call(this, a, g, c, "a", null, {style: d.style, href: "javascript:void(0)", title: d.label, hidefocus: "true", "class": d["class"], role: "button", "aria-labelledby": h}, '<span id="' + h + '" class="cke_dialog_ui_button">' + CKEDITOR.tools.htmlEncode(d.label) +
                    "</span>")
            }
        }, select: function (a, e, c) {
            if (!(arguments.length < 3)) {
                var f = b.call(this, e);
                if (e.validate)this.validate = e.validate;
                f.inputId = CKEDITOR.tools.getNextId() + "_select";
                CKEDITOR.ui.dialog.labeledElement.call(this, a, e, c, function () {
                    var b = CKEDITOR.tools.extend({}, e, {id: e.id ? e.id + "_select" : CKEDITOR.tools.getNextId() + "_select"}, true), c = [], g = [], h = {id: f.inputId, "class": "cke_dialog_ui_input_select", "aria-labelledby": this._.labelId};
                    c.push('<div class="cke_dialog_ui_input_', e.type, '" role="presentation"');
                    e.width && c.push('style="width:' + e.width + '" ');
                    c.push(">");
                    if (e.size != void 0)h.size = e.size;
                    if (e.multiple != void 0)h.multiple = e.multiple;
                    d(b);
                    for (var j = 0, s; j < e.items.length && (s = e.items[j]); j++)g.push('<option value="', CKEDITOR.tools.htmlEncode(s[1] !== void 0 ? s[1] : s[0]).replace(/"/g, "&quot;"), '" /> ', CKEDITOR.tools.htmlEncode(s[0]));
                    if (typeof b.inputStyle != "undefined")b.style = b.inputStyle;
                    f.select = new CKEDITOR.ui.dialog.uiElement(a, b, c, "select", null, h, g.join(""));
                    c.push("</div>");
                    return c.join("")
                })
            }
        },
            file: function (a, d, c) {
                if (!(arguments.length < 3)) {
                    d["default"] === void 0 && (d["default"] = "");
                    var f = CKEDITOR.tools.extend(b.call(this, d), {definition: d, buttons: []});
                    if (d.validate)this.validate = d.validate;
                    a.on("load", function () {
                        CKEDITOR.document.getById(f.frameId).getParent().addClass("cke_dialog_ui_input_file")
                    });
                    CKEDITOR.ui.dialog.labeledElement.call(this, a, d, c, function () {
                        f.frameId = CKEDITOR.tools.getNextId() + "_fileInput";
                        var a = CKEDITOR.env.isCustomDomain(), b = ['<iframe frameborder="0" allowtransparency="0" class="cke_dialog_ui_input_file" role="presentation" id="',
                            f.frameId, '" title="', d.label, '" src="javascript:void('];
                        b.push(a ? "(function(){document.open();document.domain='" + document.domain + "';document.close();})()" : "0");
                        b.push(')"></iframe>');
                        return b.join("")
                    })
                }
            }, fileButton: function (a, d, c) {
                if (!(arguments.length < 3)) {
                    b.call(this, d);
                    var f = this;
                    if (d.validate)this.validate = d.validate;
                    var g = CKEDITOR.tools.extend({}, d), h = g.onClick;
                    g.className = (g.className ? g.className + " " : "") + "cke_dialog_ui_button";
                    g.onClick = function (b) {
                        var c = d["for"];
                        if (!h || h.call(this, b) !== false) {
                            a.getContentElement(c[0],
                                c[1]).submit();
                            this.disable()
                        }
                    };
                    a.on("load", function () {
                        a.getContentElement(d["for"][0], d["for"][1])._.buttons.push(f)
                    });
                    CKEDITOR.ui.dialog.button.call(this, a, g, c)
                }
            }, html: function () {
                var a = /^\s*<[\w:]+\s+([^>]*)?>/, b = /^(\s*<[\w:]+(?:\s+[^>]*)?)((?:.|\r|\n)+)$/, d = /\/$/;
                return function (c, f, g) {
                    if (!(arguments.length < 3)) {
                        var h = [], o = f.html;
                        o.charAt(0) != "<" && (o = "<span>" + o + "</span>");
                        var q = f.focus;
                        if (q) {
                            var s = this.focus;
                            this.focus = function () {
                                (typeof q == "function" ? q : s).call(this);
                                this.fire("focus")
                            };
                            if (f.isFocusable)this.isFocusable =
                                this.isFocusable;
                            this.keyboardFocusable = true
                        }
                        CKEDITOR.ui.dialog.uiElement.call(this, c, f, h, "span", null, null, "");
                        h = h.join("").match(a);
                        o = o.match(b) || ["", "", ""];
                        if (d.test(o[1])) {
                            o[1] = o[1].slice(0, -1);
                            o[2] = "/" + o[2]
                        }
                        g.push([o[1], " ", h[1] || "", o[2]].join(""))
                    }
                }
            }(), fieldset: function (a, b, d, c, f) {
                var g = f.label;
                this._ = {children: b};
                CKEDITOR.ui.dialog.uiElement.call(this, a, f, c, "fieldset", null, null, function () {
                    var a = [];
                    g && a.push("<legend" + (f.labelStyle ? ' style="' + f.labelStyle + '"' : "") + ">" + g + "</legend>");
                    for (var b =
                        0; b < d.length; b++)a.push(d[b]);
                    return a.join("")
                })
            }}, true);
        CKEDITOR.ui.dialog.html.prototype = new CKEDITOR.ui.dialog.uiElement;
        CKEDITOR.ui.dialog.labeledElement.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.uiElement, {setLabel: function (a) {
            var b = CKEDITOR.document.getById(this._.labelId);
            b.getChildCount() < 1 ? (new CKEDITOR.dom.text(a, CKEDITOR.document)).appendTo(b) : b.getChild(0).$.nodeValue = a;
            return this
        }, getLabel: function () {
            var a = CKEDITOR.document.getById(this._.labelId);
            return!a || a.getChildCount() <
                1 ? "" : a.getChild(0).getText()
        }, eventProcessors: h}, true);
        CKEDITOR.ui.dialog.button.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.uiElement, {click: function () {
            return!this._.disabled ? this.fire("click", {dialog: this._.dialog}) : false
        }, enable: function () {
            this._.disabled = false;
            var a = this.getElement();
            a && a.removeClass("cke_disabled")
        }, disable: function () {
            this._.disabled = true;
            this.getElement().addClass("cke_disabled")
        }, isVisible: function () {
            return this.getElement().getFirst().isVisible()
        }, isEnabled: function () {
            return!this._.disabled
        },
            eventProcessors: CKEDITOR.tools.extend({}, CKEDITOR.ui.dialog.uiElement.prototype.eventProcessors, {onClick: function (a, b) {
                this.on("click", function () {
                    b.apply(this, arguments)
                })
            }}, true), accessKeyUp: function () {
                this.click()
            }, accessKeyDown: function () {
                this.focus()
            }, keyboardFocusable: true}, true);
        CKEDITOR.ui.dialog.textInput.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.labeledElement, {getInputElement: function () {
            return CKEDITOR.document.getById(this._.inputId)
        }, focus: function () {
            var a = this.selectParentTab();
            setTimeout(function () {
                var b = a.getInputElement();
                b && b.$.focus()
            }, 0)
        }, select: function () {
            var a = this.selectParentTab();
            setTimeout(function () {
                var b = a.getInputElement();
                if (b) {
                    b.$.focus();
                    b.$.select()
                }
            }, 0)
        }, accessKeyUp: function () {
            this.select()
        }, setValue: function (a) {
            !a && (a = "");
            return CKEDITOR.ui.dialog.uiElement.prototype.setValue.apply(this, arguments)
        }, keyboardFocusable: true}, f, true);
        CKEDITOR.ui.dialog.textarea.prototype = new CKEDITOR.ui.dialog.textInput;
        CKEDITOR.ui.dialog.select.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.labeledElement,
            {getInputElement: function () {
                return this._.select.getElement()
            }, add: function (a, b, d) {
                var c = new CKEDITOR.dom.element("option", this.getDialog().getParentEditor().document), f = this.getInputElement().$;
                c.$.text = a;
                c.$.value = b === void 0 || b === null ? a : b;
                d === void 0 || d === null ? CKEDITOR.env.ie ? f.add(c.$) : f.add(c.$, null) : f.add(c.$, d);
                return this
            }, remove: function (a) {
                this.getInputElement().$.remove(a);
                return this
            }, clear: function () {
                for (var a = this.getInputElement().$; a.length > 0;)a.remove(0);
                return this
            }, keyboardFocusable: true},
            f, true);
        CKEDITOR.ui.dialog.checkbox.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.uiElement, {getInputElement: function () {
            return this._.checkbox.getElement()
        }, setValue: function (a, b) {
            this.getInputElement().$.checked = a;
            !b && this.fire("change", {value: a})
        }, getValue: function () {
            return this.getInputElement().$.checked
        }, accessKeyUp: function () {
            this.setValue(!this.getValue())
        }, eventProcessors: {onChange: function (a, b) {
            if (!CKEDITOR.env.ie || CKEDITOR.env.version > 8)return h.onChange.apply(this, arguments);
            a.on("load",
                function () {
                    var a = this._.checkbox.getElement();
                    a.on("propertychange", function (b) {
                        b = b.data.$;
                        b.propertyName == "checked" && this.fire("change", {value: a.$.checked})
                    }, this)
                }, this);
            this.on("change", b);
            return null
        }}, keyboardFocusable: true}, f, true);
        CKEDITOR.ui.dialog.radio.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.uiElement, {setValue: function (a, b) {
            for (var d = this._.children, c, f = 0; f < d.length && (c = d[f]); f++)c.getElement().$.checked = c.getValue() == a;
            !b && this.fire("change", {value: a})
        }, getValue: function () {
            for (var a =
                this._.children, b = 0; b < a.length; b++)if (a[b].getElement().$.checked)return a[b].getValue();
            return null
        }, accessKeyUp: function () {
            var a = this._.children, b;
            for (b = 0; b < a.length; b++)if (a[b].getElement().$.checked) {
                a[b].getElement().focus();
                return
            }
            a[0].getElement().focus()
        }, eventProcessors: {onChange: function (a, b) {
            if (CKEDITOR.env.ie) {
                a.on("load", function () {
                    for (var a = this._.children, b = this, d = 0; d < a.length; d++)a[d].getElement().on("propertychange", function (a) {
                        a = a.data.$;
                        a.propertyName == "checked" && this.$.checked &&
                        b.fire("change", {value: this.getAttribute("value")})
                    })
                }, this);
                this.on("change", b)
            } else return h.onChange.apply(this, arguments);
            return null
        }}, keyboardFocusable: true}, f, true);
        CKEDITOR.ui.dialog.file.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.labeledElement, f, {getInputElement: function () {
            var a = CKEDITOR.document.getById(this._.frameId).getFrameDocument();
            return a.$.forms.length > 0 ? new CKEDITOR.dom.element(a.$.forms[0].elements[0]) : this.getElement()
        }, submit: function () {
            this.getInputElement().getParent().$.submit();
            return this
        }, getAction: function () {
            return this.getInputElement().getParent().$.action
        }, registerEvents: function (a) {
            var b = /^on([A-Z]\w+)/, d, c = function (a, b, d, c) {
                a.on("formLoaded", function () {
                    a.getInputElement().on(d, c, a)
                })
            }, f;
            for (f in a)if (d = f.match(b))this.eventProcessors[f] ? this.eventProcessors[f].call(this, this._.dialog, a[f]) : c(this, this._.dialog, d[1].toLowerCase(), a[f]);
            return this
        }, reset: function () {
            function a() {
                d.$.open();
                if (CKEDITOR.env.isCustomDomain())d.$.domain = document.domain;
                var i = "";
                c.size &&
                (i = c.size - (CKEDITOR.env.ie ? 7 : 0));
                var p = b.frameId + "_input";
                d.$.write(['<html dir="' + o + '" lang="' + q + '"><head><title></title></head><body style="margin: 0; overflow: hidden; background: transparent;">', '<form enctype="multipart/form-data" method="POST" dir="' + o + '" lang="' + q + '" action="', CKEDITOR.tools.htmlEncode(c.action), '"><label id="', b.labelId, '" for="', p, '" style="display:none">', CKEDITOR.tools.htmlEncode(c.label), '</label><input id="', p, '" aria-labelledby="', b.labelId, '" type="file" name="', CKEDITOR.tools.htmlEncode(c.id ||
                    "cke_upload"), '" size="', CKEDITOR.tools.htmlEncode(i > 0 ? i : ""), '" /></form></body></html>', "<script>window.parent.CKEDITOR.tools.callFunction(" + g + ");", "window.onbeforeunload = function() {window.parent.CKEDITOR.tools.callFunction(" + h + ")}<\/script>"].join(""));
                d.$.close();
                for (i = 0; i < f.length; i++)f[i].enable()
            }

            var b = this._, d = CKEDITOR.document.getById(b.frameId).getFrameDocument(), c = b.definition, f = b.buttons, g = this.formLoadedNumber, h = this.formUnloadNumber, o = b.dialog._.editor.lang.dir, q = b.dialog._.editor.langCode;
            if (!g) {
                g = this.formLoadedNumber = CKEDITOR.tools.addFunction(function () {
                    this.fire("formLoaded")
                }, this);
                h = this.formUnloadNumber = CKEDITOR.tools.addFunction(function () {
                    this.getInputElement().clearCustomData()
                }, this);
                this.getDialog()._.editor.on("destroy", function () {
                    CKEDITOR.tools.removeFunction(g);
                    CKEDITOR.tools.removeFunction(h)
                })
            }
            CKEDITOR.env.gecko ? setTimeout(a, 500) : a()
        }, getValue: function () {
            return this.getInputElement().$.value || ""
        }, setInitValue: function () {
            this._.initValue = ""
        }, eventProcessors: {onChange: function (a, b) {
            if (!this._.domOnChangeRegistered) {
                this.on("formLoaded", function () {
                    this.getInputElement().on("change", function () {
                        this.fire("change", {value: this.getValue()})
                    }, this)
                }, this);
                this._.domOnChangeRegistered = true
            }
            this.on("change", b)
        }}, keyboardFocusable: true}, true);
        CKEDITOR.ui.dialog.fileButton.prototype = new CKEDITOR.ui.dialog.button;
        CKEDITOR.ui.dialog.fieldset.prototype = CKEDITOR.tools.clone(CKEDITOR.ui.dialog.hbox.prototype);
        CKEDITOR.dialog.addUIElement("text", c);
        CKEDITOR.dialog.addUIElement("password",
            c);
        CKEDITOR.dialog.addUIElement("textarea", a);
        CKEDITOR.dialog.addUIElement("checkbox", a);
        CKEDITOR.dialog.addUIElement("radio", a);
        CKEDITOR.dialog.addUIElement("button", a);
        CKEDITOR.dialog.addUIElement("select", a);
        CKEDITOR.dialog.addUIElement("file", a);
        CKEDITOR.dialog.addUIElement("fileButton", a);
        CKEDITOR.dialog.addUIElement("html", a);
        CKEDITOR.dialog.addUIElement("fieldset", {build: function (a, b, d) {
            for (var c = b.children, f, g = [], h = [], o = 0; o < c.length && (f = c[o]); o++) {
                var q = [];
                g.push(q);
                h.push(CKEDITOR.dialog._.uiElementBuilders[f.type].build(a,
                    f, q))
            }
            return new CKEDITOR.ui.dialog[b.type](a, h, g, d, b)
        }})
    }}), CKEDITOR.DIALOG_RESIZE_NONE = 0, CKEDITOR.DIALOG_RESIZE_WIDTH = 1, CKEDITOR.DIALOG_RESIZE_HEIGHT = 2, CKEDITOR.DIALOG_RESIZE_BOTH = 3, function () {
        function b() {
            for (var a = this._.tabIdList.length, b = CKEDITOR.tools.indexOf(this._.tabIdList, this._.currentTabId) + a, d = b - 1; d > b - a; d--)if (this._.tabs[this._.tabIdList[d % a]][0].$.offsetHeight)return this._.tabIdList[d % a];
            return null
        }

        function c() {
            for (var a = this._.tabIdList.length, b = CKEDITOR.tools.indexOf(this._.tabIdList,
                this._.currentTabId), d = b + 1; d < b + a; d++)if (this._.tabs[this._.tabIdList[d % a]][0].$.offsetHeight)return this._.tabIdList[d % a];
            return null
        }

        function a(a, b) {
            for (var d = a.$.getElementsByTagName("input"), c = 0, e = d.length; c < e; c++) {
                var f = new CKEDITOR.dom.element(d[c]);
                if (f.getAttribute("type").toLowerCase() == "text")if (b) {
                    f.setAttribute("value", f.getCustomData("fake_value") || "");
                    f.removeCustomData("fake_value")
                } else {
                    f.setCustomData("fake_value", f.getAttribute("value"));
                    f.setAttribute("value", "")
                }
            }
        }

        function f(a, b) {
            var d = this.getInputElement();
            d && (a ? d.removeAttribute("aria-invalid") : d.setAttribute("aria-invalid", true));
            a || (this.select ? this.select() : this.focus());
            b && alert(b);
            this.fire("validated", {valid: a, msg: b})
        }

        function h() {
            var a = this.getInputElement();
            a && a.removeAttribute("aria-invalid")
        }

        function g(a) {
            var a = CKEDITOR.dom.element.createFromHtml(CKEDITOR.addTemplate("dialog", q).output({id: CKEDITOR.tools.getNextNumber(), editorId: a.id, langDir: a.lang.dir, langCode: a.langCode, editorDialogClass: "cke_editor_" + a.name.replace(/\./g,
                "\\.") + "_dialog", closeTitle: a.lang.common.close})), b = a.getChild([0, 0, 0, 0, 0]), d = b.getChild(0), c = b.getChild(1);
            if (CKEDITOR.env.ie && !CKEDITOR.env.ie6Compat) {
                var e = CKEDITOR.env.isCustomDomain(), e = "javascript:void(function(){" + encodeURIComponent("document.open();" + (e ? 'document.domain="' + document.domain + '";' : "") + "document.close();") + "}())";
                CKEDITOR.dom.element.createFromHtml('<iframe frameBorder="0" class="cke_iframe_shim" src="' + e + '" tabIndex="-1"></iframe>').appendTo(b.getParent())
            }
            d.unselectable();
            c.unselectable();
            return{element: a, parts: {dialog: a.getChild(0), title: d, close: c, tabs: b.getChild(2), contents: b.getChild([3, 0, 0, 0]), footer: b.getChild([3, 0, 1, 0])}}
        }

        function d(a, b, d) {
            this.element = b;
            this.focusIndex = d;
            this.tabIndex = 0;
            this.isFocusable = function () {
                return!b.getAttribute("disabled") && b.isVisible()
            };
            this.focus = function () {
                a._.currentFocusIndex = this.focusIndex;
                this.element.focus()
            };
            b.on("keydown", function (a) {
                a.data.getKeystroke()in{32: 1, 13: 1} && this.fire("click")
            });
            b.on("focus", function () {
                this.fire("mouseover")
            });
            b.on("blur", function () {
                this.fire("mouseout")
            })
        }

        function i(a) {
            function b() {
                a.layout()
            }

            var d = CKEDITOR.document.getWindow();
            d.on("resize", b);
            a.on("hide", function () {
                d.removeListener("resize", b)
            })
        }

        function e(a, b) {
            this._ = {dialog: a};
            CKEDITOR.tools.extend(this, b)
        }

        function j(a) {
            function b(d) {
                var i = a.getSize(), j = CKEDITOR.document.getWindow().getViewPaneSize(), o = d.data.$.screenX, k = d.data.$.screenY, p = o - c.x, l = k - c.y;
                c = {x: o, y: k};
                e.x = e.x + p;
                e.y = e.y + l;
                a.move(e.x + h[3] < g ? -h[3] : e.x - h[1] > j.width - i.width - g ? j.width - i.width +
                    (f.lang.dir == "rtl" ? 0 : h[1]) : e.x, e.y + h[0] < g ? -h[0] : e.y - h[2] > j.height - i.height - g ? j.height - i.height + h[2] : e.y, 1);
                d.data.preventDefault()
            }

            function d() {
                CKEDITOR.document.removeListener("mousemove", b);
                CKEDITOR.document.removeListener("mouseup", d);
                if (CKEDITOR.env.ie6Compat) {
                    var a = r.getChild(0).getFrameDocument();
                    a.removeListener("mousemove", b);
                    a.removeListener("mouseup", d)
                }
            }

            var c = null, e = null;
            a.getElement().getFirst();
            var f = a.getParentEditor(), g = f.config.dialog_magnetDistance, h = CKEDITOR.skin.margins || [0, 0, 0,
                0];
            typeof g == "undefined" && (g = 20);
            a.parts.title.on("mousedown", function (f) {
                c = {x: f.data.$.screenX, y: f.data.$.screenY};
                CKEDITOR.document.on("mousemove", b);
                CKEDITOR.document.on("mouseup", d);
                e = a.getPosition();
                if (CKEDITOR.env.ie6Compat) {
                    var g = r.getChild(0).getFrameDocument();
                    g.on("mousemove", b);
                    g.on("mouseup", d)
                }
                f.data.preventDefault()
            }, a)
        }

        function k(a) {
            var b, d;

            function c(e) {
                var p = h.lang.dir == "rtl", l = k.width, m = k.height, q = l + (e.data.$.screenX - b) * (p ? -1 : 1) * (a._.moved ? 1 : 2), s = m + (e.data.$.screenY - d) * (a._.moved ?
                    1 : 2), n = a._.element.getFirst(), n = p && n.getComputedStyle("right"), t = a.getPosition();
                t.y + s > o.height && (s = o.height - t.y);
                if ((p ? n : t.x) + q > o.width)q = o.width - (p ? n : t.x);
                if (g == CKEDITOR.DIALOG_RESIZE_WIDTH || g == CKEDITOR.DIALOG_RESIZE_BOTH)l = Math.max(f.minWidth || 0, q - i);
                if (g == CKEDITOR.DIALOG_RESIZE_HEIGHT || g == CKEDITOR.DIALOG_RESIZE_BOTH)m = Math.max(f.minHeight || 0, s - j);
                a.resize(l, m);
                a._.moved || a.layout();
                e.data.preventDefault()
            }

            function e() {
                CKEDITOR.document.removeListener("mouseup", e);
                CKEDITOR.document.removeListener("mousemove",
                    c);
                if (p) {
                    p.remove();
                    p = null
                }
                if (CKEDITOR.env.ie6Compat) {
                    var a = r.getChild(0).getFrameDocument();
                    a.removeListener("mouseup", e);
                    a.removeListener("mousemove", c)
                }
            }

            var f = a.definition, g = f.resizable;
            if (g != CKEDITOR.DIALOG_RESIZE_NONE) {
                var h = a.getParentEditor(), i, j, o, k, p, l = CKEDITOR.tools.addFunction(function (f) {
                    k = a.getSize();
                    var g = a.parts.contents;
                    if (g.$.getElementsByTagName("iframe").length) {
                        p = CKEDITOR.dom.element.createFromHtml('<div class="cke_dialog_resize_cover" style="height: 100%; position: absolute; width: 100%;"></div>');
                        g.append(p)
                    }
                    j = k.height - a.parts.contents.getSize("height", !(CKEDITOR.env.gecko || CKEDITOR.env.opera || CKEDITOR.env.ie && CKEDITOR.env.quirks));
                    i = k.width - a.parts.contents.getSize("width", 1);
                    b = f.screenX;
                    d = f.screenY;
                    o = CKEDITOR.document.getWindow().getViewPaneSize();
                    CKEDITOR.document.on("mousemove", c);
                    CKEDITOR.document.on("mouseup", e);
                    if (CKEDITOR.env.ie6Compat) {
                        g = r.getChild(0).getFrameDocument();
                        g.on("mousemove", c);
                        g.on("mouseup", e)
                    }
                    f.preventDefault && f.preventDefault()
                });
                a.on("load", function () {
                    var b = "";
                    g ==
                        CKEDITOR.DIALOG_RESIZE_WIDTH ? b = " cke_resizer_horizontal" : g == CKEDITOR.DIALOG_RESIZE_HEIGHT && (b = " cke_resizer_vertical");
                    b = CKEDITOR.dom.element.createFromHtml('<div class="cke_resizer' + b + " cke_resizer_" + h.lang.dir + '" title="' + CKEDITOR.tools.htmlEncode(h.lang.common.resize) + '" onmousedown="CKEDITOR.tools.callFunction(' + l + ', event )">' + (h.lang.dir == "ltr" ? "◢" : "◣") + "</div>");
                    a.parts.footer.append(b, 1)
                });
                h.on("destroy", function () {
                    CKEDITOR.tools.removeFunction(l)
                })
            }
        }

        function m(a) {
            a.data.preventDefault(1)
        }

        function n(a) {
            var b = CKEDITOR.document.getWindow(), d = a.config, c = d.dialog_backgroundCoverColor || "white", e = d.dialog_backgroundCoverOpacity, f = d.baseFloatZIndex, d = CKEDITOR.tools.genKey(c, e, f), g = v[d];
            if (g)g.show(); else {
                f = ['<div tabIndex="-1" style="position: ', CKEDITOR.env.ie6Compat ? "absolute" : "fixed", "; z-index: ", f, "; top: 0px; left: 0px; ", !CKEDITOR.env.ie6Compat ? "background-color: " + c : "", '" class="cke_dialog_background_cover">'];
                if (CKEDITOR.env.ie6Compat) {
                    var h = CKEDITOR.env.isCustomDomain(), c = "<html><body style=\\'background-color:" +
                        c + ";\\'></body></html>";
                    f.push('<iframe hidefocus="true" frameborder="0" id="cke_dialog_background_iframe" src="javascript:');
                    f.push("void((function(){document.open();" + (h ? "document.domain='" + document.domain + "';" : "") + "document.write( '" + c + "' );document.close();})())");
                    f.push('" style="position:absolute;left:0;top:0;width:100%;height: 100%;filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0)"></iframe>')
                }
                f.push("</div>");
                g = CKEDITOR.dom.element.createFromHtml(f.join(""));
                g.setOpacity(e != void 0 ?
                    e : 0.5);
                g.on("keydown", m);
                g.on("keypress", m);
                g.on("keyup", m);
                g.appendTo(CKEDITOR.document.getBody());
                v[d] = g
            }
            a.focusManager.add(g);
            r = g;
            var a = function () {
                var a = b.getViewPaneSize();
                g.setStyles({width: a.width + "px", height: a.height + "px"})
            }, i = function () {
                var a = b.getScrollPosition(), d = CKEDITOR.dialog._.currentTop;
                g.setStyles({left: a.x + "px", top: a.y + "px"});
                if (d) {
                    do {
                        a = d.getPosition();
                        d.move(a.x, a.y)
                    } while (d = d._.parentDialog)
                }
            };
            w = a;
            b.on("resize", a);
            a();
            (!CKEDITOR.env.mac || !CKEDITOR.env.webkit) && g.focus();
            if (CKEDITOR.env.ie6Compat) {
                var j =
                    function () {
                        i();
                        arguments.callee.prevScrollHandler.apply(this, arguments)
                    };
                b.$.setTimeout(function () {
                    j.prevScrollHandler = window.onscroll || function () {
                    };
                    window.onscroll = j
                }, 0);
                i()
            }
        }

        function l(a) {
            if (r) {
                a.focusManager.remove(r);
                a = CKEDITOR.document.getWindow();
                r.hide();
                a.removeListener("resize", w);
                CKEDITOR.env.ie6Compat && a.$.setTimeout(function () {
                    window.onscroll = window.onscroll && window.onscroll.prevScrollHandler || null
                }, 0);
                w = null
            }
        }

        var o = CKEDITOR.tools.cssLength, q = '<div class="cke cke_reset_all {editorId} {editorDialogClass}" dir="{langDir}" lang="{langCode}" role="application"><table class="cke_dialog ' +
            CKEDITOR.env.cssClass + ' cke_{langDir}" aria-labelledby="cke_dialog_title_{id}" style="position:absolute" role="dialog"><tr><td role="presentation"><div class="cke_dialog_body" role="presentation"><div id="cke_dialog_title_{id}" class="cke_dialog_title" role="presentation"></div><a id="cke_dialog_close_button_{id}" class="cke_dialog_close_button" href="javascript:void(0)" title="{closeTitle}" role="button"><span class="cke_label">X</span></a><div id="cke_dialog_tabs_{id}" class="cke_dialog_tabs" role="tablist"></div><table class="cke_dialog_contents" role="presentation"><tr><td id="cke_dialog_contents_{id}" class="cke_dialog_contents_body" role="presentation"></td></tr><tr><td id="cke_dialog_footer_{id}" class="cke_dialog_footer" role="presentation"></td></tr></table></div></td></tr></table></div>';
        CKEDITOR.dialog = function (a, d) {
            function e() {
                var a = r._.focusList;
                a.sort(function (a, b) {
                    return a.tabIndex != b.tabIndex ? b.tabIndex - a.tabIndex : a.focusIndex - b.focusIndex
                });
                for (var b = a.length, d = 0; d < b; d++)a[d].focusIndex = d
            }

            function i(a) {
                var b = r._.focusList, a = a || 0;
                if (!(b.length < 1)) {
                    var d = r._.currentFocusIndex;
                    try {
                        b[d].getInputElement().$.blur()
                    } catch (c) {
                    }
                    for (var e = d = (d + a + b.length) % b.length; a && !b[e].isFocusable();) {
                        e = (e + a + b.length) % b.length;
                        if (e == d)break
                    }
                    b[e].focus();
                    b[e].type == "text" && b[e].select()
                }
            }

            function o(d) {
                if (r ==
                    CKEDITOR.dialog._.currentTop) {
                    var e = d.data.getKeystroke(), f = a.lang.dir == "rtl";
                    v = w = 0;
                    if (e == 9 || e == CKEDITOR.SHIFT + 9) {
                        e = e == CKEDITOR.SHIFT + 9;
                        if (r._.tabBarMode) {
                            e = e ? b.call(r) : c.call(r);
                            r.selectPage(e);
                            r._.tabs[e][0].focus()
                        } else i(e ? -1 : 1);
                        v = 1
                    } else if (e == CKEDITOR.ALT + 121 && !r._.tabBarMode && r.getPageCount() > 1) {
                        r._.tabBarMode = true;
                        r._.tabs[r._.currentTabId][0].focus();
                        v = 1
                    } else if ((e == 37 || e == 39) && r._.tabBarMode) {
                        e = e == (f ? 39 : 37) ? b.call(r) : c.call(r);
                        r.selectPage(e);
                        r._.tabs[e][0].focus();
                        v = 1
                    } else if ((e == 13 || e ==
                        32) && r._.tabBarMode) {
                        this.selectPage(this._.currentTabId);
                        this._.tabBarMode = false;
                        this._.currentFocusIndex = -1;
                        i(1);
                        v = 1
                    } else if (e == 13) {
                        e = d.data.getTarget();
                        if (!e.is("a", "button", "select", "textarea") && (!e.is("input") || e.$.type != "button")) {
                            (e = this.getButton("ok")) && CKEDITOR.tools.setTimeout(e.click, 0, e);
                            v = 1
                        }
                        w = 1
                    } else if (e == 27) {
                        (e = this.getButton("cancel")) ? CKEDITOR.tools.setTimeout(e.click, 0, e) : this.fire("cancel", {hide: true}).hide !== false && this.hide();
                        w = 1
                    } else return;
                    p(d)
                }
            }

            function p(a) {
                v ? a.data.preventDefault(1) :
                    w && a.data.stopPropagation()
            }

            var l = CKEDITOR.dialog._.dialogDefinitions[d], m = CKEDITOR.tools.clone(s), q = a.config.dialog_buttonsOrder || "OS", n = a.lang.dir, t = {}, v, w;
            (q == "OS" && CKEDITOR.env.mac || q == "rtl" && n == "ltr" || q == "ltr" && n == "rtl") && m.buttons.reverse();
            l = CKEDITOR.tools.extend(l(a), m);
            l = CKEDITOR.tools.clone(l);
            l = new x(this, l);
            m = g(a);
            this._ = {editor: a, element: m.element, name: d, contentSize: {width: 0, height: 0}, size: {width: 0, height: 0}, contents: {}, buttons: {}, accessKeyMap: {}, tabs: {}, tabIdList: [], currentTabId: null,
                currentTabIndex: null, pageCount: 0, lastTab: null, tabBarMode: false, focusList: [], currentFocusIndex: 0, hasFocus: false};
            this.parts = m.parts;
            CKEDITOR.tools.setTimeout(function () {
                a.fire("ariaWidget", this.parts.contents)
            }, 0, this);
            m = {position: CKEDITOR.env.ie6Compat ? "absolute" : "fixed", top: 0, visibility: "hidden"};
            m[n == "rtl" ? "right" : "left"] = 0;
            this.parts.dialog.setStyles(m);
            CKEDITOR.event.call(this);
            this.definition = l = CKEDITOR.fire("dialogDefinition", {name: d, definition: l}, a).definition;
            if (!("removeDialogTabs"in a._) &&
                a.config.removeDialogTabs) {
                m = a.config.removeDialogTabs.split(";");
                for (n = 0; n < m.length; n++) {
                    q = m[n].split(":");
                    if (q.length == 2) {
                        var z = q[0];
                        t[z] || (t[z] = []);
                        t[z].push(q[1])
                    }
                }
                a._.removeDialogTabs = t
            }
            if (a._.removeDialogTabs && (t = a._.removeDialogTabs[d]))for (n = 0; n < t.length; n++)l.removeContents(t[n]);
            if (l.onLoad)this.on("load", l.onLoad);
            if (l.onShow)this.on("show", l.onShow);
            if (l.onHide)this.on("hide", l.onHide);
            if (l.onOk)this.on("ok", function (b) {
                a.fire("saveSnapshot");
                setTimeout(function () {
                        a.fire("saveSnapshot")
                    },
                    0);
                if (l.onOk.call(this, b) === false)b.data.hide = false
            });
            if (l.onCancel)this.on("cancel", function (a) {
                if (l.onCancel.call(this, a) === false)a.data.hide = false
            });
            var r = this, u = function (a) {
                var b = r._.contents, d = false, c;
                for (c in b)for (var e in b[c])if (d = a.call(this, b[c][e]))return
            };
            this.on("ok", function (a) {
                u(function (b) {
                    if (b.validate) {
                        var d = b.validate(this), c = typeof d == "string" || d === false;
                        if (c) {
                            a.data.hide = false;
                            a.stop()
                        }
                        f.call(b, !c, typeof d == "string" ? d : void 0);
                        return c
                    }
                })
            }, this, null, 0);
            this.on("cancel", function (b) {
                u(function (d) {
                    if (d.isChanged()) {
                        if (!confirm(a.lang.common.confirmCancel))b.data.hide =
                            false;
                        return true
                    }
                })
            }, this, null, 0);
            this.parts.close.on("click", function (a) {
                this.fire("cancel", {hide: true}).hide !== false && this.hide();
                a.data.preventDefault()
            }, this);
            this.changeFocus = i;
            var A = this._.element;
            a.focusManager.add(A, 1);
            this.on("show", function () {
                A.on("keydown", o, this);
                if (CKEDITOR.env.opera || CKEDITOR.env.gecko)A.on("keypress", p, this)
            });
            this.on("hide", function () {
                A.removeListener("keydown", o);
                (CKEDITOR.env.opera || CKEDITOR.env.gecko) && A.removeListener("keypress", p);
                u(function (a) {
                    h.apply(a)
                })
            });
            this.on("iframeAdded", function (a) {
                (new CKEDITOR.dom.document(a.data.iframe.$.contentWindow.document)).on("keydown", o, this, null, 0)
            });
            this.on("show", function () {
                e();
                if (a.config.dialog_startupFocusTab && r._.pageCount > 1) {
                    r._.tabBarMode = true;
                    r._.tabs[r._.currentTabId][0].focus()
                } else if (!this._.hasFocus) {
                    this._.currentFocusIndex = -1;
                    if (l.onFocus) {
                        var b = l.onFocus.call(this);
                        b && b.focus()
                    } else i(1)
                }
            }, this, null, 4294967295);
            if (CKEDITOR.env.ie6Compat)this.on("load", function () {
                var a = this.getElement(), b = a.getFirst();
                b.remove();
                b.appendTo(a)
            }, this);
            j(this);
            k(this);
            (new CKEDITOR.dom.text(l.title, CKEDITOR.document)).appendTo(this.parts.title);
            for (n = 0; n < l.contents.length; n++)(t = l.contents[n]) && this.addPage(t);
            this.parts.tabs.on("click", function (a) {
                var b = a.data.getTarget();
                if (b.hasClass("cke_dialog_tab")) {
                    b = b.$.id;
                    this.selectPage(b.substring(4, b.lastIndexOf("_")));
                    if (this._.tabBarMode) {
                        this._.tabBarMode = false;
                        this._.currentFocusIndex = -1;
                        i(1)
                    }
                    a.data.preventDefault()
                }
            }, this);
            n = [];
            t = CKEDITOR.dialog._.uiElementBuilders.hbox.build(this,
                {type: "hbox", className: "cke_dialog_footer_buttons", widths: [], children: l.buttons}, n).getChild();
            this.parts.footer.setHtml(n.join(""));
            for (n = 0; n < t.length; n++)this._.buttons[t[n].id] = t[n]
        };
        CKEDITOR.dialog.prototype = {destroy: function () {
            this.hide();
            this._.element.remove()
        }, resize: function () {
            return function (a, b) {
                if (!this._.contentSize || !(this._.contentSize.width == a && this._.contentSize.height == b)) {
                    CKEDITOR.dialog.fire("resize", {dialog: this, width: a, height: b}, this._.editor);
                    this.fire("resize", {width: a, height: b},
                        this._.editor);
                    this.parts.contents.setStyles({width: a + "px", height: b + "px"});
                    if (this._.editor.lang.dir == "rtl" && this._.position)this._.position.x = CKEDITOR.document.getWindow().getViewPaneSize().width - this._.contentSize.width - parseInt(this._.element.getFirst().getStyle("right"), 10);
                    this._.contentSize = {width: a, height: b}
                }
            }
        }(), getSize: function () {
            var a = this._.element.getFirst();
            return{width: a.$.offsetWidth || 0, height: a.$.offsetHeight || 0}
        }, move: function (a, b, d) {
            var c = this._.element.getFirst(), e = this._.editor.lang.dir ==
                "rtl", f = c.getComputedStyle("position") == "fixed";
            CKEDITOR.env.ie && c.setStyle("zoom", "100%");
            if (!f || !this._.position || !(this._.position.x == a && this._.position.y == b)) {
                this._.position = {x: a, y: b};
                if (!f) {
                    f = CKEDITOR.document.getWindow().getScrollPosition();
                    a = a + f.x;
                    b = b + f.y
                }
                if (e) {
                    f = this.getSize();
                    a = CKEDITOR.document.getWindow().getViewPaneSize().width - f.width - a
                }
                b = {top: (b > 0 ? b : 0) + "px"};
                b[e ? "right" : "left"] = (a > 0 ? a : 0) + "px";
                c.setStyles(b);
                d && (this._.moved = 1)
            }
        }, getPosition: function () {
            return CKEDITOR.tools.extend({},
                this._.position)
        }, show: function () {
            var a = this._.element, b = this.definition;
            !a.getParent() || !a.getParent().equals(CKEDITOR.document.getBody()) ? a.appendTo(CKEDITOR.document.getBody()) : a.setStyle("display", "block");
            if (CKEDITOR.env.gecko && CKEDITOR.env.version < 10900) {
                var d = this.parts.dialog;
                d.setStyle("position", "absolute");
                setTimeout(function () {
                    d.setStyle("position", "fixed")
                }, 0)
            }
            this.resize(this._.contentSize && this._.contentSize.width || b.width || b.minWidth, this._.contentSize && this._.contentSize.height ||
                b.height || b.minHeight);
            this.reset();
            this.selectPage(this.definition.contents[0].id);
            if (CKEDITOR.dialog._.currentZIndex === null)CKEDITOR.dialog._.currentZIndex = this._.editor.config.baseFloatZIndex;
            this._.element.getFirst().setStyle("z-index", CKEDITOR.dialog._.currentZIndex = CKEDITOR.dialog._.currentZIndex + 10);
            if (CKEDITOR.dialog._.currentTop === null) {
                CKEDITOR.dialog._.currentTop = this;
                this._.parentDialog = null;
                n(this._.editor)
            } else {
                this._.parentDialog = CKEDITOR.dialog._.currentTop;
                this._.parentDialog.getElement().getFirst().$.style.zIndex -=
                    Math.floor(this._.editor.config.baseFloatZIndex / 2);
                CKEDITOR.dialog._.currentTop = this
            }
            a.on("keydown", A);
            a.on(CKEDITOR.env.opera ? "keypress" : "keyup", B);
            this._.hasFocus = false;
            CKEDITOR.tools.setTimeout(function () {
                    this.layout();
                    i(this);
                    this.parts.dialog.setStyle("visibility", "");
                    this.fireOnce("load", {});
                    CKEDITOR.ui.fire("ready", this);
                    this.fire("show", {});
                    this._.editor.fire("dialogShow", this);
                    this._.parentDialog || this._.editor.focusManager.lock();
                    this.foreach(function (a) {
                        a.setInitValue && a.setInitValue()
                    })
                },
                100, this)
        }, layout: function () {
            var a = this.parts.dialog, b = this.getSize(), d = CKEDITOR.document.getWindow().getViewPaneSize(), c = (d.width - b.width) / 2, e = (d.height - b.height) / 2;
            CKEDITOR.env.ie6Compat || (b.height + (e > 0 ? e : 0) > d.height || b.width + (c > 0 ? c : 0) > d.width ? a.setStyle("position", "absolute") : a.setStyle("position", "fixed"));
            this.move(this._.moved ? this._.position.x : c, this._.moved ? this._.position.y : e)
        }, foreach: function (a) {
            for (var b in this._.contents)for (var d in this._.contents[b])a.call(this, this._.contents[b][d]);
            return this
        }, reset: function () {
            var a = function (a) {
                a.reset && a.reset(1)
            };
            return function () {
                this.foreach(a);
                return this
            }
        }(), setupContent: function () {
            var a = arguments;
            this.foreach(function (b) {
                b.setup && b.setup.apply(b, a)
            })
        }, commitContent: function () {
            var a = arguments;
            this.foreach(function (b) {
                CKEDITOR.env.ie && this._.currentFocusIndex == b.focusIndex && b.getInputElement().$.blur();
                b.commit && b.commit.apply(b, a)
            })
        }, hide: function () {
            if (this.parts.dialog.isVisible()) {
                this.fire("hide", {});
                this._.editor.fire("dialogHide",
                    this);
                this.selectPage(this._.tabIdList[0]);
                var a = this._.element;
                a.setStyle("display", "none");
                this.parts.dialog.setStyle("visibility", "hidden");
                for (C(this); CKEDITOR.dialog._.currentTop != this;)CKEDITOR.dialog._.currentTop.hide();
                if (this._.parentDialog) {
                    var b = this._.parentDialog.getElement().getFirst();
                    b.setStyle("z-index", parseInt(b.$.style.zIndex, 10) + Math.floor(this._.editor.config.baseFloatZIndex / 2))
                } else l(this._.editor);
                if (CKEDITOR.dialog._.currentTop = this._.parentDialog)CKEDITOR.dialog._.currentZIndex =
                    CKEDITOR.dialog._.currentZIndex - 10; else {
                    CKEDITOR.dialog._.currentZIndex = null;
                    a.removeListener("keydown", A);
                    a.removeListener(CKEDITOR.env.opera ? "keypress" : "keyup", B);
                    var d = this._.editor;
                    d.focus();
                    setTimeout(function () {
                        d.focusManager.unlock()
                    }, 0)
                }
                delete this._.parentDialog;
                this.foreach(function (a) {
                    a.resetInitValue && a.resetInitValue()
                })
            }
        }, addPage: function (a) {
            if (!a.requiredContent || this._.editor.filter.check(a.requiredContent)) {
                for (var b = [], d = a.label ? ' title="' + CKEDITOR.tools.htmlEncode(a.label) + '"' :
                    "", c = CKEDITOR.dialog._.uiElementBuilders.vbox.build(this, {type: "vbox", className: "cke_dialog_page_contents", children: a.elements, expand: !!a.expand, padding: a.padding, style: a.style || "width: 100%;"}, b), e = this._.contents[a.id] = {}, f = c.getChild(), g = 0; c = f.shift();) {
                    !c.notAllowed && (c.type != "hbox" && c.type != "vbox") && g++;
                    e[c.id] = c;
                    typeof c.getChild == "function" && f.push.apply(f, c.getChild())
                }
                if (!g)a.hidden = true;
                b = CKEDITOR.dom.element.createFromHtml(b.join(""));
                b.setAttribute("role", "tabpanel");
                c = CKEDITOR.env;
                e =
                    "cke_" + a.id + "_" + CKEDITOR.tools.getNextNumber();
                d = CKEDITOR.dom.element.createFromHtml(['<a class="cke_dialog_tab"', this._.pageCount > 0 ? " cke_last" : "cke_first", d, a.hidden ? ' style="display:none"' : "", ' id="', e, '"', c.gecko && c.version >= 10900 && !c.hc ? "" : ' href="javascript:void(0)"', ' tabIndex="-1" hidefocus="true" role="tab">', a.label, "</a>"].join(""));
                b.setAttribute("aria-labelledby", e);
                this._.tabs[a.id] = [d, b];
                this._.tabIdList.push(a.id);
                !a.hidden && this._.pageCount++;
                this._.lastTab = d;
                this.updateStyle();
                b.setAttribute("name",
                    a.id);
                b.appendTo(this.parts.contents);
                d.unselectable();
                this.parts.tabs.append(d);
                if (a.accessKey) {
                    y(this, this, "CTRL+" + a.accessKey, F, D);
                    this._.accessKeyMap["CTRL+" + a.accessKey] = a.id
                }
            }
        }, selectPage: function (b) {
            if (this._.currentTabId != b && this.fire("selectPage", {page: b, currentPage: this._.currentTabId}) !== true) {
                for (var d in this._.tabs) {
                    var c = this._.tabs[d][0], e = this._.tabs[d][1];
                    if (d != b) {
                        c.removeClass("cke_dialog_tab_selected");
                        e.hide()
                    }
                    e.setAttribute("aria-hidden", d != b)
                }
                var f = this._.tabs[b];
                f[0].addClass("cke_dialog_tab_selected");
                if (CKEDITOR.env.ie6Compat || CKEDITOR.env.ie7Compat) {
                    a(f[1]);
                    f[1].show();
                    setTimeout(function () {
                        a(f[1], 1)
                    }, 0)
                } else f[1].show();
                this._.currentTabId = b;
                this._.currentTabIndex = CKEDITOR.tools.indexOf(this._.tabIdList, b)
            }
        }, updateStyle: function () {
            this.parts.dialog[(this._.pageCount === 1 ? "add" : "remove") + "Class"]("cke_single_page")
        }, hidePage: function (a) {
            var d = this._.tabs[a] && this._.tabs[a][0];
            if (d && this._.pageCount != 1 && d.isVisible()) {
                a == this._.currentTabId && this.selectPage(b.call(this));
                d.hide();
                this._.pageCount--;
                this.updateStyle()
            }
        }, showPage: function (a) {
            if (a = this._.tabs[a] && this._.tabs[a][0]) {
                a.show();
                this._.pageCount++;
                this.updateStyle()
            }
        }, getElement: function () {
            return this._.element
        }, getName: function () {
            return this._.name
        }, getContentElement: function (a, b) {
            var d = this._.contents[a];
            return d && d[b]
        }, getValueOf: function (a, b) {
            return this.getContentElement(a, b).getValue()
        }, setValueOf: function (a, b, d) {
            return this.getContentElement(a, b).setValue(d)
        }, getButton: function (a) {
            return this._.buttons[a]
        }, click: function (a) {
            return this._.buttons[a].click()
        },
            disableButton: function (a) {
                return this._.buttons[a].disable()
            }, enableButton: function (a) {
                return this._.buttons[a].enable()
            }, getPageCount: function () {
                return this._.pageCount
            }, getParentEditor: function () {
                return this._.editor
            }, getSelectedElement: function () {
                return this.getParentEditor().getSelection().getSelectedElement()
            }, addFocusable: function (a, b) {
                if (typeof b == "undefined") {
                    b = this._.focusList.length;
                    this._.focusList.push(new d(this, a, b))
                } else {
                    this._.focusList.splice(b, 0, new d(this, a, b));
                    for (var c = b + 1; c <
                        this._.focusList.length; c++)this._.focusList[c].focusIndex++
                }
            }};
        CKEDITOR.tools.extend(CKEDITOR.dialog, {add: function (a, b) {
            if (!this._.dialogDefinitions[a] || typeof b == "function")this._.dialogDefinitions[a] = b
        }, exists: function (a) {
            return!!this._.dialogDefinitions[a]
        }, getCurrent: function () {
            return CKEDITOR.dialog._.currentTop
        }, isTabEnabled: function (a, b, d) {
            a = a.config.removeDialogTabs;
            return!(a && a.match(RegExp("(?:^|;)" + b + ":" + d + "(?:$|;)", "i")))
        }, okButton: function () {
            var a = function (a, b) {
                b = b || {};
                return CKEDITOR.tools.extend({id: "ok",
                    type: "button", label: a.lang.common.ok, "class": "cke_dialog_ui_button_ok", onClick: function (a) {
                        a = a.data.dialog;
                        a.fire("ok", {hide: true}).hide !== false && a.hide()
                    }}, b, true)
            };
            a.type = "button";
            a.override = function (b) {
                return CKEDITOR.tools.extend(function (d) {
                    return a(d, b)
                }, {type: "button"}, true)
            };
            return a
        }(), cancelButton: function () {
            var a = function (a, b) {
                b = b || {};
                return CKEDITOR.tools.extend({id: "cancel", type: "button", label: a.lang.common.cancel, "class": "cke_dialog_ui_button_cancel", onClick: function (a) {
                    a = a.data.dialog;
                    a.fire("cancel", {hide: true}).hide !== false && a.hide()
                }}, b, true)
            };
            a.type = "button";
            a.override = function (b) {
                return CKEDITOR.tools.extend(function (d) {
                    return a(d, b)
                }, {type: "button"}, true)
            };
            return a
        }(), addUIElement: function (a, b) {
            this._.uiElementBuilders[a] = b
        }});
        CKEDITOR.dialog._ = {uiElementBuilders: {}, dialogDefinitions: {}, currentTop: null, currentZIndex: null};
        CKEDITOR.event.implementOn(CKEDITOR.dialog);
        CKEDITOR.event.implementOn(CKEDITOR.dialog.prototype);
        var s = {resizable: CKEDITOR.DIALOG_RESIZE_BOTH, minWidth: 600,
            minHeight: 400, buttons: [CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton]}, p = function (a, b, d) {
            for (var c = 0, e; e = a[c]; c++) {
                if (e.id == b)return e;
                if (d && e[d])if (e = p(e[d], b, d))return e
            }
            return null
        }, t = function (a, b, d, c, e) {
            if (d) {
                for (var f = 0, g; g = a[f]; f++) {
                    if (g.id == d) {
                        a.splice(f, 0, b);
                        return b
                    }
                    if (c && g[c])if (g = t(g[c], b, d, c, true))return g
                }
                if (e)return null
            }
            a.push(b);
            return b
        }, z = function (a, b, d) {
            for (var c = 0, e; e = a[c]; c++) {
                if (e.id == b)return a.splice(c, 1);
                if (d && e[d])if (e = z(e[d], b, d))return e
            }
            return null
        }, x = function (a, b) {
            this.dialog = a;
            for (var d = b.contents, c = 0, f; f = d[c]; c++)d[c] = f && new e(a, f);
            CKEDITOR.tools.extend(this, b)
        };
        x.prototype = {getContents: function (a) {
            return p(this.contents, a)
        }, getButton: function (a) {
            return p(this.buttons, a)
        }, addContents: function (a, b) {
            return t(this.contents, a, b)
        }, addButton: function (a, b) {
            return t(this.buttons, a, b)
        }, removeContents: function (a) {
            z(this.contents, a)
        }, removeButton: function (a) {
            z(this.buttons, a)
        }};
        e.prototype = {get: function (a) {
            return p(this.elements, a, "children")
        }, add: function (a, b) {
            return t(this.elements,
                a, b, "children")
        }, remove: function (a) {
            z(this.elements, a, "children")
        }};
        var w, v = {}, r, u = {}, A = function (a) {
                var b = a.data.$.ctrlKey || a.data.$.metaKey, d = a.data.$.altKey, c = a.data.$.shiftKey, e = String.fromCharCode(a.data.$.keyCode);
                if ((b = u[(b ? "CTRL+" : "") + (d ? "ALT+" : "") + (c ? "SHIFT+" : "") + e]) && b.length) {
                    b = b[b.length - 1];
                    b.keydown && b.keydown.call(b.uiElement, b.dialog, b.key);
                    a.data.preventDefault()
                }
            }, B = function (a) {
                var b = a.data.$.ctrlKey || a.data.$.metaKey, d = a.data.$.altKey, c = a.data.$.shiftKey, e = String.fromCharCode(a.data.$.keyCode);
                if ((b = u[(b ? "CTRL+" : "") + (d ? "ALT+" : "") + (c ? "SHIFT+" : "") + e]) && b.length) {
                    b = b[b.length - 1];
                    if (b.keyup) {
                        b.keyup.call(b.uiElement, b.dialog, b.key);
                        a.data.preventDefault()
                    }
                }
            }, y = function (a, b, d, c, e) {
                (u[d] || (u[d] = [])).push({uiElement: a, dialog: b, key: d, keyup: e || a.accessKeyUp, keydown: c || a.accessKeyDown})
            }, C = function (a) {
                for (var b in u) {
                    for (var d = u[b], c = d.length - 1; c >= 0; c--)(d[c].dialog == a || d[c].uiElement == a) && d.splice(c, 1);
                    d.length === 0 && delete u[b]
                }
            }, D = function (a, b) {
                a._.accessKeyMap[b] && a.selectPage(a._.accessKeyMap[b])
            },
            F = function () {
            };
        (function () {
            CKEDITOR.ui.dialog = {uiElement: function (a, b, d, c, e, f, g) {
                if (!(arguments.length < 4)) {
                    var h = (c.call ? c(b) : c) || "div", i = ["<", h, " "], j = (e && e.call ? e(b) : e) || {}, o = (f && f.call ? f(b) : f) || {}, k = (g && g.call ? g.call(this, a, b) : g) || "", p = this.domId = o.id || CKEDITOR.tools.getNextId() + "_uiElement";
                    this.id = b.id;
                    if (b.requiredContent && !a.getParentEditor().filter.check(b.requiredContent)) {
                        j.display = "none";
                        this.notAllowed = true
                    }
                    o.id = p;
                    var l = {};
                    b.type && (l["cke_dialog_ui_" + b.type] = 1);
                    b.className && (l[b.className] =
                        1);
                    b.disabled && (l.cke_disabled = 1);
                    for (var m = o["class"] && o["class"].split ? o["class"].split(" ") : [], p = 0; p < m.length; p++)m[p] && (l[m[p]] = 1);
                    m = [];
                    for (p in l)m.push(p);
                    o["class"] = m.join(" ");
                    if (b.title)o.title = b.title;
                    l = (b.style || "").split(";");
                    if (b.align) {
                        m = b.align;
                        j["margin-left"] = m == "left" ? 0 : "auto";
                        j["margin-right"] = m == "right" ? 0 : "auto"
                    }
                    for (p in j)l.push(p + ":" + j[p]);
                    b.hidden && l.push("display:none");
                    for (p = l.length - 1; p >= 0; p--)l[p] === "" && l.splice(p, 1);
                    if (l.length > 0)o.style = (o.style ? o.style + "; " : "") + l.join("; ");
                    for (p in o)i.push(p + '="' + CKEDITOR.tools.htmlEncode(o[p]) + '" ');
                    i.push(">", k, "</", h, ">");
                    d.push(i.join(""));
                    (this._ || (this._ = {})).dialog = a;
                    if (typeof b.isChanged == "boolean")this.isChanged = function () {
                        return b.isChanged
                    };
                    if (typeof b.isChanged == "function")this.isChanged = b.isChanged;
                    if (typeof b.setValue == "function")this.setValue = CKEDITOR.tools.override(this.setValue, function (a) {
                        return function (d) {
                            a.call(this, b.setValue.call(this, d))
                        }
                    });
                    if (typeof b.getValue == "function")this.getValue = CKEDITOR.tools.override(this.getValue,
                        function (a) {
                            return function () {
                                return b.getValue.call(this, a.call(this))
                            }
                        });
                    CKEDITOR.event.implementOn(this);
                    this.registerEvents(b);
                    this.accessKeyUp && (this.accessKeyDown && b.accessKey) && y(this, a, "CTRL+" + b.accessKey);
                    var q = this;
                    a.on("load", function () {
                        var b = q.getInputElement();
                        if (b) {
                            var d = q.type in{checkbox: 1, ratio: 1} && CKEDITOR.env.ie && CKEDITOR.env.version < 8 ? "cke_dialog_ui_focused" : "";
                            b.on("focus", function () {
                                a._.tabBarMode = false;
                                a._.hasFocus = true;
                                q.fire("focus");
                                d && this.addClass(d)
                            });
                            b.on("blur", function () {
                                q.fire("blur");
                                d && this.removeClass(d)
                            })
                        }
                    });
                    if (this.keyboardFocusable) {
                        this.tabIndex = b.tabIndex || 0;
                        this.focusIndex = a._.focusList.push(this) - 1;
                        this.on("focus", function () {
                            a._.currentFocusIndex = q.focusIndex
                        })
                    }
                    CKEDITOR.tools.extend(this, b)
                }
            }, hbox: function (a, b, d, c, e) {
                if (!(arguments.length < 4)) {
                    this._ || (this._ = {});
                    var f = this._.children = b, g = e && e.widths || null, h = e && e.height || null, i, j = {role: "presentation"};
                    e && e.align && (j.align = e.align);
                    CKEDITOR.ui.dialog.uiElement.call(this, a, e || {type: "hbox"}, c, "table", {}, j, function () {
                        var a =
                            ['<tbody><tr class="cke_dialog_ui_hbox">'];
                        for (i = 0; i < d.length; i++) {
                            var b = "cke_dialog_ui_hbox_child", c = [];
                            i === 0 && (b = "cke_dialog_ui_hbox_first");
                            i == d.length - 1 && (b = "cke_dialog_ui_hbox_last");
                            a.push('<td class="', b, '" role="presentation" ');
                            g ? g[i] && c.push("width:" + o(g[i])) : c.push("width:" + Math.floor(100 / d.length) + "%");
                            h && c.push("height:" + o(h));
                            e && e.padding != void 0 && c.push("padding:" + o(e.padding));
                            CKEDITOR.env.ie && (CKEDITOR.env.quirks && f[i].align) && c.push("text-align:" + f[i].align);
                            c.length > 0 && a.push('style="' +
                                c.join("; ") + '" ');
                            a.push(">", d[i], "</td>")
                        }
                        a.push("</tr></tbody>");
                        return a.join("")
                    })
                }
            }, vbox: function (a, b, d, c, e) {
                if (!(arguments.length < 3)) {
                    this._ || (this._ = {});
                    var f = this._.children = b, g = e && e.width || null, h = e && e.heights || null;
                    CKEDITOR.ui.dialog.uiElement.call(this, a, e || {type: "vbox"}, c, "div", null, {role: "presentation"}, function () {
                        var b = ['<table role="presentation" cellspacing="0" border="0" '];
                        b.push('style="');
                        e && e.expand && b.push("height:100%;");
                        b.push("width:" + o(g || "100%"), ";");
                        CKEDITOR.env.webkit &&
                        b.push("float:none;");
                        b.push('"');
                        b.push('align="', CKEDITOR.tools.htmlEncode(e && e.align || (a.getParentEditor().lang.dir == "ltr" ? "left" : "right")), '" ');
                        b.push("><tbody>");
                        for (var c = 0; c < d.length; c++) {
                            var i = [];
                            b.push('<tr><td role="presentation" ');
                            g && i.push("width:" + o(g || "100%"));
                            h ? i.push("height:" + o(h[c])) : e && e.expand && i.push("height:" + Math.floor(100 / d.length) + "%");
                            e && e.padding != void 0 && i.push("padding:" + o(e.padding));
                            CKEDITOR.env.ie && (CKEDITOR.env.quirks && f[c].align) && i.push("text-align:" + f[c].align);
                            i.length > 0 && b.push('style="', i.join("; "), '" ');
                            b.push(' class="cke_dialog_ui_vbox_child">', d[c], "</td></tr>")
                        }
                        b.push("</tbody></table>");
                        return b.join("")
                    })
                }
            }}
        })();
        CKEDITOR.ui.dialog.uiElement.prototype = {getElement: function () {
            return CKEDITOR.document.getById(this.domId)
        }, getInputElement: function () {
            return this.getElement()
        }, getDialog: function () {
            return this._.dialog
        }, setValue: function (a, b) {
            this.getInputElement().setValue(a);
            !b && this.fire("change", {value: a});
            return this
        }, getValue: function () {
            return this.getInputElement().getValue()
        },
            isChanged: function () {
                return false
            }, selectParentTab: function () {
                for (var a = this.getInputElement(); (a = a.getParent()) && a.$.className.search("cke_dialog_page_contents") == -1;);
                if (!a)return this;
                a = a.getAttribute("name");
                this._.dialog._.currentTabId != a && this._.dialog.selectPage(a);
                return this
            }, focus: function () {
                this.selectParentTab().getInputElement().focus();
                return this
            }, registerEvents: function (a) {
                var b = /^on([A-Z]\w+)/, d, c = function (a, b, d, c) {
                    b.on("load", function () {
                        a.getInputElement().on(d, c, a)
                    })
                }, e;
                for (e in a)if (d =
                    e.match(b))this.eventProcessors[e] ? this.eventProcessors[e].call(this, this._.dialog, a[e]) : c(this, this._.dialog, d[1].toLowerCase(), a[e]);
                return this
            }, eventProcessors: {onLoad: function (a, b) {
                a.on("load", b, this)
            }, onShow: function (a, b) {
                a.on("show", b, this)
            }, onHide: function (a, b) {
                a.on("hide", b, this)
            }}, accessKeyDown: function () {
                this.focus()
            }, accessKeyUp: function () {
            }, disable: function () {
                var a = this.getElement();
                this.getInputElement().setAttribute("disabled", "true");
                a.addClass("cke_disabled")
            }, enable: function () {
                var a =
                    this.getElement();
                this.getInputElement().removeAttribute("disabled");
                a.removeClass("cke_disabled")
            }, isEnabled: function () {
                return!this.getElement().hasClass("cke_disabled")
            }, isVisible: function () {
                return this.getInputElement().isVisible()
            }, isFocusable: function () {
                return!this.isEnabled() || !this.isVisible() ? false : true
            }};
        CKEDITOR.ui.dialog.hbox.prototype = CKEDITOR.tools.extend(new CKEDITOR.ui.dialog.uiElement, {getChild: function (a) {
            if (arguments.length < 1)return this._.children.concat();
            a.splice || (a = [a]);
            return a.length <
                2 ? this._.children[a[0]] : this._.children[a[0]] && this._.children[a[0]].getChild ? this._.children[a[0]].getChild(a.slice(1, a.length)) : null
        }}, true);
        CKEDITOR.ui.dialog.vbox.prototype = new CKEDITOR.ui.dialog.hbox;
        (function () {
            var a = {build: function (a, b, d) {
                for (var c = b.children, e, f = [], g = [], h = 0; h < c.length && (e = c[h]); h++) {
                    var i = [];
                    f.push(i);
                    g.push(CKEDITOR.dialog._.uiElementBuilders[e.type].build(a, e, i))
                }
                return new CKEDITOR.ui.dialog[b.type](a, g, f, d, b)
            }};
            CKEDITOR.dialog.addUIElement("hbox", a);
            CKEDITOR.dialog.addUIElement("vbox",
                a)
        })();
        CKEDITOR.dialogCommand = function (a, b) {
            this.dialogName = a;
            CKEDITOR.tools.extend(this, b, true)
        };
        CKEDITOR.dialogCommand.prototype = {exec: function (a) {
            CKEDITOR.env.opera ? CKEDITOR.tools.setTimeout(function () {
                a.openDialog(this.dialogName)
            }, 0, this) : a.openDialog(this.dialogName)
        }, canUndo: false, editorFocus: 1};
        (function () {
            var a = /^([a]|[^a])+$/, b = /^\d*$/, d = /^\d*(?:\.\d+)?$/, c = /^(((\d*(\.\d+))|(\d*))(px|\%)?)?$/, e = /^(((\d*(\.\d+))|(\d*))(px|em|ex|in|cm|mm|pt|pc|\%)?)?$/i, f = /^(\s*[\w-]+\s*:\s*[^:;]+(?:;|$))*$/;
            CKEDITOR.VALIDATE_OR = 1;
            CKEDITOR.VALIDATE_AND = 2;
            CKEDITOR.dialog.validate = {functions: function () {
                var a = arguments;
                return function () {
                    var b = this && this.getValue ? this.getValue() : a[0], d = void 0, c = CKEDITOR.VALIDATE_AND, e = [], f;
                    for (f = 0; f < a.length; f++)if (typeof a[f] == "function")e.push(a[f]); else break;
                    if (f < a.length && typeof a[f] == "string") {
                        d = a[f];
                        f++
                    }
                    f < a.length && typeof a[f] == "number" && (c = a[f]);
                    var g = c == CKEDITOR.VALIDATE_AND ? true : false;
                    for (f = 0; f < e.length; f++)g = c == CKEDITOR.VALIDATE_AND ? g && e[f](b) : g || e[f](b);
                    return!g ?
                        d : true
                }
            }, regex: function (a, b) {
                return function (d) {
                    d = this && this.getValue ? this.getValue() : d;
                    return!a.test(d) ? b : true
                }
            }, notEmpty: function (b) {
                return this.regex(a, b)
            }, integer: function (a) {
                return this.regex(b, a)
            }, number: function (a) {
                return this.regex(d, a)
            }, cssLength: function (a) {
                return this.functions(function (a) {
                    return e.test(CKEDITOR.tools.trim(a))
                }, a)
            }, htmlLength: function (a) {
                return this.functions(function (a) {
                    return c.test(CKEDITOR.tools.trim(a))
                }, a)
            }, inlineStyle: function (a) {
                return this.functions(function (a) {
                        return f.test(CKEDITOR.tools.trim(a))
                    },
                    a)
            }, equals: function (a, b) {
                return this.functions(function (b) {
                    return b == a
                }, b)
            }, notEqual: function (a, b) {
                return this.functions(function (b) {
                    return b != a
                }, b)
            }};
            CKEDITOR.on("instanceDestroyed", function (a) {
                if (CKEDITOR.tools.isEmpty(CKEDITOR.instances)) {
                    for (var b; b = CKEDITOR.dialog._.currentTop;)b.hide();
                    for (var d in v)v[d].remove();
                    v = {}
                }
                var a = a.editor._.storedDialogs, c;
                for (c in a)a[c].destroy()
            })
        })();
        CKEDITOR.tools.extend(CKEDITOR.editor.prototype, {openDialog: function (a, b) {
            var d = null, c = CKEDITOR.dialog._.dialogDefinitions[a];
            CKEDITOR.dialog._.currentTop === null && n(this);
            if (typeof c == "function") {
                d = this._.storedDialogs || (this._.storedDialogs = {});
                d = d[a] || (d[a] = new CKEDITOR.dialog(this, a));
                b && b.call(d, d);
                d.show()
            } else {
                if (c == "failed") {
                    l(this);
                    throw Error('[CKEDITOR.dialog.openDialog] Dialog "' + a + '" failed when loading definition.');
                }
                typeof c == "string" && CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(c), function () {
                    typeof CKEDITOR.dialog._.dialogDefinitions[a] != "function" && (CKEDITOR.dialog._.dialogDefinitions[a] = "failed");
                    this.openDialog(a,
                        b)
                }, this, 0, 1)
            }
            CKEDITOR.skin.loadPart("dialog");
            return d
        }})
    }(), CKEDITOR.plugins.add("dialog", {requires: "dialogui", init: function (b) {
        b.on("contentDom", function () {
            var c = b.editable();
            c.attachListener(c, "dblclick", function (a) {
                if (b.readOnly)return false;
                a = {element: a.data.getTarget()};
                b.fire("doubleclick", a);
                a.dialog && b.openDialog(a.dialog);
                return 1
            })
        })
    }}), function () {
        CKEDITOR.plugins.add("a11yhelp", {requires: "dialog", availableLangs: {ar: 1, bg: 1, ca: 1, cs: 1, cy: 1, da: 1, de: 1, el: 1, en: 1, eo: 1, es: 1, et: 1, fa: 1, fi: 1, fr: 1,
            "fr-ca": 1, gl: 1, gu: 1, he: 1, hi: 1, hr: 1, hu: 1, it: 1, ja: 1, km: 1, ku: 1, lt: 1, lv: 1, mk: 1, mn: 1, nb: 1, nl: 1, no: 1, pl: 1, pt: 1, "pt-br": 1, ro: 1, ru: 1, sk: 1, sl: 1, sq: 1, sv: 1, th: 1, tr: 1, ug: 1, uk: 1, vi: 1, "zh-cn": 1}, init: function (b) {
            var c = this;
            b.addCommand("a11yHelp", {exec: function () {
                var a = b.langCode, a = c.availableLangs[a] ? a : c.availableLangs[a.replace(/-.*/, "")] ? a.replace(/-.*/, "") : "en";
                CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(c.path + "dialogs/lang/" + a + ".js"), function () {
                    b.lang.a11yhelp = c.langEntries[a];
                    b.openDialog("a11yHelp")
                })
            },
                modes: {wysiwyg: 1, source: 1}, readOnly: 1, canUndo: false});
            b.setKeystroke(CKEDITOR.ALT + 48, "a11yHelp");
            CKEDITOR.dialog.add("a11yHelp", this.path + "dialogs/a11yhelp.js")
        }})
    }(), CKEDITOR.plugins.add("about", {requires: "dialog", init: function (b) {
        var c = b.addCommand("about", new CKEDITOR.dialogCommand("about"));
        c.modes = {wysiwyg: 1, source: 1};
        c.canUndo = false;
        c.readOnly = 1;
        b.ui.addButton && b.ui.addButton("About", {label: b.lang.about.title, command: "about", toolbar: "about"});
        CKEDITOR.dialog.add("about", this.path + "dialogs/about.js")
    }}),
        CKEDITOR.plugins.add("basicstyles", {init: function (b) {
            var c = 0, a = function (a, g, e, h) {
                if (h) {
                    var h = new CKEDITOR.style(h), k = f[e];
                    k.unshift(h);
                    b.attachStyleStateChange(h, function (a) {
                        !b.readOnly && b.getCommand(e).setState(a)
                    });
                    b.addCommand(e, new CKEDITOR.styleCommand(h, {contentForms: k}));
                    b.ui.addButton && b.ui.addButton(a, {label: g, command: e, toolbar: "basicstyles," + (c = c + 10)})
                }
            }, f = {bold: ["strong", "b", ["span", function (a) {
                a = a.styles["font-weight"];
                return a == "bold" || +a >= 700
            }]], italic: ["em", "i", ["span", function (a) {
                return a.styles["font-style"] ==
                    "italic"
            }]], underline: ["u", ["span", function (a) {
                return a.styles["text-decoration"] == "underline"
            }]], strike: ["s", "strike", ["span", function (a) {
                return a.styles["text-decoration"] == "line-through"
            }]], subscript: ["sub"], superscript: ["sup"]}, h = b.config, g = b.lang.basicstyles;
            a("Bold", g.bold, "bold", h.coreStyles_bold);
            a("Italic", g.italic, "italic", h.coreStyles_italic);
            a("Underline", g.underline, "underline", h.coreStyles_underline);
            a("Strike", g.strike, "strike", h.coreStyles_strike);
            a("Subscript", g.subscript, "subscript",
                h.coreStyles_subscript);
            a("Superscript", g.superscript, "superscript", h.coreStyles_superscript);
            b.setKeystroke([
                [CKEDITOR.CTRL + 66, "bold"],
                [CKEDITOR.CTRL + 73, "italic"],
                [CKEDITOR.CTRL + 85, "underline"]
            ])
        }}), CKEDITOR.config.coreStyles_bold = {element: "strong", overrides: "b"}, CKEDITOR.config.coreStyles_italic = {element: "em", overrides: "i"}, CKEDITOR.config.coreStyles_underline = {element: "u"}, CKEDITOR.config.coreStyles_strike = {element: "s", overrides: "strike"}, CKEDITOR.config.coreStyles_subscript = {element: "sub"}, CKEDITOR.config.coreStyles_superscript =
    {element: "sup"}, function () {
        function b(a, b, d, c) {
            if (!a.isReadOnly() && !a.equals(d.editable())) {
                CKEDITOR.dom.element.setMarker(c, a, "bidi_processed", 1);
                for (var c = a, e = d.editable(); (c = c.getParent()) && !c.equals(e);)if (c.getCustomData("bidi_processed")) {
                    a.removeStyle("direction");
                    a.removeAttribute("dir");
                    return
                }
                c = "useComputedState"in d.config ? d.config.useComputedState : 1;
                if ((c ? a.getComputedStyle("direction") : a.getStyle("direction") || a.hasAttribute("dir")) != b) {
                    a.removeStyle("direction");
                    if (c) {
                        a.removeAttribute("dir");
                        b != a.getComputedStyle("direction") && a.setAttribute("dir", b)
                    } else a.setAttribute("dir", b);
                    d.forceNextSelectionCheck()
                }
            }
        }

        function c(a, b, d) {
            var c = a.getCommonAncestor(false, true), a = a.clone();
            a.enlarge(d == CKEDITOR.ENTER_BR ? CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS : CKEDITOR.ENLARGE_BLOCK_CONTENTS);
            if (a.checkBoundaryOfElement(c, CKEDITOR.START) && a.checkBoundaryOfElement(c, CKEDITOR.END)) {
                for (var e; c && c.type == CKEDITOR.NODE_ELEMENT && (e = c.getParent()) && e.getChildCount() == 1 && !(c.getName()in b);)c = e;
                return c.type == CKEDITOR.NODE_ELEMENT &&
                    c.getName()in b && c
            }
        }

        function a(a) {
            return{context: "p", allowedContent: {"h1 h2 h3 h4 h5 h6 table ul ol blockquote div tr p div li td": {propertiesOnly: true, attributes: "dir"}}, requiredContent: "p[dir]", refresh: function (a, b) {
                var c = a.config.useComputedState, e, c = c === void 0 || c;
                if (!c) {
                    e = b.lastElement;
                    for (var f = a.editable(); e && !(e.getName()in d || e.equals(f));) {
                        var g = e.getParent();
                        if (!g)break;
                        e = g
                    }
                }
                e = e || b.block || b.blockLimit;
                if (e.equals(a.editable()))(f = a.getSelection().getRanges()[0].getEnclosedNode()) && f.type ==
                    CKEDITOR.NODE_ELEMENT && (e = f);
                if (e) {
                    c = c ? e.getComputedStyle("direction") : e.getStyle("direction") || e.getAttribute("dir");
                    a.getCommand("bidirtl").setState(c == "rtl" ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF);
                    a.getCommand("bidiltr").setState(c == "ltr" ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF)
                }
                c = (b.block || b.blockLimit || a.editable()).getDirection(1);
                if (c != (a._.selDir || a.lang.dir)) {
                    a._.selDir = c;
                    a.fire("contentDirChanged", c)
                }
            }, exec: function (d) {
                var e = d.getSelection(), f = d.config.enterMode, i = e.getRanges();
                if (i &&
                    i.length) {
                    for (var j = {}, s = e.createBookmarks(), i = i.createIterator(), p, t = 0; p = i.getNextRange(1);) {
                        var z = p.getEnclosedNode();
                        if (!z || z && !(z.type == CKEDITOR.NODE_ELEMENT && z.getName()in g))z = c(p, h, f);
                        z && b(z, a, d, j);
                        var x = new CKEDITOR.dom.walker(p), w = s[t].startNode, v = s[t++].endNode;
                        x.evaluator = function (a) {
                            return!!(a.type == CKEDITOR.NODE_ELEMENT && a.getName()in h && !(a.getName() == (f == CKEDITOR.ENTER_P ? "p" : "div") && a.getParent().type == CKEDITOR.NODE_ELEMENT && a.getParent().getName() == "blockquote") && a.getPosition(w) &
                                CKEDITOR.POSITION_FOLLOWING && (a.getPosition(v) & CKEDITOR.POSITION_PRECEDING + CKEDITOR.POSITION_CONTAINS) == CKEDITOR.POSITION_PRECEDING)
                        };
                        for (; z = x.next();)b(z, a, d, j);
                        p = p.createIterator();
                        for (p.enlargeBr = f != CKEDITOR.ENTER_BR; z = p.getNextParagraph(f == CKEDITOR.ENTER_P ? "p" : "div");)b(z, a, d, j)
                    }
                    CKEDITOR.dom.element.clearAllMarkers(j);
                    d.forceNextSelectionCheck();
                    e.selectBookmarks(s);
                    d.focus()
                }
            }}
        }

        function f(a) {
            var b = a == i.setAttribute, d = a == i.removeAttribute, c = /\bdirection\s*:\s*(.*?)\s*(:?$|;)/;
            return function (e, f) {
                if (!this.isReadOnly()) {
                    var g;
                    if (g = e == (b || d ? "dir" : "direction") || e == "style" && (d || c.test(f))) {
                        a:{
                            g = this;
                            for (var h = g.getDocument().getBody().getParent(); g;) {
                                if (g.equals(h)) {
                                    g = false;
                                    break a
                                }
                                g = g.getParent()
                            }
                            g = true
                        }
                        g = !g
                    }
                    if (g) {
                        g = this.getDirection(1);
                        h = a.apply(this, arguments);
                        if (g != this.getDirection(1)) {
                            this.getDocument().fire("dirChanged", this);
                            return h
                        }
                    }
                }
                return a.apply(this, arguments)
            }
        }

        var h = {table: 1, ul: 1, ol: 1, blockquote: 1, div: 1}, g = {}, d = {};
        CKEDITOR.tools.extend(g, h, {tr: 1, p: 1, div: 1, li: 1});
        CKEDITOR.tools.extend(d,
            g, {td: 1});
        CKEDITOR.plugins.add("bidi", {init: function (b) {
            function d(a, c, e, f, g) {
                b.addCommand(e, new CKEDITOR.command(b, f));
                b.ui.addButton && b.ui.addButton(a, {label: c, command: e, toolbar: "bidi," + g})
            }

            if (!b.blockless) {
                var c = b.lang.bidi;
                b.ui.addToolbarGroup && b.ui.addToolbarGroup("bidi", "align", "paragraph");
                d("BidiLtr", c.ltr, "bidiltr", a("ltr"), 10);
                d("BidiRtl", c.rtl, "bidirtl", a("rtl"), 20);
                b.on("contentDom", function () {
                    b.document.on("dirChanged", function (a) {
                        b.fire("dirChanged", {node: a.data, dir: a.data.getDirection(1)})
                    })
                });
                b.on("contentDirChanged", function (a) {
                    var a = (b.lang.dir != a.data ? "add" : "remove") + "Class", d = b.ui.space(b.config.toolbarLocation);
                    if (d)d[a]("cke_mixed_dir_content")
                })
            }
        }});
        for (var i = CKEDITOR.dom.element.prototype, e = ["setStyle", "removeStyle", "setAttribute", "removeAttribute"], j = 0; j < e.length; j++)i[e[j]] = CKEDITOR.tools.override(i[e[j]], f)
    }(), function () {
        var b = {exec: function (b) {
            var a = b.getCommand("blockquote").state, f = b.getSelection(), h = f && f.getRanges(true)[0];
            if (h) {
                var g = f.createBookmarks();
                if (CKEDITOR.env.ie) {
                    var d =
                        g[0].startNode, i = g[0].endNode, e;
                    if (d && d.getParent().getName() == "blockquote")for (e = d; e = e.getNext();)if (e.type == CKEDITOR.NODE_ELEMENT && e.isBlockBoundary()) {
                        d.move(e, true);
                        break
                    }
                    if (i && i.getParent().getName() == "blockquote")for (e = i; e = e.getPrevious();)if (e.type == CKEDITOR.NODE_ELEMENT && e.isBlockBoundary()) {
                        i.move(e);
                        break
                    }
                }
                var j = h.createIterator();
                j.enlargeBr = b.config.enterMode != CKEDITOR.ENTER_BR;
                if (a == CKEDITOR.TRISTATE_OFF) {
                    for (d = []; a = j.getNextParagraph();)d.push(a);
                    if (d.length < 1) {
                        a = b.document.createElement(b.config.enterMode ==
                            CKEDITOR.ENTER_P ? "p" : "div");
                        i = g.shift();
                        h.insertNode(a);
                        a.append(new CKEDITOR.dom.text("﻿", b.document));
                        h.moveToBookmark(i);
                        h.selectNodeContents(a);
                        h.collapse(true);
                        i = h.createBookmark();
                        d.push(a);
                        g.unshift(i)
                    }
                    e = d[0].getParent();
                    h = [];
                    for (i = 0; i < d.length; i++) {
                        a = d[i];
                        e = e.getCommonAncestor(a.getParent())
                    }
                    for (a = {table: 1, tbody: 1, tr: 1, ol: 1, ul: 1}; a[e.getName()];)e = e.getParent();
                    for (i = null; d.length > 0;) {
                        for (a = d.shift(); !a.getParent().equals(e);)a = a.getParent();
                        a.equals(i) || h.push(a);
                        i = a
                    }
                    for (; h.length > 0;) {
                        a =
                            h.shift();
                        if (a.getName() == "blockquote") {
                            for (i = new CKEDITOR.dom.documentFragment(b.document); a.getFirst();) {
                                i.append(a.getFirst().remove());
                                d.push(i.getLast())
                            }
                            i.replace(a)
                        } else d.push(a)
                    }
                    h = b.document.createElement("blockquote");
                    for (h.insertBefore(d[0]); d.length > 0;) {
                        a = d.shift();
                        h.append(a)
                    }
                } else if (a == CKEDITOR.TRISTATE_ON) {
                    i = [];
                    for (e = {}; a = j.getNextParagraph();) {
                        for (d = h = null; a.getParent();) {
                            if (a.getParent().getName() == "blockquote") {
                                h = a.getParent();
                                d = a;
                                break
                            }
                            a = a.getParent()
                        }
                        if (h && d && !d.getCustomData("blockquote_moveout")) {
                            i.push(d);
                            CKEDITOR.dom.element.setMarker(e, d, "blockquote_moveout", true)
                        }
                    }
                    CKEDITOR.dom.element.clearAllMarkers(e);
                    a = [];
                    d = [];
                    for (e = {}; i.length > 0;) {
                        j = i.shift();
                        h = j.getParent();
                        if (j.getPrevious())if (j.getNext()) {
                            j.breakParent(j.getParent());
                            d.push(j.getNext())
                        } else j.remove().insertAfter(h); else j.remove().insertBefore(h);
                        if (!h.getCustomData("blockquote_processed")) {
                            d.push(h);
                            CKEDITOR.dom.element.setMarker(e, h, "blockquote_processed", true)
                        }
                        a.push(j)
                    }
                    CKEDITOR.dom.element.clearAllMarkers(e);
                    for (i = d.length - 1; i >= 0; i--) {
                        h =
                            d[i];
                        a:{
                            e = h;
                            for (var j = 0, k = e.getChildCount(), m = void 0; j < k && (m = e.getChild(j)); j++)if (m.type == CKEDITOR.NODE_ELEMENT && m.isBlockBoundary()) {
                                e = false;
                                break a
                            }
                            e = true
                        }
                        e && h.remove()
                    }
                    if (b.config.enterMode == CKEDITOR.ENTER_BR)for (h = true; a.length;) {
                        j = a.shift();
                        if (j.getName() == "div") {
                            i = new CKEDITOR.dom.documentFragment(b.document);
                            h && (j.getPrevious() && !(j.getPrevious().type == CKEDITOR.NODE_ELEMENT && j.getPrevious().isBlockBoundary())) && i.append(b.document.createElement("br"));
                            for (h = j.getNext() && !(j.getNext().type ==
                                CKEDITOR.NODE_ELEMENT && j.getNext().isBlockBoundary()); j.getFirst();)j.getFirst().remove().appendTo(i);
                            h && i.append(b.document.createElement("br"));
                            i.replace(j);
                            h = false
                        }
                    }
                }
                f.selectBookmarks(g);
                b.focus()
            }
        }, refresh: function (b, a) {
            this.setState(b.elementPath(a.block || a.blockLimit).contains("blockquote", 1) ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF)
        }, context: "blockquote", allowedContent: "blockquote", requiredContent: "blockquote"};
        CKEDITOR.plugins.add("blockquote", {init: function (c) {
            if (!c.blockless) {
                c.addCommand("blockquote",
                    b);
                c.ui.addButton && c.ui.addButton("Blockquote", {label: c.lang.blockquote.toolbar, command: "blockquote", toolbar: "blocks,10"})
            }
        }})
    }(), "use strict", function () {
        function b(a) {
            function b() {
                var c = a.editable();
                c.on(u, function (a) {
                    (!CKEDITOR.env.ie || !w) && t(a)
                });
                CKEDITOR.env.ie && c.on("paste", function (b) {
                    if (!v) {
                        g();
                        b.data.preventDefault();
                        t(b);
                        n("paste") || a.openDialog("paste")
                    }
                });
                if (CKEDITOR.env.ie) {
                    c.on("contextmenu", h, null, null, 0);
                    c.on("beforepaste", function (a) {
                        a.data && !a.data.$.ctrlKey && h()
                    }, null, null, 0)
                }
                c.on("beforecut",
                    function () {
                        !w && o(a)
                    });
                var e;
                c.attachListener(CKEDITOR.env.ie ? c : a.document.getDocumentElement(), "mouseup", function () {
                    e = setTimeout(function () {
                        z()
                    }, 0)
                });
                a.on("destroy", function () {
                    clearTimeout(e)
                });
                c.on("keyup", z)
            }

            function c(b) {
                return{type: b, canUndo: b == "cut", startDisabled: true, exec: function () {
                    this.type == "cut" && o();
                    var b;
                    var c = this.type;
                    if (CKEDITOR.env.ie)b = n(c); else try {
                        b = a.document.$.execCommand(c, false, null)
                    } catch (e) {
                        b = false
                    }
                    b || alert(a.lang.clipboard[this.type + "Error"]);
                    return b
                }}
            }

            function f() {
                return{canUndo: false,
                    async: true, exec: function (a, b) {
                        var d = function (b, d) {
                            b && l(b.type, b.dataValue, !!d);
                            a.fire("afterCommandExec", {name: "paste", command: c, returnValue: !!b})
                        }, c = this;
                        typeof b == "string" ? d({type: "auto", dataValue: b}, 1) : a.getClipboardData(d)
                    }}
            }

            function g() {
                v = 1;
                setTimeout(function () {
                    v = 0
                }, 100)
            }

            function h() {
                w = 1;
                setTimeout(function () {
                    w = 0
                }, 10)
            }

            function n(b) {
                var c = a.document, e = c.getBody(), f = false, g = function () {
                    f = true
                };
                e.on(b, g);
                (CKEDITOR.env.version > 7 ? c.$ : c.$.selection.createRange()).execCommand(b);
                e.removeListener(b,
                    g);
                return f
            }

            function l(b, c, e) {
                b = {type: b};
                if (e && !a.fire("beforePaste", b) || !c)return false;
                b.dataValue = c;
                return a.fire("paste", b)
            }

            function o() {
                if (CKEDITOR.env.ie && !CKEDITOR.env.quirks) {
                    var b = a.getSelection(), c, e, f;
                    if (b.getType() == CKEDITOR.SELECTION_ELEMENT && (c = b.getSelectedElement())) {
                        e = b.getRanges()[0];
                        f = a.document.createText("");
                        f.insertBefore(c);
                        e.setStartBefore(f);
                        e.setEndAfter(c);
                        b.selectRanges([e]);
                        setTimeout(function () {
                            if (c.getParent()) {
                                f.remove();
                                b.selectElement(c)
                            }
                        }, 0)
                    }
                }
            }

            function q(b, c) {
                var e =
                    a.document, f = a.editable(), g = function (a) {
                    a.cancel()
                }, h = CKEDITOR.env.gecko && CKEDITOR.env.version <= 10902;
                if (!e.getById("cke_pastebin")) {
                    var i = a.getSelection(), j = i.createBookmarks(), o = new CKEDITOR.dom.element(f.is("body") && !CKEDITOR.env.ie && !CKEDITOR.env.opera ? "body" : "div", e);
                    o.setAttribute("id", "cke_pastebin");
                    CKEDITOR.env.opera && o.appendBogus();
                    var k = 0, e = e.getWindow();
                    if (h) {
                        o.insertAfter(j[0].startNode);
                        o.setStyle("display", "inline")
                    } else {
                        if (CKEDITOR.env.webkit) {
                            f.append(o);
                            o.addClass("cke_editable");
                            k = (f.is("body") ? f : CKEDITOR.dom.element.get(o.$.offsetParent)).getDocumentPosition().y
                        } else f.getAscendant(CKEDITOR.env.ie || CKEDITOR.env.opera ? "body" : "html", 1).append(o);
                        o.setStyles({position: "absolute", top: e.getScrollPosition().y - k + 10 + "px", width: "1px", height: Math.max(1, e.getViewPaneSize().height - 20) + "px", overflow: "hidden", margin: 0, padding: 0})
                    }
                    if (h = o.getParent().isReadOnly()) {
                        o.setOpacity(0);
                        o.setAttribute("contenteditable", true)
                    } else o.setStyle(a.config.contentsLangDirection == "ltr" ? "left" : "right",
                        "-1000px");
                    a.on("selectionChange", g, null, null, 0);
                    h && o.focus();
                    h = new CKEDITOR.dom.range(o);
                    h.selectNodeContents(o);
                    var p = h.select();
                    if (CKEDITOR.env.ie)var l = f.once("blur", function () {
                        a.lockSelection(p)
                    });
                    var q = CKEDITOR.document.getWindow().getScrollPosition().y;
                    setTimeout(function () {
                        if (CKEDITOR.env.webkit || CKEDITOR.env.opera)CKEDITOR.document[CKEDITOR.env.webkit ? "getBody" : "getDocumentElement"]().$.scrollTop = q;
                        l && l.removeListener();
                        CKEDITOR.env.ie && f.focus();
                        i.selectBookmarks(j);
                        o.remove();
                        var b;
                        if (CKEDITOR.env.webkit &&
                            (b = o.getFirst()) && b.is && b.hasClass("Apple-style-span"))o = b;
                        a.removeListener("selectionChange", g);
                        c(o.getHtml())
                    }, 0)
                }
            }

            function s() {
                if (CKEDITOR.env.ie) {
                    a.focus();
                    g();
                    var b = a.focusManager;
                    b.lock();
                    if (a.editable().fire(u) && !n("paste")) {
                        b.unlock();
                        return false
                    }
                    b.unlock()
                } else try {
                    if (a.editable().fire(u) && !a.document.$.execCommand("Paste", false, null))throw 0;
                } catch (c) {
                    return false
                }
                return true
            }

            function p(b) {
                if (a.mode == "wysiwyg")switch (b.data.keyCode) {
                    case CKEDITOR.CTRL + 86:
                    case CKEDITOR.SHIFT + 45:
                        b = a.editable();
                        g();
                        !CKEDITOR.env.ie && b.fire("beforepaste");
                        (CKEDITOR.env.opera || CKEDITOR.env.gecko && CKEDITOR.env.version < 10900) && b.fire("paste");
                        break;
                    case CKEDITOR.CTRL + 88:
                    case CKEDITOR.SHIFT + 46:
                        a.fire("saveSnapshot");
                        setTimeout(function () {
                            a.fire("saveSnapshot")
                        }, 0)
                }
            }

            function t(b) {
                var c = {type: "auto"}, e = a.fire("beforePaste", c);
                q(b, function (a) {
                    a = a.replace(/<span[^>]+data-cke-bookmark[^<]*?<\/span>/ig, "");
                    e && l(c.type, a, 0, 1)
                })
            }

            function z() {
                if (a.mode == "wysiwyg") {
                    var b = x("Paste");
                    a.getCommand("cut").setState(x("Cut"));
                    a.getCommand("copy").setState(x("Copy"));
                    a.getCommand("paste").setState(b);
                    a.fire("pasteState", b)
                }
            }

            function x(b) {
                var c;
                if (r && b in{Paste: 1, Cut: 1})return CKEDITOR.TRISTATE_DISABLED;
                if (b == "Paste") {
                    CKEDITOR.env.ie && (w = 1);
                    try {
                        c = a.document.$.queryCommandEnabled(b) || CKEDITOR.env.webkit
                    } catch (e) {
                    }
                    w = 0
                } else {
                    b = a.getSelection();
                    c = b.getRanges();
                    c = b.getType() != CKEDITOR.SELECTION_NONE && !(c.length == 1 && c[0].collapsed)
                }
                return c ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED
            }

            var w = 0, v = 0, r = 0, u = CKEDITOR.env.ie ? "beforepaste" :
                "paste";
            (function () {
                a.on("key", p);
                a.on("contentDom", b);
                a.on("selectionChange", function (a) {
                    r = a.data.selection.getRanges()[0].checkReadOnly();
                    z()
                });
                a.contextMenu && a.contextMenu.addListener(function (a, b) {
                    r = b.getRanges()[0].checkReadOnly();
                    return{cut: x("Cut"), copy: x("Copy"), paste: x("Paste")}
                })
            })();
            (function () {
                function b(c, e, f, g, h) {
                    var i = a.lang.clipboard[e];
                    a.addCommand(e, f);
                    a.ui.addButton && a.ui.addButton(c, {label: i, command: e, toolbar: "clipboard," + g});
                    a.addMenuItems && a.addMenuItem(e, {label: i, command: e,
                        group: "clipboard", order: h})
                }

                b("Cut", "cut", c("cut"), 10, 1);
                b("Copy", "copy", c("copy"), 20, 4);
                b("Paste", "paste", f(), 30, 8)
            })();
            a.getClipboardData = function (b, c) {
                function e(a) {
                    a.removeListener();
                    a.cancel();
                    c(a.data)
                }

                function f(a) {
                    a.removeListener();
                    a.cancel();
                    j = true;
                    c({type: i, dataValue: a.data})
                }

                function g() {
                    this.customTitle = b && b.title
                }

                var h = false, i = "auto", j = false;
                if (!c) {
                    c = b;
                    b = null
                }
                a.on("paste", e, null, null, 0);
                a.on("beforePaste", function (a) {
                    a.removeListener();
                    h = true;
                    i = a.data.type
                }, null, null, 1E3);
                if (s() === false) {
                    a.removeListener("paste",
                        e);
                    if (h && a.fire("pasteDialog", g)) {
                        a.on("pasteDialogCommit", f);
                        a.on("dialogHide", function (a) {
                            a.removeListener();
                            a.data.removeListener("pasteDialogCommit", f);
                            setTimeout(function () {
                                j || c(null)
                            }, 10)
                        })
                    } else c(null)
                }
            }
        }

        function c(a) {
            if (CKEDITOR.env.webkit) {
                if (!a.match(/^[^<]*$/g) && !a.match(/^(<div><br( ?\/)?><\/div>|<div>[^<]*<\/div>)*$/gi))return"html"
            } else if (CKEDITOR.env.ie) {
                if (!a.match(/^([^<]|<br( ?\/)?>)*$/gi) && !a.match(/^(<p>([^<]|<br( ?\/)?>)*<\/p>|(\r\n))*$/gi))return"html"
            } else if (CKEDITOR.env.gecko ||
                CKEDITOR.env.opera) {
                if (!a.match(/^([^<]|<br( ?\/)?>)*$/gi))return"html"
            } else return"html";
            return"htmlifiedtext"
        }

        function a(a, b) {
            function c(a) {
                return CKEDITOR.tools.repeat("</p><p>", ~~(a / 2)) + (a % 2 == 1 ? "<br>" : "")
            }

            b = b.replace(/\s+/g, " ").replace(/> +</g, "><").replace(/<br ?\/>/gi, "<br>");
            b = b.replace(/<\/?[A-Z]+>/g, function (a) {
                return a.toLowerCase()
            });
            if (b.match(/^[^<]$/))return b;
            if (CKEDITOR.env.webkit && b.indexOf("<div>") > -1) {
                b = b.replace(/^(<div>(<br>|)<\/div>)(?!$|(<div>(<br>|)<\/div>))/g, "<br>").replace(/^(<div>(<br>|)<\/div>){2}(?!$)/g,
                    "<div></div>");
                b.match(/<div>(<br>|)<\/div>/) && (b = "<p>" + b.replace(/(<div>(<br>|)<\/div>)+/g, function (a) {
                    return c(a.split("</div><div>").length + 1)
                }) + "</p>");
                b = b.replace(/<\/div><div>/g, "<br>");
                b = b.replace(/<\/?div>/g, "")
            }
            if ((CKEDITOR.env.gecko || CKEDITOR.env.opera) && a.enterMode != CKEDITOR.ENTER_BR) {
                CKEDITOR.env.gecko && (b = b.replace(/^<br><br>$/, "<br>"));
                b.indexOf("<br><br>") > -1 && (b = "<p>" + b.replace(/(<br>){2,}/g, function (a) {
                    return c(a.length / 4)
                }) + "</p>")
            }
            return g(a, b)
        }

        function f() {
            var a = new CKEDITOR.htmlParser.filter,
                b = {blockquote: 1, dl: 1, fieldset: 1, h1: 1, h2: 1, h3: 1, h4: 1, h5: 1, h6: 1, ol: 1, p: 1, table: 1, ul: 1}, c = CKEDITOR.tools.extend({br: 0}, CKEDITOR.dtd.$inline), f = {p: 1, br: 1, "cke:br": 1}, g = CKEDITOR.dtd, h = CKEDITOR.tools.extend({area: 1, basefont: 1, embed: 1, iframe: 1, map: 1, object: 1, param: 1}, CKEDITOR.dtd.$nonBodyContent, CKEDITOR.dtd.$cdata), n = function (a) {
                    delete a.name;
                    a.add(new CKEDITOR.htmlParser.text(" "))
                }, l = function (a) {
                    for (var b = a, d; (b = b.next) && b.name && b.name.match(/^h\d$/);) {
                        d = new CKEDITOR.htmlParser.element("cke:br");
                        d.isEmpty =
                            true;
                        for (a.add(d); d = b.children.shift();)a.add(d)
                    }
                };
            a.addRules({elements: {h1: l, h2: l, h3: l, h4: l, h5: l, h6: l, img: function (a) {
                var a = CKEDITOR.tools.trim(a.attributes.alt || ""), b = " ";
                a && !a.match(/(^http|\.(jpe?g|gif|png))/i) && (b = " [" + a + "] ");
                return new CKEDITOR.htmlParser.text(b)
            }, td: n, th: n, $: function (a) {
                var d = a.name, l;
                if (h[d])return false;
                delete a.attributes;
                if (d == "br")return a;
                if (b[d])a.name = "p"; else if (c[d])delete a.name; else if (g[d]) {
                    l = new CKEDITOR.htmlParser.element("cke:br");
                    l.isEmpty = true;
                    if (CKEDITOR.dtd.$empty[d])return l;
                    a.add(l, 0);
                    l = l.clone();
                    l.isEmpty = true;
                    a.add(l);
                    delete a.name
                }
                f[a.name] || delete a.name;
                return a
            }}});
            return a
        }

        function h(a, b, c) {
            var b = new CKEDITOR.htmlParser.fragment.fromHtml(b), f = new CKEDITOR.htmlParser.basicWriter;
            b.writeHtml(f, c);
            var b = f.getHtml(), b = b.replace(/\s*(<\/?[a-z:]+ ?\/?>)\s*/g, "$1").replace(/(<cke:br \/>){2,}/g, "<cke:br />").replace(/(<cke:br \/>)(<\/?p>|<br \/>)/g, "$2").replace(/(<\/?p>|<br \/>)(<cke:br \/>)/g, "$1").replace(/<(cke:)?br( \/)?>/g, "<br>").replace(/<p><\/p>/g, ""), h = 0, b =
                b.replace(/<\/?p>/g,function (a) {
                    if (a == "<p>") {
                        if (++h > 1)return"</p><p>"
                    } else if (--h > 0)return"</p><p>";
                    return a
                }).replace(/<p><\/p>/g, "");
            return g(a, b)
        }

        function g(a, b) {
            a.enterMode == CKEDITOR.ENTER_BR ? b = b.replace(/(<\/p><p>)+/g,function (a) {
                return CKEDITOR.tools.repeat("<br>", a.length / 7 * 2)
            }).replace(/<\/?p>/g, "") : a.enterMode == CKEDITOR.ENTER_DIV && (b = b.replace(/<(\/)?p>/g, "<$1div>"));
            return b
        }

        CKEDITOR.plugins.add("clipboard", {requires: "dialog", init: function (d) {
            var g;
            b(d);
            CKEDITOR.dialog.add("paste", CKEDITOR.getUrl(this.path +
                "dialogs/paste.js"));
            d.on("paste", function (a) {
                var b = a.data.dataValue, d = CKEDITOR.dtd.$block;
                if (b.indexOf("Apple-") > -1) {
                    b = b.replace(/<span class="Apple-converted-space">&nbsp;<\/span>/gi, " ");
                    a.data.type != "html" && (b = b.replace(/<span class="Apple-tab-span"[^>]*>([^<]*)<\/span>/gi, function (a, b) {
                        return b.replace(/\t/g, "&nbsp;&nbsp; &nbsp;")
                    }));
                    if (b.indexOf('<br class="Apple-interchange-newline">') > -1) {
                        a.data.startsWithEOL = 1;
                        a.data.preSniffing = "html";
                        b = b.replace(/<br class="Apple-interchange-newline">/,
                            "")
                    }
                    b = b.replace(/(<[^>]+) class="Apple-[^"]*"/gi, "$1")
                }
                if (b.match(/^<[^<]+cke_(editable|contents)/i)) {
                    var c, f, g = new CKEDITOR.dom.element("div");
                    for (g.setHtml(b); g.getChildCount() == 1 && (c = g.getFirst()) && c.type == CKEDITOR.NODE_ELEMENT && (c.hasClass("cke_editable") || c.hasClass("cke_contents"));)g = f = c;
                    f && (b = f.getHtml().replace(/<br>$/i, ""))
                }
                CKEDITOR.env.ie ? b = b.replace(/^&nbsp;(?: |\r\n)?<(\w+)/g, function (b, c) {
                    if (c.toLowerCase()in d) {
                        a.data.preSniffing = "html";
                        return"<" + c
                    }
                    return b
                }) : CKEDITOR.env.webkit ? b =
                    b.replace(/<\/(\w+)><div><br><\/div>$/, function (b, c) {
                        if (c in d) {
                            a.data.endsWithEOL = 1;
                            return"</" + c + ">"
                        }
                        return b
                    }) : CKEDITOR.env.gecko && (b = b.replace(/(\s)<br>$/, "$1"));
                a.data.dataValue = b
            }, null, null, 3);
            d.on("paste", function (b) {
                var b = b.data, j = b.type, k = b.dataValue, m, n = d.config.clipboard_defaultContentType || "html";
                m = j == "html" || b.preSniffing == "html" ? "html" : c(k);
                m == "htmlifiedtext" ? k = a(d.config, k) : j == "text" && m == "html" && (k = h(d.config, k, g || (g = f(d))));
                b.startsWithEOL && (k = '<br data-cke-eol="1">' + k);
                b.endsWithEOL &&
                (k = k + '<br data-cke-eol="1">');
                j == "auto" && (j = m == "html" || n == "html" ? "html" : "text");
                b.type = j;
                b.dataValue = k;
                delete b.preSniffing;
                delete b.startsWithEOL;
                delete b.endsWithEOL
            }, null, null, 6);
            d.on("paste", function (a) {
                a = a.data;
                d.insertHtml(a.dataValue, a.type);
                setTimeout(function () {
                    d.fire("afterPaste")
                }, 0)
            }, null, null, 1E3);
            d.on("pasteDialog", function (a) {
                setTimeout(function () {
                    d.openDialog("paste", a.data)
                }, 0)
            })
        }})
    }(), function () {
        var b = '<a id="{id}" class="cke_button cke_button__{name} cke_button_{state} {cls}"' +
            (CKEDITOR.env.gecko && CKEDITOR.env.version >= 10900 && !CKEDITOR.env.hc ? "" : '" href="javascript:void(\'{titleJs}\')"') + ' title="{title}" tabindex="-1" hidefocus="true" role="button" aria-labelledby="{id}_label" aria-haspopup="{hasArrow}"';
        if (CKEDITOR.env.opera || CKEDITOR.env.gecko && CKEDITOR.env.mac)b = b + ' onkeypress="return false;"';
        CKEDITOR.env.gecko && (b = b + ' onblur="this.style.cssText = this.style.cssText;"');
        var b = b + (' onkeydown="return CKEDITOR.tools.callFunction({keydownFn},event);" onfocus="return CKEDITOR.tools.callFunction({focusFn},event);"  onmousedown="return CKEDITOR.tools.callFunction({mousedownFn},event);" ' +
            (CKEDITOR.env.ie ? 'onclick="return false;" onmouseup' : "onclick") + '="CKEDITOR.tools.callFunction({clickFn},this);return false;"><span class="cke_button_icon cke_button__{iconName}_icon" style="{style}"'), b = b + '>&nbsp;</span><span id="{id}_label" class="cke_button_label cke_button__{name}_label">{label}</span>{arrowHtml}</a>', c = CKEDITOR.addTemplate("buttonArrow", '<span class="cke_button_arrow">' + (CKEDITOR.env.hc ? "&#9660;" : "") + "</span>"), a = CKEDITOR.addTemplate("button", b);
        CKEDITOR.plugins.add("button",
            {beforeInit: function (a) {
                a.ui.addHandler(CKEDITOR.UI_BUTTON, CKEDITOR.ui.button.handler)
            }});
        CKEDITOR.UI_BUTTON = "button";
        CKEDITOR.ui.button = function (a) {
            CKEDITOR.tools.extend(this, a, {title: a.label, click: a.click || function (b) {
                b.execCommand(a.command)
            }});
            this._ = {}
        };
        CKEDITOR.ui.button.handler = {create: function (a) {
            return new CKEDITOR.ui.button(a)
        }};
        CKEDITOR.ui.button.prototype = {render: function (b, h) {
            var g = CKEDITOR.env, d = this._.id = CKEDITOR.tools.getNextId(), i = "", e = this.command, j;
            this._.editor = b;
            var k = {id: d, button: this,
                editor: b, focus: function () {
                    CKEDITOR.document.getById(d).focus()
                }, execute: function () {
                    this.button.click(b)
                }, attach: function (a) {
                    this.button.attach(a)
                }}, m = CKEDITOR.tools.addFunction(function (a) {
                if (k.onkey) {
                    a = new CKEDITOR.dom.event(a);
                    return k.onkey(k, a.getKeystroke()) !== false
                }
            }), n = CKEDITOR.tools.addFunction(function (a) {
                var b;
                k.onfocus && (b = k.onfocus(k, new CKEDITOR.dom.event(a)) !== false);
                CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 && a.preventBubble();
                return b
            }), l = 0, o = CKEDITOR.tools.addFunction(function () {
                if (CKEDITOR.env.opera) {
                    var a =
                        b.editable();
                    if (a.isInline() && a.hasFocus) {
                        b.lockSelection();
                        l = 1
                    }
                }
            });
            k.clickFn = j = CKEDITOR.tools.addFunction(function () {
                if (l) {
                    b.unlockSelection(1);
                    l = 0
                }
                k.execute()
            });
            if (this.modes) {
                var q = {}, s = function () {
                    var a = b.mode;
                    if (a) {
                        a = this.modes[a] ? q[a] != void 0 ? q[a] : CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED;
                        this.setState(b.readOnly && !this.readOnly ? CKEDITOR.TRISTATE_DISABLED : a)
                    }
                };
                b.on("beforeModeUnload", function () {
                    if (b.mode && this._.state != CKEDITOR.TRISTATE_DISABLED)q[b.mode] = this._.state
                }, this);
                b.on("mode",
                    s, this);
                !this.readOnly && b.on("readOnly", s, this)
            } else if (e)if (e = b.getCommand(e)) {
                e.on("state", function () {
                    this.setState(e.state)
                }, this);
                i = i + (e.state == CKEDITOR.TRISTATE_ON ? "on" : e.state == CKEDITOR.TRISTATE_DISABLED ? "disabled" : "off")
            }
            if (this.directional)b.on("contentDirChanged", function (a) {
                var d = CKEDITOR.document.getById(this._.id), c = d.getFirst(), a = a.data;
                a != b.lang.dir ? d.addClass("cke_" + a) : d.removeClass("cke_ltr").removeClass("cke_rtl");
                c.setAttribute("style", CKEDITOR.skin.getIconStyle(p, a == "rtl", this.icon,
                    this.iconOffset))
            }, this);
            e || (i = i + "off");
            var p = s = this.name || this.command;
            if (this.icon && !/\./.test(this.icon)) {
                p = this.icon;
                this.icon = null
            }
            g = {id: d, name: s, iconName: p, label: this.label, cls: this.className || "", state: i, title: this.title, titleJs: g.gecko && g.version >= 10900 && !g.hc ? "" : (this.title || "").replace("'", ""), hasArrow: this.hasArrow ? "true" : "false", keydownFn: m, mousedownFn: o, focusFn: n, clickFn: j, style: CKEDITOR.skin.getIconStyle(p, b.lang.dir == "rtl", this.icon, this.iconOffset), arrowHtml: this.hasArrow ? c.output() :
                ""};
            a.output(g, h);
            if (this.onRender)this.onRender();
            return k
        }, setState: function (a) {
            if (this._.state == a)return false;
            this._.state = a;
            var b = CKEDITOR.document.getById(this._.id);
            if (b) {
                b.setState(a, "cke_button");
                a == CKEDITOR.TRISTATE_DISABLED ? b.setAttribute("aria-disabled", true) : b.removeAttribute("aria-disabled");
                a == CKEDITOR.TRISTATE_ON ? b.setAttribute("aria-pressed", true) : b.removeAttribute("aria-pressed");
                return true
            }
            return false
        }, toFeature: function (a) {
            if (this._.feature)return this._.feature;
            var b = this;
            !this.allowedContent &&
                (!this.requiredContent && this.command) && (b = a.getCommand(this.command) || b);
            return this._.feature = b
        }};
        CKEDITOR.ui.prototype.addButton = function (a, b) {
            this.add(a, CKEDITOR.UI_BUTTON, b)
        }
    }(), CKEDITOR.plugins.add("panelbutton", {requires: "button", onLoad: function () {
        function b(b) {
            var a = this._;
            if (a.state != CKEDITOR.TRISTATE_DISABLED) {
                this.createPanel(b);
                a.on ? a.panel.hide() : a.panel.showBlock(this._.id, this.document.getById(this._.id), 4)
            }
        }

        CKEDITOR.ui.panelButton = CKEDITOR.tools.createClass({base: CKEDITOR.ui.button,
            $: function (c) {
                var a = c.panel || {};
                delete c.panel;
                this.base(c);
                this.document = a.parent && a.parent.getDocument() || CKEDITOR.document;
                a.block = {attributes: a.attributes};
                this.hasArrow = a.toolbarRelated = true;
                this.click = b;
                this._ = {panelDefinition: a}
            }, statics: {handler: {create: function (b) {
                return new CKEDITOR.ui.panelButton(b)
            }}}, proto: {createPanel: function (b) {
                var a = this._;
                if (!a.panel) {
                    var f = this._.panelDefinition, h = this._.panelDefinition.block, g = f.parent || CKEDITOR.document.getBody(), d = this._.panel = new CKEDITOR.ui.floatPanel(b,
                        g, f), f = d.addBlock(a.id, h), i = this;
                    d.onShow = function () {
                        i.className && this.element.addClass(i.className + "_panel");
                        i.setState(CKEDITOR.TRISTATE_ON);
                        a.on = 1;
                        i.editorFocus && b.focus();
                        if (i.onOpen)i.onOpen()
                    };
                    d.onHide = function (d) {
                        i.className && this.element.getFirst().removeClass(i.className + "_panel");
                        i.setState(i.modes && i.modes[b.mode] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED);
                        a.on = 0;
                        if (!d && i.onClose)i.onClose()
                    };
                    d.onEscape = function () {
                        d.hide(1);
                        i.document.getById(a.id).focus()
                    };
                    if (this.onBlock)this.onBlock(d,
                        f);
                    f.onHide = function () {
                        a.on = 0;
                        i.setState(CKEDITOR.TRISTATE_OFF)
                    }
                }
            }}})
    }, beforeInit: function (b) {
        b.ui.addHandler(CKEDITOR.UI_PANELBUTTON, CKEDITOR.ui.panelButton.handler)
    }}), CKEDITOR.UI_PANELBUTTON = "panelbutton", function () {
        CKEDITOR.plugins.add("panel", {beforeInit: function (a) {
            a.ui.addHandler(CKEDITOR.UI_PANEL, CKEDITOR.ui.panel.handler)
        }});
        CKEDITOR.UI_PANEL = "panel";
        CKEDITOR.ui.panel = function (a, b) {
            b && CKEDITOR.tools.extend(this, b);
            CKEDITOR.tools.extend(this, {className: "", css: []});
            this.id = CKEDITOR.tools.getNextId();
            this.document = a;
            this.isFramed = this.forceIFrame || this.css.length;
            this._ = {blocks: {}}
        };
        CKEDITOR.ui.panel.handler = {create: function (a) {
            return new CKEDITOR.ui.panel(a)
        }};
        var b = CKEDITOR.addTemplate("panel", '<div lang="{langCode}" id="{id}" dir={dir} class="cke cke_reset_all {editorId} cke_panel cke_panel {cls} cke_{dir}" style="z-index:{z-index}" role="presentation">{frame}</div>'), c = CKEDITOR.addTemplate("panel-frame", '<iframe id="{id}" class="cke_panel_frame" role="application" frameborder="0" src="{src}"></iframe>'),
            a = CKEDITOR.addTemplate("panel-frame-inner", '<!DOCTYPE html><html class="cke_panel_container {env}" dir="{dir}" lang="{langCode}"><head>{css}</head><body class="cke_{dir}" style="margin:0;padding:0" onload="{onload}"></body></html>');
        CKEDITOR.ui.panel.prototype = {render: function (f, h) {
            this.getHolderElement = function () {
                var b = this._.holder;
                if (!b) {
                    if (this.isFramed) {
                        var b = this.document.getById(this.id + "_frame"), d = b.getParent(), b = b.getFrameDocument();
                        CKEDITOR.env.iOS && d.setStyles({overflow: "scroll", "-webkit-overflow-scrolling": "touch"});
                        d = CKEDITOR.tools.addFunction(CKEDITOR.tools.bind(function () {
                            this.isLoaded = true;
                            if (this.onLoad)this.onLoad()
                        }, this));
                        b.write(a.output(CKEDITOR.tools.extend({css: CKEDITOR.tools.buildStyleHtml(this.css), onload: "window.parent.CKEDITOR.tools.callFunction(" + d + ");"}, g)));
                        b.getWindow().$.CKEDITOR = CKEDITOR;
                        b.on("key" + (CKEDITOR.env.opera ? "press" : "down"), function (a) {
                            var b = a.data.getKeystroke(), d = this.document.getById(this.id).getAttribute("dir");
                            this._.onKeyDown && this._.onKeyDown(b) === false ? a.data.preventDefault() :
                                (b == 27 || b == (d == "rtl" ? 39 : 37)) && this.onEscape && this.onEscape(b) === false && a.data.preventDefault()
                        }, this);
                        b = b.getBody();
                        b.unselectable();
                        CKEDITOR.env.air && CKEDITOR.tools.callFunction(d)
                    } else b = this.document.getById(this.id);
                    this._.holder = b
                }
                return b
            };
            var g = {editorId: f.id, id: this.id, langCode: f.langCode, dir: f.lang.dir, cls: this.className, frame: "", env: CKEDITOR.env.cssClass, "z-index": f.config.baseFloatZIndex + 1};
            if (this.isFramed)g.frame = c.output({id: this.id + "_frame", src: "javascript:void(document.open()," + (CKEDITOR.env.isCustomDomain() ?
                "document.domain='" + document.domain + "'," : "") + 'document.close())">'});
            var d = b.output(g);
            h && h.push(d);
            return d
        }, addBlock: function (a, b) {
            b = this._.blocks[a] = b instanceof CKEDITOR.ui.panel.block ? b : new CKEDITOR.ui.panel.block(this.getHolderElement(), b);
            this._.currentBlock || this.showBlock(a);
            return b
        }, getBlock: function (a) {
            return this._.blocks[a]
        }, showBlock: function (a) {
            var a = this._.blocks[a], b = this._.currentBlock, c = !this.forceIFrame || CKEDITOR.env.ie ? this._.holder : this.document.getById(this.id + "_frame");
            if (b) {
                c.removeAttributes(b.attributes);
                b.hide()
            }
            this._.currentBlock = a;
            c.setAttributes(a.attributes);
            CKEDITOR.fire("ariaWidget", c);
            a._.focusIndex = -1;
            this._.onKeyDown = a.onKeyDown && CKEDITOR.tools.bind(a.onKeyDown, a);
            a.show();
            return a
        }, destroy: function () {
            this.element && this.element.remove()
        }};
        CKEDITOR.ui.panel.block = CKEDITOR.tools.createClass({$: function (a, b) {
            this.element = a.append(a.getDocument().createElement("div", {attributes: {tabIndex: -1, "class": "cke_panel_block", role: "presentation"}, styles: {display: "none"}}));
            b && CKEDITOR.tools.extend(this,
                b);
            if (!this.attributes.title)this.attributes.title = this.attributes["aria-label"];
            this.keys = {};
            this._.focusIndex = -1;
            this.element.disableContextMenu()
        }, _: {markItem: function (a) {
            if (a != -1) {
                a = this.element.getElementsByTag("a").getItem(this._.focusIndex = a);
                (CKEDITOR.env.webkit || CKEDITOR.env.opera) && a.getDocument().getWindow().focus();
                a.focus();
                this.onMark && this.onMark(a)
            }
        }}, proto: {show: function () {
            this.element.setStyle("display", "")
        }, hide: function () {
            (!this.onHide || this.onHide.call(this) !== true) && this.element.setStyle("display",
                "none")
        }, onKeyDown: function (a) {
            var b = this.keys[a];
            switch (b) {
                case "next":
                    for (var a = this._.focusIndex, b = this.element.getElementsByTag("a"), c; c = b.getItem(++a);)if (c.getAttribute("_cke_focus") && c.$.offsetWidth) {
                        this._.focusIndex = a;
                        c.focus();
                        break
                    }
                    return false;
                case "prev":
                    a = this._.focusIndex;
                    for (b = this.element.getElementsByTag("a"); a > 0 && (c = b.getItem(--a));)if (c.getAttribute("_cke_focus") && c.$.offsetWidth) {
                        this._.focusIndex = a;
                        c.focus();
                        break
                    }
                    return false;
                case "click":
                case "mouseup":
                    a = this._.focusIndex;
                    (c = a >= 0 && this.element.getElementsByTag("a").getItem(a)) && (c.$[b] ? c.$[b]() : c.$["on" + b]());
                    return false
            }
            return true
        }}})
    }(), CKEDITOR.plugins.add("floatpanel", {requires: "panel"}), function () {
        function b(a, b, h, g, d) {
            var d = CKEDITOR.tools.genKey(b.getUniqueId(), h.getUniqueId(), a.lang.dir, a.uiColor || "", g.css || "", d || ""), i = c[d];
            if (!i) {
                i = c[d] = new CKEDITOR.ui.panel(b, g);
                i.element = h.append(CKEDITOR.dom.element.createFromHtml(i.render(a), b));
                i.element.setStyles({display: "none", position: "absolute"})
            }
            return i
        }

        var c =
        {};
        CKEDITOR.ui.floatPanel = CKEDITOR.tools.createClass({$: function (a, c, h, g) {
            function d() {
                k.hide()
            }

            h.forceIFrame = 1;
            h.toolbarRelated && a.elementMode == CKEDITOR.ELEMENT_MODE_INLINE && (c = CKEDITOR.document.getById("cke_" + a.name));
            var i = c.getDocument(), g = b(a, i, c, h, g || 0), e = g.element, j = e.getFirst(), k = this;
            e.disableContextMenu();
            e.setAttribute("role", "application");
            this.element = e;
            this._ = {editor: a, panel: g, parentElement: c, definition: h, document: i, iframe: j, children: [], dir: a.lang.dir};
            a.on("mode", d);
            a.on("resize", d);
            i.getWindow().on("resize", d)
        }, proto: {addBlock: function (a, b) {
            return this._.panel.addBlock(a, b)
        }, addListBlock: function (a, b) {
            return this._.panel.addListBlock(a, b)
        }, getBlock: function (a) {
            return this._.panel.getBlock(a)
        }, showBlock: function (a, b, c, g, d) {
            var i = this._.panel, e = i.showBlock(a);
            this.allowBlur(false);
            a = this._.editor.editable();
            this._.returnFocus = a.hasFocus ? a : new CKEDITOR.dom.element(CKEDITOR.document.$.activeElement);
            var j = this.element, a = this._.iframe, a = CKEDITOR.env.ie ? a : new CKEDITOR.dom.window(a.$.contentWindow),
                k = j.getDocument(), m = this._.parentElement.getPositionedAncestor(), n = b.getDocumentPosition(k), k = m ? m.getDocumentPosition(k) : {x: 0, y: 0}, l = this._.dir == "rtl", o = n.x + (g || 0) - k.x, q = n.y + (d || 0) - k.y;
            if (l && (c == 1 || c == 4))o = o + b.$.offsetWidth; else if (!l && (c == 2 || c == 3))o = o + (b.$.offsetWidth - 1);
            if (c == 3 || c == 4)q = q + (b.$.offsetHeight - 1);
            this._.panel._.offsetParentId = b.getId();
            j.setStyles({top: q + "px", left: 0, display: ""});
            j.setOpacity(0);
            j.getFirst().removeStyle("width");
            this._.editor.focusManager.add(a);
            if (!this._.blurSet) {
                CKEDITOR.event.useCapture =
                    true;
                a.on("blur", function (a) {
                    if (this.allowBlur() && a.data.getPhase() == CKEDITOR.EVENT_PHASE_AT_TARGET && this.visible && !this._.activeChild) {
                        delete this._.returnFocus;
                        this.hide()
                    }
                }, this);
                a.on("focus", function () {
                    this._.focused = true;
                    this.hideChild();
                    this.allowBlur(true)
                }, this);
                CKEDITOR.event.useCapture = false;
                this._.blurSet = 1
            }
            i.onEscape = CKEDITOR.tools.bind(function (a) {
                if (this.onEscape && this.onEscape(a) === false)return false
            }, this);
            CKEDITOR.tools.setTimeout(function () {
                var a = CKEDITOR.tools.bind(function () {
                    j.removeStyle("width");
                    if (e.autoSize) {
                        var a = e.element.getDocument(), a = (CKEDITOR.env.webkit ? e.element : a.getBody()).$.scrollWidth;
                        CKEDITOR.env.ie && (CKEDITOR.env.quirks && a > 0) && (a = a + ((j.$.offsetWidth || 0) - (j.$.clientWidth || 0) + 3));
                        j.setStyle("width", a + 10 + "px");
                        a = e.element.$.scrollHeight;
                        CKEDITOR.env.ie && (CKEDITOR.env.quirks && a > 0) && (a = a + ((j.$.offsetHeight || 0) - (j.$.clientHeight || 0) + 3));
                        j.setStyle("height", a + "px");
                        i._.currentBlock.element.setStyle("display", "none").removeStyle("display")
                    } else j.removeStyle("height");
                    l && (o = o - j.$.offsetWidth);
                    j.setStyle("left", o + "px");
                    var b = i.element.getWindow(), a = j.$.getBoundingClientRect(), b = b.getViewPaneSize(), d = a.width || a.right - a.left, c = a.height || a.bottom - a.top, g = l ? a.right : b.width - a.left, f = l ? b.width - a.right : a.left;
                    l ? g < d && (o = f > d ? o + d : b.width > d ? o - a.left : o - a.right + b.width) : g < d && (o = f > d ? o - d : b.width > d ? o - a.right + b.width : o - a.left);
                    d = a.top;
                    b.height - a.top < c && (q = d > c ? q - c : b.height > c ? q - a.bottom + b.height : q - a.top);
                    if (CKEDITOR.env.ie) {
                        b = a = new CKEDITOR.dom.element(j.$.offsetParent);
                        b.getName() == "html" && (b = b.getDocument().getBody());
                        b.getComputedStyle("direction") == "rtl" && (o = CKEDITOR.env.ie8Compat ? o - j.getDocument().getDocumentElement().$.scrollLeft * 2 : o - (a.$.scrollWidth - a.$.clientWidth))
                    }
                    var a = j.getFirst(), h;
                    (h = a.getCustomData("activePanel")) && h.onHide && h.onHide.call(this, 1);
                    a.setCustomData("activePanel", this);
                    j.setStyles({top: q + "px", left: o + "px"});
                    j.setOpacity(1)
                }, this);
                i.isLoaded ? a() : i.onLoad = a;
                CKEDITOR.tools.setTimeout(function () {
                    this.focus();
                    this.allowBlur(true);
                    this._.editor.fire("panelShow", this)
                }, 0, this)
            }, CKEDITOR.env.air ?
                200 : 0, this);
            this.visible = 1;
            this.onShow && this.onShow.call(this)
        }, focus: function () {
            if (CKEDITOR.env.webkit) {
                var a = CKEDITOR.document.getActive();
                !a.equals(this._.iframe) && a.$.blur()
            }
            (this._.lastFocused || this._.iframe.getFrameDocument().getWindow()).focus()
        }, blur: function () {
            var a = this._.iframe.getFrameDocument().getActive();
            a.is("a") && (this._.lastFocused = a)
        }, hide: function (a) {
            if (this.visible && (!this.onHide || this.onHide.call(this) !== true)) {
                this.hideChild();
                CKEDITOR.env.gecko && this._.iframe.getFrameDocument().$.activeElement.blur();
                this.element.setStyle("display", "none");
                this.visible = 0;
                this.element.getFirst().removeCustomData("activePanel");
                if (a = a && this._.returnFocus) {
                    CKEDITOR.env.webkit && a.type && a.getWindow().$.focus();
                    a.focus()
                }
                delete this._.lastFocused;
                this._.editor.fire("panelHide", this)
            }
        }, allowBlur: function (a) {
            var b = this._.panel;
            if (a != void 0)b.allowBlur = a;
            return b.allowBlur
        }, showAsChild: function (a, b, c, g, d, i) {
            if (!(this._.activeChild == a && a._.panel._.offsetParentId == c.getId())) {
                this.hideChild();
                a.onHide = CKEDITOR.tools.bind(function () {
                    CKEDITOR.tools.setTimeout(function () {
                        this._.focused ||
                        this.hide()
                    }, 0, this)
                }, this);
                this._.activeChild = a;
                this._.focused = false;
                a.showBlock(b, c, g, d, i);
                this.blur();
                (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat) && setTimeout(function () {
                    a.element.getChild(0).$.style.cssText += ""
                }, 100)
            }
        }, hideChild: function (a) {
            var b = this._.activeChild;
            if (b) {
                delete b.onHide;
                delete this._.activeChild;
                b.hide();
                a && this.focus()
            }
        }}});
        CKEDITOR.on("instanceDestroyed", function () {
            var a = CKEDITOR.tools.isEmpty(CKEDITOR.instances), b;
            for (b in c) {
                var h = c[b];
                a ? h.destroy() : h.element.hide()
            }
            a &&
            (c = {})
        })
    }(), CKEDITOR.plugins.add("colorbutton", {requires: "panelbutton,floatpanel", init: function (b) {
        function c(d, c, e, f) {
            var k = new CKEDITOR.style(h["colorButton_" + c + "Style"]), m = CKEDITOR.tools.getNextId() + "_colorBox";
            b.ui.add(d, CKEDITOR.UI_PANELBUTTON, {label: e, title: e, modes: {wysiwyg: 1}, editorFocus: 1, toolbar: "colors," + f, allowedContent: k, requiredContent: k, panel: {css: CKEDITOR.skin.getPath("editor"), attributes: {role: "listbox", "aria-label": g.panelTitle}}, onBlock: function (d, e) {
                e.autoSize = true;
                e.element.addClass("cke_colorblock");
                e.element.setHtml(a(d, c, m));
                e.element.getDocument().getBody().setStyle("overflow", "hidden");
                CKEDITOR.ui.fire("ready", this);
                var g = e.keys, f = b.lang.dir == "rtl";
                g[f ? 37 : 39] = "next";
                g[40] = "next";
                g[9] = "next";
                g[f ? 39 : 37] = "prev";
                g[38] = "prev";
                g[CKEDITOR.SHIFT + 9] = "prev";
                g[32] = "click"
            }, onOpen: function () {
                var a = b.getSelection(), a = a && a.getStartElement(), a = b.elementPath(a), d, a = a.block || a.blockLimit || b.document.getBody();
                do d = a && a.getComputedStyle(c == "back" ? "background-color" : "color") || "transparent"; while (c == "back" &&
                    d == "transparent" && a && (a = a.getParent()));
                if (!d || d == "transparent")d = "#ffffff";
                this._.panel._.iframe.getFrameDocument().getById(m).setStyle("background-color", d);
                return d
            }})
        }

        function a(a, c, e) {
            var j = [], k = h.colorButton_colors.split(","), m = CKEDITOR.tools.addFunction(function (c, e) {
                if (c == "?") {
                    var g = arguments.callee, i = function (a) {
                        this.removeListener("ok", i);
                        this.removeListener("cancel", i);
                        a.name == "ok" && g(this.getContentElement("picker", "selectedColor").getValue(), e)
                    };
                    b.openDialog("colordialog", function () {
                        this.on("ok",
                            i);
                        this.on("cancel", i)
                    })
                } else {
                    b.focus();
                    a.hide();
                    b.fire("saveSnapshot");
                    b.removeStyle(new CKEDITOR.style(h["colorButton_" + e + "Style"], {color: "inherit"}));
                    if (c) {
                        var j = h["colorButton_" + e + "Style"];
                        j.childRule = e == "back" ? function (a) {
                            return f(a)
                        } : function (a) {
                            return!(a.is("a") || a.getElementsByTag("a").count()) || f(a)
                        };
                        b.applyStyle(new CKEDITOR.style(j, {color: c}))
                    }
                    b.fire("saveSnapshot")
                }
            });
            j.push('<a class="cke_colorauto" _cke_focus=1 hidefocus=true title="', g.auto, '" onclick="CKEDITOR.tools.callFunction(',
                m, ",null,'", c, "');return false;\" href=\"javascript:void('", g.auto, '\')" role="option"><table role="presentation" cellspacing=0 cellpadding=0 width="100%"><tr><td><span class="cke_colorbox" id="', e, '"></span></td><td colspan=7 align=center>', g.auto, '</td></tr></table></a><table role="presentation" cellspacing=0 cellpadding=0 width="100%">');
            for (e = 0; e < k.length; e++) {
                e % 8 === 0 && j.push("</tr><tr>");
                var n = k[e].split("/"), l = n[0], o = n[1] || l;
                n[1] || (l = "#" + l.replace(/^(.)(.)(.)$/, "$1$1$2$2$3$3"));
                n = b.lang.colorbutton.colors[o] ||
                    o;
                j.push('<td><a class="cke_colorbox" _cke_focus=1 hidefocus=true title="', n, '" onclick="CKEDITOR.tools.callFunction(', m, ",'", l, "','", c, "'); return false;\" href=\"javascript:void('", n, '\')" role="option"><span class="cke_colorbox" style="background-color:#', o, '"></span></a></td>')
            }
            (b.plugins.colordialog && h.colorButton_enableMore === void 0 || h.colorButton_enableMore) && j.push('</tr><tr><td colspan=8 align=center><a class="cke_colormore" _cke_focus=1 hidefocus=true title="', g.more, '" onclick="CKEDITOR.tools.callFunction(',
                m, ",'?','", c, "');return false;\" href=\"javascript:void('", g.more, "')\"", ' role="option">', g.more, "</a></td>");
            j.push("</tr></table>");
            return j.join("")
        }

        function f(a) {
            return a.getAttribute("contentEditable") == "false" || a.getAttribute("data-nostyle")
        }

        var h = b.config, g = b.lang.colorbutton;
        if (!CKEDITOR.env.hc) {
            c("TextColor", "fore", g.textColorTitle, 10);
            c("BGColor", "back", g.bgColorTitle, 20)
        }
    }}), CKEDITOR.config.colorButton_colors = "000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF",
        CKEDITOR.config.colorButton_foreStyle = {element: "span", styles: {color: "#(color)"}, overrides: [
            {element: "font", attributes: {color: null}}
        ]}, CKEDITOR.config.colorButton_backStyle = {element: "span", styles: {"background-color": "#(color)"}}, CKEDITOR.plugins.colordialog = {requires: "dialog", init: function (b) {
        b.addCommand("colordialog", new CKEDITOR.dialogCommand("colordialog"));
        CKEDITOR.dialog.add("colordialog", this.path + "dialogs/colordialog.js");
        b.getColorFromDialog = function (c, a) {
            var f = function (b) {
                this.removeListener("ok",
                    f);
                this.removeListener("cancel", f);
                b = b.name == "ok" ? this.getValueOf("picker", "selectedColor") : null;
                c.call(a, b)
            }, h = function (a) {
                a.on("ok", f);
                a.on("cancel", f)
            };
            b.execCommand("colordialog");
            if (b._.storedDialogs && b._.storedDialogs.colordialog)h(b._.storedDialogs.colordialog); else CKEDITOR.on("dialogDefinition", function (a) {
                if (a.data.name == "colordialog") {
                    var b = a.data.definition;
                    a.removeListener();
                    b.onLoad = CKEDITOR.tools.override(b.onLoad, function (a) {
                        return function () {
                            h(this);
                            b.onLoad = a;
                            typeof a == "function" &&
                            a.call(this)
                        }
                    })
                }
            })
        }
    }}, CKEDITOR.plugins.add("colordialog", CKEDITOR.plugins.colordialog), CKEDITOR.plugins.add("menu", {requires: "floatpanel", beforeInit: function (b) {
        for (var c = b.config.menu_groups.split(","), a = b._.menuGroups = {}, f = b._.menuItems = {}, h = 0; h < c.length; h++)a[c[h]] = h + 1;
        b.addMenuGroup = function (b, d) {
            a[b] = d || 100
        };
        b.addMenuItem = function (b, d) {
            a[d.group] && (f[b] = new CKEDITOR.menuItem(this, b, d))
        };
        b.addMenuItems = function (a) {
            for (var b in a)this.addMenuItem(b, a[b])
        };
        b.getMenuItem = function (a) {
            return f[a]
        };
        b.removeMenuItem = function (a) {
            delete f[a]
        }
    }}), function () {
        function b(a) {
            a.sort(function (a, b) {
                return a.group < b.group ? -1 : a.group > b.group ? 1 : a.order < b.order ? -1 : a.order > b.order ? 1 : 0
            })
        }

        var c = '<span class="cke_menuitem"><a id="{id}" class="cke_menubutton cke_menubutton__{name} cke_menubutton_{state} {cls}" href="{href}" title="{title}" tabindex="-1"_cke_focus=1 hidefocus="true" role="menuitem" aria-haspopup="{hasPopup}" aria-disabled="{disabled}"';
        if (CKEDITOR.env.opera || CKEDITOR.env.gecko && CKEDITOR.env.mac)c =
            c + ' onkeypress="return false;"';
        CKEDITOR.env.gecko && (c = c + ' onblur="this.style.cssText = this.style.cssText;"');
        var c = c + (' onmouseover="CKEDITOR.tools.callFunction({hoverFn},{index});" onmouseout="CKEDITOR.tools.callFunction({moveOutFn},{index});" ' + (CKEDITOR.env.ie ? 'onclick="return false;" onmouseup' : "onclick") + '="CKEDITOR.tools.callFunction({clickFn},{index}); return false;">'), a = CKEDITOR.addTemplate("menuItem", c + '<span class="cke_menubutton_inner"><span class="cke_menubutton_icon"><span class="cke_button_icon cke_button__{iconName}_icon" style="{iconStyle}"></span></span><span class="cke_menubutton_label">{label}</span>{arrowHtml}</span></a></span>'),
            f = CKEDITOR.addTemplate("menuArrow", '<span class="cke_menuarrow"><span>{label}</span></span>');
        CKEDITOR.menu = CKEDITOR.tools.createClass({$: function (a, b) {
            b = this._.definition = b || {};
            this.id = CKEDITOR.tools.getNextId();
            this.editor = a;
            this.items = [];
            this._.listeners = [];
            this._.level = b.level || 1;
            var d = CKEDITOR.tools.extend({}, b.panel, {css: [CKEDITOR.skin.getPath("editor")], level: this._.level - 1, block: {}}), c = d.block.attributes = d.attributes || {};
            !c.role && (c.role = "menu");
            this._.panelDefinition = d
        }, _: {onShow: function () {
            var a =
                this.editor.getSelection(), b = a && a.getStartElement(), d = this.editor.elementPath(), c = this._.listeners;
            this.removeAll();
            for (var e = 0; e < c.length; e++) {
                var f = c[e](b, a, d);
                if (f)for (var k in f) {
                    var m = this.editor.getMenuItem(k);
                    if (m && (!m.command || this.editor.getCommand(m.command).state)) {
                        m.state = f[k];
                        this.add(m)
                    }
                }
            }
        }, onClick: function (a) {
            this.hide();
            if (a.onClick)a.onClick(); else a.command && this.editor.execCommand(a.command)
        }, onEscape: function (a) {
            var b = this.parent;
            b ? b._.panel.hideChild(1) : a == 27 && this.hide(1);
            return false
        },
            onHide: function () {
                this.onHide && this.onHide()
            }, showSubMenu: function (a) {
                var b = this._.subMenu, d = this.items[a];
                if (d = d.getItems && d.getItems()) {
                    if (b)b.removeAll(); else {
                        b = this._.subMenu = new CKEDITOR.menu(this.editor, CKEDITOR.tools.extend({}, this._.definition, {level: this._.level + 1}, true));
                        b.parent = this;
                        b._.onClick = CKEDITOR.tools.bind(this._.onClick, this)
                    }
                    for (var c in d) {
                        var e = this.editor.getMenuItem(c);
                        if (e) {
                            e.state = d[c];
                            b.add(e)
                        }
                    }
                    var f = this._.panel.getBlock(this.id).element.getDocument().getById(this.id +
                        ("" + a));
                    setTimeout(function () {
                        b.show(f, 2)
                    }, 0)
                } else this._.panel.hideChild(1)
            }}, proto: {add: function (a) {
            if (!a.order)a.order = this.items.length;
            this.items.push(a)
        }, removeAll: function () {
            this.items = []
        }, show: function (a, c, d, f) {
            if (!this.parent) {
                this._.onShow();
                if (!this.items.length)return
            }
            var c = c || (this.editor.lang.dir == "rtl" ? 2 : 1), e = this.items, j = this.editor, k = this._.panel, m = this._.element;
            if (!k) {
                k = this._.panel = new CKEDITOR.ui.floatPanel(this.editor, CKEDITOR.document.getBody(), this._.panelDefinition, this._.level);
                k.onEscape = CKEDITOR.tools.bind(function (a) {
                    if (this._.onEscape(a) === false)return false
                }, this);
                k.onShow = function () {
                    k._.panel.getHolderElement().getParent().addClass("cke cke_reset_all")
                };
                k.onHide = CKEDITOR.tools.bind(function () {
                    this._.onHide && this._.onHide()
                }, this);
                m = k.addBlock(this.id, this._.panelDefinition.block);
                m.autoSize = true;
                var n = m.keys;
                n[40] = "next";
                n[9] = "next";
                n[38] = "prev";
                n[CKEDITOR.SHIFT + 9] = "prev";
                n[j.lang.dir == "rtl" ? 37 : 39] = CKEDITOR.env.ie ? "mouseup" : "click";
                n[32] = CKEDITOR.env.ie ? "mouseup" :
                    "click";
                CKEDITOR.env.ie && (n[13] = "mouseup");
                m = this._.element = m.element;
                n = m.getDocument();
                n.getBody().setStyle("overflow", "hidden");
                n.getElementsByTag("html").getItem(0).setStyle("overflow", "hidden");
                this._.itemOverFn = CKEDITOR.tools.addFunction(function (a) {
                    clearTimeout(this._.showSubTimeout);
                    this._.showSubTimeout = CKEDITOR.tools.setTimeout(this._.showSubMenu, j.config.menu_subMenuDelay || 400, this, [a])
                }, this);
                this._.itemOutFn = CKEDITOR.tools.addFunction(function () {
                    clearTimeout(this._.showSubTimeout)
                }, this);
                this._.itemClickFn = CKEDITOR.tools.addFunction(function (a) {
                    var b = this.items[a];
                    if (b.state == CKEDITOR.TRISTATE_DISABLED)this.hide(1); else if (b.getItems)this._.showSubMenu(a); else this._.onClick(b)
                }, this)
            }
            b(e);
            for (var n = j.elementPath(), n = ['<div class="cke_menu' + (n && n.direction() != j.lang.dir ? " cke_mixed_dir_content" : "") + '" role="presentation">'], l = e.length, o = l && e[0].group, q = 0; q < l; q++) {
                var s = e[q];
                if (o != s.group) {
                    n.push('<div class="cke_menuseparator" role="separator"></div>');
                    o = s.group
                }
                s.render(this, q, n)
            }
            n.push("</div>");
            m.setHtml(n.join(""));
            CKEDITOR.ui.fire("ready", this);
            this.parent ? this.parent._.panel.showAsChild(k, this.id, a, c, d, f) : k.showBlock(this.id, a, c, d, f);
            j.fire("menuShow", [k])
        }, addListener: function (a) {
            this._.listeners.push(a)
        }, hide: function (a) {
            this._.onHide && this._.onHide();
            this._.panel && this._.panel.hide(a)
        }}});
        CKEDITOR.menuItem = CKEDITOR.tools.createClass({$: function (a, b, d) {
            CKEDITOR.tools.extend(this, d, {order: 0, className: "cke_menubutton__" + b});
            this.group = a._.menuGroups[this.group];
            this.editor = a;
            this.name =
                b
        }, proto: {render: function (b, c, d) {
            var i = b.id + ("" + c), e = typeof this.state == "undefined" ? CKEDITOR.TRISTATE_OFF : this.state, j = e == CKEDITOR.TRISTATE_ON ? "on" : e == CKEDITOR.TRISTATE_DISABLED ? "disabled" : "off", k = this.getItems, m = "&#" + (this.editor.lang.dir == "rtl" ? "9668" : "9658") + ";", n = this.name;
            if (this.icon && !/\./.test(this.icon))n = this.icon;
            b = {id: i, name: this.name, iconName: n, label: this.label, cls: this.className || "", state: j, hasPopup: k ? "true" : "false", disabled: e == CKEDITOR.TRISTATE_DISABLED, title: this.label, href: "javascript:void('" +
                (this.label || "").replace("'") + "')", hoverFn: b._.itemOverFn, moveOutFn: b._.itemOutFn, clickFn: b._.itemClickFn, index: c, iconStyle: CKEDITOR.skin.getIconStyle(n, this.editor.lang.dir == "rtl", n == this.icon ? null : this.icon, this.iconOffset), arrowHtml: k ? f.output({label: m}) : ""};
            a.output(b, d)
        }}})
    }(), CKEDITOR.config.menu_groups = "clipboard,form,tablecell,tablecellproperties,tablerow,tablecolumn,table,anchor,link,image,flash,checkbox,radio,textfield,hiddenfield,imagebutton,button,select,textarea,div", CKEDITOR.plugins.add("contextmenu",
        {requires: "menu", onLoad: function () {
            CKEDITOR.plugins.contextMenu = CKEDITOR.tools.createClass({base: CKEDITOR.menu, $: function (b) {
                this.base.call(this, b, {panel: {className: "cke_menu_panel", attributes: {"aria-label": b.lang.contextmenu.options}}})
            }, proto: {addTarget: function (b, c) {
                if (CKEDITOR.env.opera && !("oncontextmenu"in document.body)) {
                    var a;
                    b.on("mousedown", function (f) {
                        f = f.data;
                        if (f.$.button != 2)f.getKeystroke() == CKEDITOR.CTRL + 1 && b.fire("contextmenu", f); else if (!c || !(CKEDITOR.env.mac ? f.$.metaKey : f.$.ctrlKey)) {
                            var d =
                                f.getTarget();
                            if (!a) {
                                d = d.getDocument();
                                a = d.createElement("input");
                                a.$.type = "button";
                                d.getBody().append(a)
                            }
                            a.setAttribute("style", "position:absolute;top:" + (f.$.clientY - 2) + "px;left:" + (f.$.clientX - 2) + "px;width:5px;height:5px;opacity:0.01")
                        }
                    });
                    b.on("mouseup", function (c) {
                        if (a) {
                            a.remove();
                            a = void 0;
                            b.fire("contextmenu", c.data)
                        }
                    })
                }
                b.on("contextmenu", function (a) {
                    a = a.data;
                    if (!c || !(CKEDITOR.env.webkit ? f : CKEDITOR.env.mac ? a.$.metaKey : a.$.ctrlKey)) {
                        a.preventDefault();
                        var b = a.getTarget().getDocument(), h = a.getTarget().getDocument().getDocumentElement(),
                            e = !b.equals(CKEDITOR.document), b = b.getWindow().getScrollPosition(), j = e ? a.$.clientX : a.$.pageX || b.x + a.$.clientX, k = e ? a.$.clientY : a.$.pageY || b.y + a.$.clientY;
                        CKEDITOR.tools.setTimeout(function () {
                            this.open(h, null, j, k)
                        }, CKEDITOR.env.ie ? 200 : 0, this)
                    }
                }, this);
                if (CKEDITOR.env.opera)b.on("keypress", function (a) {
                    a = a.data;
                    a.$.keyCode === 0 && a.preventDefault()
                });
                if (CKEDITOR.env.webkit) {
                    var f, h = function () {
                        f = 0
                    };
                    b.on("keydown", function (a) {
                        f = CKEDITOR.env.mac ? a.data.$.metaKey : a.data.$.ctrlKey
                    });
                    b.on("keyup", h);
                    b.on("contextmenu",
                        h)
                }
            }, open: function (b, c, a, f) {
                this.editor.focus();
                b = b || CKEDITOR.document.getDocumentElement();
                this.editor.selectionChange(1);
                this.show(b, c, a, f)
            }}})
        }, beforeInit: function (b) {
            var c = b.contextMenu = new CKEDITOR.plugins.contextMenu(b);
            b.on("contentDom", function () {
                c.addTarget(b.editable(), b.config.browserContextMenuOnCtrl !== false)
            });
            b.addCommand("contextMenu", {exec: function () {
                b.contextMenu.open(b.document.getBody())
            }});
            b.setKeystroke(CKEDITOR.SHIFT + 121, "contextMenu");
            b.setKeystroke(CKEDITOR.CTRL + CKEDITOR.SHIFT +
                121, "contextMenu")
        }}), function () {
        function b(a) {
            var b = this.att, a = a && a.hasAttribute(b) && a.getAttribute(b) || "";
            a !== void 0 && this.setValue(a)
        }

        function c() {
            for (var a, b = 0; b < arguments.length; b++)if (arguments[b]instanceof CKEDITOR.dom.element) {
                a = arguments[b];
                break
            }
            if (a) {
                var b = this.att, c = this.getValue();
                c ? a.setAttribute(b, c) : a.removeAttribute(b, c)
            }
        }

        var a = {id: 1, dir: 1, classes: 1, styles: 1};
        CKEDITOR.plugins.add("dialogadvtab", {requires: "dialog", allowedContent: function (b) {
            b || (b = a);
            var c = [];
            b.id && c.push("id");
            b.dir &&
            c.push("dir");
            var g = "";
            c.length && (g = g + ("[" + c.join(",") + "]"));
            b.classes && (g = g + "(*)");
            b.styles && (g = g + "{*}");
            return g
        }, createAdvancedTab: function (f, h, g) {
            h || (h = a);
            var d = f.lang.common, i = {id: "advanced", label: d.advancedTab, title: d.advancedTab, elements: [
                {type: "vbox", padding: 1, children: []}
            ]}, e = [];
            if (h.id || h.dir) {
                h.id && e.push({id: "advId", att: "id", type: "text", requiredContent: g ? g + "[id]" : null, label: d.id, setup: b, commit: c});
                h.dir && e.push({id: "advLangDir", att: "dir", type: "select", requiredContent: g ? g + "[dir]" : null,
                    label: d.langDir, "default": "", style: "width:100%", items: [
                        [d.notSet, ""],
                        [d.langDirLTR, "ltr"],
                        [d.langDirRTL, "rtl"]
                    ], setup: b, commit: c});
                i.elements[0].children.push({type: "hbox", widths: ["50%", "50%"], children: [].concat(e)})
            }
            if (h.styles || h.classes) {
                e = [];
                h.styles && e.push({id: "advStyles", att: "style", type: "text", requiredContent: g ? g + "{cke-xyz}" : null, label: d.styles, "default": "", validate: CKEDITOR.dialog.validate.inlineStyle(d.invalidInlineStyle), onChange: function () {
                }, getStyle: function (a, b) {
                    var d = this.getValue().match(RegExp("(?:^|;)\\s*" +
                        a + "\\s*:\\s*([^;]*)", "i"));
                    return d ? d[1] : b
                }, updateStyle: function (a, b) {
                    var d = this.getValue(), c = f.document.createElement("span");
                    c.setAttribute("style", d);
                    c.setStyle(a, b);
                    d = CKEDITOR.tools.normalizeCssText(c.getAttribute("style"));
                    this.setValue(d, 1)
                }, setup: b, commit: c});
                h.classes && e.push({type: "hbox", widths: ["45%", "55%"], children: [
                    {id: "advCSSClasses", att: "class", type: "text", requiredContent: g ? g + "(cke-xyz)" : null, label: d.cssClasses, "default": "", setup: b, commit: c}
                ]});
                i.elements[0].children.push({type: "hbox",
                    widths: ["50%", "50%"], children: [].concat(e)})
            }
            return i
        }})
    }(), function () {
        CKEDITOR.plugins.add("div", {requires: "dialog", init: function (b) {
            if (!b.blockless) {
                var c = b.lang.div, a = "div(*)";
                CKEDITOR.dialog.isTabEnabled(b, "editdiv", "advanced") && (a = a + ";div[dir,id,lang,title]{*}");
                b.addCommand("creatediv", new CKEDITOR.dialogCommand("creatediv", {allowedContent: a, requiredContent: "div", contextSensitive: true, refresh: function (a, b) {
                    this.setState("div"in(a.config.div_wrapTable ? b.root : b.blockLimit).getDtd() ? CKEDITOR.TRISTATE_OFF :
                        CKEDITOR.TRISTATE_DISABLED)
                }}));
                b.addCommand("editdiv", new CKEDITOR.dialogCommand("editdiv", {requiredContent: "div"}));
                b.addCommand("removediv", {requiredContent: "div", exec: function (a) {
                    function b(d) {
                        if ((d = CKEDITOR.plugins.div.getSurroundDiv(a, d)) && !d.data("cke-div-added")) {
                            j.push(d);
                            d.data("cke-div-added")
                        }
                    }

                    for (var c = a.getSelection(), d = c && c.getRanges(), i, e = c.createBookmarks(), j = [], k = 0; k < d.length; k++) {
                        i = d[k];
                        if (i.collapsed)b(c.getStartElement()); else {
                            i = new CKEDITOR.dom.walker(i);
                            i.evaluator = b;
                            i.lastForward()
                        }
                    }
                    for (k =
                             0; k < j.length; k++)j[k].remove(true);
                    c.selectBookmarks(e)
                }});
                b.ui.addButton && b.ui.addButton("CreateDiv", {label: c.toolbar, command: "creatediv", toolbar: "blocks,50"});
                if (b.addMenuItems) {
                    b.addMenuItems({editdiv: {label: c.edit, command: "editdiv", group: "div", order: 1}, removediv: {label: c.remove, command: "removediv", group: "div", order: 5}});
                    b.contextMenu && b.contextMenu.addListener(function (a) {
                        return!a || a.isReadOnly() ? null : CKEDITOR.plugins.div.getSurroundDiv(b) ? {editdiv: CKEDITOR.TRISTATE_OFF, removediv: CKEDITOR.TRISTATE_OFF} :
                            null
                    })
                }
                CKEDITOR.dialog.add("creatediv", this.path + "dialogs/div.js");
                CKEDITOR.dialog.add("editdiv", this.path + "dialogs/div.js")
            }
        }});
        CKEDITOR.plugins.div = {getSurroundDiv: function (b, c) {
            var a = b.elementPath(c);
            return b.elementPath(a.blockLimit).contains("div", 1)
        }}
    }(), function () {
        var b;

        function c(c, d) {
            function f(a) {
                a = c._.elementsPath.list[a];
                if (a.equals(c.editable())) {
                    var b = c.createRange();
                    b.selectNodeContents(a);
                    b.select()
                } else c.getSelection().selectElement(a);
                c.focus()
            }

            function e() {
                k && k.setHtml(a);
                delete c._.elementsPath.list
            }

            var j = c.ui.spaceId("path"), k, m = "cke_elementspath_" + CKEDITOR.tools.getNextNumber() + "_";
            c._.elementsPath = {idBase: m, filters: []};
            d.html = d.html + ('<span id="' + j + '_label" class="cke_voice_label">' + c.lang.elementspath.eleLabel + '</span><span id="' + j + '" class="cke_path" role="group" aria-labelledby="' + j + '_label">' + a + "</span>");
            c.on("uiReady", function () {
                var a = c.ui.space("path");
                a && c.focusManager.add(a, 1)
            });
            var n = CKEDITOR.tools.addFunction(f), l = CKEDITOR.tools.addFunction(function (a, b) {
                var d = c._.elementsPath.idBase,
                    e, b = new CKEDITOR.dom.event(b);
                e = c.lang.dir == "rtl";
                switch (b.getKeystroke()) {
                    case e ? 39 : 37:
                    case 9:
                        (e = CKEDITOR.document.getById(d + (a + 1))) || (e = CKEDITOR.document.getById(d + "0"));
                        e.focus();
                        return false;
                    case e ? 37 : 39:
                    case CKEDITOR.SHIFT + 9:
                        (e = CKEDITOR.document.getById(d + (a - 1))) || (e = CKEDITOR.document.getById(d + (c._.elementsPath.list.length - 1)));
                        e.focus();
                        return false;
                    case 27:
                        c.focus();
                        return false;
                    case 13:
                    case 32:
                        f(a);
                        return false
                }
                return true
            });
            c.on("selectionChange", function (b) {
                for (var d = c.editable(), e = b.data.selection.getStartElement(),
                         b = [], f = c._.elementsPath.list = [], i = c._.elementsPath.filters; e;) {
                    var z = 0, x;
                    x = e.data("cke-display-name") ? e.data("cke-display-name") : e.data("cke-real-element-type") ? e.data("cke-real-element-type") : e.getName();
                    for (var w = 0; w < i.length; w++) {
                        var v = i[w](e, x);
                        if (v === false) {
                            z = 1;
                            break
                        }
                        x = v || x
                    }
                    if (!z) {
                        z = f.push(e) - 1;
                        w = c.lang.elementspath.eleTitle.replace(/%1/, x);
                        x = h.output({id: m + z, label: w, text: x, jsTitle: "javascript:void('" + x + "')", index: z, keyDownFn: l, clickFn: n});
                        b.unshift(x)
                    }
                    if (e.equals(d))break;
                    e = e.getParent()
                }
                k ||
                (k = CKEDITOR.document.getById(j));
                d = k;
                d.setHtml(b.join("") + a);
                c.fire("elementsPathUpdate", {space: d})
            });
            c.on("readOnly", e);
            c.on("contentDomUnload", e);
            c.addCommand("elementsPathFocus", b);
            c.setKeystroke(CKEDITOR.ALT + 122, "elementsPathFocus")
        }

        b = {editorFocus: false, readOnly: 1, exec: function (a) {
            (a = CKEDITOR.document.getById(a._.elementsPath.idBase + "0")) && a.focus(CKEDITOR.env.ie || CKEDITOR.env.air)
        }};
        var a = '<span class="cke_path_empty">&nbsp;</span>', f = "";
        if (CKEDITOR.env.opera || CKEDITOR.env.gecko && CKEDITOR.env.mac)f =
            f + ' onkeypress="return false;"';
        CKEDITOR.env.gecko && (f = f + ' onblur="this.style.cssText = this.style.cssText;"');
        var h = CKEDITOR.addTemplate("pathItem", '<a id="{id}" href="{jsTitle}" tabindex="-1" class="cke_path_item" title="{label}"' + (CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 ? ' onfocus="event.preventBubble();"' : "") + f + ' hidefocus="true"  onkeydown="return CKEDITOR.tools.callFunction({keyDownFn},{index}, event );" onclick="CKEDITOR.tools.callFunction({clickFn},{index}); return false;" role="button" aria-label="{label}">{text}</a>');
        CKEDITOR.plugins.add("elementspath", {init: function (a) {
            a.on("uiSpace", function (b) {
                b.data.space == "bottom" && c(a, b.data)
            })
        }})
    }(), function () {
        function b(a, b, d) {
            function c(d) {
                if ((j = i[d ? "getFirst" : "getLast"]()) && (!j.is || !j.isBlockBoundary()) && (k = b.root[d ? "getPrevious" : "getNext"](CKEDITOR.dom.walker.invisible(true))) && (!k.is || !k.isBlockBoundary({br: 1})))a.document.createElement("br")[d ? "insertBefore" : "insertAfter"](j)
            }

            for (var e = CKEDITOR.plugins.list.listToArray(b.root, d), f = [], g = 0; g < b.contents.length; g++) {
                var h =
                    b.contents[g];
                if ((h = h.getAscendant("li", true)) && !h.getCustomData("list_item_processed")) {
                    f.push(h);
                    CKEDITOR.dom.element.setMarker(d, h, "list_item_processed", true)
                }
            }
            h = null;
            for (g = 0; g < f.length; g++) {
                h = f[g].getCustomData("listarray_index");
                e[h].indent = -1
            }
            for (g = h + 1; g < e.length; g++)if (e[g].indent > e[g - 1].indent + 1) {
                f = e[g - 1].indent + 1 - e[g].indent;
                for (h = e[g].indent; e[g] && e[g].indent >= h;) {
                    e[g].indent = e[g].indent + f;
                    g++
                }
                g--
            }
            var i = CKEDITOR.plugins.list.arrayToList(e, d, null, a.config.enterMode, b.root.getAttribute("dir")).listNode,
                j, k;
            c(true);
            c();
            i.replace(b.root)
        }

        function c(a, b) {
            this.name = a;
            this.context = this.type = b;
            this.allowedContent = b + " li";
            this.requiredContent = b
        }

        function a(a, b, d, c) {
            for (var e, f; e = a[c ? "getLast" : "getFirst"](l);) {
                (f = e.getDirection(1)) !== b.getDirection(1) && e.setAttribute("dir", f);
                e.remove();
                d ? e[c ? "insertBefore" : "insertAfter"](d) : b.append(e, c)
            }
        }

        function f(b) {
            var d;
            (d = function (d) {
                var c = b[d ? "getPrevious" : "getNext"](k);
                if (c && c.type == CKEDITOR.NODE_ELEMENT && c.is(b.getName())) {
                    a(b, c, null, !d);
                    b.remove();
                    b = c
                }
            })();
            d(1)
        }

        function h(a) {
            return a.type == CKEDITOR.NODE_ELEMENT && (a.getName()in CKEDITOR.dtd.$block || a.getName()in CKEDITOR.dtd.$listItem) && CKEDITOR.dtd[a.getName()]["#"]
        }

        function g(b, c, e) {
            b.fire("saveSnapshot");
            e.enlarge(CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS);
            var g = e.extractContents();
            c.trim(false, true);
            var h = c.createBookmark(), i = new CKEDITOR.dom.elementPath(c.startContainer), j = i.block, i = i.lastElement.getAscendant("li", 1) || j, l = new CKEDITOR.dom.elementPath(e.startContainer), n = l.contains(CKEDITOR.dtd.$listItem),
                l = l.contains(CKEDITOR.dtd.$list);
            if (j)(j = j.getBogus()) && j.remove(); else if (l)(j = l.getPrevious(k)) && m(j) && j.remove();
            (j = g.getLast()) && (j.type == CKEDITOR.NODE_ELEMENT && j.is("br")) && j.remove();
            (j = c.startContainer.getChild(c.startOffset)) ? g.insertBefore(j) : c.startContainer.append(g);
            if (n)if (g = d(n))if (i.contains(n)) {
                a(g, n.getParent(), n);
                g.remove()
            } else i.append(g);
            for (; e.checkStartOfBlock() && e.checkEndOfBlock();) {
                l = e.startPath();
                g = l.block;
                if (g.is("li")) {
                    i = g.getParent();
                    g.equals(i.getLast(k)) && g.equals(i.getFirst(k)) &&
                    (g = i)
                }
                e.moveToPosition(g, CKEDITOR.POSITION_BEFORE_START);
                g.remove()
            }
            e = e.clone();
            g = b.editable();
            e.setEndAt(g, CKEDITOR.POSITION_BEFORE_END);
            e = new CKEDITOR.dom.walker(e);
            e.evaluator = function (a) {
                return k(a) && !m(a)
            };
            (e = e.next()) && (e.type == CKEDITOR.NODE_ELEMENT && e.getName()in CKEDITOR.dtd.$list) && f(e);
            c.moveToBookmark(h);
            c.select();
            b.fire("saveSnapshot")
        }

        function d(a) {
            return(a = a.getLast(k)) && a.type == CKEDITOR.NODE_ELEMENT && a.getName()in i ? a : null
        }

        var i = {ol: 1, ul: 1}, e = CKEDITOR.dom.walker.whitespaces(), j = CKEDITOR.dom.walker.bookmark(),
            k = function (a) {
                return!(e(a) || j(a))
            }, m = CKEDITOR.dom.walker.bogus();
        CKEDITOR.plugins.list = {listToArray: function (a, b, d, c, e) {
            if (!i[a.getName()])return[];
            c || (c = 0);
            d || (d = []);
            for (var f = 0, g = a.getChildCount(); f < g; f++) {
                var h = a.getChild(f);
                h.type == CKEDITOR.NODE_ELEMENT && h.getName()in CKEDITOR.dtd.$list && CKEDITOR.plugins.list.listToArray(h, b, d, c + 1);
                if (h.$.nodeName.toLowerCase() == "li") {
                    var j = {parent: a, indent: c, element: h, contents: []};
                    if (e)j.grandparent = e; else {
                        j.grandparent = a.getParent();
                        if (j.grandparent && j.grandparent.$.nodeName.toLowerCase() ==
                            "li")j.grandparent = j.grandparent.getParent()
                    }
                    b && CKEDITOR.dom.element.setMarker(b, h, "listarray_index", d.length);
                    d.push(j);
                    for (var k = 0, l = h.getChildCount(), m; k < l; k++) {
                        m = h.getChild(k);
                        m.type == CKEDITOR.NODE_ELEMENT && i[m.getName()] ? CKEDITOR.plugins.list.listToArray(m, b, d, c + 1, j.grandparent) : j.contents.push(m)
                    }
                }
            }
            return d
        }, arrayToList: function (a, b, d, c, e) {
            d || (d = 0);
            if (!a || a.length < d + 1)return null;
            for (var f, g = a[d].parent.getDocument(), h = new CKEDITOR.dom.documentFragment(g), j = null, l = d, m = Math.max(a[d].indent, 0),
                     n = null, B, y, C = c == CKEDITOR.ENTER_P ? "p" : "div"; ;) {
                var D = a[l];
                f = D.grandparent;
                B = D.element.getDirection(1);
                if (D.indent == m) {
                    if (!j || a[l].parent.getName() != j.getName()) {
                        j = a[l].parent.clone(false, 1);
                        e && j.setAttribute("dir", e);
                        h.append(j)
                    }
                    n = j.append(D.element.clone(0, 1));
                    B != j.getDirection(1) && n.setAttribute("dir", B);
                    for (f = 0; f < D.contents.length; f++)n.append(D.contents[f].clone(1, 1));
                    l++
                } else if (D.indent == Math.max(m, 0) + 1) {
                    y = a[l - 1].element.getDirection(1);
                    l = CKEDITOR.plugins.list.arrayToList(a, null, l, c, y != B ? B : null);
                    !n.getChildCount() && (CKEDITOR.env.ie && !(g.$.documentMode > 7)) && n.append(g.createText(" "));
                    n.append(l.listNode);
                    l = l.nextIndex
                } else if (D.indent == -1 && !d && f) {
                    if (i[f.getName()]) {
                        n = D.element.clone(false, true);
                        B != f.getDirection(1) && n.setAttribute("dir", B)
                    } else n = new CKEDITOR.dom.documentFragment(g);
                    var j = f.getDirection(1) != B, F = D.element, E = F.getAttribute("class"), K = F.getAttribute("style"), I = n.type == CKEDITOR.NODE_DOCUMENT_FRAGMENT && (c != CKEDITOR.ENTER_BR || j || K || E), G, H = D.contents.length;
                    for (f = 0; f < H; f++) {
                        G = D.contents[f];
                        if (G.type == CKEDITOR.NODE_ELEMENT && G.isBlockBoundary()) {
                            j && !G.getDirection() && G.setAttribute("dir", B);
                            var L = G, J = F.getAttribute("style");
                            J && L.setAttribute("style", J.replace(/([^;])$/, "$1;") + (L.getAttribute("style") || ""));
                            E && G.addClass(E)
                        } else if (I) {
                            if (!y) {
                                y = g.createElement(C);
                                j && y.setAttribute("dir", B)
                            }
                            K && y.setAttribute("style", K);
                            E && y.setAttribute("class", E);
                            y.append(G.clone(1, 1))
                        }
                        n.append(y || G.clone(1, 1))
                    }
                    if (n.type == CKEDITOR.NODE_DOCUMENT_FRAGMENT && l != a.length - 1) {
                        (B = n.getLast()) && (B.type == CKEDITOR.NODE_ELEMENT &&
                            B.getAttribute("type") == "_moz") && B.remove();
                        (!n.getLast(k) || !(B.type == CKEDITOR.NODE_ELEMENT && B.getName()in CKEDITOR.dtd.$block)) && n.append(g.createElement("br"))
                    }
                    B = n.$.nodeName.toLowerCase();
                    !CKEDITOR.env.ie && (B == "div" || B == "p") && n.appendBogus();
                    h.append(n);
                    j = null;
                    l++
                } else return null;
                y = null;
                if (a.length <= l || Math.max(a[l].indent, 0) < m)break
            }
            if (b)for (a = h.getFirst(); a;) {
                if (a.type == CKEDITOR.NODE_ELEMENT) {
                    CKEDITOR.dom.element.clearMarkers(b, a);
                    if (a.getName()in CKEDITOR.dtd.$listItem) {
                        d = a;
                        g = e = c = void 0;
                        if (c =
                            d.getDirection()) {
                            for (e = d.getParent(); e && !(g = e.getDirection());)e = e.getParent();
                            c == g && d.removeAttribute("dir")
                        }
                    }
                }
                a = a.getNextSourceNode()
            }
            return{listNode: h, nextIndex: l}
        }};
        var n = /^h[1-6]$/, l = CKEDITOR.dom.walker.nodeType(CKEDITOR.NODE_ELEMENT);
        c.prototype = {exec: function (a) {
            this.refresh(a, a.elementPath());
            var d = a.config, c = a.getSelection(), e = c && c.getRanges(true);
            if (this.state == CKEDITOR.TRISTATE_OFF) {
                var g = a.editable();
                if (g.getFirst(k)) {
                    var h = e.length == 1 && e[0];
                    (d = h && h.getEnclosedNode()) && (d.is && this.type ==
                        d.getName()) && this.setState(CKEDITOR.TRISTATE_ON)
                } else {
                    d.enterMode == CKEDITOR.ENTER_BR ? g.appendBogus() : e[0].fixBlock(1, d.enterMode == CKEDITOR.ENTER_P ? "p" : "div");
                    c.selectRanges(e)
                }
            }
            for (var d = c.createBookmarks(true), g = [], j = {}, e = e.createIterator(), l = 0; (h = e.getNextRange()) && ++l;) {
                var m = h.getBoundaryNodes(), r = m.startNode, u = m.endNode;
                r.type == CKEDITOR.NODE_ELEMENT && r.getName() == "td" && h.setStartAt(m.startNode, CKEDITOR.POSITION_AFTER_START);
                u.type == CKEDITOR.NODE_ELEMENT && u.getName() == "td" && h.setEndAt(m.endNode,
                    CKEDITOR.POSITION_BEFORE_END);
                h = h.createIterator();
                for (h.forceBrBreak = this.state == CKEDITOR.TRISTATE_OFF; m = h.getNextParagraph();)if (!m.getCustomData("list_block")) {
                    CKEDITOR.dom.element.setMarker(j, m, "list_block", 1);
                    for (var A = a.elementPath(m), r = A.elements, u = 0, A = A.blockLimit, B, y = r.length - 1; y >= 0 && (B = r[y]); y--)if (i[B.getName()] && A.contains(B)) {
                        A.removeCustomData("list_group_object_" + l);
                        if (r = B.getCustomData("list_group_object"))r.contents.push(m); else {
                            r = {root: B, contents: [m]};
                            g.push(r);
                            CKEDITOR.dom.element.setMarker(j,
                                B, "list_group_object", r)
                        }
                        u = 1;
                        break
                    }
                    if (!u) {
                        u = A;
                        if (u.getCustomData("list_group_object_" + l))u.getCustomData("list_group_object_" + l).contents.push(m); else {
                            r = {root: u, contents: [m]};
                            CKEDITOR.dom.element.setMarker(j, u, "list_group_object_" + l, r);
                            g.push(r)
                        }
                    }
                }
            }
            for (B = []; g.length > 0;) {
                r = g.shift();
                if (this.state == CKEDITOR.TRISTATE_OFF)if (i[r.root.getName()]) {
                    m = a;
                    e = r;
                    r = j;
                    l = B;
                    u = CKEDITOR.plugins.list.listToArray(e.root, r);
                    A = [];
                    for (h = 0; h < e.contents.length; h++) {
                        y = e.contents[h];
                        if ((y = y.getAscendant("li", true)) && !y.getCustomData("list_item_processed")) {
                            A.push(y);
                            CKEDITOR.dom.element.setMarker(r, y, "list_item_processed", true)
                        }
                    }
                    for (var y = e.root.getDocument(), C = void 0, D = void 0, h = 0; h < A.length; h++) {
                        var F = A[h].getCustomData("listarray_index"), C = u[F].parent;
                        if (!C.is(this.type)) {
                            D = y.createElement(this.type);
                            C.copyAttributes(D, {start: 1, type: 1});
                            D.removeStyle("list-style-type");
                            u[F].parent = D
                        }
                    }
                    m = CKEDITOR.plugins.list.arrayToList(u, r, null, m.config.enterMode);
                    r = void 0;
                    u = m.listNode.getChildCount();
                    for (h = 0; h < u && (r = m.listNode.getChild(h)); h++)r.getName() == this.type && l.push(r);
                    m.listNode.replace(e.root)
                } else {
                    u = a;
                    m = r;
                    h = B;
                    A = m.contents;
                    e = m.root.getDocument();
                    l = [];
                    if (A.length == 1 && A[0].equals(m.root)) {
                        r = e.createElement("div");
                        A[0].moveChildren && A[0].moveChildren(r);
                        A[0].append(r);
                        A[0] = r
                    }
                    m = m.contents[0].getParent();
                    for (y = 0; y < A.length; y++)m = m.getCommonAncestor(A[y].getParent());
                    C = u.config.useComputedState;
                    u = r = void 0;
                    C = C === void 0 || C;
                    for (y = 0; y < A.length; y++)for (D = A[y]; F = D.getParent();) {
                        if (F.equals(m)) {
                            l.push(D);
                            !u && D.getDirection() && (u = 1);
                            D = D.getDirection(C);
                            r !== null && (r = r && r != D ?
                                null : D);
                            break
                        }
                        D = F
                    }
                    if (!(l.length < 1)) {
                        A = l[l.length - 1].getNext();
                        y = e.createElement(this.type);
                        h.push(y);
                        for (C = h = void 0; l.length;) {
                            h = l.shift();
                            C = e.createElement("li");
                            if (h.is("pre") || n.test(h.getName()))h.appendTo(C); else {
                                h.copyAttributes(C);
                                if (r && h.getDirection()) {
                                    C.removeStyle("direction");
                                    C.removeAttribute("dir")
                                }
                                h.moveChildren(C);
                                h.remove()
                            }
                            C.appendTo(y)
                        }
                        r && u && y.setAttribute("dir", r);
                        A ? y.insertBefore(A) : y.appendTo(m)
                    }
                } else this.state == CKEDITOR.TRISTATE_ON && i[r.root.getName()] && b.call(this, a, r, j)
            }
            for (y =
                     0; y < B.length; y++)f(B[y]);
            CKEDITOR.dom.element.clearAllMarkers(j);
            c.selectBookmarks(d);
            a.focus()
        }, refresh: function (a, b) {
            var d = b.contains(i, 1), c = b.blockLimit || b.root;
            d && c.contains(d) ? this.setState(d.is(this.type) ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF) : this.setState(CKEDITOR.TRISTATE_OFF)
        }};
        CKEDITOR.plugins.add("list", {requires: "indent", init: function (a) {
            if (!a.blockless) {
                a.addCommand("numberedlist", new c("numberedlist", "ol"));
                a.addCommand("bulletedlist", new c("bulletedlist", "ul"));
                if (a.ui.addButton) {
                    a.ui.addButton("NumberedList",
                        {label: a.lang.list.numberedlist, command: "numberedlist", directional: true, toolbar: "list,10"});
                    a.ui.addButton("BulletedList", {label: a.lang.list.bulletedlist, command: "bulletedlist", directional: true, toolbar: "list,20"})
                }
                a.on("key", function (b) {
                    var c = b.data.keyCode;
                    if (a.mode == "wysiwyg" && c in{8: 1, 46: 1}) {
                        var e = a.getSelection().getRanges()[0], f = e.startPath();
                        if (e.collapsed) {
                            var f = new CKEDITOR.dom.elementPath(e.startContainer), j = c == 8, l = a.editable(), n = new CKEDITOR.dom.walker(e.clone());
                            n.evaluator = function (a) {
                                return k(a) && !m(a)
                            };
                            n.guard = function (a, b) {
                                return!(b && a.type == CKEDITOR.NODE_ELEMENT && a.is("table"))
                            };
                            c = e.clone();
                            if (j) {
                                var v, r;
                                if ((v = f.contains(i)) && e.checkBoundaryOfElement(v, CKEDITOR.START) && (v = v.getParent()) && v.is("li") && (v = d(v))) {
                                    r = v;
                                    v = v.getPrevious(k);
                                    c.moveToPosition(v && m(v) ? v : r, CKEDITOR.POSITION_BEFORE_START)
                                } else {
                                    n.range.setStartAt(l, CKEDITOR.POSITION_AFTER_START);
                                    n.range.setEnd(e.startContainer, e.startOffset);
                                    if ((v = n.previous()) && v.type == CKEDITOR.NODE_ELEMENT && (v.getName()in i || v.is("li"))) {
                                        if (!v.is("li")) {
                                            n.range.selectNodeContents(v);
                                            n.reset();
                                            n.evaluator = h;
                                            v = n.previous()
                                        }
                                        r = v;
                                        c.moveToElementEditEnd(r)
                                    }
                                }
                                if (r) {
                                    g(a, c, e);
                                    b.cancel()
                                } else if ((c = f.contains(i)) && e.checkBoundaryOfElement(c, CKEDITOR.START)) {
                                    r = c.getFirst(k);
                                    if (e.checkBoundaryOfElement(r, CKEDITOR.START)) {
                                        v = c.getPrevious(k);
                                        if (d(r)) {
                                            if (v) {
                                                e.moveToElementEditEnd(v);
                                                e.select()
                                            }
                                        } else a.execCommand("outdent");
                                        b.cancel()
                                    }
                                }
                            } else if (r = f.contains("li")) {
                                n.range.setEndAt(l, CKEDITOR.POSITION_BEFORE_END);
                                l = (f = r.getLast(k)) && h(f) ? f : r;
                                r = 0;
                                if ((v = n.next()) && v.type == CKEDITOR.NODE_ELEMENT &&
                                    v.getName()in i && v.equals(f)) {
                                    r = 1;
                                    v = n.next()
                                } else e.checkBoundaryOfElement(l, CKEDITOR.END) && (r = 1);
                                if (r && v) {
                                    e = e.clone();
                                    e.moveToElementEditStart(v);
                                    g(a, c, e);
                                    b.cancel()
                                }
                            } else {
                                n.range.setEndAt(l, CKEDITOR.POSITION_BEFORE_END);
                                if ((v = n.next()) && v.type == CKEDITOR.NODE_ELEMENT && v.is(i)) {
                                    v = v.getFirst(k);
                                    if (f.block && e.checkStartOfBlock() && e.checkEndOfBlock()) {
                                        f.block.remove();
                                        e.moveToElementEditStart(v);
                                        e.select()
                                    } else if (d(v)) {
                                        e.moveToElementEditStart(v);
                                        e.select()
                                    } else {
                                        e = e.clone();
                                        e.moveToElementEditStart(v);
                                        g(a, c, e)
                                    }
                                    b.cancel()
                                }
                            }
                            setTimeout(function () {
                                a.selectionChange(1)
                            })
                        }
                    }
                })
            }
        }})
    }(), function () {
        function b(a, b) {
            this.name = b;
            var c = this.useIndentClasses = a.config.indentClasses && a.config.indentClasses.length > 0;
            if (c) {
                this.classNameRegex = RegExp("(?:^|\\s+)(" + a.config.indentClasses.join("|") + ")(?=$|\\s)");
                this.indentClassMap = {};
                for (var f = 0; f < a.config.indentClasses.length; f++)this.indentClassMap[a.config.indentClasses[f]] = f + 1
            }
            this.startDisabled = b == "outdent";
            this.allowedContent = {"div h1 h2 h3 h4 h5 h6 ol p pre ul": {propertiesOnly: true,
                styles: !c ? "margin-left,margin-right" : null, classes: c ? a.config.indentClasses : null}};
            this.requiredContent = ["p" + (c ? "(" + a.config.indentClasses[0] + ")" : "{margin-left}"), "li"]
        }

        function c(a, b) {
            return(b || a.getComputedStyle("direction")) == "ltr" ? "margin-left" : "margin-right"
        }

        function a(a) {
            return a.type == CKEDITOR.NODE_ELEMENT && a.is("li")
        }

        var f = {ol: 1, ul: 1}, h = CKEDITOR.dom.walker.whitespaces(true), g = CKEDITOR.dom.walker.bookmark(false, true);
        b.prototype = {context: "p", refresh: function (a, b) {
            var e = b && b.contains(f), g = b.block ||
                b.blockLimit;
            if (e)this.setState(CKEDITOR.TRISTATE_OFF); else if (!this.useIndentClasses && this.name == "indent")this.setState(CKEDITOR.TRISTATE_OFF); else if (g)if (this.useIndentClasses) {
                e = g.$.className.match(this.classNameRegex);
                g = 0;
                if (e) {
                    e = e[1];
                    g = this.indentClassMap[e]
                }
                this.name == "outdent" && !g || this.name == "indent" && g == a.config.indentClasses.length ? this.setState(CKEDITOR.TRISTATE_DISABLED) : this.setState(CKEDITOR.TRISTATE_OFF)
            } else {
                e = parseInt(g.getStyle(c(g)), 10);
                isNaN(e) && (e = 0);
                e <= 0 ? this.setState(CKEDITOR.TRISTATE_DISABLED) :
                    this.setState(CKEDITOR.TRISTATE_OFF)
            } else this.setState(CKEDITOR.TRISTATE_DISABLED)
        }, exec: function (b) {
            function i(a) {
                for (var c = o.startContainer, e = o.endContainer; c && !c.getParent().equals(a);)c = c.getParent();
                for (; e && !e.getParent().equals(a);)e = e.getParent();
                if (c && e) {
                    for (var i = c, c = [], j = false; !j;) {
                        i.equals(e) && (j = true);
                        c.push(i);
                        i = i.getNext()
                    }
                    if (!(c.length < 1)) {
                        i = a.getParents(true);
                        for (e = 0; e < i.length; e++)if (i[e].getName && f[i[e].getName()]) {
                            a = i[e];
                            break
                        }
                        for (var i = k.name == "indent" ? 1 : -1, e = c[0], c = c[c.length -
                            1], j = CKEDITOR.plugins.list.listToArray(a, m), l = j[c.getCustomData("listarray_index")].indent, e = e.getCustomData("listarray_index"); e <= c.getCustomData("listarray_index"); e++) {
                            j[e].indent = j[e].indent + i;
                            if (i > 0) {
                                var n = j[e].parent;
                                j[e].parent = new CKEDITOR.dom.element(n.getName(), n.getDocument())
                            }
                        }
                        for (e = c.getCustomData("listarray_index") + 1; e < j.length && j[e].indent > l; e++)j[e].indent = j[e].indent + i;
                        c = CKEDITOR.plugins.list.arrayToList(j, m, null, b.config.enterMode, a.getDirection());
                        if (k.name == "outdent") {
                            var p;
                            if ((p =
                                a.getParent()) && p.is("li"))for (var i = c.listNode.getChildren(), q = [], s, e = i.count() - 1; e >= 0; e--)(s = i.getItem(e)) && (s.is && s.is("li")) && q.push(s)
                        }
                        c && c.listNode.replace(a);
                        if (q && q.length)for (e = 0; e < q.length; e++) {
                            for (s = a = q[e]; (s = s.getNext()) && s.is && s.getName()in f;) {
                                CKEDITOR.env.ie && !a.getFirst(function (a) {
                                    return h(a) && g(a)
                                }) && a.append(o.document.createText(" "));
                                a.append(s)
                            }
                            a.insertAfter(p)
                        }
                    }
                }
            }

            function e() {
                var a = o.createIterator(), c = b.config.enterMode;
                a.enforceRealBlocks = true;
                a.enlargeBr = c != CKEDITOR.ENTER_BR;
                for (var e; e = a.getNextParagraph(c == CKEDITOR.ENTER_P ? "p" : "div");)j(e)
            }

            function j(a, e) {
                if (a.getCustomData("indent_processed"))return false;
                if (k.useIndentClasses) {
                    var f = a.$.className.match(k.classNameRegex), g = 0;
                    if (f) {
                        f = f[1];
                        g = k.indentClassMap[f]
                    }
                    k.name == "outdent" ? g-- : g++;
                    if (g < 0)return false;
                    g = Math.min(g, b.config.indentClasses.length);
                    g = Math.max(g, 0);
                    a.$.className = CKEDITOR.tools.ltrim(a.$.className.replace(k.classNameRegex, ""));
                    g > 0 && a.addClass(b.config.indentClasses[g - 1])
                } else {
                    f = c(a, e);
                    g = parseInt(a.getStyle(f),
                        10);
                    isNaN(g) && (g = 0);
                    var h = b.config.indentOffset || 40, g = g + (k.name == "indent" ? 1 : -1) * h;
                    if (g < 0)return false;
                    g = Math.max(g, 0);
                    g = Math.ceil(g / h) * h;
                    a.setStyle(f, g ? g + (b.config.indentUnit || "px") : "");
                    a.getAttribute("style") === "" && a.removeAttribute("style")
                }
                CKEDITOR.dom.element.setMarker(m, a, "indent_processed", 1);
                return true
            }

            for (var k = this, m = {}, n = b.getSelection(), l = n.createBookmarks(1), o, q = (n && n.getRanges(1)).createIterator(); o = q.getNextRange();) {
                for (var s = o.getCommonAncestor(); s && !(s.type == CKEDITOR.NODE_ELEMENT &&
                    f[s.getName()]);)s = s.getParent();
                if (!s) {
                    var p = o.getEnclosedNode();
                    if (p && p.type == CKEDITOR.NODE_ELEMENT && p.getName()in f) {
                        o.setStartAt(p, CKEDITOR.POSITION_AFTER_START);
                        o.setEndAt(p, CKEDITOR.POSITION_BEFORE_END);
                        s = p
                    }
                }
                if (s && o.startContainer.type == CKEDITOR.NODE_ELEMENT && o.startContainer.getName()in f) {
                    p = new CKEDITOR.dom.walker(o);
                    p.evaluator = a;
                    o.startContainer = p.next()
                }
                if (s && o.endContainer.type == CKEDITOR.NODE_ELEMENT && o.endContainer.getName()in f) {
                    p = new CKEDITOR.dom.walker(o);
                    p.evaluator = a;
                    o.endContainer =
                        p.previous()
                }
                if (s) {
                    var p = s.getFirst(a), t = !!p.getNext(a), z = o.startContainer;
                    (!p.equals(z) && !p.contains(z) || !(k.name == "indent" || k.useIndentClasses || parseInt(s.getStyle(c(s)), 10)) || !j(s, !t && p.getDirection())) && i(s)
                } else e()
            }
            CKEDITOR.dom.element.clearAllMarkers(m);
            b.forceNextSelectionCheck();
            n.selectBookmarks(l)
        }};
        CKEDITOR.plugins.add("indent", {requires: "list", onLoad: function () {
            (CKEDITOR.env.ie6Compat || CKEDITOR.env.ie7Compat) && CKEDITOR.addCss(".cke_editable ul,.cke_editable ol{\tmargin-left: 0px;\tpadding-left: 40px;}")
        },
            init: function (a) {
                if (!a.blockless) {
                    a.addCommand("indent", new b(a, "indent"));
                    a.addCommand("outdent", new b(a, "outdent"));
                    if (a.ui.addButton) {
                        a.ui.addButton("Indent", {label: a.lang.indent.indent, command: "indent", directional: true, toolbar: "indent,20"});
                        a.ui.addButton("Outdent", {label: a.lang.indent.outdent, command: "outdent", directional: true, toolbar: "indent,10"})
                    }
                    a.on("dirChanged", function (b) {
                        var c = a.createRange();
                        c.setStartBefore(b.data.node);
                        c.setEndAfter(b.data.node);
                        for (var f = new CKEDITOR.dom.walker(c),
                                 g; g = f.next();)if (g.type == CKEDITOR.NODE_ELEMENT)if (!g.equals(b.data.node) && g.getDirection()) {
                            c.setStartAfter(g);
                            f = new CKEDITOR.dom.walker(c)
                        } else {
                            var h = a.config.indentClasses;
                            if (h)for (var n = b.data.dir == "ltr" ? ["_rtl", ""] : ["", "_rtl"], l = 0; l < h.length; l++)if (g.hasClass(h[l] + n[0])) {
                                g.removeClass(h[l] + n[0]);
                                g.addClass(h[l] + n[1])
                            }
                            h = g.getStyle("margin-right");
                            n = g.getStyle("margin-left");
                            h ? g.setStyle("margin-left", h) : g.removeStyle("margin-left");
                            n ? g.setStyle("margin-right", n) : g.removeStyle("margin-right")
                        }
                    })
                }
            }})
    }(),
        function () {
            function b(a, b, c) {
                c = a.config.forceEnterMode || c;
                if (a.mode != "wysiwyg")return false;
                if (!b)b = a.config.enterMode;
                if (!a.elementPath().isContextFor("p")) {
                    b = CKEDITOR.ENTER_BR;
                    c = 1
                }
                a.fire("saveSnapshot");
                b == CKEDITOR.ENTER_BR ? g(a, b, null, c) : d(a, b, null, c);
                a.fire("saveSnapshot");
                return true
            }

            function c(a) {
                for (var a = a.getSelection().getRanges(true), b = a.length - 1; b > 0; b--)a[b].deleteContents();
                return a[0]
            }

            CKEDITOR.plugins.add("enterkey", {requires: "indent", init: function (a) {
                a.addCommand("enter", {modes: {wysiwyg: 1},
                    editorFocus: false, exec: function (a) {
                        b(a)
                    }});
                a.addCommand("shiftEnter", {modes: {wysiwyg: 1}, editorFocus: false, exec: function (a) {
                    a.mode == "wysiwyg" && b(a, a.config.shiftEnterMode, 1)
                }});
                a.setKeystroke([
                    [13, "enter"],
                    [CKEDITOR.SHIFT + 13, "shiftEnter"]
                ])
            }});
            var a = CKEDITOR.dom.walker.whitespaces(), f = CKEDITOR.dom.walker.bookmark();
            CKEDITOR.plugins.enterkey = {enterBlock: function (b, d, h, m) {
                if (h = h || c(b)) {
                    var n = h.document, l = h.checkStartOfBlock(), o = h.checkEndOfBlock(), q = b.elementPath(h.startContainer).block;
                    if (l && o) {
                        if (q &&
                            (q.is("li") || q.getParent().is("li"))) {
                            b.execCommand("outdent");
                            return
                        }
                        if (q && q.getParent().is("blockquote")) {
                            q.breakParent(q.getParent());
                            q.getPrevious().getFirst(CKEDITOR.dom.walker.invisible(1)) || q.getPrevious().remove();
                            q.getNext().getFirst(CKEDITOR.dom.walker.invisible(1)) || q.getNext().remove();
                            h.moveToElementEditStart(q);
                            h.select();
                            return
                        }
                    } else if (q && q.is("pre") && !o) {
                        g(b, d, h, m);
                        return
                    }
                    var q = d == CKEDITOR.ENTER_DIV ? "div" : "p", s = h.splitBlock(q);
                    if (s) {
                        var d = s.previousBlock, b = s.nextBlock, l = s.wasStartOfBlock,
                            o = s.wasEndOfBlock, p;
                        if (b) {
                            p = b.getParent();
                            if (p.is("li")) {
                                b.breakParent(p);
                                b.move(b.getNext(), 1)
                            }
                        } else if (d && (p = d.getParent()) && p.is("li")) {
                            d.breakParent(p);
                            p = d.getNext();
                            h.moveToElementEditStart(p);
                            d.move(d.getPrevious())
                        }
                        if (!l && !o) {
                            if (b.is("li")) {
                                var t = h.clone();
                                t.selectNodeContents(b);
                                t = new CKEDITOR.dom.walker(t);
                                t.evaluator = function (b) {
                                    return!(f(b) || a(b) || b.type == CKEDITOR.NODE_ELEMENT && b.getName()in CKEDITOR.dtd.$inline && !(b.getName()in CKEDITOR.dtd.$empty))
                                };
                                (p = t.next()) && (p.type == CKEDITOR.NODE_ELEMENT &&
                                    p.is("ul", "ol")) && (CKEDITOR.env.ie ? n.createText(" ") : n.createElement("br")).insertBefore(p)
                            }
                            b && h.moveToElementEditStart(b)
                        } else {
                            var z;
                            if (d) {
                                if (d.is("li") || !i.test(d.getName()) && !d.is("pre"))t = d.clone()
                            } else b && (t = b.clone());
                            if (t)m && !t.is("li") && t.renameNode(q); else if (p && p.is("li"))t = p; else {
                                t = n.createElement(q);
                                d && (z = d.getDirection()) && t.setAttribute("dir", z)
                            }
                            if (n = s.elementPath) {
                                m = 0;
                                for (p = n.elements.length; m < p; m++) {
                                    z = n.elements[m];
                                    if (z.equals(n.block) || z.equals(n.blockLimit))break;
                                    if (CKEDITOR.dtd.$removeEmpty[z.getName()]) {
                                        z =
                                            z.clone();
                                        t.moveChildren(z);
                                        t.append(z)
                                    }
                                }
                            }
                            CKEDITOR.env.ie || t.appendBogus();
                            t.getParent() || h.insertNode(t);
                            t.is("li") && t.removeAttribute("value");
                            if (CKEDITOR.env.ie && l && (!o || !d.getChildCount())) {
                                h.moveToElementEditStart(o ? d : t);
                                h.select()
                            }
                            h.moveToElementEditStart(l && !o ? b : t)
                        }
                        h.select();
                        h.scrollIntoView()
                    }
                }
            }, enterBr: function (a, b, f, g) {
                if (f = f || c(a)) {
                    var h = f.document, l = f.checkEndOfBlock(), o = new CKEDITOR.dom.elementPath(a.getSelection().getStartElement()), q = o.block, o = q && o.block.getName();
                    if (!g && o == "li")d(a,
                        b, f, g); else {
                        if (!g && l && i.test(o))if (l = q.getDirection()) {
                            h = h.createElement("div");
                            h.setAttribute("dir", l);
                            h.insertAfter(q);
                            f.setStart(h, 0)
                        } else {
                            h.createElement("br").insertAfter(q);
                            CKEDITOR.env.gecko && h.createText("").insertAfter(q);
                            f.setStartAt(q.getNext(), CKEDITOR.env.ie ? CKEDITOR.POSITION_BEFORE_START : CKEDITOR.POSITION_AFTER_START)
                        } else {
                            q = o == "pre" && CKEDITOR.env.ie && CKEDITOR.env.version < 8 ? h.createText("\r") : h.createElement("br");
                            f.deleteContents();
                            f.insertNode(q);
                            if (CKEDITOR.env.ie)f.setStartAt(q,
                                CKEDITOR.POSITION_AFTER_END); else {
                                h.createText("﻿").insertAfter(q);
                                l && q.getParent().appendBogus();
                                q.getNext().$.nodeValue = "";
                                f.setStartAt(q.getNext(), CKEDITOR.POSITION_AFTER_START)
                            }
                        }
                        f.collapse(true);
                        f.select();
                        f.scrollIntoView()
                    }
                }
            }};
            var h = CKEDITOR.plugins.enterkey, g = h.enterBr, d = h.enterBlock, i = /^h[1-6]$/
        }(), function () {
        function b(b, a) {
            var f = {}, h = [], g = {nbsp: " ", shy: "­", gt: ">", lt: "<", amp: "&", apos: "'", quot: '"'}, b = b.replace(/\b(nbsp|shy|gt|lt|amp|apos|quot)(?:,|$)/g, function (b, d) {
                var c = a ? "&" + d + ";" : g[d];
                f[c] = a ? g[d] : "&" + d + ";";
                h.push(c);
                return""
            });
            if (!a && b) {
                var b = b.split(","), d = document.createElement("div"), i;
                d.innerHTML = "&" + b.join(";&") + ";";
                i = d.innerHTML;
                d = null;
                for (d = 0; d < i.length; d++) {
                    var e = i.charAt(d);
                    f[e] = "&" + b[d] + ";";
                    h.push(e)
                }
            }
            f.regex = h.join(a ? "|" : "");
            return f
        }

        CKEDITOR.plugins.add("entities", {afterInit: function (c) {
            var a = c.config;
            if (c = (c = c.dataProcessor) && c.htmlFilter) {
                var f = [];
                a.basicEntities !== false && f.push("nbsp,gt,lt,amp");
                if (a.entities) {
                    f.length && f.push("quot,iexcl,cent,pound,curren,yen,brvbar,sect,uml,copy,ordf,laquo,not,shy,reg,macr,deg,plusmn,sup2,sup3,acute,micro,para,middot,cedil,sup1,ordm,raquo,frac14,frac12,frac34,iquest,times,divide,fnof,bull,hellip,prime,Prime,oline,frasl,weierp,image,real,trade,alefsym,larr,uarr,rarr,darr,harr,crarr,lArr,uArr,rArr,dArr,hArr,forall,part,exist,empty,nabla,isin,notin,ni,prod,sum,minus,lowast,radic,prop,infin,ang,and,or,cap,cup,int,there4,sim,cong,asymp,ne,equiv,le,ge,sub,sup,nsub,sube,supe,oplus,otimes,perp,sdot,lceil,rceil,lfloor,rfloor,lang,rang,loz,spades,clubs,hearts,diams,circ,tilde,ensp,emsp,thinsp,zwnj,zwj,lrm,rlm,ndash,mdash,lsquo,rsquo,sbquo,ldquo,rdquo,bdquo,dagger,Dagger,permil,lsaquo,rsaquo,euro");
                    a.entities_latin && f.push("Agrave,Aacute,Acirc,Atilde,Auml,Aring,AElig,Ccedil,Egrave,Eacute,Ecirc,Euml,Igrave,Iacute,Icirc,Iuml,ETH,Ntilde,Ograve,Oacute,Ocirc,Otilde,Ouml,Oslash,Ugrave,Uacute,Ucirc,Uuml,Yacute,THORN,szlig,agrave,aacute,acirc,atilde,auml,aring,aelig,ccedil,egrave,eacute,ecirc,euml,igrave,iacute,icirc,iuml,eth,ntilde,ograve,oacute,ocirc,otilde,ouml,oslash,ugrave,uacute,ucirc,uuml,yacute,thorn,yuml,OElig,oelig,Scaron,scaron,Yuml");
                    a.entities_greek && f.push("Alpha,Beta,Gamma,Delta,Epsilon,Zeta,Eta,Theta,Iota,Kappa,Lambda,Mu,Nu,Xi,Omicron,Pi,Rho,Sigma,Tau,Upsilon,Phi,Chi,Psi,Omega,alpha,beta,gamma,delta,epsilon,zeta,eta,theta,iota,kappa,lambda,mu,nu,xi,omicron,pi,rho,sigmaf,sigma,tau,upsilon,phi,chi,psi,omega,thetasym,upsih,piv");
                    a.entities_additional && f.push(a.entities_additional)
                }
                var h = b(f.join(",")), g = h.regex ? "[" + h.regex + "]" : "a^";
                delete h.regex;
                a.entities && a.entities_processNumerical && (g = "[^ -~]|" + g);
                var g = RegExp(g, "g"), d = function (b) {
                    return a.entities_processNumerical == "force" || !h[b] ? "&#" + b.charCodeAt(0) + ";" : h[b]
                }, i = b("nbsp,gt,lt,amp,shy", true), e = RegExp(i.regex, "g"), j = function (a) {
                    return i[a]
                };
                c.addRules({text: function (a) {
                    return a.replace(e, j).replace(g, d)
                }})
            }
        }})
    }(), CKEDITOR.config.basicEntities = !0, CKEDITOR.config.entities = !0, CKEDITOR.config.entities_latin = !0, CKEDITOR.config.entities_greek = !0, CKEDITOR.config.entities_additional = "#39", CKEDITOR.plugins.add("popup"), CKEDITOR.tools.extend(CKEDITOR.editor.prototype, {popup: function (b, c, a, f) {
        c = c || "80%";
        a = a || "70%";
        typeof c == "string" && (c.length > 1 && c.substr(c.length - 1, 1) == "%") && (c = parseInt(window.screen.width * parseInt(c, 10) / 100, 10));
        typeof a == "string" && (a.length > 1 && a.substr(a.length - 1, 1) == "%") && (a = parseInt(window.screen.height * parseInt(a, 10) / 100, 10));
        c < 640 && (c = 640);
        a < 420 && (a =
            420);
        var h = parseInt((window.screen.height - a) / 2, 10), g = parseInt((window.screen.width - c) / 2, 10), f = (f || "location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes") + ",width=" + c + ",height=" + a + ",top=" + h + ",left=" + g, d = window.open("", null, f, true);
        if (!d)return false;
        try {
            if (navigator.userAgent.toLowerCase().indexOf(" chrome/") == -1) {
                d.moveTo(g, h);
                d.resizeTo(c, a)
            }
            d.focus();
            d.location.href = b
        } catch (i) {
            window.open(b, null, f, true)
        }
        return true
    }}), function () {
        function b(a, b) {
            var d = [];
            if (b)for (var c in b)d.push(c + "=" + encodeURIComponent(b[c])); else return a;
            return a + (a.indexOf("?") != -1 ? "&" : "?") + d.join("&")
        }

        function c(a) {
            a = a + "";
            return a.charAt(0).toUpperCase() + a.substr(1)
        }

        function a() {
            var a = this.getDialog(), d = a.getParentEditor();
            d._.filebrowserSe = this;
            var f = d.config["filebrowser" + c(a.getName()) + "WindowWidth"] || d.config.filebrowserWindowWidth || "80%", a = d.config["filebrowser" + c(a.getName()) + "WindowHeight"] || d.config.filebrowserWindowHeight || "70%", g = this.filebrowser.params ||
            {};
            g.CKEditor = d.name;
            g.CKEditorFuncNum = d._.filebrowserFn;
            if (!g.langCode)g.langCode = d.langCode;
            g = b(this.filebrowser.url, g);
            d.popup(g, f, a, d.config.filebrowserWindowFeatures || d.config.fileBrowserWindowFeatures)
        }

        function f() {
            var a = this.getDialog();
            a.getParentEditor()._.filebrowserSe = this;
            return!a.getContentElement(this["for"][0], this["for"][1]).getInputElement().$.value || !a.getContentElement(this["for"][0], this["for"][1]).getAction() ? false : true
        }

        function h(a, d, c) {
            var f = c.params || {};
            f.CKEditor = a.name;
            f.CKEditorFuncNum =
                a._.filebrowserFn;
            if (!f.langCode)f.langCode = a.langCode;
            d.action = b(c.url, f);
            d.filebrowser = c
        }

        function g(b, d, i, m) {
            if (m && m.length)for (var n, l = m.length; l--;) {
                n = m[l];
                (n.type == "hbox" || n.type == "vbox" || n.type == "fieldset") && g(b, d, i, n.children);
                if (n.filebrowser) {
                    if (typeof n.filebrowser == "string")n.filebrowser = {action: n.type == "fileButton" ? "QuickUpload" : "Browse", target: n.filebrowser};
                    if (n.filebrowser.action == "Browse") {
                        var o = n.filebrowser.url;
                        if (o === void 0) {
                            o = b.config["filebrowser" + c(d) + "BrowseUrl"];
                            if (o === void 0)o =
                                b.config.filebrowserBrowseUrl
                        }
                        if (o) {
                            n.onClick = a;
                            n.filebrowser.url = o;
                            n.hidden = false
                        }
                    } else if (n.filebrowser.action == "QuickUpload" && n["for"]) {
                        o = n.filebrowser.url;
                        if (o === void 0) {
                            o = b.config["filebrowser" + c(d) + "UploadUrl"];
                            if (o === void 0)o = b.config.filebrowserUploadUrl
                        }
                        if (o) {
                            var q = n.onClick;
                            n.onClick = function (a) {
                                var b = a.sender;
                                return q && q.call(b, a) === false ? false : f.call(b, a)
                            };
                            n.filebrowser.url = o;
                            n.hidden = false;
                            h(b, i.getContents(n["for"][0]).get(n["for"][1]), n.filebrowser)
                        }
                    }
                }
            }
        }

        function d(a, b, c) {
            if (c.indexOf(";") !== -1) {
                for (var c = c.split(";"), f = 0; f < c.length; f++)if (d(a, b, c[f]))return true;
                return false
            }
            return(a = a.getContents(b).get(c).filebrowser) && a.url
        }

        function i(a, b) {
            var d = this._.filebrowserSe.getDialog(), c = this._.filebrowserSe["for"], f = this._.filebrowserSe.filebrowser.onSelect;
            c && d.getContentElement(c[0], c[1]).reset();
            if (!(typeof b == "function" && b.call(this._.filebrowserSe) === false) && !(f && f.call(this._.filebrowserSe, a, b) === false)) {
                typeof b == "string" && b && alert(b);
                if (a) {
                    c = this._.filebrowserSe;
                    d = c.getDialog();
                    if (c = c.filebrowser.target || null) {
                        c = c.split(":");
                        if (f = d.getContentElement(c[0], c[1])) {
                            f.setValue(a);
                            d.selectPage(c[0])
                        }
                    }
                }
            }
        }

        CKEDITOR.plugins.add("filebrowser", {requires: "popup", init: function (a) {
            a._.filebrowserFn = CKEDITOR.tools.addFunction(i, a);
            a.on("destroy", function () {
                CKEDITOR.tools.removeFunction(this._.filebrowserFn)
            })
        }});
        CKEDITOR.on("dialogDefinition", function (a) {
            for (var b = a.data.definition, c, f = 0; f < b.contents.length; ++f)if (c = b.contents[f]) {
                g(a.editor, a.data.name, b, c.elements);
                if (c.hidden && c.filebrowser)c.hidden = !d(b, c.id, c.filebrowser)
            }
        })
    }(), CKEDITOR.plugins.add("find", {requires: "dialog", init: function (b) {
        var c = b.addCommand("find", new CKEDITOR.dialogCommand("find"));
        c.canUndo = false;
        c.readOnly = 1;
        b.addCommand("replace", new CKEDITOR.dialogCommand("replace")).canUndo = false;
        if (b.ui.addButton) {
            b.ui.addButton("Find", {label: b.lang.find.find, command: "find", toolbar: "find,10"});
            b.ui.addButton("Replace", {label: b.lang.find.replace, command: "replace", toolbar: "find,20"})
        }
        CKEDITOR.dialog.add("find", this.path + "dialogs/find.js");
        CKEDITOR.dialog.add("replace", this.path + "dialogs/find.js")
    }}), CKEDITOR.config.find_highlight = {element: "span", styles: {"background-color": "#004", color: "#fff"}}, function () {
        function b(a, b) {
            var c = f.exec(a), g = f.exec(b);
            if (c) {
                if (!c[2] && g[2] == "px")return g[1];
                if (c[2] == "px" && !g[2])return g[1] + "px"
            }
            return b
        }

        var c = CKEDITOR.htmlParser.cssStyle, a = CKEDITOR.tools.cssLength, f = /^((?:\d*(?:\.\d+))|(?:\d+))(.*)?$/i, h = {elements: {$: function (a) {
            var f = a.attributes;
            if ((f = (f = (f = f && f["data-cke-realelement"]) && new CKEDITOR.htmlParser.fragment.fromHtml(decodeURIComponent(f))) &&
                f.children[0]) && a.attributes["data-cke-resizable"]) {
                var e = (new c(a)).rules, a = f.attributes, g = e.width, e = e.height;
                g && (a.width = b(a.width, g));
                e && (a.height = b(a.height, e))
            }
            return f
        }}}, g = CKEDITOR.plugins.add("fakeobjects", {afterInit: function (a) {
            (a = (a = a.dataProcessor) && a.htmlFilter) && a.addRules(h)
        }});
        CKEDITOR.editor.prototype.createFakeElement = function (b, f, e, h) {
            var k = this.lang.fakeobjects, k = k[e] || k.unknown, f = {"class": f, "data-cke-realelement": encodeURIComponent(b.getOuterHtml()), "data-cke-real-node-type": b.type,
                alt: k, title: k, align: b.getAttribute("align") || ""};
            if (!CKEDITOR.env.hc)f.src = CKEDITOR.getUrl(g.path + "images/spacer.gif");
            e && (f["data-cke-real-element-type"] = e);
            if (h) {
                f["data-cke-resizable"] = h;
                e = new c;
                h = b.getAttribute("width");
                b = b.getAttribute("height");
                h && (e.rules.width = a(h));
                b && (e.rules.height = a(b));
                e.populate(f)
            }
            return this.document.createElement("img", {attributes: f})
        };
        CKEDITOR.editor.prototype.createFakeParserElement = function (b, f, e, h) {
            var k = this.lang.fakeobjects, k = k[e] || k.unknown, m;
            m = new CKEDITOR.htmlParser.basicWriter;
            b.writeHtml(m);
            m = m.getHtml();
            f = {"class": f, "data-cke-realelement": encodeURIComponent(m), "data-cke-real-node-type": b.type, alt: k, title: k, align: b.attributes.align || ""};
            if (!CKEDITOR.env.hc)f.src = CKEDITOR.getUrl(g.path + "images/spacer.gif");
            e && (f["data-cke-real-element-type"] = e);
            if (h) {
                f["data-cke-resizable"] = h;
                h = b.attributes;
                b = new c;
                e = h.width;
                h = h.height;
                e != void 0 && (b.rules.width = a(e));
                h != void 0 && (b.rules.height = a(h));
                b.populate(f)
            }
            return new CKEDITOR.htmlParser.element("img", f)
        };
        CKEDITOR.editor.prototype.restoreRealElement =
            function (a) {
                if (a.data("cke-real-node-type") != CKEDITOR.NODE_ELEMENT)return null;
                var c = CKEDITOR.dom.element.createFromHtml(decodeURIComponent(a.data("cke-realelement")), this.document);
                if (a.data("cke-resizable")) {
                    var e = a.getStyle("width"), a = a.getStyle("height");
                    e && c.setAttribute("width", b(c.getAttribute("width"), e));
                    a && c.setAttribute("height", b(c.getAttribute("height"), a))
                }
                return c
            }
    }(), function () {
        function b(b) {
            b = b.attributes;
            return b.type == "application/x-shockwave-flash" || a.test(b.src || "")
        }

        function c(a, b) {
            return a.createFakeParserElement(b, "cke_flash", "flash", true)
        }

        var a = /\.swf(?:$|\?)/i;
        CKEDITOR.plugins.add("flash", {requires: "dialog,fakeobjects", onLoad: function () {
            CKEDITOR.addCss("img.cke_flash{background-image: url(" + CKEDITOR.getUrl(this.path + "images/placeholder.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}")
        }, init: function (a) {
            var b = "object[classid,codebase,height,hspace,vspace,width];param[name,value];embed[height,hspace,pluginspage,src,type,vspace,width]";
            CKEDITOR.dialog.isTabEnabled(a, "flash", "properties") && (b = b + ";object[align]; embed[allowscriptaccess,quality,scale,wmode]");
            CKEDITOR.dialog.isTabEnabled(a, "flash", "advanced") && (b = b + ";object[id]{*}; embed[bgcolor]{*}(*)");
            a.addCommand("flash", new CKEDITOR.dialogCommand("flash", {allowedContent: b, requiredContent: "embed"}));
            a.ui.addButton && a.ui.addButton("Flash", {label: a.lang.common.flash, command: "flash", toolbar: "insert,20"});
            CKEDITOR.dialog.add("flash", this.path + "dialogs/flash.js");
            a.addMenuItems && a.addMenuItems({flash: {label: a.lang.flash.properties,
                command: "flash", group: "flash"}});
            a.on("doubleclick", function (a) {
                var b = a.data.element;
                if (b.is("img") && b.data("cke-real-element-type") == "flash")a.data.dialog = "flash"
            });
            a.contextMenu && a.contextMenu.addListener(function (a) {
                if (a && a.is("img") && !a.isReadOnly() && a.data("cke-real-element-type") == "flash")return{flash: CKEDITOR.TRISTATE_OFF}
            })
        }, afterInit: function (a) {
            var h = a.dataProcessor;
            (h = h && h.dataFilter) && h.addRules({elements: {"cke:object": function (g) {
                var d = g.attributes;
                if ((!d.classid || !("" + d.classid).toLowerCase()) && !b(g)) {
                    for (d = 0; d < g.children.length; d++)if (g.children[d].name == "cke:embed") {
                        if (!b(g.children[d]))break;
                        return c(a, g)
                    }
                    return null
                }
                return c(a, g)
            }, "cke:embed": function (g) {
                return!b(g) ? null : c(a, g)
            }}}, 5)
        }})
    }(), CKEDITOR.tools.extend(CKEDITOR.config, {flashEmbedTagOnly: !1, flashAddEmbedTag: !0, flashConvertOnEdit: !1}), function () {
        function b(a) {
            var b = a == "left" ? "pageXOffset" : "pageYOffset";
            return b in f.$ ? f.$[b] : CKEDITOR.document.$.documentElement[a == "left" ? "scrollLeft" : "scrollTop"]
        }

        function c(c) {
            var d, i = c.config,
                e = i.floatSpaceDockedOffsetX || 0, j = i.floatSpaceDockedOffsetY || 0, k = i.floatSpacePinnedOffsetX || 0, m = i.floatSpacePinnedOffsetY || 0, n = function (a) {
                    function i(a, b, d) {
                        q.setStyle(b, h(d));
                        q.setStyle("position", a)
                    }

                    function l(a) {
                        var b = o.getDocumentPosition();
                        switch (a) {
                            case "top":
                                i("absolute", "top", b.y - v - j);
                                break;
                            case "pin":
                                i("fixed", "top", m);
                                break;
                            case "bottom":
                                i("absolute", "top", b.y + (w.height || w.bottom - w.top) + j)
                        }
                        d = a
                    }

                    var o = c.editable();
                    if (o) {
                        a.name == "focus" && q.show();
                        q.removeStyle("left");
                        q.removeStyle("right");
                        var x = q.getClientRect(), w = o.getClientRect(), v = x.height, r = b("left");
                        if (d) {
                            d == "top" && x.top < m ? l("pin") : d == "pin" ? w.top > j + v ? l("top") : w.bottom - x.bottom < v && l("bottom") : d == "bottom" && (w.top > j + v ? l("top") : w.bottom > 2 * v + m && l("pin"));
                            var a = f.getViewPaneSize(), u = a.width / 2, u = w.left > 0 && w.right < a.width && w.width > x.width ? c.config.contentsLangDirection == "rtl" ? "right" : "left" : u - w.left > w.right - u ? "left" : "right", A;
                            if (x.width > a.width) {
                                u = "left";
                                A = 0
                            } else {
                                A = u == "left" ? w.left > 0 ? w.left : 0 : w.right < a.width ? a.width - w.right : 0;
                                if (A + x.width >
                                    a.width) {
                                    u = u == "left" ? "right" : "left";
                                    A = 0
                                }
                            }
                            q.setStyle(u, h((d == "pin" ? k : e) + A + (d == "pin" ? 0 : u == "left" ? r : -r)))
                        } else {
                            d = "pin";
                            l("pin");
                            n(a)
                        }
                    }
                }, i = CKEDITOR.document.getBody(), l = {id: c.id, name: c.name, langDir: c.lang.dir, langCode: c.langCode}, o = c.fire("uiSpace", {space: "top", html: ""}).html;
            if (o) {
                var q = i.append(CKEDITOR.dom.element.createFromHtml(a.output(CKEDITOR.tools.extend({topId: c.ui.spaceId("top"), content: o, style: "display:none;z-index:" + (c.config.baseFloatZIndex - 1)}, l))));
                q.unselectable();
                q.on("mousedown", function (a) {
                    a =
                        a.data;
                    a.getTarget().hasAscendant("a", 1) || a.preventDefault()
                });
                c.on("focus", function (a) {
                    n(a);
                    f.on("scroll", n);
                    f.on("resize", n)
                });
                c.on("blur", function () {
                    q.hide();
                    f.removeListener("scroll", n);
                    f.removeListener("resize", n)
                });
                c.on("destroy", function () {
                    f.removeListener("scroll", n);
                    f.removeListener("resize", n);
                    q.clearCustomData();
                    q.remove()
                });
                c.focusManager.hasFocus && q.show();
                c.focusManager.add(q, 1)
            }
        }

        var a = CKEDITOR.addTemplate("floatcontainer", '<div id="cke_{name}" class="cke {id} cke_reset_all cke_chrome cke_editor_{name} cke_float cke_{langDir} ' +
            CKEDITOR.env.cssClass + '" dir="{langDir}" title="' + (CKEDITOR.env.gecko ? " " : "") + '" lang="{langCode}" role="application" style="{style}"><div class="cke_inner"><div id="{topId}" class="cke_top" role="presentation">{content}</div></div></div>');
        CKEDITOR.plugins.add("floatingspace", {init: function (a) {
            a.on("loaded", function () {
                c(a)
            }, null, null, 20)
        }});
        var f = CKEDITOR.document.getWindow(), h = CKEDITOR.tools.cssLength
    }(), CKEDITOR.plugins.add("listblock", {requires: "panel", onLoad: function () {
        var b = CKEDITOR.addTemplate("panel-list",
            '<ul role="presentation" class="cke_panel_list">{items}</ul>'), c = CKEDITOR.addTemplate("panel-list-item", '<li id="{id}" class="cke_panel_listItem" role=presentation><a id="{id}_option" _cke_focus=1 hidefocus=true title="{title}" href="javascript:void(\'{val}\')"  {onclick}="CKEDITOR.tools.callFunction({clickFn},\'{val}\'); return false;" role="option">{text}</a></li>'), a = CKEDITOR.addTemplate("panel-list-group", '<h1 id="{id}" class="cke_panel_grouptitle" role="presentation" >{label}</h1>');
        CKEDITOR.ui.panel.prototype.addListBlock =
            function (a, b) {
                return this.addBlock(a, new CKEDITOR.ui.listBlock(this.getHolderElement(), b))
            };
        CKEDITOR.ui.listBlock = CKEDITOR.tools.createClass({base: CKEDITOR.ui.panel.block, $: function (a, b) {
            var b = b || {}, c = b.attributes || (b.attributes = {});
            (this.multiSelect = !!b.multiSelect) && (c["aria-multiselectable"] = true);
            !c.role && (c.role = "listbox");
            this.base.apply(this, arguments);
            c = this.keys;
            c[40] = "next";
            c[9] = "next";
            c[38] = "prev";
            c[CKEDITOR.SHIFT + 9] = "prev";
            c[32] = CKEDITOR.env.ie ? "mouseup" : "click";
            CKEDITOR.env.ie && (c[13] =
                "mouseup");
            this._.pendingHtml = [];
            this._.pendingList = [];
            this._.items = {};
            this._.groups = {}
        }, _: {close: function () {
            if (this._.started) {
                var a = b.output({items: this._.pendingList.join("")});
                this._.pendingList = [];
                this._.pendingHtml.push(a);
                delete this._.started
            }
        }, getClick: function () {
            if (!this._.click)this._.click = CKEDITOR.tools.addFunction(function (a) {
                var b = this.toggle(a);
                if (this.onClick)this.onClick(a, b)
            }, this);
            return this._.click
        }}, proto: {add: function (a, b, g) {
            var d = CKEDITOR.tools.getNextId();
            if (!this._.started) {
                this._.started =
                    1;
                this._.size = this._.size || 0
            }
            this._.items[a] = d;
            a = {id: d, val: a, onclick: CKEDITOR.env.ie ? 'onclick="return false;" onmouseup' : "onclick", clickFn: this._.getClick(), title: g || a, text: b || a};
            this._.pendingList.push(c.output(a))
        }, startGroup: function (b) {
            this._.close();
            var c = CKEDITOR.tools.getNextId();
            this._.groups[b] = c;
            this._.pendingHtml.push(a.output({id: c, label: b}))
        }, commit: function () {
            this._.close();
            this.element.appendHtml(this._.pendingHtml.join(""));
            delete this._.size;
            this._.pendingHtml = []
        }, toggle: function (a) {
            var b =
                this.isMarked(a);
            b ? this.unmark(a) : this.mark(a);
            return!b
        }, hideGroup: function (a) {
            var b = (a = this.element.getDocument().getById(this._.groups[a])) && a.getNext();
            if (a) {
                a.setStyle("display", "none");
                b && b.getName() == "ul" && b.setStyle("display", "none")
            }
        }, hideItem: function (a) {
            this.element.getDocument().getById(this._.items[a]).setStyle("display", "none")
        }, showAll: function () {
            var a = this._.items, b = this._.groups, c = this.element.getDocument(), d;
            for (d in a)c.getById(a[d]).setStyle("display", "");
            for (var i in b) {
                a = c.getById(b[i]);
                d = a.getNext();
                a.setStyle("display", "");
                d && d.getName() == "ul" && d.setStyle("display", "")
            }
        }, mark: function (a) {
            this.multiSelect || this.unmarkAll();
            var a = this._.items[a], b = this.element.getDocument().getById(a);
            b.addClass("cke_selected");
            this.element.getDocument().getById(a + "_option").setAttribute("aria-selected", true);
            this.onMark && this.onMark(b)
        }, unmark: function (a) {
            var b = this.element.getDocument(), a = this._.items[a], c = b.getById(a);
            c.removeClass("cke_selected");
            b.getById(a + "_option").removeAttribute("aria-selected");
            this.onUnmark && this.onUnmark(c)
        }, unmarkAll: function () {
            var a = this._.items, b = this.element.getDocument(), c;
            for (c in a) {
                var d = a[c];
                b.getById(d).removeClass("cke_selected");
                b.getById(d + "_option").removeAttribute("aria-selected")
            }
            this.onUnmark && this.onUnmark()
        }, isMarked: function (a) {
            return this.element.getDocument().getById(this._.items[a]).hasClass("cke_selected")
        }, focus: function (a) {
            this._.focusIndex = -1;
            if (a) {
                for (var b = this.element.getDocument().getById(this._.items[a]).getFirst(), a = this.element.getElementsByTag("a"),
                         c, d = -1; c = a.getItem(++d);)if (c.equals(b)) {
                    this._.focusIndex = d;
                    break
                }
                setTimeout(function () {
                    b.focus()
                }, 0)
            }
        }}})
    }}), CKEDITOR.plugins.add("richcombo", {requires: "floatpanel,listblock,button", beforeInit: function (b) {
        b.ui.addHandler(CKEDITOR.UI_RICHCOMBO, CKEDITOR.ui.richCombo.handler)
    }}), function () {
        var b = '<span id="{id}" class="cke_combo cke_combo__{name} {cls}" role="presentation"><span id="{id}_label" class="cke_combo_label">{label}</span><a class="cke_combo_button" hidefocus=true title="{title}" tabindex="-1"' +
            (CKEDITOR.env.gecko && CKEDITOR.env.version >= 10900 && !CKEDITOR.env.hc ? "" : '" href="javascript:void(\'{titleJs}\')"') + ' hidefocus="true" role="button" aria-labelledby="{id}_label" aria-haspopup="true"';
        if (CKEDITOR.env.opera || CKEDITOR.env.gecko && CKEDITOR.env.mac)b = b + ' onkeypress="return false;"';
        CKEDITOR.env.gecko && (b = b + ' onblur="this.style.cssText = this.style.cssText;"');
        var b = b + (' onkeydown="return CKEDITOR.tools.callFunction({keydownFn},event,this);" onmousedown="return CKEDITOR.tools.callFunction({mousedownFn},event);"  onfocus="return CKEDITOR.tools.callFunction({focusFn},event);" ' +
            (CKEDITOR.env.ie ? 'onclick="return false;" onmouseup' : "onclick") + '="CKEDITOR.tools.callFunction({clickFn},this);return false;"><span id="{id}_text" class="cke_combo_text cke_combo_inlinelabel">{label}</span><span class="cke_combo_open"><span class="cke_combo_arrow">' + (CKEDITOR.env.hc ? "&#9660;" : CKEDITOR.env.air ? "&nbsp;" : "") + "</span></span></a></span>"), c = CKEDITOR.addTemplate("combo", b);
        CKEDITOR.UI_RICHCOMBO = "richcombo";
        CKEDITOR.ui.richCombo = CKEDITOR.tools.createClass({$: function (a) {
            CKEDITOR.tools.extend(this,
                a, {canGroup: false, title: a.label, modes: {wysiwyg: 1}, editorFocus: 1});
            a = this.panel || {};
            delete this.panel;
            this.id = CKEDITOR.tools.getNextNumber();
            this.document = a.parent && a.parent.getDocument() || CKEDITOR.document;
            a.className = "cke_combopanel";
            a.block = {multiSelect: a.multiSelect, attributes: a.attributes};
            a.toolbarRelated = true;
            this._ = {panelDefinition: a, items: {}}
        }, proto: {renderHtml: function (a) {
            var b = [];
            this.render(a, b);
            return b.join("")
        }, render: function (a, b) {
            function h() {
                var b = this.modes[a.mode] ? CKEDITOR.TRISTATE_OFF :
                    CKEDITOR.TRISTATE_DISABLED;
                this.setState(a.readOnly && !this.readOnly ? CKEDITOR.TRISTATE_DISABLED : b);
                this.setValue("")
            }

            var g = CKEDITOR.env, d = "cke_" + this.id, i = CKEDITOR.tools.addFunction(function (b) {
                if (n) {
                    a.unlockSelection(1);
                    n = 0
                }
                j.execute(b)
            }, this), e = this, j = {id: d, combo: this, focus: function () {
                CKEDITOR.document.getById(d).getChild(1).focus()
            }, execute: function (b) {
                var d = e._;
                if (d.state != CKEDITOR.TRISTATE_DISABLED) {
                    e.createPanel(a);
                    if (d.on)d.panel.hide(); else {
                        e.commit();
                        var c = e.getValue();
                        c ? d.list.mark(c) : d.list.unmarkAll();
                        d.panel.showBlock(e.id, new CKEDITOR.dom.element(b), 4)
                    }
                }
            }, clickFn: i};
            a.on("mode", h, this);
            !this.readOnly && a.on("readOnly", h, this);
            var k = CKEDITOR.tools.addFunction(function (a, b) {
                var a = new CKEDITOR.dom.event(a), d = a.getKeystroke();
                switch (d) {
                    case 13:
                    case 32:
                    case 40:
                        CKEDITOR.tools.callFunction(i, b);
                        break;
                    default:
                        j.onkey(j, d)
                }
                a.preventDefault()
            }), m = CKEDITOR.tools.addFunction(function () {
                j.onfocus && j.onfocus()
            }), n = 0, l = CKEDITOR.tools.addFunction(function () {
                if (CKEDITOR.env.opera) {
                    var b = a.editable();
                    if (b.isInline() &&
                        b.hasFocus) {
                        a.lockSelection();
                        n = 1
                    }
                }
            });
            j.keyDownFn = k;
            g = {id: d, name: this.name || this.command, label: this.label, title: this.title, cls: this.className || "", titleJs: g.gecko && g.version >= 10900 && !g.hc ? "" : (this.title || "").replace("'", ""), keydownFn: k, mousedownFn: l, focusFn: m, clickFn: i};
            c.output(g, b);
            if (this.onRender)this.onRender();
            return j
        }, createPanel: function (a) {
            if (!this._.panel) {
                var b = this._.panelDefinition, c = this._.panelDefinition.block, g = b.parent || CKEDITOR.document.getBody(), d = "cke_combopanel__" + this.name, i =
                    new CKEDITOR.ui.floatPanel(a, g, b), e = i.addListBlock(this.id, c), j = this;
                i.onShow = function () {
                    this.element.addClass(d);
                    j.setState(CKEDITOR.TRISTATE_ON);
                    e.focus(!e.multiSelect && j.getValue());
                    j._.on = 1;
                    j.editorFocus && a.focus();
                    if (j.onOpen)j.onOpen()
                };
                i.onHide = function (b) {
                    this.element.removeClass(d);
                    j.setState(j.modes && j.modes[a.mode] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED);
                    j._.on = 0;
                    if (!b && j.onClose)j.onClose()
                };
                i.onEscape = function () {
                    i.hide(1)
                };
                e.onClick = function (a, b) {
                    j.onClick && j.onClick.call(j,
                        a, b);
                    i.hide()
                };
                this._.panel = i;
                this._.list = e;
                i.getBlock(this.id).onHide = function () {
                    j._.on = 0;
                    j.setState(CKEDITOR.TRISTATE_OFF)
                };
                this.init && this.init()
            }
        }, setValue: function (a, b) {
            this._.value = a;
            var c = this.document.getById("cke_" + this.id + "_text");
            if (c) {
                if (!a && !b) {
                    b = this.label;
                    c.addClass("cke_combo_inlinelabel")
                } else c.removeClass("cke_combo_inlinelabel");
                c.setText(typeof b != "undefined" ? b : a)
            }
        }, getValue: function () {
            return this._.value || ""
        }, unmarkAll: function () {
            this._.list.unmarkAll()
        }, mark: function (a) {
            this._.list.mark(a)
        },
            hideItem: function (a) {
                this._.list.hideItem(a)
            }, hideGroup: function (a) {
                this._.list.hideGroup(a)
            }, showAll: function () {
                this._.list.showAll()
            }, add: function (a, b, c) {
                this._.items[a] = c || a;
                this._.list.add(a, b, c)
            }, startGroup: function (a) {
                this._.list.startGroup(a)
            }, commit: function () {
                if (!this._.committed) {
                    this._.list.commit();
                    this._.committed = 1;
                    CKEDITOR.ui.fire("ready", this)
                }
                this._.committed = 1
            }, setState: function (a) {
                if (this._.state != a) {
                    var b = this.document.getById("cke_" + this.id);
                    b.setState(a, "cke_combo");
                    a == CKEDITOR.TRISTATE_DISABLED ?
                        b.setAttribute("aria-disabled", true) : b.removeAttribute("aria-disabled");
                    this._.state = a
                }
            }, enable: function () {
                this._.state == CKEDITOR.TRISTATE_DISABLED && this.setState(this._.lastState)
            }, disable: function () {
                if (this._.state != CKEDITOR.TRISTATE_DISABLED) {
                    this._.lastState = this._.state;
                    this.setState(CKEDITOR.TRISTATE_DISABLED)
                }
            }}, statics: {handler: {create: function (a) {
            return new CKEDITOR.ui.richCombo(a)
        }}}});
        CKEDITOR.ui.prototype.addRichCombo = function (a, b) {
            this.add(a, CKEDITOR.UI_RICHCOMBO, b)
        }
    }(), function () {
        function b(b, a, f, h, g, d, i, e) {
            for (var j = b.config, k = new CKEDITOR.style(i), m = g.split(";"), g = [], n = {}, l = 0; l < m.length; l++) {
                var o = m[l];
                if (o) {
                    var o = o.split("/"), q = {}, s = m[l] = o[0];
                    q[f] = g[l] = o[1] || s;
                    n[s] = new CKEDITOR.style(i, q);
                    n[s]._.definition.name = s
                } else m.splice(l--, 1)
            }
            b.ui.addRichCombo(a, {label: h.label, title: h.panelTitle, toolbar: "styles," + e, allowedContent: k, requiredContent: k, panel: {css: [CKEDITOR.skin.getPath("editor")].concat(j.contentsCss), multiSelect: false, attributes: {"aria-label": h.panelTitle}}, init: function () {
                this.startGroup(h.panelTitle);
                for (var a = 0; a < m.length; a++) {
                    var b = m[a];
                    this.add(b, n[b].buildPreview(), b)
                }
            }, onClick: function (a) {
                b.focus();
                b.fire("saveSnapshot");
                var d = n[a];
                b[this.getValue() == a ? "removeStyle" : "applyStyle"](d);
                b.fire("saveSnapshot")
            }, onRender: function () {
                b.on("selectionChange", function (a) {
                    for (var b = this.getValue(), a = a.data.path.elements, c = 0, e; c < a.length; c++) {
                        e = a[c];
                        for (var f in n)if (n[f].checkElementMatch(e, true)) {
                            f != b && this.setValue(f);
                            return
                        }
                    }
                    this.setValue("", d)
                }, this)
            }})
        }

        CKEDITOR.plugins.add("font", {requires: "richcombo",
            init: function (c) {
                var a = c.config;
                b(c, "Font", "family", c.lang.font, a.font_names, a.font_defaultLabel, a.font_style, 30);
                b(c, "FontSize", "size", c.lang.font.fontSize, a.fontSize_sizes, a.fontSize_defaultLabel, a.fontSize_style, 40)
            }})
    }(), CKEDITOR.config.font_names = "Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif",
        CKEDITOR.config.font_defaultLabel = "", CKEDITOR.config.font_style = {element: "span", styles: {"font-family": "#(family)"}, overrides: [
        {element: "font", attributes: {face: null}}
    ]}, CKEDITOR.config.fontSize_sizes = "8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px", CKEDITOR.config.fontSize_defaultLabel = "", CKEDITOR.config.fontSize_style = {element: "span", styles: {"font-size": "#(size)"}, overrides: [
        {element: "font", attributes: {size: null}}
    ]}, CKEDITOR.plugins.add("format",
        {requires: "richcombo", init: function (b) {
            if (!b.blockless) {
                for (var c = b.config, a = b.lang.format, f = c.format_tags.split(";"), h = {}, g = 0, d = [], i = 0; i < f.length; i++) {
                    var e = f[i], j = new CKEDITOR.style(c["format_" + e]);
                    if (!b.filter.customConfig || b.filter.check(j)) {
                        g++;
                        h[e] = j;
                        h[e]._.enterMode = b.config.enterMode;
                        d.push(j)
                    }
                }
                g !== 0 && b.ui.addRichCombo("Format", {label: a.label, title: a.panelTitle, toolbar: "styles,20", allowedContent: d, panel: {css: [CKEDITOR.skin.getPath("editor")].concat(c.contentsCss), multiSelect: false, attributes: {"aria-label": a.panelTitle}},
                    init: function () {
                        this.startGroup(a.panelTitle);
                        for (var b in h) {
                            var d = a["tag_" + b];
                            this.add(b, h[b].buildPreview(d), d)
                        }
                    }, onClick: function (a) {
                        b.focus();
                        b.fire("saveSnapshot");
                        var a = h[a], d = b.elementPath();
                        b[a.checkActive(d) ? "removeStyle" : "applyStyle"](a);
                        setTimeout(function () {
                            b.fire("saveSnapshot")
                        }, 0)
                    }, onRender: function () {
                        b.on("selectionChange", function (a) {
                            var d = this.getValue(), a = a.data.path, c = !b.readOnly && a.isContextFor("p");
                            this[c ? "enable" : "disable"]();
                            if (c) {
                                for (var e in h)if (h[e].checkActive(a)) {
                                    e !=
                                        d && this.setValue(e, b.lang.format["tag_" + e]);
                                    return
                                }
                                this.setValue("")
                            }
                        }, this)
                    }})
            }
        }}), CKEDITOR.config.format_tags = "p;h1;h2;h3;h4;h5;h6;pre;address;div", CKEDITOR.config.format_p = {element: "p"}, CKEDITOR.config.format_div = {element: "div"}, CKEDITOR.config.format_pre = {element: "pre"}, CKEDITOR.config.format_address = {element: "address"}, CKEDITOR.config.format_h1 = {element: "h1"}, CKEDITOR.config.format_h2 = {element: "h2"}, CKEDITOR.config.format_h3 = {element: "h3"}, CKEDITOR.config.format_h4 = {element: "h4"}, CKEDITOR.config.format_h5 =
    {element: "h5"}, CKEDITOR.config.format_h6 = {element: "h6"}, CKEDITOR.plugins.add("forms", {requires: "dialog,fakeobjects", onLoad: function () {
        CKEDITOR.addCss(".cke_editable form{border: 1px dotted #FF0000;padding: 2px;}\n");
        CKEDITOR.addCss("img.cke_hidden{background-image: url(" + CKEDITOR.getUrl(this.path + "images/hiddenfield.gif") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 16px !important;height: 16px !important;}")
    }, init: function (b) {
        var c = b.lang, a =
            0, f = {email: 1, password: 1, search: 1, tel: 1, text: 1, url: 1}, h = {checkbox: "input[type,name,checked]", radio: "input[type,name,checked]", textfield: "input[type,name,value,size,maxlength]", textarea: "textarea[cols,rows,name]", select: "select[name,size,multiple]; option[value,selected]", button: "input[type,name,value]", form: "form[action,name,id,enctype,target,method]", hiddenfield: "input[type,name,value]", imagebutton: "input[type,alt,src]{width,height,border,border-width,border-style,margin,float}"}, g = {checkbox: "input",
            radio: "input", textfield: "input", textarea: "textarea", select: "select", button: "input", form: "form", hiddenfield: "input", imagebutton: "input"}, d = function (d, e, f) {
            var i = {allowedContent: h[e], requiredContent: g[e]};
            e == "form" && (i.context = "form");
            b.addCommand(e, new CKEDITOR.dialogCommand(e, i));
            b.ui.addButton && b.ui.addButton(d, {label: c.common[d.charAt(0).toLowerCase() + d.slice(1)], command: e, toolbar: "forms," + (a = a + 10)});
            CKEDITOR.dialog.add(e, f)
        }, i = this.path + "dialogs/";
        !b.blockless && d("Form", "form", i + "form.js");
        d("Checkbox",
            "checkbox", i + "checkbox.js");
        d("Radio", "radio", i + "radio.js");
        d("TextField", "textfield", i + "textfield.js");
        d("Textarea", "textarea", i + "textarea.js");
        d("Select", "select", i + "select.js");
        d("Button", "button", i + "button.js");
        var e = CKEDITOR.plugins.get("image");
        e && d("ImageButton", "imagebutton", CKEDITOR.plugins.getPath("image") + "dialogs/image.js");
        d("HiddenField", "hiddenfield", i + "hiddenfield.js");
        if (b.addMenuItems) {
            d = {checkbox: {label: c.forms.checkboxAndRadio.checkboxTitle, command: "checkbox", group: "checkbox"},
                radio: {label: c.forms.checkboxAndRadio.radioTitle, command: "radio", group: "radio"}, textfield: {label: c.forms.textfield.title, command: "textfield", group: "textfield"}, hiddenfield: {label: c.forms.hidden.title, command: "hiddenfield", group: "hiddenfield"}, imagebutton: {label: c.image.titleButton, command: "imagebutton", group: "imagebutton"}, button: {label: c.forms.button.title, command: "button", group: "button"}, select: {label: c.forms.select.title, command: "select", group: "select"}, textarea: {label: c.forms.textarea.title, command: "textarea",
                    group: "textarea"}};
            !b.blockless && (d.form = {label: c.forms.form.menu, command: "form", group: "form"});
            b.addMenuItems(d)
        }
        if (b.contextMenu) {
            !b.blockless && b.contextMenu.addListener(function (a, b, d) {
                if ((a = d.contains("form", 1)) && !a.isReadOnly())return{form: CKEDITOR.TRISTATE_OFF}
            });
            b.contextMenu.addListener(function (a) {
                if (a && !a.isReadOnly()) {
                    var b = a.getName();
                    if (b == "select")return{select: CKEDITOR.TRISTATE_OFF};
                    if (b == "textarea")return{textarea: CKEDITOR.TRISTATE_OFF};
                    if (b == "input") {
                        var d = a.getAttribute("type") ||
                            "text";
                        switch (d) {
                            case "button":
                            case "submit":
                            case "reset":
                                return{button: CKEDITOR.TRISTATE_OFF};
                            case "checkbox":
                                return{checkbox: CKEDITOR.TRISTATE_OFF};
                            case "radio":
                                return{radio: CKEDITOR.TRISTATE_OFF};
                            case "image":
                                return e ? {imagebutton: CKEDITOR.TRISTATE_OFF} : null
                        }
                        if (f[d])return{textfield: CKEDITOR.TRISTATE_OFF}
                    }
                    if (b == "img" && a.data("cke-real-element-type") == "hiddenfield")return{hiddenfield: CKEDITOR.TRISTATE_OFF}
                }
            })
        }
        b.on("doubleclick", function (a) {
            var d = a.data.element;
            if (!b.blockless && d.is("form"))a.data.dialog =
                "form"; else if (d.is("select"))a.data.dialog = "select"; else if (d.is("textarea"))a.data.dialog = "textarea"; else if (d.is("img") && d.data("cke-real-element-type") == "hiddenfield")a.data.dialog = "hiddenfield"; else if (d.is("input")) {
                d = d.getAttribute("type") || "text";
                switch (d) {
                    case "button":
                    case "submit":
                    case "reset":
                        a.data.dialog = "button";
                        break;
                    case "checkbox":
                        a.data.dialog = "checkbox";
                        break;
                    case "radio":
                        a.data.dialog = "radio";
                        break;
                    case "image":
                        a.data.dialog = "imagebutton"
                }
                if (f[d])a.data.dialog = "textfield"
            }
        })
    },
        afterInit: function (b) {
            var c = b.dataProcessor, a = c && c.htmlFilter, c = c && c.dataFilter;
            CKEDITOR.env.ie && a && a.addRules({elements: {input: function (a) {
                var a = a.attributes, b = a.type;
                if (!b)a.type = "text";
                (b == "checkbox" || b == "radio") && a.value == "on" && delete a.value
            }}});
            c && c.addRules({elements: {input: function (a) {
                if (a.attributes.type == "hidden")return b.createFakeParserElement(a, "cke_hidden", "hiddenfield")
            }}})
        }}), CKEDITOR.env.ie && (CKEDITOR.dom.element.prototype.hasAttribute = CKEDITOR.tools.override(CKEDITOR.dom.element.prototype.hasAttribute,
        function (b) {
            return function (c) {
                this.$.attributes.getNamedItem(c);
                if (this.getName() == "input")switch (c) {
                    case "class":
                        return this.$.className.length > 0;
                    case "checked":
                        return!!this.$.checked;
                    case "value":
                        var a = this.getAttribute("type");
                        return a == "checkbox" || a == "radio" ? this.$.value != "on" : this.$.value
                }
                return b.apply(this, arguments)
            }
        })), function () {
        var b = {canUndo: false, exec: function (b) {
            var a = b.document.createElement("hr");
            b.insertElement(a)
        }, allowedContent: "hr", requiredContent: "hr"};
        CKEDITOR.plugins.add("horizontalrule",
            {init: function (c) {
                if (!c.blockless) {
                    c.addCommand("horizontalrule", b);
                    c.ui.addButton && c.ui.addButton("HorizontalRule", {label: c.lang.horizontalrule.toolbar, command: "horizontalrule", toolbar: "insert,40"})
                }
            }})
    }(), CKEDITOR.plugins.add("htmlwriter", {init: function (b) {
        var c = new CKEDITOR.htmlWriter;
        c.forceSimpleAmpersand = b.config.forceSimpleAmpersand;
        c.indentationChars = b.config.dataIndentationChars || "\t";
        b.dataProcessor.writer = c
    }}), CKEDITOR.htmlWriter = CKEDITOR.tools.createClass({base: CKEDITOR.htmlParser.basicWriter,
        $: function () {
            this.base();
            this.indentationChars = "\t";
            this.selfClosingEnd = " />";
            this.lineBreakChars = "\n";
            this.sortAttributes = 1;
            this._.indent = 0;
            this._.indentation = "";
            this._.inPre = 0;
            this._.rules = {};
            var b = CKEDITOR.dtd, c;
            for (c in CKEDITOR.tools.extend({}, b.$nonBodyContent, b.$block, b.$listItem, b.$tableContent))this.setRules(c, {indent: !b[c]["#"], breakBeforeOpen: 1, breakBeforeClose: !b[c]["#"], breakAfterClose: 1, needsSpace: c in b.$block && !(c in{li: 1, dt: 1, dd: 1})});
            this.setRules("br", {breakAfterOpen: 1});
            this.setRules("title",
                {indent: 0, breakAfterOpen: 0});
            this.setRules("style", {indent: 0, breakBeforeClose: 1});
            this.setRules("pre", {breakAfterOpen: 1, indent: 0})
        }, proto: {openTag: function (b) {
            var c = this._.rules[b];
            this._.afterCloser && (c && c.needsSpace && this._.needsSpace) && this._.output.push("\n");
            if (this._.indent)this.indentation(); else if (c && c.breakBeforeOpen) {
                this.lineBreak();
                this.indentation()
            }
            this._.output.push("<", b);
            this._.afterCloser = 0
        }, openTagClose: function (b, c) {
            var a = this._.rules[b];
            if (c) {
                this._.output.push(this.selfClosingEnd);
                if (a && a.breakAfterClose)this._.needsSpace = a.needsSpace
            } else {
                this._.output.push(">");
                if (a && a.indent)this._.indentation = this._.indentation + this.indentationChars
            }
            a && a.breakAfterOpen && this.lineBreak();
            b == "pre" && (this._.inPre = 1)
        }, attribute: function (b, c) {
            if (typeof c == "string") {
                this.forceSimpleAmpersand && (c = c.replace(/&amp;/g, "&"));
                c = CKEDITOR.tools.htmlEncodeAttr(c)
            }
            this._.output.push(" ", b, '="', c, '"')
        }, closeTag: function (b) {
            var c = this._.rules[b];
            if (c && c.indent)this._.indentation = this._.indentation.substr(this.indentationChars.length);
            if (this._.indent)this.indentation(); else if (c && c.breakBeforeClose) {
                this.lineBreak();
                this.indentation()
            }
            this._.output.push("</", b, ">");
            b == "pre" && (this._.inPre = 0);
            if (c && c.breakAfterClose) {
                this.lineBreak();
                this._.needsSpace = c.needsSpace
            }
            this._.afterCloser = 1
        }, text: function (b) {
            if (this._.indent) {
                this.indentation();
                !this._.inPre && (b = CKEDITOR.tools.ltrim(b))
            }
            this._.output.push(b)
        }, comment: function (b) {
            this._.indent && this.indentation();
            this._.output.push("<\!--", b, "--\>")
        }, lineBreak: function () {
            !this._.inPre &&
                this._.output.length > 0 && this._.output.push(this.lineBreakChars);
            this._.indent = 1
        }, indentation: function () {
            !this._.inPre && this._.indentation && this._.output.push(this._.indentation);
            this._.indent = 0
        }, reset: function () {
            this._.output = [];
            this._.indent = 0;
            this._.indentation = "";
            this._.afterCloser = 0;
            this._.inPre = 0
        }, setRules: function (b, c) {
            var a = this._.rules[b];
            a ? CKEDITOR.tools.extend(a, c, true) : this._.rules[b] = c
        }}}), function () {
        CKEDITOR.plugins.add("iframe", {requires: "dialog,fakeobjects", onLoad: function () {
            CKEDITOR.addCss("img.cke_iframe{background-image: url(" +
                CKEDITOR.getUrl(this.path + "images/placeholder.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}")
        }, init: function (b) {
            var c = b.lang.iframe, a = "iframe[align,longdesc,frameborder,height,name,scrolling,src,title,width]";
            b.plugins.dialogadvtab && (a = a + (";iframe" + b.plugins.dialogadvtab.allowedContent({id: 1, classes: 1, styles: 1})));
            CKEDITOR.dialog.add("iframe", this.path + "dialogs/iframe.js");
            b.addCommand("iframe", new CKEDITOR.dialogCommand("iframe",
                {allowedContent: a, requiredContent: "iframe"}));
            b.ui.addButton && b.ui.addButton("Iframe", {label: c.toolbar, command: "iframe", toolbar: "insert,80"});
            b.on("doubleclick", function (a) {
                var b = a.data.element;
                if (b.is("img") && b.data("cke-real-element-type") == "iframe")a.data.dialog = "iframe"
            });
            b.addMenuItems && b.addMenuItems({iframe: {label: c.title, command: "iframe", group: "image"}});
            b.contextMenu && b.contextMenu.addListener(function (a) {
                if (a && a.is("img") && a.data("cke-real-element-type") == "iframe")return{iframe: CKEDITOR.TRISTATE_OFF}
            })
        },
            afterInit: function (b) {
                var c = b.dataProcessor;
                (c = c && c.dataFilter) && c.addRules({elements: {iframe: function (a) {
                    return b.createFakeParserElement(a, "cke_iframe", "iframe", true)
                }}})
            }})
    }(), function () {
        function b(a, b) {
            b || (b = a.getSelection().getSelectedElement());
            if (b && b.is("img") && !b.data("cke-realelement") && !b.isReadOnly())return b
        }

        function c(a) {
            var b = a.getStyle("float");
            if (b == "inherit" || b == "none")b = 0;
            b || (b = a.getAttribute("align"));
            return b
        }

        CKEDITOR.plugins.add("image", {requires: "dialog", init: function (a) {
            CKEDITOR.dialog.add("image",
                this.path + "dialogs/image.js");
            var c = "img[alt,!src]{border-style,border-width,float,height,margin,margin-bottom,margin-left,margin-right,margin-top,width}";
            CKEDITOR.dialog.isTabEnabled(a, "image", "advanced") && (c = "img[alt,dir,id,lang,longdesc,!src,title]{*}(*)");
            a.addCommand("image", new CKEDITOR.dialogCommand("image", {allowedContent: c, requiredContent: "img[alt,src]", contentTransformations: [
                ["img{width}: sizeToStyle", "img[width]: sizeToAttribute"],
                ["img{float}: alignmentToStyle", "img[align]: alignmentToAttribute"]
            ]}));
            a.ui.addButton && a.ui.addButton("Image", {label: a.lang.common.image, command: "image", toolbar: "insert,10"});
            a.on("doubleclick", function (a) {
                var b = a.data.element;
                if (b.is("img") && !b.data("cke-realelement") && !b.isReadOnly())a.data.dialog = "image"
            });
            a.addMenuItems && a.addMenuItems({image: {label: a.lang.image.menu, command: "image", group: "image"}});
            a.contextMenu && a.contextMenu.addListener(function (c) {
                if (b(a, c))return{image: CKEDITOR.TRISTATE_OFF}
            })
        }, afterInit: function (a) {
            function f(f) {
                var g = a.getCommand("justify" +
                    f);
                if (g) {
                    if (f == "left" || f == "right")g.on("exec", function (d) {
                        var g = b(a), e;
                        if (g) {
                            e = c(g);
                            if (e == f) {
                                g.removeStyle("float");
                                f == c(g) && g.removeAttribute("align")
                            } else g.setStyle("float", f);
                            d.cancel()
                        }
                    });
                    g.on("refresh", function (d) {
                        var g = b(a);
                        if (g) {
                            g = c(g);
                            this.setState(g == f ? CKEDITOR.TRISTATE_ON : f == "right" || f == "left" ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED);
                            d.cancel()
                        }
                    })
                }
            }

            f("left");
            f("right");
            f("center");
            f("block")
        }})
    }(), CKEDITOR.config.image_removeLinkByEmptyURL = !0, function () {
        function b(a, b) {
            var b = b === void 0 || b, c;
            if (b)c = a.getComputedStyle("text-align"); else {
                for (; !a.hasAttribute || !a.hasAttribute("align") && !a.getStyle("text-align");) {
                    c = a.getParent();
                    if (!c)break;
                    a = c
                }
                c = a.getStyle("text-align") || a.getAttribute("align") || ""
            }
            c && (c = c.replace(/(?:-(?:moz|webkit)-)?(?:start|auto)/i, ""));
            !c && b && (c = a.getComputedStyle("direction") == "rtl" ? "right" : "left");
            return c
        }

        function c(a, b, c) {
            this.editor = a;
            this.name = b;
            this.value = c;
            this.context = "p";
            var b = a.config.justifyClasses, d = a.config.enterMode == CKEDITOR.ENTER_P ? "p" :
                "div";
            if (b) {
                switch (c) {
                    case "left":
                        this.cssClassName = b[0];
                        break;
                    case "center":
                        this.cssClassName = b[1];
                        break;
                    case "right":
                        this.cssClassName = b[2];
                        break;
                    case "justify":
                        this.cssClassName = b[3]
                }
                this.cssClassRegex = RegExp("(?:^|\\s+)(?:" + b.join("|") + ")(?=$|\\s)");
                this.requiredContent = d + "(" + this.cssClassName + ")"
            } else this.requiredContent = d + "{text-align}";
            this.allowedContent = {"caption div h1 h2 h3 h4 h5 h6 p pre td th li": {propertiesOnly: true, styles: this.cssClassName ? null : "text-align", classes: this.cssClassName ||
                null}};
            if (a.config.enterMode == CKEDITOR.ENTER_BR)this.allowedContent.div = true
        }

        function a(a) {
            var b = a.editor, c = b.createRange();
            c.setStartBefore(a.data.node);
            c.setEndAfter(a.data.node);
            for (var d = new CKEDITOR.dom.walker(c), i; i = d.next();)if (i.type == CKEDITOR.NODE_ELEMENT)if (!i.equals(a.data.node) && i.getDirection()) {
                c.setStartAfter(i);
                d = new CKEDITOR.dom.walker(c)
            } else {
                var e = b.config.justifyClasses;
                if (e)if (i.hasClass(e[0])) {
                    i.removeClass(e[0]);
                    i.addClass(e[2])
                } else if (i.hasClass(e[2])) {
                    i.removeClass(e[2]);
                    i.addClass(e[0])
                }
                e = i.getStyle("text-align");
                e == "left" ? i.setStyle("text-align", "right") : e == "right" && i.setStyle("text-align", "left")
            }
        }

        c.prototype = {exec: function (a) {
            var c = a.getSelection(), g = a.config.enterMode;
            if (c) {
                for (var d = c.createBookmarks(), i = c.getRanges(true), e = this.cssClassName, j, k, m = a.config.useComputedState, m = m === void 0 || m, n = i.length - 1; n >= 0; n--) {
                    j = i[n].createIterator();
                    for (j.enlargeBr = g != CKEDITOR.ENTER_BR; k = j.getNextParagraph(g == CKEDITOR.ENTER_P ? "p" : "div");) {
                        k.removeAttribute("align");
                        k.removeStyle("text-align");
                        var l = e && (k.$.className = CKEDITOR.tools.ltrim(k.$.className.replace(this.cssClassRegex, ""))), o = this.state == CKEDITOR.TRISTATE_OFF && (!m || b(k, true) != this.value);
                        e ? o ? k.addClass(e) : l || k.removeAttribute("class") : o && k.setStyle("text-align", this.value)
                    }
                }
                a.focus();
                a.forceNextSelectionCheck();
                c.selectBookmarks(d)
            }
        }, refresh: function (a, c) {
            var g = c.block || c.blockLimit;
            this.setState(g.getName() != "body" && b(g, this.editor.config.useComputedState) == this.value ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF)
        }};
        CKEDITOR.plugins.add("justify",
            {init: function (b) {
                if (!b.blockless) {
                    var h = new c(b, "justifyleft", "left"), g = new c(b, "justifycenter", "center"), d = new c(b, "justifyright", "right"), i = new c(b, "justifyblock", "justify");
                    b.addCommand("justifyleft", h);
                    b.addCommand("justifycenter", g);
                    b.addCommand("justifyright", d);
                    b.addCommand("justifyblock", i);
                    if (b.ui.addButton) {
                        b.ui.addButton("JustifyLeft", {label: b.lang.justify.left, command: "justifyleft", toolbar: "align,10"});
                        b.ui.addButton("JustifyCenter", {label: b.lang.justify.center, command: "justifycenter",
                            toolbar: "align,20"});
                        b.ui.addButton("JustifyRight", {label: b.lang.justify.right, command: "justifyright", toolbar: "align,30"});
                        b.ui.addButton("JustifyBlock", {label: b.lang.justify.block, command: "justifyblock", toolbar: "align,40"})
                    }
                    b.on("dirChanged", a)
                }
            }})
    }(), CKEDITOR.plugins.add("link", {requires: "dialog,fakeobjects", onLoad: function () {
        function b(b) {
            return a.replace(/%1/g, b == "rtl" ? "right" : "left").replace(/%2/g, "cke_contents_" + b)
        }

        var c = "background:url(" + CKEDITOR.getUrl(this.path + "images/anchor.png") + ") no-repeat %1 center;border:1px dotted #00f;",
            a = ".%2 a.cke_anchor,.%2 a.cke_anchor_empty,.cke_editable.%2 a[name],.cke_editable.%2 a[data-cke-saved-name]{" + c + "padding-%1:18px;cursor:auto;}" + (CKEDITOR.env.ie ? "a.cke_anchor_empty{display:inline-block;}" : "") + ".%2 img.cke_anchor{" + c + "width:16px;min-height:15px;height:1.15em;vertical-align:" + (CKEDITOR.env.opera ? "middle" : "text-bottom") + ";}";
        CKEDITOR.addCss(b("ltr") + b("rtl"))
    }, init: function (b) {
        var c = "a[!href]";
        CKEDITOR.dialog.isTabEnabled(b, "link", "advanced") && (c = c.replace("]", ",accesskey,charset,dir,id,lang,name,rel,tabindex,title,type]{*}(*)"));
        CKEDITOR.dialog.isTabEnabled(b, "link", "target") && (c = c.replace("]", ",target,onclick]"));
        b.addCommand("link", new CKEDITOR.dialogCommand("link", {allowedContent: c, requiredContent: "a[href]"}));
        b.addCommand("anchor", new CKEDITOR.dialogCommand("anchor", {allowedContent: "a[!name,id]", requiredContent: "a[name]"}));
        b.addCommand("unlink", new CKEDITOR.unlinkCommand);
        b.addCommand("removeAnchor", new CKEDITOR.removeAnchorCommand);
        b.setKeystroke(CKEDITOR.CTRL + 76, "link");
        if (b.ui.addButton) {
            b.ui.addButton("Link", {label: b.lang.link.toolbar,
                command: "link", toolbar: "links,10"});
            b.ui.addButton("Unlink", {label: b.lang.link.unlink, command: "unlink", toolbar: "links,20"});
            b.ui.addButton("Anchor", {label: b.lang.link.anchor.toolbar, command: "anchor", toolbar: "links,30"})
        }
        CKEDITOR.dialog.add("link", this.path + "dialogs/link.js");
        CKEDITOR.dialog.add("anchor", this.path + "dialogs/anchor.js");
        b.on("doubleclick", function (a) {
            var c = CKEDITOR.plugins.link.getSelectedLink(b) || a.data.element;
            if (!c.isReadOnly())if (c.is("a")) {
                a.data.dialog = c.getAttribute("name") &&
                    (!c.getAttribute("href") || !c.getChildCount()) ? "anchor" : "link";
                b.getSelection().selectElement(c)
            } else if (CKEDITOR.plugins.link.tryRestoreFakeAnchor(b, c))a.data.dialog = "anchor"
        });
        b.addMenuItems && b.addMenuItems({anchor: {label: b.lang.link.anchor.menu, command: "anchor", group: "anchor", order: 1}, removeAnchor: {label: b.lang.link.anchor.remove, command: "removeAnchor", group: "anchor", order: 5}, link: {label: b.lang.link.menu, command: "link", group: "link", order: 1}, unlink: {label: b.lang.link.unlink, command: "unlink", group: "link",
            order: 5}});
        b.contextMenu && b.contextMenu.addListener(function (a) {
            if (!a || a.isReadOnly())return null;
            a = CKEDITOR.plugins.link.tryRestoreFakeAnchor(b, a);
            if (!a && !(a = CKEDITOR.plugins.link.getSelectedLink(b)))return null;
            var c = {};
            a.getAttribute("href") && a.getChildCount() && (c = {link: CKEDITOR.TRISTATE_OFF, unlink: CKEDITOR.TRISTATE_OFF});
            if (a && a.hasAttribute("name"))c.anchor = c.removeAnchor = CKEDITOR.TRISTATE_OFF;
            return c
        })
    }, afterInit: function (b) {
        var c = b.dataProcessor, a = c && c.dataFilter, c = c && c.htmlFilter, f = b._.elementsPath &&
            b._.elementsPath.filters;
        a && a.addRules({elements: {a: function (a) {
            var c = a.attributes;
            if (!c.name)return null;
            var d = !a.children.length;
            if (CKEDITOR.plugins.link.synAnchorSelector) {
                var a = d ? "cke_anchor_empty" : "cke_anchor", f = c["class"];
                if (c.name && (!f || f.indexOf(a) < 0))c["class"] = (f || "") + " " + a;
                if (d && CKEDITOR.plugins.link.emptyAnchorFix) {
                    c.contenteditable = "false";
                    c["data-cke-editable"] = 1
                }
            } else if (CKEDITOR.plugins.link.fakeAnchor && d)return b.createFakeParserElement(a, "cke_anchor", "anchor");
            return null
        }}});
        CKEDITOR.plugins.link.emptyAnchorFix &&
            c && c.addRules({elements: {a: function (a) {
            delete a.attributes.contenteditable
        }}});
        f && f.push(function (a, c) {
            if (c == "a" && (CKEDITOR.plugins.link.tryRestoreFakeAnchor(b, a) || a.getAttribute("name") && (!a.getAttribute("href") || !a.getChildCount())))return"anchor"
        })
    }}), CKEDITOR.plugins.link = {getSelectedLink: function (b) {
        var c = b.getSelection(), a = c.getSelectedElement();
        if (a && a.is("a"))return a;
        if (c = c.getRanges(true)[0]) {
            c.shrink(CKEDITOR.SHRINK_TEXT);
            return b.elementPath(c.getCommonAncestor()).contains("a", 1)
        }
        return null
    },
        fakeAnchor: CKEDITOR.env.opera || CKEDITOR.env.webkit, synAnchorSelector: CKEDITOR.env.ie, emptyAnchorFix: CKEDITOR.env.ie && 8 > CKEDITOR.env.version, tryRestoreFakeAnchor: function (b, c) {
            if (c && c.data("cke-real-element-type") && c.data("cke-real-element-type") == "anchor") {
                var a = b.restoreRealElement(c);
                if (a.data("cke-saved-name"))return a
            }
        }}, CKEDITOR.unlinkCommand = function () {
    }, CKEDITOR.unlinkCommand.prototype = {exec: function (b) {
        var c = new CKEDITOR.style({element: "a", type: CKEDITOR.STYLE_INLINE, alwaysRemoveElement: 1});
        b.removeStyle(c)
    }, refresh: function (b, c) {
        var a = c.lastElement && c.lastElement.getAscendant("a", true);
        a && a.getName() == "a" && a.getAttribute("href") && a.getChildCount() ? this.setState(CKEDITOR.TRISTATE_OFF) : this.setState(CKEDITOR.TRISTATE_DISABLED)
    }, contextSensitive: 1, startDisabled: 1, requiredContent: "a[href]"}, CKEDITOR.removeAnchorCommand = function () {
    }, CKEDITOR.removeAnchorCommand.prototype = {exec: function (b) {
        var c = b.getSelection(), a = c.createBookmarks(), f;
        if (c && (f = c.getSelectedElement()) && (CKEDITOR.plugins.link.fakeAnchor && !f.getChildCount() ? CKEDITOR.plugins.link.tryRestoreFakeAnchor(b, f) : f.is("a")))f.remove(1); else if (f = CKEDITOR.plugins.link.getSelectedLink(b))if (f.hasAttribute("href")) {
            f.removeAttributes({name: 1, "data-cke-saved-name": 1});
            f.removeClass("cke_anchor")
        } else f.remove(1);
        c.selectBookmarks(a)
    }, requiredContent: "a[name]"}, CKEDITOR.tools.extend(CKEDITOR.config, {linkShowAdvancedTab: !0, linkShowTargetTab: !0}), function () {
        CKEDITOR.plugins.liststyle = {requires: "dialog,contextmenu", init: function (b) {
            if (!b.blockless) {
                var c;
                c = new CKEDITOR.dialogCommand("numberedListStyle", {requiredContent: "ol", allowedContent: "ol{list-style-type}[start]"});
                c = b.addCommand("numberedListStyle", c);
                b.addFeature(c);
                CKEDITOR.dialog.add("numberedListStyle", this.path + "dialogs/liststyle.js");
                c = new CKEDITOR.dialogCommand("bulletedListStyle", {requiredContent: "ul", allowedContent: "ul{list-style-type}"});
                c = b.addCommand("bulletedListStyle", c);
                b.addFeature(c);
                CKEDITOR.dialog.add("bulletedListStyle", this.path + "dialogs/liststyle.js");
                b.addMenuGroup("list",
                    108);
                b.addMenuItems({numberedlist: {label: b.lang.liststyle.numberedTitle, group: "list", command: "numberedListStyle"}, bulletedlist: {label: b.lang.liststyle.bulletedTitle, group: "list", command: "bulletedListStyle"}});
                b.contextMenu.addListener(function (a) {
                    if (!a || a.isReadOnly())return null;
                    for (; a;) {
                        var b = a.getName();
                        if (b == "ol")return{numberedlist: CKEDITOR.TRISTATE_OFF};
                        if (b == "ul")return{bulletedlist: CKEDITOR.TRISTATE_OFF};
                        a = a.getParent()
                    }
                    return null
                })
            }
        }};
        CKEDITOR.plugins.add("liststyle", CKEDITOR.plugins.liststyle)
    }(),
        "use strict", function () {
        function b(a, b, d) {
            return j(b) && j(d) && d.equals(b.getNext(function (a) {
                return!(T(a) || V(a) || k(a))
            }))
        }

        function c(a) {
            this.upper = a[0];
            this.lower = a[1];
            this.set.apply(this, a.slice(2))
        }

        function a(a) {
            var b = a.element, d;
            return b && j(b) ? (d = b.getAscendant(a.triggers, true)) && !d.contains(a.editable) && !d.equals(a.editable) ? d : null : null
        }

        function f(a, b, d) {
            p(a, b);
            p(a, d);
            a = b.size.bottom;
            d = d.size.top;
            return a && d ? 0 | (a + d) / 2 : a || d
        }

        function h(a, b, d) {
            return b = b[d ? "getPrevious" : "getNext"](function (b) {
                return b &&
                    b.type == CKEDITOR.NODE_TEXT && !T(b) || j(b) && !k(b) && !e(a, b)
            })
        }

        function g(a) {
            var b = a.doc, c = r('<span contenteditable="false" style="' + Q + "position:absolute;border-top:1px dashed " + a.boxColor + '"></span>', b);
            w(c, {attach: function () {
                this.wrap.getParent() || this.wrap.appendTo(a.editable, true);
                return this
            }, lineChildren: [w(r('<span title="' + a.editor.lang.magicline.title + '" contenteditable="false">&#8629;</span>', b), {base: Q + "height:17px;width:17px;" + (a.rtl ? "left" : "right") + ":17px;background:url(" + this.path + "images/icon.png) center no-repeat " +
                a.boxColor + ";cursor:pointer;" + (u.hc ? "font-size: 15px;line-height:14px;border:1px solid #fff;text-align:center;" : ""), looks: ["top:-8px;" + CKEDITOR.tools.cssVendorPrefix("border-radius", "2px", 1), "top:-17px;" + CKEDITOR.tools.cssVendorPrefix("border-radius", "2px 2px 0px 0px", 1), "top:-1px;" + CKEDITOR.tools.cssVendorPrefix("border-radius", "0px 0px 2px 2px", 1)]}), w(r(N, b), {base: R + "left:0px;border-left-color:" + a.boxColor + ";", looks: ["border-width:8px 0 8px 8px;top:-8px", "border-width:8px 0 0 8px;top:-8px", "border-width:0 0 8px 8px;top:0px"]}),
                w(r(N, b), {base: R + "right:0px;border-right-color:" + a.boxColor + ";", looks: ["border-width:8px 8px 8px 0;top:-8px", "border-width:8px 8px 0 0;top:-8px", "border-width:0 8px 8px 0;top:0px"]})], detach: function () {
                this.wrap.getParent() && this.wrap.remove();
                return this
            }, mouseNear: function () {
                p(a, this);
                var b = a.holdDistance, d = this.size;
                return d && a.mouse.y > d.top - b && a.mouse.y < d.bottom + b && a.mouse.x > d.left - b && a.mouse.x < d.right + b ? true : false
            }, place: function () {
                var b = a.view, d = a.editable, c = a.trigger, e = c.upper, g = c.lower, f =
                    e || g, h = f.getParent(), i = {};
                this.trigger = c;
                e && p(a, e, true);
                g && p(a, g, true);
                p(a, h, true);
                a.inInlineMode && t(a, true);
                if (h.equals(d)) {
                    i.left = b.scroll.x;
                    i.right = -b.scroll.x;
                    i.width = ""
                } else {
                    i.left = f.size.left - f.size.margin.left + b.scroll.x - (a.inInlineMode ? b.editable.left + b.editable.border.left : 0);
                    i.width = f.size.outerWidth + f.size.margin.left + f.size.margin.right + b.scroll.x;
                    i.right = ""
                }
                if (e && g)i.top = e.size.margin.bottom === g.size.margin.top ? 0 | e.size.bottom + e.size.margin.bottom / 2 : e.size.margin.bottom < g.size.margin.top ?
                    e.size.bottom + e.size.margin.bottom : e.size.bottom + e.size.margin.bottom - g.size.margin.top; else if (e) {
                    if (!g)i.top = e.size.bottom + e.size.margin.bottom
                } else i.top = g.size.top - g.size.margin.top;
                if (c.is(E) || i.top > b.scroll.y - 15 && i.top < b.scroll.y + 5) {
                    i.top = a.inInlineMode ? 0 : b.scroll.y;
                    this.look(E)
                } else if (c.is(K) || i.top > b.pane.bottom - 5 && i.top < b.pane.bottom + 15) {
                    i.top = a.inInlineMode ? b.editable.height + b.editable.padding.top + b.editable.padding.bottom : b.pane.bottom - 1;
                    this.look(K)
                } else {
                    if (a.inInlineMode)i.top = i.top -
                        (b.editable.top + b.editable.border.top);
                    this.look(I)
                }
                if (a.inInlineMode) {
                    i.top--;
                    i.top = i.top + b.editable.scroll.top;
                    i.left = i.left + b.editable.scroll.left
                }
                for (var j in i)i[j] = CKEDITOR.tools.cssLength(i[j]);
                this.setStyles(i)
            }, look: function (a) {
                if (this.oldLook != a) {
                    for (var b = this.lineChildren.length, d; b--;)(d = this.lineChildren[b]).setAttribute("style", d.base + d.looks[0 | a / 2]);
                    this.oldLook = a
                }
            }, wrap: new v("span", a.doc)});
            for (b = c.lineChildren.length; b--;)c.lineChildren[b].appendTo(c);
            c.look(I);
            c.appendTo(c.wrap);
            c.unselectable();
            c.lineChildren[0].on("mouseup", function (b) {
                c.detach();
                d(a, function (b) {
                    var d = a.line.trigger;
                    b[d.is(B) ? "insertBefore" : "insertAfter"](d.is(B) ? d.lower : d.upper)
                }, true);
                a.editor.focus();
                !u.ie && a.enterMode != CKEDITOR.ENTER_BR && a.hotNode.scrollIntoView();
                b.data.preventDefault(true)
            });
            c.on("mousedown", function (a) {
                a.data.preventDefault(true)
            });
            a.line = c
        }

        function d(a, b, d) {
            var c = new CKEDITOR.dom.range(a.doc), e = a.editor, g;
            if (u.ie && a.enterMode == CKEDITOR.ENTER_BR)g = a.doc.createText(G); else {
                g = new v(a.enterBehavior,
                    a.doc);
                a.enterMode != CKEDITOR.ENTER_BR && a.doc.createText(G).appendTo(g)
            }
            d && e.fire("saveSnapshot");
            b(g);
            c.moveToPosition(g, CKEDITOR.POSITION_AFTER_START);
            e.getSelection().selectRanges([c]);
            a.hotNode = g;
            d && e.fire("saveSnapshot")
        }

        function i(b, c) {
            return{canUndo: true, modes: {wysiwyg: 1}, exec: function () {
                function e(a) {
                    var g = u.ie && u.version < 9 ? " " : G, f = b.hotNode && b.hotNode.getText() == g && b.element.equals(b.hotNode) && b.lastCmdDirection === !!c;
                    d(b, function (d) {
                        f && b.hotNode && b.hotNode.remove();
                        d[c ? "insertAfter" : "insertBefore"](a);
                        d.setAttributes({"data-cke-magicline-hot": 1, "data-cke-magicline-dir": !!c});
                        b.lastCmdDirection = !!c
                    });
                    !u.ie && b.enterMode != CKEDITOR.ENTER_BR && b.hotNode.scrollIntoView();
                    b.line.detach()
                }

                return function (d) {
                    d = d.getSelection().getStartElement();
                    if ((d = d.getAscendant(P, 1)) && !d.equals(b.editable) && !d.contains(b.editable)) {
                        b.element = d;
                        var g = h(b, d, !c), f;
                        if (j(g) && g.is(b.triggers) && g.is(J) && (!h(b, g, !c) || (f = h(b, g, !c)) && j(f) && f.is(b.triggers)))e(g); else {
                            f = a(b, d);
                            if (j(f))if (h(b, f, !c))(d = h(b, f, !c)) && (j(d) && d.is(b.triggers)) &&
                            e(f); else e(f)
                        }
                    }
                }
            }()}
        }

        function e(a, b) {
            if (!b || !(b.type == CKEDITOR.NODE_ELEMENT && b.$))return false;
            var d = a.line;
            return d.wrap.equals(b) || d.wrap.contains(b)
        }

        function j(a) {
            return a && a.type == CKEDITOR.NODE_ELEMENT && a.$
        }

        function k(a) {
            if (!j(a))return false;
            var b;
            if (!(b = m(a)))if (j(a)) {
                b = {left: 1, right: 1, center: 1};
                b = !(!b[a.getComputedStyle("float")] && !b[a.getAttribute("align")])
            } else b = false;
            return b
        }

        function m(a) {
            return!!{absolute: 1, fixed: 1, relative: 1}[a.getComputedStyle("position")]
        }

        function n(a, b) {
            return j(b) ?
                b.is(a.triggers) : null
        }

        function l(a, b, d) {
            b = b[d ? "getLast" : "getFirst"](function (b) {
                return a.isRelevant(b) && !b.is(L)
            });
            if (!b)return false;
            p(a, b);
            return d ? b.size.top > a.mouse.y : b.size.bottom < a.mouse.y
        }

        function o(a) {
            var b = a.editable, d = a.mouse, g = a.view, f = a.triggerOffset;
            t(a);
            var h = d.y > (a.inInlineMode ? g.editable.top + g.editable.height / 2 : Math.min(g.editable.height, g.pane.height) / 2), b = b[h ? "getLast" : "getFirst"](function (a) {
                return!(T(a) || V(a))
            });
            if (!b)return null;
            e(a, b) && (b = a.line.wrap[h ? "getPrevious" : "getNext"](function (a) {
                return!(T(a) ||
                    V(a))
            }));
            if (!j(b) || k(b) || !n(a, b))return null;
            p(a, b);
            if (!h && b.size.top >= 0 && d.y > 0 && d.y < b.size.top + f) {
                a = a.inInlineMode || g.scroll.y === 0 ? E : I;
                return new c([null, b, B, D, a])
            }
            if (h && b.size.bottom <= g.pane.height && d.y > b.size.bottom - f && d.y < g.pane.height) {
                a = a.inInlineMode || b.size.bottom > g.pane.height - f && b.size.bottom < g.pane.height ? K : I;
                return new c([b, null, y, D, a])
            }
            return null
        }

        function q(b) {
            var d = b.mouse, e = b.view, g = b.triggerOffset, f = a(b);
            if (!f)return null;
            p(b, f);
            var g = Math.min(g, 0 | f.size.outerHeight / 2), i = [], o, m;
            if (d.y >
                f.size.top - 1 && d.y < f.size.top + g)m = false; else if (d.y > f.size.bottom - g && d.y < f.size.bottom + 1)m = true; else return null;
            if (k(f) || l(b, f, m) || f.getParent().is(H))return null;
            var q = h(b, f, !m);
            if (q) {
                if (q && q.type == CKEDITOR.NODE_TEXT)return null;
                if (j(q)) {
                    if (k(q) || !n(b, q) || q.getParent().is(H))return null;
                    i = [q, f][m ? "reverse" : "concat"]().concat([C, D])
                }
            } else {
                if (f.equals(b.editable[m ? "getLast" : "getFirst"](b.isRelevant))) {
                    t(b);
                    m && d.y > f.size.bottom - g && d.y < e.pane.height && f.size.bottom > e.pane.height - g && f.size.bottom < e.pane.height ?
                        o = K : d.y > 0 && d.y < f.size.top + g && (o = E)
                } else o = I;
                i = [null, f][m ? "reverse" : "concat"]().concat([m ? y : B, D, o, f.equals(b.editable[m ? "getLast" : "getFirst"](b.isRelevant)) ? m ? K : E : I])
            }
            return 0 in i ? new c(i) : null
        }

        function s(a, b, d, c) {
            for (var e = function () {
                var d = u.ie ? b.$.currentStyle : a.win.$.getComputedStyle(b.$, "");
                return u.ie ? function (a) {
                    return d[CKEDITOR.tools.cssStyleToDomStyle(a)]
                } : function (a) {
                    return d.getPropertyValue(a)
                }
            }(), g = b.getDocumentPosition(), f = {}, h = {}, i = {}, j = {}, l = S.length; l--;) {
                f[S[l]] = parseInt(e("border-" +
                    S[l] + "-width"), 10) || 0;
                i[S[l]] = parseInt(e("padding-" + S[l]), 10) || 0;
                h[S[l]] = parseInt(e("margin-" + S[l]), 10) || 0
            }
            (!d || c) && z(a, c);
            j.top = g.y - (d ? 0 : a.view.scroll.y);
            j.left = g.x - (d ? 0 : a.view.scroll.x);
            j.outerWidth = b.$.offsetWidth;
            j.outerHeight = b.$.offsetHeight;
            j.height = j.outerHeight - (i.top + i.bottom + f.top + f.bottom);
            j.width = j.outerWidth - (i.left + i.right + f.left + f.right);
            j.bottom = j.top + j.outerHeight;
            j.right = j.left + j.outerWidth;
            if (a.inInlineMode)j.scroll = {top: b.$.scrollTop, left: b.$.scrollLeft};
            return w({border: f, padding: i,
                margin: h, ignoreScroll: d}, j, true)
        }

        function p(a, b, d) {
            if (!j(b))return b.size = null;
            if (b.size) {
                if (b.size.ignoreScroll == d && b.size.date > new Date - M)return null
            } else b.size = {};
            return w(b.size, s(a, b, d), {date: +new Date}, true)
        }

        function t(a, b) {
            a.view.editable = s(a, a.editable, b, true)
        }

        function z(a, b) {
            if (!a.view)a.view = {};
            var d = a.view;
            if (b || !(d && d.date > new Date - M)) {
                var c = a.win, d = c.getScrollPosition(), c = c.getViewPaneSize();
                w(a.view, {scroll: {x: d.x, y: d.y, width: a.doc.$.documentElement.scrollWidth - c.width, height: a.doc.$.documentElement.scrollHeight -
                    c.height}, pane: {width: c.width, height: c.height, bottom: c.height + d.y}, date: +new Date}, true)
            }
        }

        function x(a, b, d, e) {
            for (var g = e, f = e, h = 0, i = false, j = false, l = a.view.pane.height, o = a.mouse; o.y + h < l && o.y - h > 0;) {
                i || (i = b(g, e));
                j || (j = b(f, e));
                !i && o.y - h > 0 && (g = d(a, {x: o.x, y: o.y - h}));
                !j && o.y + h < l && (f = d(a, {x: o.x, y: o.y + h}));
                if (i && j)break;
                h = h + 2
            }
            return new c([g, f, null, null])
        }

        CKEDITOR.plugins.add("magicline", {init: function (b) {
            var f = {};
            f[CKEDITOR.ENTER_BR] = "br";
            f[CKEDITOR.ENTER_P] = "p";
            f[CKEDITOR.ENTER_DIV] = "div";
            var l = b.config,
                n = l.magicline_triggerOffset || 30, p = l.enterMode, r = {editor: b, enterBehavior: f[p], enterMode: p, triggerOffset: n, holdDistance: 0 | n * (l.magicline_holdDistance || 0.5), boxColor: l.magicline_color || "#ff0000", rtl: l.contentsLangDirection == "rtl", triggers: l.magicline_everywhere ? P : {table: 1, hr: 1, div: 1, ul: 1, ol: 1, dl: 1, form: 1, blockquote: 1}}, v, x, y;
            r.isRelevant = function (a) {
                return j(a) && !e(r, a) && !k(a)
            };
            b.on("contentDom", function () {
                var f = b.editable(), j = b.document, k = b.window;
                w(r, {editable: f, inInlineMode: f.isInline(), doc: j, win: k},
                    true);
                r.boundary = r.inInlineMode ? r.editable : r.doc.getDocumentElement();
                if (!f.is(A.$inline)) {
                    r.inInlineMode && !m(f) && f.setStyles({position: "relative", top: null, left: null});
                    g.call(this, r);
                    z(r);
                    f.attachListener(b, "beforeUndoImage", function () {
                        r.line.detach()
                    });
                    f.attachListener(b, "beforeGetData", function () {
                        if (r.line.wrap.getParent()) {
                            r.line.detach();
                            b.once("getData", function () {
                                r.line.attach()
                            }, null, null, 1E3)
                        }
                    }, null, null, 0);
                    f.attachListener(r.inInlineMode ? j : j.getWindow().getFrame(), "mouseout", function (a) {
                        if (b.mode ==
                            "wysiwyg")if (r.inInlineMode) {
                            var d = a.data.$.clientX, a = a.data.$.clientY;
                            z(r);
                            t(r, true);
                            var c = r.view.editable, e = r.view.scroll;
                            if (!(d > c.left - e.x && d < c.right - e.x) || !(a > c.top - e.y && a < c.bottom - e.y)) {
                                clearTimeout(y);
                                y = null;
                                r.line.detach()
                            }
                        } else {
                            clearTimeout(y);
                            y = null;
                            r.line.detach()
                        }
                    });
                    f.attachListener(f, "keyup", function () {
                        r.hiddenMode = 0
                    });
                    f.attachListener(f, "keydown", function (a) {
                        if (b.mode == "wysiwyg") {
                            a = a.data.getKeystroke();
                            b.getSelection().getStartElement();
                            switch (a) {
                                case 2228240:
                                case 16:
                                    r.hiddenMode = 1;
                                    r.line.detach()
                            }
                        }
                    });
                    f.attachListener(r.inInlineMode ? f : j, "mousemove", function (a) {
                        x = true;
                        if (!(b.mode != "wysiwyg" || b.readOnly || y)) {
                            var d = {x: a.data.$.clientX, y: a.data.$.clientY};
                            y = setTimeout(function () {
                                r.mouse = d;
                                y = r.trigger = null;
                                z(r);
                                if (x && !r.hiddenMode && b.focusManager.hasFocus && !r.line.mouseNear() && (r.element = U(r, true))) {
                                    if (r.trigger = o(r) || q(r) || W(r))r.line.attach().place(); else {
                                        r.trigger = null;
                                        r.line.detach()
                                    }
                                    x = false
                                }
                            }, 30)
                        }
                    });
                    f.attachListener(k, "scroll", function () {
                        if (b.mode == "wysiwyg") {
                            r.line.detach();
                            if (u.webkit) {
                                r.hiddenMode = 1;
                                clearTimeout(v);
                                v = setTimeout(function () {
                                    r.hiddenMode = 0
                                }, 50)
                            }
                        }
                    });
                    f.attachListener(k, "mousedown", function () {
                        if (b.mode == "wysiwyg") {
                            r.line.detach();
                            r.hiddenMode = 1
                        }
                    });
                    f.attachListener(k, "mouseup", function () {
                        r.hiddenMode = 0
                    });
                    b.addCommand("accessPreviousSpace", i(r));
                    b.addCommand("accessNextSpace", i(r, true));
                    b.setKeystroke([
                        [l.magicline_keystrokePrevious, "accessPreviousSpace"],
                        [l.magicline_keystrokeNext, "accessNextSpace"]
                    ]);
                    b.on("loadSnapshot", function () {
                        for (var a = b.document.getElementsByTag(r.enterBehavior),
                                 d, c = a.count(); c--;)if ((d = a.getItem(c)).hasAttribute("data-cke-magicline-hot")) {
                            r.hotNode = d;
                            r.lastCmdDirection = d.getAttribute("data-cke-magicline-dir") === "true" ? true : false;
                            break
                        }
                    });
                    this.backdoor = {accessFocusSpace: d, boxTrigger: c, isLine: e, getAscendantTrigger: a, getNonEmptyNeighbour: h, getSize: s, that: r, triggerEdge: q, triggerEditable: o, triggerExpand: W}
                }
            }, this)
        }});
        var w = CKEDITOR.tools.extend, v = CKEDITOR.dom.element, r = v.createFromHtml, u = CKEDITOR.env, A = CKEDITOR.dtd, B = 128, y = 64, C = 32, D = 16, F = 8, E = 4, K = 2, I = 1, G = " ",
            H = A.$listItem, L = A.$tableContent, J = w({}, A.$nonEditable, A.$empty), P = A.$block, M = 100, Q = "width:0px;height:0px;padding:0px;margin:0px;display:block;z-index:9999;color:#fff;position:absolute;font-size: 0px;line-height:0px;", R = Q + "border-color:transparent;display:block;border-style:solid;", N = "<span>" + G + "</span>";
        c.prototype = {set: function (a, b, d) {
            this.properties = a + b + (d || I);
            return this
        }, is: function (a) {
            return(this.properties & a) == a
        }};
        var U = function () {
            return function (a, b, d) {
                if (!a.mouse)return null;
                var c = a.doc, g =
                    a.line.wrap, d = d || a.mouse, f = new CKEDITOR.dom.element(c.$.elementFromPoint(d.x, d.y));
                if (b && e(a, f)) {
                    g.hide();
                    f = new CKEDITOR.dom.element(c.$.elementFromPoint(d.x, d.y));
                    g.show()
                }
                return!f || !(f.type == CKEDITOR.NODE_ELEMENT && f.$) || u.ie && u.version < 9 && !a.boundary.equals(f) && !a.boundary.contains(f) ? null : f
            }
        }(), T = CKEDITOR.dom.walker.whitespaces(), V = CKEDITOR.dom.walker.nodeType(CKEDITOR.NODE_COMMENT), W = function () {
            function a(c) {
                var e = c.element, g, h, i;
                if (!j(e) || e.contains(c.editable))return null;
                i = x(c, function (a, b) {
                        return!b.equals(a)
                    },
                    function (a, b) {
                        return U(a, true, b)
                    }, e);
                g = i.upper;
                h = i.lower;
                if (b(c, g, h))return i.set(C, F);
                if (g && e.contains(g))for (; !g.getParent().equals(e);)g = g.getParent(); else g = e.getFirst(function (a) {
                    return d(c, a)
                });
                if (h && e.contains(h))for (; !h.getParent().equals(e);)h = h.getParent(); else h = e.getLast(function (a) {
                    return d(c, a)
                });
                if (!g || !h)return null;
                p(c, g);
                p(c, h);
                if (!(c.mouse.y > g.size.top && c.mouse.y < h.size.bottom))return null;
                for (var e = Number.MAX_VALUE, l, o, k, m; h && !h.equals(g);) {
                    if (!(o = g.getNext(c.isRelevant)))break;
                    l = Math.abs(f(c, g, o) - c.mouse.y);
                    if (l < e) {
                        e = l;
                        k = g;
                        m = o
                    }
                    g = o;
                    p(c, g)
                }
                if (!k || !m || !(c.mouse.y > k.size.top && c.mouse.y < m.size.bottom))return null;
                i.upper = k;
                i.lower = m;
                return i.set(C, F)
            }

            function d(a, b) {
                return!(b && b.type == CKEDITOR.NODE_TEXT || V(b) || k(b) || e(a, b) || b.type == CKEDITOR.NODE_ELEMENT && b.$ && b.is("br"))
            }

            return function (d) {
                var c = a(d), e;
                if (e = c) {
                    e = c.upper;
                    var g = c.lower;
                    e = !e || !g || k(g) || k(e) || g.equals(e) || e.equals(g) || g.contains(e) || e.contains(g) ? false : n(d, e) && n(d, g) && b(d, e, g) ? true : false
                }
                return e ? c : null
            }
        }(), S = ["top",
            "left", "right", "bottom"]
    }(), CKEDITOR.config.magicline_keystrokePrevious = CKEDITOR.CTRL + CKEDITOR.SHIFT + 219, CKEDITOR.config.magicline_keystrokeNext = CKEDITOR.CTRL + CKEDITOR.SHIFT + 221, function () {
        function b(a) {
            if (!a || a.type != CKEDITOR.NODE_ELEMENT || a.getName() != "form")return[];
            for (var b = [], c = ["style", "className"], e = 0; e < c.length; e++) {
                var f = a.$.elements.namedItem(c[e]);
                if (f) {
                    f = new CKEDITOR.dom.element(f);
                    b.push([f, f.nextSibling]);
                    f.remove()
                }
            }
            return b
        }

        function c(a, b) {
            if (a && !(a.type != CKEDITOR.NODE_ELEMENT || a.getName() !=
                "form") && b.length > 0)for (var c = b.length - 1; c >= 0; c--) {
                var e = b[c][0], f = b[c][1];
                f ? e.insertBefore(f) : e.appendTo(a)
            }
        }

        function a(a, d) {
            var f = b(a), e = {}, h = a.$;
            if (!d) {
                e["class"] = h.className || "";
                h.className = ""
            }
            e.inline = h.style.cssText || "";
            if (!d)h.style.cssText = "position: static; overflow: visible";
            c(f);
            return e
        }

        function f(a, d) {
            var f = b(a), e = a.$;
            if ("class"in d)e.className = d["class"];
            if ("inline"in d)e.style.cssText = d.inline;
            c(f)
        }

        function h(a) {
            if (!a.editable().isInline()) {
                var b = CKEDITOR.instances, c;
                for (c in b) {
                    var e =
                        b[c];
                    if (e.mode == "wysiwyg" && !e.readOnly) {
                        e = e.document.getBody();
                        e.setAttribute("contentEditable", false);
                        e.setAttribute("contentEditable", true)
                    }
                }
                if (a.editable().hasFocus) {
                    a.toolbox.focus();
                    a.focus()
                }
            }
        }

        CKEDITOR.plugins.add("maximize", {init: function (b) {
            function d() {
                var a = j.getViewPaneSize();
                b.resize(a.width, a.height, null, true)
            }

            if (b.elementMode != CKEDITOR.ELEMENT_MODE_INLINE) {
                var c = b.lang, e = CKEDITOR.document, j = e.getWindow(), k, m, n, l = CKEDITOR.TRISTATE_OFF;
                b.addCommand("maximize", {modes: {wysiwyg: !CKEDITOR.env.iOS,
                    source: !CKEDITOR.env.iOS}, readOnly: 1, editorFocus: false, exec: function () {
                    var o = b.container.getChild(1), q = b.ui.space("contents");
                    if (b.mode == "wysiwyg") {
                        var s = b.getSelection();
                        k = s && s.getRanges();
                        m = j.getScrollPosition()
                    } else {
                        var p = b.editable().$;
                        k = !CKEDITOR.env.ie && [p.selectionStart, p.selectionEnd];
                        m = [p.scrollLeft, p.scrollTop]
                    }
                    if (this.state == CKEDITOR.TRISTATE_OFF) {
                        j.on("resize", d);
                        n = j.getScrollPosition();
                        for (s = b.container; s = s.getParent();) {
                            s.setCustomData("maximize_saved_styles", a(s));
                            s.setStyle("z-index",
                                b.config.baseFloatZIndex - 5)
                        }
                        q.setCustomData("maximize_saved_styles", a(q, true));
                        o.setCustomData("maximize_saved_styles", a(o, true));
                        q = {overflow: CKEDITOR.env.webkit ? "" : "hidden", width: 0, height: 0};
                        e.getDocumentElement().setStyles(q);
                        !CKEDITOR.env.gecko && e.getDocumentElement().setStyle("position", "fixed");
                        (!CKEDITOR.env.gecko || !CKEDITOR.env.quirks) && e.getBody().setStyles(q);
                        CKEDITOR.env.ie ? setTimeout(function () {
                            j.$.scrollTo(0, 0)
                        }, 0) : j.$.scrollTo(0, 0);
                        o.setStyle("position", CKEDITOR.env.gecko && CKEDITOR.env.quirks ?
                            "fixed" : "absolute");
                        o.$.offsetLeft;
                        o.setStyles({"z-index": b.config.baseFloatZIndex - 5, left: "0px", top: "0px"});
                        o.addClass("cke_maximized");
                        d();
                        q = o.getDocumentPosition();
                        o.setStyles({left: -1 * q.x + "px", top: -1 * q.y + "px"});
                        CKEDITOR.env.gecko && h(b)
                    } else if (this.state == CKEDITOR.TRISTATE_ON) {
                        j.removeListener("resize", d);
                        q = [q, o];
                        for (s = 0; s < q.length; s++) {
                            f(q[s], q[s].getCustomData("maximize_saved_styles"));
                            q[s].removeCustomData("maximize_saved_styles")
                        }
                        for (s = b.container; s = s.getParent();) {
                            f(s, s.getCustomData("maximize_saved_styles"));
                            s.removeCustomData("maximize_saved_styles")
                        }
                        CKEDITOR.env.ie ? setTimeout(function () {
                            j.$.scrollTo(n.x, n.y)
                        }, 0) : j.$.scrollTo(n.x, n.y);
                        o.removeClass("cke_maximized");
                        if (CKEDITOR.env.webkit) {
                            o.setStyle("display", "inline");
                            setTimeout(function () {
                                o.setStyle("display", "block")
                            }, 0)
                        }
                        b.fire("resize")
                    }
                    this.toggleState();
                    if (s = this.uiItems[0]) {
                        q = this.state == CKEDITOR.TRISTATE_OFF ? c.maximize.maximize : c.maximize.minimize;
                        s = CKEDITOR.document.getById(s._.id);
                        s.getChild(1).setHtml(q);
                        s.setAttribute("title", q);
                        s.setAttribute("href",
                            'javascript:void("' + q + '");')
                    }
                    if (b.mode == "wysiwyg")if (k) {
                        CKEDITOR.env.gecko && h(b);
                        b.getSelection().selectRanges(k);
                        (p = b.getSelection().getStartElement()) && p.scrollIntoView(true)
                    } else j.$.scrollTo(m.x, m.y); else {
                        if (k) {
                            p.selectionStart = k[0];
                            p.selectionEnd = k[1]
                        }
                        p.scrollLeft = m[0];
                        p.scrollTop = m[1]
                    }
                    k = m = null;
                    l = this.state;
                    b.fire("maximize", this.state)
                }, canUndo: false});
                b.ui.addButton && b.ui.addButton("Maximize", {label: c.maximize.maximize, command: "maximize", toolbar: "tools,10"});
                b.on("mode", function () {
                    var a = b.getCommand("maximize");
                    a.setState(a.state == CKEDITOR.TRISTATE_DISABLED ? CKEDITOR.TRISTATE_DISABLED : l)
                }, null, null, 100)
            }
        }})
    }(), CKEDITOR.plugins.add("newpage", {init: function (b) {
        b.addCommand("newpage", {modes: {wysiwyg: 1, source: 1}, exec: function (b) {
            var a = this;
            b.setData(b.config.newpage_html || "", function () {
                b.focus();
                setTimeout(function () {
                    b.fire("afterCommandExec", {name: "newpage", command: a});
                    b.selectionChange()
                }, 200)
            })
        }, async: true});
        b.ui.addButton && b.ui.addButton("NewPage", {label: b.lang.newpage.toolbar, command: "newpage", toolbar: "document,20"})
    }}),
        CKEDITOR.plugins.add("pagebreak", {requires: "fakeobjects", onLoad: function () {
            var b = ["{", "background: url(" + CKEDITOR.getUrl(this.path + "images/pagebreak.gif") + ") no-repeat center center;", "clear: both;width:100%; _width:99.9%;border-top: #999999 1px dotted;border-bottom: #999999 1px dotted;padding:0;height: 5px;cursor: default;}"].join("").replace(/;/g, " !important;");
            CKEDITOR.addCss("div.cke_pagebreak" + b)
        }, init: function (b) {
            if (!b.blockless) {
                b.addCommand("pagebreak", CKEDITOR.plugins.pagebreakCmd);
                b.ui.addButton &&
                b.ui.addButton("PageBreak", {label: b.lang.pagebreak.toolbar, command: "pagebreak", toolbar: "insert,70"});
                CKEDITOR.env.opera && b.on("contentDom", function () {
                    b.document.on("click", function (c) {
                        c = c.data.getTarget();
                        c.is("div") && c.hasClass("cke_pagebreak") && b.getSelection().selectElement(c)
                    })
                })
            }
        }, afterInit: function (b) {
            var c = b.lang.pagebreak.alt, a = b.dataProcessor, b = a && a.dataFilter;
            (a = a && a.htmlFilter) && a.addRules({attributes: {"class": function (a, b) {
                var c = a.replace("cke_pagebreak", "");
                if (c != a) {
                    var d = CKEDITOR.htmlParser.fragment.fromHtml('<span style="display: none;">&nbsp;</span>').children[0];
                    b.children.length = 0;
                    b.add(d);
                    d = b.attributes;
                    delete d["aria-label"];
                    delete d.contenteditable;
                    delete d.title
                }
                return c
            }}}, 5);
            b && b.addRules({elements: {div: function (a) {
                var b = a.attributes, g = b && b.style, d = g && a.children.length == 1 && a.children[0];
                if ((d = d && d.name == "span" && d.attributes.style) && /page-break-after\s*:\s*always/i.test(g) && /display\s*:\s*none/i.test(d)) {
                    b.contenteditable = "false";
                    b["class"] = "cke_pagebreak";
                    b["data-cke-display-name"] = "pagebreak";
                    b["aria-label"] = c;
                    b.title = c;
                    a.children.length = 0
                }
            }}})
        }}),
        CKEDITOR.plugins.pagebreakCmd = {exec: function (b) {
            var c = b.lang.pagebreak.alt, c = CKEDITOR.dom.element.createFromHtml('<div style="page-break-after: always;"contenteditable="false" title="' + c + '" aria-label="' + c + '" data-cke-display-name="pagebreak" class="cke_pagebreak"></div>', b.document);
            b.insertElement(c)
        }, context: "div", allowedContent: {div: {styles: "!page-break-after"}, span: {match: function (b) {
            return(b = b.parent) && b.name == "div" && b.styles["page-break-after"]
        }, styles: "display"}}, requiredContent: "div{page-break-after}"},
        function () {
            function b(a, b, c) {
                var g = CKEDITOR.cleanWord;
                if (g)c(); else {
                    a = CKEDITOR.getUrl(a.config.pasteFromWordCleanupFile || b + "filter/default.js");
                    CKEDITOR.scriptLoader.load(a, c, null, true)
                }
                return!g
            }

            function c(a) {
                a.data.type = "html"
            }

            CKEDITOR.plugins.add("pastefromword", {requires: "clipboard", init: function (a) {
                var f = 0, h = this.path;
                a.addCommand("pastefromword", {canUndo: false, async: true, exec: function (a) {
                    var b = this;
                    f = 1;
                    a.once("beforePaste", c);
                    a.getClipboardData({title: a.lang.pastefromword.title}, function (c) {
                        c &&
                        a.fire("paste", {type: "html", dataValue: c.dataValue});
                        a.fire("afterCommandExec", {name: "pastefromword", command: b, returnValue: !!c})
                    })
                }});
                a.ui.addButton && a.ui.addButton("PasteFromWord", {label: a.lang.pastefromword.toolbar, command: "pastefromword", toolbar: "clipboard,50"});
                a.on("pasteState", function (b) {
                    a.getCommand("pastefromword").setState(b.data)
                });
                a.on("paste", function (c) {
                    var d = c.data, i = d.dataValue;
                    if (i && (f || /(class=\"?Mso|style=\"[^\"]*\bmso\-|w:WordDocument)/.test(i))) {
                        var e = b(a, h, function () {
                            if (e)a.fire("paste",
                                d); else if (!a.config.pasteFromWordPromptCleanup || f || confirm(a.lang.pastefromword.confirmCleanup))d.dataValue = CKEDITOR.cleanWord(i, a)
                        });
                        e && c.cancel()
                    }
                }, null, null, 3)
            }})
        }(), function () {
        var b = {canUndo: false, async: true, exec: function (c) {
            c.getClipboardData({title: c.lang.pastetext.title}, function (a) {
                a && c.fire("paste", {type: "text", dataValue: a.dataValue});
                c.fire("afterCommandExec", {name: "pastetext", command: b, returnValue: !!a})
            })
        }};
        CKEDITOR.plugins.add("pastetext", {requires: "clipboard", init: function (c) {
            c.addCommand("pastetext",
                b);
            c.ui.addButton && c.ui.addButton("PasteText", {label: c.lang.pastetext.button, command: "pastetext", toolbar: "clipboard,40"});
            if (c.config.forcePasteAsPlainText)c.on("beforePaste", function (a) {
                if (a.data.type != "html")a.data.type = "text"
            });
            c.on("pasteState", function (a) {
                c.getCommand("pastetext").setState(a.data)
            })
        }})
    }(), function () {
        var b, c = {modes: {wysiwyg: 1, source: 1}, canUndo: false, readOnly: 1, exec: function (a) {
            var c, h = a.config, g = h.baseHref ? '<base href="' + h.baseHref + '"/>' : "", d = CKEDITOR.env.isCustomDomain();
            if (h.fullPage)c =
                a.getData().replace(/<head>/, "$&" + g).replace(/[^>]*(?=<\/title>)/, "$& &mdash; " + a.lang.preview.preview); else {
                var h = "<body ", i = a.document && a.document.getBody();
                if (i) {
                    i.getAttribute("id") && (h = h + ('id="' + i.getAttribute("id") + '" '));
                    i.getAttribute("class") && (h = h + ('class="' + i.getAttribute("class") + '" '))
                }
                c = a.config.docType + '<html dir="' + a.config.contentsLangDirection + '"><head>' + g + "<title>" + a.lang.preview.preview + "</title>" + CKEDITOR.tools.buildStyleHtml(a.config.contentsCss) + "</head>" + (h + ">") + a.getData() +
                    "</body></html>"
            }
            g = 640;
            h = 420;
            i = 80;
            try {
                var e = window.screen, g = Math.round(e.width * 0.8), h = Math.round(e.height * 0.7), i = Math.round(e.width * 0.1)
            } catch (j) {
            }
            if (!a.fire("contentPreview", a = {dataValue: c}))return false;
            e = "";
            if (d) {
                window._cke_htmlToLoad = a.dataValue;
                e = 'javascript:void( (function(){document.open();document.domain="' + document.domain + '";document.write( window.opener._cke_htmlToLoad );document.close();window.opener._cke_htmlToLoad = null;})() )'
            }
            if (CKEDITOR.env.gecko) {
                window._cke_htmlToLoad = a.dataValue;
                e = b + "preview.html"
            }
            e = window.open(e, null, "toolbar=yes,location=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=" + g + ",height=" + h + ",left=" + i);
            if (!d && !CKEDITOR.env.gecko) {
                var k = e.document;
                k.open();
                k.write(a.dataValue);
                k.close();
                CKEDITOR.env.webkit && setTimeout(function () {
                    k.body.innerHTML = k.body.innerHTML + ""
                }, 0)
            }
            return true
        }};
        CKEDITOR.plugins.add("preview", {init: function (a) {
            if (a.elementMode != CKEDITOR.ELEMENT_MODE_INLINE) {
                b = this.path;
                a.addCommand("preview", c);
                a.ui.addButton && a.ui.addButton("Preview",
                    {label: a.lang.preview.preview, command: "preview", toolbar: "document,40"})
            }
        }})
    }(), CKEDITOR.plugins.add("print", {init: function (b) {
        if (b.elementMode != CKEDITOR.ELEMENT_MODE_INLINE) {
            b.addCommand("print", CKEDITOR.plugins.print);
            b.ui.addButton && b.ui.addButton("Print", {label: b.lang.print.toolbar, command: "print", toolbar: "document,50"})
        }
    }}), CKEDITOR.plugins.print = {exec: function (b) {
        CKEDITOR.env.opera || (CKEDITOR.env.gecko ? b.window.$.print() : b.document.$.execCommand("Print"))
    }, canUndo: !1, readOnly: 1, modes: {wysiwyg: !CKEDITOR.env.opera}},
        CKEDITOR.plugins.add("removeformat", {init: function (b) {
            b.addCommand("removeFormat", CKEDITOR.plugins.removeformat.commands.removeformat);
            b.ui.addButton && b.ui.addButton("RemoveFormat", {label: b.lang.removeformat.toolbar, command: "removeFormat", toolbar: "cleanup,10"})
        }}), CKEDITOR.plugins.removeformat = {commands: {removeformat: {exec: function (b) {
        for (var c = b._.removeFormatRegex || (b._.removeFormatRegex = RegExp("^(?:" + b.config.removeFormatTags.replace(/,/g, "|") + ")$", "i")), a = b._.removeAttributes || (b._.removeAttributes =
            b.config.removeFormatAttributes.split(",")), f = CKEDITOR.plugins.removeformat.filter, h = b.getSelection().getRanges(1), g = h.createIterator(), d; d = g.getNextRange();) {
            d.collapsed || d.enlarge(CKEDITOR.ENLARGE_ELEMENT);
            var i = d.createBookmark(), e = i.startNode, j = i.endNode, k = function (a) {
                for (var d = b.elementPath(a), e = d.elements, g = 1, h; h = e[g]; g++) {
                    if (h.equals(d.block) || h.equals(d.blockLimit))break;
                    c.test(h.getName()) && f(b, h) && a.breakParent(h)
                }
            };
            k(e);
            if (j) {
                k(j);
                for (e = e.getNextSourceNode(true, CKEDITOR.NODE_ELEMENT); e;) {
                    if (e.equals(j))break;
                    k = e.getNextSourceNode(false, CKEDITOR.NODE_ELEMENT);
                    if (!(e.getName() == "img" && e.data("cke-realelement")) && f(b, e))if (c.test(e.getName()))e.remove(1); else {
                        e.removeAttributes(a);
                        b.fire("removeFormatCleanup", e)
                    }
                    e = k
                }
            }
            d.moveToBookmark(i)
        }
        b.forceNextSelectionCheck();
        b.getSelection().selectRanges(h)
    }}}, filter: function (b, c) {
        for (var a = b._.removeFormatFilters || [], f = 0; f < a.length; f++)if (a[f](c) === false)return false;
        return true
    }}, CKEDITOR.editor.prototype.addRemoveFormatFilter = function (b) {
        if (!this._.removeFormatFilters)this._.removeFormatFilters =
            [];
        this._.removeFormatFilters.push(b)
    }, CKEDITOR.config.removeFormatTags = "b,big,code,del,dfn,em,font,i,ins,kbd,q,s,samp,small,span,strike,strong,sub,sup,tt,u,var", CKEDITOR.config.removeFormatAttributes = "class,style,lang,width,height,align,hspace,valign", CKEDITOR.plugins.add("resize", {init: function (b) {
        var c, a, f, h, g = b.config, d = b.ui.spaceId("resizer"), i = b.element ? b.element.getDirection(1) : "ltr";
        !g.resize_dir && (g.resize_dir = "vertical");
        g.resize_maxWidth == void 0 && (g.resize_maxWidth = 3E3);
        g.resize_maxHeight == void 0 && (g.resize_maxHeight = 3E3);
        g.resize_minWidth == void 0 && (g.resize_minWidth = 750);
        g.resize_minHeight == void 0 && (g.resize_minHeight = 250);
        if (g.resize_enabled !== false) {
            var e = null, j = (g.resize_dir == "both" || g.resize_dir == "horizontal") && g.resize_minWidth != g.resize_maxWidth, k = (g.resize_dir == "both" || g.resize_dir == "vertical") && g.resize_minHeight != g.resize_maxHeight, m = function (d) {
                var e = c, l = a, m = e + (d.data.$.screenX - f) * (i == "rtl" ? -1 : 1), d = l + (d.data.$.screenY - h);
                j && (e = Math.max(g.resize_minWidth, Math.min(m, g.resize_maxWidth)));
                k && (l = Math.max(g.resize_minHeight, Math.min(d, g.resize_maxHeight)));
                b.resize(j ? e : null, l)
            }, n = function () {
                CKEDITOR.document.removeListener("mousemove", m);
                CKEDITOR.document.removeListener("mouseup", n);
                if (b.document) {
                    b.document.removeListener("mousemove", m);
                    b.document.removeListener("mouseup", n)
                }
            }, l = CKEDITOR.tools.addFunction(function (d) {
                e || (e = b.getResizable());
                c = e.$.offsetWidth || 0;
                a = e.$.offsetHeight || 0;
                f = d.screenX;
                h = d.screenY;
                g.resize_minWidth > c && (g.resize_minWidth = c);
                g.resize_minHeight > a && (g.resize_minHeight =
                    a);
                CKEDITOR.document.on("mousemove", m);
                CKEDITOR.document.on("mouseup", n);
                if (b.document) {
                    b.document.on("mousemove", m);
                    b.document.on("mouseup", n)
                }
                d.preventDefault && d.preventDefault()
            });
            b.on("destroy", function () {
                CKEDITOR.tools.removeFunction(l)
            });
            b.on("uiSpace", function (a) {
                if (a.data.space == "bottom") {
                    var c = "";
                    j && !k && (c = " cke_resizer_horizontal");
                    !j && k && (c = " cke_resizer_vertical");
                    var e = '<span id="' + d + '" class="cke_resizer' + c + " cke_resizer_" + i + '" title="' + CKEDITOR.tools.htmlEncode(b.lang.common.resize) +
                        '" onmousedown="CKEDITOR.tools.callFunction(' + l + ', event)">' + (i == "ltr" ? "◢" : "◣") + "</span>";
                    i == "ltr" && c == "ltr" ? a.data.html = a.data.html + e : a.data.html = e + a.data.html
                }
            }, b, null, 100);
            b.on("maximize", function (a) {
                b.ui.space("resizer")[a.data == CKEDITOR.TRISTATE_ON ? "hide" : "show"]()
            })
        }
    }}), function () {
        var b = {modes: {wysiwyg: 1, source: 1}, readOnly: 1, exec: function (b) {
            if (b = b.element.$.form)try {
                b.submit()
            } catch (a) {
                b.submit.click && b.submit.click()
            }
        }};
        CKEDITOR.plugins.add("save", {init: function (c) {
            if (c.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE) {
                c.addCommand("save",
                    b).modes = {wysiwyg: !!c.element.$.form};
                c.ui.addButton && c.ui.addButton("Save", {label: c.lang.save.toolbar, command: "save", toolbar: "document,10"})
            }
        }})
    }(), CKEDITOR.plugins.add("menubutton", {requires: "button,menu", onLoad: function () {
        var b = function (b) {
            var a = this._;
            if (a.state !== CKEDITOR.TRISTATE_DISABLED) {
                a.previousState = a.state;
                var f = a.menu;
                if (!f) {
                    f = a.menu = new CKEDITOR.menu(b, {panel: {className: "cke_menu_panel", attributes: {"aria-label": b.lang.common.options}}});
                    f.onHide = CKEDITOR.tools.bind(function () {
                        this.setState(this.modes &&
                            this.modes[b.mode] ? a.previousState : CKEDITOR.TRISTATE_DISABLED)
                    }, this);
                    this.onMenu && f.addListener(this.onMenu)
                }
                if (a.on)f.hide(); else {
                    this.setState(CKEDITOR.TRISTATE_ON);
                    setTimeout(function () {
                        f.show(CKEDITOR.document.getById(a.id), 4)
                    }, 0)
                }
            }
        };
        CKEDITOR.ui.menuButton = CKEDITOR.tools.createClass({base: CKEDITOR.ui.button, $: function (c) {
            delete c.panel;
            this.base(c);
            this.hasArrow = true;
            this.click = b
        }, statics: {handler: {create: function (b) {
            return new CKEDITOR.ui.menuButton(b)
        }}}})
    }, beforeInit: function (b) {
        b.ui.addHandler(CKEDITOR.UI_MENUBUTTON,
            CKEDITOR.ui.menuButton.handler)
    }}), CKEDITOR.UI_MENUBUTTON = "menubutton", function () {
        function b(a, b) {
            var c = 0, f;
            for (f in b)if (b[f] == a) {
                c = 1;
                break
            }
            return c
        }

        var c = "", a = function () {
            function a() {
                e.once("focus", g);
                e.once("blur", b)
            }

            function b(c) {
                var c = c.editor, e = f.getScayt(c), g = c.elementMode == CKEDITOR.ELEMENT_MODE_INLINE;
                if (e) {
                    f.setPaused(c, !e.disabled);
                    f.setControlId(c, e.id);
                    e.destroy(true);
                    delete f.instances[c.name];
                    g && a()
                }
            }

            var e = this, g = function () {
                if (!(typeof f.instances[e.name] != "undefined" || f.instances[e.name] !=
                    null)) {
                    var a = e.config, b = {};
                    b.srcNodeRef = e.editable().$.nodeName == "BODY" ? e.document.getWindow().$.frameElement : e.editable().$;
                    b.assocApp = "CKEDITOR." + CKEDITOR.version + "@" + CKEDITOR.revision;
                    b.customerid = a.scayt_customerid || "1:WvF0D4-UtPqN1-43nkD4-NKvUm2-daQqk3-LmNiI-z7Ysb4-mwry24-T8YrS3-Q2tpq2";
                    b.customDictionaryIds = a.scayt_customDictionaryIds || "";
                    b.userDictionaryName = a.scayt_userDictionaryName || "";
                    b.sLang = a.scayt_sLang || "en_US";
                    b.onLoad = function () {
                        CKEDITOR.env.ie && CKEDITOR.env.version < 8 || this.addStyle(this.selectorCss(),
                            "padding-bottom: 2px !important;");
                        e.editable().hasFocus && !f.isControlRestored(e) && this.focus()
                    };
                    b.onBeforeChange = function () {
                        f.getScayt(e) && !e.checkDirty() && setTimeout(function () {
                            e.resetDirty()
                        }, 0)
                    };
                    a = window.scayt_custom_params;
                    if (typeof a == "object")for (var d in a)b[d] = a[d];
                    if (f.getControlId(e))b.id = f.getControlId(e);
                    var c = new window.scayt(b);
                    c.afterMarkupRemove.push(function (a) {
                        (new CKEDITOR.dom.element(a, c.document)).mergeSiblings()
                    });
                    if (b = f.instances[e.name]) {
                        c.sLang = b.sLang;
                        c.option(b.option());
                        c.paused = b.paused
                    }
                    f.instances[e.name] = c;
                    try {
                        c.setDisabled(f.isPaused(e) === false)
                    } catch (g) {
                    }
                    e.fire("showScaytState")
                }
            };
            e.elementMode == CKEDITOR.ELEMENT_MODE_INLINE ? a() : e.on("contentDom", g);
            e.on("contentDomUnload", function () {
                for (var a = CKEDITOR.document.getElementsByTag("script"), b = /^dojoIoScript(\d+)$/i, d = /^https?:\/\/svc\.webspellchecker\.net\/spellcheck\/script\/ssrv\.cgi/i, c = 0; c < a.count(); c++) {
                    var e = a.getItem(c), f = e.getId(), g = e.getAttribute("src");
                    f && (g && f.match(b) && g.match(d)) && e.remove()
                }
            });
            e.on("beforeCommandExec",
                function (a) {
                    a.data.name == "source" && e.mode == "source" && f.markControlRestore(e)
                });
            e.on("afterCommandExec", function (a) {
                f.isScaytEnabled(e) && e.mode == "wysiwyg" && (a.data.name == "undo" || a.data.name == "redo") && window.setTimeout(function () {
                    f.getScayt(e).refresh()
                }, 10)
            });
            e.on("destroy", b);
            e.on("setData", b);
            e.on("insertElement", function () {
                var a = f.getScayt(e);
                if (f.isScaytEnabled(e)) {
                    CKEDITOR.env.ie && e.getSelection().unlock(true);
                    window.setTimeout(function () {
                        a.focus();
                        a.refresh()
                    }, 10)
                }
            }, this, null, 50);
            e.on("insertHtml",
                function () {
                    var a = f.getScayt(e);
                    if (f.isScaytEnabled(e)) {
                        CKEDITOR.env.ie && e.getSelection().unlock(true);
                        window.setTimeout(function () {
                            a.focus();
                            a.refresh()
                        }, 10)
                    }
                }, this, null, 50);
            e.on("scaytDialog", function (a) {
                a.data.djConfig = window.djConfig;
                a.data.scayt_control = f.getScayt(e);
                a.data.tab = c;
                a.data.scayt = window.scayt
            });
            var h = e.dataProcessor;
            (h = h && h.htmlFilter) && h.addRules({elements: {span: function (a) {
                if (a.attributes["data-scayt_word"] && a.attributes["data-scaytid"]) {
                    delete a.name;
                    return a
                }
            }}});
            h = CKEDITOR.plugins.undo.Image.prototype;
            h.equals = CKEDITOR.tools.override(h.equals, function (a) {
                return function (b) {
                    var d = this.contents, c = b.contents, e = f.getScayt(this.editor);
                    if (e && f.isScaytReady(this.editor)) {
                        this.contents = e.reset(d) || "";
                        b.contents = e.reset(c) || ""
                    }
                    e = a.apply(this, arguments);
                    this.contents = d;
                    b.contents = c;
                    return e
                }
            });
            e.document && (e.elementMode != CKEDITOR.ELEMENT_MODE_INLINE || e.focusManager.hasFocus) && g()
        };
        CKEDITOR.plugins.scayt = {engineLoaded: false, instances: {}, controlInfo: {}, setControlInfo: function (a, b) {
            a && (a.name && typeof this.controlInfo[a.name] !=
                "object") && (this.controlInfo[a.name] = {});
            for (var c in b)this.controlInfo[a.name][c] = b[c]
        }, isControlRestored: function (a) {
            return a && a.name && this.controlInfo[a.name] ? this.controlInfo[a.name].restored : false
        }, markControlRestore: function (a) {
            this.setControlInfo(a, {restored: true})
        }, setControlId: function (a, b) {
            this.setControlInfo(a, {id: b})
        }, getControlId: function (a) {
            return a && a.name && this.controlInfo[a.name] && this.controlInfo[a.name].id ? this.controlInfo[a.name].id : null
        }, setPaused: function (a, b) {
            this.setControlInfo(a,
                {paused: b})
        }, isPaused: function (a) {
            if (a && a.name && this.controlInfo[a.name])return this.controlInfo[a.name].paused
        }, getScayt: function (a) {
            return this.instances[a.name]
        }, isScaytReady: function (a) {
            return this.engineLoaded === true && "undefined" !== typeof window.scayt && this.getScayt(a)
        }, isScaytEnabled: function (a) {
            return(a = this.getScayt(a)) ? a.disabled === false : false
        }, getUiTabs: function (a) {
            var b = [], c = a.config.scayt_uiTabs || "1,1,1", c = c.split(",");
            c[3] = "1";
            for (var f = 0; f < 4; f++)b[f] = typeof window.scayt != "undefined" &&
                typeof window.scayt.uiTags != "undefined" ? parseInt(c[f], 10) && window.scayt.uiTags[f] : parseInt(c[f], 10);
            typeof a.plugins.wsc == "object" ? b.push(1) : b.push(0);
            return b
        }, loadEngine: function (b) {
            if (CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 || CKEDITOR.env.opera || CKEDITOR.env.air)return b.fire("showScaytState");
            if (this.engineLoaded === true)return a.apply(b);
            if (this.engineLoaded == -1)return CKEDITOR.on("scaytReady", function () {
                a.apply(b)
            });
            CKEDITOR.on("scaytReady", a, b);
            CKEDITOR.on("scaytReady", function () {
                this.engineLoaded =
                    true
            }, this, null, 0);
            this.engineLoaded = -1;
            var c = document.location.protocol, c = c.search(/https?:/) != -1 ? c : "http:", c = b.config.scayt_srcUrl || c + "//svc.webspellchecker.net/scayt26/loader__base.js", e = f.parseUrl(c).path + "/";
            if (window.scayt == void 0) {
                CKEDITOR._djScaytConfig = {baseUrl: e, addOnLoad: [function () {
                    CKEDITOR.fireOnce("scaytReady")
                }], isDebug: false};
                CKEDITOR.document.getHead().append(CKEDITOR.document.createElement("script", {attributes: {type: "text/javascript", async: "true", src: c}}))
            } else CKEDITOR.fireOnce("scaytReady");
            return null
        }, parseUrl: function (a) {
            var b;
            return a.match && (b = a.match(/(.*)[\/\\](.*?\.\w+)$/)) ? {path: b[1], file: b[2]} : a
        }};
        var f = CKEDITOR.plugins.scayt, h = function (a, b, c, f, g, h, n) {
            a.addCommand(f, g);
            a.addMenuItem(f, {label: c, command: f, group: h, order: n})
        }, g = {preserveState: true, editorFocus: false, canUndo: false, exec: function (a) {
            if (f.isScaytReady(a)) {
                var b = f.isScaytEnabled(a);
                this.setState(b ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_ON);
                a = f.getScayt(a);
                a.focus();
                a.setDisabled(b)
            } else if (!a.config.scayt_autoStartup &&
                f.engineLoaded >= 0) {
                a.focus();
                this.setState(CKEDITOR.TRISTATE_DISABLED);
                f.loadEngine(a)
            }
        }};
        CKEDITOR.plugins.add("scayt", {requires: "menubutton,dialog", beforeInit: function (a) {
            var b = a.config.scayt_contextMenuItemsOrder || "suggest|moresuggest|control", c = "";
            if ((b = b.split("|")) && b.length)for (var f = 0; f < b.length; f++)c = c + ("scayt_" + b[f] + (b.length != parseInt(f, 10) + 1 ? "," : ""));
            a.config.menu_groups = c + "," + a.config.menu_groups
        }, checkEnvironment: function () {
            return CKEDITOR.env.opera || CKEDITOR.env.air ? 0 : 1
        }, init: function (a) {
            var i =
                a.dataProcessor && a.dataProcessor.dataFilter, e = {elements: {span: function (a) {
                var b = a.attributes;
                b && b["data-scaytid"] && delete a.name
            }}};
            i && i.addRules(e);
            var j = {}, k = {}, m = a.addCommand("scaytcheck", g);
            CKEDITOR.dialog.add("scaytcheck", CKEDITOR.getUrl(this.path + "dialogs/options.js"));
            i = f.getUiTabs(a);
            a.addMenuGroup("scaytButton");
            a.addMenuGroup("scayt_suggest", -10);
            a.addMenuGroup("scayt_moresuggest", -9);
            a.addMenuGroup("scayt_control", -8);
            var e = {}, n = a.lang.scayt;
            e.scaytToggle = {label: n.enable, command: "scaytcheck",
                group: "scaytButton"};
            if (i[0] == 1)e.scaytOptions = {label: n.options, group: "scaytButton", onClick: function () {
                c = "options";
                a.openDialog("scaytcheck")
            }};
            if (i[1] == 1)e.scaytLangs = {label: n.langs, group: "scaytButton", onClick: function () {
                c = "langs";
                a.openDialog("scaytcheck")
            }};
            if (i[2] == 1)e.scaytDict = {label: n.dictionariesTab, group: "scaytButton", onClick: function () {
                c = "dictionaries";
                a.openDialog("scaytcheck")
            }};
            e.scaytAbout = {label: a.lang.scayt.about, group: "scaytButton", onClick: function () {
                c = "about";
                a.openDialog("scaytcheck")
            }};
            if (i[4] == 1)e.scaytWSC = {label: a.lang.wsc.toolbar, group: "scaytButton", command: "checkspell"};
            a.addMenuItems(e);
            a.ui.add("Scayt", CKEDITOR.UI_MENUBUTTON, {label: n.title, title: CKEDITOR.env.opera ? n.opera_title : n.title, modes: {wysiwyg: this.checkEnvironment()}, toolbar: "spellchecker,20", onRender: function () {
                m.on("state", function () {
                    this.setState(m.state)
                }, this)
            }, onMenu: function () {
                var b = f.isScaytEnabled(a);
                a.getMenuItem("scaytToggle").label = n[b ? "disable" : "enable"];
                var c = f.getUiTabs(a);
                return{scaytToggle: CKEDITOR.TRISTATE_OFF,
                    scaytOptions: b && c[0] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, scaytLangs: b && c[1] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, scaytDict: b && c[2] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, scaytAbout: b && c[3] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, scaytWSC: c[4] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED}
            }});
            a.contextMenu && a.addMenuItems && a.contextMenu.addListener(function (c, e) {
                if (!f.isScaytEnabled(a) || e.getRanges()[0].checkReadOnly())return null;
                var g = f.getScayt(a),
                    i = g.getScaytNode();
                if (!i)return null;
                var p = g.getWord(i);
                if (!p)return null;
                var m = g.getLang(), z = a.config.scayt_contextCommands || "all", p = window.scayt.getSuggestion(p, m), z = z.split("|"), x;
                for (x in j) {
                    delete a._.menuItems[x];
                    delete a.commands[x]
                }
                for (x in k) {
                    delete a._.menuItems[x];
                    delete a.commands[x]
                }
                if (!p || !p.length) {
                    h(a, "no_sugg", n.noSuggestions, "scayt_no_sugg", {exec: function () {
                    }}, "scayt_control", 1, true);
                    k.scayt_no_sugg = CKEDITOR.TRISTATE_OFF
                } else {
                    j = {};
                    k = {};
                    x = a.config.scayt_moreSuggestions || "on";
                    var m =
                        false, w = a.config.scayt_maxSuggestions;
                    typeof w != "number" && (w = 5);
                    !w && (w = p.length);
                    for (var v = 0, r = p.length; v < r; v = v + 1) {
                        var u = "scayt_suggestion_" + p[v].replace(" ", "_"), A = function (a, b) {
                            return{exec: function () {
                                g.replace(a, b)
                            }}
                        }(i, p[v]);
                        if (v < w) {
                            h(a, "button_" + u, p[v], u, A, "scayt_suggest", v + 1);
                            k[u] = CKEDITOR.TRISTATE_OFF
                        } else if (x == "on") {
                            h(a, "button_" + u, p[v], u, A, "scayt_moresuggest", v + 1);
                            j[u] = CKEDITOR.TRISTATE_OFF;
                            m = true
                        }
                    }
                    if (m) {
                        a.addMenuItem("scayt_moresuggest", {label: n.moreSuggestions, group: "scayt_moresuggest",
                            order: 10, getItems: function () {
                                return j
                            }});
                        k.scayt_moresuggest = CKEDITOR.TRISTATE_OFF
                    }
                }
                if (b("all", z) || b("ignore", z)) {
                    h(a, "ignore", n.ignore, "scayt_ignore", {exec: function () {
                        g.ignore(i)
                    }}, "scayt_control", 2);
                    k.scayt_ignore = CKEDITOR.TRISTATE_OFF
                }
                if (b("all", z) || b("ignoreall", z)) {
                    h(a, "ignore_all", n.ignoreAll, "scayt_ignore_all", {exec: function () {
                        g.ignoreAll(i)
                    }}, "scayt_control", 3);
                    k.scayt_ignore_all = CKEDITOR.TRISTATE_OFF
                }
                if (b("all", z) || b("add", z)) {
                    h(a, "add_word", n.addWord, "scayt_add_word", {exec: function () {
                            window.scayt.addWordToUserDictionary(i)
                        }},
                        "scayt_control", 4);
                    k.scayt_add_word = CKEDITOR.TRISTATE_OFF
                }
                g.fireOnContextMenu && g.fireOnContextMenu(a);
                return k
            });
            i = function (b) {
                b.removeListener();
                CKEDITOR.env.opera || CKEDITOR.env.air ? m.setState(CKEDITOR.TRISTATE_DISABLED) : m.setState(f.isScaytEnabled(a) ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF)
            };
            a.on("showScaytState", i);
            a.on("instanceReady", i);
            if (a.config.scayt_autoStartup)a.on("instanceReady", function () {
                f.loadEngine(a)
            })
        }, afterInit: function (a) {
            var b, c = function (a) {
                if (a.hasAttribute("data-scaytid"))return false
            };
            a._.elementsPath && (b = a._.elementsPath.filters) && b.push(c);
            a.addRemoveFormatFilter && a.addRemoveFormatFilter(c)
        }})
    }(), function () {
        CKEDITOR.plugins.add("selectall", {init: function (b) {
            b.addCommand("selectAll", {modes: {wysiwyg: 1, source: 1}, exec: function (b) {
                var a = b.editable();
                if (a.is("textarea")) {
                    b = a.$;
                    if (CKEDITOR.env.ie)b.createTextRange().execCommand("SelectAll"); else {
                        b.selectionStart = 0;
                        b.selectionEnd = b.value.length
                    }
                    b.focus()
                } else {
                    if (a.is("body"))b.document.$.execCommand("SelectAll", false, null); else {
                        var f =
                            b.createRange();
                        f.selectNodeContents(a);
                        f.select()
                    }
                    b.forceNextSelectionCheck();
                    b.selectionChange()
                }
            }, canUndo: false});
            b.ui.addButton && b.ui.addButton("SelectAll", {label: b.lang.selectall.toolbar, command: "selectAll", toolbar: "selection,10"})
        }})
    }(), function () {
        var b = {readOnly: 1, preserveState: true, editorFocus: false, exec: function (b) {
            this.toggleState();
            this.refresh(b)
        }, refresh: function (b) {
            if (b.document) {
                var a = this.state == CKEDITOR.TRISTATE_ON && (b.elementMode != CKEDITOR.ELEMENT_MODE_INLINE || b.focusManager.hasFocus) ?
                    "attachClass" : "removeClass";
                b.editable()[a]("cke_show_blocks")
            }
        }};
        CKEDITOR.plugins.add("showblocks", {onLoad: function () {
            function b(a) {
                return".%1.%2 p,.%1.%2 div,.%1.%2 pre,.%1.%2 address,.%1.%2 blockquote,.%1.%2 h1,.%1.%2 h2,.%1.%2 h3,.%1.%2 h4,.%1.%2 h5,.%1.%2 h6{background-position: top %3;padding-%3: 8px;}".replace(/%1/g, "cke_show_blocks").replace(/%2/g, "cke_contents_" + a).replace(/%3/g, a == "rtl" ? "right" : "left")
            }

            CKEDITOR.addCss(".%2 p,.%2 div,.%2 pre,.%2 address,.%2 blockquote,.%2 h1,.%2 h2,.%2 h3,.%2 h4,.%2 h5,.%2 h6{background-repeat: no-repeat;border: 1px dotted gray;padding-top: 8px;}.%2 p{%1p.png);}.%2 div{%1div.png);}.%2 pre{%1pre.png);}.%2 address{%1address.png);}.%2 blockquote{%1blockquote.png);}.%2 h1{%1h1.png);}.%2 h2{%1h2.png);}.%2 h3{%1h3.png);}.%2 h4{%1h4.png);}.%2 h5{%1h5.png);}.%2 h6{%1h6.png);}".replace(/%1/g,
                "background-image: url(" + CKEDITOR.getUrl(this.path) + "images/block_").replace(/%2/g, "cke_show_blocks ") + b("ltr") + b("rtl"))
        }, init: function (c) {
            if (!c.blockless) {
                var a = c.addCommand("showblocks", b);
                a.canUndo = false;
                c.config.startupOutlineBlocks && a.setState(CKEDITOR.TRISTATE_ON);
                c.ui.addButton && c.ui.addButton("ShowBlocks", {label: c.lang.showblocks.toolbar, command: "showblocks", toolbar: "tools,20"});
                c.on("mode", function () {
                    a.state != CKEDITOR.TRISTATE_DISABLED && a.refresh(c)
                });
                if (c.elementMode == CKEDITOR.ELEMENT_MODE_INLINE) {
                    var f =
                        function () {
                            a.refresh(c)
                        };
                    c.on("focus", f);
                    c.on("blur", f)
                }
                c.on("contentDom", function () {
                    a.state != CKEDITOR.TRISTATE_DISABLED && a.refresh(c)
                })
            }
        }})
    }(), function () {
        var b = {preserveState: true, editorFocus: false, readOnly: 1, exec: function (b) {
            this.toggleState();
            this.refresh(b)
        }, refresh: function (b) {
            if (b.document) {
                var a = this.state == CKEDITOR.TRISTATE_ON ? "attachClass" : "removeClass";
                b.editable()[a]("cke_show_borders")
            }
        }};
        CKEDITOR.plugins.add("showborders", {modes: {wysiwyg: 1}, onLoad: function () {
            var b;
            b = (CKEDITOR.env.ie6Compat ?
                [".%1 table.%2,", ".%1 table.%2 td, .%1 table.%2 th", "{", "border : #d3d3d3 1px dotted", "}"] : [".%1 table.%2,", ".%1 table.%2 > tr > td, .%1 table.%2 > tr > th,", ".%1 table.%2 > tbody > tr > td, .%1 table.%2 > tbody > tr > th,", ".%1 table.%2 > thead > tr > td, .%1 table.%2 > thead > tr > th,", ".%1 table.%2 > tfoot > tr > td, .%1 table.%2 > tfoot > tr > th", "{", "border : #d3d3d3 1px dotted", "}"]).join("").replace(/%2/g, "cke_show_border").replace(/%1/g, "cke_show_borders ");
            CKEDITOR.addCss(b)
        }, init: function (c) {
            var a =
                c.addCommand("showborders", b);
            a.canUndo = false;
            c.config.startupShowBorders !== false && a.setState(CKEDITOR.TRISTATE_ON);
            c.on("mode", function () {
                a.state != CKEDITOR.TRISTATE_DISABLED && a.refresh(c)
            }, null, null, 100);
            c.on("contentDom", function () {
                a.state != CKEDITOR.TRISTATE_DISABLED && a.refresh(c)
            });
            c.on("removeFormatCleanup", function (a) {
                a = a.data;
                c.getCommand("showborders").state == CKEDITOR.TRISTATE_ON && (a.is("table") && (!a.hasAttribute("border") || parseInt(a.getAttribute("border"), 10) <= 0)) && a.addClass("cke_show_border")
            })
        },
            afterInit: function (b) {
                var a = b.dataProcessor, b = a && a.dataFilter, a = a && a.htmlFilter;
                b && b.addRules({elements: {table: function (a) {
                    var a = a.attributes, b = a["class"], c = parseInt(a.border, 10);
                    if ((!c || c <= 0) && (!b || b.indexOf("cke_show_border") == -1))a["class"] = (b || "") + " cke_show_border"
                }}});
                a && a.addRules({elements: {table: function (a) {
                    var a = a.attributes, b = a["class"];
                    b && (a["class"] = b.replace("cke_show_border", "").replace(/\s{2}/, " ").replace(/^\s+|\s+$/, ""))
                }}})
            }});
        CKEDITOR.on("dialogDefinition", function (b) {
            var a = b.data.name;
            if (a == "table" || a == "tableProperties") {
                b = b.data.definition;
                a = b.getContents("info").get("txtBorder");
                a.commit = CKEDITOR.tools.override(a.commit, function (a) {
                    return function (b, c) {
                        a.apply(this, arguments);
                        var d = parseInt(this.getValue(), 10);
                        c[!d || d <= 0 ? "addClass" : "removeClass"]("cke_show_border")
                    }
                });
                if (b = (b = b.getContents("advanced")) && b.get("advCSSClasses")) {
                    b.setup = CKEDITOR.tools.override(b.setup, function (a) {
                        return function () {
                            a.apply(this, arguments);
                            this.setValue(this.getValue().replace(/cke_show_border/,
                                ""))
                        }
                    });
                    b.commit = CKEDITOR.tools.override(b.commit, function (a) {
                        return function (b, c) {
                            a.apply(this, arguments);
                            parseInt(c.getAttribute("border"), 10) || c.addClass("cke_show_border")
                        }
                    })
                }
            }
        })
    }(), CKEDITOR.plugins.add("smiley", {requires: "dialog", init: function (b) {
        b.config.smiley_path = b.config.smiley_path || this.path + "images/";
        b.addCommand("smiley", new CKEDITOR.dialogCommand("smiley", {allowedContent: "img[alt,height,!src,title,width]", requiredContent: "img"}));
        b.ui.addButton && b.ui.addButton("Smiley", {label: b.lang.smiley.toolbar,
            command: "smiley", toolbar: "insert,50"});
        CKEDITOR.dialog.add("smiley", this.path + "dialogs/smiley.js")
    }}), CKEDITOR.config.smiley_images = "regular_smile.gif sad_smile.gif wink_smile.gif teeth_smile.gif confused_smile.gif tongue_smile.gif embarrassed_smile.gif omg_smile.gif whatchutalkingabout_smile.gif angry_smile.gif angel_smile.gif shades_smile.gif devil_smile.gif cry_smile.gif lightbulb.gif thumbs_down.gif thumbs_up.gif heart.gif broken_heart.gif kiss.gif envelope.gif".split(" "), CKEDITOR.config.smiley_descriptions =
        "smiley;sad;wink;laugh;frown;cheeky;blush;surprise;indecision;angry;angel;cool;devil;crying;enlightened;no;yes;heart;broken heart;kiss;mail".split(";"), function () {
        CKEDITOR.plugins.add("sourcearea", {init: function (c) {
            function a() {
                this.hide();
                this.setStyle("height", this.getParent().$.clientHeight + "px");
                this.setStyle("width", this.getParent().$.clientWidth + "px");
                this.show()
            }

            if (c.elementMode != CKEDITOR.ELEMENT_MODE_INLINE) {
                var f = CKEDITOR.plugins.sourcearea;
                c.addMode("source", function (f) {
                    var g = c.ui.space("contents").getDocument().createElement("textarea");
                    g.setStyles(CKEDITOR.tools.extend({width: CKEDITOR.env.ie7Compat ? "99%" : "100%", height: "100%", resize: "none", outline: "none", "text-align": "left"}, CKEDITOR.tools.cssVendorPrefix("tab-size", c.config.sourceAreaTabSize || 4)));
                    g.setAttribute("dir", "ltr");
                    g.addClass("cke_source cke_reset cke_enable_context_menu");
                    c.ui.space("contents").append(g);
                    g = c.editable(new b(c, g));
                    g.setData(c.getData(1));
                    if (CKEDITOR.env.ie) {
                        g.attachListener(c, "resize", a, g);
                        g.attachListener(CKEDITOR.document.getWindow(), "resize", a, g);
                        CKEDITOR.tools.setTimeout(a,
                            0, g)
                    }
                    c.fire("ariaWidget", this);
                    f()
                });
                c.addCommand("source", f.commands.source);
                c.ui.addButton && c.ui.addButton("Source", {label: c.lang.sourcearea.toolbar, command: "source", toolbar: "mode,10"});
                c.on("mode", function () {
                    c.getCommand("source").setState(c.mode == "source" ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF)
                })
            }
        }});
        var b = CKEDITOR.tools.createClass({base: CKEDITOR.editable, proto: {setData: function (b) {
            this.setValue(b);
            this.editor.fire("dataReady")
        }, getData: function () {
            return this.getValue()
        }, insertHtml: function () {
        },
            insertElement: function () {
            }, insertText: function () {
            }, setReadOnly: function (b) {
                this[(b ? "set" : "remove") + "Attribute"]("readOnly", "readonly")
            }, detach: function () {
                b.baseProto.detach.call(this);
                this.clearCustomData();
                this.remove()
            }}})
    }(), CKEDITOR.plugins.sourcearea = {commands: {source: {modes: {wysiwyg: 1, source: 1}, editorFocus: !1, readOnly: 1, exec: function (b) {
        b.mode == "wysiwyg" && b.fire("saveSnapshot");
        b.getCommand("source").setState(CKEDITOR.TRISTATE_DISABLED);
        b.setMode(b.mode == "source" ? "wysiwyg" : "source")
    }, canUndo: !1}}},
        CKEDITOR.plugins.add("specialchar", {availableLangs: {bg: 1, ca: 1, cs: 1, cy: 1, de: 1, el: 1, en: 1, eo: 1, es: 1, et: 1, fa: 1, fi: 1, fr: 1, "fr-ca": 1, gl: 1, he: 1, hr: 1, hu: 1, it: 1, ku: 1, lv: 1, nb: 1, nl: 1, no: 1, pl: 1, "pt-br": 1, ru: 1, sk: 1, sq: 1, sv: 1, th: 1, tr: 1, ug: 1, "zh-cn": 1}, requires: "dialog", init: function (b) {
            var c = this;
            CKEDITOR.dialog.add("specialchar", this.path + "dialogs/specialchar.js");
            b.addCommand("specialchar", {exec: function () {
                var a = b.langCode, a = c.availableLangs[a] ? a : c.availableLangs[a.replace(/-.*/, "")] ? a.replace(/-.*/, "") : "en";
                CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(c.path + "dialogs/lang/" + a + ".js"), function () {
                    CKEDITOR.tools.extend(b.lang.specialchar, c.langEntries[a]);
                    b.openDialog("specialchar")
                })
            }, modes: {wysiwyg: 1}, canUndo: false});
            b.ui.addButton && b.ui.addButton("SpecialChar", {label: b.lang.specialchar.toolbar, command: "specialchar", toolbar: "insert,50"})
        }}), CKEDITOR.config.specialChars = "! &quot; # $ % &amp; ' ( ) * + - . / 0 1 2 3 4 5 6 7 8 9 : ; &lt; = &gt; ? @ A B C D E F G H I J K L M N O P Q R S T U V W X Y Z [ ] ^ _ ` a b c d e f g h i j k l m n o p q r s t u v w x y z { | } ~ &euro; &lsquo; &rsquo; &ldquo; &rdquo; &ndash; &mdash; &iexcl; &cent; &pound; &curren; &yen; &brvbar; &sect; &uml; &copy; &ordf; &laquo; &not; &reg; &macr; &deg; &sup2; &sup3; &acute; &micro; &para; &middot; &cedil; &sup1; &ordm; &raquo; &frac14; &frac12; &frac34; &iquest; &Agrave; &Aacute; &Acirc; &Atilde; &Auml; &Aring; &AElig; &Ccedil; &Egrave; &Eacute; &Ecirc; &Euml; &Igrave; &Iacute; &Icirc; &Iuml; &ETH; &Ntilde; &Ograve; &Oacute; &Ocirc; &Otilde; &Ouml; &times; &Oslash; &Ugrave; &Uacute; &Ucirc; &Uuml; &Yacute; &THORN; &szlig; &agrave; &aacute; &acirc; &atilde; &auml; &aring; &aelig; &ccedil; &egrave; &eacute; &ecirc; &euml; &igrave; &iacute; &icirc; &iuml; &eth; &ntilde; &ograve; &oacute; &ocirc; &otilde; &ouml; &divide; &oslash; &ugrave; &uacute; &ucirc; &uuml; &yacute; &thorn; &yuml; &OElig; &oelig; &#372; &#374 &#373 &#375; &sbquo; &#8219; &bdquo; &hellip; &trade; &#9658; &bull; &rarr; &rArr; &hArr; &diams; &asymp;".split(" "),
        function () {
            CKEDITOR.plugins.add("stylescombo", {requires: "richcombo", init: function (b) {
                var c = b.config, a = b.lang.stylescombo, f = {}, h = [], g = [];
                b.on("stylesSet", function (a) {
                    if (a = a.data.styles) {
                        for (var i, e, j = 0, k = a.length; j < k; j++) {
                            i = a[j];
                            if (!(b.blockless && i.element in CKEDITOR.dtd.$block)) {
                                e = i.name;
                                i = new CKEDITOR.style(i);
                                if (!b.filter.customConfig || b.filter.check(i)) {
                                    i._name = e;
                                    i._.enterMode = c.enterMode;
                                    i._.weight = j + (i.type == CKEDITOR.STYLE_OBJECT ? 1 : i.type == CKEDITOR.STYLE_BLOCK ? 2 : 3) * 1E3;
                                    f[e] = i;
                                    h.push(i);
                                    g.push(i)
                                }
                            }
                        }
                        h.sort(function (a, b) {
                            return a._.weight - b._.weight
                        })
                    }
                });
                b.ui.addRichCombo("Styles", {label: a.label, title: a.panelTitle, toolbar: "styles,10", allowedContent: g, panel: {css: [CKEDITOR.skin.getPath("editor")].concat(c.contentsCss), multiSelect: true, attributes: {"aria-label": a.panelTitle}}, init: function () {
                    var b, c, e, f, g, m;
                    g = 0;
                    for (m = h.length; g < m; g++) {
                        b = h[g];
                        c = b._name;
                        f = b.type;
                        if (f != e) {
                            this.startGroup(a["panelTitle" + f]);
                            e = f
                        }
                        this.add(c, b.type == CKEDITOR.STYLE_OBJECT ? c : b.buildPreview(), c)
                    }
                    this.commit()
                }, onClick: function (a) {
                    b.focus();
                    b.fire("saveSnapshot");
                    var a = f[a], c = b.elementPath();
                    b[a.checkActive(c) ? "removeStyle" : "applyStyle"](a);
                    b.fire("saveSnapshot")
                }, onRender: function () {
                    b.on("selectionChange", function (a) {
                        for (var b = this.getValue(), a = a.data.path.elements, c = 0, g = a.length, h; c < g; c++) {
                            h = a[c];
                            for (var m in f)if (f[m].checkElementRemovable(h, true)) {
                                m != b && this.setValue(m);
                                return
                            }
                        }
                        this.setValue("")
                    }, this)
                }, onOpen: function () {
                    var c = b.getSelection().getSelectedElement(), c = b.elementPath(c), g = [0, 0, 0, 0];
                    this.showAll();
                    this.unmarkAll();
                    for (var e in f) {
                        var h =
                            f[e], k = h.type;
                        if (k == CKEDITOR.STYLE_BLOCK && !c.isContextFor(h.element))this.hideItem(e); else {
                            if (h.checkActive(c))this.mark(e); else if (k == CKEDITOR.STYLE_OBJECT && !h.checkApplicable(c)) {
                                this.hideItem(e);
                                g[k]--
                            }
                            g[k]++
                        }
                    }
                    g[CKEDITOR.STYLE_BLOCK] || this.hideGroup(a["panelTitle" + CKEDITOR.STYLE_BLOCK]);
                    g[CKEDITOR.STYLE_INLINE] || this.hideGroup(a["panelTitle" + CKEDITOR.STYLE_INLINE]);
                    g[CKEDITOR.STYLE_OBJECT] || this.hideGroup(a["panelTitle" + CKEDITOR.STYLE_OBJECT])
                }, reset: function () {
                    f = {};
                    h = []
                }})
            }})
        }(), function () {
        function b(a) {
            return{editorFocus: false,
                canUndo: false, modes: {wysiwyg: 1}, exec: function (b) {
                    if (b.editable().hasFocus) {
                        var c = b.getSelection(), f;
                        if (f = (new CKEDITOR.dom.elementPath(c.getCommonAncestor(), c.root)).contains({td: 1, th: 1}, 1)) {
                            var c = b.createRange(), e = CKEDITOR.tools.tryThese(function () {
                                var b = f.getParent().$.cells[f.$.cellIndex + (a ? -1 : 1)];
                                b.parentNode.parentNode;
                                return b
                            }, function () {
                                var b = f.getParent(), b = b.getAscendant("table").$.rows[b.$.rowIndex + (a ? -1 : 1)];
                                return b.cells[a ? b.cells.length - 1 : 0]
                            });
                            if (!e && !a) {
                                for (var j = f.getAscendant("table").$,
                                         e = f.getParent().$.cells, j = new CKEDITOR.dom.element(j.insertRow(-1), b.document), k = 0, m = e.length; k < m; k++) {
                                    var n = j.append((new CKEDITOR.dom.element(e[k], b.document)).clone(false, false));
                                    !CKEDITOR.env.ie && n.appendBogus()
                                }
                                c.moveToElementEditStart(j)
                            } else if (e) {
                                e = new CKEDITOR.dom.element(e);
                                c.moveToElementEditStart(e);
                                (!c.checkStartOfBlock() || !c.checkEndOfBlock()) && c.selectNodeContents(e)
                            } else return true;
                            c.select(true);
                            return true
                        }
                    }
                    return false
                }}
        }

        var c = {editorFocus: false, modes: {wysiwyg: 1, source: 1}}, a = {exec: function (a) {
            a.container.focusNext(true,
                a.tabIndex)
        }}, f = {exec: function (a) {
            a.container.focusPrevious(true, a.tabIndex)
        }};
        CKEDITOR.plugins.add("tab", {init: function (h) {
            for (var g = h.config.enableTabKeyTools !== false, d = h.config.tabSpaces || 0, i = ""; d--;)i = i + " ";
            if (i)h.on("key", function (a) {
                if (a.data.keyCode == 9) {
                    h.insertHtml(i);
                    a.cancel()
                }
            });
            if (g)h.on("key", function (a) {
                (a.data.keyCode == 9 && h.execCommand("selectNextCell") || a.data.keyCode == CKEDITOR.SHIFT + 9 && h.execCommand("selectPreviousCell")) && a.cancel()
            });
            h.addCommand("blur", CKEDITOR.tools.extend(a,
                c));
            h.addCommand("blurBack", CKEDITOR.tools.extend(f, c));
            h.addCommand("selectNextCell", b());
            h.addCommand("selectPreviousCell", b(true))
        }})
    }(), CKEDITOR.dom.element.prototype.focusNext = function (b, c) {
        var a = c === void 0 ? this.getTabIndex() : c, f, h, g, d, i, e;
        if (a <= 0)for (i = this.getNextSourceNode(b, CKEDITOR.NODE_ELEMENT); i;) {
            if (i.isVisible() && i.getTabIndex() === 0) {
                g = i;
                break
            }
            i = i.getNextSourceNode(false, CKEDITOR.NODE_ELEMENT)
        } else for (i = this.getDocument().getBody().getFirst(); i = i.getNextSourceNode(false, CKEDITOR.NODE_ELEMENT);) {
            if (!f)if (!h &&
                i.equals(this)) {
                h = true;
                if (b) {
                    if (!(i = i.getNextSourceNode(true, CKEDITOR.NODE_ELEMENT)))break;
                    f = 1
                }
            } else h && !this.contains(i) && (f = 1);
            if (i.isVisible() && !((e = i.getTabIndex()) < 0)) {
                if (f && e == a) {
                    g = i;
                    break
                }
                if (e > a && (!g || !d || e < d)) {
                    g = i;
                    d = e
                } else if (!g && e === 0) {
                    g = i;
                    d = e
                }
            }
        }
        g && g.focus()
    }, CKEDITOR.dom.element.prototype.focusPrevious = function (b, c) {
        for (var a = c === void 0 ? this.getTabIndex() : c, f, h, g, d = 0, i, e = this.getDocument().getBody().getLast(); e = e.getPreviousSourceNode(false, CKEDITOR.NODE_ELEMENT);) {
            if (!f)if (!h && e.equals(this)) {
                h =
                    true;
                if (b) {
                    if (!(e = e.getPreviousSourceNode(true, CKEDITOR.NODE_ELEMENT)))break;
                    f = 1
                }
            } else h && !this.contains(e) && (f = 1);
            if (e.isVisible() && !((i = e.getTabIndex()) < 0))if (a <= 0) {
                if (f && i === 0) {
                    g = e;
                    break
                }
                if (i > d) {
                    g = e;
                    d = i
                }
            } else {
                if (f && i == a) {
                    g = e;
                    break
                }
                if (i < a && (!g || i > d)) {
                    g = e;
                    d = i
                }
            }
        }
        g && g.focus()
    }, CKEDITOR.plugins.add("table", {requires: "dialog", init: function (b) {
        function c(a) {
            return CKEDITOR.tools.extend(a || {}, {contextSensitive: 1, refresh: function (a, b) {
                this.setState(b.contains("table", 1) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED)
            }})
        }

        if (!b.blockless) {
            var a = b.lang.table;
            b.addCommand("table", new CKEDITOR.dialogCommand("table", {context: "table", allowedContent: "table{width,height}[align,border,cellpadding,cellspacing,summary];caption tbody thead tfoot;th td tr[scope];" + (b.plugins.dialogadvtab ? "table" + b.plugins.dialogadvtab.allowedContent() : ""), requiredContent: "table", contentTransformations: [
                ["table{width}: sizeToStyle", "table[width]: sizeToAttribute"]
            ]}));
            b.addCommand("tableProperties", new CKEDITOR.dialogCommand("tableProperties",
                c()));
            b.addCommand("tableDelete", c({exec: function (a) {
                var b = a.elementPath().contains("table", 1);
                if (b) {
                    var c = b.getParent();
                    c.getChildCount() == 1 && !c.is("body", "td", "th") && (b = c);
                    a = a.createRange();
                    a.moveToPosition(b, CKEDITOR.POSITION_BEFORE_START);
                    b.remove();
                    a.select()
                }
            }}));
            b.ui.addButton && b.ui.addButton("Table", {label: a.toolbar, command: "table", toolbar: "insert,30"});
            CKEDITOR.dialog.add("table", this.path + "dialogs/table.js");
            CKEDITOR.dialog.add("tableProperties", this.path + "dialogs/table.js");
            b.addMenuItems &&
            b.addMenuItems({table: {label: a.menu, command: "tableProperties", group: "table", order: 5}, tabledelete: {label: a.deleteTable, command: "tableDelete", group: "table", order: 1}});
            b.on("doubleclick", function (a) {
                if (a.data.element.is("table"))a.data.dialog = "tableProperties"
            });
            b.contextMenu && b.contextMenu.addListener(function () {
                return{tabledelete: CKEDITOR.TRISTATE_OFF, table: CKEDITOR.TRISTATE_OFF}
            })
        }
    }}), function () {
        function b(a) {
            function b(a) {
                if (!(c.length > 0) && a.type == CKEDITOR.NODE_ELEMENT && n.test(a.getName()) && !a.getCustomData("selected_cell")) {
                    CKEDITOR.dom.element.setMarker(d,
                        a, "selected_cell", true);
                    c.push(a)
                }
            }

            for (var a = a.getRanges(), c = [], d = {}, e = 0; e < a.length; e++) {
                var f = a[e];
                if (f.collapsed) {
                    f = f.getCommonAncestor();
                    (f = f.getAscendant("td", true) || f.getAscendant("th", true)) && c.push(f)
                } else {
                    var f = new CKEDITOR.dom.walker(f), g;
                    for (f.guard = b; g = f.next();)if (g.type != CKEDITOR.NODE_ELEMENT || !g.is(CKEDITOR.dtd.table))if ((g = g.getAscendant("td", true) || g.getAscendant("th", true)) && !g.getCustomData("selected_cell")) {
                        CKEDITOR.dom.element.setMarker(d, g, "selected_cell", true);
                        c.push(g)
                    }
                }
            }
            CKEDITOR.dom.element.clearAllMarkers(d);
            return c
        }

        function c(a, c) {
            for (var d = b(a), e = d[0], f = e.getAscendant("table"), e = e.getDocument(), g = d[0].getParent(), h = g.$.rowIndex, d = d[d.length - 1], i = d.getParent().$.rowIndex + d.$.rowSpan - 1, d = new CKEDITOR.dom.element(f.$.rows[i]), h = c ? h : i, g = c ? g : d, d = CKEDITOR.tools.buildTableMap(f), f = d[h], h = c ? d[h - 1] : d[h + 1], d = d[0].length, e = e.createElement("tr"), i = 0; f[i] && i < d; i++) {
                var j;
                if (f[i].rowSpan > 1 && h && f[i] == h[i]) {
                    j = f[i];
                    j.rowSpan = j.rowSpan + 1
                } else {
                    j = (new CKEDITOR.dom.element(f[i])).clone();
                    j.removeAttribute("rowSpan");
                    !CKEDITOR.env.ie &&
                    j.appendBogus();
                    e.append(j);
                    j = j.$
                }
                i = i + (j.colSpan - 1)
            }
            c ? e.insertBefore(g) : e.insertAfter(g)
        }

        function a(c) {
            if (c instanceof CKEDITOR.dom.selection) {
                for (var d = b(c), e = d[0].getAscendant("table"), f = CKEDITOR.tools.buildTableMap(e), c = d[0].getParent().$.rowIndex, d = d[d.length - 1], g = d.getParent().$.rowIndex + d.$.rowSpan - 1, d = [], h = c; h <= g; h++) {
                    for (var i = f[h], j = new CKEDITOR.dom.element(e.$.rows[h]), k = 0; k < i.length; k++) {
                        var m = new CKEDITOR.dom.element(i[k]), n = m.getParent().$.rowIndex;
                        if (m.$.rowSpan == 1)m.remove(); else {
                            m.$.rowSpan =
                                m.$.rowSpan - 1;
                            if (n == h) {
                                n = f[h + 1];
                                n[k - 1] ? m.insertAfter(new CKEDITOR.dom.element(n[k - 1])) : (new CKEDITOR.dom.element(e.$.rows[h + 1])).append(m, 1)
                            }
                        }
                        k = k + (m.$.colSpan - 1)
                    }
                    d.push(j)
                }
                f = e.$.rows;
                e = new CKEDITOR.dom.element(f[g + 1] || (c > 0 ? f[c - 1] : null) || e.$.parentNode);
                for (h = d.length; h >= 0; h--)a(d[h]);
                return e
            }
            if (c instanceof CKEDITOR.dom.element) {
                e = c.getAscendant("table");
                e.$.rows.length == 1 ? e.remove() : c.remove()
            }
            return null
        }

        function f(a, b) {
            for (var c = b ? Infinity : 0, d = 0; d < a.length; d++) {
                var e;
                e = a[d];
                for (var f = b, g = e.getParent().$.cells,
                         h = 0, i = 0; i < g.length; i++) {
                    var j = g[i], h = h + (f ? 1 : j.colSpan);
                    if (j == e.$)break
                }
                e = h - 1;
                if (b ? e < c : e > c)c = e
            }
            return c
        }

        function h(a, c) {
            for (var d = b(a), e = d[0].getAscendant("table"), g = f(d, 1), d = f(d), g = c ? g : d, h = CKEDITOR.tools.buildTableMap(e), e = [], d = [], i = h.length, j = 0; j < i; j++) {
                e.push(h[j][g]);
                d.push(c ? h[j][g - 1] : h[j][g + 1])
            }
            for (j = 0; j < i; j++)if (e[j]) {
                if (e[j].colSpan > 1 && d[j] == e[j]) {
                    g = e[j];
                    g.colSpan = g.colSpan + 1
                } else {
                    g = (new CKEDITOR.dom.element(e[j])).clone();
                    g.removeAttribute("colSpan");
                    !CKEDITOR.env.ie && g.appendBogus();
                    g[c ?
                        "insertBefore" : "insertAfter"].call(g, new CKEDITOR.dom.element(e[j]));
                    g = g.$
                }
                j = j + (g.rowSpan - 1)
            }
        }

        function g(a, b) {
            var c = a.getStartElement();
            if (c = c.getAscendant("td", 1) || c.getAscendant("th", 1)) {
                var d = c.clone();
                CKEDITOR.env.ie || d.appendBogus();
                b ? d.insertBefore(c) : d.insertAfter(c)
            }
        }

        function d(a) {
            if (a instanceof CKEDITOR.dom.selection) {
                var a = b(a), c = a[0] && a[0].getAscendant("table"), e;
                a:{
                    var f = 0;
                    e = a.length - 1;
                    for (var g = {}, h, j; h = a[f++];)CKEDITOR.dom.element.setMarker(g, h, "delete_cell", true);
                    for (f = 0; h = a[f++];)if ((j =
                        h.getPrevious()) && !j.getCustomData("delete_cell") || (j = h.getNext()) && !j.getCustomData("delete_cell")) {
                        CKEDITOR.dom.element.clearAllMarkers(g);
                        e = j;
                        break a
                    }
                    CKEDITOR.dom.element.clearAllMarkers(g);
                    j = a[0].getParent();
                    if (j = j.getPrevious())e = j.getLast(); else {
                        j = a[e].getParent();
                        e = (j = j.getNext()) ? j.getChild(0) : null
                    }
                }
                for (j = a.length - 1; j >= 0; j--)d(a[j]);
                e ? i(e, true) : c && c.remove()
            } else if (a instanceof CKEDITOR.dom.element) {
                c = a.getParent();
                c.getChildCount() == 1 ? c.remove() : a.remove()
            }
        }

        function i(a, b) {
            var c = new CKEDITOR.dom.range(a.getDocument());
            if (!c["moveToElementEdit" + (b ? "End" : "Start")](a)) {
                c.selectNodeContents(a);
                c.collapse(b ? false : true)
            }
            c.select(true)
        }

        function e(a, b, c) {
            a = a[b];
            if (typeof c == "undefined")return a;
            for (b = 0; a && b < a.length; b++) {
                if (c.is && a[b] == c.$)return b;
                if (b == c)return new CKEDITOR.dom.element(a[b])
            }
            return c.is ? -1 : null
        }

        function j(a, c, d) {
            var f = b(a), g;
            if ((c ? f.length != 1 : f.length < 2) || (g = a.getCommonAncestor()) && g.type == CKEDITOR.NODE_ELEMENT && g.is("table"))return false;
            var h, a = f[0];
            g = a.getAscendant("table");
            var i = CKEDITOR.tools.buildTableMap(g),
                j = i.length, k = i[0].length, m = a.getParent().$.rowIndex, n = e(i, m, a);
            if (c) {
                var u;
                try {
                    var A = parseInt(a.getAttribute("rowspan"), 10) || 1;
                    h = parseInt(a.getAttribute("colspan"), 10) || 1;
                    u = i[c == "up" ? m - A : c == "down" ? m + A : m][c == "left" ? n - h : c == "right" ? n + h : n]
                } catch (B) {
                    return false
                }
                if (!u || a.$ == u)return false;
                f[c == "up" || c == "left" ? "unshift" : "push"](new CKEDITOR.dom.element(u))
            }
            for (var c = a.getDocument(), y = m, A = u = 0, C = !d && new CKEDITOR.dom.documentFragment(c), D = 0, c = 0; c < f.length; c++) {
                h = f[c];
                var F = h.getParent(), E = h.getFirst(), K = h.$.colSpan,
                    I = h.$.rowSpan, F = F.$.rowIndex, G = e(i, F, h), D = D + K * I, A = Math.max(A, G - n + K);
                u = Math.max(u, F - m + I);
                if (!d) {
                    K = h;
                    (I = K.getBogus()) && I.remove();
                    K.trim();
                    if (h.getChildren().count()) {
                        if (F != y && E && (!E.isBlockBoundary || !E.isBlockBoundary({br: 1})))(y = C.getLast(CKEDITOR.dom.walker.whitespaces(true))) && (!y.is || !y.is("br")) && C.append("br");
                        h.moveChildren(C)
                    }
                    c ? h.remove() : h.setHtml("")
                }
                y = F
            }
            if (d)return u * A == D;
            C.moveChildren(a);
            CKEDITOR.env.ie || a.appendBogus();
            A >= k ? a.removeAttribute("rowSpan") : a.$.rowSpan = u;
            u >= j ? a.removeAttribute("colSpan") :
                a.$.colSpan = A;
            d = new CKEDITOR.dom.nodeList(g.$.rows);
            f = d.count();
            for (c = f - 1; c >= 0; c--) {
                g = d.getItem(c);
                if (!g.$.cells.length) {
                    g.remove();
                    f++
                }
            }
            return a
        }

        function k(a, c) {
            var d = b(a);
            if (d.length > 1)return false;
            if (c)return true;
            var d = d[0], f = d.getParent(), g = f.getAscendant("table"), h = CKEDITOR.tools.buildTableMap(g), i = f.$.rowIndex, j = e(h, i, d), k = d.$.rowSpan, m;
            if (k > 1) {
                m = Math.ceil(k / 2);
                for (var k = Math.floor(k / 2), f = i + m, g = new CKEDITOR.dom.element(g.$.rows[f]), h = e(h, f), n, f = d.clone(), i = 0; i < h.length; i++) {
                    n = h[i];
                    if (n.parentNode ==
                        g.$ && i > j) {
                        f.insertBefore(new CKEDITOR.dom.element(n));
                        break
                    } else n = null
                }
                n || g.append(f, true)
            } else {
                k = m = 1;
                g = f.clone();
                g.insertAfter(f);
                g.append(f = d.clone());
                n = e(h, i);
                for (j = 0; j < n.length; j++)n[j].rowSpan++
            }
            CKEDITOR.env.ie || f.appendBogus();
            d.$.rowSpan = m;
            f.$.rowSpan = k;
            m == 1 && d.removeAttribute("rowSpan");
            k == 1 && f.removeAttribute("rowSpan");
            return f
        }

        function m(a, c) {
            var d = b(a);
            if (d.length > 1)return false;
            if (c)return true;
            var d = d[0], f = d.getParent(), g = f.getAscendant("table"), g = CKEDITOR.tools.buildTableMap(g), h =
                e(g, f.$.rowIndex, d), i = d.$.colSpan;
            if (i > 1) {
                f = Math.ceil(i / 2);
                i = Math.floor(i / 2)
            } else {
                for (var i = f = 1, j = [], k = 0; k < g.length; k++) {
                    var m = g[k];
                    j.push(m[h]);
                    m[h].rowSpan > 1 && (k = k + (m[h].rowSpan - 1))
                }
                for (g = 0; g < j.length; g++)j[g].colSpan++
            }
            g = d.clone();
            g.insertAfter(d);
            CKEDITOR.env.ie || g.appendBogus();
            d.$.colSpan = f;
            g.$.colSpan = i;
            f == 1 && d.removeAttribute("colSpan");
            i == 1 && g.removeAttribute("colSpan");
            return g
        }

        var n = /^(?:td|th)$/;
        CKEDITOR.plugins.tabletools = {requires: "table,dialog,contextmenu", init: function (e) {
            function f(a) {
                return CKEDITOR.tools.extend(a ||
                {}, {contextSensitive: 1, refresh: function (a, b) {
                    this.setState(b.contains({td: 1, th: 1}, 1) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED)
                }})
            }

            function n(a, b) {
                var c = e.addCommand(a, b);
                e.addFeature(c)
            }

            var s = e.lang.table;
            n("cellProperties", new CKEDITOR.dialogCommand("cellProperties", f({allowedContent: "td th{width,height,border-color,background-color,white-space,vertical-align,text-align}[colspan,rowspan]", requiredContent: "table"})));
            CKEDITOR.dialog.add("cellProperties", this.path + "dialogs/tableCell.js");
            n("rowDelete",
                f({requiredContent: "table", exec: function (b) {
                    b = b.getSelection();
                    i(a(b))
                }}));
            n("rowInsertBefore", f({requiredContent: "table", exec: function (a) {
                a = a.getSelection();
                c(a, true)
            }}));
            n("rowInsertAfter", f({requiredContent: "table", exec: function (a) {
                a = a.getSelection();
                c(a)
            }}));
            n("columnDelete", f({requiredContent: "table", exec: function (a) {
                for (var a = a.getSelection(), a = b(a), c = a[0], d = a[a.length - 1], a = c.getAscendant("table"), e = CKEDITOR.tools.buildTableMap(a), f, g, h = [], j = 0, l = e.length; j < l; j++)for (var k = 0, m = e[j].length; k <
                    m; k++) {
                    e[j][k] == c.$ && (f = k);
                    e[j][k] == d.$ && (g = k)
                }
                for (j = f; j <= g; j++)for (k = 0; k < e.length; k++) {
                    d = e[k];
                    c = new CKEDITOR.dom.element(a.$.rows[k]);
                    d = new CKEDITOR.dom.element(d[j]);
                    if (d.$) {
                        d.$.colSpan == 1 ? d.remove() : d.$.colSpan = d.$.colSpan - 1;
                        k = k + (d.$.rowSpan - 1);
                        c.$.cells.length || h.push(c)
                    }
                }
                g = a.$.rows[0] && a.$.rows[0].cells;
                f = new CKEDITOR.dom.element(g[f] || (f ? g[f - 1] : a.$.parentNode));
                h.length == l && a.remove();
                f && i(f, true)
            }}));
            n("columnInsertBefore", f({requiredContent: "table", exec: function (a) {
                a = a.getSelection();
                h(a,
                    true)
            }}));
            n("columnInsertAfter", f({requiredContent: "table", exec: function (a) {
                a = a.getSelection();
                h(a)
            }}));
            n("cellDelete", f({requiredContent: "table", exec: function (a) {
                a = a.getSelection();
                d(a)
            }}));
            n("cellMerge", f({allowedContent: "td[colspan,rowspan]", requiredContent: "td[colspan,rowspan]", exec: function (a) {
                i(j(a.getSelection()), true)
            }}));
            n("cellMergeRight", f({allowedContent: "td[colspan]", requiredContent: "td[colspan]", exec: function (a) {
                i(j(a.getSelection(), "right"), true)
            }}));
            n("cellMergeDown", f({allowedContent: "td[rowspan]",
                requiredContent: "td[rowspan]", exec: function (a) {
                    i(j(a.getSelection(), "down"), true)
                }}));
            n("cellVerticalSplit", f({allowedContent: "td[rowspan]", requiredContent: "td[rowspan]", exec: function (a) {
                i(k(a.getSelection()))
            }}));
            n("cellHorizontalSplit", f({allowedContent: "td[colspan]", requiredContent: "td[colspan]", exec: function (a) {
                i(m(a.getSelection()))
            }}));
            n("cellInsertBefore", f({requiredContent: "table", exec: function (a) {
                a = a.getSelection();
                g(a, true)
            }}));
            n("cellInsertAfter", f({requiredContent: "table", exec: function (a) {
                a =
                    a.getSelection();
                g(a)
            }}));
            e.addMenuItems && e.addMenuItems({tablecell: {label: s.cell.menu, group: "tablecell", order: 1, getItems: function () {
                var a = e.getSelection(), c = b(a);
                return{tablecell_insertBefore: CKEDITOR.TRISTATE_OFF, tablecell_insertAfter: CKEDITOR.TRISTATE_OFF, tablecell_delete: CKEDITOR.TRISTATE_OFF, tablecell_merge: j(a, null, true) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, tablecell_merge_right: j(a, "right", true) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, tablecell_merge_down: j(a, "down", true) ?
                    CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, tablecell_split_vertical: k(a, true) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, tablecell_split_horizontal: m(a, true) ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED, tablecell_properties: c.length > 0 ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED}
            }}, tablecell_insertBefore: {label: s.cell.insertBefore, group: "tablecell", command: "cellInsertBefore", order: 5}, tablecell_insertAfter: {label: s.cell.insertAfter, group: "tablecell", command: "cellInsertAfter", order: 10},
                tablecell_delete: {label: s.cell.deleteCell, group: "tablecell", command: "cellDelete", order: 15}, tablecell_merge: {label: s.cell.merge, group: "tablecell", command: "cellMerge", order: 16}, tablecell_merge_right: {label: s.cell.mergeRight, group: "tablecell", command: "cellMergeRight", order: 17}, tablecell_merge_down: {label: s.cell.mergeDown, group: "tablecell", command: "cellMergeDown", order: 18}, tablecell_split_horizontal: {label: s.cell.splitHorizontal, group: "tablecell", command: "cellHorizontalSplit", order: 19}, tablecell_split_vertical: {label: s.cell.splitVertical,
                    group: "tablecell", command: "cellVerticalSplit", order: 20}, tablecell_properties: {label: s.cell.title, group: "tablecellproperties", command: "cellProperties", order: 21}, tablerow: {label: s.row.menu, group: "tablerow", order: 1, getItems: function () {
                    return{tablerow_insertBefore: CKEDITOR.TRISTATE_OFF, tablerow_insertAfter: CKEDITOR.TRISTATE_OFF, tablerow_delete: CKEDITOR.TRISTATE_OFF}
                }}, tablerow_insertBefore: {label: s.row.insertBefore, group: "tablerow", command: "rowInsertBefore", order: 5}, tablerow_insertAfter: {label: s.row.insertAfter,
                    group: "tablerow", command: "rowInsertAfter", order: 10}, tablerow_delete: {label: s.row.deleteRow, group: "tablerow", command: "rowDelete", order: 15}, tablecolumn: {label: s.column.menu, group: "tablecolumn", order: 1, getItems: function () {
                    return{tablecolumn_insertBefore: CKEDITOR.TRISTATE_OFF, tablecolumn_insertAfter: CKEDITOR.TRISTATE_OFF, tablecolumn_delete: CKEDITOR.TRISTATE_OFF}
                }}, tablecolumn_insertBefore: {label: s.column.insertBefore, group: "tablecolumn", command: "columnInsertBefore", order: 5}, tablecolumn_insertAfter: {label: s.column.insertAfter,
                    group: "tablecolumn", command: "columnInsertAfter", order: 10}, tablecolumn_delete: {label: s.column.deleteColumn, group: "tablecolumn", command: "columnDelete", order: 15}});
            e.contextMenu && e.contextMenu.addListener(function (a, b, c) {
                return(a = c.contains({td: 1, th: 1}, 1)) && !a.isReadOnly() ? {tablecell: CKEDITOR.TRISTATE_OFF, tablerow: CKEDITOR.TRISTATE_OFF, tablecolumn: CKEDITOR.TRISTATE_OFF} : null
            })
        }, getSelectedCells: b};
        CKEDITOR.plugins.add("tabletools", CKEDITOR.plugins.tabletools)
    }(), CKEDITOR.tools.buildTableMap = function (b) {
        for (var b =
            b.$.rows, c = -1, a = [], f = 0; f < b.length; f++) {
            c++;
            !a[c] && (a[c] = []);
            for (var h = -1, g = 0; g < b[f].cells.length; g++) {
                var d = b[f].cells[g];
                for (h++; a[c][h];)h++;
                for (var i = isNaN(d.colSpan) ? 1 : d.colSpan, d = isNaN(d.rowSpan) ? 1 : d.rowSpan, e = 0; e < d; e++) {
                    a[c + e] || (a[c + e] = []);
                    for (var j = 0; j < i; j++)a[c + e][h + j] = b[f].cells[g]
                }
                h = h + (i - 1)
            }
        }
        return a
    }, function () {
        CKEDITOR.plugins.add("templates", {requires: "dialog", init: function (a) {
            CKEDITOR.dialog.add("templates", CKEDITOR.getUrl(this.path + "dialogs/templates.js"));
            a.addCommand("templates",
                new CKEDITOR.dialogCommand("templates"));
            a.ui.addButton && a.ui.addButton("Templates", {label: a.lang.templates.button, command: "templates", toolbar: "doctools,10"})
        }});
        var b = {}, c = {};
        CKEDITOR.addTemplates = function (a, c) {
            b[a] = c
        };
        CKEDITOR.getTemplates = function (a) {
            return b[a]
        };
        CKEDITOR.loadTemplates = function (a, b) {
            for (var h = [], g = 0, d = a.length; g < d; g++)if (!c[a[g]]) {
                h.push(a[g]);
                c[a[g]] = 1
            }
            h.length ? CKEDITOR.scriptLoader.load(h, b) : setTimeout(b, 0)
        }
    }(), CKEDITOR.config.templates_files = [CKEDITOR.getUrl("plugins/templates/templates/default.js")],
        CKEDITOR.config.templates_replaceContent = !0, function () {
        function b(a) {
            function b() {
                for (var e = d(), g = CKEDITOR.tools.clone(a.config.toolbarGroups) || c(a), j = 0; j < g.length; j++) {
                    var k = g[j];
                    if (k != "/") {
                        typeof k == "string" && (k = g[j] = {name: k});
                        var q, s = k.groups;
                        if (s)for (var p = 0; p < s.length; p++) {
                            q = s[p];
                            (q = e[q]) && f(k, q)
                        }
                        (q = e[k.name]) && f(k, q)
                    }
                }
                return g
            }

            function d() {
                var b = {}, c, d, e;
                for (c in a.ui.items) {
                    d = a.ui.items[c];
                    e = d.toolbar || "others";
                    e = e.split(",");
                    d = e[0];
                    e = parseInt(e[1] || -1, 10);
                    b[d] || (b[d] = []);
                    b[d].push({name: c,
                        order: e})
                }
                for (d in b)b[d] = b[d].sort(function (a, b) {
                    return a.order == b.order ? 0 : b.order < 0 ? -1 : a.order < 0 ? 1 : a.order < b.order ? -1 : 1
                });
                return b
            }

            function f(b, c) {
                if (c.length) {
                    b.items ? b.items.push(a.ui.create("-")) : b.items = [];
                    for (var d; d = c.shift();) {
                        d = typeof d == "string" ? d : d.name;
                        if (!j || CKEDITOR.tools.indexOf(j, d) == -1)(d = a.ui.create(d)) && a.addFeature(d) && b.items.push(d)
                    }
                }
            }

            function e(a) {
                var b = [], c, d, e;
                for (c = 0; c < a.length; ++c) {
                    d = a[c];
                    e = {};
                    if (d == "/")b.push(d); else if (CKEDITOR.tools.isArray(d)) {
                        f(e, CKEDITOR.tools.clone(d));
                        b.push(e)
                    } else if (d.items) {
                        f(e, CKEDITOR.tools.clone(d.items));
                        e.name = d.name;
                        b.push(e)
                    }
                }
                return b
            }

            var j = a.config.removeButtons, j = j && j.split(","), k = a.config.toolbar;
            typeof k == "string" && (k = a.config["toolbar_" + k]);
            return a.toolbar = k ? e(k) : b()
        }

        function c(a) {
            return a._.toolbarGroups || (a._.toolbarGroups = [
                {name: "document", groups: ["mode", "document", "doctools"]},
                {name: "clipboard", groups: ["clipboard", "undo"]},
                {name: "editing", groups: ["find", "selection", "spellchecker"]},
                {name: "forms"},
                "/",
                {name: "basicstyles", groups: ["basicstyles",
                    "cleanup"]},
                {name: "paragraph", groups: ["list", "indent", "blocks", "align"]},
                {name: "links"},
                {name: "insert"},
                "/",
                {name: "styles"},
                {name: "colors"},
                {name: "tools"},
                {name: "others"},
                {name: "about"}
            ])
        }

        var a = function () {
            this.toolbars = [];
            this.focusCommandExecuted = false
        };
        a.prototype.focus = function () {
            for (var a = 0, b; b = this.toolbars[a++];)for (var c = 0, f; f = b.items[c++];)if (f.focus) {
                f.focus();
                return
            }
        };
        var f = {modes: {wysiwyg: 1, source: 1}, readOnly: 1, exec: function (a) {
            if (a.toolbox) {
                a.toolbox.focusCommandExecuted = true;
                CKEDITOR.env.ie ||
                    CKEDITOR.env.air ? setTimeout(function () {
                    a.toolbox.focus()
                }, 100) : a.toolbox.focus()
            }
        }};
        CKEDITOR.plugins.add("toolbar", {requires: "button", init: function (c) {
            var g, d = function (a, b) {
                var f, k = c.lang.dir == "rtl", m = c.config.toolbarGroupCycling, m = m === void 0 || m;
                switch (b) {
                    case 9:
                    case CKEDITOR.SHIFT + 9:
                        for (; !f || !f.items.length;) {
                            f = b == 9 ? (f ? f.next : a.toolbar.next) || c.toolbox.toolbars[0] : (f ? f.previous : a.toolbar.previous) || c.toolbox.toolbars[c.toolbox.toolbars.length - 1];
                            if (f.items.length)for (a = f.items[g ? f.items.length - 1 :
                                0]; a && !a.focus;)(a = g ? a.previous : a.next) || (f = 0)
                        }
                        a && a.focus();
                        return false;
                    case k ? 37 : 39:
                    case 40:
                        f = a;
                        do {
                            f = f.next;
                            !f && m && (f = a.toolbar.items[0])
                        } while (f && !f.focus);
                        f ? f.focus() : d(a, 9);
                        return false;
                    case k ? 39 : 37:
                    case 38:
                        f = a;
                        do {
                            f = f.previous;
                            !f && m && (f = a.toolbar.items[a.toolbar.items.length - 1])
                        } while (f && !f.focus);
                        if (f)f.focus(); else {
                            g = 1;
                            d(a, CKEDITOR.SHIFT + 9);
                            g = 0
                        }
                        return false;
                    case 27:
                        c.focus();
                        return false;
                    case 13:
                    case 32:
                        a.execute();
                        return false
                }
                return true
            };
            c.on("uiSpace", function (f) {
                if (f.data.space == c.config.toolbarLocation) {
                    f.removeListener();
                    c.toolbox = new a;
                    var e = CKEDITOR.tools.getNextId(), g = ['<span id="', e, '" class="cke_voice_label">', c.lang.toolbar.toolbars, "</span>", '<span id="' + c.ui.spaceId("toolbox") + '" class="cke_toolbox" role="group" aria-labelledby="', e, '" onmousedown="return false;">'], e = c.config.toolbarStartupExpanded !== false, k, m;
                    c.config.toolbarCanCollapse && c.elementMode != CKEDITOR.ELEMENT_MODE_INLINE && g.push('<span class="cke_toolbox_main"' + (e ? ">" : ' style="display:none">'));
                    for (var n = c.toolbox.toolbars, l = b(c), o = 0; o < l.length; o++) {
                        var q,
                            s = 0, p, t = l[o], z;
                        if (t) {
                            if (k) {
                                g.push("</span>");
                                m = k = 0
                            }
                            if (t === "/")g.push('<span class="cke_toolbar_break"></span>'); else {
                                z = t.items || t;
                                for (var x = 0; x < z.length; x++) {
                                    var w = z[x], v;
                                    if (w)if (w.type == CKEDITOR.UI_SEPARATOR)m = k && w; else {
                                        v = w.canGroup !== false;
                                        if (!s) {
                                            q = CKEDITOR.tools.getNextId();
                                            s = {id: q, items: []};
                                            p = t.name && (c.lang.toolbar.toolbarGroups[t.name] || t.name);
                                            g.push('<span id="', q, '" class="cke_toolbar"', p ? ' aria-labelledby="' + q + '_label"' : "", ' role="toolbar">');
                                            p && g.push('<span id="', q, '_label" class="cke_voice_label">',
                                                p, "</span>");
                                            g.push('<span class="cke_toolbar_start"></span>');
                                            var r = n.push(s) - 1;
                                            if (r > 0) {
                                                s.previous = n[r - 1];
                                                s.previous.next = s
                                            }
                                        }
                                        if (v) {
                                            if (!k) {
                                                g.push('<span class="cke_toolgroup" role="presentation">');
                                                k = 1
                                            }
                                        } else if (k) {
                                            g.push("</span>");
                                            k = 0
                                        }
                                        q = function (a) {
                                            a = a.render(c, g);
                                            r = s.items.push(a) - 1;
                                            if (r > 0) {
                                                a.previous = s.items[r - 1];
                                                a.previous.next = a
                                            }
                                            a.toolbar = s;
                                            a.onkey = d;
                                            a.onfocus = function () {
                                                c.toolbox.focusCommandExecuted || c.focus()
                                            }
                                        };
                                        if (m) {
                                            q(m);
                                            m = 0
                                        }
                                        q(w)
                                    }
                                }
                                if (k) {
                                    g.push("</span>");
                                    m = k = 0
                                }
                                s && g.push('<span class="cke_toolbar_end"></span></span>')
                            }
                        }
                    }
                    c.config.toolbarCanCollapse &&
                    g.push("</span>");
                    if (c.config.toolbarCanCollapse && c.elementMode != CKEDITOR.ELEMENT_MODE_INLINE) {
                        var u = CKEDITOR.tools.addFunction(function () {
                            c.execCommand("toolbarCollapse")
                        });
                        c.on("destroy", function () {
                            CKEDITOR.tools.removeFunction(u)
                        });
                        c.addCommand("toolbarCollapse", {readOnly: 1, exec: function (a) {
                            var b = a.ui.space("toolbar_collapser"), c = b.getPrevious(), d = a.ui.space("contents"), e = c.getParent(), f = parseInt(d.$.style.height, 10), g = e.$.offsetHeight, h = b.hasClass("cke_toolbox_collapser_min");
                            if (h) {
                                c.show();
                                b.removeClass("cke_toolbox_collapser_min");
                                b.setAttribute("title", a.lang.toolbar.toolbarCollapse)
                            } else {
                                c.hide();
                                b.addClass("cke_toolbox_collapser_min");
                                b.setAttribute("title", a.lang.toolbar.toolbarExpand)
                            }
                            b.getFirst().setText(h ? "▲" : "◀");
                            d.setStyle("height", f - (e.$.offsetHeight - g) + "px");
                            a.fire("resize")
                        }, modes: {wysiwyg: 1, source: 1}});
                        c.setKeystroke(CKEDITOR.ALT + (CKEDITOR.env.ie || CKEDITOR.env.webkit ? 189 : 109), "toolbarCollapse");
                        g.push('<a title="' + (e ? c.lang.toolbar.toolbarCollapse : c.lang.toolbar.toolbarExpand) + '" id="' + c.ui.spaceId("toolbar_collapser") +
                            '" tabIndex="-1" class="cke_toolbox_collapser');
                        e || g.push(" cke_toolbox_collapser_min");
                        g.push('" onclick="CKEDITOR.tools.callFunction(' + u + ')">', '<span class="cke_arrow">&#9650;</span>', "</a>")
                    }
                    g.push("</span>");
                    f.data.html = f.data.html + g.join("")
                }
            });
            c.on("destroy", function () {
                if (this.toolbox) {
                    var a, b = 0, c, d, f;
                    for (a = this.toolbox.toolbars; b < a.length; b++) {
                        d = a[b].items;
                        for (c = 0; c < d.length; c++) {
                            f = d[c];
                            f.clickFn && CKEDITOR.tools.removeFunction(f.clickFn);
                            f.keyDownFn && CKEDITOR.tools.removeFunction(f.keyDownFn)
                        }
                    }
                }
            });
            c.on("uiReady", function () {
                var a = c.ui.space("toolbox");
                a && c.focusManager.add(a, 1)
            });
            c.addCommand("toolbarFocus", f);
            c.setKeystroke(CKEDITOR.ALT + 121, "toolbarFocus");
            c.ui.add("-", CKEDITOR.UI_SEPARATOR, {});
            c.ui.addHandler(CKEDITOR.UI_SEPARATOR, {create: function () {
                return{render: function (a, b) {
                    b.push('<span class="cke_toolbar_separator" role="separator"></span>');
                    return{}
                }}
            }})
        }});
        CKEDITOR.ui.prototype.addToolbarGroup = function (a, b, d) {
            var f = c(this.editor), e = b === 0, j = {name: a};
            if (d) {
                if (d = CKEDITOR.tools.search(f,
                    function (a) {
                        return a.name == d
                    })) {
                    !d.groups && (d.groups = []);
                    if (b) {
                        b = CKEDITOR.tools.indexOf(d.groups, b);
                        if (b >= 0) {
                            d.groups.splice(b + 1, 0, a);
                            return
                        }
                    }
                    e ? d.groups.splice(0, 0, a) : d.groups.push(a);
                    return
                }
                b = null
            }
            b && (b = CKEDITOR.tools.indexOf(f, function (a) {
                return a.name == b
            }));
            e ? f.splice(0, 0, a) : typeof b == "number" ? f.splice(b + 1, 0, j) : f.push(a)
        }
    }(), CKEDITOR.UI_SEPARATOR = "separator", CKEDITOR.config.toolbarLocation = "top", function () {
        function b(a) {
            this.editor = a;
            this.reset()
        }

        CKEDITOR.plugins.add("undo", {init: function (a) {
            function c(a) {
                f.enabled &&
                    a.data.command.canUndo !== false && f.save()
            }

            function e() {
                f.enabled = a.readOnly ? false : a.mode == "wysiwyg";
                f.onChange()
            }

            var f = new b(a), g = a.addCommand("undo", {exec: function () {
                if (f.undo()) {
                    a.selectionChange();
                    this.fire("afterUndo")
                }
            }, state: CKEDITOR.TRISTATE_DISABLED, canUndo: false}), h = a.addCommand("redo", {exec: function () {
                if (f.redo()) {
                    a.selectionChange();
                    this.fire("afterRedo")
                }
            }, state: CKEDITOR.TRISTATE_DISABLED, canUndo: false});
            a.setKeystroke([
                [CKEDITOR.CTRL + 90, "undo"],
                [CKEDITOR.CTRL + 89, "redo"],
                [CKEDITOR.CTRL +
                    CKEDITOR.SHIFT + 90, "redo"]
            ]);
            f.onChange = function () {
                g.setState(f.undoable() ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED);
                h.setState(f.redoable() ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED)
            };
            a.on("beforeCommandExec", c);
            a.on("afterCommandExec", c);
            a.on("saveSnapshot", function (a) {
                f.save(a.data && a.data.contentOnly)
            });
            a.on("contentDom", function () {
                a.editable().on("keydown", function (a) {
                    !a.data.$.ctrlKey && !a.data.$.metaKey && f.type(a)
                })
            });
            a.on("beforeModeUnload", function () {
                a.mode == "wysiwyg" && f.save(true)
            });
            a.on("mode", e);
            a.on("readOnly", e);
            if (a.ui.addButton) {
                a.ui.addButton("Undo", {label: a.lang.undo.undo, command: "undo", toolbar: "undo,10"});
                a.ui.addButton("Redo", {label: a.lang.undo.redo, command: "redo", toolbar: "undo,20"})
            }
            a.resetUndo = function () {
                f.reset();
                a.fire("saveSnapshot")
            };
            a.on("updateSnapshot", function () {
                f.currentImage && f.update()
            });
            a.on("lockSnapshot", f.lock, f);
            a.on("unlockSnapshot", f.unlock, f)
        }});
        CKEDITOR.plugins.undo = {};
        var c = CKEDITOR.plugins.undo.Image = function (a) {
            this.editor = a;
            a.fire("beforeUndoImage");
            var b = a.getSnapshot(), c = b && a.getSelection();
            CKEDITOR.env.ie && b && (b = b.replace(/\s+data-cke-expando=".*?"/g, ""));
            this.contents = b;
            this.bookmarks = c && c.createBookmarks2(true);
            a.fire("afterUndoImage")
        }, a = /\b(?:href|src|name)="[^"]*?"/gi;
        c.prototype = {equals: function (b, c) {
            var e = this.contents, f = b.contents;
            if (CKEDITOR.env.ie && (CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat)) {
                e = e.replace(a, "");
                f = f.replace(a, "")
            }
            if (e != f)return false;
            if (c)return true;
            e = this.bookmarks;
            f = b.bookmarks;
            if (e || f) {
                if (!e || !f || e.length !=
                    f.length)return false;
                for (var g = 0; g < e.length; g++) {
                    var h = e[g], n = f[g];
                    if (h.startOffset != n.startOffset || h.endOffset != n.endOffset || !CKEDITOR.tools.arrayCompare(h.start, n.start) || !CKEDITOR.tools.arrayCompare(h.end, n.end))return false
                }
            }
            return true
        }};
        var f = {8: 1, 46: 1}, h = {16: 1, 17: 1, 18: 1}, g = {37: 1, 38: 1, 39: 1, 40: 1};
        b.prototype = {type: function (a) {
            var a = a && a.data.getKey(), b = a in f, e = this.lastKeystroke in f, j = b && a == this.lastKeystroke, k = a in g, m = this.lastKeystroke in g;
            if (!(a in h || this.typing) || !b && !k && (e || m) || b && !j) {
                var n = new c(this.editor), l = this.snapshots.length;
                CKEDITOR.tools.setTimeout(function () {
                    var a = this.editor.getSnapshot();
                    CKEDITOR.env.ie && (a = a.replace(/\s+data-cke-expando=".*?"/g, ""));
                    if (n.contents != a && l == this.snapshots.length) {
                        this.typing = true;
                        this.save(false, n, false) || this.snapshots.splice(this.index + 1, this.snapshots.length - this.index - 1);
                        this.hasUndo = true;
                        this.hasRedo = false;
                        this.modifiersCount = this.typesCount = 1;
                        this.onChange()
                    }
                }, 0, this)
            }
            this.lastKeystroke = a;
            if (b) {
                this.typesCount = 0;
                this.modifiersCount++;
                if (this.modifiersCount > 25) {
                    this.save(false, null, false);
                    this.modifiersCount = 1
                }
            } else if (!k) {
                this.modifiersCount = 0;
                this.typesCount++;
                if (this.typesCount > 25) {
                    this.save(false, null, false);
                    this.typesCount = 1
                }
            }
        }, reset: function () {
            this.lastKeystroke = 0;
            this.snapshots = [];
            this.index = -1;
            this.limit = this.editor.config.undoStackSize || 20;
            this.currentImage = null;
            this.hasRedo = this.hasUndo = false;
            this.locked = null;
            this.resetType()
        }, resetType: function () {
            this.typing = false;
            delete this.lastKeystroke;
            this.modifiersCount = this.typesCount =
                0
        }, fireChange: function () {
            this.hasUndo = !!this.getNextImage(true);
            this.hasRedo = !!this.getNextImage(false);
            this.resetType();
            this.onChange()
        }, save: function (a, b, e) {
            if (this.locked)return false;
            var f = this.snapshots;
            b || (b = new c(this.editor));
            if (b.contents === false || this.currentImage && b.equals(this.currentImage, a))return false;
            f.splice(this.index + 1, f.length - this.index - 1);
            f.length == this.limit && f.shift();
            this.index = f.push(b) - 1;
            this.currentImage = b;
            e !== false && this.fireChange();
            return true
        }, restoreImage: function (a) {
            var b =
                this.editor, c;
            if (a.bookmarks) {
                b.focus();
                c = b.getSelection()
            }
            this.locked = 1;
            this.editor.loadSnapshot(a.contents);
            if (a.bookmarks)c.selectBookmarks(a.bookmarks); else if (CKEDITOR.env.ie) {
                b = this.editor.document.getBody().$.createTextRange();
                b.collapse(true);
                b.select()
            }
            this.locked = 0;
            this.index = a.index;
            this.update();
            this.fireChange()
        }, getNextImage: function (a) {
            var b = this.snapshots, c = this.currentImage, f;
            if (c)if (a)for (f = this.index - 1; f >= 0; f--) {
                a = b[f];
                if (!c.equals(a, true)) {
                    a.index = f;
                    return a
                }
            } else for (f = this.index +
                1; f < b.length; f++) {
                a = b[f];
                if (!c.equals(a, true)) {
                    a.index = f;
                    return a
                }
            }
            return null
        }, redoable: function () {
            return this.enabled && this.hasRedo
        }, undoable: function () {
            return this.enabled && this.hasUndo
        }, undo: function () {
            if (this.undoable()) {
                this.save(true);
                var a = this.getNextImage(true);
                if (a)return this.restoreImage(a), true
            }
            return false
        }, redo: function () {
            if (this.redoable()) {
                this.save(true);
                if (this.redoable()) {
                    var a = this.getNextImage(false);
                    if (a)return this.restoreImage(a), true
                }
            }
            return false
        }, update: function () {
            if (!this.locked)this.snapshots.splice(this.index,
                1, this.currentImage = new c(this.editor))
        }, lock: function () {
            if (this.locked)this.locked.level++; else {
                var a = new c(this.editor);
                this.locked = {update: this.currentImage && this.currentImage.equals(a, true) ? a : null, level: 1}
            }
        }, unlock: function () {
            if (this.locked && !--this.locked.level) {
                var a = this.locked.update;
                this.locked = null;
                a && !a.equals(new c(this.editor), true) && this.update()
            }
        }}
    }(), CKEDITOR.plugins.add("wsc", {requires: "dialog", init: function (b) {
        b.addCommand("checkspell", new CKEDITOR.dialogCommand("checkspell")).modes =
        {wysiwyg: !CKEDITOR.env.opera && !CKEDITOR.env.air && document.domain == window.location.hostname};
        typeof b.plugins.scayt == "undefined" && b.ui.addButton && b.ui.addButton("SpellChecker", {label: b.lang.wsc.toolbar, command: "checkspell", toolbar: "spellchecker,10"});
        CKEDITOR.dialog.add("checkspell", this.path + "dialogs/wsc.js")
    }}), CKEDITOR.config.wsc_customerId = CKEDITOR.config.wsc_customerId || "1:ua3xw1-2XyGJ3-GWruD3-6OFNT1-oXcuB1-nR6Bp4-hgQHc-EcYng3-sdRXG3-NOfFk", CKEDITOR.config.wsc_customLoaderScript = CKEDITOR.config.wsc_customLoaderScript ||
        null, function () {
        function b(b) {
            var c = this.editor, e = b.document, f = e.body;
            (b = e.getElementById("cke_actscrpt")) && b.parentNode.removeChild(b);
            (b = e.getElementById("cke_shimscrpt")) && b.parentNode.removeChild(b);
            if (CKEDITOR.env.gecko) {
                f.contentEditable = false;
                if (CKEDITOR.env.version < 2E4) {
                    f.innerHTML = f.innerHTML.replace(/^.*<\!-- cke-content-start --\>/, "");
                    setTimeout(function () {
                            var a = new CKEDITOR.dom.range(new CKEDITOR.dom.document(e));
                            a.setStart(new CKEDITOR.dom.node(f), 0);
                            c.getSelection().selectRanges([a])
                        },
                        0)
                }
            }
            f.contentEditable = true;
            if (CKEDITOR.env.ie) {
                f.hideFocus = true;
                f.disabled = true;
                f.removeAttribute("disabled")
            }
            delete this._.isLoadingData;
            this.$ = f;
            e = new CKEDITOR.dom.document(e);
            this.setup();
            if (CKEDITOR.env.ie) {
                e.getDocumentElement().addClass(e.$.compatMode);
                c.config.enterMode != CKEDITOR.ENTER_P && e.on("selectionchange", function () {
                    var a = e.getBody(), b = c.getSelection(), d = b && b.getRanges()[0];
                    d && (a.getHtml().match(/^<p>&nbsp;<\/p>$/i) && d.startContainer.equals(a)) && setTimeout(function () {
                        d = c.getSelection().getRanges()[0];
                        if (!d.startContainer.equals("body")) {
                            a.getFirst().remove(1);
                            d.moveToElementEditEnd(a);
                            d.select()
                        }
                    }, 0)
                })
            }
            CKEDITOR.env.gecko && CKEDITOR.tools.setTimeout(a, 0, this, c);
            try {
                c.document.$.execCommand("2D-position", false, true)
            } catch (g) {
            }
            try {
                c.document.$.execCommand("enableInlineTableEditing", false, !c.config.disableNativeTableHandles)
            } catch (h) {
            }
            if (c.config.disableObjectResizing)try {
                this.getDocument().$.execCommand("enableObjectResizing", false, false)
            } catch (n) {
                this.attachListener(this, CKEDITOR.env.ie ? "resizestart" :
                    "resize", function (a) {
                    a.data.preventDefault()
                })
            }
            (CKEDITOR.env.gecko || CKEDITOR.env.ie && c.document.$.compatMode == "CSS1Compat") && this.attachListener(this, "keydown", function (a) {
                var b = a.data.getKeystroke();
                if (b == 33 || b == 34)if (CKEDITOR.env.ie)setTimeout(function () {
                    c.getSelection().scrollIntoView()
                }, 0); else if (c.window.$.innerHeight > this.$.offsetHeight) {
                    var d = c.createRange();
                    d[b == 33 ? "moveToElementEditStart" : "moveToElementEditEnd"](this);
                    d.select();
                    a.data.preventDefault()
                }
            });
            CKEDITOR.env.ie && this.attachListener(e,
                "blur", function () {
                    try {
                        e.$.selection.empty()
                    } catch (a) {
                    }
                });
            c.document.getElementsByTag("title").getItem(0).data("cke-title", c.document.$.title);
            if (CKEDITOR.env.ie)c.document.$.title = this._.docTitle;
            CKEDITOR.tools.setTimeout(function () {
                c.fire("contentDom");
                if (this._.isPendingFocus) {
                    c.focus();
                    this._.isPendingFocus = false
                }
                setTimeout(function () {
                    c.fire("dataReady")
                }, 0);
                CKEDITOR.env.ie && setTimeout(function () {
                    if (c.document) {
                        var a = c.document.$.body;
                        a.runtimeStyle.marginBottom = "0px";
                        a.runtimeStyle.marginBottom =
                            ""
                    }
                }, 1E3)
            }, 0, this)
        }

        function c(a) {
            a.checkDirty() || setTimeout(function () {
                a.resetDirty()
            }, 0)
        }

        function a(a) {
            if (!a.readOnly) {
                var b = a.window, e = a.document, f = e.getBody(), g = f.getFirst(), h = f.getChildren().count();
                if (!h || h == 1 && g.type == CKEDITOR.NODE_ELEMENT && g.hasAttribute("_moz_editor_bogus_node")) {
                    c(a);
                    var g = CKEDITOR.document, n = g.getDocumentElement(), l = n.$.scrollTop, o = n.$.scrollLeft, q = e.$.createEvent("KeyEvents");
                    q.initKeyEvent("keypress", true, true, b.$, false, false, false, false, 0, 32);
                    e.$.dispatchEvent(q);
                    (l !=
                        n.$.scrollTop || o != n.$.scrollLeft) && g.getWindow().$.scrollTo(o, l);
                    h && f.getFirst().remove();
                    e.getBody().appendBogus();
                    a = a.createRange();
                    a.setStartAt(f, CKEDITOR.POSITION_AFTER_START);
                    a.select()
                }
            }
        }

        function f() {
            var a = [];
            if (CKEDITOR.document.$.documentMode >= 8) {
                a.push("html.CSS1Compat [contenteditable=false]{min-height:0 !important}");
                var b = [], c;
                for (c in CKEDITOR.dtd.$removeEmpty)b.push("html.CSS1Compat " + c + "[contenteditable=false]");
                a.push(b.join(",") + "{display:inline-block}")
            } else if (CKEDITOR.env.gecko) {
                a.push("html{height:100% !important}");
                a.push("img:-moz-broken{-moz-force-broken-image-icon:1;min-width:24px;min-height:24px}")
            }
            a.push("html{cursor:text;*cursor:auto}");
            a.push("img,input,textarea{cursor:default}");
            return a.join("\n")
        }

        CKEDITOR.plugins.add("wysiwygarea", {init: function (a) {
            a.config.fullPage && a.addFeature({allowedContent: "html head title; style [media,type]; body (*)[id]; meta link [*]", requiredContent: "body"});
            a.addMode("wysiwyg", function (b) {
                function c(e) {
                    e && e.removeListener();
                    a.editable(new g(a, f.$.contentWindow.document.body));
                    a.setData(a.getData(1), b)
                }

                var f = CKEDITOR.document.createElement("iframe");
                f.setStyles({width: "100%", height: "100%"});
                f.addClass("cke_wysiwyg_frame cke_reset");
                var k = a.ui.space("contents");
                k.append(f);
                var m = "document.open();" + (h ? 'document.domain="' + document.domain + '";' : "") + "document.close();", m = CKEDITOR.env.air ? "javascript:void(0)" : CKEDITOR.env.ie ? "javascript:void(function(){" + encodeURIComponent(m) + "}())" : "", n = CKEDITOR.env.ie || CKEDITOR.env.gecko;
                if (n)f.on("load", c);
                var l = [a.lang.editor, a.name].join(),
                    o = a.lang.common.editorHelp;
                CKEDITOR.env.ie && (l = l + (", " + o));
                var q = CKEDITOR.tools.getNextId(), s = CKEDITOR.dom.element.createFromHtml('<span id="' + q + '" class="cke_voice_label">' + o + "</span>");
                k.append(s, 1);
                a.on("beforeModeUnload", function (a) {
                    a.removeListener();
                    s.remove()
                });
                f.setAttributes({frameBorder: 0, "aria-describedby": q, title: l, src: m, tabIndex: a.tabIndex, allowTransparency: "true"});
                !n && c();
                if (CKEDITOR.env.webkit) {
                    m = function () {
                        k.setStyle("width", "100%");
                        f.hide();
                        f.setSize("width", k.getSize("width"));
                        k.removeStyle("width");
                        f.show()
                    };
                    f.setCustomData("onResize", m);
                    CKEDITOR.document.getWindow().on("resize", m)
                }
                a.fire("ariaWidget", f)
            })
        }});
        var h = CKEDITOR.env.isCustomDomain(), g = CKEDITOR.tools.createClass({$: function (a) {
            this.base.apply(this, arguments);
            this._.frameLoadedHandler = CKEDITOR.tools.addFunction(function (a) {
                CKEDITOR.tools.setTimeout(b, 0, this, a)
            }, this);
            this._.docTitle = this.getWindow().getFrame().getAttribute("title")
        }, base: CKEDITOR.editable, proto: {setData: function (a, b) {
            var c = this.editor;
            if (b)this.setHtml(a);
            else {
                this._.isLoadingData = true;
                c._.dataStore = {id: 1};
                var g = c.config, k = g.fullPage, m = g.docType, n = CKEDITOR.tools.buildStyleHtml(f()).replace(/<style>/, '<style data-cke-temp="1">');
                k || (n = n + CKEDITOR.tools.buildStyleHtml(c.config.contentsCss));
                var l = g.baseHref ? '<base href="' + g.baseHref + '" data-cke-temp="1" />' : "";
                k && (a = a.replace(/<!DOCTYPE[^>]*>/i,function (a) {
                    c.docType = m = a;
                    return""
                }).replace(/<\?xml\s[^\?]*\?>/i, function (a) {
                    c.xmlDeclaration = a;
                    return""
                }));
                c.dataProcessor && (a = c.dataProcessor.toHtml(a));
                if (k) {
                    /<body[\s|>]/.test(a) ||
                    (a = "<body>" + a);
                    /<html[\s|>]/.test(a) || (a = "<html>" + a + "</html>");
                    /<head[\s|>]/.test(a) ? /<title[\s|>]/.test(a) || (a = a.replace(/<head[^>]*>/, "$&<title></title>")) : a = a.replace(/<html[^>]*>/, "$&<head><title></title></head>");
                    l && (a = a.replace(/<head>/, "$&" + l));
                    a = a.replace(/<\/head\s*>/, n + "$&");
                    a = m + a
                } else a = g.docType + '<html dir="' + g.contentsLangDirection + '" lang="' + (g.contentsLanguage || c.langCode) + '"><head><title>' + this._.docTitle + "</title>" + l + n + "</head><body" + (g.bodyId ? ' id="' + g.bodyId + '"' : "") + (g.bodyClass ?
                    ' class="' + g.bodyClass + '"' : "") + ">" + a + "</body></html>";
                if (CKEDITOR.env.gecko) {
                    a = a.replace(/<body/, '<body contenteditable="true" ');
                    CKEDITOR.env.version < 2E4 && (a = a.replace(/<body[^>]*>/, "$&<\!-- cke-content-start --\>"))
                }
                g = '<script id="cke_actscrpt" type="text/javascript"' + (CKEDITOR.env.ie ? ' defer="defer" ' : "") + ">" + (h ? 'document.domain="' + document.domain + '";' : "") + "var wasLoaded=0;function onload(){if(!wasLoaded)window.parent.CKEDITOR.tools.callFunction(" + this._.frameLoadedHandler + ",window);wasLoaded=1;}" +
                    (CKEDITOR.env.ie ? "onload();" : 'document.addEventListener("DOMContentLoaded", onload, false );') + "<\/script>";
                CKEDITOR.env.ie && CKEDITOR.env.version < 9 && (g = g + '<script id="cke_shimscrpt">(function(){var e="abbr,article,aside,audio,bdi,canvas,data,datalist,details,figcaption,figure,footer,header,hgroup,mark,meter,nav,output,progress,section,summary,time,video".split(","),i=e.length;while(i--){document.createElement(e[i])}})()<\/script>');
                a = a.replace(/(?=\s*<\/(:?head)>)/, g);
                this.clearCustomData();
                this.clearListeners();
                c.fire("contentDomUnload");
                var o = this.getDocument();
                try {
                    o.write(a)
                } catch (q) {
                    setTimeout(function () {
                        o.write(a)
                    }, 0)
                }
            }
        }, getData: function (a) {
            if (a)return this.getHtml();
            var a = this.editor, b = a.config.fullPage, c = b && a.docType, f = b && a.xmlDeclaration, g = this.getDocument(), b = b ? g.getDocumentElement().getOuterHtml() : g.getBody().getHtml();
            CKEDITOR.env.gecko && (b = b.replace(/<br>(?=\s*(:?$|<\/body>))/, ""));
            a.dataProcessor && (b = a.dataProcessor.toDataFormat(b));
            f && (b = f + "\n" + b);
            c && (b = c + "\n" + b);
            return b
        }, focus: function () {
            this._.isLoadingData ?
                this._.isPendingFocus = true : g.baseProto.focus.call(this)
        }, detach: function () {
            var a = this.editor, b = a.document, c = a.window.getFrame();
            g.baseProto.detach.call(this);
            this.clearCustomData();
            b.getDocumentElement().clearCustomData();
            c.clearCustomData();
            CKEDITOR.tools.removeFunction(this._.frameLoadedHandler);
            (b = c.removeCustomData("onResize")) && b.removeListener();
            a.fire("contentDomUnload");
            c.remove()
        }}})
    }(), CKEDITOR.config.disableObjectResizing = !1, CKEDITOR.config.disableNativeTableHandles = !0, CKEDITOR.config.disableNativeSpellChecker = !0, CKEDITOR.config.contentsCss = CKEDITOR.basePath + "contents.css", CKEDITOR.config.plugins = "dialogui,dialog,a11yhelp,about,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,menu,contextmenu,dialogadvtab,div,elementspath,list,indent,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,format,forms,horizontalrule,htmlwriter,iframe,image,justify,link,liststyle,magicline,maximize,newpage,pagebreak,pastefromword,pastetext,preview,print,removeformat,resize,save,menubutton,scayt,selectall,showblocks,showborders,smiley,sourcearea,specialchar,stylescombo,tab,table,tabletools,templates,toolbar,undo,wsc,wysiwygarea",
        CKEDITOR.config.skin = "moono", function () {
        for (var b = "about,0,bold,32,italic,64,strike,96,subscript,128,superscript,160,underline,192,bidiltr,224,bidirtl,256,blockquote,288,copy-rtl,320,copy,352,cut-rtl,384,cut,416,paste-rtl,448,paste,480,bgcolor,512,textcolor,544,creatediv,576,find-rtl,608,find,640,replace,672,flash,704,button,736,checkbox,768,form,800,hiddenfield,832,imagebutton,864,radio,896,select-rtl,928,select,960,textarea-rtl,992,textarea,1024,textfield-rtl,1056,textfield,1088,horizontalrule,1120,iframe,1152,image,1184,indent-rtl,1216,indent,1248,outdent-rtl,1280,outdent,1312,justifyblock,1344,justifycenter,1376,justifyleft,1408,justifyright,1440,anchor-rtl,1472,anchor,1504,link,1536,unlink,1568,bulletedlist-rtl,1600,bulletedlist,1632,numberedlist-rtl,1664,numberedlist,1696,maximize,1728,newpage-rtl,1760,newpage,1792,pagebreak-rtl,1824,pagebreak,1856,pastefromword-rtl,1888,pastefromword,1920,pastetext-rtl,1952,pastetext,1984,preview-rtl,2016,preview,2048,print,2080,removeformat,2112,save,2144,scayt,2176,selectall,2208,showblocks-rtl,2240,showblocks,2272,smiley,2304,source-rtl,2336,source,2368,specialchar,2400,table,2432,templates-rtl,2464,templates,2496,redo-rtl,2528,redo,2560,undo-rtl,2592,undo,2624,spellchecker,2656",
                 c = CKEDITOR.getUrl("plugins/icons.png"), b = b.split(","), a = 0; a < b.length; a++)CKEDITOR.skin.icons[b[a]] = {path: c, offset: -b[++a]}
    }()
})();