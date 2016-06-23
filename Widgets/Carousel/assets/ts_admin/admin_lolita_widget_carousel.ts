/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class WidgetCarousel {

        /**
         * Gallery control class constructor
         */
        constructor() {
            jQuery(document).on(
                'change',
                '.carousel_type-class',
                (e: any) => this.change(e)
            );

            jQuery(document).on(
                'widget-updated',
                () => this.update()
            );

            jQuery(document).on(
                'widget-added',
                () => this.update()
            );

            this.update();
        }

        /**
         * Update
         */
        update() {
            var me = this;
            jQuery('.carousel_type-class').each(
                function() {
                    me.toggleStyles(
                        jQuery(this).closest('.widget'),
                        'style_' + jQuery(this).val()
                    );
                }
            );
        }

        /**
         * Change event
         * @param {any} e event.
         */
        change(e: any) {
            this.toggleStyles(
                jQuery(e.currentTarget).closest('.widget'),
                'style_' + jQuery(e.currentTarget).val()
            );
        }

        /**
         * Toggle style type
         * @param {any}    $widget    jQuery object.
         * @param {string} small_name small repeater name.
         */
        toggleStyles($widget: any, small_name: string) {
            $widget.find('.lolita-repeater-container').each(
                function() {
                    if (jQuery(this).data('smallName') == small_name) {
                        jQuery(this).closest('.widget_control_row').show();
                    } else {
                        jQuery(this).closest('.widget_control_row').hide();
                    }
                }
            );
        }
    }

    (<any>window).LolitaFramework.widget_carousel = new WidgetCarousel();
}