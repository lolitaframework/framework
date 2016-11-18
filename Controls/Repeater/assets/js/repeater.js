var LolitaFramework;
(function (LolitaFramework) {
    var Repeater = (function () {
        function Repeater(main_selector) {
            var _this = this;
            this.$el = null;
            this.$list = null;
            this.$add_button = null;
            this.$add_prev = null;
            this.$remove_button = null;
            this.template = null;
            this.template_html = null;
            this.$el = jQuery(main_selector);
            this.template = atob(this.$el.find('.underscore_template').html());
            this.$list = this.$el.find('.lolita-repeater-sortable');
            this.$add_button = this.$el.find('.lolita-repeater-main-add');
            this.$add_prev = this.$el.find('.lolita-repeater-add');
            this.$remove_button = this.$el.find('.lolita-repeater-remove');
            this.$add_button.one('click', function (e) { return _this.addAfter(e); });
            this.$add_prev.one('click', function (e) { return _this.addPrev(e); });
            this.$remove_button.one('click', function (e) { return _this.remove(e); });
            jQuery(document).on('lolita-repeater-row-added', function () { return _this.recountOrder(); });
            jQuery(document).on('lolita-repeater-row-removed', function () { return _this.recountOrder(); });
            this.sort();
        }
        Repeater.prototype.sort = function () {
            var me = this;
            this.$list.sortable({
                helper: function (e, ui) {
                    ui.children().each(function () {
                        jQuery(this).width(jQuery(this).width());
                    });
                    return ui;
                },
                forcePlaceholderSize: true,
                placeholder: 'lolita-ui-state-highlight',
                handle: '.lolita-repeater-order',
                update: function () {
                    me.recountOrder();
                }
            });
        };
        Repeater.prototype.remove = function (e) {
            if (this.$list.find('.lolita-repeater-row').length > 1) {
                jQuery(e.currentTarget).closest('.lolita-repeater-row').remove();
                jQuery(document).trigger('lolita-repeater-row-removed');
            }
        };
        Repeater.prototype.recountOrder = function () {
            var me = this;
            this.$list.find('.lolita-repeater-row').each(function (index) {
                var current_index = index + 1;
                jQuery(this).find('.lolita-repeater-order span').text(current_index);
                jQuery(this).find('input, textarea, select').each(function () {
                    jQuery(this).attr('name', me.reName(me.$el.data('name'), jQuery(this).attr('name'), current_index));
                    jQuery(this).attr('id', me.reID(me.$el.attr('id'), jQuery(this).attr('id'), current_index));
                });
                jQuery(this).find('[data-control]').each(function () {
                    jQuery(this).get(0).dataset['name'] = me.reName(me.$el.data('name'), jQuery(this).get(0).dataset['name'], current_index);
                    jQuery(this).attr('id', me.reID(me.$el.attr('id'), jQuery(this).attr('id'), current_index));
                });
            });
        };
        Repeater.prototype.reName = function (repeater_name, el_name, index) {
            var result_name;
            el_name = el_name.replace(repeater_name, '');
            el_name = el_name.replace(/^\[[0-9]*\]/, '[' + index + ']');
            result_name = repeater_name + el_name;
            return repeater_name + el_name;
        };
        Repeater.prototype.reID = function (repeater_id, el_id, index) {
            if (el_id === undefined) {
                return undefined;
            }
            el_id = el_id.replace(repeater_id, '');
            el_id = el_id.replace(/^_[0-9]*_/, '_' + index + '_');
            return repeater_id + el_id;
        };
        Repeater.prototype.addPrev = function (e) {
            e.preventDefault();
            var new_row = this.getNewRow();
            jQuery(e.srcElement).closest('.lolita-repeater-row').before(new_row);
            jQuery(document).trigger('lolita-repeater-row-added');
        };
        Repeater.prototype.addAfter = function (e) {
            e.preventDefault();
            var new_row = this.getNewRow();
            this.$list.append(new_row);
            jQuery(document).trigger('lolita-repeater-row-added');
        };
        Repeater.prototype.getNextIndex = function () {
            return this.$list.find('.lolita-repeater-row').length + 1;
        };
        Repeater.prototype.getNewRow = function () {
            var template, next_index, $widget, parsed;
            $widget = this.$el.parents('.widget');
            template = this.template;
            next_index = this.$list.find('.lolita-repeater-row').length + 1;
            template = template.replace(/__row_index__/g, next_index);
            if ($widget.length) {
                parsed = this.parseWidgetId($widget.attr('id'));
                template = template.replace(/__i__/g, parsed.number);
            }
            return template;
        };
        Repeater.prototype.parseWidgetId = function (widgetId) {
            var matches, parsed;
            parsed = {
                number: null,
                id_base: null
            };
            matches = widgetId.match(/^(.+)-(\d+)$/);
            if (matches) {
                parsed.id_base = matches[1];
                parsed.number = parseInt(matches[2], 10);
            }
            else {
                parsed.id_base = widgetId;
            }
            return parsed;
        };
        return Repeater;
    }());
    LolitaFramework.Repeater = Repeater;
})(LolitaFramework || (LolitaFramework = {}));
var LolitaFramework;
(function (LolitaFramework) {
    var Repeaters = (function () {
        function Repeaters() {
            var _this = this;
            this.repeater_selector = '.lolita-repeater-container';
            this.collection = [];
            this.update();
            jQuery(document).on('widget-updated', function () { return _this.widgetUpdate(); });
            jQuery(document).on('widget-added', function () { return _this.widgetUpdate(); });
            jQuery(document).on('lolita-repeater-row-added', function () { return _this.widgetUpdate(); });
        }
        Repeaters.prototype.widgetUpdate = function () {
            this.update();
        };
        Repeaters.prototype.update = function () {
            var me = this;
            jQuery(this.repeater_selector).each(function () {
                me.collection[jQuery(this).attr('id')] = new LolitaFramework.Repeater('#' + jQuery(this).attr('id'));
            });
        };
        return Repeaters;
    }());
    LolitaFramework.Repeaters = Repeaters;
    window.LolitaFramework.repeaters = new Repeaters();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=repeater.js.map