var LolitaFramework;
(function (LolitaFramework) {
    var SubscribeForm = (function () {
        function SubscribeForm() {
            var _this = this;
            this.css_loader = window.LolitaFramework.css_loader;
            this.data = window.lolita_widget_subscribe_form;
            this.$widget = '';
            this.$success_message = '';
            this.$error_message = '';
            this.$form = '';
            this.$input = '';
            this.type = 'default';
            this.mailchimp_api_key = '';
            this.mailchimp_list_id = '';
            this.$widget = jQuery('.lolita-subscribe');
            this.$success_message = jQuery('.lolita-subscribe-success-message');
            this.$error_message = jQuery('.lolita-subscribe-error-message');
            this.$form = jQuery('.lolita-subscribe-form');
            this.$input = this.$form.find('.lolita-subscribe-email');
            this.type = this.$form.find('.lolita-subscribe-type').val();
            this.mailchimp_api_key = this.$form.find('.lolita-subscribe-mailchimp-api-key').val();
            this.mailchimp_list_id = this.$form.find('.lolita-subscribe-mailchimp-list-id').val();
            this.$form.on('submit', function (e) { return _this.submit(e); });
        }
        SubscribeForm.prototype.submit = function (e) {
            var _this = this;
            if (undefined !== this.css_loader && undefined !== this.css_loader.show) {
                this.css_loader.show();
            }
            this.ajax({
                action: 'lolita_subscribe',
                value: this.$input.val(),
                type: this.type,
                mailchimp_api_key: this.mailchimp_api_key,
                mailchimp_list_id: this.mailchimp_list_id
            }, function (response) { return _this.done(response); }, function (response) { return _this.fail(response); }, function (response) { return _this.always(response); });
            e.preventDefault();
        };
        SubscribeForm.prototype.done = function (response) {
            this.$input.val('');
            this.hide();
            this.$success_message.show();
        };
        SubscribeForm.prototype.fail = function (response) {
            this.hide();
            this.$error_message.show();
        };
        SubscribeForm.prototype.hide = function () {
            this.$widget.hide();
            this.$success_message.hide();
            this.$error_message.hide();
        };
        SubscribeForm.prototype.always = function (response) {
            if (undefined !== this.css_loader && undefined !== this.css_loader.hide) {
                this.css_loader.hide();
            }
        };
        SubscribeForm.prototype.ajax = function (data, done, fail, always) {
            data.security = this.data.security;
            var request = jQuery.ajax({
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
        };
        return SubscribeForm;
    }());
    window.LolitaFramework.subscribe_form = new SubscribeForm();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=lolita_widget_subscribe_form.js.map