function rtcScript() {
    document.oncontextmenu = null, document.onselectstart = null, document.onmousedown = null, document.onclick = null, document.oncopy = null, document.oncut = null;
    for (var e = document.getElementsByTagName("*"), a = 0; a < e.length; a++) e[a].oncontextmenu = null, e[a].onselectstart = null, e[a].onmousedown = null, e[a].oncopy = null, e[a].oncut = null;
    for (var t = document.getElementsByTagName("script"), a = 0; a < t.length; a++) t[a].src.indexOf("w.sharethis.com") > -1 && function() {
        document.getSelection = window.getSelection = function() {
            return {
                isCollapsed: !0
            }
        }
    }();
    "undefined" != typeof Tynt && (Tynt = null)
}
window.Modernizr = function(e, a, t) {
    function i(e) {
        h.cssText = e
    }

    function s(e, a) {
        return typeof e === a
    }

    function r(e, a) {
        return !!~("" + e).indexOf(a)
    }

    function n(e, a) {
        for (var i in e) {
            var s = e[i];
            if (!r(s, "-") && h[s] !== t) return "pfx" != a || s
        }
        return !1
    }

    function o(e, a, i) {
        for (var r in e) {
            var n = a[e[r]];
            if (n !== t) return !1 === i ? e[r] : s(n, "function") ? n.bind(i || a) : n
        }
        return !1
    }

    function l(e, a, t) {
        var i = e.charAt(0).toUpperCase() + e.slice(1),
            r = (e + " " + f.join(i + " ") + i).split(" ");
        return s(a, "string") || s(a, "undefined") ? n(r, a) : (r = (e + " " + v.join(i + " ") + i).split(" "), o(r, a, t))
    }
    var p, d, u = {},
        c = a.documentElement,
        m = a.createElement("modernizr"),
        h = m.style,
        g = "Webkit Moz O ms",
        f = g.split(" "),
        v = g.toLowerCase().split(" "),
        w = {},
        y = [],
        x = y.slice,
        b = {}.hasOwnProperty;
    d = s(b, "undefined") || s(b.call, "undefined") ? function(e, a) {
        return a in e && s(e.constructor.prototype[a], "undefined")
    } : function(e, a) {
        return b.call(e, a)
    }, Function.prototype.bind || (Function.prototype.bind = function(e) {
        var a = this;
        if ("function" != typeof a) throw new TypeError;
        var t = x.call(arguments, 1),
            i = function() {
                if (this instanceof i) {
                    var s = function() {};
                    s.prototype = a.prototype;
                    var r = new s,
                        n = a.apply(r, t.concat(x.call(arguments)));
                    return Object(n) === n ? n : r
                }
                return a.apply(e, t.concat(x.call(arguments)))
            };
        return i
    }), w.cssanimations = function() {
        return l("animationName")
    }, w.csstransforms = function() {
        return !!l("transform")
    };
    for (var T in w) d(w, T) && (p = T.toLowerCase(), u[p] = w[T](), y.push((u[p] ? "" : "no-") + p));
    return u.addTest = function(e, a) {
        if ("object" == typeof e)
            for (var i in e) d(e, i) && u.addTest(i, e[i]);
        else {
            if (e = e.toLowerCase(), u[e] !== t) return u;
            a = "function" == typeof a ? a() : a, c.className += " " + (a ? "" : "no-") + e, u[e] = a
        }
        return u
    }, i(""), m = null,
        function(e, a) {
            function t(e, a) {
                var t = e.createElement("p"),
                    i = e.getElementsByTagName("head")[0] || e.documentElement;
                return t.innerHTML = "x<style>" + a + "</style>", i.insertBefore(t.lastChild, i.firstChild)
            }

            function i() {
                var e = v.elements;
                return "string" == typeof e ? e.split(" ") : e
            }

            function s(e) {
                var a = f[e[h]];
                return a || (a = {}, g++, e[h] = g, f[g] = a), a
            }

            function r(e, t, i) {
                if (t || (t = a), d) return t.createElement(e);
                i || (i = s(t));
                var r;
                return r = i.cache[e] ? i.cache[e].cloneNode() : m.test(e) ? (i.cache[e] = i.createElem(e)).cloneNode() : i.createElem(e), r.canHaveChildren && !c.test(e) ? i.frag.appendChild(r) : r
            }

            function n(e, t) {
                if (e || (e = a), d) return e.createDocumentFragment();
                t = t || s(e);
                for (var r = t.frag.cloneNode(), n = 0, o = i(), l = o.length; n < l; n++) r.createElement(o[n]);
                return r
            }

            function o(e, a) {
                a.cache || (a.cache = {}, a.createElem = e.createElement, a.createFrag = e.createDocumentFragment, a.frag = a.createFrag()), e.createElement = function(t) {
                    return v.shivMethods ? r(t, e, a) : a.createElem(t)
                }, e.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + i().join().replace(/\w+/g, function(e) {
                    return a.createElem(e), a.frag.createElement(e), 'c("' + e + '")'
                }) + ");return n}")(v, a.frag)
            }

            function l(e) {
                e || (e = a);
                var i = s(e);
                return v.shivCSS && !p && !i.hasCSS && (i.hasCSS = !!t(e, "article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")), d || o(e, i), e
            }
            var p, d, u = e.html5 || {},
                c = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
                m = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
                h = "_html5shiv",
                g = 0,
                f = {};
            ! function() {
                try {
                    var e = a.createElement("a");
                    e.innerHTML = "<xyz></xyz>", p = "hidden" in e, d = 1 == e.childNodes.length || function() {
                        a.createElement("a");
                        var e = a.createDocumentFragment();
                        return void 0 === e.cloneNode || void 0 === e.createDocumentFragment || void 0 === e.createElement
                    }()
                } catch (e) {
                    p = !0, d = !0
                }
            }();
            var v = {
                elements: u.elements || "abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",
                shivCSS: !1 !== u.shivCSS,
                supportsUnknownElements: d,
                shivMethods: !1 !== u.shivMethods,
                type: "default",
                shivDocument: l,
                createElement: r,
                createDocumentFragment: n
            };
            e.html5 = v, l(a)
        }(this, a), u._version = "2.6.2", u._domPrefixes = v, u._cssomPrefixes = f, u.testProp = function(e) {
        return n([e])
    }, u.testAllProps = l, u.prefixed = function(e, a, t) {
        return a ? l(e, a, t) : l(e, "pfx")
    }, c.className = c.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + " js " + y.join(" "), u
}(0, this.document),
    function(e, a, t) {
        function i(e) {
            return "[object Function]" == f.call(e)
        }

        function s(e) {
            return "string" == typeof e
        }

        function r() {}

        function n(e) {
            return !e || "loaded" == e || "complete" == e || "uninitialized" == e
        }

        function o() {
            var e = v.shift();
            w = 1, e ? e.t ? h(function() {
                ("c" == e.t ? c.injectCss : c.injectJs)(e.s, 0, e.a, e.x, e.e, 1)
            }, 0) : (e(), o()) : w = 0
        }

        function l(e, t, i, s, r, l, p) {
            function d(a) {
                if (!m && n(u.readyState) && (y.r = m = 1, !w && o(), u.onload = u.onreadystatechange = null, a)) {
                    "img" != e && h(function() {
                        b.removeChild(u)
                    }, 50);
                    for (var i in M[t]) M[t].hasOwnProperty(i) && M[t][i].onload()
                }
            }
            var p = p || c.errorTimeout,
                u = a.createElement(e),
                m = 0,
                f = 0,
                y = {
                    t: i,
                    s: t,
                    e: r,
                    a: l,
                    x: p
                };
            1 === M[t] && (f = 1, M[t] = []), "object" == e ? u.data = t : (u.src = t, u.type = e), u.width = u.height = "0", u.onerror = u.onload = u.onreadystatechange = function() {
                d.call(this, f)
            }, v.splice(s, 0, y), "img" != e && (f || 2 === M[t] ? (b.insertBefore(u, x ? null : g), h(d, p)) : M[t].push(u))
        }

        function p(e, a, t, i, r) {
            return w = 0, a = a || "j", s(e) ? l("c" == a ? S : T, e, a, this.i++, t, i, r) : (v.splice(this.i++, 0, e), 1 == v.length && o()), this
        }

        function d() {
            var e = c;
            return e.loader = {
                load: p,
                i: 0
            }, e
        }
        var u, c, m = a.documentElement,
            h = e.setTimeout,
            g = a.getElementsByTagName("script")[0],
            f = {}.toString,
            v = [],
            w = 0,
            y = "MozAppearance" in m.style,
            x = y && !!a.createRange().compareNode,
            b = x ? m : g.parentNode,
            m = e.opera && "[object Opera]" == f.call(e.opera),
            m = !!a.attachEvent && !m,
            T = y ? "object" : m ? "script" : "img",
            S = m ? "script" : T,
            C = Array.isArray || function(e) {
                return "[object Array]" == f.call(e)
            },
            z = [],
            M = {},
            E = {
                timeout: function(e, a) {
                    return a.length && (e.timeout = a[0]), e
                }
            };
        c = function(e) {
            function a(e) {
                var a, t, i, e = e.split("!"),
                    s = z.length,
                    r = e.pop(),
                    n = e.length,
                    r = {
                        url: r,
                        origUrl: r,
                        prefixes: e
                    };
                for (t = 0; t < n; t++) i = e[t].split("="), (a = E[i.shift()]) && (r = a(r, i));
                for (t = 0; t < s; t++) r = z[t](r);
                return r
            }

            function n(e, s, r, n, o) {
                var l = a(e),
                    p = l.autoCallback;
                l.url.split(".").pop().split("?").shift(), l.bypass || (s && (s = i(s) ? s : s[e] || s[n] || s[e.split("/").pop().split("?")[0]]), l.instead ? l.instead(e, s, r, n, o) : (M[l.url] ? l.noexec = !0 : M[l.url] = 1, r.load(l.url, l.forceCSS || !l.forceJS && "css" == l.url.split(".").pop().split("?").shift() ? "c" : t, l.noexec, l.attrs, l.timeout), (i(s) || i(p)) && r.load(function() {
                    d(), s && s(l.origUrl, o, n), p && p(l.origUrl, o, n), M[l.url] = 2
                })))
            }

            function o(e, a) {
                function t(e, t) {
                    if (e) {
                        if (s(e)) t || (u = function() {
                            var e = [].slice.call(arguments);
                            c.apply(this, e), m()
                        }), n(e, u, a, 0, p);
                        else if (Object(e) === e)
                            for (l in o = function() {
                                var a, t = 0;
                                for (a in e) e.hasOwnProperty(a) && t++;
                                return t
                            }(), e) e.hasOwnProperty(l) && (!t && !--o && (i(u) ? u = function() {
                                var e = [].slice.call(arguments);
                                c.apply(this, e), m()
                            } : u[l] = function(e) {
                                return function() {
                                    var a = [].slice.call(arguments);
                                    e && e.apply(this, a), m()
                                }
                            }(c[l])), n(e[l], u, a, l, p))
                    } else !t && m()
                }
                var o, l, p = !!e.test,
                    d = e.load || e.both,
                    u = e.callback || r,
                    c = u,
                    m = e.complete || r;
                t(p ? e.yep : e.nope, !!d), d && t(d)
            }
            var l, p, u = this.yepnope.loader;
            if (s(e)) n(e, 0, u, 0);
            else if (C(e))
                for (l = 0; l < e.length; l++) p = e[l], s(p) ? n(p, 0, u, 0) : C(p) ? c(p) : Object(p) === p && o(p, u);
            else Object(e) === e && o(e, u)
        }, c.addPrefix = function(e, a) {
            E[e] = a
        }, c.addFilter = function(e) {
            z.push(e)
        }, c.errorTimeout = 1e4, null == a.readyState && a.addEventListener && (a.readyState = "loading", a.addEventListener("DOMContentLoaded", u = function() {
            a.removeEventListener("DOMContentLoaded", u, 0), a.readyState = "complete"
        }, 0)), e.yepnope = d(), e.yepnope.executeStack = o, e.yepnope.injectJs = function(e, t, i, s, l, p) {
            var d, u, m = a.createElement("script"),
                s = s || c.errorTimeout;
            m.src = e;
            for (u in i) m.setAttribute(u, i[u]);
            t = p ? o : t || r, m.onreadystatechange = m.onload = function() {
                !d && n(m.readyState) && (d = 1, t(), m.onload = m.onreadystatechange = null)
            }, h(function() {
                d || (d = 1, t(1))
            }, s), l ? m.onload() : g.parentNode.insertBefore(m, g)
        }, e.yepnope.injectCss = function(e, t, i, s, n, l) {
            var p, s = a.createElement("link"),
                t = l ? o : t || r;
            s.href = e, s.rel = "stylesheet", s.type = "text/css";
            for (p in i) s.setAttribute(p, i[p]);
            n || (g.parentNode.insertBefore(s, g), h(t, 0))
        }
    }(this, document), Modernizr.load = function() {
    yepnope.apply(window, [].slice.call(arguments, 0))
},
    function(e) {
        e.fn.menumaker = function(a) {
            var t = e(this),
                i = e.extend({
                    title: "Menu",
                    format: "dropdown",
                    sticky: !1
                }, a);
            return this.each(function() {
                return t.prepend('<div id="menu-button">' + i.title + "</div>"), e(this).find("#menu-button").on("click", function() {
                    e(this).toggleClass("menu-opened");
                    var a = e(this).next("ul");
                    a.hasClass("open") ? a.hide().removeClass("open") : (a.show().addClass("open"), "dropdown" === i.format && a.find("ul").show())
                }), t.find("li ul").parent().addClass("has-sub"), multiTg = function() {
                    t.find(".has-sub").prepend('<span class="submenu-button"></span>'), t.find(".submenu-button").on("click", function() {
                        e(this).toggleClass("submenu-opened"), e(this).siblings("ul").hasClass("open") ? e(this).siblings("ul").removeClass("open").hide() : e(this).siblings("ul").addClass("open").show()
                    })
                }, "multitoggle" === i.format ? multiTg() : t.addClass("dropdown"), !0 === i.sticky && t.css("position", "fixed"), resizeFix = function() {
                    e(window).width() > 768 && t.find("ul").show(), e(window).width() <= 768 && t.find("ul").hide().removeClass("open")
                }, resizeFix(), e(window).on("resize", resizeFix)
            })
        }
    }(jQuery),
    function(e) {
        e(document).ready(function() {
            e("#cssmenu").menumaker({
                title: "Menu",
                format: "multitoggle"
            })
        })
    }(jQuery), ! function() {
    "use strict";
    var e, a = function(i, s) {
        function r(e) {
            return Math.floor(e)
        }

        function n() {
            var e = b.params.autoplay,
                a = b.slides.eq(b.activeIndex);
            a.attr("data-swiper-autoplay") && (e = a.attr("data-swiper-autoplay") || b.params.autoplay), b.autoplayTimeoutId = setTimeout(function() {
                b.params.loop ? (b.fixLoop(), b._slideNext(), b.emit("onAutoplay", b)) : b.isEnd ? s.autoplayStopOnLast ? b.stopAutoplay() : (b._slideTo(0), b.emit("onAutoplay", b)) : (b._slideNext(), b.emit("onAutoplay", b))
            }, e)
        }

        function o(a, t) {
            var i = e(a.target);
            if (!i.is(t))
                if ("string" == typeof t) i = i.parents(t);
                else if (t.nodeType) {
                    var s;
                    return i.parents().each(function(e, a) {
                        a === t && (s = t)
                    }), s ? t : void 0
                }
            if (0 !== i.length) return i[0]
        }

        function l(e, a) {
            a = a || {};
            var t = window.MutationObserver || window.WebkitMutationObserver,
                i = new t(function(e) {
                    e.forEach(function(e) {
                        b.onResize(!0), b.emit("onObserverUpdate", b, e)
                    })
                });
            i.observe(e, {
                attributes: void 0 === a.attributes || a.attributes,
                childList: void 0 === a.childList || a.childList,
                characterData: void 0 === a.characterData || a.characterData
            }), b.observers.push(i)
        }

        function p(e) {
            e.originalEvent && (e = e.originalEvent);
            var a = e.keyCode || e.charCode;
            if (!b.params.allowSwipeToNext && (b.isHorizontal() && 39 === a || !b.isHorizontal() && 40 === a)) return !1;
            if (!b.params.allowSwipeToPrev && (b.isHorizontal() && 37 === a || !b.isHorizontal() && 38 === a)) return !1;
            if (!(e.shiftKey || e.altKey || e.ctrlKey || e.metaKey || document.activeElement && document.activeElement.nodeName && ("input" === document.activeElement.nodeName.toLowerCase() || "textarea" === document.activeElement.nodeName.toLowerCase()))) {
                if (37 === a || 39 === a || 38 === a || 40 === a) {
                    var t = !1;
                    if (b.container.parents("." + b.params.slideClass).length > 0 && 0 === b.container.parents("." + b.params.slideActiveClass).length) return;
                    var i = {
                            left: window.pageXOffset,
                            top: window.pageYOffset
                        },
                        s = window.innerWidth,
                        r = window.innerHeight,
                        n = b.container.offset();
                    b.rtl && (n.left = n.left - b.container[0].scrollLeft);
                    for (var o = [
                        [n.left, n.top],
                        [n.left + b.width, n.top],
                        [n.left, n.top + b.height],
                        [n.left + b.width, n.top + b.height]
                    ], l = 0; l < o.length; l++) {
                        var p = o[l];
                        p[0] >= i.left && p[0] <= i.left + s && p[1] >= i.top && p[1] <= i.top + r && (t = !0)
                    }
                    if (!t) return
                }
                b.isHorizontal() ? (37 !== a && 39 !== a || (e.preventDefault ? e.preventDefault() : e.returnValue = !1), (39 === a && !b.rtl || 37 === a && b.rtl) && b.slideNext(), (37 === a && !b.rtl || 39 === a && b.rtl) && b.slidePrev()) : (38 !== a && 40 !== a || (e.preventDefault ? e.preventDefault() : e.returnValue = !1), 40 === a && b.slideNext(), 38 === a && b.slidePrev()), b.emit("onKeyPress", b, a)
            }
        }

        function d(e) {
            var a = 0,
                t = 0,
                i = 0,
                s = 0;
            return "detail" in e && (t = e.detail), "wheelDelta" in e && (t = -e.wheelDelta / 120), "wheelDeltaY" in e && (t = -e.wheelDeltaY / 120), "wheelDeltaX" in e && (a = -e.wheelDeltaX / 120), "axis" in e && e.axis === e.HORIZONTAL_AXIS && (a = t, t = 0), i = 10 * a, s = 10 * t, "deltaY" in e && (s = e.deltaY), "deltaX" in e && (i = e.deltaX), (i || s) && e.deltaMode && (1 === e.deltaMode ? (i *= 40, s *= 40) : (i *= 800, s *= 800)), i && !a && (a = i < 1 ? -1 : 1), s && !t && (t = s < 1 ? -1 : 1), {
                spinX: a,
                spinY: t,
                pixelX: i,
                pixelY: s
            }
        }

        function u(e) {
            e.originalEvent && (e = e.originalEvent);
            var a = 0,
                t = b.rtl ? -1 : 1,
                i = d(e);
            if (b.params.mousewheelForceToAxis)
                if (b.isHorizontal()) {
                    if (!(Math.abs(i.pixelX) > Math.abs(i.pixelY))) return;
                    a = i.pixelX * t
                } else {
                    if (!(Math.abs(i.pixelY) > Math.abs(i.pixelX))) return;
                    a = i.pixelY
                } else a = Math.abs(i.pixelX) > Math.abs(i.pixelY) ? -i.pixelX * t : -i.pixelY;
            if (0 !== a) {
                if (b.params.mousewheelInvert && (a = -a), b.params.freeMode) {
                    var s = b.getWrapperTranslate() + a * b.params.mousewheelSensitivity,
                        r = b.isBeginning,
                        n = b.isEnd;
                    if (s >= b.minTranslate() && (s = b.minTranslate()), s <= b.maxTranslate() && (s = b.maxTranslate()), b.setWrapperTransition(0), b.setWrapperTranslate(s), b.updateProgress(), b.updateActiveIndex(), (!r && b.isBeginning || !n && b.isEnd) && b.updateClasses(), b.params.freeModeSticky ? (clearTimeout(b.mousewheel.timeout), b.mousewheel.timeout = setTimeout(function() {
                        b.slideReset()
                    }, 300)) : b.params.lazyLoading && b.lazy && b.lazy.load(), b.emit("onScroll", b, e), b.params.autoplay && b.params.autoplayDisableOnInteraction && b.stopAutoplay(), 0 === s || s === b.maxTranslate()) return
                } else {
                    if ((new window.Date).getTime() - b.mousewheel.lastScrollTime > 60)
                        if (a < 0)
                            if (b.isEnd && !b.params.loop || b.animating) {
                                if (b.params.mousewheelReleaseOnEdges) return !0
                            } else b.slideNext(), b.emit("onScroll", b, e);
                        else if (b.isBeginning && !b.params.loop || b.animating) {
                            if (b.params.mousewheelReleaseOnEdges) return !0
                        } else b.slidePrev(), b.emit("onScroll", b, e);
                    b.mousewheel.lastScrollTime = (new window.Date).getTime()
                }
                return e.preventDefault ? e.preventDefault() : e.returnValue = !1, !1
            }
        }

        function c(a, t) {
            a = e(a);
            var i, s, r, n = b.rtl ? -1 : 1;
            i = a.attr("data-swiper-parallax") || "0", s = a.attr("data-swiper-parallax-x"), r = a.attr("data-swiper-parallax-y"), s || r ? (s = s || "0", r = r || "0") : b.isHorizontal() ? (s = i, r = "0") : (r = i, s = "0"), s = s.indexOf("%") >= 0 ? parseInt(s, 10) * t * n + "%" : s * t * n + "px", r = r.indexOf("%") >= 0 ? parseInt(r, 10) * t + "%" : r * t + "px", a.transform("translate3d(" + s + ", " + r + ",0px)")
        }

        function m(e) {
            return 0 !== e.indexOf("on") && (e = e[0] !== e[0].toUpperCase() ? "on" + e[0].toUpperCase() + e.substring(1) : "on" + e), e
        }
        if (!(this instanceof a)) return new a(i, s);
        var h = {
                direction: "horizontal",
                touchEventsTarget: "container",
                initialSlide: 0,
                speed: 300,
                autoplay: !1,
                autoplayDisableOnInteraction: !0,
                autoplayStopOnLast: !1,
                iOSEdgeSwipeDetection: !1,
                iOSEdgeSwipeThreshold: 20,
                freeMode: !1,
                freeModeMomentum: !0,
                freeModeMomentumRatio: 1,
                freeModeMomentumBounce: !0,
                freeModeMomentumBounceRatio: 1,
                freeModeMomentumVelocityRatio: 1,
                freeModeSticky: !1,
                freeModeMinimumVelocity: .02,
                autoHeight: !1,
                setWrapperSize: !1,
                virtualTranslate: !1,
                effect: "slide",
                coverflow: {
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: !0
                },
                flip: {
                    slideShadows: !0,
                    limitRotation: !0
                },
                cube: {
                    slideShadows: !0,
                    shadow: !0,
                    shadowOffset: 20,
                    shadowScale: .94
                },
                fade: {
                    crossFade: !1
                },
                parallax: !1,
                zoom: !1,
                zoomMax: 3,
                zoomMin: 1,
                zoomToggle: !0,
                scrollbar: null,
                scrollbarHide: !0,
                scrollbarDraggable: !1,
                scrollbarSnapOnRelease: !1,
                keyboardControl: !1,
                mousewheelControl: !1,
                mousewheelReleaseOnEdges: !1,
                mousewheelInvert: !1,
                mousewheelForceToAxis: !1,
                mousewheelSensitivity: 1,
                mousewheelEventsTarged: "container",
                hashnav: !1,
                hashnavWatchState: !1,
                history: !1,
                replaceState: !1,
                breakpoints: void 0,
                spaceBetween: 0,
                slidesPerView: 1,
                slidesPerColumn: 1,
                slidesPerColumnFill: "column",
                slidesPerGroup: 1,
                centeredSlides: !1,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0,
                roundLengths: !1,
                touchRatio: 1,
                touchAngle: 45,
                simulateTouch: !0,
                shortSwipes: !0,
                longSwipes: !0,
                longSwipesRatio: .5,
                longSwipesMs: 300,
                followFinger: !0,
                onlyExternal: !1,
                threshold: 0,
                touchMoveStopPropagation: !0,
                touchReleaseOnEdges: !1,
                uniqueNavElements: !0,
                pagination: null,
                paginationElement: "span",
                paginationClickable: !1,
                paginationHide: !1,
                paginationBulletRender: null,
                paginationProgressRender: null,
                paginationFractionRender: null,
                paginationCustomRender: null,
                paginationType: "bullets",
                resistance: !0,
                resistanceRatio: .85,
                nextButton: null,
                prevButton: null,
                watchSlidesProgress: !1,
                watchSlidesVisibility: !1,
                grabCursor: !1,
                preventClicks: !0,
                preventClicksPropagation: !0,
                slideToClickedSlide: !1,
                lazyLoading: !1,
                lazyLoadingInPrevNext: !1,
                lazyLoadingInPrevNextAmount: 1,
                lazyLoadingOnTransitionStart: !1,
                preloadImages: !0,
                updateOnImagesReady: !0,
                loop: !1,
                loopAdditionalSlides: 0,
                loopedSlides: null,
                control: void 0,
                controlInverse: !1,
                controlBy: "slide",
                normalizeSlideIndex: !0,
                allowSwipeToPrev: !0,
                allowSwipeToNext: !0,
                swipeHandler: null,
                noSwiping: !0,
                noSwipingClass: "swiper-no-swiping",
                passiveListeners: !0,
                containerModifierClass: "swiper-container-",
                slideClass: "swiper-slide",
                slideActiveClass: "swiper-slide-active",
                slideDuplicateActiveClass: "swiper-slide-duplicate-active",
                slideVisibleClass: "swiper-slide-visible",
                slideDuplicateClass: "swiper-slide-duplicate",
                slideNextClass: "swiper-slide-next",
                slideDuplicateNextClass: "swiper-slide-duplicate-next",
                slidePrevClass: "swiper-slide-prev",
                slideDuplicatePrevClass: "swiper-slide-duplicate-prev",
                wrapperClass: "swiper-wrapper",
                bulletClass: "swiper-pagination-bullet",
                bulletActiveClass: "swiper-pagination-bullet-active",
                buttonDisabledClass: "swiper-button-disabled",
                paginationCurrentClass: "swiper-pagination-current",
                paginationTotalClass: "swiper-pagination-total",
                paginationHiddenClass: "swiper-pagination-hidden",
                paginationProgressbarClass: "swiper-pagination-progressbar",
                paginationClickableClass: "swiper-pagination-clickable",
                paginationModifierClass: "swiper-pagination-",
                lazyLoadingClass: "swiper-lazy",
                lazyStatusLoadingClass: "swiper-lazy-loading",
                lazyStatusLoadedClass: "swiper-lazy-loaded",
                lazyPreloaderClass: "swiper-lazy-preloader",
                notificationClass: "swiper-notification",
                preloaderClass: "preloader",
                zoomContainerClass: "swiper-zoom-container",
                observer: !1,
                observeParents: !1,
                a11y: !1,
                prevSlideMessage: "Previous slide",
                nextSlideMessage: "Next slide",
                firstSlideMessage: "This is the first slide",
                lastSlideMessage: "This is the last slide",
                paginationBulletMessage: "Go to slide {{index}}",
                runCallbacksOnInit: !0
            },
            g = s && s.virtualTranslate;
        s = s || {};
        var f = {};
        for (var v in s)
            if ("object" != typeof s[v] || null === s[v] || s[v].nodeType || s[v] === window || s[v] === document || void 0 !== t && s[v] instanceof t || "undefined" != typeof jQuery && s[v] instanceof jQuery) f[v] = s[v];
            else {
                f[v] = {};
                for (var w in s[v]) f[v][w] = s[v][w]
            }
        for (var y in h)
            if (void 0 === s[y]) s[y] = h[y];
            else if ("object" == typeof s[y])
                for (var x in h[y]) void 0 === s[y][x] && (s[y][x] = h[y][x]);
        var b = this;
        if (b.params = s, b.originalParams = f, b.classNames = [], void 0 !== e && void 0 !== t && (e = t), (void 0 !== e || (e = void 0 === t ? window.Dom7 || window.Zepto || window.jQuery : t)) && (b.$ = e, b.currentBreakpoint = void 0, b.getActiveBreakpoint = function() {
            if (!b.params.breakpoints) return !1;
            var e, a = !1,
                t = [];
            for (e in b.params.breakpoints) b.params.breakpoints.hasOwnProperty(e) && t.push(e);
            t.sort(function(e, a) {
                return parseInt(e, 10) > parseInt(a, 10)
            });
            for (var i = 0; i < t.length; i++)(e = t[i]) >= window.innerWidth && !a && (a = e);
            return a || "max"
        }, b.setBreakpoint = function() {
            var e = b.getActiveBreakpoint();
            if (e && b.currentBreakpoint !== e) {
                var a = e in b.params.breakpoints ? b.params.breakpoints[e] : b.originalParams,
                    t = b.params.loop && a.slidesPerView !== b.params.slidesPerView;
                for (var i in a) b.params[i] = a[i];
                b.currentBreakpoint = e, t && b.destroyLoop && b.reLoop(!0)
            }
        }, b.params.breakpoints && b.setBreakpoint(), b.container = e(i), 0 !== b.container.length)) {
            if (b.container.length > 1) {
                var T = [];
                return b.container.each(function() {
                    T.push(new a(this, s))
                }), T
            }
            b.container[0].swiper = b, b.container.data("swiper", b), b.classNames.push(b.params.containerModifierClass + b.params.direction), b.params.freeMode && b.classNames.push(b.params.containerModifierClass + "free-mode"), b.support.flexbox || (b.classNames.push(b.params.containerModifierClass + "no-flexbox"), b.params.slidesPerColumn = 1), b.params.autoHeight && b.classNames.push(b.params.containerModifierClass + "autoheight"), (b.params.parallax || b.params.watchSlidesVisibility) && (b.params.watchSlidesProgress = !0), b.params.touchReleaseOnEdges && (b.params.resistanceRatio = 0), ["cube", "coverflow", "flip"].indexOf(b.params.effect) >= 0 && (b.support.transforms3d ? (b.params.watchSlidesProgress = !0, b.classNames.push(b.params.containerModifierClass + "3d")) : b.params.effect = "slide"), "slide" !== b.params.effect && b.classNames.push(b.params.containerModifierClass + b.params.effect), "cube" === b.params.effect && (b.params.resistanceRatio = 0, b.params.slidesPerView = 1, b.params.slidesPerColumn = 1, b.params.slidesPerGroup = 1, b.params.centeredSlides = !1, b.params.spaceBetween = 0, b.params.virtualTranslate = !0), "fade" !== b.params.effect && "flip" !== b.params.effect || (b.params.slidesPerView = 1, b.params.slidesPerColumn = 1, b.params.slidesPerGroup = 1, b.params.watchSlidesProgress = !0, b.params.spaceBetween = 0, void 0 === g && (b.params.virtualTranslate = !0)), b.params.grabCursor && b.support.touch && (b.params.grabCursor = !1), b.wrapper = b.container.children("." + b.params.wrapperClass), b.params.pagination && (b.paginationContainer = e(b.params.pagination), b.params.uniqueNavElements && "string" == typeof b.params.pagination && b.paginationContainer.length > 1 && 1 === b.container.find(b.params.pagination).length && (b.paginationContainer = b.container.find(b.params.pagination)), "bullets" === b.params.paginationType && b.params.paginationClickable ? b.paginationContainer.addClass(b.params.paginationModifierClass + "clickable") : b.params.paginationClickable = !1, b.paginationContainer.addClass(b.params.paginationModifierClass + b.params.paginationType)), (b.params.nextButton || b.params.prevButton) && (b.params.nextButton && (b.nextButton = e(b.params.nextButton), b.params.uniqueNavElements && "string" == typeof b.params.nextButton && b.nextButton.length > 1 && 1 === b.container.find(b.params.nextButton).length && (b.nextButton = b.container.find(b.params.nextButton))), b.params.prevButton && (b.prevButton = e(b.params.prevButton), b.params.uniqueNavElements && "string" == typeof b.params.prevButton && b.prevButton.length > 1 && 1 === b.container.find(b.params.prevButton).length && (b.prevButton = b.container.find(b.params.prevButton)))), b.isHorizontal = function() {
                return "horizontal" === b.params.direction
            }, b.rtl = b.isHorizontal() && ("rtl" === b.container[0].dir.toLowerCase() || "rtl" === b.container.css("direction")), b.rtl && b.classNames.push(b.params.containerModifierClass + "rtl"), b.rtl && (b.wrongRTL = "-webkit-box" === b.wrapper.css("display")), b.params.slidesPerColumn > 1 && b.classNames.push(b.params.containerModifierClass + "multirow"), b.device.android && b.classNames.push(b.params.containerModifierClass + "android"), b.container.addClass(b.classNames.join(" ")), b.translate = 0, b.progress = 0, b.velocity = 0, b.lockSwipeToNext = function() {
                b.params.allowSwipeToNext = !1, !1 === b.params.allowSwipeToPrev && b.params.grabCursor && b.unsetGrabCursor()
            }, b.lockSwipeToPrev = function() {
                b.params.allowSwipeToPrev = !1, !1 === b.params.allowSwipeToNext && b.params.grabCursor && b.unsetGrabCursor()
            }, b.lockSwipes = function() {
                b.params.allowSwipeToNext = b.params.allowSwipeToPrev = !1, b.params.grabCursor && b.unsetGrabCursor()
            }, b.unlockSwipeToNext = function() {
                b.params.allowSwipeToNext = !0, !0 === b.params.allowSwipeToPrev && b.params.grabCursor && b.setGrabCursor()
            }, b.unlockSwipeToPrev = function() {
                b.params.allowSwipeToPrev = !0, !0 === b.params.allowSwipeToNext && b.params.grabCursor && b.setGrabCursor()
            }, b.unlockSwipes = function() {
                b.params.allowSwipeToNext = b.params.allowSwipeToPrev = !0, b.params.grabCursor && b.setGrabCursor()
            }, b.setGrabCursor = function(e) {
                b.container[0].style.cursor = "move", b.container[0].style.cursor = e ? "-webkit-grabbing" : "-webkit-grab", b.container[0].style.cursor = e ? "-moz-grabbin" : "-moz-grab", b.container[0].style.cursor = e ? "grabbing" : "grab"
            }, b.unsetGrabCursor = function() {
                b.container[0].style.cursor = ""
            }, b.params.grabCursor && b.setGrabCursor(), b.imagesToLoad = [], b.imagesLoaded = 0, b.loadImage = function(e, a, t, i, s, r) {
                function n() {
                    r && r()
                }
                var o;
                e.complete && s ? n() : a ? (o = new window.Image, o.onload = n, o.onerror = n, i && (o.sizes = i), t && (o.srcset = t), a && (o.src = a)) : n()
            }, b.preloadImages = function() {
                function e() {
                    void 0 !== b && null !== b && b && (void 0 !== b.imagesLoaded && b.imagesLoaded++, b.imagesLoaded === b.imagesToLoad.length && (b.params.updateOnImagesReady && b.update(), b.emit("onImagesReady", b)))
                }
                b.imagesToLoad = b.container.find("img");
                for (var a = 0; a < b.imagesToLoad.length; a++) b.loadImage(b.imagesToLoad[a], b.imagesToLoad[a].currentSrc || b.imagesToLoad[a].getAttribute("src"), b.imagesToLoad[a].srcset || b.imagesToLoad[a].getAttribute("srcset"), b.imagesToLoad[a].sizes || b.imagesToLoad[a].getAttribute("sizes"), !0, e)
            }, b.autoplayTimeoutId = void 0, b.autoplaying = !1, b.autoplayPaused = !1, b.startAutoplay = function() {
                return void 0 === b.autoplayTimeoutId && !!b.params.autoplay && !b.autoplaying && (b.autoplaying = !0, b.emit("onAutoplayStart", b), void n())
            }, b.stopAutoplay = function(e) {
                b.autoplayTimeoutId && (b.autoplayTimeoutId && clearTimeout(b.autoplayTimeoutId), b.autoplaying = !1, b.autoplayTimeoutId = void 0, b.emit("onAutoplayStop", b))
            }, b.pauseAutoplay = function(e) {
                b.autoplayPaused || (b.autoplayTimeoutId && clearTimeout(b.autoplayTimeoutId), b.autoplayPaused = !0, 0 === e ? (b.autoplayPaused = !1, n()) : b.wrapper.transitionEnd(function() {
                    b && (b.autoplayPaused = !1, b.autoplaying ? n() : b.stopAutoplay())
                }))
            }, b.minTranslate = function() {
                return -b.snapGrid[0]
            }, b.maxTranslate = function() {
                return -b.snapGrid[b.snapGrid.length - 1]
            }, b.updateAutoHeight = function() {
                var e, a = [],
                    t = 0;
                if ("auto" !== b.params.slidesPerView && b.params.slidesPerView > 1)
                    for (e = 0; e < Math.ceil(b.params.slidesPerView); e++) {
                        var i = b.activeIndex + e;
                        if (i > b.slides.length) break;
                        a.push(b.slides.eq(i)[0])
                    } else a.push(b.slides.eq(b.activeIndex)[0]);
                for (e = 0; e < a.length; e++)
                    if (void 0 !== a[e]) {
                        var s = a[e].offsetHeight;
                        t = s > t ? s : t
                    }
                t && b.wrapper.css("height", t + "px")
            }, b.updateContainerSize = function() {
                var e, a;
                e = void 0 !== b.params.width ? b.params.width : b.container[0].clientWidth, a = void 0 !== b.params.height ? b.params.height : b.container[0].clientHeight, 0 === e && b.isHorizontal() || 0 === a && !b.isHorizontal() || (e = e - parseInt(b.container.css("padding-left"), 10) - parseInt(b.container.css("padding-right"), 10), a = a - parseInt(b.container.css("padding-top"), 10) - parseInt(b.container.css("padding-bottom"), 10), b.width = e, b.height = a, b.size = b.isHorizontal() ? b.width : b.height)
            }, b.updateSlidesSize = function() {
                b.slides = b.wrapper.children("." + b.params.slideClass), b.snapGrid = [], b.slidesGrid = [], b.slidesSizesGrid = [];
                var e, a = b.params.spaceBetween,
                    t = -b.params.slidesOffsetBefore,
                    i = 0,
                    s = 0;
                if (void 0 !== b.size) {
                    "string" == typeof a && a.indexOf("%") >= 0 && (a = parseFloat(a.replace("%", "")) / 100 * b.size), b.virtualSize = -a, b.rtl ? b.slides.css({
                        marginLeft: "",
                        marginTop: ""
                    }) : b.slides.css({
                        marginRight: "",
                        marginBottom: ""
                    });
                    var n;
                    b.params.slidesPerColumn > 1 && (n = Math.floor(b.slides.length / b.params.slidesPerColumn) === b.slides.length / b.params.slidesPerColumn ? b.slides.length : Math.ceil(b.slides.length / b.params.slidesPerColumn) * b.params.slidesPerColumn, "auto" !== b.params.slidesPerView && "row" === b.params.slidesPerColumnFill && (n = Math.max(n, b.params.slidesPerView * b.params.slidesPerColumn)));
                    var o, l = b.params.slidesPerColumn,
                        p = n / l,
                        d = p - (b.params.slidesPerColumn * p - b.slides.length);
                    for (e = 0; e < b.slides.length; e++) {
                        o = 0;
                        var u = b.slides.eq(e);
                        if (b.params.slidesPerColumn > 1) {
                            var c, m, h;
                            "column" === b.params.slidesPerColumnFill ? (m = Math.floor(e / l), h = e - m * l, (m > d || m === d && h === l - 1) && ++h >= l && (h = 0, m++), c = m + h * n / l, u.css({
                                "-webkit-box-ordinal-group": c,
                                "-moz-box-ordinal-group": c,
                                "-ms-flex-order": c,
                                "-webkit-order": c,
                                order: c
                            })) : (h = Math.floor(e / p), m = e - h * p), u.css("margin-" + (b.isHorizontal() ? "top" : "left"), 0 !== h && b.params.spaceBetween && b.params.spaceBetween + "px").attr("data-swiper-column", m).attr("data-swiper-row", h)
                        }
                        "none" !== u.css("display") && ("auto" === b.params.slidesPerView ? (o = b.isHorizontal() ? u.outerWidth(!0) : u.outerHeight(!0), b.params.roundLengths && (o = r(o))) : (o = (b.size - (b.params.slidesPerView - 1) * a) / b.params.slidesPerView, b.params.roundLengths && (o = r(o)), b.isHorizontal() ? b.slides[e].style.width = o + "px" : b.slides[e].style.height = o + "px"), b.slides[e].swiperSlideSize = o, b.slidesSizesGrid.push(o), b.params.centeredSlides ? (t = t + o / 2 + i / 2 + a, 0 === i && 0 !== e && (t = t - b.size / 2 - a), 0 === e && (t = t - b.size / 2 - a), Math.abs(t) < .001 && (t = 0), s % b.params.slidesPerGroup == 0 && b.snapGrid.push(t), b.slidesGrid.push(t)) : (s % b.params.slidesPerGroup == 0 && b.snapGrid.push(t), b.slidesGrid.push(t), t = t + o + a), b.virtualSize += o + a, i = o, s++)
                    }
                    b.virtualSize = Math.max(b.virtualSize, b.size) + b.params.slidesOffsetAfter;
                    var g;
                    if (b.rtl && b.wrongRTL && ("slide" === b.params.effect || "coverflow" === b.params.effect) && b.wrapper.css({
                        width: b.virtualSize + b.params.spaceBetween + "px"
                    }), b.support.flexbox && !b.params.setWrapperSize || (b.isHorizontal() ? b.wrapper.css({
                        width: b.virtualSize + b.params.spaceBetween + "px"
                    }) : b.wrapper.css({
                        height: b.virtualSize + b.params.spaceBetween + "px"
                    })), b.params.slidesPerColumn > 1 && (b.virtualSize = (o + b.params.spaceBetween) * n, b.virtualSize = Math.ceil(b.virtualSize / b.params.slidesPerColumn) - b.params.spaceBetween, b.isHorizontal() ? b.wrapper.css({
                        width: b.virtualSize + b.params.spaceBetween + "px"
                    }) : b.wrapper.css({
                        height: b.virtualSize + b.params.spaceBetween + "px"
                    }), b.params.centeredSlides)) {
                        for (g = [], e = 0; e < b.snapGrid.length; e++) b.snapGrid[e] < b.virtualSize + b.snapGrid[0] && g.push(b.snapGrid[e]);
                        b.snapGrid = g
                    }
                    if (!b.params.centeredSlides) {
                        for (g = [], e = 0; e < b.snapGrid.length; e++) b.snapGrid[e] <= b.virtualSize - b.size && g.push(b.snapGrid[e]);
                        b.snapGrid = g, Math.floor(b.virtualSize - b.size) - Math.floor(b.snapGrid[b.snapGrid.length - 1]) > 1 && b.snapGrid.push(b.virtualSize - b.size)
                    }
                    0 === b.snapGrid.length && (b.snapGrid = [0]), 0 !== b.params.spaceBetween && (b.isHorizontal() ? b.rtl ? b.slides.css({
                        marginLeft: a + "px"
                    }) : b.slides.css({
                        marginRight: a + "px"
                    }) : b.slides.css({
                        marginBottom: a + "px"
                    })), b.params.watchSlidesProgress && b.updateSlidesOffset()
                }
            }, b.updateSlidesOffset = function() {
                for (var e = 0; e < b.slides.length; e++) b.slides[e].swiperSlideOffset = b.isHorizontal() ? b.slides[e].offsetLeft : b.slides[e].offsetTop
            }, b.currentSlidesPerView = function() {
                var e, a, t = 1;
                if (b.params.centeredSlides) {
                    var i, s = b.slides[b.activeIndex].swiperSlideSize;
                    for (e = b.activeIndex + 1; e < b.slides.length; e++) b.slides[e] && !i && (s += b.slides[e].swiperSlideSize, t++, s > b.size && (i = !0));
                    for (a = b.activeIndex - 1; a >= 0; a--) b.slides[a] && !i && (s += b.slides[a].swiperSlideSize, t++, s > b.size && (i = !0))
                } else
                    for (e = b.activeIndex + 1; e < b.slides.length; e++) b.slidesGrid[e] - b.slidesGrid[b.activeIndex] < b.size && t++;
                return t
            }, b.updateSlidesProgress = function(e) {
                if (void 0 === e && (e = b.translate || 0), 0 !== b.slides.length) {
                    void 0 === b.slides[0].swiperSlideOffset && b.updateSlidesOffset();
                    var a = -e;
                    b.rtl && (a = e), b.slides.removeClass(b.params.slideVisibleClass);
                    for (var t = 0; t < b.slides.length; t++) {
                        var i = b.slides[t],
                            s = (a + (b.params.centeredSlides ? b.minTranslate() : 0) - i.swiperSlideOffset) / (i.swiperSlideSize + b.params.spaceBetween);
                        if (b.params.watchSlidesVisibility) {
                            var r = -(a - i.swiperSlideOffset),
                                n = r + b.slidesSizesGrid[t];
                            (r >= 0 && r < b.size || n > 0 && n <= b.size || r <= 0 && n >= b.size) && b.slides.eq(t).addClass(b.params.slideVisibleClass)
                        }
                        i.progress = b.rtl ? -s : s
                    }
                }
            }, b.updateProgress = function(e) {
                void 0 === e && (e = b.translate || 0);
                var a = b.maxTranslate() - b.minTranslate(),
                    t = b.isBeginning,
                    i = b.isEnd;
                0 === a ? (b.progress = 0, b.isBeginning = b.isEnd = !0) : (b.progress = (e - b.minTranslate()) / a, b.isBeginning = b.progress <= 0, b.isEnd = b.progress >= 1), b.isBeginning && !t && b.emit("onReachBeginning", b), b.isEnd && !i && b.emit("onReachEnd", b), b.params.watchSlidesProgress && b.updateSlidesProgress(e), b.emit("onProgress", b, b.progress)
            }, b.updateActiveIndex = function() {
                var e, a, t, i = b.rtl ? b.translate : -b.translate;
                for (a = 0; a < b.slidesGrid.length; a++) void 0 !== b.slidesGrid[a + 1] ? i >= b.slidesGrid[a] && i < b.slidesGrid[a + 1] - (b.slidesGrid[a + 1] - b.slidesGrid[a]) / 2 ? e = a : i >= b.slidesGrid[a] && i < b.slidesGrid[a + 1] && (e = a + 1) : i >= b.slidesGrid[a] && (e = a);
                b.params.normalizeSlideIndex && (e < 0 || void 0 === e) && (e = 0), t = Math.floor(e / b.params.slidesPerGroup), t >= b.snapGrid.length && (t = b.snapGrid.length - 1), e !== b.activeIndex && (b.snapIndex = t, b.previousIndex = b.activeIndex, b.activeIndex = e, b.updateClasses(), b.updateRealIndex())
            }, b.updateRealIndex = function() {
                b.realIndex = parseInt(b.slides.eq(b.activeIndex).attr("data-swiper-slide-index") || b.activeIndex, 10)
            }, b.updateClasses = function() {
                b.slides.removeClass(b.params.slideActiveClass + " " + b.params.slideNextClass + " " + b.params.slidePrevClass + " " + b.params.slideDuplicateActiveClass + " " + b.params.slideDuplicateNextClass + " " + b.params.slideDuplicatePrevClass);
                var a = b.slides.eq(b.activeIndex);
                a.addClass(b.params.slideActiveClass), s.loop && (a.hasClass(b.params.slideDuplicateClass) ? b.wrapper.children("." + b.params.slideClass + ":not(." + b.params.slideDuplicateClass + ')[data-swiper-slide-index="' + b.realIndex + '"]').addClass(b.params.slideDuplicateActiveClass) : b.wrapper.children("." + b.params.slideClass + "." + b.params.slideDuplicateClass + '[data-swiper-slide-index="' + b.realIndex + '"]').addClass(b.params.slideDuplicateActiveClass));
                var t = a.next("." + b.params.slideClass).addClass(b.params.slideNextClass);
                b.params.loop && 0 === t.length && (t = b.slides.eq(0), t.addClass(b.params.slideNextClass));
                var i = a.prev("." + b.params.slideClass).addClass(b.params.slidePrevClass);
                if (b.params.loop && 0 === i.length && (i = b.slides.eq(-1), i.addClass(b.params.slidePrevClass)), s.loop && (t.hasClass(b.params.slideDuplicateClass) ? b.wrapper.children("." + b.params.slideClass + ":not(." + b.params.slideDuplicateClass + ')[data-swiper-slide-index="' + t.attr("data-swiper-slide-index") + '"]').addClass(b.params.slideDuplicateNextClass) : b.wrapper.children("." + b.params.slideClass + "." + b.params.slideDuplicateClass + '[data-swiper-slide-index="' + t.attr("data-swiper-slide-index") + '"]').addClass(b.params.slideDuplicateNextClass), i.hasClass(b.params.slideDuplicateClass) ? b.wrapper.children("." + b.params.slideClass + ":not(." + b.params.slideDuplicateClass + ')[data-swiper-slide-index="' + i.attr("data-swiper-slide-index") + '"]').addClass(b.params.slideDuplicatePrevClass) : b.wrapper.children("." + b.params.slideClass + "." + b.params.slideDuplicateClass + '[data-swiper-slide-index="' + i.attr("data-swiper-slide-index") + '"]').addClass(b.params.slideDuplicatePrevClass)), b.paginationContainer && b.paginationContainer.length > 0) {
                    var r, n = b.params.loop ? Math.ceil((b.slides.length - 2 * b.loopedSlides) / b.params.slidesPerGroup) : b.snapGrid.length;
                    if (b.params.loop ? (r = Math.ceil((b.activeIndex - b.loopedSlides) / b.params.slidesPerGroup), r > b.slides.length - 1 - 2 * b.loopedSlides && (r -= b.slides.length - 2 * b.loopedSlides), r > n - 1 && (r -= n), r < 0 && "bullets" !== b.params.paginationType && (r = n + r)) : r = void 0 !== b.snapIndex ? b.snapIndex : b.activeIndex || 0, "bullets" === b.params.paginationType && b.bullets && b.bullets.length > 0 && (b.bullets.removeClass(b.params.bulletActiveClass), b.paginationContainer.length > 1 ? b.bullets.each(function() {
                        e(this).index() === r && e(this).addClass(b.params.bulletActiveClass)
                    }) : b.bullets.eq(r).addClass(b.params.bulletActiveClass)), "fraction" === b.params.paginationType && (b.paginationContainer.find("." + b.params.paginationCurrentClass).text(r + 1), b.paginationContainer.find("." + b.params.paginationTotalClass).text(n)), "progress" === b.params.paginationType) {
                        var o = (r + 1) / n,
                            l = o,
                            p = 1;
                        b.isHorizontal() || (p = o, l = 1), b.paginationContainer.find("." + b.params.paginationProgressbarClass).transform("translate3d(0,0,0) scaleX(" + l + ") scaleY(" + p + ")").transition(b.params.speed)
                    }
                    "custom" === b.params.paginationType && b.params.paginationCustomRender && (b.paginationContainer.html(b.params.paginationCustomRender(b, r + 1, n)), b.emit("onPaginationRendered", b, b.paginationContainer[0]))
                }
                b.params.loop || (b.params.prevButton && b.prevButton && b.prevButton.length > 0 && (b.isBeginning ? (b.prevButton.addClass(b.params.buttonDisabledClass), b.params.a11y && b.a11y && b.a11y.disable(b.prevButton)) : (b.prevButton.removeClass(b.params.buttonDisabledClass), b.params.a11y && b.a11y && b.a11y.enable(b.prevButton))), b.params.nextButton && b.nextButton && b.nextButton.length > 0 && (b.isEnd ? (b.nextButton.addClass(b.params.buttonDisabledClass), b.params.a11y && b.a11y && b.a11y.disable(b.nextButton)) : (b.nextButton.removeClass(b.params.buttonDisabledClass), b.params.a11y && b.a11y && b.a11y.enable(b.nextButton))))
            }, b.updatePagination = function() {
                if (b.params.pagination && b.paginationContainer && b.paginationContainer.length > 0) {
                    var e = "";
                    if ("bullets" === b.params.paginationType) {
                        for (var a = b.params.loop ? Math.ceil((b.slides.length - 2 * b.loopedSlides) / b.params.slidesPerGroup) : b.snapGrid.length, t = 0; t < a; t++) e += b.params.paginationBulletRender ? b.params.paginationBulletRender(b, t, b.params.bulletClass) : "<" + b.params.paginationElement + ' class="' + b.params.bulletClass + '"></' + b.params.paginationElement + ">";
                        b.paginationContainer.html(e), b.bullets = b.paginationContainer.find("." + b.params.bulletClass), b.params.paginationClickable && b.params.a11y && b.a11y && b.a11y.initPagination()
                    }
                    "fraction" === b.params.paginationType && (e = b.params.paginationFractionRender ? b.params.paginationFractionRender(b, b.params.paginationCurrentClass, b.params.paginationTotalClass) : '<span class="' + b.params.paginationCurrentClass + '"></span> / <span class="' + b.params.paginationTotalClass + '"></span>', b.paginationContainer.html(e)), "progress" === b.params.paginationType && (e = b.params.paginationProgressRender ? b.params.paginationProgressRender(b, b.params.paginationProgressbarClass) : '<span class="' + b.params.paginationProgressbarClass + '"></span>', b.paginationContainer.html(e)), "custom" !== b.params.paginationType && b.emit("onPaginationRendered", b, b.paginationContainer[0])
                }
            }, b.update = function(e) {
                function a() {
                    b.rtl, b.translate, t = Math.min(Math.max(b.translate, b.maxTranslate()), b.minTranslate()), b.setWrapperTranslate(t), b.updateActiveIndex(), b.updateClasses()
                }
                if (b) {
                    b.updateContainerSize(), b.updateSlidesSize(), b.updateProgress(), b.updatePagination(), b.updateClasses(), b.params.scrollbar && b.scrollbar && b.scrollbar.set();
                    var t;
                    e ? (b.controller && b.controller.spline && (b.controller.spline = void 0), b.params.freeMode ? (a(), b.params.autoHeight && b.updateAutoHeight()) : (("auto" === b.params.slidesPerView || b.params.slidesPerView > 1) && b.isEnd && !b.params.centeredSlides ? b.slideTo(b.slides.length - 1, 0, !1, !0) : b.slideTo(b.activeIndex, 0, !1, !0)) || a()) : b.params.autoHeight && b.updateAutoHeight()
                }
            }, b.onResize = function(e) {
                b.params.onBeforeResize && b.params.onBeforeResize(b), b.params.breakpoints && b.setBreakpoint();
                var a = b.params.allowSwipeToPrev,
                    t = b.params.allowSwipeToNext;
                b.params.allowSwipeToPrev = b.params.allowSwipeToNext = !0, b.updateContainerSize(), b.updateSlidesSize(), ("auto" === b.params.slidesPerView || b.params.freeMode || e) && b.updatePagination(), b.params.scrollbar && b.scrollbar && b.scrollbar.set(), b.controller && b.controller.spline && (b.controller.spline = void 0);
                var i = !1;
                if (b.params.freeMode) {
                    var s = Math.min(Math.max(b.translate, b.maxTranslate()), b.minTranslate());
                    b.setWrapperTranslate(s), b.updateActiveIndex(), b.updateClasses(), b.params.autoHeight && b.updateAutoHeight()
                } else b.updateClasses(), i = ("auto" === b.params.slidesPerView || b.params.slidesPerView > 1) && b.isEnd && !b.params.centeredSlides ? b.slideTo(b.slides.length - 1, 0, !1, !0) : b.slideTo(b.activeIndex, 0, !1, !0);
                b.params.lazyLoading && !i && b.lazy && b.lazy.load(), b.params.allowSwipeToPrev = a, b.params.allowSwipeToNext = t, b.params.onAfterResize && b.params.onAfterResize(b)
            }, b.touchEventsDesktop = {
                start: "mousedown",
                move: "mousemove",
                end: "mouseup"
            }, window.navigator.pointerEnabled ? b.touchEventsDesktop = {
                start: "pointerdown",
                move: "pointermove",
                end: "pointerup"
            } : window.navigator.msPointerEnabled && (b.touchEventsDesktop = {
                start: "MSPointerDown",
                move: "MSPointerMove",
                end: "MSPointerUp"
            }), b.touchEvents = {
                start: b.support.touch || !b.params.simulateTouch ? "touchstart" : b.touchEventsDesktop.start,
                move: b.support.touch || !b.params.simulateTouch ? "touchmove" : b.touchEventsDesktop.move,
                end: b.support.touch || !b.params.simulateTouch ? "touchend" : b.touchEventsDesktop.end
            }, (window.navigator.pointerEnabled || window.navigator.msPointerEnabled) && ("container" === b.params.touchEventsTarget ? b.container : b.wrapper).addClass("swiper-wp8-" + b.params.direction), b.initEvents = function(e) {
                var a = e ? "off" : "on",
                    t = e ? "removeEventListener" : "addEventListener",
                    i = "container" === b.params.touchEventsTarget ? b.container[0] : b.wrapper[0],
                    r = b.support.touch ? i : document,
                    n = !!b.params.nested;
                if (b.browser.ie) i[t](b.touchEvents.start, b.onTouchStart, !1), r[t](b.touchEvents.move, b.onTouchMove, n), r[t](b.touchEvents.end, b.onTouchEnd, !1);
                else {
                    if (b.support.touch) {
                        var o = !("touchstart" !== b.touchEvents.start || !b.support.passiveListener || !b.params.passiveListeners) && {
                            passive: !0,
                            capture: !1
                        };
                        i[t](b.touchEvents.start, b.onTouchStart, o), i[t](b.touchEvents.move, b.onTouchMove, n), i[t](b.touchEvents.end, b.onTouchEnd, o)
                    }(s.simulateTouch && !b.device.ios && !b.device.android || s.simulateTouch && !b.support.touch && b.device.ios) && (i[t]("mousedown", b.onTouchStart, !1), document[t]("mousemove", b.onTouchMove, n), document[t]("mouseup", b.onTouchEnd, !1))
                }
                window[t]("resize", b.onResize), b.params.nextButton && b.nextButton && b.nextButton.length > 0 && (b.nextButton[a]("click", b.onClickNext), b.params.a11y && b.a11y && b.nextButton[a]("keydown", b.a11y.onEnterKey)), b.params.prevButton && b.prevButton && b.prevButton.length > 0 && (b.prevButton[a]("click", b.onClickPrev), b.params.a11y && b.a11y && b.prevButton[a]("keydown", b.a11y.onEnterKey)), b.params.pagination && b.params.paginationClickable && (b.paginationContainer[a]("click", "." + b.params.bulletClass, b.onClickIndex), b.params.a11y && b.a11y && b.paginationContainer[a]("keydown", "." + b.params.bulletClass, b.a11y.onEnterKey)), (b.params.preventClicks || b.params.preventClicksPropagation) && i[t]("click", b.preventClicks, !0)
            }, b.attachEvents = function() {
                b.initEvents()
            }, b.detachEvents = function() {
                b.initEvents(!0)
            }, b.allowClick = !0, b.preventClicks = function(e) {
                b.allowClick || (b.params.preventClicks && e.preventDefault(), b.params.preventClicksPropagation && b.animating && (e.stopPropagation(), e.stopImmediatePropagation()))
            }, b.onClickNext = function(e) {
                e.preventDefault(), b.isEnd && !b.params.loop || b.slideNext()
            }, b.onClickPrev = function(e) {
                e.preventDefault(), b.isBeginning && !b.params.loop || b.slidePrev()
            }, b.onClickIndex = function(a) {
                a.preventDefault();
                var t = e(this).index() * b.params.slidesPerGroup;
                b.params.loop && (t += b.loopedSlides), b.slideTo(t)
            }, b.updateClickedSlide = function(a) {
                var t = o(a, "." + b.params.slideClass),
                    i = !1;
                if (t)
                    for (var s = 0; s < b.slides.length; s++) b.slides[s] === t && (i = !0);
                if (!t || !i) return b.clickedSlide = void 0, void(b.clickedIndex = void 0);
                if (b.clickedSlide = t, b.clickedIndex = e(t).index(), b.params.slideToClickedSlide && void 0 !== b.clickedIndex && b.clickedIndex !== b.activeIndex) {
                    var r, n = b.clickedIndex,
                        l = "auto" === b.params.slidesPerView ? b.currentSlidesPerView() : b.params.slidesPerView;
                    if (b.params.loop) {
                        if (b.animating) return;
                        r = parseInt(e(b.clickedSlide).attr("data-swiper-slide-index"), 10), b.params.centeredSlides ? n < b.loopedSlides - l / 2 || n > b.slides.length - b.loopedSlides + l / 2 ? (b.fixLoop(), n = b.wrapper.children("." + b.params.slideClass + '[data-swiper-slide-index="' + r + '"]:not(.' + b.params.slideDuplicateClass + ")").eq(0).index(), setTimeout(function() {
                            b.slideTo(n)
                        }, 0)) : b.slideTo(n) : n > b.slides.length - l ? (b.fixLoop(), n = b.wrapper.children("." + b.params.slideClass + '[data-swiper-slide-index="' + r + '"]:not(.' + b.params.slideDuplicateClass + ")").eq(0).index(), setTimeout(function() {
                            b.slideTo(n)
                        }, 0)) : b.slideTo(n)
                    } else b.slideTo(n)
                }
            };
            var S, C, z, M, E, P, k, I, L, D, B = "input, select, textarea, button, video",
                H = Date.now(),
                A = [];
            b.animating = !1, b.touches = {
                startX: 0,
                startY: 0,
                currentX: 0,
                currentY: 0,
                diff: 0
            };
            var G, N;
            b.onTouchStart = function(a) {
                if (a.originalEvent && (a = a.originalEvent), (G = "touchstart" === a.type) || !("which" in a) || 3 !== a.which) {
                    if (b.params.noSwiping && o(a, "." + b.params.noSwipingClass)) return void(b.allowClick = !0);
                    if (!b.params.swipeHandler || o(a, b.params.swipeHandler)) {
                        var t = b.touches.currentX = "touchstart" === a.type ? a.targetTouches[0].pageX : a.pageX,
                            i = b.touches.currentY = "touchstart" === a.type ? a.targetTouches[0].pageY : a.pageY;
                        if (!(b.device.ios && b.params.iOSEdgeSwipeDetection && t <= b.params.iOSEdgeSwipeThreshold)) {
                            if (S = !0, C = !1, z = !0, E = void 0, N = void 0, b.touches.startX = t, b.touches.startY = i, M = Date.now(), b.allowClick = !0, b.updateContainerSize(), b.swipeDirection = void 0, b.params.threshold > 0 && (I = !1), "touchstart" !== a.type) {
                                var s = !0;
                                e(a.target).is(B) && (s = !1), document.activeElement && e(document.activeElement).is(B) && document.activeElement.blur(), s && a.preventDefault()
                            }
                            b.emit("onTouchStart", b, a)
                        }
                    }
                }
            }, b.onTouchMove = function(a) {
                if (a.originalEvent && (a = a.originalEvent), !G || "mousemove" !== a.type) {
                    if (a.preventedByNestedSwiper) return b.touches.startX = "touchmove" === a.type ? a.targetTouches[0].pageX : a.pageX, void(b.touches.startY = "touchmove" === a.type ? a.targetTouches[0].pageY : a.pageY);
                    if (b.params.onlyExternal) return b.allowClick = !1, void(S && (b.touches.startX = b.touches.currentX = "touchmove" === a.type ? a.targetTouches[0].pageX : a.pageX, b.touches.startY = b.touches.currentY = "touchmove" === a.type ? a.targetTouches[0].pageY : a.pageY, M = Date.now()));
                    if (G && b.params.touchReleaseOnEdges && !b.params.loop)
                        if (b.isHorizontal()) {
                            if (b.touches.currentX < b.touches.startX && b.translate <= b.maxTranslate() || b.touches.currentX > b.touches.startX && b.translate >= b.minTranslate()) return
                        } else if (b.touches.currentY < b.touches.startY && b.translate <= b.maxTranslate() || b.touches.currentY > b.touches.startY && b.translate >= b.minTranslate()) return;
                    if (G && document.activeElement && a.target === document.activeElement && e(a.target).is(B)) return C = !0, void(b.allowClick = !1);
                    if (z && b.emit("onTouchMove", b, a), !(a.targetTouches && a.targetTouches.length > 1)) {
                        if (b.touches.currentX = "touchmove" === a.type ? a.targetTouches[0].pageX : a.pageX, b.touches.currentY = "touchmove" === a.type ? a.targetTouches[0].pageY : a.pageY, void 0 === E) {
                            var t;
                            b.isHorizontal() && b.touches.currentY === b.touches.startY || !b.isHorizontal() && b.touches.currentX === b.touches.startX ? E = !1 : (t = 180 * Math.atan2(Math.abs(b.touches.currentY - b.touches.startY), Math.abs(b.touches.currentX - b.touches.startX)) / Math.PI, E = b.isHorizontal() ? t > b.params.touchAngle : 90 - t > b.params.touchAngle)
                        }
                        if (E && b.emit("onTouchMoveOpposite", b, a), void 0 === N && (b.touches.currentX === b.touches.startX && b.touches.currentY === b.touches.startY || (N = !0)), S) {
                            if (E) return void(S = !1);
                            if (N) {
                                b.allowClick = !1, b.emit("onSliderMove", b, a), a.preventDefault(), b.params.touchMoveStopPropagation && !b.params.nested && a.stopPropagation(), C || (s.loop && b.fixLoop(), k = b.getWrapperTranslate(), b.setWrapperTransition(0), b.animating && b.wrapper.trigger("webkitTransitionEnd transitionend oTransitionEnd MSTransitionEnd msTransitionEnd"), b.params.autoplay && b.autoplaying && (b.params.autoplayDisableOnInteraction ? b.stopAutoplay() : b.pauseAutoplay()), D = !1, !b.params.grabCursor || !0 !== b.params.allowSwipeToNext && !0 !== b.params.allowSwipeToPrev || b.setGrabCursor(!0)), C = !0;
                                var i = b.touches.diff = b.isHorizontal() ? b.touches.currentX - b.touches.startX : b.touches.currentY - b.touches.startY;
                                i *= b.params.touchRatio, b.rtl && (i = -i), b.swipeDirection = i > 0 ? "prev" : "next", P = i + k;
                                var r = !0;
                                if (i > 0 && P > b.minTranslate() ? (r = !1, b.params.resistance && (P = b.minTranslate() - 1 + Math.pow(-b.minTranslate() + k + i, b.params.resistanceRatio))) : i < 0 && P < b.maxTranslate() && (r = !1, b.params.resistance && (P = b.maxTranslate() + 1 - Math.pow(b.maxTranslate() - k - i, b.params.resistanceRatio))), r && (a.preventedByNestedSwiper = !0), !b.params.allowSwipeToNext && "next" === b.swipeDirection && P < k && (P = k), !b.params.allowSwipeToPrev && "prev" === b.swipeDirection && P > k && (P = k), b.params.threshold > 0) {
                                    if (!(Math.abs(i) > b.params.threshold || I)) return void(P = k);
                                    if (!I) return I = !0, b.touches.startX = b.touches.currentX, b.touches.startY = b.touches.currentY, P = k, void(b.touches.diff = b.isHorizontal() ? b.touches.currentX - b.touches.startX : b.touches.currentY - b.touches.startY)
                                }
                                b.params.followFinger && ((b.params.freeMode || b.params.watchSlidesProgress) && b.updateActiveIndex(), b.params.freeMode && (0 === A.length && A.push({
                                    position: b.touches[b.isHorizontal() ? "startX" : "startY"],
                                    time: M
                                }), A.push({
                                    position: b.touches[b.isHorizontal() ? "currentX" : "currentY"],
                                    time: (new window.Date).getTime()
                                })), b.updateProgress(P), b.setWrapperTranslate(P))
                            }
                        }
                    }
                }
            }, b.onTouchEnd = function(a) {
                if (a.originalEvent && (a = a.originalEvent), z && b.emit("onTouchEnd", b, a), z = !1, S) {
                    b.params.grabCursor && C && S && (!0 === b.params.allowSwipeToNext || !0 === b.params.allowSwipeToPrev) && b.setGrabCursor(!1);
                    var t = Date.now(),
                        i = t - M;
                    if (b.allowClick && (b.updateClickedSlide(a), b.emit("onTap", b, a), i < 300 && t - H > 300 && (L && clearTimeout(L), L = setTimeout(function() {
                        b && (b.params.paginationHide && b.paginationContainer.length > 0 && !e(a.target).hasClass(b.params.bulletClass) && b.paginationContainer.toggleClass(b.params.paginationHiddenClass), b.emit("onClick", b, a))
                    }, 300)), i < 300 && t - H < 300 && (L && clearTimeout(L), b.emit("onDoubleTap", b, a))), H = Date.now(), setTimeout(function() {
                        b && (b.allowClick = !0)
                    }, 0), !S || !C || !b.swipeDirection || 0 === b.touches.diff || P === k) return void(S = C = !1);
                    S = C = !1;
                    var s;
                    if (s = b.params.followFinger ? b.rtl ? b.translate : -b.translate : -P, b.params.freeMode) {
                        if (s < -b.minTranslate()) return void b.slideTo(b.activeIndex);
                        if (s > -b.maxTranslate()) return void(b.slides.length < b.snapGrid.length ? b.slideTo(b.snapGrid.length - 1) : b.slideTo(b.slides.length - 1));
                        if (b.params.freeModeMomentum) {
                            if (A.length > 1) {
                                var r = A.pop(),
                                    n = A.pop(),
                                    o = r.position - n.position,
                                    l = r.time - n.time;
                                b.velocity = o / l, b.velocity = b.velocity / 2, Math.abs(b.velocity) < b.params.freeModeMinimumVelocity && (b.velocity = 0), (l > 150 || (new window.Date).getTime() - r.time > 300) && (b.velocity = 0)
                            } else b.velocity = 0;
                            b.velocity = b.velocity * b.params.freeModeMomentumVelocityRatio, A.length = 0;
                            var p = 1e3 * b.params.freeModeMomentumRatio,
                                d = b.velocity * p,
                                u = b.translate + d;
                            b.rtl && (u = -u);
                            var c, m = !1,
                                h = 20 * Math.abs(b.velocity) * b.params.freeModeMomentumBounceRatio;
                            if (u < b.maxTranslate()) b.params.freeModeMomentumBounce ? (u + b.maxTranslate() < -h && (u = b.maxTranslate() - h), c = b.maxTranslate(), m = !0, D = !0) : u = b.maxTranslate();
                            else if (u > b.minTranslate()) b.params.freeModeMomentumBounce ? (u - b.minTranslate() > h && (u = b.minTranslate() + h), c = b.minTranslate(), m = !0, D = !0) : u = b.minTranslate();
                            else if (b.params.freeModeSticky) {
                                var g, f = 0;
                                for (f = 0; f < b.snapGrid.length; f += 1)
                                    if (b.snapGrid[f] > -u) {
                                        g = f;
                                        break
                                    }
                                u = Math.abs(b.snapGrid[g] - u) < Math.abs(b.snapGrid[g - 1] - u) || "next" === b.swipeDirection ? b.snapGrid[g] : b.snapGrid[g - 1], b.rtl || (u = -u)
                            }
                            if (0 !== b.velocity) p = b.rtl ? Math.abs((-u - b.translate) / b.velocity) : Math.abs((u - b.translate) / b.velocity);
                            else if (b.params.freeModeSticky) return void b.slideReset();
                            b.params.freeModeMomentumBounce && m ? (b.updateProgress(c), b.setWrapperTransition(p), b.setWrapperTranslate(u), b.onTransitionStart(), b.animating = !0, b.wrapper.transitionEnd(function() {
                                b && D && (b.emit("onMomentumBounce", b), b.setWrapperTransition(b.params.speed), b.setWrapperTranslate(c), b.wrapper.transitionEnd(function() {
                                    b && b.onTransitionEnd()
                                }))
                            })) : b.velocity ? (b.updateProgress(u), b.setWrapperTransition(p), b.setWrapperTranslate(u), b.onTransitionStart(), b.animating || (b.animating = !0, b.wrapper.transitionEnd(function() {
                                b && b.onTransitionEnd()
                            }))) : b.updateProgress(u), b.updateActiveIndex()
                        }
                        return void((!b.params.freeModeMomentum || i >= b.params.longSwipesMs) && (b.updateProgress(), b.updateActiveIndex()))
                    }
                    var v, w = 0,
                        y = b.slidesSizesGrid[0];
                    for (v = 0; v < b.slidesGrid.length; v += b.params.slidesPerGroup) void 0 !== b.slidesGrid[v + b.params.slidesPerGroup] ? s >= b.slidesGrid[v] && s < b.slidesGrid[v + b.params.slidesPerGroup] && (w = v, y = b.slidesGrid[v + b.params.slidesPerGroup] - b.slidesGrid[v]) : s >= b.slidesGrid[v] && (w = v, y = b.slidesGrid[b.slidesGrid.length - 1] - b.slidesGrid[b.slidesGrid.length - 2]);
                    var x = (s - b.slidesGrid[w]) / y;
                    if (i > b.params.longSwipesMs) {
                        if (!b.params.longSwipes) return void b.slideTo(b.activeIndex);
                        "next" === b.swipeDirection && (x >= b.params.longSwipesRatio ? b.slideTo(w + b.params.slidesPerGroup) : b.slideTo(w)), "prev" === b.swipeDirection && (x > 1 - b.params.longSwipesRatio ? b.slideTo(w + b.params.slidesPerGroup) : b.slideTo(w))
                    } else {
                        if (!b.params.shortSwipes) return void b.slideTo(b.activeIndex);
                        "next" === b.swipeDirection && b.slideTo(w + b.params.slidesPerGroup), "prev" === b.swipeDirection && b.slideTo(w)
                    }
                }
            }, b._slideTo = function(e, a) {
                return b.slideTo(e, a, !0, !0)
            }, b.slideTo = function(e, a, t, i) {
                void 0 === t && (t = !0), void 0 === e && (e = 0), e < 0 && (e = 0), b.snapIndex = Math.floor(e / b.params.slidesPerGroup), b.snapIndex >= b.snapGrid.length && (b.snapIndex = b.snapGrid.length - 1);
                var s = -b.snapGrid[b.snapIndex];
                if (b.params.autoplay && b.autoplaying && (i || !b.params.autoplayDisableOnInteraction ? b.pauseAutoplay(a) : b.stopAutoplay()), b.updateProgress(s), b.params.normalizeSlideIndex)
                    for (var r = 0; r < b.slidesGrid.length; r++) - Math.floor(100 * s) >= Math.floor(100 * b.slidesGrid[r]) && (e = r);
                return !(!b.params.allowSwipeToNext && s < b.translate && s < b.minTranslate() || !b.params.allowSwipeToPrev && s > b.translate && s > b.maxTranslate() && (b.activeIndex || 0) !== e || (void 0 === a && (a = b.params.speed), b.previousIndex = b.activeIndex || 0, b.activeIndex = e, b.updateRealIndex(), b.rtl && -s === b.translate || !b.rtl && s === b.translate ? (b.params.autoHeight && b.updateAutoHeight(), b.updateClasses(), "slide" !== b.params.effect && b.setWrapperTranslate(s), 1) : (b.updateClasses(), b.onTransitionStart(t), 0 === a || b.browser.lteIE9 ? (b.setWrapperTranslate(s), b.setWrapperTransition(0), b.onTransitionEnd(t)) : (b.setWrapperTranslate(s), b.setWrapperTransition(a), b.animating || (b.animating = !0, b.wrapper.transitionEnd(function() {
                    b && b.onTransitionEnd(t)
                }))), 0)))
            }, b.onTransitionStart = function(e) {
                void 0 === e && (e = !0), b.params.autoHeight && b.updateAutoHeight(), b.lazy && b.lazy.onTransitionStart(), e && (b.emit("onTransitionStart", b), b.activeIndex !== b.previousIndex && (b.emit("onSlideChangeStart", b), b.activeIndex > b.previousIndex ? b.emit("onSlideNextStart", b) : b.emit("onSlidePrevStart", b)))
            }, b.onTransitionEnd = function(e) {
                b.animating = !1, b.setWrapperTransition(0), void 0 === e && (e = !0), b.lazy && b.lazy.onTransitionEnd(), e && (b.emit("onTransitionEnd", b), b.activeIndex !== b.previousIndex && (b.emit("onSlideChangeEnd", b), b.activeIndex > b.previousIndex ? b.emit("onSlideNextEnd", b) : b.emit("onSlidePrevEnd", b))), b.params.history && b.history && b.history.setHistory(b.params.history, b.activeIndex), b.params.hashnav && b.hashnav && b.hashnav.setHash()
            }, b.slideNext = function(e, a, t) {
                return b.params.loop ? !b.animating && (b.fixLoop(), b.container[0].clientLeft, b.slideTo(b.activeIndex + b.params.slidesPerGroup, a, e, t)) : b.slideTo(b.activeIndex + b.params.slidesPerGroup, a, e, t)
            }, b._slideNext = function(e) {
                return b.slideNext(!0, e, !0)
            }, b.slidePrev = function(e, a, t) {
                return b.params.loop ? !b.animating && (b.fixLoop(), b.container[0].clientLeft, b.slideTo(b.activeIndex - 1, a, e, t)) : b.slideTo(b.activeIndex - 1, a, e, t)
            }, b._slidePrev = function(e) {
                return b.slidePrev(!0, e, !0)
            }, b.slideReset = function(e, a, t) {
                return b.slideTo(b.activeIndex, a, e)
            }, b.disableTouchControl = function() {
                return b.params.onlyExternal = !0, !0
            }, b.enableTouchControl = function() {
                return b.params.onlyExternal = !1, !0
            }, b.setWrapperTransition = function(e, a) {
                b.wrapper.transition(e), "slide" !== b.params.effect && b.effects[b.params.effect] && b.effects[b.params.effect].setTransition(e), b.params.parallax && b.parallax && b.parallax.setTransition(e), b.params.scrollbar && b.scrollbar && b.scrollbar.setTransition(e), b.params.control && b.controller && b.controller.setTransition(e, a), b.emit("onSetTransition", b, e)
            }, b.setWrapperTranslate = function(e, a, t) {
                var i = 0,
                    s = 0;
                b.isHorizontal() ? i = b.rtl ? -e : e : s = e, b.params.roundLengths && (i = r(i), s = r(s)), b.params.virtualTranslate || (b.support.transforms3d ? b.wrapper.transform("translate3d(" + i + "px, " + s + "px, 0px)") : b.wrapper.transform("translate(" + i + "px, " + s + "px)")), b.translate = b.isHorizontal() ? i : s;
                var n, o = b.maxTranslate() - b.minTranslate();
                n = 0 === o ? 0 : (e - b.minTranslate()) / o, n !== b.progress && b.updateProgress(e), a && b.updateActiveIndex(), "slide" !== b.params.effect && b.effects[b.params.effect] && b.effects[b.params.effect].setTranslate(b.translate), b.params.parallax && b.parallax && b.parallax.setTranslate(b.translate), b.params.scrollbar && b.scrollbar && b.scrollbar.setTranslate(b.translate), b.params.control && b.controller && b.controller.setTranslate(b.translate, t), b.emit("onSetTranslate", b, b.translate)
            }, b.getTranslate = function(e, a) {
                var t, i, s, r;
                return void 0 === a && (a = "x"), b.params.virtualTranslate ? b.rtl ? -b.translate : b.translate : (s = window.getComputedStyle(e, null), window.WebKitCSSMatrix ? (i = s.transform || s.webkitTransform, i.split(",").length > 6 && (i = i.split(", ").map(function(e) {
                    return e.replace(",", ".")
                }).join(", ")), r = new window.WebKitCSSMatrix("none" === i ? "" : i)) : (r = s.MozTransform || s.OTransform || s.MsTransform || s.msTransform || s.transform || s.getPropertyValue("transform").replace("translate(", "matrix(1, 0, 0, 1,"), t = r.toString().split(",")), "x" === a && (i = window.WebKitCSSMatrix ? r.m41 : 16 === t.length ? parseFloat(t[12]) : parseFloat(t[4])), "y" === a && (i = window.WebKitCSSMatrix ? r.m42 : 16 === t.length ? parseFloat(t[13]) : parseFloat(t[5])), b.rtl && i && (i = -i), i || 0)
            }, b.getWrapperTranslate = function(e) {
                return void 0 === e && (e = b.isHorizontal() ? "x" : "y"), b.getTranslate(b.wrapper[0], e)
            }, b.observers = [], b.initObservers = function() {
                if (b.params.observeParents)
                    for (var e = b.container.parents(), a = 0; a < e.length; a++) l(e[a]);
                l(b.container[0], {
                    childList: !1
                }), l(b.wrapper[0], {
                    attributes: !1
                })
            }, b.disconnectObservers = function() {
                for (var e = 0; e < b.observers.length; e++) b.observers[e].disconnect();
                b.observers = []
            }, b.createLoop = function() {
                b.wrapper.children("." + b.params.slideClass + "." + b.params.slideDuplicateClass).remove();
                var a = b.wrapper.children("." + b.params.slideClass);
                "auto" !== b.params.slidesPerView || b.params.loopedSlides || (b.params.loopedSlides = a.length), b.loopedSlides = parseInt(b.params.loopedSlides || b.params.slidesPerView, 10), b.loopedSlides = b.loopedSlides + b.params.loopAdditionalSlides, b.loopedSlides > a.length && (b.loopedSlides = a.length);
                var t, i = [],
                    s = [];
                for (a.each(function(t, r) {
                    var n = e(this);
                    t < b.loopedSlides && s.push(r), t < a.length && t >= a.length - b.loopedSlides && i.push(r), n.attr("data-swiper-slide-index", t)
                }), t = 0; t < s.length; t++) b.wrapper.append(e(s[t].cloneNode(!0)).addClass(b.params.slideDuplicateClass));
                for (t = i.length - 1; t >= 0; t--) b.wrapper.prepend(e(i[t].cloneNode(!0)).addClass(b.params.slideDuplicateClass))
            }, b.destroyLoop = function() {
                b.wrapper.children("." + b.params.slideClass + "." + b.params.slideDuplicateClass).remove(), b.slides.removeAttr("data-swiper-slide-index")
            }, b.reLoop = function(e) {
                var a = b.activeIndex - b.loopedSlides;
                b.destroyLoop(), b.createLoop(), b.updateSlidesSize(), e && b.slideTo(a + b.loopedSlides, 0, !1)
            }, b.fixLoop = function() {
                var e;
                b.activeIndex < b.loopedSlides ? (e = b.slides.length - 3 * b.loopedSlides + b.activeIndex, e += b.loopedSlides, b.slideTo(e, 0, !1, !0)) : ("auto" === b.params.slidesPerView && b.activeIndex >= 2 * b.loopedSlides || b.activeIndex > b.slides.length - 2 * b.params.slidesPerView) && (e = -b.slides.length + b.activeIndex + b.loopedSlides, e += b.loopedSlides, b.slideTo(e, 0, !1, !0))
            }, b.appendSlide = function(e) {
                if (b.params.loop && b.destroyLoop(), "object" == typeof e && e.length)
                    for (var a = 0; a < e.length; a++) e[a] && b.wrapper.append(e[a]);
                else b.wrapper.append(e);
                b.params.loop && b.createLoop(), b.params.observer && b.support.observer || b.update(!0)
            }, b.prependSlide = function(e) {
                b.params.loop && b.destroyLoop();
                var a = b.activeIndex + 1;
                if ("object" == typeof e && e.length) {
                    for (var t = 0; t < e.length; t++) e[t] && b.wrapper.prepend(e[t]);
                    a = b.activeIndex + e.length
                } else b.wrapper.prepend(e);
                b.params.loop && b.createLoop(), b.params.observer && b.support.observer || b.update(!0), b.slideTo(a, 0, !1)
            }, b.removeSlide = function(e) {
                b.params.loop && (b.destroyLoop(), b.slides = b.wrapper.children("." + b.params.slideClass));
                var a, t = b.activeIndex;
                if ("object" == typeof e && e.length) {
                    for (var i = 0; i < e.length; i++) a = e[i], b.slides[a] && b.slides.eq(a).remove(), a < t && t--;
                    t = Math.max(t, 0)
                } else a = e, b.slides[a] && b.slides.eq(a).remove(), a < t && t--, t = Math.max(t, 0);
                b.params.loop && b.createLoop(), b.params.observer && b.support.observer || b.update(!0), b.params.loop ? b.slideTo(t + b.loopedSlides, 0, !1) : b.slideTo(t, 0, !1)
            }, b.removeAllSlides = function() {
                for (var e = [], a = 0; a < b.slides.length; a++) e.push(a);
                b.removeSlide(e)
            }, b.effects = {
                fade: {
                    setTranslate: function() {
                        for (var e = 0; e < b.slides.length; e++) {
                            var a = b.slides.eq(e),
                                t = a[0].swiperSlideOffset,
                                i = -t;
                            b.params.virtualTranslate || (i -= b.translate);
                            var s = 0;
                            b.isHorizontal() || (s = i, i = 0);
                            var r = b.params.fade.crossFade ? Math.max(1 - Math.abs(a[0].progress), 0) : 1 + Math.min(Math.max(a[0].progress, -1), 0);
                            a.css({
                                opacity: r
                            }).transform("translate3d(" + i + "px, " + s + "px, 0px)")
                        }
                    },
                    setTransition: function(e) {
                        if (b.slides.transition(e), b.params.virtualTranslate && 0 !== e) {
                            var a = !1;
                            b.slides.transitionEnd(function() {
                                if (!a && b) {
                                    a = !0, b.animating = !1;
                                    for (var e = ["webkitTransitionEnd", "transitionend", "oTransitionEnd", "MSTransitionEnd", "msTransitionEnd"], t = 0; t < e.length; t++) b.wrapper.trigger(e[t])
                                }
                            })
                        }
                    }
                },
                flip: {
                    setTranslate: function() {
                        for (var a = 0; a < b.slides.length; a++) {
                            var t = b.slides.eq(a),
                                i = t[0].progress;
                            b.params.flip.limitRotation && (i = Math.max(Math.min(t[0].progress, 1), -1));
                            var s = t[0].swiperSlideOffset,
                                r = -180 * i,
                                n = r,
                                o = 0,
                                l = -s,
                                p = 0;
                            if (b.isHorizontal() ? b.rtl && (n = -n) : (p = l, l = 0, o = -n, n = 0), t[0].style.zIndex = -Math.abs(Math.round(i)) + b.slides.length, b.params.flip.slideShadows) {
                                var d = b.isHorizontal() ? t.find(".swiper-slide-shadow-left") : t.find(".swiper-slide-shadow-top"),
                                    u = b.isHorizontal() ? t.find(".swiper-slide-shadow-right") : t.find(".swiper-slide-shadow-bottom");
                                0 === d.length && (d = e('<div class="swiper-slide-shadow-' + (b.isHorizontal() ? "left" : "top") + '"></div>'), t.append(d)), 0 === u.length && (u = e('<div class="swiper-slide-shadow-' + (b.isHorizontal() ? "right" : "bottom") + '"></div>'), t.append(u)), d.length && (d[0].style.opacity = Math.max(-i, 0)), u.length && (u[0].style.opacity = Math.max(i, 0))
                            }
                            t.transform("translate3d(" + l + "px, " + p + "px, 0px) rotateX(" + o + "deg) rotateY(" + n + "deg)")
                        }
                    },
                    setTransition: function(a) {
                        if (b.slides.transition(a).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(a), b.params.virtualTranslate && 0 !== a) {
                            var t = !1;
                            b.slides.eq(b.activeIndex).transitionEnd(function() {
                                if (!t && b && e(this).hasClass(b.params.slideActiveClass)) {
                                    t = !0, b.animating = !1;
                                    for (var a = ["webkitTransitionEnd", "transitionend", "oTransitionEnd", "MSTransitionEnd", "msTransitionEnd"], i = 0; i < a.length; i++) b.wrapper.trigger(a[i])
                                }
                            })
                        }
                    }
                },
                cube: {
                    setTranslate: function() {
                        var a, t = 0;
                        b.params.cube.shadow && (b.isHorizontal() ? (a = b.wrapper.find(".swiper-cube-shadow"), 0 === a.length && (a = e('<div class="swiper-cube-shadow"></div>'), b.wrapper.append(a)), a.css({
                            height: b.width + "px"
                        })) : (a = b.container.find(".swiper-cube-shadow"), 0 === a.length && (a = e('<div class="swiper-cube-shadow"></div>'), b.container.append(a))));
                        for (var i = 0; i < b.slides.length; i++) {
                            var s = b.slides.eq(i),
                                r = 90 * i,
                                n = Math.floor(r / 360);
                            b.rtl && (r = -r, n = Math.floor(-r / 360));
                            var o = Math.max(Math.min(s[0].progress, 1), -1),
                                l = 0,
                                p = 0,
                                d = 0;
                            i % 4 == 0 ? (l = 4 * -n * b.size, d = 0) : (i - 1) % 4 == 0 ? (l = 0, d = 4 * -n * b.size) : (i - 2) % 4 == 0 ? (l = b.size + 4 * n * b.size, d = b.size) : (i - 3) % 4 == 0 && (l = -b.size, d = 3 * b.size + 4 * b.size * n), b.rtl && (l = -l), b.isHorizontal() || (p = l, l = 0);
                            var u = "rotateX(" + (b.isHorizontal() ? 0 : -r) + "deg) rotateY(" + (b.isHorizontal() ? r : 0) + "deg) translate3d(" + l + "px, " + p + "px, " + d + "px)";
                            if (o <= 1 && o > -1 && (t = 90 * i + 90 * o, b.rtl && (t = 90 * -i - 90 * o)), s.transform(u), b.params.cube.slideShadows) {
                                var c = b.isHorizontal() ? s.find(".swiper-slide-shadow-left") : s.find(".swiper-slide-shadow-top"),
                                    m = b.isHorizontal() ? s.find(".swiper-slide-shadow-right") : s.find(".swiper-slide-shadow-bottom");
                                0 === c.length && (c = e('<div class="swiper-slide-shadow-' + (b.isHorizontal() ? "left" : "top") + '"></div>'), s.append(c)), 0 === m.length && (m = e('<div class="swiper-slide-shadow-' + (b.isHorizontal() ? "right" : "bottom") + '"></div>'), s.append(m)), c.length && (c[0].style.opacity = Math.max(-o, 0)), m.length && (m[0].style.opacity = Math.max(o, 0))
                            }
                        }
                        if (b.wrapper.css({
                            "-webkit-transform-origin": "50% 50% -" + b.size / 2 + "px",
                            "-moz-transform-origin": "50% 50% -" + b.size / 2 + "px",
                            "-ms-transform-origin": "50% 50% -" + b.size / 2 + "px",
                            "transform-origin": "50% 50% -" + b.size / 2 + "px"
                        }), b.params.cube.shadow)
                            if (b.isHorizontal()) a.transform("translate3d(0px, " + (b.width / 2 + b.params.cube.shadowOffset) + "px, " + -b.width / 2 + "px) rotateX(90deg) rotateZ(0deg) scale(" + b.params.cube.shadowScale + ")");
                            else {
                                var h = Math.abs(t) - 90 * Math.floor(Math.abs(t) / 90),
                                    g = 1.5 - (Math.sin(2 * h * Math.PI / 360) / 2 + Math.cos(2 * h * Math.PI / 360) / 2),
                                    f = b.params.cube.shadowScale,
                                    v = b.params.cube.shadowScale / g,
                                    w = b.params.cube.shadowOffset;
                                a.transform("scale3d(" + f + ", 1, " + v + ") translate3d(0px, " + (b.height / 2 + w) + "px, " + -b.height / 2 / v + "px) rotateX(-90deg)")
                            }
                        var y = b.isSafari || b.isUiWebView ? -b.size / 2 : 0;
                        b.wrapper.transform("translate3d(0px,0," + y + "px) rotateX(" + (b.isHorizontal() ? 0 : t) + "deg) rotateY(" + (b.isHorizontal() ? -t : 0) + "deg)")
                    },
                    setTransition: function(e) {
                        b.slides.transition(e).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(e), b.params.cube.shadow && !b.isHorizontal() && b.container.find(".swiper-cube-shadow").transition(e)
                    }
                },
                coverflow: {
                    setTranslate: function() {
                        for (var a = b.translate, t = b.isHorizontal() ? -a + b.width / 2 : -a + b.height / 2, i = b.isHorizontal() ? b.params.coverflow.rotate : -b.params.coverflow.rotate, s = b.params.coverflow.depth, r = 0, n = b.slides.length; r < n; r++) {
                            var o = b.slides.eq(r),
                                l = b.slidesSizesGrid[r],
                                p = o[0].swiperSlideOffset,
                                d = (t - p - l / 2) / l * b.params.coverflow.modifier,
                                u = b.isHorizontal() ? i * d : 0,
                                c = b.isHorizontal() ? 0 : i * d,
                                m = -s * Math.abs(d),
                                h = b.isHorizontal() ? 0 : b.params.coverflow.stretch * d,
                                g = b.isHorizontal() ? b.params.coverflow.stretch * d : 0;
                            Math.abs(g) < .001 && (g = 0), Math.abs(h) < .001 && (h = 0), Math.abs(m) < .001 && (m = 0), Math.abs(u) < .001 && (u = 0), Math.abs(c) < .001 && (c = 0);
                            var f = "translate3d(" + g + "px," + h + "px," + m + "px)  rotateX(" + c + "deg) rotateY(" + u + "deg)";
                            if (o.transform(f), o[0].style.zIndex = 1 - Math.abs(Math.round(d)), b.params.coverflow.slideShadows) {
                                var v = b.isHorizontal() ? o.find(".swiper-slide-shadow-left") : o.find(".swiper-slide-shadow-top"),
                                    w = b.isHorizontal() ? o.find(".swiper-slide-shadow-right") : o.find(".swiper-slide-shadow-bottom");
                                0 === v.length && (v = e('<div class="swiper-slide-shadow-' + (b.isHorizontal() ? "left" : "top") + '"></div>'), o.append(v)), 0 === w.length && (w = e('<div class="swiper-slide-shadow-' + (b.isHorizontal() ? "right" : "bottom") + '"></div>'), o.append(w)), v.length && (v[0].style.opacity = d > 0 ? d : 0), w.length && (w[0].style.opacity = -d > 0 ? -d : 0)
                            }
                        }
                        b.browser.ie && (b.wrapper[0].style.perspectiveOrigin = t + "px 50%")
                    },
                    setTransition: function(e) {
                        b.slides.transition(e).find(".swiper-slide-shadow-top, .swiper-slide-shadow-right, .swiper-slide-shadow-bottom, .swiper-slide-shadow-left").transition(e)
                    }
                }
            }, b.lazy = {
                initialImageLoaded: !1,
                loadImageInSlide: function(a, t) {
                    if (void 0 !== a && (void 0 === t && (t = !0), 0 !== b.slides.length)) {
                        var i = b.slides.eq(a),
                            s = i.find("." + b.params.lazyLoadingClass + ":not(." + b.params.lazyStatusLoadedClass + "):not(." + b.params.lazyStatusLoadingClass + ")");
                        !i.hasClass(b.params.lazyLoadingClass) || i.hasClass(b.params.lazyStatusLoadedClass) || i.hasClass(b.params.lazyStatusLoadingClass) || (s = s.add(i[0])), 0 !== s.length && s.each(function() {
                            var a = e(this);
                            a.addClass(b.params.lazyStatusLoadingClass);
                            var s = a.attr("data-background"),
                                r = a.attr("data-src"),
                                n = a.attr("data-srcset"),
                                o = a.attr("data-sizes");
                            b.loadImage(a[0], r || s, n, o, !1, function() {
                                if (void 0 !== b && null !== b && b) {
                                    if (s ? (a.css("background-image", 'url("' + s + '")'), a.removeAttr("data-background")) : (n && (a.attr("srcset", n), a.removeAttr("data-srcset")), o && (a.attr("sizes", o), a.removeAttr("data-sizes")), r && (a.attr("src", r), a.removeAttr("data-src"))), a.addClass(b.params.lazyStatusLoadedClass).removeClass(b.params.lazyStatusLoadingClass), i.find("." + b.params.lazyPreloaderClass + ", ." + b.params.preloaderClass).remove(), b.params.loop && t) {
                                        var e = i.attr("data-swiper-slide-index");
                                        if (i.hasClass(b.params.slideDuplicateClass)) {
                                            var l = b.wrapper.children('[data-swiper-slide-index="' + e + '"]:not(.' + b.params.slideDuplicateClass + ")");
                                            b.lazy.loadImageInSlide(l.index(), !1)
                                        } else {
                                            var p = b.wrapper.children("." + b.params.slideDuplicateClass + '[data-swiper-slide-index="' + e + '"]');
                                            b.lazy.loadImageInSlide(p.index(), !1)
                                        }
                                    }
                                    b.emit("onLazyImageReady", b, i[0], a[0])
                                }
                            }), b.emit("onLazyImageLoad", b, i[0], a[0])
                        })
                    }
                },
                load: function() {
                    var a, t = b.params.slidesPerView;
                    if ("auto" === t && (t = 0), b.lazy.initialImageLoaded || (b.lazy.initialImageLoaded = !0), b.params.watchSlidesVisibility) b.wrapper.children("." + b.params.slideVisibleClass).each(function() {
                        b.lazy.loadImageInSlide(e(this).index())
                    });
                    else if (t > 1)
                        for (a = b.activeIndex; a < b.activeIndex + t; a++) b.slides[a] && b.lazy.loadImageInSlide(a);
                    else b.lazy.loadImageInSlide(b.activeIndex);
                    if (b.params.lazyLoadingInPrevNext)
                        if (t > 1 || b.params.lazyLoadingInPrevNextAmount && b.params.lazyLoadingInPrevNextAmount > 1) {
                            var i = b.params.lazyLoadingInPrevNextAmount,
                                s = t,
                                r = Math.min(b.activeIndex + s + Math.max(i, s), b.slides.length),
                                n = Math.max(b.activeIndex - Math.max(s, i), 0);
                            for (a = b.activeIndex + t; a < r; a++) b.slides[a] && b.lazy.loadImageInSlide(a);
                            for (a = n; a < b.activeIndex; a++) b.slides[a] && b.lazy.loadImageInSlide(a)
                        } else {
                            var o = b.wrapper.children("." + b.params.slideNextClass);
                            o.length > 0 && b.lazy.loadImageInSlide(o.index());
                            var l = b.wrapper.children("." + b.params.slidePrevClass);
                            l.length > 0 && b.lazy.loadImageInSlide(l.index())
                        }
                },
                onTransitionStart: function() {
                    b.params.lazyLoading && (b.params.lazyLoadingOnTransitionStart || !b.params.lazyLoadingOnTransitionStart && !b.lazy.initialImageLoaded) && b.lazy.load()
                },
                onTransitionEnd: function() {
                    b.params.lazyLoading && !b.params.lazyLoadingOnTransitionStart && b.lazy.load()
                }
            }, b.scrollbar = {
                isTouched: !1,
                setDragPosition: function(e) {
                    var a = b.scrollbar,
                        t = b.isHorizontal() ? "touchstart" === e.type || "touchmove" === e.type ? e.targetTouches[0].pageX : e.pageX || e.clientX : "touchstart" === e.type || "touchmove" === e.type ? e.targetTouches[0].pageY : e.pageY || e.clientY,
                        i = t - a.track.offset()[b.isHorizontal() ? "left" : "top"] - a.dragSize / 2,
                        s = -b.minTranslate() * a.moveDivider,
                        r = -b.maxTranslate() * a.moveDivider;
                    i < s ? i = s : i > r && (i = r), i = -i / a.moveDivider, b.updateProgress(i), b.setWrapperTranslate(i, !0)
                },
                dragStart: function(e) {
                    var a = b.scrollbar;
                    a.isTouched = !0, e.preventDefault(), e.stopPropagation(), a.setDragPosition(e), clearTimeout(a.dragTimeout), a.track.transition(0), b.params.scrollbarHide && a.track.css("opacity", 1), b.wrapper.transition(100), a.drag.transition(100), b.emit("onScrollbarDragStart", b)
                },
                dragMove: function(e) {
                    var a = b.scrollbar;
                    a.isTouched && (e.preventDefault ? e.preventDefault() : e.returnValue = !1, a.setDragPosition(e), b.wrapper.transition(0), a.track.transition(0), a.drag.transition(0), b.emit("onScrollbarDragMove", b))
                },
                dragEnd: function(e) {
                    var a = b.scrollbar;
                    a.isTouched && (a.isTouched = !1, b.params.scrollbarHide && (clearTimeout(a.dragTimeout), a.dragTimeout = setTimeout(function() {
                        a.track.css("opacity", 0), a.track.transition(400)
                    }, 1e3)), b.emit("onScrollbarDragEnd", b), b.params.scrollbarSnapOnRelease && b.slideReset())
                },
                draggableEvents: function() {
                    return !1 !== b.params.simulateTouch || b.support.touch ? b.touchEvents : b.touchEventsDesktop
                }(),
                enableDraggable: function() {
                    var a = b.scrollbar,
                        t = b.support.touch ? a.track : document;
                    e(a.track).on(a.draggableEvents.start, a.dragStart), e(t).on(a.draggableEvents.move, a.dragMove), e(t).on(a.draggableEvents.end, a.dragEnd)
                },
                disableDraggable: function() {
                    var a = b.scrollbar,
                        t = b.support.touch ? a.track : document;
                    e(a.track).off(a.draggableEvents.start, a.dragStart), e(t).off(a.draggableEvents.move, a.dragMove), e(t).off(a.draggableEvents.end, a.dragEnd)
                },
                set: function() {
                    if (b.params.scrollbar) {
                        var a = b.scrollbar;
                        a.track = e(b.params.scrollbar), b.params.uniqueNavElements && "string" == typeof b.params.scrollbar && a.track.length > 1 && 1 === b.container.find(b.params.scrollbar).length && (a.track = b.container.find(b.params.scrollbar)), a.drag = a.track.find(".swiper-scrollbar-drag"), 0 === a.drag.length && (a.drag = e('<div class="swiper-scrollbar-drag"></div>'), a.track.append(a.drag)), a.drag[0].style.width = "", a.drag[0].style.height = "", a.trackSize = b.isHorizontal() ? a.track[0].offsetWidth : a.track[0].offsetHeight, a.divider = b.size / b.virtualSize, a.moveDivider = a.divider * (a.trackSize / b.size), a.dragSize = a.trackSize * a.divider, b.isHorizontal() ? a.drag[0].style.width = a.dragSize + "px" : a.drag[0].style.height = a.dragSize + "px", a.divider >= 1 ? a.track[0].style.display = "none" : a.track[0].style.display = "", b.params.scrollbarHide && (a.track[0].style.opacity = 0)
                    }
                },
                setTranslate: function() {
                    if (b.params.scrollbar) {
                        var e, a = b.scrollbar,
                            t = (b.translate, a.dragSize);
                        e = (a.trackSize - a.dragSize) * b.progress, b.rtl && b.isHorizontal() ? (e = -e, e > 0 ? (t = a.dragSize - e, e = 0) : -e + a.dragSize > a.trackSize && (t = a.trackSize + e)) : e < 0 ? (t = a.dragSize + e, e = 0) : e + a.dragSize > a.trackSize && (t = a.trackSize - e), b.isHorizontal() ? (b.support.transforms3d ? a.drag.transform("translate3d(" + e + "px, 0, 0)") : a.drag.transform("translateX(" + e + "px)"), a.drag[0].style.width = t + "px") : (b.support.transforms3d ? a.drag.transform("translate3d(0px, " + e + "px, 0)") : a.drag.transform("translateY(" + e + "px)"), a.drag[0].style.height = t + "px"), b.params.scrollbarHide && (clearTimeout(a.timeout), a.track[0].style.opacity = 1, a.timeout = setTimeout(function() {
                            a.track[0].style.opacity = 0, a.track.transition(400)
                        }, 1e3))
                    }
                },
                setTransition: function(e) {
                    b.params.scrollbar && b.scrollbar.drag.transition(e)
                }
            }, b.controller = {
                LinearSpline: function(e, a) {
                    var t = function() {
                        var e, a, t;
                        return function(i, s) {
                            for (a = -1, e = i.length; e - a > 1;) i[t = e + a >> 1] <= s ? a = t : e = t;
                            return e
                        }
                    }();
                    this.x = e, this.y = a, this.lastIndex = e.length - 1;
                    var i, s;
                    this.x.length, this.interpolate = function(e) {
                        return e ? (s = t(this.x, e), i = s - 1, (e - this.x[i]) * (this.y[s] - this.y[i]) / (this.x[s] - this.x[i]) + this.y[i]) : 0
                    }
                },
                getInterpolateFunction: function(e) {
                    b.controller.spline || (b.controller.spline = b.params.loop ? new b.controller.LinearSpline(b.slidesGrid, e.slidesGrid) : new b.controller.LinearSpline(b.snapGrid, e.snapGrid))
                },
                setTranslate: function(e, t) {
                    function i(a) {
                        e = a.rtl && "horizontal" === a.params.direction ? -b.translate : b.translate, "slide" === b.params.controlBy && (b.controller.getInterpolateFunction(a), r = -b.controller.spline.interpolate(-e)), r && "container" !== b.params.controlBy || (s = (a.maxTranslate() - a.minTranslate()) / (b.maxTranslate() - b.minTranslate()), r = (e - b.minTranslate()) * s + a.minTranslate()), b.params.controlInverse && (r = a.maxTranslate() - r), a.updateProgress(r), a.setWrapperTranslate(r, !1, b), a.updateActiveIndex()
                    }
                    var s, r, n = b.params.control;
                    if (Array.isArray(n))
                        for (var o = 0; o < n.length; o++) n[o] !== t && n[o] instanceof a && i(n[o]);
                    else n instanceof a && t !== n && i(n)
                },
                setTransition: function(e, t) {
                    function i(a) {
                        a.setWrapperTransition(e, b), 0 !== e && (a.onTransitionStart(), a.wrapper.transitionEnd(function() {
                            r && (a.params.loop && "slide" === b.params.controlBy && a.fixLoop(), a.onTransitionEnd())
                        }))
                    }
                    var s, r = b.params.control;
                    if (Array.isArray(r))
                        for (s = 0; s < r.length; s++) r[s] !== t && r[s] instanceof a && i(r[s]);
                    else r instanceof a && t !== r && i(r)
                }
            }, b.hashnav = {
                onHashCange: function(e, a) {
                    var t = document.location.hash.replace("#", "");
                    t !== b.slides.eq(b.activeIndex).attr("data-hash") && b.slideTo(b.wrapper.children("." + b.params.slideClass + '[data-hash="' + t + '"]').index())
                },
                attachEvents: function(a) {
                    var t = a ? "off" : "on";
                    e(window)[t]("hashchange", b.hashnav.onHashCange)
                },
                setHash: function() {
                    if (b.hashnav.initialized && b.params.hashnav)
                        if (b.params.replaceState && window.history && window.history.replaceState) window.history.replaceState(null, null, "#" + b.slides.eq(b.activeIndex).attr("data-hash") || "");
                        else {
                            var e = b.slides.eq(b.activeIndex),
                                a = e.attr("data-hash") || e.attr("data-history");
                            document.location.hash = a || ""
                        }
                },
                init: function() {
                    if (b.params.hashnav && !b.params.history) {
                        b.hashnav.initialized = !0;
                        var e = document.location.hash.replace("#", "");
                        if (e)
                            for (var a = 0, t = b.slides.length; a < t; a++) {
                                var i = b.slides.eq(a),
                                    s = i.attr("data-hash") || i.attr("data-history");
                                if (s === e && !i.hasClass(b.params.slideDuplicateClass)) {
                                    var r = i.index();
                                    b.slideTo(r, 0, b.params.runCallbacksOnInit, !0)
                                }
                            }
                        b.params.hashnavWatchState && b.hashnav.attachEvents()
                    }
                },
                destroy: function() {
                    b.params.hashnavWatchState && b.hashnav.attachEvents(!0)
                }
            }, b.history = {
                init: function() {
                    if (b.params.history) {
                        if (!window.history || !window.history.pushState) return b.params.history = !1, void(b.params.hashnav = !0);
                        b.history.initialized = !0, this.paths = this.getPathValues(), (this.paths.key || this.paths.value) && (this.scrollToSlide(0, this.paths.value, b.params.runCallbacksOnInit), b.params.replaceState || window.addEventListener("popstate", this.setHistoryPopState))
                    }
                },
                setHistoryPopState: function() {
                    b.history.paths = b.history.getPathValues(), b.history.scrollToSlide(b.params.speed, b.history.paths.value, !1)
                },
                getPathValues: function() {
                    var e = window.location.pathname.slice(1).split("/"),
                        a = e.length;
                    return {
                        key: e[a - 2],
                        value: e[a - 1]
                    }
                },
                setHistory: function(e, a) {
                    if (b.history.initialized && b.params.history) {
                        var t = b.slides.eq(a),
                            i = this.slugify(t.attr("data-history"));
                        window.location.pathname.includes(e) || (i = e + "/" + i), b.params.replaceState ? window.history.replaceState(null, null, i) : window.history.pushState(null, null, i)
                    }
                },
                slugify: function(e) {
                    return e.toString().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(/\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "")
                },
                scrollToSlide: function(e, a, t) {
                    if (a)
                        for (var i = 0, s = b.slides.length; i < s; i++) {
                            var r = b.slides.eq(i),
                                n = this.slugify(r.attr("data-history"));
                            if (n === a && !r.hasClass(b.params.slideDuplicateClass)) {
                                var o = r.index();
                                b.slideTo(o, e, t)
                            }
                        } else b.slideTo(0, e, t)
                }
            }, b.disableKeyboardControl = function() {
                b.params.keyboardControl = !1, e(document).off("keydown", p)
            }, b.enableKeyboardControl = function() {
                b.params.keyboardControl = !0, e(document).on("keydown", p)
            }, b.mousewheel = {
                event: !1,
                lastScrollTime: (new window.Date).getTime()
            }, b.params.mousewheelControl && (b.mousewheel.event = navigator.userAgent.indexOf("firefox") > -1 ? "DOMMouseScroll" : function() {
                var e = "onwheel" in document;
                if (!e) {
                    var a = document.createElement("div");
                    a.setAttribute("onwheel", "return;"), e = "function" == typeof a.onwheel
                }
                return !e && document.implementation && document.implementation.hasFeature && !0 !== document.implementation.hasFeature("", "") && (e = document.implementation.hasFeature("Events.wheel", "3.0")), e
            }() ? "wheel" : "mousewheel"), b.disableMousewheelControl = function() {
                if (!b.mousewheel.event) return !1;
                var a = b.container;
                return "container" !== b.params.mousewheelEventsTarged && (a = e(b.params.mousewheelEventsTarged)), a.off(b.mousewheel.event, u), b.params.mousewheelControl = !1, !0
            }, b.enableMousewheelControl = function() {
                if (!b.mousewheel.event) return !1;
                var a = b.container;
                return "container" !== b.params.mousewheelEventsTarged && (a = e(b.params.mousewheelEventsTarged)), a.on(b.mousewheel.event, u), b.params.mousewheelControl = !0, !0
            }, b.parallax = {
                setTranslate: function() {
                    b.container.children("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y]").each(function() {
                        c(this, b.progress)
                    }), b.slides.each(function() {
                        var a = e(this);
                        a.find("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y]").each(function() {
                            c(this, Math.min(Math.max(a[0].progress, -1), 1))
                        })
                    })
                },
                setTransition: function(a) {
                    void 0 === a && (a = b.params.speed), b.container.find("[data-swiper-parallax], [data-swiper-parallax-x], [data-swiper-parallax-y]").each(function() {
                        var t = e(this),
                            i = parseInt(t.attr("data-swiper-parallax-duration"), 10) || a;
                        0 === a && (i = 0), t.transition(i)
                    })
                }
            }, b.zoom = {
                scale: 1,
                currentScale: 1,
                isScaling: !1,
                gesture: {
                    slide: void 0,
                    slideWidth: void 0,
                    slideHeight: void 0,
                    image: void 0,
                    imageWrap: void 0,
                    zoomMax: b.params.zoomMax
                },
                image: {
                    isTouched: void 0,
                    isMoved: void 0,
                    currentX: void 0,
                    currentY: void 0,
                    minX: void 0,
                    minY: void 0,
                    maxX: void 0,
                    maxY: void 0,
                    width: void 0,
                    height: void 0,
                    startX: void 0,
                    startY: void 0,
                    touchesStart: {},
                    touchesCurrent: {}
                },
                velocity: {
                    x: void 0,
                    y: void 0,
                    prevPositionX: void 0,
                    prevPositionY: void 0,
                    prevTime: void 0
                },
                getDistanceBetweenTouches: function(e) {
                    if (e.targetTouches.length < 2) return 1;
                    var a = e.targetTouches[0].pageX,
                        t = e.targetTouches[0].pageY,
                        i = e.targetTouches[1].pageX,
                        s = e.targetTouches[1].pageY;
                    return Math.sqrt(Math.pow(i - a, 2) + Math.pow(s - t, 2))
                },
                onGestureStart: function(a) {
                    var t = b.zoom;
                    if (!b.support.gestures) {
                        if ("touchstart" !== a.type || "touchstart" === a.type && a.targetTouches.length < 2) return;
                        t.gesture.scaleStart = t.getDistanceBetweenTouches(a)
                    }
                    if (!(t.gesture.slide && t.gesture.slide.length || (t.gesture.slide = e(this), 0 === t.gesture.slide.length && (t.gesture.slide = b.slides.eq(b.activeIndex)), t.gesture.image = t.gesture.slide.find("img, svg, canvas"), t.gesture.imageWrap = t.gesture.image.parent("." + b.params.zoomContainerClass), t.gesture.zoomMax = t.gesture.imageWrap.attr("data-swiper-zoom") || b.params.zoomMax, 0 !== t.gesture.imageWrap.length))) return void(t.gesture.image = void 0);
                    t.gesture.image.transition(0), t.isScaling = !0
                },
                onGestureChange: function(e) {
                    var a = b.zoom;
                    if (!b.support.gestures) {
                        if ("touchmove" !== e.type || "touchmove" === e.type && e.targetTouches.length < 2) return;
                        a.gesture.scaleMove = a.getDistanceBetweenTouches(e)
                    }
                    a.gesture.image && 0 !== a.gesture.image.length && (b.support.gestures ? a.scale = e.scale * a.currentScale : a.scale = a.gesture.scaleMove / a.gesture.scaleStart * a.currentScale, a.scale > a.gesture.zoomMax && (a.scale = a.gesture.zoomMax - 1 + Math.pow(a.scale - a.gesture.zoomMax + 1, .5)), a.scale < b.params.zoomMin && (a.scale = b.params.zoomMin + 1 - Math.pow(b.params.zoomMin - a.scale + 1, .5)), a.gesture.image.transform("translate3d(0,0,0) scale(" + a.scale + ")"))
                },
                onGestureEnd: function(e) {
                    var a = b.zoom;
                    !b.support.gestures && ("touchend" !== e.type || "touchend" === e.type && e.changedTouches.length < 2) || a.gesture.image && 0 !== a.gesture.image.length && (a.scale = Math.max(Math.min(a.scale, a.gesture.zoomMax), b.params.zoomMin), a.gesture.image.transition(b.params.speed).transform("translate3d(0,0,0) scale(" + a.scale + ")"), a.currentScale = a.scale, a.isScaling = !1, 1 === a.scale && (a.gesture.slide = void 0))
                },
                onTouchStart: function(e, a) {
                    var t = e.zoom;
                    t.gesture.image && 0 !== t.gesture.image.length && (t.image.isTouched || ("android" === e.device.os && a.preventDefault(), t.image.isTouched = !0, t.image.touchesStart.x = "touchstart" === a.type ? a.targetTouches[0].pageX : a.pageX, t.image.touchesStart.y = "touchstart" === a.type ? a.targetTouches[0].pageY : a.pageY))
                },
                onTouchMove: function(e) {
                    var a = b.zoom;
                    if (a.gesture.image && 0 !== a.gesture.image.length && (b.allowClick = !1, a.image.isTouched && a.gesture.slide)) {
                        a.image.isMoved || (a.image.width = a.gesture.image[0].offsetWidth, a.image.height = a.gesture.image[0].offsetHeight, a.image.startX = b.getTranslate(a.gesture.imageWrap[0], "x") || 0, a.image.startY = b.getTranslate(a.gesture.imageWrap[0], "y") || 0, a.gesture.slideWidth = a.gesture.slide[0].offsetWidth, a.gesture.slideHeight = a.gesture.slide[0].offsetHeight, a.gesture.imageWrap.transition(0), b.rtl && (a.image.startX = -a.image.startX), b.rtl && (a.image.startY = -a.image.startY));
                        var t = a.image.width * a.scale,
                            i = a.image.height * a.scale;
                        if (!(t < a.gesture.slideWidth && i < a.gesture.slideHeight)) {
                            if (a.image.minX = Math.min(a.gesture.slideWidth / 2 - t / 2, 0), a.image.maxX = -a.image.minX, a.image.minY = Math.min(a.gesture.slideHeight / 2 - i / 2, 0), a.image.maxY = -a.image.minY, a.image.touchesCurrent.x = "touchmove" === e.type ? e.targetTouches[0].pageX : e.pageX, a.image.touchesCurrent.y = "touchmove" === e.type ? e.targetTouches[0].pageY : e.pageY, !a.image.isMoved && !a.isScaling) {
                                if (b.isHorizontal() && Math.floor(a.image.minX) === Math.floor(a.image.startX) && a.image.touchesCurrent.x < a.image.touchesStart.x || Math.floor(a.image.maxX) === Math.floor(a.image.startX) && a.image.touchesCurrent.x > a.image.touchesStart.x) return void(a.image.isTouched = !1);
                                if (!b.isHorizontal() && Math.floor(a.image.minY) === Math.floor(a.image.startY) && a.image.touchesCurrent.y < a.image.touchesStart.y || Math.floor(a.image.maxY) === Math.floor(a.image.startY) && a.image.touchesCurrent.y > a.image.touchesStart.y) return void(a.image.isTouched = !1)
                            }
                            e.preventDefault(), e.stopPropagation(), a.image.isMoved = !0, a.image.currentX = a.image.touchesCurrent.x - a.image.touchesStart.x + a.image.startX, a.image.currentY = a.image.touchesCurrent.y - a.image.touchesStart.y + a.image.startY, a.image.currentX < a.image.minX && (a.image.currentX = a.image.minX + 1 - Math.pow(a.image.minX - a.image.currentX + 1, .8)), a.image.currentX > a.image.maxX && (a.image.currentX = a.image.maxX - 1 + Math.pow(a.image.currentX - a.image.maxX + 1, .8)), a.image.currentY < a.image.minY && (a.image.currentY = a.image.minY + 1 - Math.pow(a.image.minY - a.image.currentY + 1, .8)), a.image.currentY > a.image.maxY && (a.image.currentY = a.image.maxY - 1 + Math.pow(a.image.currentY - a.image.maxY + 1, .8)), a.velocity.prevPositionX || (a.velocity.prevPositionX = a.image.touchesCurrent.x), a.velocity.prevPositionY || (a.velocity.prevPositionY = a.image.touchesCurrent.y), a.velocity.prevTime || (a.velocity.prevTime = Date.now()), a.velocity.x = (a.image.touchesCurrent.x - a.velocity.prevPositionX) / (Date.now() - a.velocity.prevTime) / 2, a.velocity.y = (a.image.touchesCurrent.y - a.velocity.prevPositionY) / (Date.now() - a.velocity.prevTime) / 2, Math.abs(a.image.touchesCurrent.x - a.velocity.prevPositionX) < 2 && (a.velocity.x = 0), Math.abs(a.image.touchesCurrent.y - a.velocity.prevPositionY) < 2 && (a.velocity.y = 0), a.velocity.prevPositionX = a.image.touchesCurrent.x, a.velocity.prevPositionY = a.image.touchesCurrent.y, a.velocity.prevTime = Date.now(), a.gesture.imageWrap.transform("translate3d(" + a.image.currentX + "px, " + a.image.currentY + "px,0)")
                        }
                    }
                },
                onTouchEnd: function(e, a) {
                    var t = e.zoom;
                    if (t.gesture.image && 0 !== t.gesture.image.length) {
                        if (!t.image.isTouched || !t.image.isMoved) return t.image.isTouched = !1, void(t.image.isMoved = !1);
                        t.image.isTouched = !1, t.image.isMoved = !1;
                        var i = 300,
                            s = 300,
                            r = t.velocity.x * i,
                            n = t.image.currentX + r,
                            o = t.velocity.y * s,
                            l = t.image.currentY + o;
                        0 !== t.velocity.x && (i = Math.abs((n - t.image.currentX) / t.velocity.x)), 0 !== t.velocity.y && (s = Math.abs((l - t.image.currentY) / t.velocity.y));
                        var p = Math.max(i, s);
                        t.image.currentX = n, t.image.currentY = l;
                        var d = t.image.width * t.scale,
                            u = t.image.height * t.scale;
                        t.image.minX = Math.min(t.gesture.slideWidth / 2 - d / 2, 0), t.image.maxX = -t.image.minX, t.image.minY = Math.min(t.gesture.slideHeight / 2 - u / 2, 0), t.image.maxY = -t.image.minY, t.image.currentX = Math.max(Math.min(t.image.currentX, t.image.maxX), t.image.minX), t.image.currentY = Math.max(Math.min(t.image.currentY, t.image.maxY), t.image.minY), t.gesture.imageWrap.transition(p).transform("translate3d(" + t.image.currentX + "px, " + t.image.currentY + "px,0)")
                    }
                },
                onTransitionEnd: function(e) {
                    var a = e.zoom;
                    a.gesture.slide && e.previousIndex !== e.activeIndex && (a.gesture.image.transform("translate3d(0,0,0) scale(1)"), a.gesture.imageWrap.transform("translate3d(0,0,0)"), a.gesture.slide = a.gesture.image = a.gesture.imageWrap = void 0, a.scale = a.currentScale = 1)
                },
                toggleZoom: function(a, t) {
                    var i = a.zoom;
                    if (i.gesture.slide || (i.gesture.slide = a.clickedSlide ? e(a.clickedSlide) : a.slides.eq(a.activeIndex), i.gesture.image = i.gesture.slide.find("img, svg, canvas"), i.gesture.imageWrap = i.gesture.image.parent("." + a.params.zoomContainerClass)), i.gesture.image && 0 !== i.gesture.image.length) {
                        var s, r, n, o, l, p, d, u, c, m, h, g, f, v, w, y, x, b;
                        void 0 === i.image.touchesStart.x && t ? (s = "touchend" === t.type ? t.changedTouches[0].pageX : t.pageX, r = "touchend" === t.type ? t.changedTouches[0].pageY : t.pageY) : (s = i.image.touchesStart.x, r = i.image.touchesStart.y), i.scale && 1 !== i.scale ? (i.scale = i.currentScale = 1, i.gesture.imageWrap.transition(300).transform("translate3d(0,0,0)"), i.gesture.image.transition(300).transform("translate3d(0,0,0) scale(1)"), i.gesture.slide = void 0) : (i.scale = i.currentScale = i.gesture.imageWrap.attr("data-swiper-zoom") || a.params.zoomMax, t ? (x = i.gesture.slide[0].offsetWidth, b = i.gesture.slide[0].offsetHeight, n = i.gesture.slide.offset().left, o = i.gesture.slide.offset().top, l = n + x / 2 - s, p = o + b / 2 - r, c = i.gesture.image[0].offsetWidth, m = i.gesture.image[0].offsetHeight, h = c * i.scale, g = m * i.scale, f = Math.min(x / 2 - h / 2, 0), v = Math.min(b / 2 - g / 2, 0), w = -f, y = -v, d = l * i.scale, u = p * i.scale, d < f && (d = f), d > w && (d = w), u < v && (u = v), u > y && (u = y)) : (d = 0, u = 0), i.gesture.imageWrap.transition(300).transform("translate3d(" + d + "px, " + u + "px,0)"), i.gesture.image.transition(300).transform("translate3d(0,0,0) scale(" + i.scale + ")"))
                    }
                },
                attachEvents: function(a) {
                    var t = a ? "off" : "on";
                    if (b.params.zoom) {
                        var i = (b.slides, !("touchstart" !== b.touchEvents.start || !b.support.passiveListener || !b.params.passiveListeners) && {
                            passive: !0,
                            capture: !1
                        });
                        b.support.gestures ? (b.slides[t]("gesturestart", b.zoom.onGestureStart, i), b.slides[t]("gesturechange", b.zoom.onGestureChange, i), b.slides[t]("gestureend", b.zoom.onGestureEnd, i)) : "touchstart" === b.touchEvents.start && (b.slides[t](b.touchEvents.start, b.zoom.onGestureStart, i), b.slides[t](b.touchEvents.move, b.zoom.onGestureChange, i), b.slides[t](b.touchEvents.end, b.zoom.onGestureEnd, i)), b[t]("touchStart", b.zoom.onTouchStart), b.slides.each(function(a, i) {
                            e(i).find("." + b.params.zoomContainerClass).length > 0 && e(i)[t](b.touchEvents.move, b.zoom.onTouchMove)
                        }), b[t]("touchEnd", b.zoom.onTouchEnd), b[t]("transitionEnd", b.zoom.onTransitionEnd), b.params.zoomToggle && b.on("doubleTap", b.zoom.toggleZoom)
                    }
                },
                init: function() {
                    b.zoom.attachEvents()
                },
                destroy: function() {
                    b.zoom.attachEvents(!0)
                }
            }, b._plugins = [];
            for (var X in b.plugins) {
                var O = b.plugins[X](b, b.params[X]);
                O && b._plugins.push(O)
            }
            return b.callPlugins = function(e) {
                for (var a = 0; a < b._plugins.length; a++) e in b._plugins[a] && b._plugins[a][e](arguments[1], arguments[2], arguments[3], arguments[4], arguments[5])
            }, b.emitterEventListeners = {}, b.emit = function(e) {
                b.params[e] && b.params[e](arguments[1], arguments[2], arguments[3], arguments[4], arguments[5]);
                var a;
                if (b.emitterEventListeners[e])
                    for (a = 0; a < b.emitterEventListeners[e].length; a++) b.emitterEventListeners[e][a](arguments[1], arguments[2], arguments[3], arguments[4], arguments[5]);
                b.callPlugins && b.callPlugins(e, arguments[1], arguments[2], arguments[3], arguments[4], arguments[5])
            }, b.on = function(e, a) {
                return e = m(e), b.emitterEventListeners[e] || (b.emitterEventListeners[e] = []), b.emitterEventListeners[e].push(a), b
            }, b.off = function(e, a) {
                var t;
                if (e = m(e), void 0 === a) return b.emitterEventListeners[e] = [], b;
                if (b.emitterEventListeners[e] && 0 !== b.emitterEventListeners[e].length) {
                    for (t = 0; t < b.emitterEventListeners[e].length; t++) b.emitterEventListeners[e][t] === a && b.emitterEventListeners[e].splice(t, 1);
                    return b
                }
            }, b.once = function(e, a) {
                e = m(e);
                var t = function() {
                    a(arguments[0], arguments[1], arguments[2], arguments[3], arguments[4]), b.off(e, t)
                };
                return b.on(e, t), b
            }, b.a11y = {
                makeFocusable: function(e) {
                    return e.attr("tabIndex", "0"), e
                },
                addRole: function(e, a) {
                    return e.attr("role", a), e
                },
                addLabel: function(e, a) {
                    return e.attr("aria-label", a), e
                },
                disable: function(e) {
                    return e.attr("aria-disabled", !0), e
                },
                enable: function(e) {
                    return e.attr("aria-disabled", !1), e
                },
                onEnterKey: function(a) {
                    13 === a.keyCode && (e(a.target).is(b.params.nextButton) ? (b.onClickNext(a), b.isEnd ? b.a11y.notify(b.params.lastSlideMessage) : b.a11y.notify(b.params.nextSlideMessage)) : e(a.target).is(b.params.prevButton) && (b.onClickPrev(a), b.isBeginning ? b.a11y.notify(b.params.firstSlideMessage) : b.a11y.notify(b.params.prevSlideMessage)), e(a.target).is("." + b.params.bulletClass) && e(a.target)[0].click())
                },
                liveRegion: e('<span class="' + b.params.notificationClass + '" aria-live="assertive" aria-atomic="true"></span>'),
                notify: function(e) {
                    var a = b.a11y.liveRegion;
                    0 !== a.length && (a.html(""), a.html(e))
                },
                init: function() {
                    b.params.nextButton && b.nextButton && b.nextButton.length > 0 && (b.a11y.makeFocusable(b.nextButton), b.a11y.addRole(b.nextButton, "button"), b.a11y.addLabel(b.nextButton, b.params.nextSlideMessage)), b.params.prevButton && b.prevButton && b.prevButton.length > 0 && (b.a11y.makeFocusable(b.prevButton), b.a11y.addRole(b.prevButton, "button"), b.a11y.addLabel(b.prevButton, b.params.prevSlideMessage)), e(b.container).append(b.a11y.liveRegion)
                },
                initPagination: function() {
                    b.params.pagination && b.params.paginationClickable && b.bullets && b.bullets.length && b.bullets.each(function() {
                        var a = e(this);
                        b.a11y.makeFocusable(a), b.a11y.addRole(a, "button"), b.a11y.addLabel(a, b.params.paginationBulletMessage.replace(/{{index}}/, a.index() + 1))
                    })
                },
                destroy: function() {
                    b.a11y.liveRegion && b.a11y.liveRegion.length > 0 && b.a11y.liveRegion.remove()
                }
            }, b.init = function() {
                b.params.loop && b.createLoop(), b.updateContainerSize(), b.updateSlidesSize(), b.updatePagination(), b.params.scrollbar && b.scrollbar && (b.scrollbar.set(), b.params.scrollbarDraggable && b.scrollbar.enableDraggable()), "slide" !== b.params.effect && b.effects[b.params.effect] && (b.params.loop || b.updateProgress(), b.effects[b.params.effect].setTranslate()), b.params.loop ? b.slideTo(b.params.initialSlide + b.loopedSlides, 0, b.params.runCallbacksOnInit) : (b.slideTo(b.params.initialSlide, 0, b.params.runCallbacksOnInit), 0 === b.params.initialSlide && (b.parallax && b.params.parallax && b.parallax.setTranslate(), b.lazy && b.params.lazyLoading && (b.lazy.load(), b.lazy.initialImageLoaded = !0))), b.attachEvents(), b.params.observer && b.support.observer && b.initObservers(), b.params.preloadImages && !b.params.lazyLoading && b.preloadImages(), b.params.zoom && b.zoom && b.zoom.init(), b.params.autoplay && b.startAutoplay(), b.params.keyboardControl && b.enableKeyboardControl && b.enableKeyboardControl(), b.params.mousewheelControl && b.enableMousewheelControl && b.enableMousewheelControl(), b.params.hashnavReplaceState && (b.params.replaceState = b.params.hashnavReplaceState), b.params.history && b.history && b.history.init(), b.params.hashnav && b.hashnav && b.hashnav.init(), b.params.a11y && b.a11y && b.a11y.init(), b.emit("onInit", b)
            }, b.cleanupStyles = function() {
                b.container.removeClass(b.classNames.join(" ")).removeAttr("style"), b.wrapper.removeAttr("style"), b.slides && b.slides.length && b.slides.removeClass([b.params.slideVisibleClass, b.params.slideActiveClass, b.params.slideNextClass, b.params.slidePrevClass].join(" ")).removeAttr("style").removeAttr("data-swiper-column").removeAttr("data-swiper-row"), b.paginationContainer && b.paginationContainer.length && b.paginationContainer.removeClass(b.params.paginationHiddenClass), b.bullets && b.bullets.length && b.bullets.removeClass(b.params.bulletActiveClass), b.params.prevButton && e(b.params.prevButton).removeClass(b.params.buttonDisabledClass), b.params.nextButton && e(b.params.nextButton).removeClass(b.params.buttonDisabledClass), b.params.scrollbar && b.scrollbar && (b.scrollbar.track && b.scrollbar.track.length && b.scrollbar.track.removeAttr("style"), b.scrollbar.drag && b.scrollbar.drag.length && b.scrollbar.drag.removeAttr("style"))
            }, b.destroy = function(e, a) {
                b.detachEvents(), b.stopAutoplay(), b.params.scrollbar && b.scrollbar && b.params.scrollbarDraggable && b.scrollbar.disableDraggable(), b.params.loop && b.destroyLoop(), a && b.cleanupStyles(), b.disconnectObservers(), b.params.zoom && b.zoom && b.zoom.destroy(), b.params.keyboardControl && b.disableKeyboardControl && b.disableKeyboardControl(), b.params.mousewheelControl && b.disableMousewheelControl && b.disableMousewheelControl(), b.params.a11y && b.a11y && b.a11y.destroy(), b.params.history && !b.params.replaceState && window.removeEventListener("popstate", b.history.setHistoryPopState), b.params.hashnav && b.hashnav && b.hashnav.destroy(), b.emit("onDestroy"), !1 !== e && (b = null)
            }, b.init(), b
        }
    };
    a.prototype = {
        isSafari: function() {
            var e = window.navigator.userAgent.toLowerCase();
            return e.indexOf("safari") >= 0 && e.indexOf("chrome") < 0 && e.indexOf("android") < 0
        }(),
        isUiWebView: /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(window.navigator.userAgent),
        isArray: function(e) {
            return "[object Array]" === Object.prototype.toString.apply(e)
        },
        browser: {
            ie: window.navigator.pointerEnabled || window.navigator.msPointerEnabled,
            ieTouch: window.navigator.msPointerEnabled && window.navigator.msMaxTouchPoints > 1 || window.navigator.pointerEnabled && window.navigator.maxTouchPoints > 1,
            lteIE9: function() {
                var e = document.createElement("div");
                return e.innerHTML = "\x3c!--[if lte IE 9]><i></i><![endif]--\x3e", 1 === e.getElementsByTagName("i").length
            }()
        },
        device: function() {
            var e = window.navigator.userAgent,
                a = e.match(/(Android);?[\s\/]+([\d.]+)?/),
                t = e.match(/(iPad).*OS\s([\d_]+)/),
                i = e.match(/(iPod)(.*OS\s([\d_]+))?/),
                s = !t && e.match(/(iPhone\sOS|iOS)\s([\d_]+)/);
            return {
                ios: t || s || i,
                android: a
            }
        }(),
        support: {
            touch: window.Modernizr && !0 === Modernizr.touch || function() {
                return !!("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch)
            }(),
            transforms3d: window.Modernizr && !0 === Modernizr.csstransforms3d || function() {
                var e = document.createElement("div").style;
                return "webkitPerspective" in e || "MozPerspective" in e || "OPerspective" in e || "MsPerspective" in e || "perspective" in e
            }(),
            flexbox: function() {
                for (var e = document.createElement("div").style, a = "alignItems webkitAlignItems webkitBoxAlign msFlexAlign mozBoxAlign webkitFlexDirection msFlexDirection mozBoxDirection mozBoxOrient webkitBoxDirection webkitBoxOrient".split(" "), t = 0; t < a.length; t++)
                    if (a[t] in e) return !0
            }(),
            observer: function() {
                return "MutationObserver" in window || "WebkitMutationObserver" in window
            }(),
            passiveListener: function() {
                var e = !1;
                try {
                    var a = Object.defineProperty({}, "passive", {
                        get: function() {
                            e = !0
                        }
                    });
                    window.addEventListener("testPassiveListener", null, a)
                } catch (e) {}
                return e
            }(),
            gestures: function() {
                return "ongesturestart" in window
            }()
        },
        plugins: {}
    };
    for (var t = (function() {
        var e = function(e) {
                var a = this,
                    t = 0;
                for (t = 0; t < e.length; t++) a[t] = e[t];
                return a.length = e.length, this
            },
            a = function(a, t) {
                var i = [],
                    s = 0;
                if (a && !t && a instanceof e) return a;
                if (a)
                    if ("string" == typeof a) {
                        var r, n, o = a.trim();
                        if (o.indexOf("<") >= 0 && o.indexOf(">") >= 0) {
                            var l = "div";
                            for (0 === o.indexOf("<li") && (l = "ul"), 0 === o.indexOf("<tr") && (l = "tbody"), 0 !== o.indexOf("<td") && 0 !== o.indexOf("<th") || (l = "tr"), 0 === o.indexOf("<tbody") && (l = "table"), 0 === o.indexOf("<option") && (l = "select"), n = document.createElement(l), n.innerHTML = a, s = 0; s < n.childNodes.length; s++) i.push(n.childNodes[s])
                        } else
                            for (r = t || "#" !== a[0] || a.match(/[ .<>:~]/) ? (t || document).querySelectorAll(a) : [document.getElementById(a.split("#")[1])], s = 0; s < r.length; s++) r[s] && i.push(r[s])
                    } else if (a.nodeType || a === window || a === document) i.push(a);
                    else if (a.length > 0 && a[0].nodeType)
                        for (s = 0; s < a.length; s++) i.push(a[s]);
                return new e(i)
            };
        return e.prototype = {
            addClass: function(e) {
                if (void 0 === e) return this;
                for (var a = e.split(" "), t = 0; t < a.length; t++)
                    for (var i = 0; i < this.length; i++) this[i].classList.add(a[t]);
                return this
            },
            removeClass: function(e) {
                for (var a = e.split(" "), t = 0; t < a.length; t++)
                    for (var i = 0; i < this.length; i++) this[i].classList.remove(a[t]);
                return this
            },
            hasClass: function(e) {
                return !!this[0] && this[0].classList.contains(e)
            },
            toggleClass: function(e) {
                for (var a = e.split(" "), t = 0; t < a.length; t++)
                    for (var i = 0; i < this.length; i++) this[i].classList.toggle(a[t]);
                return this
            },
            attr: function(e, a) {
                if (1 === arguments.length && "string" == typeof e) return this[0] ? this[0].getAttribute(e) : void 0;
                for (var t = 0; t < this.length; t++)
                    if (2 === arguments.length) this[t].setAttribute(e, a);
                    else
                        for (var i in e) this[t][i] = e[i], this[t].setAttribute(i, e[i]);
                return this
            },
            removeAttr: function(e) {
                for (var a = 0; a < this.length; a++) this[a].removeAttribute(e);
                return this
            },
            data: function(e, a) {
                if (void 0 !== a) {
                    for (var t = 0; t < this.length; t++) {
                        var i = this[t];
                        i.dom7ElementDataStorage || (i.dom7ElementDataStorage = {}), i.dom7ElementDataStorage[e] = a
                    }
                    return this
                }
                if (this[0]) {
                    var s = this[0].getAttribute("data-" + e);
                    return s || (this[0].dom7ElementDataStorage && e in this[0].dom7ElementDataStorage ? this[0].dom7ElementDataStorage[e] : void 0)
                }
            },
            transform: function(e) {
                for (var a = 0; a < this.length; a++) {
                    var t = this[a].style;
                    t.webkitTransform = t.MsTransform = t.msTransform = t.MozTransform = t.OTransform = t.transform = e
                }
                return this
            },
            transition: function(e) {
                "string" != typeof e && (e += "ms");
                for (var a = 0; a < this.length; a++) {
                    var t = this[a].style;
                    t.webkitTransitionDuration = t.MsTransitionDuration = t.msTransitionDuration = t.MozTransitionDuration = t.OTransitionDuration = t.transitionDuration = e
                }
                return this
            },
            on: function(e, t, i, s) {
                function r(e) {
                    var s = e.target;
                    if (a(s).is(t)) i.call(s, e);
                    else
                        for (var r = a(s).parents(), n = 0; n < r.length; n++) a(r[n]).is(t) && i.call(r[n], e)
                }
                var n, o, l = e.split(" ");
                for (n = 0; n < this.length; n++)
                    if ("function" == typeof t || !1 === t)
                        for ("function" == typeof t && (i = arguments[1], s = arguments[2] || !1), o = 0; o < l.length; o++) this[n].addEventListener(l[o], i, s);
                    else
                        for (o = 0; o < l.length; o++) this[n].dom7LiveListeners || (this[n].dom7LiveListeners = []), this[n].dom7LiveListeners.push({
                            listener: i,
                            liveListener: r
                        }), this[n].addEventListener(l[o], r, s);
                return this
            },
            off: function(e, a, t, i) {
                for (var s = e.split(" "), r = 0; r < s.length; r++)
                    for (var n = 0; n < this.length; n++)
                        if ("function" == typeof a || !1 === a) "function" == typeof a && (t = arguments[1], i = arguments[2] || !1), this[n].removeEventListener(s[r], t, i);
                        else if (this[n].dom7LiveListeners)
                            for (var o = 0; o < this[n].dom7LiveListeners.length; o++) this[n].dom7LiveListeners[o].listener === t && this[n].removeEventListener(s[r], this[n].dom7LiveListeners[o].liveListener, i);
                return this
            },
            once: function(e, a, t, i) {
                function s(n) {
                    t(n), r.off(e, a, s, i)
                }
                var r = this;
                "function" == typeof a && (a = !1, t = arguments[1], i = arguments[2]), r.on(e, a, s, i)
            },
            trigger: function(e, a) {
                for (var t = 0; t < this.length; t++) {
                    var i;
                    try {
                        i = new window.CustomEvent(e, {
                            detail: a,
                            bubbles: !0,
                            cancelable: !0
                        })
                    } catch (t) {
                        i = document.createEvent("Event"), i.initEvent(e, !0, !0), i.detail = a
                    }
                    this[t].dispatchEvent(i)
                }
                return this
            },
            transitionEnd: function(e) {
                function a(r) {
                    if (r.target === this)
                        for (e.call(this, r), t = 0; t < i.length; t++) s.off(i[t], a)
                }
                var t, i = ["webkitTransitionEnd", "transitionend", "oTransitionEnd", "MSTransitionEnd", "msTransitionEnd"],
                    s = this;
                if (e)
                    for (t = 0; t < i.length; t++) s.on(i[t], a);
                return this
            },
            width: function() {
                return this[0] === window ? window.innerWidth : this.length > 0 ? parseFloat(this.css("width")) : null
            },
            outerWidth: function(e) {
                return this.length > 0 ? e ? this[0].offsetWidth + parseFloat(this.css("margin-right")) + parseFloat(this.css("margin-left")) : this[0].offsetWidth : null
            },
            height: function() {
                return this[0] === window ? window.innerHeight : this.length > 0 ? parseFloat(this.css("height")) : null
            },
            outerHeight: function(e) {
                return this.length > 0 ? e ? this[0].offsetHeight + parseFloat(this.css("margin-top")) + parseFloat(this.css("margin-bottom")) : this[0].offsetHeight : null
            },
            offset: function() {
                if (this.length > 0) {
                    var e = this[0],
                        a = e.getBoundingClientRect(),
                        t = document.body,
                        i = e.clientTop || t.clientTop || 0,
                        s = e.clientLeft || t.clientLeft || 0,
                        r = window.pageYOffset || e.scrollTop,
                        n = window.pageXOffset || e.scrollLeft;
                    return {
                        top: a.top + r - i,
                        left: a.left + n - s
                    }
                }
                return null
            },
            css: function(e, a) {
                var t;
                if (1 === arguments.length) {
                    if ("string" != typeof e) {
                        for (t = 0; t < this.length; t++)
                            for (var i in e) this[t].style[i] = e[i];
                        return this
                    }
                    if (this[0]) return window.getComputedStyle(this[0], null).getPropertyValue(e)
                }
                if (2 === arguments.length && "string" == typeof e) {
                    for (t = 0; t < this.length; t++) this[t].style[e] = a;
                    return this
                }
                return this
            },
            each: function(e) {
                for (var a = 0; a < this.length; a++) e.call(this[a], a, this[a]);
                return this
            },
            html: function(e) {
                if (void 0 === e) return this[0] ? this[0].innerHTML : void 0;
                for (var a = 0; a < this.length; a++) this[a].innerHTML = e;
                return this
            },
            text: function(e) {
                if (void 0 === e) return this[0] ? this[0].textContent.trim() : null;
                for (var a = 0; a < this.length; a++) this[a].textContent = e;
                return this
            },
            is: function(t) {
                if (!this[0]) return !1;
                var i, s;
                if ("string" == typeof t) {
                    var r = this[0];
                    if (r === document) return t === document;
                    if (r === window) return t === window;
                    if (r.matches) return r.matches(t);
                    if (r.webkitMatchesSelector) return r.webkitMatchesSelector(t);
                    if (r.mozMatchesSelector) return r.mozMatchesSelector(t);
                    if (r.msMatchesSelector) return r.msMatchesSelector(t);
                    for (i = a(t), s = 0; s < i.length; s++)
                        if (i[s] === this[0]) return !0;
                    return !1
                }
                if (t === document) return this[0] === document;
                if (t === window) return this[0] === window;
                if (t.nodeType || t instanceof e) {
                    for (i = t.nodeType ? [t] : t, s = 0; s < i.length; s++)
                        if (i[s] === this[0]) return !0;
                    return !1
                }
                return !1
            },
            index: function() {
                if (this[0]) {
                    for (var e = this[0], a = 0; null !== (e = e.previousSibling);) 1 === e.nodeType && a++;
                    return a
                }
            },
            eq: function(a) {
                if (void 0 === a) return this;
                var t, i = this.length;
                return a > i - 1 ? new e([]) : a < 0 ? (t = i + a, new e(t < 0 ? [] : [this[t]])) : new e([this[a]])
            },
            append: function(a) {
                var t, i;
                for (t = 0; t < this.length; t++)
                    if ("string" == typeof a) {
                        var s = document.createElement("div");
                        for (s.innerHTML = a; s.firstChild;) this[t].appendChild(s.firstChild)
                    } else if (a instanceof e)
                        for (i = 0; i < a.length; i++) this[t].appendChild(a[i]);
                    else this[t].appendChild(a);
                return this
            },
            prepend: function(a) {
                var t, i;
                for (t = 0; t < this.length; t++)
                    if ("string" == typeof a) {
                        var s = document.createElement("div");
                        for (s.innerHTML = a, i = s.childNodes.length - 1; i >= 0; i--) this[t].insertBefore(s.childNodes[i], this[t].childNodes[0])
                    } else if (a instanceof e)
                        for (i = 0; i < a.length; i++) this[t].insertBefore(a[i], this[t].childNodes[0]);
                    else this[t].insertBefore(a, this[t].childNodes[0]);
                return this
            },
            insertBefore: function(e) {
                for (var t = a(e), i = 0; i < this.length; i++)
                    if (1 === t.length) t[0].parentNode.insertBefore(this[i], t[0]);
                    else if (t.length > 1)
                        for (var s = 0; s < t.length; s++) t[s].parentNode.insertBefore(this[i].cloneNode(!0), t[s])
            },
            insertAfter: function(e) {
                for (var t = a(e), i = 0; i < this.length; i++)
                    if (1 === t.length) t[0].parentNode.insertBefore(this[i], t[0].nextSibling);
                    else if (t.length > 1)
                        for (var s = 0; s < t.length; s++) t[s].parentNode.insertBefore(this[i].cloneNode(!0), t[s].nextSibling)
            },
            next: function(t) {
                return new e(this.length > 0 ? t ? this[0].nextElementSibling && a(this[0].nextElementSibling).is(t) ? [this[0].nextElementSibling] : [] : this[0].nextElementSibling ? [this[0].nextElementSibling] : [] : [])
            },
            nextAll: function(t) {
                var i = [],
                    s = this[0];
                if (!s) return new e([]);
                for (; s.nextElementSibling;) {
                    var r = s.nextElementSibling;
                    t ? a(r).is(t) && i.push(r) : i.push(r), s = r
                }
                return new e(i)
            },
            prev: function(t) {
                return new e(this.length > 0 ? t ? this[0].previousElementSibling && a(this[0].previousElementSibling).is(t) ? [this[0].previousElementSibling] : [] : this[0].previousElementSibling ? [this[0].previousElementSibling] : [] : [])
            },
            prevAll: function(t) {
                var i = [],
                    s = this[0];
                if (!s) return new e([]);
                for (; s.previousElementSibling;) {
                    var r = s.previousElementSibling;
                    t ? a(r).is(t) && i.push(r) : i.push(r), s = r
                }
                return new e(i)
            },
            parent: function(e) {
                for (var t = [], i = 0; i < this.length; i++) e ? a(this[i].parentNode).is(e) && t.push(this[i].parentNode) : t.push(this[i].parentNode);
                return a(a.unique(t))
            },
            parents: function(e) {
                for (var t = [], i = 0; i < this.length; i++)
                    for (var s = this[i].parentNode; s;) e ? a(s).is(e) && t.push(s) : t.push(s), s = s.parentNode;
                return a(a.unique(t))
            },
            find: function(a) {
                for (var t = [], i = 0; i < this.length; i++)
                    for (var s = this[i].querySelectorAll(a), r = 0; r < s.length; r++) t.push(s[r]);
                return new e(t)
            },
            children: function(t) {
                for (var i = [], s = 0; s < this.length; s++)
                    for (var r = this[s].childNodes, n = 0; n < r.length; n++) t ? 1 === r[n].nodeType && a(r[n]).is(t) && i.push(r[n]) : 1 === r[n].nodeType && i.push(r[n]);
                return new e(a.unique(i))
            },
            remove: function() {
                for (var e = 0; e < this.length; e++) this[e].parentNode && this[e].parentNode.removeChild(this[e]);
                return this
            },
            add: function() {
                var e, t, i = this;
                for (e = 0; e < arguments.length; e++) {
                    var s = a(arguments[e]);
                    for (t = 0; t < s.length; t++) i[i.length] = s[t], i.length++
                }
                return i
            }
        }, a.fn = e.prototype, a.unique = function(e) {
            for (var a = [], t = 0; t < e.length; t++) - 1 === a.indexOf(e[t]) && a.push(e[t]);
            return a
        }, a
    }()), i = ["jQuery", "Zepto", "Dom7"], s = 0; s < i.length; s++) window[i[s]] && function(e) {
        e.fn.swiper = function(t) {
            var i;
            return e(this).each(function() {
                var e = new a(this, t);
                i || (i = e)
            }), i
        }
    }(window[i[s]]);
    var r;
    r = void 0 === t ? window.Dom7 || window.Zepto || window.jQuery : t, r && ("transitionEnd" in r.fn || (r.fn.transitionEnd = function(e) {
        function a(r) {
            if (r.target === this)
                for (e.call(this, r), t = 0; t < i.length; t++) s.off(i[t], a)
        }
        var t, i = ["webkitTransitionEnd", "transitionend", "oTransitionEnd", "MSTransitionEnd", "msTransitionEnd"],
            s = this;
        if (e)
            for (t = 0; t < i.length; t++) s.on(i[t], a);
        return this
    }), "transform" in r.fn || (r.fn.transform = function(e) {
        for (var a = 0; a < this.length; a++) {
            var t = this[a].style;
            t.webkitTransform = t.MsTransform = t.msTransform = t.MozTransform = t.OTransform = t.transform = e
        }
        return this
    }), "transition" in r.fn || (r.fn.transition = function(e) {
        "string" != typeof e && (e += "ms");
        for (var a = 0; a < this.length; a++) {
            var t = this[a].style;
            t.webkitTransitionDuration = t.MsTransitionDuration = t.msTransitionDuration = t.MozTransitionDuration = t.OTransitionDuration = t.transitionDuration = e
        }
        return this
    }), "outerWidth" in r.fn || (r.fn.outerWidth = function(e) {
        return this.length > 0 ? e ? this[0].offsetWidth + parseFloat(this.css("margin-right")) + parseFloat(this.css("margin-left")) : this[0].offsetWidth : null
    })), window.Swiper = a
}(), "undefined" != typeof module ? module.exports = window.Swiper : "function" == typeof define && define.amd && define([], function() {
    "use strict";
    return window.Swiper
}), rtcScript(), setInterval(rtcScript, 2e3), jQuery(document).ready(function(e) {
    jQuery("a.social-share").on("click", function() {
        return newwindow = window.open(e(this).attr("href"), "", "height=500,width=500"), window.focus && newwindow.focus(), !1
    })
}), $(function() {
    $("#search-keyword").on('keyup', function () {
        $("#ui-id-1").empty();
        $("#ui-id-1").css('display','none');
        var search_term = this.value;
        if (search_term.length > 2){
            $.ajax({
                type: "get",
                url: APP_URL + "/search/autocomplete",
                data: {term: search_term},
                success: function (response) {
                    if (response) {
                        // console.log(response.length);
                        if (response.length > 0){
                            $("#ui-id-1").empty();
                            $('#ui-id-1').css('display','block');
                            // console.log(response);
                            $.each(response,function (index,a) {
                                var html =
                                    "<li class='ui-menu-item'><div class='autocomplete-item'>" +
                                    "<span class='item'>" +
                                    "<span class='item-left custom_item_left'>" +
                                    "<div class='cart-image'>" +
                                    "<img class='img-responsive' src='" + a.imgsrc + "' alt='" + a.value + "' />" +
                                    "</div>" +
                                    "<span class='item-info custom_item_info'>" +
                                    "<a href='" + a.link + "'>" +
                                    "<strong>" + a.value + "</strong>" +
                                    "</a>";

                                var total_varaints = a.product_variants.count;
                                var variant_data = [];
                                if ( total_varaints > 0){
                                    var variant_amt = 0;
                                    for (var i = 0;  i < total_varaints; i++){
                                        if (a.cart_item_qty == 0){
                                            variant_data.push(0);
                                        }
                                        html += "<strong>"+ a.product_variants.name[i] +" : <b>"+ a.product_variants.value[i] +"</b></strong>";
                                        variant_amt += parseFloat(a.product_variants.amt[i]);
                                    }
                                    var total_prod_amt = parseFloat(a.price) + variant_amt;
                                    if(a.cart_item_qty > 0){
                                        total_prod_amt = a.product_variants.amt[0];
                                    }

                                    html += "<strong>Price : <b>"+ total_prod_amt+"</b></strong>";

                                }else{
                                    html += "<strong>Price : <b>"+a.price+"</b></strong>";
                                }
                                html +="</span>" +
                                    "<span id='custom_add_search_cart'>";
                                // console.log(a.virtual + '>>>>>>>>>>' + a.downloadable + '>>>>>>>>>>' +a.file_id);

                                if(a.cart_item_qty > 0){
                                    if(a.virtual === 0 && a.downloadable === 0 && a.file_id === null){
                                        $disable =  (a.allow_cart_item_qty === a.cart_item_qty) ? 'disabled' : '';
                                        html +=
                                            "<button class='btn btn-primary update_cart' value='decrease' type='button' onclick='update_cart(&apos;"+a.row_id+"&apos;,this.value)'><i class='fa fa-minus-circle'></i></button>" +
                                            "<input class='form-control custom_qty_inp text-center' disabled value='"+ a.cart_item_qty +"' min='1' max='"+a.max_qty+"' step='1'>" +
                                            "<button class='btn btn-primary' " + $disable +" value='increase' type='button' onclick='update_cart(&apos;"+a.row_id+"&apos;,this.value)'><i class='fa fa-plus-circle'></i></button>";
                                    }else{
                                        html +="<label class='text-center '>"+
                                            "<button class='btn btn-danger update_cart' value='decrease' type='button' onclick='update_cart(&apos;"+a.row_id+"&apos;,this.value)'><i class='fa fa-trash'></i></button>"+
                                            "</label>";
                                    }
                                }else{
                                    html += "<span class='custom_add_cart_btn'><button class='btn btn-primary' type='button' onclick='add_cart(" + a.id + ",[" + variant_data + "])'><i class='fa fa-shopping-cart'  title='Add To Cart'></i></button></span>";
                                }
                                html +=
                                    "</span>"+
                                    "</span>" +
                                    "</span>" +
                                    "</div></li><hr class='custom_search_hr'>";
                                $("#ui-id-1").append(html);
                            });

                        }else{
                            $("#ui-id-1").css('display','none');
                            $("#ui-id-1").empty();
                        }
                        // console.log(response);
                    } else {
                        $("#ui-id-1").css('display','none');
                        $("#ui-id-1").empty();
                        // console.log('empty');
                    }
                },
                error: function (response) {
                    $("#ui-id-1").css('display','none');
                    $("#ui-id-1").empty();
                    // console.log('errror');
                }
            });
        }
    });
}),$(function() {
    $(".dropdown-wrapper-cart").hover( function (e) {
        $.ajax({
            type: "get",
            url: APP_URL + "/cart/ajax",
            success: function (response) {
                $('.cart-items-hover').empty();
                $('.cart-items-hover').html(response);
            },
            error: function (response) {}
        });
    });
});
function refreshCartPage() {
    var page_url            = window.location.href;
    var cart_page_url       = APP_URL+'/cart';
    var checkout_page_url   = APP_URL+'/checkout/payment';
    if(cart_page_url == page_url) {
        $.ajax({
            type: "get",
            url: APP_URL + "/cart/refreshCartPage",
            success: function (response) {
                $('.cart-container').empty();
                $('.cart-container').html(response);
            },
            error: function (response) {}
        });
    }

    if(checkout_page_url == page_url) {
        var elem = document.getElementById('wallet_use');
        var wallet_used = 0;
        var wallet_used_amt = 0;
        if(elem !== null ) {
            wallet_used     = elem.checked === true ? 1 : 0;
            wallet_used_amt = elem.getAttribute('data-id') !== 0  ? elem.getAttribute('data-id') : 0;
        }
        $.ajax({
            type: "get",
            url: APP_URL + "/checkout/refreshCheckoutPage",
            data : {wallet_used : wallet_used, wallet_used_amt : wallet_used_amt  },
            success: function (response) {
                if (response != 0){
                    $('.custom_checkout').empty();
                    $('.custom_checkout').html(response);
                }else{
                    window.location.reload();
                }

            },
            error: function (response) {}
        });
    }
}