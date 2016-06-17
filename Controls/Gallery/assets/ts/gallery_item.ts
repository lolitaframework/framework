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
         * Gallery Item name
         * @type {string}
         */
        name: string = '';

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
        constructor(value: string, src: string, name:string, template: any) {
            this.value = value;
            this.src = src;
            this.name = name;
            this.template = template;
        }

        /**
         * Render gallery item
         */
        render() {
            this.el = this.template({
                value: this.value,
                image: this.src,
                name: this.name
            });

            this.$el = jQuery(this.el);
            return this;
        }

        /**
         * Set widget number
         * @param {number} index widget index.
         */
        setWidgetNumber(index:string) {
            var new_name:string = this.$el.find('input').attr('name');
            new_name = new_name.replace(/__i__/, index);
            this.$el.find('input').attr('name', new_name);
            this.el = this.$el[0].outerHTML;
            return this;
        }
    }
}