var LolitaFramework;
(function (LolitaFramework) {
    var CssLoader = (function () {
        function CssLoader() {
            this.$body = null;
            this.$template_name = null;
            this.$body = jQuery('body');
        }
        CssLoader.prototype.show = function (n) {
            if (n === void 0) { n = 1; }
            var $selected_template = 'lolita_css_loader_tmp_' + n;
            if ($selected_template == this.$template_name) {
                console.log('%c This template is already displayed', 'color: red');
                return;
            }
            var $template = jQuery('#' + $selected_template).html();
            $template = jQuery($template).addClass($selected_template);
            if ($template.length == 0) {
                console.log('%c There is no template with specified index', 'color: red');
                return;
            }
            if (this.$template_name != null) {
                this.$body.find('.' + this.$template_name).remove();
            }
            this.$body.prepend(jQuery('<div class="lolita_css_loader_bg"></div>').addClass($selected_template));
            this.$body.prepend($template);
            this.$template_name = $selected_template;
        };
        CssLoader.prototype.hide = function () {
            if (this.$template_name != null) {
                this.$body.find('.' + this.$template_name).remove();
                this.$template_name = null;
            }
        };
        return CssLoader;
    }());
    window.LolitaFramework.css_loader = new CssLoader();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=lolita_css_loader.js.map