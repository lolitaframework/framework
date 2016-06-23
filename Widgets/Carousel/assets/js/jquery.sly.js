/*! sly 1.2.4 - 19th Aug 2014 | https://github.com/darsain/sly */
(function (l, A, Da) {
    function ka(f, k, Xa) {
        var pa, E, qa, s, ra, A, sa, la;

        function ba() {
            var b = 0, h = w.length;
            e.old = l.extend({}, e);
            y = I ? 0 : F[c.horizontal ? "width" : "height"]();
            R = K[c.horizontal ? "width" : "height"]();
            t = I ? f : u[c.horizontal ? "outerWidth" : "outerHeight"]();
            w.length = 0;
            e.start = 0;
            e.end = Math.max(t - y, 0);
            if (B) {
                b = p.length;
                G = u.children(c.itemSelector);
                p.length = 0;
                var a = ma(u, c.horizontal ? "paddingLeft" : "paddingTop"), ta = ma(u, c.horizontal ? "paddingRight" : "paddingBottom"), k = "border-box" === l(G).css("boxSizing"), Ya = "none" !==
                    G.css("float"), s = 0, m = G.length - 1, q;
                t = 0;
                G.each(function (b, h) {
                    var d = l(h), e = d[c.horizontal ? "outerWidth" : "outerHeight"](), f = ma(d, c.horizontal ? "marginLeft" : "marginTop"), d = ma(d, c.horizontal ? "marginRight" : "marginBottom"), g = e + f + d, n = !f || !d, k = {};
                    k.el = h;
                    k.size = n ? e : g;
                    k.half = k.size / 2;
                    k.start = t + (n ? f : 0);
                    k.center = k.start - Math.round(y / 2 - k.size / 2);
                    k.end = k.start - y + k.size;
                    b || (t += a);
                    t += g;
                    c.horizontal || Ya || d && (f && 0 < b) && (t -= Math.min(f, d));
                    b === m && (k.end += ta, t += ta, s = n ? d : 0);
                    p.push(k);
                    q = k
                });
                u[0].style[c.horizontal ? "width" : "height"] =
                    (k ? t : t - a - ta) + "px";
                t -= s;
                p.length ? (e.start = p[0][S ? "center" : "start"], e.end = S ? q.center : y < t ? q.end : e.start) : e.start = e.end = 0
            }
            e.center = Math.round(e.end / 2 + e.start / 2);
            l.extend(g, ua(void 0));
            C.length && 0 < R && (c.dynamicHandle ? (N = e.start === e.end ? R : Math.round(R * y / t), N = v(N, c.minHandleSize, R), C[0].style[c.horizontal ? "width" : "height"] = N + "px") : N = C[c.horizontal ? "outerWidth" : "outerHeight"](), r.end = R - N, T || Ea());
            if (!I && 0 < y) {
                var n = e.start, k = "";
                if (B)l.each(p, function (b, h) {
                    S ? w.push(h.center) : h.start + h.size > n && n <= e.end && (n =
                        h.start, w.push(n), n += y, n > e.end && n < e.end + y && w.push(e.end))
                }); else for (; n - y < e.end;)w.push(n), n += y;
                if (U[0] && h !== w.length) {
                    for (h = 0; h < w.length; h++)k += c.pageBuilder.call(d, h);
                    fa = U.html(k).children();
                    fa.eq(g.activePage).addClass(c.activeClass)
                }
            }
            g.slideeSize = t;
            g.frameSize = y;
            g.sbSize = R;
            g.handleSize = N;
            B ? (d.initialized ? (g.activeItem >= p.length || 0 === b && 0 < p.length) && na(g.activeItem >= p.length ? p.length - 1 : 0, !b) : (na(c.startAt), d[L ? "toCenter" : "toStart"](c.startAt)), J(L && p.length ? p[g.activeItem].center : v(e.dest, e.start,
                e.end))) : d.initialized ? J(v(e.dest, e.start, e.end)) : J(c.startAt, 1);
            z("load")
        }

        function J(b, h, ea) {
            if (B && a.released && !ea) {
                ea = ua(b);
                var f = b > e.start && b < e.end;
                L ? (f && (b = p[ea.centerItem].center), S && c.activateMiddle && na(ea.centerItem)) : f && (b = p[ea.firstItem].start)
            }
            a.init && a.slidee && c.elasticBounds ? b > e.end ? b = e.end + (b - e.end) / 6 : b < e.start && (b = e.start + (b - e.start) / 6) : b = v(b, e.start, e.end);
            pa = +new Date;
            E = 0;
            qa = e.cur;
            s = b;
            ra = b - e.cur;
            A = a.tweese || a.init && !a.slidee;
            sa = !A && (h || a.init && a.slidee || !c.speed);
            a.tweese = 0;
            b !== e.dest &&
            (e.dest = b, z("change"), T || va());
            a.released && !d.isPaused && d.resume();
            l.extend(g, ua(void 0));
            Fa();
            fa[0] && q.page !== g.activePage && (q.page = g.activePage, fa.removeClass(c.activeClass).eq(g.activePage).addClass(c.activeClass), z("activePage", q.page))
        }

        function va() {
            T ? (sa ? e.cur = s : A ? (la = s - e.cur, 0.1 > Math.abs(la) ? e.cur = s : e.cur += la * (a.released ? c.swingSpeed : c.syncSpeed)) : (E = Math.min(+new Date - pa, c.speed), e.cur = qa + ra * jQuery.easing[c.easing](E / c.speed, E, 0, 1, c.speed)), s === e.cur ? (e.cur = s, a.tweese = T = 0) : T = ga(va), z("move"),
            I || (D ? u[0].style[D] = ha + (c.horizontal ? "translateX" : "translateY") + "(" + -e.cur + "px)" : u[0].style[c.horizontal ? "left" : "top"] = -Math.round(e.cur) + "px"), !T && a.released && z("moveEnd"), Ea()) : (T = ga(va), a.released && z("moveStart"))
        }

        function Ea() {
            C.length && (r.cur = e.start === e.end ? 0 : ((a.init && !a.slidee ? e.dest : e.cur) - e.start) / (e.end - e.start) * r.end, r.cur = v(Math.round(r.cur), r.start, r.end), q.hPos !== r.cur && (q.hPos = r.cur, D ? C[0].style[D] = ha + (c.horizontal ? "translateX" : "translateY") + "(" + r.cur + "px)" : C[0].style[c.horizontal ?
                "left" : "top"] = r.cur + "px"))
        }

        function Ga() {
            x.speed && e.cur !== (0 < x.speed ? e.end : e.start) || d.stop();
            Ha = a.init ? ga(Ga) : 0;
            x.now = +new Date;
            x.pos = e.cur + (x.now - x.lastTime) / 1E3 * x.speed;
            J(a.init ? x.pos : Math.round(x.pos));
            a.init || e.cur !== e.dest || z("moveEnd");
            x.lastTime = x.now
        }

        function wa(b, h, a) {
            "boolean" === ia(h) && (a = h, h = Da);
            h === Da ? J(e[b], a) : L && "center" !== b || (h = d.getPos(h)) && J(h[b], a, !L)
        }

        function oa(b) {
            return null != b ? O(b) ? 0 <= b && b < p.length ? b : -1 : G.index(b) : -1
        }

        function xa(b) {
            return oa(O(b) && 0 > b ? b + p.length : b)
        }

        function na(b,
                    h) {
            var a = oa(b);
            if (!B || 0 > a)return !1;
            if (q.active !== a || h)G.eq(g.activeItem).removeClass(c.activeClass), G.eq(a).addClass(c.activeClass), q.active = g.activeItem = a, Fa(), z("active", a);
            return a
        }

        function ua(b) {
            b = v(O(b) ? b : e.dest, e.start, e.end);
            var h = {}, a = S ? 0 : y / 2;
            if (!I)for (var c = 0, d = w.length; c < d; c++) {
                if (b >= e.end || c === w.length - 1) {
                    h.activePage = w.length - 1;
                    break
                }
                if (b <= w[c] + a) {
                    h.activePage = c;
                    break
                }
            }
            if (B) {
                for (var d = c = a = !1, f = 0, g = p.length; f < g; f++)if (!1 === a && b <= p[f].start + p[f].half && (a = f), !1 === d && b <= p[f].center + p[f].half &&
                    (d = f), f === g - 1 || b <= p[f].end + p[f].half) {
                    c = f;
                    break
                }
                h.firstItem = O(a) ? a : 0;
                h.centerItem = O(d) ? d : h.firstItem;
                h.lastItem = O(c) ? c : h.centerItem
            }
            return h
        }

        function Fa() {
            var b = e.dest <= e.start, h = e.dest >= e.end, d = b ? 1 : h ? 2 : 3;
            q.slideePosState !== d && (q.slideePosState = d, V.is("button,input") && V.prop("disabled", b), W.is("button,input") && W.prop("disabled", h), V.add(ca)[b ? "addClass" : "removeClass"](c.disabledClass), W.add(X)[h ? "addClass" : "removeClass"](c.disabledClass));
            q.fwdbwdState !== d && a.released && (q.fwdbwdState = d, ca.is("button,input") &&
            ca.prop("disabled", b), X.is("button,input") && X.prop("disabled", h));
            B && (b = 0 === g.activeItem, h = g.activeItem >= p.length - 1, d = b ? 1 : h ? 2 : 3, q.itemsButtonState !== d && (q.itemsButtonState = d, Y.is("button,input") && Y.prop("disabled", b), Z.is("button,input") && Z.prop("disabled", h), Y[b ? "addClass" : "removeClass"](c.disabledClass), Z[h ? "addClass" : "removeClass"](c.disabledClass)))
        }

        function Ia(b, a, c) {
            b = xa(b);
            a = xa(a);
            if (-1 < b && -1 < a && b !== a && !(c && a === b - 1 || !c && a === b + 1)) {
                G.eq(b)[c ? "insertAfter" : "insertBefore"](p[a].el);
                var d = b < a ? b :
                    c ? a : a - 1, e = b > a ? b : c ? a + 1 : a, f = b > a;
                b === g.activeItem ? q.active = g.activeItem = c ? f ? a + 1 : a : f ? a : a - 1 : g.activeItem > d && g.activeItem < e && (q.active = g.activeItem += f ? 1 : -1);
                ba()
            }
        }

        function Ja(b, a) {
            for (var c = 0, d = H[b].length; c < d; c++)if (H[b][c] === a)return c;
            return -1
        }

        function Ka(b) {
            return Math.round(v(b, r.start, r.end) / r.end * (e.end - e.start)) + e.start
        }

        function Za() {
            a.history[0] = a.history[1];
            a.history[1] = a.history[2];
            a.history[2] = a.history[3];
            a.history[3] = a.delta
        }

        function La(b) {
            a.released = 0;
            a.source = b;
            a.slidee = "slidee" === b
        }

        function Ma(b) {
            if (!(a.init || ~l.inArray(b.target.nodeName, Na) || l(b.target).is(c.interactive))) {
                var h = "touchstart" === b.type, f = b.data.source, g = "slidee" === f;
                if ("handle" !== f || c.dragHandle && r.start !== r.end)if (!g || (h ? c.touchDragging : c.mouseDragging && 2 > b.which))h || M(b, 1), La(f), a.init = 1, a.$source = l(b.target), a.touch = h, a.pointer = h ? b.originalEvent.touches[0] : b, a.initX = a.pointer.pageX, a.initY = a.pointer.pageY, a.initPos = g ? e.cur : r.cur, a.start = +new Date, a.time = 0, a.path = 0, a.delta = 0, a.locked = 0, a.history = [0, 0, 0, 0], a.pathToLock = g ? h ? 30 : 10 : 0, a.initLoc =
                    a[c.horizontal ? "initX" : "initY"], a.deltaMin = g ? -a.initLoc : -r.cur, a.deltaMax = g ? document[c.horizontal ? "width" : "height"] - a.initLoc : r.end - r.cur, $.on(h ? Oa : Pa, Qa), d.pause(1), (g ? u : C).addClass(c.draggedClass), z("moveStart"), g && (Ra = setInterval(Za, 10))
            }
        }

        function Qa(b) {
            a.released = "mouseup" === b.type || "touchend" === b.type;
            a.pointer = a.touch ? b.originalEvent[a.released ? "changedTouches" : "touches"][0] : b;
            a.pathX = a.pointer.pageX - a.initX;
            a.pathY = a.pointer.pageY - a.initY;
            a.path = Math.sqrt(Math.pow(a.pathX, 2) + Math.pow(a.pathY,
                2));
            a.delta = v(c.horizontal ? a.pathX : a.pathY, a.deltaMin, a.deltaMax);
            if (!a.locked && a.path > a.pathToLock)if (a.locked = 1, c.horizontal ? Math.abs(a.pathX) < Math.abs(a.pathY) : Math.abs(a.pathX) > Math.abs(a.pathY))a.released = 1; else if (a.slidee)a.$source.on(aa, ya);
            a.released ? (a.touch || M(b), $a(), c.releaseSwing && a.slidee && (a.swing = 300 * ((a.delta - a.history[0]) / 40), a.delta += a.swing, a.tweese = 10 < Math.abs(a.swing))) : !a.locked && a.touch || M(b);
            J(a.slidee ? Math.round(a.initPos - a.delta) : Ka(a.initPos + a.delta))
        }

        function $a() {
            clearInterval(Ra);
            $.off(a.touch ? Oa : Pa, Qa);
            (a.slidee ? u : C).removeClass(c.draggedClass);
            setTimeout(function () {
                a.$source.off(aa, ya)
            });
            d.resume(1);
            e.cur === e.dest && a.init && z("moveEnd");
            a.init = 0
        }

        function Sa() {
            d.stop();
            $.off("mouseup", Sa)
        }

        function da(b) {
            M(b);
            switch (this) {
                case X[0]:
                case ca[0]:
                    d.moveBy(X.is(this) ? c.moveBy : -c.moveBy);
                    $.on("mouseup", Sa);
                    break;
                case Y[0]:
                    d.prev();
                    break;
                case Z[0]:
                    d.next();
                    break;
                case V[0]:
                    d.prevPage();
                    break;
                case W[0]:
                    d.nextPage()
            }
        }

        function ab(b) {
            n.curDelta = (c.horizontal ? b.deltaY || b.deltaX : b.deltaY) || -b.wheelDelta;
            n.curDelta /= 1 === b.deltaMode ? 3 : 100;
            if (!B)return n.curDelta;
            za = +new Date;
            n.last < za - n.resetTime && (n.delta = 0);
            n.last = za;
            n.delta += n.curDelta;
            1 > Math.abs(n.delta) ? n.finalDelta = 0 : (n.finalDelta = Math.round(n.delta / 1), n.delta %= 1);
            return n.finalDelta
        }

        function bb(b) {
            var a = +new Date;
            Aa + 300 > a ? Aa = a : c.scrollBy && e.start !== e.end && (M(b, 1), d.slideBy(c.scrollBy * ab(b.originalEvent)))
        }

        function cb(b) {
            c.clickBar && b.target === K[0] && (M(b), J(Ka((c.horizontal ? b.pageX - K.offset().left : b.pageY - K.offset().top) - N / 2)))
        }

        function db(b) {
            if (c.keyboardNavBy)switch (b.which) {
                case c.horizontal ? 37 : 38:
                    M(b);
                    d["pages" === c.keyboardNavBy ? "prevPage" : "prev"]();
                    break;
                case c.horizontal ? 39 : 40:
                    M(b), d["pages" === c.keyboardNavBy ? "nextPage" : "next"]()
            }
        }

        function eb(b) {
            ~l.inArray(this.nodeName, Na) || l(this).is(c.interactive) ? b.stopPropagation() : this.parentNode === u[0] && d.activate(this)
        }

        function fb() {
            this.parentNode === U[0] && d.activatePage(fa.index(this))
        }

        function gb(b) {
            if (c.pauseOnHover)d["mouseenter" === b.type ? "pause" : "resume"](2)
        }

        function z(b,
                   a) {
            if (H[b]) {
                Ba = H[b].length;
                for (P = Ca.length = 0; P < Ba; P++)Ca.push(H[b][P]);
                for (P = 0; P < Ba; P++)Ca[P].call(d, b, a)
            }
        }

        var c = l.extend({}, ka.defaults, k), d = this, I = O(f), F = l(f), u = F.children().eq(0), y = 0, t = 0, e = {
            start: 0,
            center: 0,
            end: 0,
            cur: 0,
            dest: 0
        }, K = l(c.scrollBar).eq(0), C = K.children().eq(0), R = 0, N = 0, r = {
            start: 0,
            end: 0,
            cur: 0
        }, U = l(c.pagesBar), fa = 0, w = [], G = 0, p = [], g = {
            firstItem: 0,
            lastItem: 0,
            centerItem: 0,
            activeItem: -1,
            activePage: 0
        };
        k = "basic" === c.itemNav;
        var S = "forceCentered" === c.itemNav, L = "centered" === c.itemNav || S, B = !I && (k ||
            L || S), Ta = c.scrollSource ? l(c.scrollSource) : F, hb = c.dragSource ? l(c.dragSource) : F, X = l(c.forward), ca = l(c.backward), Y = l(c.prev), Z = l(c.next), V = l(c.prevPage), W = l(c.nextPage), H = {}, q = {};
        la = sa = A = ra = s = qa = E = pa = void 0;
        var x = {}, a = {released: 1}, n = {last: 0, delta: 0, resetTime: 200}, T = 0, Ra = 0, Q = 0, Ha = 0, P, Ba;
        I || (f = F[0]);
        d.initialized = 0;
        d.frame = f;
        d.slidee = u[0];
        d.pos = e;
        d.rel = g;
        d.items = p;
        d.pages = w;
        d.isPaused = 0;
        d.options = c;
        d.dragging = a;
        d.reload = ba;
        d.getPos = function (b) {
            if (B)return b = oa(b), -1 !== b ? p[b] : !1;
            var a = u.find(b).eq(0);
            return a[0] ?
                (b = c.horizontal ? a.offset().left - u.offset().left : a.offset().top - u.offset().top, a = a[c.horizontal ? "outerWidth" : "outerHeight"](), {
                    start: b,
                    center: b - y / 2 + a / 2,
                    end: b - y + a,
                    size: a
                }) : !1
        };
        d.moveBy = function (b) {
            x.speed = b;
            !a.init && (x.speed && e.cur !== (0 < x.speed ? e.end : e.start)) && (x.lastTime = +new Date, x.startPos = e.cur, La("button"), a.init = 1, z("moveStart"), ja(Ha), Ga())
        };
        d.stop = function () {
            "button" === a.source && (a.init = 0, a.released = 1)
        };
        d.prev = function () {
            d.activate(g.activeItem - 1)
        };
        d.next = function () {
            d.activate(g.activeItem +
            1)
        };
        d.prevPage = function () {
            d.activatePage(g.activePage - 1)
        };
        d.nextPage = function () {
            d.activatePage(g.activePage + 1)
        };
        d.slideBy = function (b, a) {
            if (b)if (B)d[L ? "toCenter" : "toStart"](v((L ? g.centerItem : g.firstItem) + c.scrollBy * b, 0, p.length)); else J(e.dest + b, a)
        };
        d.slideTo = function (b, a) {
            J(b, a)
        };
        d.toStart = function (b, a) {
            wa("start", b, a)
        };
        d.toEnd = function (b, a) {
            wa("end", b, a)
        };
        d.toCenter = function (b, a) {
            wa("center", b, a)
        };
        d.getIndex = oa;
        d.activate = function (b, e) {
            var f = na(b);
            c.smart && !1 !== f && (L ? d.toCenter(f, e) : f >= g.lastItem ?
                d.toStart(f, e) : f <= g.firstItem ? d.toEnd(f, e) : a.released && !d.isPaused && d.resume())
        };
        d.activatePage = function (b, a) {
            O(b) && J(w[v(b, 0, w.length - 1)], a)
        };
        d.resume = function (b) {
            !c.cycleBy || (!c.cycleInterval || "items" === c.cycleBy && !p[0] || b < d.isPaused) || (d.isPaused = 0, Q ? Q = clearTimeout(Q) : z("resume"), Q = setTimeout(function () {
                z("cycle");
                switch (c.cycleBy) {
                    case "items":
                        d.activate(g.activeItem >= p.length - 1 ? 0 : g.activeItem + 1);
                        break;
                    case "pages":
                        d.activatePage(g.activePage >= w.length - 1 ? 0 : g.activePage + 1)
                }
            }, c.cycleInterval))
        };
        d.pause = function (b) {
            b < d.isPaused || (d.isPaused = b || 100, Q && (Q = clearTimeout(Q), z("pause")))
        };
        d.toggle = function () {
            d[Q ? "pause" : "resume"]()
        };
        d.set = function (b, a) {
            l.isPlainObject(b) ? l.extend(c, b) : c.hasOwnProperty(b) && (c[b] = a)
        };
        d.add = function (b, a) {
            var c = l(b);
            B ? (null != a && p[0] ? p.length && c.insertBefore(p[a].el) : c.appendTo(u), a <= g.activeItem && (q.active = g.activeItem += c.length)) : u.append(c);
            ba()
        };
        d.remove = function (b) {
            if (B) {
                if (b = xa(b), -1 < b) {
                    G.eq(b).remove();
                    var a = b === g.activeItem;
                    b < g.activeItem && (q.active = --g.activeItem);
                    ba();
                    a && (q.active = null, d.activate(g.activeItem))
                }
            } else l(b).remove(), ba()
        };
        d.moveAfter = function (b, a) {
            Ia(b, a, 1)
        };
        d.moveBefore = function (b, a) {
            Ia(b, a)
        };
        d.on = function (b, a) {
            if ("object" === ia(b))for (var c in b) {
                if (b.hasOwnProperty(c))d.on(c, b[c])
            } else if ("function" === ia(a)) {
                c = b.split(" ");
                for (var e = 0, f = c.length; e < f; e++)H[c[e]] = H[c[e]] || [], -1 === Ja(c[e], a) && H[c[e]].push(a)
            } else if ("array" === ia(a))for (c = 0, e = a.length; c < e; c++)d.on(b, a[c])
        };
        d.one = function (b, a) {
            function c() {
                a.apply(d, arguments);
                d.off(b, c)
            }

            d.on(b,
                c)
        };
        d.off = function (a, c) {
            if (c instanceof Array)for (var e = 0, f = c.length; e < f; e++)d.off(a, c[e]); else for (var e = a.split(" "), f = 0, g = e.length; f < g; f++)if (H[e[f]] = H[e[f]] || [], null == c)H[e[f]].length = 0; else {
                var k = Ja(e[f], c);
                -1 !== k && H[e[f]].splice(k, 1)
            }
        };
        d.destroy = function () {
            $.add(Ta).add(C).add(K).add(U).add(X).add(ca).add(Y).add(Z).add(V).add(W).unbind("." + m);
            Y.add(Z).add(V).add(W).removeClass(c.disabledClass);
            G && G.eq(g.activeItem).removeClass(c.activeClass);
            U.empty();
            I || (F.unbind("." + m), u.add(C).css(D || (c.horizontal ?
                "left" : "top"), D ? "none" : 0), l.removeData(f, m));
            p.length = w.length = 0;
            q = {};
            d.initialized = 0;
            return d
        };
        d.init = function () {
            if (!d.initialized) {
                d.on(Xa);
                var a = C;
                I || (a = a.add(u), F.css("overflow", "hidden"), D || "static" !== F.css("position") || F.css("position", "relative"));
                D ? ha && a.css(D, ha) : ("static" === K.css("position") && K.css("position", "relative"), a.css({position: "absolute"}));
                if (c.forward)X.on(Ua, da);
                if (c.backward)ca.on(Ua, da);
                if (c.prev)Y.on(aa, da);
                if (c.next)Z.on(aa, da);
                if (c.prevPage)V.on(aa, da);
                if (c.nextPage)W.on(aa,
                    da);
                Ta.on(Va, bb);
                if (K[0])K.on(aa, cb);
                if (B && c.activateOn)F.on(c.activateOn + "." + m, "*", eb);
                if (U[0] && c.activatePageOn)U.on(c.activatePageOn + "." + m, "*", fb);
                hb.on(Wa, {source: "slidee"}, Ma);
                if (C)C.on(Wa, {source: "handle"}, Ma);
                $.bind("keydown." + m, db);
                I || (F.on("mouseenter." + m + " mouseleave." + m, gb), F.on("scroll." + m, ib));
                ba();
                if (c.cycleBy && !I)d[c.startPaused ? "pause" : "resume"]();
                d.initialized = 1;
                return d
            }
        }
    }

    function ia(f) {
        return null == f ? String(f) : "object" === typeof f || "function" === typeof f ? Object.prototype.toString.call(f).match(/\s([a-z]+)/i)[1].toLowerCase() ||
        "object" : typeof f
    }

    function M(f, k) {
        f.preventDefault();
        k && f.stopPropagation()
    }

    function ya(f) {
        M(f, 1);
        l(this).off(f.type, ya)
    }

    function ib() {
        this.scrollTop = this.scrollLeft = 0
    }

    function O(f) {
        return !isNaN(parseFloat(f)) && isFinite(f)
    }

    function ma(f, k) {
        return 0 | Math.round(String(f.css(k)).replace(/[^\-0-9.]/g, ""))
    }

    function v(f, k, l) {
        return f < k ? k : f > l ? l : f
    }

    var m = "sly", ja = A.cancelAnimationFrame || A.cancelRequestAnimationFrame, ga = A.requestAnimationFrame, D, ha, $ = l(document), Wa = "touchstart." + m + " mousedown." + m, Pa = "mousemove." +
        m + " mouseup." + m, Oa = "touchmove." + m + " touchend." + m, Va = (document.implementation.hasFeature("Event.wheel", "3.0") ? "wheel." : "mousewheel.") + m, aa = "click." + m, Ua = "mousedown." + m, Na = ["INPUT", "SELECT", "BUTTON", "TEXTAREA"], Ca = [], za, Aa = 0;
    $.on(Va, function () {
        Aa = +new Date
    });
    (function (f) {
        for (var k = ["moz", "webkit", "o"], l = 0, m = 0, E = k.length; m < E && !ja; ++m)ga = (ja = f[k[m] + "CancelAnimationFrame"] || f[k[m] + "CancelRequestAnimationFrame"]) && f[k[m] + "RequestAnimationFrame"];
        ja || (ga = function (k) {
            var m = +new Date, E = Math.max(0, 16 - (m - l));
            l = m + E;
            return f.setTimeout(function () {
                k(m + E)
            }, E)
        }, ja = function (f) {
            clearTimeout(f)
        })
    })(window);
    (function () {
        function f(f) {
            for (var m = 0, v = k.length; m < v; m++) {
                var s = k[m] ? k[m] + f.charAt(0).toUpperCase() + f.slice(1) : f;
                if (null != l.style[s])return s
            }
        }

        var k = ["", "webkit", "moz", "ms", "o"], l = document.createElement("div");
        D = f("transform");
        ha = f("perspective") ? "translateZ(0) " : ""
    })();
    A.Sly = ka;
    l.fn.sly = function (f, k) {
        var v, D;
        if (!l.isPlainObject(f)) {
            if ("string" === ia(f) || !1 === f)v = !1 === f ? "destroy" : f, D = Array.prototype.slice.call(arguments,
                1);
            f = {}
        }
        return this.each(function (E, A) {
            var s = l.data(A, m);
            s || v ? s && v && s[v] && s[v].apply(s, D) : l.data(A, m, (new ka(A, f, k)).init())
        })
    };
    ka.defaults = {
        horizontal: 0,
        itemNav: null,
        itemSelector: null,
        smart: 0,
        activateOn: null,
        activateMiddle: 0,
        scrollSource: null,
        scrollBy: 0,
        scrollHijack: 300,
        dragSource: null,
        mouseDragging: 0,
        touchDragging: 0,
        releaseSwing: 0,
        swingSpeed: 0.2,
        elasticBounds: 0,
        interactive: null,
        scrollBar: null,
        dragHandle: 0,
        dynamicHandle: 0,
        minHandleSize: 50,
        clickBar: 0,
        syncSpeed: 0.5,
        pagesBar: null,
        activatePageOn: null,
        pageBuilder: function (f) {
            return "<li>" + (f + 1) + "</li>"
        },
        forward: null,
        backward: null,
        prev: null,
        next: null,
        prevPage: null,
        nextPage: null,
        cycleBy: null,
        cycleInterval: 5E3,
        pauseOnHover: 0,
        startPaused: 0,
        moveBy: 300,
        speed: 0,
        easing: "swing",
        startAt: 0,
        keyboardNavBy: null,
        draggedClass: "dragged",
        activeClass: "active",
        disabledClass: "disabled"
    }
})(jQuery, window);