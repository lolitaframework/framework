/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class Galleries {

        /**
         * Init a WordPress media window.
         * @type {any}
         */
        frame: any = null;

        /**
         * Gallery selector
         * @type {string}
         */
        gallery_selector: string = '.lolita-collection-wrapper';

        /**
         * Galleries collection
         * @type {any}
         */
        collection: any = [];

        /**
         * Galleries controls
         */
        constructor() {
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
                (e: any) => this.select(e)
            );

            this.update();

            jQuery(document).on(
                'widget-updated',
                () => this.widgetUpdate()
            );

            jQuery(document).on(
                'widget-added',
                () => this.widgetUpdate()
            );

            jQuery(document).on(
                'lolita-repeater-row-added',
                () => this.widgetUpdate()
            );
        }

        /**
         * Widget update
         */
        widgetUpdate() {
            this.update();
        }

        /**
         * Rebind events
         */
        update() {
            var me: any = this;
            jQuery(this.gallery_selector).each(
                function() {
                    me.collection[jQuery(this).attr('id')] = new Gallery(
                        '#' + jQuery(this).attr('id'),
                        me.frame
                    );
                }
            );
        }

        /**
         * Run when an item is selected in the media library.
         * The event is fired when the "insert" button is clicked.
         *
         * @returns void
         */
        select(e: any) {
            var selection = this.frame.state('library').get('selection');

            selection.map(
                function(attachment: any) {
                    this.frame.current.insertItem(
                        attachment.get('id'),
                        this.frame.current.getAttachmentThumbnail(attachment)
                    ).toggleCollectionContainer();
                },
                this
            );
        }
    }
    (<any>window).LolitaFramework.galleries = new Galleries();
}