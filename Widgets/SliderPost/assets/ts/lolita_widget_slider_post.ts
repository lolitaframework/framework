/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class WidgetSliderPost {

        /**
         * Options slider
         * @type {any}
         */
        options: any = {
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

            // TOUCH
            touchEnabled: false,
            swipeThreshold: 50,
            oneToOneTouch: true,
            preventDefaultSwipeX: true,
            preventDefaultSwipeY: false,

            // KEYBOARD
            keyboardEnabled: false,

            // PAGER
            pager: false,
            pagerType: 'full',
            pagerShortSeparator: ' / ',
            pagerSelector: null,
            buildPager: null,
            pagerCustom: null,

            // CONTROLS
            controls: false,
            nextText: 'Next',
            prevText: 'Prev',
            autoControls: false,
            startText: 'Start',
            stopText: 'Stop',
            autoControlsCombine: false,
            autoControlsSelector: null,

            // AUTO
            auto: true,
            pause: parseInt((<any>window).lf_widget_slider_post_l10n.speed),
            autoStart: true,
            autoDirection: 'next',
            autoHover: false,
            autoDelay: 0,
            autoSlideForOnePage: false,

            // CAROUSEL
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 0,
            slideWidth: 0
        };

        /**
         * Slider
         * @type {any}
         */
        sliders: any = [];

        /**
         * Slider widget
         */
        constructor() {
            var me : any = this;
            jQuery('.lf_slider_post').find('.lf_slider_post__bx_slider').each(
                (index, obj) => this.each(index, obj)
            );

            jQuery('.lf_slider_post').find('.lf_slider_post__pause').on(
                'click', 
                function() {
                if (!jQuery(this).hasClass('active')) {
                    me.sliders[jQuery(this).index()].stopAuto();
                    jQuery(this).addClass('active');
                } else {
                    me.sliders[jQuery(this).index()].startAuto();
                    jQuery(this).removeClass('active');
                }
            });
        }

        /**
         * jQuery each
         * @param {number} index object index.
         * @param {any}    obj   object.
         */
        each(index:number, obj:any) {
            this.sliders.push(jQuery(obj).bxSlider(this.options));
        }
    }
    (<any>window).LolitaFramework.slider_widget_post = new WidgetSliderPost();
}