/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class Repeater {

        /**
         * Repeater control element
         * @type {any}
         */
        $el: any = null;

        /**
         * Row list
         * @type {any}
         */
        $list: any = null;

        /**
         * Add button
         * @type {any}
         */
        $add_button: any = null;

        /**
         * Add button
         * @type {any}
         */
        $add_prev: any = null;

        /**
         * Remove button
         * @type {any}
         */
        $remove_button: any = null;

        /**
         * Underscore template 
         * @type {any}
         */
        template: any = null;

        /**
         * HTML template
         * @type {any}
         */
        template_html: any = null;

        /**
         * Repeater control class constructor
         */
        constructor(main_selector:string) {
            this.$el            = jQuery(main_selector);
            this.template       = atob(this.$el.find('.underscore_template').html());
            this.$list          = this.$el.find('.lolita-repeater-sortable');
            this.$add_button    = this.$el.find('.lolita-repeater-main-add');
            this.$add_prev      = this.$el.find('.lolita-repeater-add');
            this.$remove_button = this.$el.find('.lolita-repeater-remove');

            this.$add_button.one(
                'click',
                (e: any) => this.addAfter(e)
            );

            this.$add_prev.one(
                'click',
                (e: any) => this.addPrev(e)
            );

            this.$remove_button.one(
                'click',
                (e: any) => this.remove(e)
            );

            jQuery(document).on(
                'lolita-repeater-row-added',
                () => this.recountOrder()
            );

            jQuery(document).on(
                'lolita-repeater-row-removed',
                () => this.recountOrder()
            );
            this.sort();
        }

        /**
         * Sortable
         */
        sort() {
            var me: any = this;
            this.$list.sortable({
                helper : function(e:any, ui:any) {
                    ui.children().each(function() {
                        jQuery(this).width(jQuery(this).width());
                    });
                    return ui;
                },
                forcePlaceholderSize : true,
                placeholder : 'lolita-ui-state-highlight',
                handle : '.lolita-repeater-order',
                update : function(){
                    me.recountOrder();
                }
            });
        }

        /**
         * Remove row
         * @param {any} e event.
         */
        remove(e: any) {
            if (this.$list.find('.lolita-repeater-row').length > 1) {
                jQuery(e.currentTarget).closest('.lolita-repeater-row').remove();
                jQuery(document).trigger('lolita-repeater-row-removed');
            }
        }

        /**
         * Reorder all list items
         */
        recountOrder() {
            var me: any = this;

            this.$list.find('.lolita-repeater-row').each(
                function(index:any) {
                    var current_index = index + 1;
                    jQuery(this).find('.lolita-repeater-order span').text(current_index);
                    jQuery(this).find('input, textarea, select').each(
                        function() {
                            jQuery(this).attr(
                                'name',
                                me.reName(
                                    me.$el.data('name'),
                                    jQuery(this).attr('name'),
                                    current_index
                                )
                            );

                            jQuery(this).attr(
                                'id',
                                me.reID(
                                    me.$el.attr('id'),
                                    jQuery(this).attr('id'),
                                    current_index
                                )
                            );
                        }
                    );

                    jQuery(this).find('[data-control]').each(
                        function() {
                            jQuery(this).get(0).dataset['name'] = me.reName(
                                me.$el.data('name'),
                                jQuery(this).get(0).dataset['name'],
                                current_index
                            );

                            jQuery(this).attr(
                                'id',
                                me.reID(
                                    me.$el.attr('id'),
                                    jQuery(this).attr('id'),
                                    current_index
                                )
                            );
                        }
                    );
                }
            );
        }

        /**
         * Rename with new order index
         * @param {string} repeater_name repeater name.
         * @param {string} el_name       element name.
         * @param {number} index         index.
         */
        reName(repeater_name:string, el_name:string, index:number) {
            var result_name: string;
            el_name = el_name.replace(repeater_name, '');
            el_name = el_name.replace(/^\[[0-9]*\]/, '[' + index + ']');
            result_name = repeater_name + el_name;
            return repeater_name + el_name;
        }

        /**
         * Rename ID with new order index
         * @param {string} repeater_id repeater id.
         * @param {string} el_id       element id.
         * @param {number} index       index.
         */
        reID(repeater_id: string, el_id:string, index:number) {
            if(el_id === undefined) {
                return undefined;
            }
            el_id = el_id.replace(repeater_id, '');
            el_id = el_id.replace(/^_[0-9]*_/, '_' + index + '_');
            return repeater_id + el_id;
        }

        /**
         * Add prev row
         * @param {any} e event.
         */
        addPrev(e: any) {
            e.preventDefault();
            var new_row: any = this.getNewRow();
            jQuery(e.srcElement).closest('.lolita-repeater-row').before(new_row);
            jQuery(document).trigger('lolita-repeater-row-added');
        }

        /**
         * Add after row
         * @param {any} e event.
         */
        addAfter(e:any) {
            e.preventDefault();
            var new_row: any = this.getNewRow();
            this.$list.append(new_row);
            jQuery(document).trigger('lolita-repeater-row-added');
        }

        /**
         * Get next index
         */
        getNextIndex() {
            return this.$list.find('.lolita-repeater-row').length + 1;
        }

        /**
         * Get new row
         */
        getNewRow() {
            var template: any, next_index: number, $widget: any, parsed: any;
            $widget = this.$el.parents('.widget');
            template = this.template;
            next_index = this.$list.find('.lolita-repeater-row').length + 1;
            template = template.replace(/__row_index__/g, next_index);
            if ($widget.length) {
                parsed = this.parseWidgetId($widget.attr('id'));
                template = template.replace(/__i__/g, parsed.number);
            }
            return template;
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