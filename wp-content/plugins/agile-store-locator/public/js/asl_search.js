jQuery( document ).ready(function() {
  
  if(!window['google'] || !google.maps)return;


  if (!Array.isArray) {
    Array.isArray = function(arg) {
      return Object.prototype.toString.call(arg) === '[object Array]';
    };
  }

  //  make sure it is not intialized
  //http://getbootstrap.com/customize/?id=23dc7cc41297275c7297bb237a95bbd7
  if(!jQuery.fn.adropdown) {
    if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(t){"use strict";var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<9||1==e[0]&&9==e[1]&&e[2]<1||e[0]>3){}}(jQuery),+function(t){"use strict";function e(e){var n=e.attr("data-target");n||(n=e.attr("href"),n=n&&/#[A-Za-z]/.test(n)&&n.replace(/.*(?=#[^\s]*$)/,""));var i=n&&t(n);return i&&i.length?i:e.parent()}function n(n){n&&3===n.which||(t(a).remove(),t(o).each(function(){var i=t(this),a=e(i),o={relatedTarget:this};a.hasClass("open")&&(n&&"click"==n.type&&/input|textarea/i.test(n.target.tagName)&&t.contains(a[0],n.target)||(a.trigger(n=t.Event("hide.bs.adropdown",o)),n.isDefaultPrevented()||(i.attr("aria-expanded","false"),a.removeClass("open").trigger(t.Event("hidden.bs.adropdown",o)))))}))}function i(e){return this.each(function(){var n=t(this),i=n.data("bs.adropdown");i||n.data("bs.adropdown",i=new r(this)),"string"==typeof e&&i[e].call(n)})}var a=".adropdown-backdrop",o='[data-toggle="adropdown"]',r=function(e){t(e).on("click.bs.adropdown",this.toggle)};r.VERSION="3.3.7",r.prototype.toggle=function(i){var a=t(this);if(!a.is(".disabled, :disabled")){var o=e(a),r=o.hasClass("open");if(n(),!r){"ontouchstart"in document.documentElement&&!o.closest(".navbar-nav").length&&t(document.createElement("div")).addClass("adropdown-backdrop").insertAfter(t(this)).on("click",n);var s={relatedTarget:this};if(o.trigger(i=t.Event("show.bs.adropdown",s)),i.isDefaultPrevented())return;a.trigger("focus").attr("aria-expanded","true"),o.toggleClass("open").trigger(t.Event("shown.bs.adropdown",s))}return!1}},r.prototype.keydown=function(n){if(/(38|40|27|32)/.test(n.which)&&!/input|textarea/i.test(n.target.tagName)){var i=t(this);if(n.preventDefault(),n.stopPropagation(),!i.is(".disabled, :disabled")){var a=e(i),r=a.hasClass("open");if(!r&&27!=n.which||r&&27==n.which)return 27==n.which&&a.find(o).trigger("focus"),i.trigger("click");var s=" li:not(.disabled):visible a",l=a.find(".adropdown-menu"+s);if(l.length){var d=l.index(n.target);38==n.which&&d>0&&d--,40==n.which&&d<l.length-1&&d++,~d||(d=0),l.eq(d).trigger("focus")}}}};var s=t.fn.adropdown;t.fn.adropdown=i,t.fn.adropdown.Constructor=r,t.fn.adropdown.noConflict=function(){return t.fn.adropdown=s,this},t(document).on("click.bs.adropdown.data-api",n).on("click.bs.adropdown.data-api",".adropdown form",function(t){t.stopPropagation()}).on("click.bs.adropdown.data-api",o,r.prototype.toggle).on("keydown.bs.adropdown.data-api",o,r.prototype.keydown).on("keydown.bs.adropdown.data-api",".adropdown-menu",r.prototype.keydown)}(jQuery),+function(t){"use strict";function e(e){var n,i=e.attr("data-target")||(n=e.attr("href"))&&n.replace(/.*(?=#[^\s]+$)/,"");return t(i)}function n(e){return this.each(function(){var n=t(this),a=n.data("bs.collapse"),o=t.extend({},i.DEFAULTS,n.data(),"object"==typeof e&&e);!a&&o.toggle&&/show|hide/.test(e)&&(o.toggle=!1),a||n.data("bs.collapse",a=new i(this,o)),"string"==typeof e&&a[e]()})}var i=function(e,n){this.$element=t(e),this.options=t.extend({},i.DEFAULTS,n),this.$trigger=t('[data-toggle="collapse"][href="#'+e.id+'"],[data-toggle="collapse"][data-target="#'+e.id+'"]'),this.transitioning=null,this.options.parent?this.$parent=this.getParent():this.addAriaAndCollapsedClass(this.$element,this.$trigger),this.options.toggle&&this.toggle()};i.VERSION="3.3.7",i.TRANSITION_DURATION=350,i.DEFAULTS={toggle:!0},i.prototype.dimension=function(){var t=this.$element.hasClass("width");return t?"width":"height"},i.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var e,a=this.$parent&&this.$parent.children(".panel").children(".in, .collapsing");if(!(a&&a.length&&(e=a.data("bs.collapse"),e&&e.transitioning))){var o=t.Event("show.bs.collapse");if(this.$element.trigger(o),!o.isDefaultPrevented()){a&&a.length&&(n.call(a,"hide"),e||a.data("bs.collapse",null));var r=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[r](0).attr("aria-expanded",!0),this.$trigger.removeClass("collapsed").attr("aria-expanded",!0),this.transitioning=1;var s=function(){this.$element.removeClass("collapsing").addClass("collapse in")[r](""),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!t.support.transition)return s.call(this);var l=t.camelCase(["scroll",r].join("-"));this.$element.one("bsTransitionEnd",t.proxy(s,this)).emulateTransitionEnd(i.TRANSITION_DURATION)[r](this.$element[0][l])}}}},i.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var e=t.Event("hide.bs.collapse");if(this.$element.trigger(e),!e.isDefaultPrevented()){var n=this.dimension();this.$element[n](this.$element[n]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded",!1),this.$trigger.addClass("collapsed").attr("aria-expanded",!1),this.transitioning=1;var a=function(){this.transitioning=0,this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")};return t.support.transition?void this.$element[n](0).one("bsTransitionEnd",t.proxy(a,this)).emulateTransitionEnd(i.TRANSITION_DURATION):a.call(this)}}},i.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()},i.prototype.getParent=function(){return t(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each(t.proxy(function(n,i){var a=t(i);this.addAriaAndCollapsedClass(e(a),a)},this)).end()},i.prototype.addAriaAndCollapsedClass=function(t,e){var n=t.hasClass("in");t.attr("aria-expanded",n),e.toggleClass("collapsed",!n).attr("aria-expanded",n)};var a=t.fn.collapse;t.fn.collapse=n,t.fn.collapse.Constructor=i,t.fn.collapse.noConflict=function(){return t.fn.collapse=a,this},t(document).on("click.bs.collapse.data-api",'[data-toggle="collapse"]',function(i){var a=t(this);a.attr("data-target")||i.preventDefault();var o=e(a),r=o.data("bs.collapse"),s=r?"toggle":a.data();n.call(o,s)})}(jQuery),+function(t){"use strict";function e(){var t=document.createElement("bootstrap"),e={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var n in e)if(void 0!==t.style[n])return{end:e[n]};return!1}t.fn.emulateTransitionEnd=function(e){var n=!1,i=this;t(this).one("bsTransitionEnd",function(){n=!0});var a=function(){n||t(i).trigger(t.support.transition.end)};return setTimeout(a,e),this},t(function(){t.support.transition=e(),t.support.transition&&(t.event.special.bsTransitionEnd={bindType:t.support.transition.end,delegateType:t.support.transition.end,handle:function(e){return t(e.target).is(this)?e.handleObj.handler.apply(this,arguments):void 0}})})}(jQuery);
  }

  (function($) {

    window['destination']  = null;


    /**
     * [sortBy description]
     * @param  {[type]} _items  [description]
     * @param  {[type]} _type   [description]
     * @param  {[type]} _string [description]
     * @return {[type]}         [description]
     */
    var sortBy = function(_items, _type, _string) {

      var sort_method = null;

      //  For Strings
      if(_string) {

        if(asl_search_configuration.sort_order == 'desc') {

          //  For string to with localecompare
          sort_method = function(a, b) {
            
            return (a[_type] && b[_type])? -(a[_type].localeCompare(b[_type])): 0;
          };
        }
        else {

          //  For string to with localecompare
          sort_method = function(a, b) {
            
            return (a[_type] && b[_type])? a[_type].localeCompare(b[_type]): 0;
          };
        }
      }
      //  Integers
      else {

        if(asl_search_configuration.sort_order == 'desc') {

          sort_method = function(a, b) {
            return parseInt(b[_type]) - parseInt(a[_type]);
          };
        }
        else
          sort_method = function(a, b) {
            return parseInt(a[_type]) - parseInt(b[_type]);
          };  
      }

      return _items.sort(sort_method);
    };

    /**
     * [asl_search_widget Search Widget of the Store Locator]
     * @return {[type]} [description]
     */
    $.fn.asl_search_widget = function() {
  
      return this.each(function() {
         
        var container          = $(this); 

        var search_input       = container.find('#sl-search-widget-text');

        //  Make sure the search field exist
        if(!search_input[0]) {
          return;
        }

        var clear              = search_input[0].parentNode.querySelector('.asl-clear-btn');

        /**
         * [geoLocatePosition GeoLocate the User Location]
         * @param  {[type]} _callback [description]
         * @param  {[type]} _error    [description]
         * @return {[type]}           [description]
         */
        function geoLocatePosition(_callback, _error) {

          var that = this;

          if (window.navigator && navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(function(pos) {
              
                _callback(new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude));
              },
              function(error) {

                var error_text = '';

                switch (error.code) {

                  case error.PERMISSION_DENIED:
                    error_text = error.message || "User denied the request for Geolocation.";
                    break;
                  case error.POSITION_UNAVAILABLE:
                    error_text = "Location information is unavailable.";
                    break;
                  case error.TIMEOUT:
                    error_text = "The request to get user location timed out.";
                    break;
                  case error.UNKNOWN_ERROR:
                    error_text = "An unknown error occurred.";
                    break;
                  default:
                    error_text = error.message;
                }

                //  Error Callback
                _error(error_text);

              }, ({
                maximumAge: 60 * 1000,
                timeout: 10 * 1000
              })
            ) ;
          }
        };
        /**
         * [geoCoder Geocoder]
         * @param  {[type]} _input    [description]
         * @param  {[type]} _callback [description]
         * @return {[type]}           [description]
         */
        var geoCoder = function(_input, _callback) {

          var that      = this;
          var geocoder  = new google.maps.Geocoder(),
            _callback   = _callback || function(results, status) {
          
          if (status == 'OK') {

              destination = results[0].geometry;
              search_input.removeClass('on-error');
              clear.style.display = 'block';
            }
              else {
              console.log('Geocode was not successful for the following reason: ' + status);
            }
          };

          
          // Enter Key
          $(_input).bind('keyup', function(e) {

            if (e.keyCode == 13) {
              
              var addr_value = $.trim(this.value);

              if(addr_value)
                geocoder.geocode({'address': addr_value}, _callback);
            }
          });
        };

        /**
        * Adds autocomplete to the input box.
        * @private
        */
        var initAutocomplete_ = function() {
          
          var that  = this;

          var options = {};

          if(asl_search_configuration.search_type != '3') {

            if(asl_search_configuration.google_search_type) {
              
              options['types'] = (asl_search_configuration.google_search_type == 'cities' || asl_search_configuration.google_search_type == 'regions')?['('+asl_search_configuration.google_search_type+')']:[asl_search_configuration.google_search_type];
            }

            //options['types'] = ['postal_code'];

            if(asl_search_configuration.country_restrict) {
              options['componentRestrictions'] = {country: asl_search_configuration.country_restrict.toLowerCase()}
            }

            this.autoComplete_ = new google.maps.places.Autocomplete(search_input[0], options);

            //  Restrict the data
            var fields = ['geometry'];
            this.autoComplete_.setFields(fields);

            google.maps.event.addListener(this.autoComplete_, 'place_changed',
              function() {
                var p = this.getPlace();

                
                if(p.geometry) {

                  clear.style.display = 'block';

                  //p.geometry.location
                  destination = p.geometry;
                  search_input.removeClass('on-error');

                }
            });

          }
          geoCoder(search_input[0]);
        };

        initAutocomplete_();


        //  Clear the button
        clear.addEventListener('click', function(e) {
          search_input.val('');
          clear.style.display = 'none';
          destination = null;
        });

        
        ///////////////////////////
        ///////Category Dropdown //
        ///////////////////////////
        //Multiple or Single 
        var _multiple_cat  = (asl_search_configuration.single_cat_select == '1')?'':'multiple="multiple"';

        //$category_cont.append('<select class="form-control border-0" id="asl-categories" '+_multiple_cat+'></select>');
        var $category_ddl = container.find('#asl-categories');

        //  Add the multiple tag
        if(asl_search_configuration.single_cat_select != '1') {

          $category_ddl.attr('multiple','multiple');
        }

        if($category_ddl[0]) {
              
          //  For NONE
          if(asl_search_configuration.single_cat_select == '1') {
            var $temp = $('<option value="0">'+asl_search_configuration.words['category']+'</option>');
            $category_ddl.append($temp);
          }

          asl_search_categories  =  Object.values(asl_search_categories);
          asl_search_categories  =  (!asl_search_configuration.cat_sort)? sortBy(asl_search_categories, 'name', true): sortBy(asl_search_categories, 'ordr');

          //  Loop over the categories  
          for (var _c in asl_search_categories) {

            var $temp = $('<option  value="'+asl_search_categories[_c].id+'">'+asl_search_categories[_c].name+'</option>');
            $category_ddl.append($temp);
          }

          $category_ddl[0].style.display = 'block';

          
          $category_ddl.multiselect({
            enableFiltering: false,
            disableIfEmpty: true,
            enableCaseInsensitiveFiltering: false,
            nonSelectedText: asl_search_configuration.words.select_option,
            filterPlaceholder: asl_search_configuration.words.search || "Search",
                  nonSelectedText: asl_search_configuration.words['category'] || "Select",
                  nSelectedText: asl_search_configuration.words.selected || "selected",
                  allSelectedText: (asl_search_configuration.words.all_selected || "All selected"),
            includeSelectAllOption: false,
            numberDisplayed: 1,
            maxHeight: 400,
            onChange : function(option, checked) {
              console.log('===> asl_search.js ===> 285');
            }
          });
        }


        //  Geo-location Bind
        container.find('.asl-geo').bind('click', function(e) {

            geoLocatePosition(function(_coordinate) {

              search_input.val(asl_search_configuration.words.geo);
              clear.style.display = 'block';
              destination = {location: _coordinate};
            }, 
            function(_text) {

              var $err_msg = $('<div class="alert alert-danger asl-geo-err"></div>');
              $err_msg.html(_text || 'Geo-Location is blocked, please check your preferences.');
              $err_msg.appendTo('.asl-cont.asl-search');
                window.setTimeout(function() {
                  $err_msg.remove();
                }, 5000);
            });
        });


        /////////////////////////////
        ////// Attribute DDL/////////
        /////////////////////////////

        var attr_keys = Object.keys(asl_attributes);

        for(var attr_key in attr_keys) {

          if (!attr_keys.hasOwnProperty(attr_key)) continue;

          var $attribute_ddl = container.find('#asl-' + attr_keys[attr_key]);

          //  Add the multiple tag
          if(asl_search_configuration.single_cat_select != '1') {

            $attribute_ddl.attr('multiple','multiple');
          }

          if($attribute_ddl[0]) {

            var attr_label = asl_search_configuration.words[attr_keys[attr_key]] ||  "Select";
            

            //  For NONE
            if(asl_search_configuration.single_cat_select == '1') {
              var $temp = $('<option value="0">'+attr_label+'</option>');
              $attribute_ddl.append($temp);
            }

            var attr_list_values = asl_attributes[attr_keys[attr_key]];

            for (var _c in attr_list_values) {
                
                var $temp = $('<option  value="'+attr_list_values[_c].id+'">'+attr_list_values[_c].name+'</option>');
                $attribute_ddl.append($temp);
            }

            $attribute_ddl[0].style.display = 'block';


            $attribute_ddl.multiselect({
              enableFiltering: false,
              disableIfEmpty: true,
              enableCaseInsensitiveFiltering: false,
              nonSelectedText: asl_search_configuration.words.select_option,
              filterPlaceholder: asl_search_configuration.words.search || "Search",
                    nonSelectedText: attr_label,
                    nSelectedText: asl_search_configuration.words.selected || "selected",
                    allSelectedText: (asl_search_configuration.words.all_selected || "All selected"),
              includeSelectAllOption: false,
              numberDisplayed: 1,
              maxHeight: 400,
              onChange : function(option, checked) {
                
              }
            }); 
          }
        }


        //////////////////////////
        //// FIND BUTTON/////// //
        //////////////////////////
          

        /**
         * [search_button_event Event fired to perform the search]
         * @param  {[type]} e [description]
         * @return {[type]}   [description]
         */
        function search_button_event(e) {
          
          ///var addr_value = $.trim(_input.value);
          var categories = ($category_ddl && $category_ddl.val())?$category_ddl.val(): null;

          var params = {};



          if(categories && categories != '0') {
            params['sl-category'] = Array.isArray(categories)? categories.join(','): categories;
          }


          var search_text = $.trim(search_input.val());
          var has_value   = false;

          //  Add the Attribute Values
          for(var attr_key in attr_keys) {

            if (!attr_keys.hasOwnProperty(attr_key)) continue;

            var $attribute_ddl = container.find('#asl-' + attr_keys[attr_key]);

            if($attribute_ddl[0]) {

              var attr_values = ($attribute_ddl.val())?$attribute_ddl.val(): null;

              if(attr_values && attr_values != '0') {
                params['sl-' + attr_keys[attr_key]] = Array.isArray(attr_values)? attr_values.join(','): attr_values;

                if(params['sl-' + attr_keys[attr_key]]) {
                  has_value = true;
                }
              }
            }
          }

          //  Add the additional query parameters
          if(asl_search_configuration['q-params']) {

            //  explode it on & as multiple parameters can be passed
            var q_params = asl_search_configuration['q-params'];

            q_params  = q_params.split('&');
              
            //  loop over the params
            for(var q = 0;q < q_params.length;q++) {

              var q_param = q_params[q];

              q_param = q_param.split('=');

              if(q_param.length == 2) {

                q_param[0] = q_param[0].replace('amp;', '');

                //  add it in the main parameters list
                params[q_param[0]] = q_param[1];
              }

            }
          }

          //  when condition is positive to redirect
          if(destination || search_text || params['sl-category'] || has_value) {

            //?sl-category=2&sl-addr=Denver%2C+CO%2C+USA&lat=39.7392358&lng=-104.990251

            if(search_input[0].required && !search_text) {
              search_input.addClass('on-error');
              return;
            }

            //  Address
            if(search_text)
              params['sl-addr']    = search_text;

            //  Coordinates
            if(destination && typeof destination == 'object' && destination.location) {

              params['lat'] = destination.location.lat();
              params['lng'] = destination.location.lng();
            }


            window.location.href = asl_search_configuration.redirect + '?' + $.param(params);
          }
          else
            search_input.addClass('on-error'); 
        };

        
        //  Make it Search Button
        container.find('#asl-btn-search').bind('click', search_button_event);

        //  Make it searching on enter
        if(asl_search_configuration.enter_search) {

          search_input.bind('keyup', function(_e) {

            if (_e.keyCode == 13) {
              
              search_button_event(_e);
            }
          });
        }
      });
    };


    //  Run the script
    $('.asl-cont.asl-search').asl_search_widget();

  })(jQuery);
});