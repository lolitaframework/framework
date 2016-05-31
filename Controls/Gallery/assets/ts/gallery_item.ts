/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class GalleryItem {

        /**
         * GalleryItem id
         * @type {string}
         */
        value:string = '';

        /**
         * GalleryItem source
         * @type {string}
         */
        src: string = '';

        /**
         * DOM element
         * @type {any}
         */
        el: any = null;

        /**
         * jQuery object
         * @type {any}
         */
        $el: any = null;

        /**
         * Underscore temlate object
         * @type {any}
         */
        template: any = null;

        /**
         * GalleryItem control class constructor
         */
        constructor(value: string, src: string) {
            this.value = value;
            this.src = src;
            this.template = (<any>window)._.template(jQuery('#lolita-collection-item-template').html());
        }

        /**
         * Render gallery item
         */
        render() {
            this.el = this.template({
                value: this.value,
                image: this.src
            });

            this.$el = jQuery(this.el);
            return this;
        }
    }
}