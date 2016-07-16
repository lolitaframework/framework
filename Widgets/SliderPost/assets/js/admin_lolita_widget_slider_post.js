var LolitaFramework;
(function (LolitaFramework) {
    var WidgetSliderPost = (function () {
        function WidgetSliderPost() {
            var _this = this;
            jQuery(document).on('change', '.lf_slide_post_type', function (e) { return _this.change(e); });
            jQuery(document).on('widget-updated', function () { return _this.update(); });
            jQuery(document).on('widget-added', function () { return _this.update(); });
            this.update();
        }
        WidgetSliderPost.prototype.update = function () {
            var me = this;
            jQuery('.lf_slide_post_type').each(function () {
                me.toggleTaxonomies(jQuery(this).closest('.widget'), jQuery(this).val());
            });
        };
        WidgetSliderPost.prototype.change = function (e) {
            this.toggleTaxonomies(jQuery(e.currentTarget).closest('.widget'), jQuery(e.currentTarget).val());
        };
        WidgetSliderPost.prototype.toggleTaxonomies = function ($widget, post_type) {
            $widget.find('.lf_slider_post_taxonomy').each(function () {
                if (!jQuery(this).hasClass('lf_slider_post_type__' + post_type)) {
                    jQuery(this).parent().hide();
                }
                else {
                    jQuery(this).parent().show();
                }
            });
        };
        return WidgetSliderPost;
    }());
    LolitaFramework.WidgetSliderPost = WidgetSliderPost;
    window.LolitaFramework.widget_slider_post = new WidgetSliderPost();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=admin_lolita_widget_slider_post.js.map