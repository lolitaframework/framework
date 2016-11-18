/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class Repeaters {

        /**
         * Gallery selector
         * @type {string}
         */
        repeater_selector: string = '.lolita-repeater-container';

        /**
         * Repeaters collection
         * @type {any}
         */
        public collection: any = [];

        /**
         * Repeaters controls
         */
        constructor() {

            this.update();

            jQuery(document).on(
                'widget-updated',
                () => this.widgetUpdate()
            );

            jQuery(document).on(
                'widget-added',
                () => this.widgetUpdate()
            );

            jQuery(document).on(
                'lolita-repeater-row-added',
                () => this.widgetUpdate()
            );
        }

        /**
         * Widget update
         */
        widgetUpdate() {
            this.update();
        }

        /**
         * Rebind events
         */
        update() {
            var me: any = this;
            jQuery(this.repeater_selector).each(
                function() {
                    me.collection[jQuery(this).attr('id')] = new Repeater(
                        '#' + jQuery(this).attr('id')
                    );
                }
            );
        }
    }
    (<any>window).LolitaFramework.repeaters = new Repeaters();
}