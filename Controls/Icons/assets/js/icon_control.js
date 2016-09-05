var LolitaFramework;
(function (LolitaFramework) {
    var IconControl = (function () {
        function IconControl() {
            var _this = this;
            this.timer_id = 0;
            this.animation_speed = 0;
            jQuery(document).on('click', '.lf_icon_packs ul li a', function (e) { return _this.iconClick(e); });
            jQuery(document).on('keyup', '.lf_icons_wrapper input', function (e) { return _this.change(e); });
            jQuery(document).on('click', function (e) { return _this.leaveFocus(e); });
            jQuery(document).on('keydown', '.lf_icon_packs', function (e) { return _this.exit(e); });
            jQuery(document).on('widget-updated', function () { return _this.update(); });
            jQuery(document).on('widget-added', function () { return _this.update(); });
            this.update();
        }
        IconControl.prototype.update = function () {
            var _this = this;
            jQuery('.lf_icons_wrapper input').each(function (index, obj) { return _this.setIcon(jQuery(obj)); });
        };
        IconControl.prototype.exit = function (e) {
            var me = this;
            if (27 === e.keyCode) {
                jQuery('.lf_icon_packs').each(function () {
                    me.packHide(jQuery(this));
                });
            }
        };
        IconControl.prototype.setIcon = function ($input) {
            $input.parent().find('.lf_icons_control_icon i').attr('class', $input.val());
        };
        IconControl.prototype.packShow = function ($pack) {
            if ('visible' === $pack.css('display')) {
                return true;
            }
            if (true === this.isIntoView($pack.parent().find('input'))) {
                $pack.css({ "top": "100%", "bottom": "inherit" });
            }
            else {
                $pack.css({ "bottom": "100%", "top": "inherit" });
            }
            $pack.show(this.animation_speed);
        };
        IconControl.prototype.packHide = function ($pack) {
            $pack.hide(this.animation_speed);
        };
        IconControl.prototype.leaveFocus = function (e) {
            var $el, me;
            $el = jQuery(e.target);
            me = this;
            if (!$el.closest('.lf_icons_wrapper').length) {
                jQuery('.lf_icon_packs').each(function () {
                    me.packHide(jQuery(this));
                });
            }
        };
        IconControl.prototype.iconClick = function (e) {
            e.preventDefault();
            jQuery(e.currentTarget).closest('.lf_icons_wrapper').find('input').val(jQuery(e.currentTarget).attr('href').replace('#', ''));
            this.setIcon(jQuery(e.currentTarget).closest('.lf_icons_wrapper').find('input'));
            jQuery(e.currentTarget).closest('.lf_icons_wrapper').find('input').trigger('change');
        };
        IconControl.prototype.change = function (e) {
            var _this = this;
            var $wrapper;
            clearTimeout(this.timer_id);
            $wrapper = jQuery(e.target.parentNode);
            this.timer_id = setTimeout(function () { return _this.filter(e.target.value, $wrapper); }, 100);
        };
        IconControl.prototype.filter = function (str, $wrapper) {
            var _this = this;
            var count = 0;
            this.setIcon($wrapper.find('input'));
            $wrapper.find('.lf_icon_packs').fadeIn(this.animation_speed);
            $wrapper.find('ul li a').each(function (e, me) { return count += _this.filter_object(e, jQuery(me), str); });
            if (count) {
                this.packShow($wrapper.find('.lf_icon_packs'));
            }
            else {
                this.packHide($wrapper.find('.lf_icon_packs'));
            }
        };
        IconControl.prototype.filter_object = function (i, $me, val) {
            var rx, match;
            rx = new RegExp('.*?' + val + '.*?');
            match = $me.attr('href').match(rx);
            if (null === match) {
                $me.parent().fadeOut(this.animation_speed);
                return false;
            }
            else {
                $me.parent().fadeIn(this.animation_speed);
                return true;
            }
        };
        IconControl.prototype.isIntoView = function ($el) {
            var docViewTop = jQuery(window).scrollTop();
            var docViewBottom = docViewTop + jQuery(window).height();
            var elemTop = $el.offset().top;
            var elemBottom = elemTop + 300;
            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        };
        return IconControl;
    }());
    LolitaFramework.IconControl = IconControl;
    window.LolitaFramework.icon_control = new IconControl();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=icon_control.js.map