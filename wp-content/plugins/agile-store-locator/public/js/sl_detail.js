(function( $ ) {

  'use strict';


  /**
   * [store-detail description]
   * @param  {[type]} _options [description]
   * @return {[type]}          [description]
   */
  $.fn.asl_store_detail = function(_options) {


    /**
     * [store_method The main method of the store detail widget]
     * @return {[type]} [description]
     */
    function store_method() {

      var $container = $(this),
          $map_div   = $container.find('.asl-detail-map');

      //  Map div must exist!
      if(!$map_div[0]) {
        return;
      }

      var detail_config = $container.data('config');


      //  Google Library must exist!
      if(!window['google'] || !google.maps || !detail_config)return;

      /**
       * [createMarker Create a marker]
       * @param  {[type]} _lat [description]
       * @param  {[type]} _lng [description]
       * @return {[type]}      [description]
       */
      function createMarker(_location) {
          
        var url          = detail_config.URL + 'icon/' + ((detail_config.icon)? detail_config.icon: 'default.png');

        var marker_param = {
          position: _location,
          //animation: google.maps.Animation.BOUNCE,
          icon: {
            url: url
          }
        };

        return new google.maps.Marker(marker_param);
      };

      var asl_lat  = (detail_config.default_lat) ? parseFloat(detail_config.default_lat) : 39.9217698526,
          asl_lng  = (detail_config.default_lng) ? parseFloat(detail_config.default_lng) : -75.5718432,
          location = new google.maps.LatLng(asl_lat, asl_lng);


      var maps_params = {
        center: location,
        zoom: parseInt(detail_config.zoom),
        scrollwheel: detail_config.scroll_wheel,
        gestureHandling: detail_config.gesture_handling || 'cooperative', //cooperative,greedy
        mapTypeId: detail_config.map_type
      };

      if (detail_config.zoomcontrol == 'false') maps_params.zoomControl = false;
      if (detail_config.maptypecontrol == 'false') maps_params.mapTypeControl = false;
      if (detail_config.scalecontrol == 'false') maps_params.scaleControl = false;
      if (detail_config.rotatecontrol == 'false') maps_params.rotateControl = false;
      if (detail_config.fullscreencontrol == 'false') maps_params.fullscreenControl = false;
      if (detail_config.streetviewcontrol == 'false') maps_params.streetViewControl = false;

      maps_params['fullscreenControlOptions'] = {
        position: google.maps.ControlPosition.RIGHT_CENTER
      };

      // FULL SCREEN Positions
      if(detail_config.position_fullscreen) {
        maps_params['fullscreenControlOptions'] = {position: google.maps.ControlPosition[detail_config.position_fullscreen]};
      }

      // ZOOM Positions
      if(detail_config.position_zoom) {
        maps_params['zoomControlOptions'] = {position: google.maps.ControlPosition[detail_config.position_zoom]};
      }

      // STREETVIEW Positions
      if(detail_config.position_streetview) {
        maps_params['streetViewControlOptions'] = {position: google.maps.ControlPosition[detail_config.position_streetview]};
      }

      if (detail_config.maxzoom && !isNaN(detail_config.maxzoom)) {
        maps_params['maxZoom'] = parseInt(detail_config.maxzoom);
      }

      if (detail_config.minzoom && !isNaN(detail_config.minzoom)) {
        maps_params['minZoom'] = parseInt(detail_config.minzoom);
      }

      var map = new google.maps.Map($map_div[0], maps_params);

      if (detail_config.map_layout) {
        var map_style = eval('(' + detail_config.map_layout + ')');
        map.set('styles', map_style);
      }
      
      //  Create a marker to the location

      var marker_inst = createMarker(location);

      marker_inst.setMap(map);
    };

    /*loop for each*/
    this.each(store_method);

    return this;
  };


  // Callee
  $('.asl-cont.asl-store-pg').asl_store_detail();

}( jQuery ));
