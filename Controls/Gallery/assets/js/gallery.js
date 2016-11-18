var LolitaFramework;
(function (LolitaFramework) {
    var GalleryItem = (function () {
        function GalleryItem(value, src, name, template) {
            this.value = '';
            this.src = '';
            this.name = '';
            this.el = null;
            this.$el = null;
            this.template = null;
            this.value = value;
            this.src = src;
            this.name = name;
            this.template = template;
        }
        GalleryItem.prototype.render = function () {
            this.el = this.template({
                value: this.value,
                image: this.src,
                name: this.name
            });
            this.$el = jQuery(this.el);
            return this;
        };
        GalleryItem.prototype.setWidgetNumber = function (index) {
            var new_name = this.$el.find('input').attr('name');
            new_name = new_name.replace(/__i__/, index);
            this.$el.find('input').attr('name', new_name);
            this.el = this.$el[0].outerHTML;
            return this;
        };
        return GalleryItem;
    }());
    LolitaFramework.GalleryItem = GalleryItem;
})(LolitaFramework || (LolitaFramework = {}));
var LolitaFramework;
(function (LolitaFramework) {
    var Gallery = (function () {
        function Gallery(main_selector, frame) {
            var _this = this;
            this.$el = null;
            this.$add_button = null;
            this.$remove_button = null;
            this.$list = null;
            this.$list_item = null;
            this.item_template = null;
            this.frame = null;
            this.frame = frame;
            this.$el = jQuery(main_selector);
            this.$add_button = this.$el.find('#lolita-collection-add');
            this.$remove_button = this.$el.find('#lolita-collection-remove');
            this.$list = this.$el.find('.lolita-collection-list');
            this.$list_item = this.$list.find('.lolita-collection__item');
            this.item_template = window._.template(atob(this.$el.find('.underscore_template').html()));
            this.$add_button.on('click', function (e) { return _this.add(e); });
            this.$remove_button.on('click', function (e) { return _this.remove(e); });
            this.$el.on('click', '.lolita-collection-list li .lolita-collection__item', function (e) { return _this.clickItem(e); });
            this.$el.on('click', '.lolita-collection-list li .lolita-collection__item.selected .check', function (e) { return _this.clickItemCheck(e); });
            this.toggleCollectionContainer();
            this.sort();
        }
        Gallery.prototype.clickItemCheck = function (e) {
            e.preventDefault();
            jQuery(e.currentTarget).closest('li').remove();
            this.toggleRemoveButton().toggleCollectionContainer();
        };
        Gallery.prototype.clickItem = function (e) {
            jQuery(e.currentTarget).toggleClass('selected');
            this.toggleRemoveButton();
        };
        Gallery.prototype.add = function (e) {
            this.frame.current = this;
            this.frame.open();
        };
        Gallery.prototype.toggleRemoveButton = function () {
            if (this.$el.find('.lolita-collection-list li .lolita-collection__item.selected').length) {
                this.$el.find('#lolita-collection-remove').removeClass('hide');
            }
            else {
                this.$el.find('.lolita-button-remove').addClass('hide');
            }
            return this;
        };
        Gallery.prototype.toggleCollectionContainer = function () {
            if (this.$el.find('.lolita-collection-list li').length) {
                this.$el.find('.lolita-collection-container').removeClass('hide');
            }
            else {
                this.$el.find('.lolita-collection-container').addClass('hide');
            }
            return this;
        };
        Gallery.prototype.remove = function (e) {
            this.$el.find('.lolita-collection-list li .lolita-collection__item.selected').parent().remove();
            this.toggleRemoveButton().toggleCollectionContainer();
            return this;
        };
        Gallery.prototype.insertItem = function (id, thumbnail) {
            var item, $widget, parsed;
            item = new LolitaFramework.GalleryItem(id, thumbnail, this.$el.data('name') + '[]', this.item_template);
            item.render();
            $widget = this.$list.parents('.widget');
            if ($widget.length) {
                parsed = this.parseWidgetId($widget.attr('id'));
                item.setWidgetNumber(parsed.number);
            }
            this.$list.append(item.el);
            return this;
        };
        Gallery.prototype.getAttachmentThumbnail = function (attachment) {
            var type = attachment.get('type'), url = attachment.get('icon');
            if ('image' === type) {
                var sizes = attachment.get('sizes');
                if (undefined !== sizes.thumbnail) {
                    url = sizes.thumbnail.url;
                }
                else {
                    url = sizes.full.url;
                }
            }
            return url;
        };
        Gallery.prototype.sort = function () {
            this.$el.find('ul.lolita-collection-list').sortable({
                helper: function (e, ui) {
                    ui.children().each(function () {
                        jQuery(this).width(jQuery(this).width());
                    });
                    return ui;
                },
                forcePlaceholderSize: true,
                placeholder: 'lolita-collection-ui-state-highlight',
                handle: '.lolita-collection__item'
            });
        };
        Gallery.prototype.parseWidgetId = function (widgetId) {
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
        return Gallery;
    }());
    LolitaFramework.Gallery = Gallery;
})(LolitaFramework || (LolitaFramework = {}));
var LolitaFramework;
(function (LolitaFramework) {
    var Galleries = (function () {
        function Galleries() {
            var _this = this;
            this.frame = null;
            this.gallery_selector = '.lolita-collection-wrapper';
            this.collection = [];
            this.frame = window.wp.media({
                frame: 'select',
                multiple: true,
                title: 'Insert media',
                button: {
                    text: 'Insert',
                    close: true
                }
            });
            this.frame.on('select', function (e) { return _this.select(e); });
            this.update();
            jQuery(document).on('widget-updated', function () { return _this.widgetUpdate(); });
            jQuery(document).on('widget-added', function () { return _this.widgetUpdate(); });
            jQuery(document).on('lolita-repeater-row-added', function () { return _this.widgetUpdate(); });
        }
        Galleries.prototype.widgetUpdate = function () {
            this.update();
        };
        Galleries.prototype.update = function () {
            var me = this;
            jQuery(this.gallery_selector).each(function () {
                me.collection[jQuery(this).attr('id')] = new LolitaFramework.Gallery('#' + jQuery(this).attr('id'), me.frame);
            });
        };
        Galleries.prototype.select = function (e) {
            var selection = this.frame.state('library').get('selection');
            selection.map(function (attachment) {
                this.frame.current.insertItem(attachment.get('id'), this.frame.current.getAttachmentThumbnail(attachment)).toggleCollectionContainer();
            }, this);
        };
        return Galleries;
    }());
    LolitaFramework.Galleries = Galleries;
    window.LolitaFramework.galleries = new Galleries();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=gallery.js.map