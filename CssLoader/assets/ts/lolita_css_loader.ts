/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    class CssLoader {
        /**
         * Body
         * @type {any}
         */
        $body: any = null;
        $template_name: any = null;

        /**
         * CssLoader control class constructor
         */
        constructor() {
            this.$body = jQuery('body');
        }

        /**
         * Show Loader
         */
        show(n: number = 1) {

            var $selected_template: string = 'lolita_css_loader_tmp_' + n;

            if ($selected_template == this.$template_name) {
                console.log('%c This template is already displayed', 'color: red');
                return;
            }

            var $template: any = jQuery('#' + $selected_template).html();
            $template = jQuery($template).addClass($selected_template);

            if ($template.length == 0) {
                console.log('%c There is no template with specified index', 'color: red');
                return;
            }

            if (this.$template_name != null) {
                this.$body.find('.' + this.$template_name).remove();
            }

            this.$body.prepend(jQuery('<div class="lolita_css_loader_bg"></div>').addClass($selected_template));
            this.$body.prepend($template); 
            this.$template_name = $selected_template;
        }

        /**
         * Hide Loader
         */
        hide() {
            if (this.$template_name != null) {
                this.$body.find('.' + this.$template_name).remove();
                this.$template_name = null;
            }
        }

    }

    (<any>window).LolitaFramework.css_loader = new CssLoader();
}