/// <reference path="jquery.d.ts" />

namespace LolitaFramework {
    export class WidgetGoogleMap {

        /**
         * Goole maps API lib.
         * @type {any}
         */
        public static script: any = null;
        /**
         * Google map widget
         */
        constructor() {
            jQuery('.lf_google_map').each(
                function(){
                    if (null === LolitaFramework.WidgetGoogleMap.script) {
                        LolitaFramework.WidgetGoogleMap.script = document.createElement("script");
                        LolitaFramework.WidgetGoogleMap.script.type = 'text/javascript';
                        LolitaFramework.WidgetGoogleMap.script.src  = 'http://maps.googleapis.com/maps/api/js?callback=LolitaFramework.WidgetGoogleMap.init&key=';
                        LolitaFramework.WidgetGoogleMap.script.src = LolitaFramework.WidgetGoogleMap.script.src + jQuery(this).data('apiKey');
                        document.body.appendChild(LolitaFramework.WidgetGoogleMap.script);
                    }
                }
            );
        }

        /**
         * Init google maps.
         */
        public static init() {
            jQuery('.lf_google_map').each(
                function(){
                    LolitaFramework.WidgetGoogleMap.loadLatLng(jQuery(this));
                }
            );
            
        }

        /**
         * Load lat and lng
         * @param {any} $map jquery object.
         */
        public static loadLatLng($map: any) {
            var me = this;
            jQuery.ajax({
                type: 'GET',
                dataType: 'json',
                url: 'http://maps.googleapis.com/maps/api/geocode/json?address=' + $map.data('address'),
                success: function( response ) {
                    if ( 'OK' === response.status ) {
                        LolitaFramework.WidgetGoogleMap.initMap(
                            response.results[0].geometry.location.lat,
                            response.results[0].geometry.location.lng,
                            $map[0],
                            $map.data('pinImg')
                        );
                    } else {
                        LolitaFramework.WidgetGoogleMap.initMap( 40.4778838, -74.290702, $map[0], $map.data('pinImg') );
                    }
                }
            });
        }

        /**
         * Init our map
         * @param {number} lat latitude.
         * @param {number} lng longitude.
         * @param {any}    el  dom element
         * @param {any}    pin image.
         */
        public static initMap(lat:number, lng:number, el:any, pin:any) {
            var mapOptions : any = {
                zoom: 15,
                center: new (<any>window).google.maps.LatLng( lat, lng ),
                scrollwheel: false,
                disableDefaultUI: true,
                draggable: false
            };

            var map = new (<any>window).google.maps.Map(el, mapOptions);

            var marker = new (<any>window).google.maps.Marker(
                {
                    position: new (<any>window).google.maps.LatLng( lat, lng ),
                    map: map,
                    icon: pin,
                    title: 'Click to zoom'
                }
            );

            (<any>window).google.maps.event.addListener(
                marker,
                'click',
                function() {
                    map.setZoom( 8 );
                    map.setCenter( marker.getPosition() );
                }
            );
        }
    }
    (<any>window).LolitaFramework.widget_google_map = new WidgetGoogleMap();
}