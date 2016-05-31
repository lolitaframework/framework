var LolitaFramework;
(function (LolitaFramework) {
    var GalleryItem = (function () {
        function GalleryItem(value, src) {
            this.value = '';
            this.src = '';
            this.el = null;
            this.$el = null;
            this.template = null;
            this.value = value;
            this.src = src;
            this.template = window._.template(jQuery('#lolita-collection-item-template').html());
        }
        GalleryItem.prototype.render = function () {
            this.el = this.template({
                value: this.value,
                image: this.src
            });
            this.$el = jQuery(this.el);
            return this;
        };
        return GalleryItem;
    }());
    LolitaFramework.GalleryItem = GalleryItem;
})(LolitaFramework || (LolitaFramework = {}));
var LolitaFramework;
(function (LolitaFramework) {
    var Gallery = (function () {
        function Gallery() {
            var _this = this;
            this.$el = null;
            this.$add_button = null;
            this.$remove_button = null;
            this.$list = null;
            this.frame = null;
            this.l10n = window.lolita_gallery_control_l10n.items;
            this.$el = jQuery('.lolita-collection-wrapper');
            this.$add_button = this.$el.find('#lolita-collection-add');
            this.$remove_button = this.$el.find('#lolita-collection-remove');
            this.$list = this.$el.find('.lolita-collection-list');
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
            this.$add_button.on('click', function (e) { return _this.add(e); });
            this.$remove_button.on('click', function (e) { return _this.remove(e); });
            this.loadFromL10n();
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
        Gallery.prototype.loadFromL10n = function () {
            var i;
            for (i = 0; i < this.l10n.length; i++) {
                this.insertItem(this.l10n[i].ID, this.l10n[i].src);
            }
            return this;
        };
        Gallery.prototype.add = function (e) {
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
        Gallery.prototype.select = function (e) {
            var selection = this.frame.state('library').get('selection');
            selection.map(function (attachment) {
                this.insertItem(attachment.get('id'), this.getAttachmentThumbnail(attachment));
            }, this);
            this.toggleCollectionContainer();
        };
        Gallery.prototype.insertItem = function (id, thumbnail) {
            var item = new LolitaFramework.GalleryItem(id, thumbnail);
            this.$list.append(item.render().el);
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
        return Gallery;
    }());
    LolitaFramework.Gallery = Gallery;
    window.LolitaFramework.gallery = new Gallery();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=gallery.js.map