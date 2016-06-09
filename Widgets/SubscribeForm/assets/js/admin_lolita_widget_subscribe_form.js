var LolitaFramework;
(function (LolitaFramework) {
    var SubscribeFormAdmin = (function () {
        function SubscribeFormAdmin() {
            jQuery('.mailchimp_api_key-class').each(function () {
                if ('default' === jQuery(this).parent().parent().find('.type-class').val()) {
                    jQuery(this).parent().hide();
                }
            });
            jQuery('.mailchimp_list_id-class').each(function () {
                if ('default' === jQuery(this).parent().parent().find('.type-class').val()) {
                    jQuery(this).parent().hide();
                }
            });
            jQuery(document).on('change', 'select.type-class', function () {
                console.log('default' === jQuery(this).val(), jQuery(this).val());
                if ('default' === jQuery(this).val()) {
                    jQuery(this).parent().parent().find('.mailchimp_api_key-class').parent().hide();
                    jQuery(this).parent().parent().find('.mailchimp_list_id-class').parent().hide();
                }
                else {
                    jQuery(this).parent().parent().find('.mailchimp_api_key-class').parent().show();
                    jQuery(this).parent().parent().find('.mailchimp_list_id-class').parent().show();
                }
            });
        }
        return SubscribeFormAdmin;
    }());
    window.LolitaFramework.subscribe_form_admin = new SubscribeFormAdmin();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=admin_lolita_widget_subscribe_form.js.map