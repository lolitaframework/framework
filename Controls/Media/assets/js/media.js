var LolitaFramework;
(function (LolitaFramework) {
    var Media = (function () {
        function Media() {
            var _this = this;
            this.$el = null;
            this.$add_button = null;
            this.$remove_button = null;
            this.$preview = null;
            this.$input = null;
            this.$info_id = null;
            this.$info_path = null;
            this.$thumbnail = null;
            this.frame = null;
            this.rebind('.lolita-media-control');
            this.frame = window.wp.media({
                frame: 'select',
                multiple: false,
                title: 'Insert media',
                button: {
                    text: 'Insert',
                    close: true
                },
                library: {
                    type: 'image'
                }
            });
            this.frame.on('select', function (e) { return _this.select(e); });
            jQuery(document).on('click', '.lolita-media-add', function (e) { return _this.add(e); });
            jQuery(document).on('click', '.lolita-button-remove', function (e) { return _this.remove(e); });
        }
        Media.prototype.rebind = function (main_selector) {
            this.$el = jQuery(main_selector);
            this.$add_button = this.$el.find('.lolita-media-add');
            this.$remove_button = this.$el.find('.lolita-button-remove');
            this.$preview = this.$el.find('.media-preview');
            this.$input = this.$el.find('input');
            this.$info_id = this.$el.find('p.info-id');
            this.$info_path = this.$el.find('p.info-path');
            this.$thumbnail = this.$el.find('img.media-thumbnail');
        };
        Media.prototype.add = function (e) {
            console.log('click');
            this.rebind(jQuery(e.currentTarget).closest('.lolita-media-control'));
            this.frame.open();
        };
        Media.prototype.remove = function (e) {
            this.rebind(jQuery(e.currentTarget).closest('.lolita-media-control'));
            this.$input.val('');
            this.$info_id.text('');
            this.$info_path.text('');
            this.toggleButtons();
        };
        Media.prototype.getItem = function () {
            var selection = this.frame.state().get('selection').first();
            return selection;
        };
        Media.prototype.select = function (e) {
            var selection, type, value, display, thumb_url, title;
            selection = this.getItem();
            type = selection.get('type');
            value = selection.get('id');
            thumb_url = selection.get('icon');
            title = selection.get('title');
            if ('image' === type) {
                var sizes = selection.get('sizes');
                try {
                    thumb_url = sizes.thumbnail.url;
                }
                catch (err) {
                    thumb_url = selection.get('icon');
                }
            }
            this.$input.val(value);
            this.$info_id.text(value);
            this.$info_path.text(thumb_url);
            this.$thumbnail.attr('src', thumb_url);
            this.toggleButtons();
        };
        Media.prototype.toggleButtons = function () {
            if ('' === this.$input.val()) {
                this.$add_button.removeClass('hide');
                this.$preview.addClass('hide');
            }
            else {
                this.$add_button.addClass('hide');
                this.$preview.removeClass('hide');
            }
        };
        return Media;
    }());
    window.LolitaFramework.media = new Media();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=media.js.map