var LolitaFramework;
(function (LolitaFramework) {
    var WidgetCarousel = (function () {
        function WidgetCarousel() {
            var _this = this;
            jQuery(document).on('change', '.carousel_type-class', function (e) { return _this.change(e); });
            jQuery(document).on('widget-updated', function () { return _this.update(); });
            jQuery(document).on('widget-added', function () { return _this.update(); });
            this.update();
        }
        WidgetCarousel.prototype.update = function () {
            var me = this;
            jQuery('.carousel_type-class').each(function () {
                me.toggleStyles(jQuery(this).closest('.widget'), 'style_' + jQuery(this).val());
            });
        };
        WidgetCarousel.prototype.change = function (e) {
            this.toggleStyles(jQuery(e.currentTarget).closest('.widget'), 'style_' + jQuery(e.currentTarget).val());
        };
        WidgetCarousel.prototype.toggleStyles = function ($widget, small_name) {
            $widget.find('.lolita-repeater-container').each(function () {
                if (jQuery(this).data('smallName') == small_name) {
                    jQuery(this).closest('.widget_control_row').show();
                }
                else {
                    jQuery(this).closest('.widget_control_row').hide();
                }
            });
        };
        return WidgetCarousel;
    }());
    LolitaFramework.WidgetCarousel = WidgetCarousel;
    window.LolitaFramework.widget_carousel = new WidgetCarousel();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=admin_lolita_widget_carousel.js.map