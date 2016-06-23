var LolitaFramework;
(function (LolitaFramework) {
    var WidgetCarousel = (function () {
        function WidgetCarousel() {
            jQuery('.sly').each(function () {
                if (jQuery(this).hasClass('horizontal')) {
                    var $scroolbar = jQuery('<div class="scrollbar" style="position: absolute; right: 0; left: 0; bottom: 0; height: 1px; background: lightgray"><div class="handle" style="position: absolute; top: -1px; width: 100px; height: 3px; background: #000000; cursor: w-resize;"></div></div>');
                }
                else {
                    var $scroolbar = jQuery('<div class="scrollbar" style="position: absolute; right: 0; top: 0; bottom: 0; width: 1px; background: lightgray"><div class="handle" style="position: absolute; left: -1px; height: 100px; width: 3px; background: #000000; cursor: n-resize;"></div></div>');
                }
                jQuery(this).append($scroolbar);
                var $options = {
                    horizontal: false,
                    itemNav: null,
                    itemSelector: null,
                    smart: true,
                    activateOn: 'click',
                    activateMiddle: false,
                    scrollSource: null,
                    scrollBy: 60,
                    scrollHijack: 300,
                    scrollTrap: true,
                    dragSource: null,
                    mouseDragging: false,
                    touchDragging: true,
                    releaseSwing: true,
                    swingSpeed: 0.2,
                    elasticBounds: true,
                    interactive: null,
                    scrollBar: $scroolbar,
                    dragHandle: true,
                    dynamicHandle: true,
                    minHandleSize: 50,
                    clickBar: true,
                    syncSpeed: 0.5,
                    pagesBar: null,
                    activatePageOn: null,
                    pageBuilder: function (index) {
                        return '<li>' + (index + 1) + '</li>';
                    },
                    forward: null,
                    backward: null,
                    prev: null,
                    next: null,
                    prevPage: null,
                    nextPage: null,
                    cycleBy: null,
                    cycleInterval: 7000,
                    pauseOnHover: true,
                    startPaused: false,
                    moveBy: 300,
                    speed: 200,
                    easing: 'swing',
                    startAt: null,
                    keyboardNavBy: null,
                    draggedClass: 'dragged',
                    activeClass: 'active',
                    disabledClass: 'disabled'
                };
                if (jQuery(this).hasClass('horizontal')) {
                    $options.horizontal = true;
                }
                if (jQuery(this).hasClass('basic')) {
                    $options.itemNav = 'basic';
                    $options.scrollBy = 1;
                }
                if (jQuery(this).hasClass('centered')) {
                    $options.itemNav = 'centered';
                    $options.startAt = 2;
                    $options.scrollBy = 1;
                }
                if (jQuery(this).hasClass('forceCentered')) {
                    $options.itemNav = 'forceCentered';
                    $options.scrollBy = 1;
                }
                if (jQuery(this).hasClass('mouse')) {
                    $options.mouseDragging = true;
                }
                if (jQuery(this).hasClass('cycle_by_items')) {
                    $options.cycleBy = 'items';
                    $options.cycleInterval = 1000;
                }
                if (jQuery(this).hasClass('cycle_by_pages')) {
                    $options.cycleBy = 'pages';
                    $options.cycleInterval = 7000;
                }
                if (jQuery(this).hasClass('one_per_frame')) {
                    $options.itemNav = 'forceCentered';
                    $options.activateMiddle = true;
                }
                if (jQuery(this).hasClass('crazy')) {
                    $options.itemNav = 'basic';
                }
                var $navigation = null;
                if (jQuery(this).hasClass('prev_next_item')) {
                    if ($navigation == null) {
                        $navigation = jQuery('<div class="navigation"></div>');
                    }
                    var $prev_button = jQuery('<span class="prev" style="cursor: pointer">Previous Item</span>');
                    var $next_button = jQuery('<span class="next" style="cursor: pointer">Next Item</span>');
                    $navigation.append($prev_button);
                    $navigation.append($next_button);
                    $options.prev = $prev_button;
                    $options.next = $next_button;
                    jQuery(this).append($navigation);
                }
                if (jQuery(this).hasClass('prev_next_page')) {
                    if ($navigation == null) {
                        $navigation = jQuery('<div class="navigation"></div>');
                    }
                    var $prev_button = jQuery('<span class="prevPage" style="cursor: pointer">Previous Page</span>');
                    var $next_button = jQuery('<span class="nextPage" style="cursor: pointer">Next Page</span>');
                    $navigation.append($prev_button);
                    $navigation.append($next_button);
                    $options.prevPage = $prev_button;
                    $options.nextPage = $next_button;
                    jQuery(this).append($navigation);
                }
                var $object = new Sly(jQuery(this).find('.frame'), $options);
                jQuery(window).on('load resize sly_reactivate', function () {
                    $object.reload();
                });
                if (jQuery(this).hasClass('crazy')) {
                    jQuery(window).load(function () {
                        $object.reload();
                    });
                }
                $object.init();
            });
        }
        return WidgetCarousel;
    }());
    LolitaFramework.WidgetCarousel = WidgetCarousel;
    window.LolitaFramework.carousel_widget = new WidgetCarousel();
})(LolitaFramework || (LolitaFramework = {}));
//# sourceMappingURL=lolita_widget_carousel.js.map