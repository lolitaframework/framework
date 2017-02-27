/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    class Media {

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
         * Preview container
         * @type {any}
         */
        $preview: any = null;

        /**
         * Init field properties.
         * The hidden input DOM element.
         * @type {any}
         */
        $input: any = null;

        /**
         * The <p class="info-id"></p> DOM element.
         * @type {any}
         */
        $info_id: any = null;

        /**
         * The <p class="info-path"></p> DOM element.
         * @type {any}
         */
        $info_path: any = null;

        /**
         * The img thumbnail DOM element.
         * @type {any}
         */
        $thumbnail: any = null;

        /**
         * Init a WordPress media window.
         * @type {any}
         */
        frame: any = null;

        /**
         * Media control class constructor
         */
        constructor() {
            this.rebind('.lolita-media-control');
            this.frame     = (<any>window).wp.media({
                // Define behaviour of the media window.
                // 'post' if related to a WordPress post.
                // 'select' if use outside WordPress post.
                frame: 'select',
                // Allow or not multiple selection.
                multiple: false,
                // The displayed title.
                title: 'Insert media',
                // The button behaviour
                button: {
                    text: 'Insert',
                    close: true
                },
                // Type of files shown in the library.
                // 'image', 'application' (pdf, doc,...)
                library:{
                    type: 'image'
                }
            });

            this.frame.on(
                'select',
                (e:any) => this.select(e)
            );

            jQuery(document).on(
                'click',
                '.lolita-media-add',
                (e: any) => this.add(e)
            );

            jQuery(document).on(
                'click',
                '.lolita-button-remove',
                (e: any) => this.remove(e)
            );

        }

        /**
         * Rebind variables
         * @param {any} main_selector selector.
         */
        rebind(main_selector:any) {
            this.$el = jQuery(main_selector);
            this.$add_button = this.$el.find('.lolita-media-add');
            this.$remove_button = this.$el.find('.lolita-button-remove');
            this.$preview = this.$el.find('.media-preview')
            this.$input = this.$el.find('input');
            this.$info_id = this.$el.find('p.info-id');
            this.$info_path = this.$el.find('p.info-path');
            this.$thumbnail = this.$el.find('img.media-thumbnail');
        }

        /**
         * Add button event
         * @param {any} e event.
         */
        add(e:any) {
            this.rebind(jQuery(e.currentTarget).closest('.lolita-media-control'));
            this.frame.open();
        }

        /**
         * Remove button event
         * @param {any} e event.
         */
        remove(e:any) {
            this.rebind(jQuery(e.currentTarget).closest('.lolita-media-control'));
            this.$input.val('');
            this.$info_id.text('');
            this.$info_path.text('');
            this.toggleButtons();
        }

        /**
         * Get the selected item from the library.
         *
         * @returns {object} A backbone model object.
         */
        getItem() {
            var selection = this.frame.state().get('selection').first();
            return selection;
        }

        /**
         * Run when an item is selected in the media library.
         * The event is fired when the "insert" button is clicked.
         *
         * @returns void
         */
        select(e:any) {
            var selection:any, type:any, value:any, display:any, thumb_url:string, title:string;
            selection = this.getItem();
            type      = selection.get('type');
            value     = selection.get('id');
            thumb_url = selection.get('icon');
            title     = selection.get('title');

            console.log(selection);

            // If image, get a thumbnail.
            if ('image' === type) {
                // Check if the defined size is available.
                var sizes = selection.get('sizes');
                thumb_url = selection.get('icon');
                if(undefined !== sizes.thumbnail) {
                    thumb_url = sizes.thumbnail.url;
                }

                if(undefined !== sizes.full) {
                    thumb_url = sizes.full.url;
                }
            }

            // Update the DOM elements.
            this.$input.val(value);
            this.$info_id.text(value);
            this.$info_path.text(thumb_url);
            this.$thumbnail.attr('src', thumb_url);

            this.toggleButtons();
        }

        /**
         * Toggle button
         */
        toggleButtons() {
            if('' === this.$input.val()) {
                this.$add_button.removeClass('hide');
                this.$preview.addClass('hide');
            } else {
                this.$add_button.addClass('hide');
                this.$preview.removeClass('hide');
            }
        }
    }

    (<any>window).LolitaFramework.media = new Media();
}