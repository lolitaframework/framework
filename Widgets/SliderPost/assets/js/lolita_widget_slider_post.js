var LolitaFramework;
(function (LolitaFramework) {
    var WidgetSliderPost = (function () {
        function WidgetSliderPost() {
            var _this = this;
            this.options = {
                mode: 'fade',
                slideSelector: '',
                infiniteLoop: true,
                hideControlOnEnd: false,
                speed: 500,
                easing: 'swing',
                slideMargin: 0,
                startSlide: 0,
                randomStart: false,
                captions: false,
                ticker: false,
                tickerHover: false,
                adaptiveHeight: false,
                adaptiveHeightSpeed: 500,
                video: false,
                useCSS: true,
                preloadImages: 'visible',
                responsive: true,
                slideZIndex: 50,
                wrapperClass: 'bx-wrapper',
                touchEnabled: false,
                swipeThreshold: 50,
                oneToOneTouch: true,
                preventDefaultSwipeX: true,
                preventDefaultSwipeY: false,
                keyboardEnabled: false,
                pager: false,
                pagerType: 'full',
                pagerShortSeparator: ' / ',
                pagerSelector: null,
                buildPager: null,
                pagerCustom: null,
                controls: false,
                nextText: 'Next',
                prevText: 'Prev',
                autoControls: false,
                startText: 'Start',
                stopText: 'Stop',
                autoControlsCombine: false,
                autoControlsSelector: null,
                auto: true,
                pause: parseInt(window.lf_widget_slider_post_l10n.speed),
                autoStart: true,
                autoDirection: 'next',
                autoHover: false,
                autoDelay: 0,
                autoSlideForOnePage: false,
                minSlides: 1,
                maxSlides: 1,
                moveSlides: 0,
                slideWidth: 0
            };
            this.sliders = [];
            var me = this;
            jQuery('.lf_slider_post').find('.lf_slider_post__bx_slider').each(function (index, obj) { return _this.each(index, obj); });
            jQuery('.lf_slider_post').find('.lf_slider_post__pause').on('click', function () {
                if (!jQuery(this).hasClass('active')) {
                    me.sliders[jQuery(this).index()].stopAuto();
                    jQuery(this).addClass('active');
                }
                else {
                    me.sliders[jQuery(this).index()].startAuto();
                    jQuery(this).removeClass('active');
                }
            });
        }
        WidgetSliderPost.prototype.each = function (index, obj) {
            this.sliders.push(jQuery(obj).bxSlider(this.options));
        };
        return WidgetSliderPost;
    }());
    LolitaFramework.WidgetSliderPost = WidgetSliderPost;
    window.LolitaFramework.slider_widget_post = new WidgetSliderPost();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=lolita_widget_slider_post.js.map