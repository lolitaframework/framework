var LolitaFramework;
(function (LolitaFramework) {
    var WidgetSlider = (function () {
        function WidgetSlider() {
            var _this = this;
            jQuery(document).on('change', '.slider_type-class', function (e) { return _this.change(e); });
            jQuery(document).on('widget-updated', function () { return _this.update(); });
            jQuery(document).on('widget-added', function () { return _this.update(); });
            this.update();
        }
        WidgetSlider.prototype.update = function () {
            var me = this;
            jQuery('.slider_type-class').each(function () {
                me.toggleStyles(jQuery(this).closest('.widget'), 'style_' + jQuery(this).val());
            });
        };
        WidgetSlider.prototype.change = function (e) {
            this.toggleStyles(jQuery(e.currentTarget).closest('.widget'), 'style_' + jQuery(e.currentTarget).val());
        };
        WidgetSlider.prototype.toggleStyles = function ($widget, small_name) {
            $widget.find('.lolita-repeater-container').each(function () {
                if (jQuery(this).data('smallName') == small_name) {
                    jQuery(this).closest('.widget_control_row').show();
                }
                else {
                    jQuery(this).closest('.widget_control_row').hide();
                }
            });
        };
        return WidgetSlider;
    }());
    LolitaFramework.WidgetSlider = WidgetSlider;
    window.LolitaFramework.widget_slider = new WidgetSlider();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=admin_lolita_widget_slider.js.map