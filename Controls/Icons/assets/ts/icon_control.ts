/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class IconControl {

        /**
         * Timeout handler.
         * @type {any}
         */
        timer_id: any = 0;

        /**
         * Fade speed animation.
         * @type {String}
         */
        animation_speed: any = 0;

        /**
         * Repeaters controls
         */
        constructor() {
            jQuery(document).on(
                'click',
                '.lf_icon_packs ul li a',
                (e:any) => this.iconClick(e)
            );
            jQuery(document).on(
                'keyup',
                '.lf_icons_wrapper input',
                (e:any) => this.change(e)
            );
            jQuery(document).on(
                'click',
                (e:any) => this.leaveFocus(e)
            );
            jQuery(document).on(
                'keydown',
                '.lf_icon_packs',
                (e:any) => this.exit(e)
            );

            jQuery(document).on(
                'widget-updated',
                () => this.update()
            );

            jQuery(document).on(
                'widget-added',
                () => this.update()
            );
            this.update();
        }

        /**
         * Icon update
         */
        update() {
            jQuery('.lf_icons_wrapper input').each(
                (index, obj) => this.setIcon(jQuery(obj))
            );
        }

        /**
         * Exit from icons pack
         * @param {any} e event.
         */
        exit(e:any) {
            var me: any = this;
            if(27 === e.keyCode) {
                jQuery('.lf_icon_packs').each(
                    function() {
                        me.packHide(jQuery(this));
                    }
                );
            }
        }

        /**
         * Set icon
         * @param {any} $input jQuery object.
         */
        setIcon($input:any) {
            $input.parent().find('.lf_icons_control_icon i').attr('class', $input.val());
        }

        /**
         * Pack show
         * @param {any} $pack jQuery object.
         */
        packShow($pack:any) {
            if ('visible' === $pack.css('display')) {
                return true;
            }
            if (true === this.isIntoView($pack.parent().find('input'))) {
                $pack.css({ "top" : "100%", "bottom" : "inherit" });
            } else {
                $pack.css({ "bottom" : "100%", "top" : "inherit" });
            }
            $pack.show(this.animation_speed);
        }

        /**
         * Pack hide
         * @param {any} $pack jQuery object.
         */
        packHide($pack:any) {
            $pack.hide(this.animation_speed);
        }

        /**
         * Leave input focus
         *
         * @param {any} e event.
         */
        leaveFocus(e:any) {
            var $el: any, me: any;

            $el = jQuery(e.target);
            me  = this;

            if (!$el.closest('.lf_icons_wrapper').length) {
                jQuery('.lf_icon_packs').each(
                    function() {
                        me.packHide(jQuery(this));
                    }
                );
            }
        }

        /**
         * Icon click
         * @param {any} e event.
         */
        iconClick(e:any) {
            e.preventDefault();
            jQuery(e.currentTarget).closest('.lf_icons_wrapper').find('input').val(jQuery(e.currentTarget).attr('href').replace('#', ''));
            this.setIcon(jQuery(e.currentTarget).closest('.lf_icons_wrapper').find('input'));
            jQuery(e.currentTarget).closest('.lf_icons_wrapper').find('input').trigger('change');
        }

        /**
         * Main input change event
         *
         * @param {any} e input change event.
         */
        change(e:any) {
            var $wrapper:any;
            clearTimeout(this.timer_id);

            $wrapper      = jQuery(e.target.parentNode);
            this.timer_id = setTimeout(() => this.filter(e.target.value, $wrapper), 100);
        }

        /**
         * Filter objects
         *
         * @param {string} str value.
         * @param {any} $objects_wrap jquery objects wrap.
         */
        filter(str:string, $wrapper:any) {
            var count: any = 0;
            this.setIcon($wrapper.find('input'));
            $wrapper.find('.lf_icon_packs').fadeIn(this.animation_speed);
            $wrapper.find('ul li a').each(
                (e: any, me: any) => count += this.filter_object(e, jQuery(me), str)
            );
            if(count) {
                this.packShow($wrapper.find('.lf_icon_packs'));
            } else {
                this.packHide($wrapper.find('.lf_icon_packs'));
            }
        }

        /**
         * Filter object by href value
         *
         * @param {any} i index.
         * @param {any} $me jquery object.
         * @param {string} val query.
         */
        filter_object(i:any, $me:any, val:string) {
            var rx:any, match: any;

            rx    = new RegExp('.*?' + val + '.*?');
            match = $me.attr('href').match(rx);

            if (null === match) {
                $me.parent().fadeOut(this.animation_speed);
                return false;
            } else {
                $me.parent().fadeIn(this.animation_speed);
                return true;
            }
        }

        /**
         * Is into view?
         * @param {any} elem element/
         */
        isIntoView($el:any) {
            var docViewTop = jQuery(window).scrollTop();
            var docViewBottom = docViewTop + jQuery(window).height();

            var elemTop = $el.offset().top;
            var elemBottom = elemTop + 300;

            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }
    }
    (<any>window).LolitaFramework.icon_control = new IconControl();
}