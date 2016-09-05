var LolitaFramework;
(function (LolitaFramework) {
    var CustomizeIcons = (function () {
        function CustomizeIcons() {
            this.api = window.wp.customize;
            this.api.bind('ready', function () { return window.LolitaFramework.icon_control.update(); });
        }
        return CustomizeIcons;
    }());
    LolitaFramework.CustomizeIcons = CustomizeIcons;
    window.LolitaFramework.customize_icons = new CustomizeIcons();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=customize_icons.js.map