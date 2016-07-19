/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    class SubscribeForm {

        /**
         * CssLoader module
         * @type {any}
         */
        css_loader: any = (<any>window).LolitaFramework.css_loader;

        /**
         * Preset data
         * @type {any}
         */
        data: any = (<any>window).lolita_widget_subscribe_form;

        /**
         * jQuery object
         * @type {any}
         */
        $widget: any = '';

        /**
         * jQuery object
         * @type {any}
         */
        $success_message: any = '';

        /**
         * jQuery object
         * @type {any}
         */
        $error_message: any = '';

        /**
         * jQuery object
         * @type {any}
         */
        $form: any = '';

        /**
         * Jquery object
         * @type {any}
         */
        $input: any = '';

        /**
         * Subscribe type
         * @type {string}
         */
        type: string = 'default';

        /**
         * Mail Chimp API key
         * @type {string}
         */
        mailchimp_api_key: string = '';

        /**
         * Mail Chimp List id
         * @type {string}
         */
        mailchimp_list_id: string = '';

        /**
         * SubscribeForm control class constructor
         */
        constructor() {
            this.$widget           = jQuery('.lf_subscribe');
            this.$success_message  = jQuery('.lolita-subscribe-success-message');
            this.$error_message    = jQuery('.lolita-subscribe-error-message');
            this.$form             = jQuery('.lolita-subscribe-form');
            this.$input            = this.$form.find('.lolita-subscribe-email');
            this.type              = this.$form.find('.lolita-subscribe-type').val();
            this.mailchimp_api_key = this.$form.find('.lolita-subscribe-mailchimp-api-key').val();
            this.mailchimp_list_id = this.$form.find('.lolita-subscribe-mailchimp-list-id').val();

            this.$form.on(
                'submit',
                (e: any) => this.submit(e)
            );
        }

        /**
         * Submite
         * @param {any} e [description]
         */
        submit(e:any) {
            if (undefined !== this.css_loader && undefined !== this.css_loader.show) {
                this.css_loader.show(5);
            }
            this.ajax(
                {
                    action: 'lolita_subscribe',
                    value : this.$input.val(),
                    type : this.type,
                    mailchimp_api_key : this.mailchimp_api_key,
                    mailchimp_list_id : this.mailchimp_list_id
                },
                (response: any) => this.done(response),
                (response: any) => this.fail(response),
                (response: any) => this.always(response)
            );
            e.preventDefault();
        }

        /**
         * If ajax is success
         * @param {object} response ajax response
         */
        done(response:any) {
            this.$input.val('');
            this.hide();
            if(true === response.success) {
                this.$success_message.show();
            } else {
                this.$error_message.show();
            }
        }

        /**
         * If ajax has error
         * @param {object} response ajax response
         */
        fail(response: any) {
            this.hide();
            this.$error_message.show();
        }

        /**
         * Hide all elements
         */
        hide() {
            this.$widget.hide();
            this.$success_message.hide();
            this.$error_message.hide();
        }

        /**
         * Always
         * @param {object} response ajax response
         */
        always(response: any) {
            if (undefined !== this.css_loader && undefined !== this.css_loader.hide) {
                this.css_loader.hide();
            }
        }

        /**
         * Simply ajax requrest
         * @param {any} data   to send.
         * @param {any} done   function.
         * @param {any} fail   function.
         * @param {any} always function.
         */
        ajax(data: any, done: any, fail: any, always: any) {
            data.security = this.data.security;
            var request: any = jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: this.data.ajax_url,
                data: data
            });

            if (undefined !== done) {
                request.done(done);
            }

            if (undefined !== fail) {
                request.fail(fail);
            }

            if (undefined !== always) {
                request.always(always);
            }
        }
    }

    (<any>window).LolitaFramework.subscribe_form = new SubscribeForm();
}