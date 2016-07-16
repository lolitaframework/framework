/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class WidgetCarousel {

        /**
         * All widget carousels
         * @type {array}
         */
        collection: any = [];
    }
    (<any>window).LolitaFramework.widget_carousel = new WidgetCarousel();
}