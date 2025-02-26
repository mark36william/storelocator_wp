var asl_engine = {};

(function($, app_engine) {
  'use strict';

  /* API method to get paging information */
  if($.fn.dataTableExt && $.fn.dataTableExt.oApi){
    
    $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings ){return {"iStart":         oSettings._iDisplayStart,"iEnd":           oSettings.fnDisplayEnd(),"iLength":        oSettings._iDisplayLength,"iTotal":         oSettings.fnRecordsTotal(),"iFilteredTotal": oSettings.fnRecordsDisplay(),"iPage":          oSettings._iDisplayLength === -1 ?0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),"iTotalPages":    oSettings._iDisplayLength === -1 ?0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )};};

    /* Bootstrap style pagination control */
    $.extend($.fn.dataTableExt.oPagination,{bootstrap:{fnInit:function(i,a,e){var t=i.oLanguage.oPaginate,l=function(a){a.preventDefault(),i.oApi._fnPageChange(i,a.data.action)&&e(i)};$(a).addClass("pagination").append('<ul class="pagination mt-3"><li class="page-item prev disabled"><a class="page-link" href="#">&larr; '+t.sPrevious+'</a></li><li class="page-item next disabled"><a class="page-link" href="#">'+t.sNext+" &rarr; </a></li></ul>");var s=$("a",a);$(s[0]).bind("click.DT",{action:"previous"},l),$(s[1]).bind("click.DT",{action:"next"},l)},fnUpdate:function(i,e){var a,t,l,s,n,o=i.oInstance.fnPagingInfo(),g=i.aanFeatures.p,r=Math.floor(2.5);n=o.iTotalPages<5?(s=1,o.iTotalPages):o.iPage<=r?(s=1,5):o.iPage>=o.iTotalPages-r?(s=o.iTotalPages-5+1,o.iTotalPages):(s=o.iPage-r+1)+5-1;var d=g.length;for(a=0;a<d;a++){for($("li:gt(0)",g[a]).filter(":not(:last)").remove(),t=s;t<=n;t++)l=t==o.iPage+1?"active":"",$('<li class="page-item '+l+'"><a class="page-link" href="#">'+t+"</a></li>").insertBefore($("li:last",g[a])[0]).bind("click",function(a){a.preventDefault(),i._iDisplayStart=(parseInt($("a",this).text(),10)-1)*o.iLength,e(i)});0===o.iPage?$("li:first",g[a]).addClass("disabled"):$("li:first",g[a]).removeClass("disabled"),o.iPage===o.iTotalPages-1||0===o.iTotalPages?$("li:last",g[a]).addClass("disabled"):$("li:last",g[a]).removeClass("disabled")}}}});
  }
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
     * [lead_manager description]
     * @return {[type]} [description]
     */
    lead_manager: function() {

      var table = null;


      var asInitVals = {};
      table = $('#tbl_leads').dataTable({
        "bProcessing": true,
        "sPaginationType": "bootstrap",
        "bFilter": false,
        "bServerSide": true,
        //"scrollX": true,
        /*"aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 1 ] }
        ],*/
        "bAutoWidth": true,
        "columnDefs": [
          { 'bSortable': false, "width": "75px", "targets": 0 },
          { "width": "75px", "targets": 1 },
          { "width": "150px", "targets": 2 },
          { "width": "150px", "targets": 3 },
          { "width": "150px", "targets": 4 },
          { "width": "150px", "targets": 5 },
          { "width": "150px", "targets": 6 },
          { "width": "150px", "targets": 7, 'bSortable': false,
            render: function (data, type, full, meta) {
              return moment(data).fromNow();
            }
          },
          { 'bSortable': false, 'aTargets': [0] }
        ],
        "iDisplayLength": 10,
        "sAjaxSource": ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=get_leads",
        "columns": [
          { "data": "check" },
          { "data": "id" },
          { "data": "name" },
          { "data": "phone" },
          { "data": "email" },
          { "data": "postal_code" },
          { "data": "title" },
          { "data": "created_on" }
        ],
        'fnServerData': function(sSource, aoData, fnCallback) {

          $.get(sSource, aoData, function(json) {

            fnCallback(json);

          }, 'json');

        },
        "fnServerParams": function(aoData) {

          //  Add the filters
          $("thead input").each(function(i) {
            if (this.value != "") {
              aoData.push({
                "name": 'filter[' + $(this).attr('data-id') + ']',
                "value": this.value
              });
            }
          });
        },
        "order": [
          [1, 'desc']
        ]
      });

      var date_time_options = {
        "timePicker": false,
        "parentEl": '.asl-p-cont',
        "alwaysShowCalendars": false,
         "ranges": {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')]//,
          //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      };

      var $datepicker = $('#sl-datetimepicker');

      //  Export the leads
      $('#sl-btn-export-leads').bind('click', function() {

        window.location.href = ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=export_leads&sl-duration=" + encodeURI($datepicker.val());
      });

      //  Export by dealers
      $('#sl-btn-export-dealers').bind('click', function() {

        window.location.href = ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=export_dealers&sl-duration=" + encodeURI($datepicker.val());
      });

      //  Get the dates
      //$('#sl-datetimepicker').data('daterangepicker').startDate.format('YYYY-MM-DD')

      
      //  Add datetimepicker
      $datepicker.daterangepicker(date_time_options, 
        function(start, end, label) {
          console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')')
      });

      //Select all button
      $('.table .select-all').bind('click', function(e) {

        $('.asl-p-cont .table input').attr('checked', 'checked');

      });

      //Delete Selected Categories:: bulk
      $('#btn-asl-delete-all').bind('click', function(e) {

        var $tmp_categories = $('.asl-p-cont .table input:checked');

        if ($tmp_categories.length == 0) {
          displayMessage('No Category selected', $(".dump-message"), 'alert alert-danger static', true);
          return;
        }

        var item_ids = [];
        $('.asl-p-cont .table input:checked').each(function(i) {
          item_ids.push($(this).attr('data-id'));
        });

        aswal({
          title: "Delete Lead",
          text: "Are you sure you want to delete selected lead?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#dc3545",
          confirmButtonText: "Delete it!"
        }).then(function() {

          ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=delete_lead", {item_ids: item_ids, multiple: true }, function(_response) {

            toastIt(_response);

            if (_response.success) {
              table.fnDraw();
              return;
            }


          }, 'json');
        });
      });



      //  View lead details in model
      $('#tbl_lead tbody').on('click', '.edit_attr', function(e) {

        var _value = $(this).data('value'),
          _id      = $(this).data('id'),
          _ordr    = $(this).data('ordr');

      });


      //  Show delete lead model
      $('#tbl_lead tbody').on('click', '.delete_attr', function(e) {

        var _category_id = $(this).attr("data-id");

        aswal({
          title: "Delete " + _params.title,
          text: "Are you sure you want to delete " + _params.title + " " + _category_id + " ?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#dc3545",
          confirmButtonText: "Delete it!",
        }).then(
          function() {

            ServerCall(ASL_REMOTE.URL + "?action=asl_ajax_handler&asl-nounce=" + ASL_REMOTE.nounce + "&sl-action=delete_lead", { title: _params.title, name: _params.name, category_id: _category_id }, function(_response) {

              toastIt(_response);

              if (_response.success) {
                table.fnDraw();
                return;
              }

            }, 'json');

          }
        );
      });


      //  Search
      $("thead input").keyup(function(e) {

        if (e.keyCode == 13) {
          table.fnDraw();
        }
      });
    }
  };


})(jQuery, asl_engine);