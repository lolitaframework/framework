var LolitaFramework;
(function (LolitaFramework) {
    var CustomizeRepeaters = (function () {
        function CustomizeRepeaters() {
            var _this = this;
            this.api = window.wp.customize;
            this.repeaters = window.LolitaFramework.repeaters;
            this.collection = [];
            var api, me;
            api = this.api;
            me = this;
            this.repeaters.update();
            this.api.bind('ready', function () { return _this.repeaters.widgetUpdate(); });
            this.api.RepeaterControl = this.api.Control.extend({
                ready: function () {
                    var control, element, events, type;
                    control = this;
                    control.container.find('[data-customize-setting-link]').each(function (index, data) {
                        element = jQuery(this);
                        events = '';
                        if (element.is('input, select, textarea')) {
                            events += 'change';
                            if (element.is('input')) {
                                type = element.prop('type');
                                if ('text' === type || 'password' === type) {
                                    events += ' keyup';
                                }
                                else if ('range' === type) {
                                    events += ' input propertychange';
                                }
                            }
                            else if (element.is('textarea')) {
                                events += ' keyup';
                            }
                        }
                        jQuery(document).on(events, element, function (e) { return me.update(e, control); });
                    });
                }
            });
            jQuery.extend(this.api.controlConstructor, {
                'repeater': this.api.RepeaterControl
            });
        }
        CustomizeRepeaters.prototype.update = function (e, control) {
            var obj, keys, i, k, new_obj;
            obj = window.jQuery.deparam(control.container.find(':input').serialize());
            obj = jQuery.extend({}, obj[control.id]);
            this.api.instance(control.id).set(obj);
        };
        return CustomizeRepeaters;
    }());
    LolitaFramework.CustomizeRepeaters = CustomizeRepeaters;
    window.LolitaFramework.repeaters = new CustomizeRepeaters();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=customize_repeater.js.map