/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class CustomizeRepeaters {
        /**
         * WordPress customizer
         * @type {any}
         */
        api: any = (<any>window).wp.customize;

        /**
         * Lolita repeaters
         * @type {any}
         */
        repeaters: any = (<any>window).LolitaFramework.repeaters;

        /**
         * Repeaters collection
         * @type {any}
         */
        public collection: any = [];

        /**
         * Repeaters controls
         */
        constructor() {
            var api: any, me:any;
            api = this.api;
            me  = this;
            this.repeaters.update();

            this.api.bind(
                'ready',
                () => this.repeaters.widgetUpdate()
            );

            this.api.RepeaterControl = this.api.Control.extend({
                ready: function() {
                    var control:any, element:any, events:string, type:string;
                    control = this;

                    control.container.find('[data-customize-setting-link]').each(
                        function(index:any, data:any) {
                            element     = jQuery(this);
                            events      = 'lolita-repeater-row-added, lolita-repeater-row-removed';

                            if ( element.is('input, select, textarea') ) {
                                events += ' change';

                                if ( element.is('input') ) {
                                    type = element.prop('type');
                                    if ( 'text' === type || 'password' === type ) {
                                        events += ' keyup';
                                    } else if ( 'range' === type ) {
                                        events += ' input propertychange';
                                    }
                                } else if ( element.is('textarea') ) {
                                    events += ' keyup';
                                }
                            }

                            jQuery(document).on(
                                events,
                                element,
                                (e:any) => me.update(e, control)
                            );
                        }
                    );
                }
            });

            jQuery.extend(
                this.api.controlConstructor,
                {
                    'repeater': this.api.RepeaterControl,
                }
            );
        }

        /**
         * Update
         * @param {any} e       [description]
         * @param {any} control [description]
         */
        update(e:any, control:any)
        {
            var obj:any, keys:any, i:number, k:string, new_obj:any;
            obj = (<any>window).jQuery.deparam(control.container.find(':input').serialize());
            obj = jQuery.extend({}, obj[control.id]);
            this.api.instance(control.id).set(obj);
        }
    }
    (<any>window).LolitaFramework.repeaters = new CustomizeRepeaters();
}