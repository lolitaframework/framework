/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class CustomizeIcons {
        /**
         * WordPress customizer
         * @type {any}
         */
        api: any = (<any>window).wp.customize;

        /**
         * Repeaters controls
         */
        constructor() {
            this.api.bind(
                'ready',
                () => (<any>window).LolitaFramework.icon_control.update()
            );
        }
    }
    (<any>window).LolitaFramework.customize_icons = new CustomizeIcons();
}