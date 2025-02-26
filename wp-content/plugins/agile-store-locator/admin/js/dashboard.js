var asl_engine = window['asl_engine'] || {};

(function($, app_engine) {
  'use strict';


  /**
   * [toastIt toast it based on the error or message]
   * @param  {[type]} _response [description]
   * @return {[type]}           [description]
   */
  var toastIt = function(_response) {

    if(_response.success) {
      atoastr.success(_response.msg || _response.message);
    }
    else {
      atoastr.error(_response.error || _response.message || _response.msg);
    }
  };

  app_engine['pages'] = {
    /**
     * [dashboard Main Dashboard page]
     * @return {[type]} [description]
     */
    dashboard: function() {

      var current_date  = 0,
        date_           = new Date();

      var day_arr = [];
      var months  = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        month     = months[date_.getMonth()],
        data_arr  = [];

      //  Tabs switch
      $('.asl-p-cont .nav-tabs a').click(function(e) {
        e.preventDefault()
        $(this).tab('show');
      });
      
      //  add dummy data
      for (var a = 1; a <= date_.getDate(); a++) {

        day_arr.push(a + ' ' + month);
        data_arr.push(0);
      }

      var lineChartData = {
        labels: day_arr,
        datasets: [{
          tension: 0.1,
          lineTension: 0.1,
          backgroundColor: "rgba(75, 192, 192, 0.4)",
          borderColor: "rgba(75, 192, 192, 1",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "rgba(75, 192, 192, 1)",
          pointBackgroundColor: "#fff",
          pointBorderWidth: 1,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(75, 192, 192, 1)",
          pointHoverBorderColor: "rgba(220, 220, 220, 1)",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          label: 'Searches',
          backgroundColor: "#57C8F2",
          data: data_arr
        }]

      };

      asl_initialize_chart();

      //  Datetime
      var $datepicker = $('#sl-datetimepicker');

      /**
       * [get_duration_string Return the duration  string used for the AJAX]
       * @return {[type]} [description]
       */
      function get_duration_string() {

        var dt_data = $datepicker.data('daterangepicker');

        return encodeURI('sl-start=' + dt_data.startDate.format('YYYY-MM-DD') + '&sl-end=' + dt_data.endDate.format('YYYY-MM-DD'));
      };

      /////////////////////////////////
      //  Change the expertise level //
      /////////////////////////////////
      var $sl_level = $('#asl-level-swtch');

      /**
       * [update_level description]
       * @param  {[type]} _status [description]
       */
      function update_level(_status, _callback) {
        
        ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&sl-action=expertise_level", {status: $sl_level[0].checked? '1': '0'}, function(_response) {

          toastIt(_response);

          if(_callback) {
            _callback(_response);
          }

          window.setTimeout(function(){
            
            window.location.reload();

          }, 1500);


        }, 'json');
      }; 

      //  Cache Switch Event
      $sl_level.bind('change', function(e) {

        var chk_ctrl = e.target,
            status   = (chk_ctrl.checked)? '1': '0';

        update_level(status);
      });


      //////////////////////////
      //  Backup the Template //
      //////////////////////////
      $('#sl-btn-tmpl-backup').bind('click', function(e) {

        aswal({
          title: ASL_REMOTE.LANG.backup_tmpl,
          type: 'question',
          html: '<p>'+ ASL_REMOTE.LANG.backup_tmpl_msg + '</p>' + '<select class="custom-select" id="sl-tmpl-section-1"><option value="0">Template 0</option><option value="1">Template 1</option><option value="2">Template 2</option><option value="3">Template 3</option><option value="4">Template 4</option><option value="list">Template List</option><option value="form">Store Form</option><option value="store">Store Detail</option><option value="search">Search Widget</option><option value="lead">Lead Form</option><option value="grid">Store Grid</option></select>',
          showCancelButton: true,
          confirmButtonColor: "#dc3545",
          confirmButtonText: ASL_REMOTE.LANG.backup,
          preConfirm: function(_value) {

            var _value = $('#sl-tmpl-section-1').val();
            
            return new Promise(function(resolve, reject) {

              aswal.showLoading();

              ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&sl-action=backup_tmpl", { template: _value }, function(_response) {

                aswal.close();

                toastIt(_response);

              }, 'json');

            })
          }
        });
      });

      //  Export the leads
      $('#sl-btn-export-stats').bind('click', function() {

        window.location.href = ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=export_stats&" + get_duration_string();
      });



      //  Remove the Template
      $('#sl-btn-tmpl-remove').bind('click', function(e) {

        aswal({
          title: ASL_REMOTE.LANG.remove_tmpl,
          text: ASL_REMOTE.LANG.remove_tmpl_msg,
          type: 'question',
          html: '<select class="custom-select" id="sl-tmpl-section-2"><option value="0">Template 0</option><option value="1">Template 1</option><option value="2">Template 2</option><option value="3">Template 3</option><option value="4">Template 4</option><option value="list">Template List</option><option value="form">Store Form</option><option value="store">Store Detail</option><option value="search">Search Widget</option><option value="lead">Lead Form</option><option value="grid">Store Grid</option></select>',
          showCancelButton: true,
          confirmButtonColor: "#dc3545",
          confirmButtonText: ASL_REMOTE.LANG.remove,
          preConfirm: function(_value) {

            var _value = $('#sl-tmpl-section-2').val();
            
            return new Promise(function(resolve, reject) {

              aswal.showLoading();

              ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&sl-action=remove_tmpl", { template: _value }, function(_response) {

                aswal.close();

                toastIt(_response);

              }, 'json');

            })
          }
        });
      });


      ///////////bar chart
      var ctx = document.getElementById("asl_search_canvas").getContext("2d"),
        charts_option = {
          type: 'line',
          data: lineChartData,
          options: {
            bezierCurve: true,
            animation: true,
            responsive: true,
            maintainAspectRatio: false,
            title: {
              display: true,
              text: '#Searches'
            },
            scales: {
              y: {
                suggestedMin: 0,
                ticks: {
                  beginAtZero: true
                }
              }
            }
          }
        };
      var myBar = new Chart(ctx, charts_option);

      Chart.defaults.scales.linear.min = 0;

      /**
       * [updateChart Get the Stats Chart]
       * @return {[type]}   [description]
       */
      function updateChart(_chart_data) {

        var temp_keys = [],
            temp_vals = [];

        for (var k in _chart_data) {

          temp_keys.push(_chart_data[k]['label']);
          temp_vals.push(_chart_data[k]['data']);
        }
        
        myBar.config.data.labels           = temp_keys;
        myBar.config.data.datasets[0].data = temp_vals;
        myBar.update();
      };


      //
      //updateChart(m, y);

      /**
       * [getViews Get the Stores Views and Search]
       * @return {[type]}   [description]
       */
      function getViews(stores_views, search_views) {

        //  Clear old records
        jQuery('#asl-stores-views li').remove();
        jQuery('#asl-searches-views li').remove();


        ///////////////////////////////
        //  Iterate to fill the list //
        ///////////////////////////////

        var stores_views_html = '';
        if (stores_views && stores_views.length) {

          for (var s = 0; s < stores_views.length; s++) {

            var _store_view = stores_views[s];
            stores_views_html += `<li class="list-group-item">
                          <div class="row">
                            <div class="col-3"><div class="list-items asl-store-id">${_store_view.store_id}</div></div>
                            <div class="col-7"><div class="list-items">${_store_view.title} - ${_store_view.city}</div></div>
                            <div class="col-2"><div class="list-items">${_store_view.views}</div></div>
                          </div>
                        </li>`;
          }
        } 
        else {

          stores_views_html += `<li class="list-group-item">
                          <div class="row">
                            <div class="col-12 text-center">No Store Views!</div>
                          </div>
                        </li>`;
        }

        jQuery('#asl-stores-views').append(stores_views_html);


        ///////////////////////////////
        //  Iterate to fill the list //
        ///////////////////////////////
        var searches_views_html = '';
        if (search_views && search_views.length) {

          for (var s = 0; s < search_views.length; s++) {

            var _store_view = search_views[s];
            searches_views_html += `<li class="list-group-item">
                          <div class="row">
                            <div class="col-9"><div class="list-items">${_store_view.search_str}</div></div>
                            <div class="col-3"><div class="list-items">${_store_view.views}</div></div>
                          </div>
                        </li>`;
          }
        } 
        else {

          searches_views_html += `<li class="list-group-item">
                          <div class="row">
                            <div class="col-12 text-center">No Searches Result!</div>
                          </div>
                        </li>`;
        }
        

        jQuery('#asl-searches-views').append(searches_views_html);
      };


      //  Export the analytics
      $('#sl-btn-export-analytics').bind('click', function() {

        window.location.href = ASL_REMOTE.URL + "?action=asl_ajax_handler&sl-action=export_stats&" + get_duration_string();
      });


      //getViews(temp[0], temp[1]);
      
      //  datetime options
      var date_time_options = {
        "timePicker": false,
        "parentEl": '.asl-p-cont',
        "alwaysShowCalendars": false,
        "startDate": moment().subtract(6, 'days'),
        "endDate": moment().startOf('hour'),
         "ranges": {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      };
    
      /**
       * [refetch_data Refetch data of the stats]
       * @return {[type]} [description]
       */
      function refetch_data() {

        //  Length of data
        var rows_len = $('#asl-search-len').val();

        //  apply servercall
        ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=get_stats&" + get_duration_string(), {len: rows_len }, function(_response) {

          var stores_views = _response.stores;
          var search_views = _response.searches;
          var chart_data   = _response.chart_data;

          getViews(stores_views, search_views);

          updateChart(chart_data);
        });
      };


      //  Add datetimepicker
      $datepicker.daterangepicker(date_time_options, 
        function(start, end, label) {
          
          refetch_data();
          //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')')
      });

      //  first time
      refetch_data();

      //  For the Views
      $('#asl-search-view,#asl-search-len').bind('change', function(e) {
        refetch_data();
      });


      //  support status button
      $('#asl-support-status-btn').bind('click', function(e) {

          aswal({
        title: ASL_REMOTE.LANG.support_title,
        html: ASL_REMOTE.LANG.support_text,
        input: 'text',
        type: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "VALIDATE",
        preConfirm: function(_value) {

          return new Promise(function(resolve, reject) {

            if ($.trim(_value) == '') {

              aswal.showValidationError('Purchase Code is Missing!');
              return false;
            }

            aswal.showLoading();

            ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&sl-action=refresh_support_license", { value: _value }, function(_response) {

              aswal.hideLoading();

              if (!_response.success) {

                aswal.showValidationError(_response.message);
                reject();
                return false;
              } else {

                aswal({
                  type: (_response.success) ? 'success' : 'error',
                  title: (_response.success) ? 'Validate Successfully!' : 'Validation Failed!',
                  html: (_response.message) ? _response.message : ('Validation Failed, Please Contact Support')
                });

                reject();
                return true;
              }

            }, 'json');

          })
        }
        /*inputValidator: function(value) {
              return !value && 'You need to write something!'
          }*/
      })
      });
    }
  };


  asl_engine.pages.dashboard();

})(jQuery, asl_engine);