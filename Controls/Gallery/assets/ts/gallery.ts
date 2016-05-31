/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class Gallery {

        /**
         * Media control element
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
         * Init a WordPress media window.
         * @type {any}
         */
        frame: any = null;

        /**
         * Data from backend
         * @type {any}
         */
        l10n: any = (<any>window).lolita_gallery_control_l10n.items;

        /**
         * Gallery control class constructor
         */
        constructor() {
            this.$el = jQuery('.lolita-collection-wrapper');
            this.$add_button = this.$el.find('#lolita-collection-add');
            this.$remove_button = this.$el.find('#lolita-collection-remove');
            this.$list = this.$el.find('.lolita-collection-list');

            this.frame = (<any>window).wp.media({
                // Define behaviour of the media window.
                // 'post' if related to a WordPress post.
                // 'select' if use outside WordPress post.
                frame: 'select',
                // Allow or not multiple selection.
                multiple: true,
                // The displayed title.
                title: 'Insert media',
                // The button behaviour
                button: {
                    text: 'Insert',
                    close: true
                }
            });

            this.frame.on(
                'select',
                (e:any) => this.select(e)
            );
            this.$add_button.on(
                'click',
                (e:any) => this.add(e)
            );
            this.$remove_button.on(
                'click',
                (e:any) => this.remove(e)
            );
            this.loadFromL10n();
            this.$el.on(
                'click',
                '.lolita-collection-list li .lolita-collection__item',
                (e:any) => this.clickItem(e)
            );

            this.$el.on(
                'click',
                '.lolita-collection-list li .lolita-collection__item.selected .check',
                (e:any) => this.clickItemCheck(e)
            );
            this.toggleCollectionContainer();
            this.sort();
        }

        /**
         * Click item check
         * @param {any} e event.
         */
        clickItemCheck(e:any) {
            e.preventDefault();
            jQuery(e.currentTarget).closest('li').remove();
            this.toggleRemoveButton().toggleCollectionContainer();
        }

        /**
         * Click to item event.
         * @param {any} e event.
         */
        clickItem(e:any) {
            jQuery(e.currentTarget).toggleClass('selected');
            this.toggleRemoveButton();
        }

        /**
         * Load from backend
         * @returns Gallery object.
         */
        loadFromL10n() {
            var i: number;
            for (i = 0; i < this.l10n.length; i++) {
                this.insertItem(this.l10n[i].ID, this.l10n[i].src);
            }
            return this;
        }

        /**
         * Add button event
         * @param {any} e event.
         */
        add(e:any) {
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
        remove(e:any) {
            this.$el.find('.lolita-collection-list li .lolita-collection__item.selected').parent().remove();
            this.toggleRemoveButton().toggleCollectionContainer();
            return this;
        }

        /**
         * Run when an item is selected in the media library.
         * The event is fired when the "insert" button is clicked.
         *
         * @returns void
         */
        select(e:any) {
            var selection = this.frame.state('library').get('selection');

            selection.map(
                function(attachment:any)
                {
                    this.insertItem(
                        attachment.get('id'),
                        this.getAttachmentThumbnail(attachment)
                    );
                },
                this
            );
            this.toggleCollectionContainer();
        }

        /**
         * Insert selected items to the collection view and its collection.
         *
         * @param attachment The attachment model from the WordPress media API.
         * @return void
         */
        insertItem(id:string, thumbnail:string) {
            var item = new GalleryItem(
                id,
                thumbnail
            );
            this.$list.append(item.render().el);
        }

        /**
         * Get the attachment thumbnail URL and returns it.
         *
         * @param {object} attachment The attachment model.
         * @return {string} The attachment thumbnail URL.
         */
        getAttachmentThumbnail(attachment:any) {
            var type = attachment.get('type'),
                url = attachment.get('icon');

            if('image' === type)
            {
                // Check if the thumbnail size is available.
                var sizes = attachment.get('sizes');

                if (undefined !== sizes.thumbnail)
                {
                    url = sizes.thumbnail.url;
                }
                else
                {
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
                helper : function(e:any, ui:any) {
                    ui.children().each(function() {
                        jQuery(this).width(jQuery(this).width());
                    });
                    return ui;
                },
                forcePlaceholderSize : true,
                placeholder: 'lolita-collection-ui-state-highlight',
                handle: '.lolita-collection__item'
            });
        }
    }

    (<any>window).LolitaFramework.gallery = new Gallery();
}