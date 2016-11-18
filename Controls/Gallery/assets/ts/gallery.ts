/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class Gallery {

        /**
         * Gallery control element
         * @type {any}
         */
        $el: any = null;

        /**
         * Add button
         * @type {any}
         */
        $add_button: any = null;

        /**
         * Remove button
         * @type {any}
         */
        $remove_button: any = null;

        /**
         * Gallery items list
         * @type {any}
         */
        $list: any = null;

        /**
         * List item
         * @type {any}
         */
        $list_item: any = null;

        /**
         * Underscore template
         * @type {any}
         */
        item_template: any = null;

        /**
         * Init a WordPress media window.
         * @type {any}
         */
        frame: any = null;

        /**
         * Gallery control class constructor
         */
        constructor(main_selector:string, frame:any) {
            this.frame = frame;
            this.$el = jQuery(main_selector);
            this.$add_button = this.$el.find('#lolita-collection-add');
            this.$remove_button = this.$el.find('#lolita-collection-remove');
            this.$list = this.$el.find('.lolita-collection-list');
            this.$list_item = this.$list.find('.lolita-collection__item');
            this.item_template = (<any>window)._.template(atob(this.$el.find('.underscore_template').html()));

            this.$add_button.on(
                'click',
                (e: any) => this.add(e)
            );
            this.$remove_button.on(
                'click',
                (e: any) => this.remove(e)
            );

            this.$el.on(
                'click',
                '.lolita-collection-list li .lolita-collection__item',
                (e: any) => this.clickItem(e)
            );

            this.$el.on(
                'click',
                '.lolita-collection-list li .lolita-collection__item.selected .check',
                (e: any) => this.clickItemCheck(e)
            );

            this.toggleCollectionContainer();
            this.sort();
        }

        /**
         * Click item check
         * @param {any} e event.
         */
        clickItemCheck(e: any) {
            e.preventDefault();
            jQuery(e.currentTarget).closest('li').remove();
            this.toggleRemoveButton().toggleCollectionContainer();
        }

        /**
         * Click to item event.
         * @param {any} e event.
         */
        clickItem(e: any) {
            jQuery(e.currentTarget).toggleClass('selected');
            this.toggleRemoveButton();
        }

        /**
         * Add button event
         * @param {any} e event.
         */
        add(e: any) {
            this.frame.current = this;
            this.frame.open();
        }

        /**
         * Toggle remove button
         */
        toggleRemoveButton() {
            if (this.$el.find('.lolita-collection-list li .lolita-collection__item.selected').length) {
                this.$el.find('#lolita-collection-remove').removeClass('hide');
            } else {
                this.$el.find('.lolita-button-remove').addClass('hide');
            }
            return this;
        }

        /**
         * Toggle collection container
         */
        toggleCollectionContainer() {
            if (this.$el.find('.lolita-collection-list li').length) {
                this.$el.find('.lolita-collection-container').removeClass('hide');
            } else {
                this.$el.find('.lolita-collection-container').addClass('hide');
            }
            return this;
        }

        /**
         * Remove button event
         * @param {any} e event.
         */
        remove(e: any) {
            this.$el.find('.lolita-collection-list li .lolita-collection__item.selected').parent().remove();
            this.toggleRemoveButton().toggleCollectionContainer();
            return this;
        }

        /**
         * Insert selected items to the collection view and its collection.
         *
         * @param attachment The attachment model from the WordPress media API.
         * @return void
         */
        insertItem(id: string, thumbnail: string) {
            var item: any, $widget: any, parsed: any;
            item = new GalleryItem(
                id,
                thumbnail,
                this.$el.data('name') + '[]',
                this.item_template
            );
            item.render()

            $widget = this.$list.parents('.widget');
            if ($widget.length) {
                parsed = this.parseWidgetId($widget.attr('id'));
                item.setWidgetNumber(parsed.number);
            }
            this.$list.append(item.el);
            return this;
        }

        /**
         * Get the attachment thumbnail URL and returns it.
         *
         * @param {object} attachment The attachment model.
         * @return {string} The attachment thumbnail URL.
         */
        getAttachmentThumbnail(attachment: any) {
            var type = attachment.get('type'),
                url = attachment.get('icon');

            if ('image' === type) {
                // Check if the thumbnail size is available.
                var sizes = attachment.get('sizes');

                if (undefined !== sizes.thumbnail) {
                    url = sizes.thumbnail.url;
                }
                else {
                    // Original image is less than 100px.
                    url = sizes.full.url;
                }
            }

            return url;
        }

        /**
         * Allow collection items to be sortable using drag&drop.
         *
         * @return void
         */
        sort() {
            this.$el.find('ul.lolita-collection-list').sortable({
                helper: function(e: any, ui: any) {
                    ui.children().each(function() {
                        jQuery(this).width(jQuery(this).width());
                    });
                    return ui;
                },
                forcePlaceholderSize: true,
                placeholder: 'lolita-collection-ui-state-highlight',
                handle: '.lolita-collection__item'
            });
        }

        /**
         * @param {String} widgetId
         * @returns {Object}
         */
        parseWidgetId(widgetId: string) {
            var matches: any, parsed: any;
            parsed = {
                number: null,
                id_base: null
            };

            matches = widgetId.match(/^(.+)-(\d+)$/);
            if (matches) {
                parsed.id_base = matches[1];
                parsed.number = parseInt(matches[2], 10);
            } else {
                // likely an old single widget
                parsed.id_base = widgetId;
            }

            return parsed;
        }
    }
}