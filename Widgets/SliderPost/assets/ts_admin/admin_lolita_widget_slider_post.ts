/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class WidgetSliderPost {

        /**
         * Gallery control class constructor
         */
        constructor() {
            jQuery(document).on(
                'change',
                '.lf_slide_post_type',
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
            jQuery('.lf_slide_post_type').each(
                function() {
                    me.toggleTaxonomies(
                        jQuery(this).closest('.widget'),
                        jQuery(this).val()
                    );
                }
            );
        }

        /**
         * Change event
         * @param {any} e event.
         */
        change(e: any) {
            this.toggleTaxonomies(
                jQuery(e.currentTarget).closest('.widget'),
                jQuery(e.currentTarget).val()
            );
        }

        /**
         * Toggle taxonomies
         * @param {any}    $widget    jQuery object.
         * @param {string} post_type post_type slug.
         */
        toggleTaxonomies($widget: any, post_type: string) {
            $widget.find('.lf_slider_post_taxonomy').each(
                function() {
                    if(!jQuery(this).hasClass('lf_slider_post_type__' + post_type)) {
                        jQuery(this).parent().hide();
                    } else {
                        jQuery(this).parent().show();
                    }
                }
            );
        }
    }

    (<any>window).LolitaFramework.widget_slider_post = new WidgetSliderPost();
}