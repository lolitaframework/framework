/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class WidgetCarousel {

        /**
         * Carousel widget
         */
        constructor() {

            // Init jQuery SLY triggers
            //============================================================================================//
            jQuery('.sly').each(function() {

                if (jQuery(this).hasClass('horizontal')) {
                    var $scroolbar = jQuery('<div class="scrollbar" style="position: absolute; right: 0; left: 0; bottom: 0; height: 1px; background: lightgray"><div class="handle" style="position: absolute; top: -1px; width: 100px; height: 3px; background: #000000; cursor: w-resize;"></div></div>');
                } else {
                    var $scroolbar = jQuery('<div class="scrollbar" style="position: absolute; right: 0; top: 0; bottom: 0; width: 1px; background: lightgray"><div class="handle" style="position: absolute; left: -1px; height: 100px; width: 3px; background: #000000; cursor: n-resize;"></div></div>');
                }

                jQuery(this).append($scroolbar);

                var $options = {

                    horizontal: false, // Switch to horizontal mode.

                    // Item based navigation
                    itemNav: null, // Item navigation type. Can be: 'basic', 'centered', 'forceCentered'.
                    itemSelector: null, // Select only items that match this selector.
                    smart: true, // Repositions the activated item to help with further navigation.
                    activateOn: 'click', // Activate an item on this event. Can be: 'click', 'mouseenter', ...
                    activateMiddle: false, // Always activate the item in the middle of the FRAME. forceCentered only.

                    // Scrolling
                    scrollSource: null, // Element for catching the mouse wheel scrolling. Default is FRAME.
                    scrollBy: 60, // Pixels or items to move per one mouse scroll. 0 to disable scrolling.
                    scrollHijack: 300, // Milliseconds since last wheel event after which it is acceptable to hijack global scroll.
                    scrollTrap: true, // Don't bubble scrolling when hitting scrolling limits.

                    // Dragging
                    dragSource: null, // Selector or DOM element for catching dragging events. Default is FRAME.
                    mouseDragging: false, // Enable navigation by dragging the SLIDEE with mouse cursor.
                    touchDragging: true, // Enable navigation by dragging the SLIDEE with touch events.
                    releaseSwing: true, // Ease out on dragging swing release.
                    swingSpeed: 0.2, // Swing synchronization speed, where: 1 = instant, 0 = infinite.
                    elasticBounds: true, // Stretch SLIDEE position limits when dragging past FRAME boundaries.
                    interactive: null, // Selector for special interactive elements.

                    // Scrollbar
                    scrollBar: $scroolbar, // Selector or DOM element for scrollbar container.
                    dragHandle: true, // Whether the scrollbar handle should be draggable.
                    dynamicHandle: true, // Scrollbar handle represents the ratio between hidden and visible content.
                    minHandleSize: 50, // Minimal height or width (depends on sly direction) of a handle in pixels.
                    clickBar: true, // Enable navigation by clicking on scrollbar.
                    syncSpeed: 0.5, // Handle => SLIDEE synchronization speed, where: 1 = instant, 0 = infinite.

                    // Pagesbar
                    pagesBar: null, // Selector or DOM element for pages bar container.
                    activatePageOn: null, // Event used to activate page. Can be: click, mouseenter, ...
                    pageBuilder: // Page item generator.
                        function(index) {
                        return '<li>' + (index + 1) + '</li>';
                    },

                    // Navigation buttons
                    forward: null, // Selector or DOM element for "forward movement" button.
                    backward: null, // Selector or DOM element for "backward movement" button.
                    prev: null, // Selector or DOM element for "previous item" button.
                    next: null, // Selector or DOM element for "next item" button.
                    prevPage: null, // Selector or DOM element for "previous page" button.
                    nextPage: null, // Selector or DOM element for "next page" button.

                    // Automated cycling
                    cycleBy: null, // Enable automatic cycling by 'items' or 'pages'.
                    cycleInterval: 7000, // Delay between cycles in milliseconds.
                    pauseOnHover: true, // Pause cycling when mouse hovers over the FRAME.
                    startPaused: false, // Whether to start in paused sate.

                    // Mixed options
                    moveBy: 300, // Speed in pixels per second used by forward and backward buttons.
                    speed: 200, // Animations speed in milliseconds. 0 to disable animations.
                    easing: 'swing', // Easing for duration based (tweening) animations.
                    startAt: null, // Starting offset in pixels or items.
                    keyboardNavBy: null, // Enable keyboard navigation by 'items' or 'pages'.

                    // Classes
                    draggedClass: 'dragged', // Class for dragged elements (like SLIDEE or scrollbar handle).
                    activeClass: 'active', // Class for active items and pages.
                    disabledClass: 'disabled' // Class for disabled navigation elements.
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

                //init sly
                var $object = new Sly(jQuery(this).find('.frame'), $options);


                //reload on resize and trigger
                jQuery(window).on('load resize sly_reactivate', function() {
                    $object.reload();
                });

                //if crazy type then reload on load
                if (jQuery(this).hasClass('crazy')) {
                    jQuery(window).load(function() {
                        $object.reload();
                    });
                }

                $object.init();
            });
        }
    }
    (<any>window).LolitaFramework.carousel_widget = new WidgetCarousel();
}